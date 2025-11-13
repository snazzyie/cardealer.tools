<?php
/**
 * Sales Invoices List Controller
 * View all sales invoices
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_invoice_id'])) {
    $invoiceId = intval($_POST['delete_invoice_id']);
    $success = fn_core_database_query(
        "DELETE FROM sales_invoices WHERE invoice_id = ? AND company_id = ?",
        [$invoiceId, $company_id]
    );

    if ($success) {
        header("Location: /invoices/sales?deleted=1");
        exit;
    } else {
        $error = "Failed to delete invoice.";
    }
}

// Handle PDF generation
if (isset($_GET['generate_pdf']) && !empty($_GET['generate_pdf'])) {
    $invoiceId = intval($_GET['generate_pdf']);
    $pdfPath = fn_pdf_generate_invoice($invoiceId, 'sales', $company_id);

    if ($pdfPath) {
        header("Location: /invoices/sales/view?id={$invoiceId}&pdf_generated=1");
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
if (!empty($_GET['date_from'])) {
    $filters['date_from'] = $_GET['date_from'];
}
if (!empty($_GET['date_to'])) {
    $filters['date_to'] = $_GET['date_to'];
}

// Build query
$query = "SELECT si.*, v.make, v.model, v.year, v.registration,
          c.first_name as customer_first_name, c.last_name as customer_last_name
          FROM sales_invoices si
          LEFT JOIN vehicles v ON si.vehicle_id = v.vehicle_id
          LEFT JOIN customers c ON si.customer_id = c.customer_id
          WHERE si.company_id = ?";
$params = [$company_id];

if (!empty($filters['status'])) {
    $query .= " AND si.status = ?";
    $params[] = $filters['status'];
}

if (!empty($filters['payment_status'])) {
    $query .= " AND si.payment_status = ?";
    $params[] = $filters['payment_status'];
}

if (!empty($filters['date_from'])) {
    $query .= " AND si.sale_date >= ?";
    $params[] = $filters['date_from'];
}

if (!empty($filters['date_to'])) {
    $query .= " AND si.sale_date <= ?";
    $params[] = $filters['date_to'];
}

$query .= " ORDER BY si.sale_date DESC, si.created_date DESC";

$invoices = fn_core_database_rows($query, $params);

// Get stats
$stats = [
    'total_invoices' => fn_core_count_rows_company('sales_invoices', $company_id),
    'total_value' => fn_core_database_row(
        "SELECT SUM(total_amount) as total FROM sales_invoices WHERE company_id = ?",
        [$company_id]
    )['total'] ?? 0,
    'paid' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM sales_invoices WHERE company_id = ? AND payment_status = 'paid'",
        [$company_id]
    )['total'] ?? 0,
    'pending' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM sales_invoices WHERE company_id = ? AND payment_status = 'pending'",
        [$company_id]
    )['total'] ?? 0,
    'this_month' => fn_core_database_row(
        "SELECT SUM(total_amount) as total FROM sales_invoices
         WHERE company_id = ? AND sale_date >= DATE_FORMAT(NOW(), '%Y-%m-01')",
        [$company_id]
    )['total'] ?? 0
];

// Page header data
$page_header = [
    'title' => 'Sales Invoices',
    'subtitle' => 'Manage vehicle sales',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Invoices' => '/invoices',
        'Sales' => ''
    ]
];

// Load view
require BASE_PATH . 'views/invoices/sales-invoices.php';
