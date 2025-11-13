<?php
/**
 * Service Item Form
 * Add or edit service items (labor/parts)
 */

// Security check
fn_check_security($user_id, $company_id);

// Get item ID if editing
$itemId = isset($_GET['id']) ? intval($_GET['id']) : null;
$isEdit = !empty($itemId);

// Get existing item data if editing
$item = null;
if ($isEdit) {
    $item = fn_service_item_get($itemId, $company_id);
    if (!$item) {
        header("Location: /service/items?error=not_found");
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'item_type' => $_POST['item_type'] ?? 'part',
        'item_code' => trim($_POST['item_code'] ?? ''),
        'item_name' => trim($_POST['item_name'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'unit_price' => floatval($_POST['unit_price'] ?? 0),
        'cost_price' => floatval($_POST['cost_price'] ?? 0),
        'estimated_time_minutes' => intval($_POST['estimated_time_minutes'] ?? 0),
        'quantity_in_stock' => intval($_POST['quantity_in_stock'] ?? 0),
        'reorder_level' => intval($_POST['reorder_level'] ?? 0),
        'supplier' => trim($_POST['supplier'] ?? ''),
        'is_active' => isset($_POST['is_active']) ? 1 : 1
    ];

    // Validate
    if (empty($data['item_name']) || $data['unit_price'] <= 0) {
        $error = "Please fill in all required fields.";
    } else {
        if ($isEdit) {
            $success = fn_service_item_update($itemId, $company_id, $data);
            $redirectParam = $success ? 'updated=1' : 'error=update_failed';
        } else {
            $newItemId = fn_service_item_create($company_id, $data);
            $success = $newItemId !== false;
            $redirectParam = $success ? 'created=1' : 'error=create_failed';
        }

        if ($success) {
            header("Location: /service/items?{$redirectParam}");
            exit;
        } else {
            $error = "Failed to save item.";
        }
    }
}

// Page header data
$page_header = [
    'title' => $isEdit ? 'Edit Service Item' : 'Add Service Item',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Service Items' => '/service/items',
        $isEdit ? 'Edit Item' : 'Add Item' => ''
    ]
];

// Load view
require BASE_PATH . 'views/service/service-item-form.php';
