<?php
/**
 * Stock Alerts Cron Job
 * Check new vehicles against active stock alerts and send notifications
 *
 * Run: php /path/to/cardealer.tools/cron/stock-alerts.php
 * Schedule: Every hour or when new vehicles are added
 */

// Set working directory
define('BASE_PATH', dirname(__DIR__) . '/');

// Load configuration
$config = require BASE_PATH . 'config.php';

// Load required functions
require BASE_PATH . 'app/functions/fn_core_database.php';
require BASE_PATH . 'app/functions/fn_stock_alerts.php';
require BASE_PATH . 'app/functions/fn_vehicles.php';
require BASE_PATH . 'app/functions/fn_company.php';
require BASE_PATH . 'app/functions/fn_communications.php';

// Get all active companies
$companies = fn_core_database_rows("SELECT company_id FROM core_company WHERE status IN ('trial', 'active')");

$totalNotifications = 0;

foreach ($companies as $company) {
    $companyId = $company['company_id'];

    // Get recently added vehicles (last hour)
    $query = "SELECT vehicle_id FROM vehicles
              WHERE company_id = ?
              AND status = 'available'
              AND date_added >= DATE_SUB(NOW(), INTERVAL 1 HOUR)";

    $recentVehicles = fn_core_database_rows($query, [$companyId]);

    foreach ($recentVehicles as $vehicle) {
        $notified = fn_stock_alert_check_vehicle($vehicle['vehicle_id'], $companyId);
        $totalNotifications += $notified;

        if ($notified > 0) {
            echo "✓ Vehicle {$vehicle['vehicle_id']}: {$notified} alerts sent\n";
        }
    }
}

echo "\n=== Stock Alerts Cron Complete ===\n";
echo "Total notifications sent: {$totalNotifications}\n";
echo "Completed at: " . date('Y-m-d H:i:s') . "\n";
