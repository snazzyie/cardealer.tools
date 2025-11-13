<?php
/**
 * Manage Subscription
 */

fn_require_login(2);

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

$company_data = fn_company_get($company_id);
$subscription = fn_subscriptions_get_subscription($company_id);
$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel') {
    if ($subscription && $subscription['stripe_subscription_id']) {
        $cancelled = fn_subscriptions_cancel($company_id, $subscription['stripe_subscription_id']);

        if ($cancelled) {
            header("Location: /subscriptions/manage?success=cancelled");
            exit;
        } else {
            $error = "Failed to cancel subscription. Please contact support.";
        }
    }
}

// Get usage stats
$vehicle_count = fn_core_count_rows_company('vehicles', $company_id);
$user_count = fn_core_database_row("SELECT COUNT(*) as count FROM users WHERE company_id = ?", [$company_id])['count'];

// Get limits if subscribed
$limits = null;
if ($subscription) {
    $limits = fn_subscriptions_get_limits($subscription['plan_id']);
}

$page_header = [
    'title' => 'Manage Subscription',
    'subtitle' => 'View and manage your subscription details'
];

require BASE_PATH . 'views/subscriptions/manage.php';
