<?php
/**
 * Dashboard Controller
 */

// Require login
fn_require_login();

// Get user data
$email = $_SESSION['email'];
$user_data = fn_core_session_get_user_data($email);
$permission = $_SESSION['user_type'];
$company_id = $_SESSION['company_id'] ?? null;

// Page header data
$page_header = [
    'title' => 'Dashboard',
    'subtitle' => 'Welcome back, ' . $user_data['first_name'] . '!'
];

// Get dashboard stats (if company exists)
$stats = [
    'total_vehicles' => 0,
    'active_leads' => 0,
    'pending_enquiries' => 0,
    'this_month_sales' => 0
];

if ($company_id) {
    $stats['total_vehicles'] = fn_core_count_rows_company('vehicles', $company_id);
    $stats['active_leads'] = fn_core_database_row(
        "SELECT COUNT(*) as total FROM crm_leads WHERE company_id = :company_id AND status NOT IN ('won', 'lost')",
        ['company_id' => $company_id]
    )['total'] ?? 0;
    $stats['pending_enquiries'] = fn_core_database_row(
        "SELECT COUNT(*) as total FROM enquiries WHERE company_id = :company_id AND status = 'new'",
        ['company_id' => $company_id]
    )['total'] ?? 0;
}

// Load dashboard view
require BASE_PATH . 'views/dash/index.php';
