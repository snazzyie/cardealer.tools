<?php
/**
 * Subscription Check Cron Job
 * Check for expired trials and suspended subscriptions
 *
 * Run: php /path/to/cardealer.tools/cron/subscription-check.php
 * Schedule: Daily at midnight
 */

// Set working directory
define('BASE_PATH', dirname(__DIR__) . '/');

// Load configuration
$config = require BASE_PATH . 'config.php';

// Load required functions
require BASE_PATH . 'app/functions/fn_core_database.php';
require BASE_PATH . 'app/functions/fn_company.php';
require BASE_PATH . 'app/functions/fn_communications.php';

echo "=== Subscription Check Cron Started ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

// Check for expired trials
$query = "SELECT company_id, company_name, company_email, trial_end
          FROM core_company
          WHERE status = 'trial'
          AND trial_end < NOW()";

$expiredTrials = fn_core_database_rows($query);

foreach ($expiredTrials as $company) {
    // Update status to suspended
    $updateQuery = "UPDATE core_company SET status = 'suspended' WHERE company_id = ?";
    fn_core_edit_row_no_redirect($updateQuery, [$company['company_id']]);

    // Send notification email
    $subject = "Your Trial Has Expired - {$company['company_name']}";
    $htmlBody = "<h2>Trial Expired</h2><p>Your trial has ended. Please upgrade to continue using the platform.</p>";
    $textBody = "Your trial has expired. Please upgrade to continue.";

    fn_email_send_postmark($company['company_email'], $subject, $htmlBody, $textBody, $company['company_id']);

    echo "✓ Suspended: {$company['company_name']} (trial ended " . $company['trial_end'] . ")\n";
}

// Check for trials expiring soon (3 days)
$query = "SELECT company_id, company_name, company_email, trial_end
          FROM core_company
          WHERE status = 'trial'
          AND trial_end BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 3 DAY)
          AND (last_trial_reminder IS NULL OR last_trial_reminder < DATE_SUB(NOW(), INTERVAL 1 DAY))";

$expiringSoon = fn_core_database_rows($query);

foreach ($expiringSoon as $company) {
    // Send reminder email
    $daysLeft = ceil((strtotime($company['trial_end']) - time()) / 86400);
    $subject = "Your Trial Ends in {$daysLeft} Days - {$company['company_name']}";
    $htmlBody = "<h2>Trial Ending Soon</h2><p>Your trial ends in {$daysLeft} days. Upgrade now to avoid interruption.</p>";
    $textBody = "Your trial ends in {$daysLeft} days. Upgrade now!";

    fn_email_send_postmark($company['company_email'], $subject, $htmlBody, $textBody, $company['company_id']);

    // Update last reminder timestamp
    $updateQuery = "UPDATE core_company SET last_trial_reminder = NOW() WHERE company_id = ?";
    fn_core_edit_row_no_redirect($updateQuery, [$company['company_id']]);

    echo "✓ Reminder sent: {$company['company_name']} ({$daysLeft} days left)\n";
}

echo "\n=== Subscription Check Complete ===\n";
echo "Expired trials: " . count($expiredTrials) . "\n";
echo "Reminders sent: " . count($expiringSoon) . "\n";
