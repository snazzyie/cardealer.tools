<?php
/**
 * Service Invoices List
 * View all service invoices and manage jobs
 */

// Security check
fn_check_security($user_id, $company_id);

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_invoice_id'])) {
    $invoiceId = intval($_POST['delete_invoice_id']);
    $success = fn_service_invoice_delete($invoiceId, $company_id);

    if ($success) {
        header("Location: /service/invoices?deleted=1");
        exit;
    } else {
        $error = "Failed to delete invoice.";
    }
}

// Handle PDF generation
if (isset($_GET['generate_pdf']) && !empty($_GET['generate_pdf'])) {
    $invoiceId = intval($_GET['generate_pdf']);
    $pdfPath = fn_pdf_generate_invoice($invoiceId, 'service', $company_id);

    if ($pdfPath) {
        header("Location: /service/invoice/{$invoiceId}?pdf_generated=1");
        exit;
    } else {
        $error = "Failed to generate PDF.";
    }
}

// Get filters
$filters = [];
if (!empty($_GET['status'])) {
    $filters['status'] = $_GET['status'];
}
if (!empty($_GET['payment_status'])) {
    $filters['payment_status'] = $_GET['payment_status'];
}

// Get invoices
$invoices = fn_service_invoices_get_all($company_id, $filters);

// Get stats
$stats = fn_service_invoice_stats($company_id);

// Page header data
$page_header = [
    'title' => 'Service Invoices',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Service Invoices' => ''
    ]
];

// Load view
require BASE_PATH . 'views/service/service-invoices.php';
