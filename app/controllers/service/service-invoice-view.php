<?php
/**
 * Service Invoice View
 * View single service invoice details
 */

// Security check
fn_check_security($user_id, $company_id);

// Get invoice ID from query parameter
$invoiceId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (empty($invoiceId)) {
    header("Location: /service/invoices?error=no_id");
    exit;
}

// Get invoice data
$invoice = fn_service_invoice_get($invoiceId, $company_id);

if (!$invoice) {
    header("Location: /service/invoices?error=not_found");
    exit;
}

// Handle payment update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_payment'])) {
    $paymentStatus = $_POST['payment_status'] ?? 'unpaid';
    $paidAmount = floatval($_POST['paid_amount'] ?? 0);
    $paymentMethod = trim($_POST['payment_method'] ?? '');

    $success = fn_service_invoice_update_payment($invoiceId, $company_id, $paymentStatus, $paidAmount, $paymentMethod);

    if ($success) {
        header("Location: /service/invoice/view?id={$invoiceId}&payment_updated=1");
        exit;
    } else {
        $error = "Failed to update payment status.";
    }
}

// Get company data for PDF display
$company_data = fn_company_get_by_id($company_id);

// Page header data
$page_header = [
    'title' => 'Service Invoice ' . $invoice['invoice_number'],
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Service Invoices' => '/service/invoices',
        $invoice['invoice_number'] => ''
    ]
];

// Load view
require BASE_PATH . 'views/service/service-invoice-view.php';
