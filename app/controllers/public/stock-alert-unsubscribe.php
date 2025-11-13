<?php
/**
 * Public Stock Alert Unsubscribe
 */

// Get company from constant (set by public routing)
if (!defined('PUBLIC_SITE_COMPANY')) {
    http_response_code(500);
    echo '<h1>500 - Server Error</h1>';
    echo '<p>Company context not found.</p>';
    exit;
}

$company_data = PUBLIC_SITE_COMPANY;
$company_id = PUBLIC_SITE_COMPANY_ID;

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
