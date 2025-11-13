<?php
/**
 * Customers List Controller
 * Display all customers with search and filtering
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get search/filter parameters
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? 'all';

// Build query
$query = "SELECT DISTINCT
    c.customer_id,
    c.first_name,
    c.last_name,
    c.email,
    c.phone,
    c.address,
    c.city,
    c.created_date,
    COUNT(DISTINCT si.invoice_id) as purchase_count,
    SUM(si.total_amount) as total_spent
FROM customers c
LEFT JOIN sales_invoices si ON c.customer_id = si.customer_id
WHERE c.company_id = ?";

$params = [$company_id];

// Apply search
if (!empty($search)) {
    $query .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$query .= " GROUP BY c.customer_id";

// Apply filter
if ($filter === 'with_purchases') {
    $query .= " HAVING purchase_count > 0";
} elseif ($filter === 'no_purchases') {
    $query .= " HAVING purchase_count = 0";
}

$query .= " ORDER BY c.created_date DESC";

$customers = fn_core_database_rows($query, $params);

// Get stats
$stats = [
    'total' => fn_core_count_rows_company('customers', $company_id),
    'with_purchases' => fn_core_database_row(
        "SELECT COUNT(DISTINCT customer_id) as total FROM sales_invoices WHERE company_id = ?",
        [$company_id]
    )['total'] ?? 0,
    'this_month' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM customers WHERE company_id = ? AND created_date >= DATE_FORMAT(NOW(), '%Y-%m-01')",
        [$company_id]
    )['total'] ?? 0
];

// Page header data
$page_header = [
    'title' => 'Customers',
    'subtitle' => 'Manage your customer database',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Customers' => ''
    ]
];

// Load view
require BASE_PATH . 'views/customers/customers.php';
