<?php
/**
 * Admin Stock Alerts Management
 */

// Security check
fn_check_security($user_id, $company_id);

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_alert_id'])) {
    $alertId = intval($_POST['delete_alert_id']);
    $success = fn_stock_alert_delete($alertId, $company_id);

    if ($success) {
        header("Location: /stock-alerts?deleted=1");
        exit;
    }
}

// Get all alerts
$alerts = fn_stock_alerts_get_all($company_id, false);

// Get stats
$stats = fn_stock_alert_stats($company_id);

// Page header
$page_header = [
    'title' => 'Stock Alerts',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Stock Alerts' => ''
    ]
];

// Load view
require BASE_PATH . 'views/stock-alerts/stock-alerts.php';
