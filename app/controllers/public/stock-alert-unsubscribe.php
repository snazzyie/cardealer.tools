<?php
/**
 * Public Stock Alert Unsubscribe
 */

$token = $_GET['token'] ?? '';
$success = false;
$error = '';

if (empty($token)) {
    $error = "Invalid unsubscribe link.";
} else {
    $alert = fn_stock_alert_get_by_token($token);

    if (!$alert) {
        $error = "Stock alert not found.";
    } elseif ($alert['is_active'] == 0) {
        $success = true;
        $already_unsubscribed = true;
    } else {
        $success = fn_stock_alert_unsubscribe($token);
        if (!$success) {
            $error = "Failed to unsubscribe. Please try again.";
        }
    }
}

// Page header
$page_header = ['title' => 'Unsubscribe from Stock Alerts'];

// Load view
require BASE_PATH . 'views/public/stock-alert-unsubscribe.php';
