<?php
/**
 * Service Items List
 * Manage service catalog (labor services and parts)
 */

// Security check
fn_check_security($user_id, $company_id);

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item_id'])) {
    $itemId = intval($_POST['delete_item_id']);
    $success = fn_service_item_delete($itemId, $company_id);

    if ($success) {
        header("Location: /service/items?deleted=1");
        exit;
    } else {
        $error = "Failed to delete item.";
    }
}

// Get filter
$itemType = $_GET['type'] ?? 'all';

// Get service items
$service_items = fn_service_items_get_all($company_id, $itemType);

// Get low stock items
$low_stock_items = fn_service_items_low_stock($company_id);

// Page header data
$page_header = [
    'title' => 'Service Items',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Service Items' => ''
    ]
];

// Load view
require BASE_PATH . 'views/service/service-items.php';
