<?php
/**
 * Public Stock Alert Registration
 * Allow customers to subscribe to vehicle stock alerts
 */

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'email' => trim($_POST['email'] ?? ''),
        'make' => trim($_POST['make'] ?? ''),
        'model' => trim($_POST['model'] ?? ''),
        'min_price' => trim($_POST['min_price'] ?? ''),
        'max_price' => trim($_POST['max_price'] ?? ''),
        'min_year' => trim($_POST['min_year'] ?? ''),
        'max_year' => trim($_POST['max_year'] ?? ''),
        'fuel_type' => trim($_POST['fuel_type'] ?? ''),
        'body_type' => trim($_POST['body_type'] ?? '')
    ];

    if (empty($data['email'])) {
        $error = "Email address is required.";
    } else {
        $alertId = fn_stock_alert_create($company_id, $data);

        if ($alertId) {
            $success = true;
        } else {
            $error = "Failed to create stock alert. Please check your email address.";
        }
    }
}

// Get available makes for dropdown
$makes = fn_vehicles_get_unique_makes($company_id);

// Page header
$page_header = ['title' => 'Stock Alert Registration'];

// Load view
require BASE_PATH . 'views/public/stock-alert-register.php';
