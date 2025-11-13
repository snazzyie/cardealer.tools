<?php
/**
 * Deposits List Controller
 * Manage customer deposits
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get filters
$filters = [];
if (!empty($_GET['status'])) {
    $filters['status'] = $_GET['status'];
}
if (!empty($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}

// Build query
$query = "SELECT d.*, v.make, v.model, v.year, v.registration,
          c.first_name as customer_first_name, c.last_name as customer_last_name,
          c.email as customer_email, c.phone as customer_phone
          FROM deposits d
          LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
          LEFT JOIN customers c ON d.customer_id = c.customer_id
          WHERE d.company_id = ?";
$params = [$company_id];

if (!empty($filters['status'])) {
    $query .= " AND d.status = ?";
    $params[] = $filters['status'];
}

if (!empty($filters['search'])) {
    $query .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR c.email LIKE ? OR d.deposit_reference LIKE ?)";
    $searchTerm = '%' . $filters['search'] . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$query .= " ORDER BY d.created_date DESC";

$deposits = fn_core_database_rows($query, $params);

// Get stats
$stats = [
    'total_deposits' => fn_core_count_rows_company('deposits', $company_id),
    'active_deposits' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM deposits WHERE company_id = ? AND status = 'active'",
        [$company_id]
    )['total'] ?? 0,
    'total_value' => fn_core_database_row(
        "SELECT SUM(amount) as total FROM deposits WHERE company_id = ? AND status = 'active'",
        [$company_id]
    )['total'] ?? 0,
    'refunded' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM deposits WHERE company_id = ? AND status = 'refunded'",
        [$company_id]
    )['total'] ?? 0,
    'completed' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM deposits WHERE company_id = ? AND status = 'completed'",
        [$company_id]
    )['total'] ?? 0
];

// Page header data
$page_header = [
    'title' => 'Deposits',
    'subtitle' => 'Manage customer deposits',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Deposits' => ''
    ]
];

// Load view
require BASE_PATH . 'views/deposits/deposits.php';
