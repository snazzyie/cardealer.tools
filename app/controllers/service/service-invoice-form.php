<?php
/**
 * Service Invoice Form
 * Create or edit service invoices
 */

// Security check
fn_check_security($user_id, $company_id);

// Get invoice ID if editing
$invoiceId = isset($_GET['id']) ? intval($_GET['id']) : null;
$isEdit = !empty($invoiceId);

// Get existing invoice data if editing
$invoice = null;
if ($isEdit) {
    $invoice = fn_service_invoice_get($invoiceId, $company_id);
    if (!$invoice) {
        header("Location: /service/invoices?error=not_found");
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse line items from POST data
    $lineItems = [];
    if (!empty($_POST['item_id'])) {
        foreach ($_POST['item_id'] as $index => $itemId) {
            if (!empty($itemId) || !empty($_POST['item_name'][$index])) {
                $lineItems[] = [
                    'item_id' => !empty($itemId) ? intval($itemId) : null,
                    'item_name' => trim($_POST['item_name'][$index] ?? ''),
                    'item_type' => $_POST['item_type'][$index] ?? 'part',
                    'description' => trim($_POST['item_description'][$index] ?? ''),
                    'quantity' => floatval($_POST['quantity'][$index] ?? 1),
                    'unit_price' => floatval($_POST['unit_price'][$index] ?? 0)
                ];
            }
        }
    }

    $data = [
        'customer_id' => intval($_POST['customer_id'] ?? 0),
        'customer_name' => trim($_POST['customer_name'] ?? ''),
        'customer_email' => trim($_POST['customer_email'] ?? ''),
        'customer_phone' => trim($_POST['customer_phone'] ?? ''),
        'vehicle_registration' => trim($_POST['vehicle_registration'] ?? ''),
        'invoice_date' => $_POST['invoice_date'] ?? date('Y-m-d'),
        'due_date' => $_POST['due_date'] ?? null,
        'service_type' => trim($_POST['service_type'] ?? 'General Service'),
        'service_date' => $_POST['service_date'] ?? date('Y-m-d'),
        'mileage' => !empty($_POST['mileage']) ? intval($_POST['mileage']) : null,
        'technician_id' => !empty($_POST['technician_id']) ? intval($_POST['technician_id']) : null,
        'line_items' => $lineItems,
        'vat_rate' => floatval($_POST['vat_rate'] ?? 23.00),
        'work_performed' => trim($_POST['work_performed'] ?? ''),
        'notes' => trim($_POST['notes'] ?? ''),
        'status' => $_POST['status'] ?? 'draft',
        'created_by' => $user_id
    ];

    // Validate
    if (empty($data['customer_id']) || empty($data['customer_name']) || empty($lineItems)) {
        $error = "Please fill in all required fields and add at least one line item.";
    } else {
        if ($isEdit) {
            $success = fn_service_invoice_update($invoiceId, $company_id, $data);
            $redirectId = $invoiceId;
            $redirectParam = $success ? 'updated=1' : 'error=update_failed';
        } else {
            $newInvoiceId = fn_service_invoice_create($company_id, $data);
            $success = $newInvoiceId !== false;
            $redirectId = $newInvoiceId;
            $redirectParam = $success ? 'created=1' : 'error=create_failed';
        }

        if ($success) {
            header("Location: /service/invoice/view?id={$redirectId}&{$redirectParam}");
            exit;
        } else {
            $error = "Failed to save invoice.";
        }
    }
}

// Get customers for dropdown
$customers = fn_core_database_rows("SELECT customer_id, first_name, last_name, email, phone
                                      FROM customers WHERE company_id = ? ORDER BY first_name, last_name", [$company_id]);

// Get technicians (staff users)
$technicians = fn_core_database_rows("SELECT user_id, first_name, last_name
                                        FROM users WHERE company_id = ? AND permission_level >= 2 ORDER BY first_name, last_name", [$company_id]);

// Get service items for quick add
$service_items = fn_service_items_get_all($company_id, 'all');

// Page header data
$page_header = [
    'title' => $isEdit ? 'Edit Service Invoice' : 'Create Service Invoice',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Service Invoices' => '/service/invoices',
        $isEdit ? 'Edit Invoice' : 'Create Invoice' => ''
    ]
];

// Load view
require BASE_PATH . 'views/service/service-invoice-form.php';
