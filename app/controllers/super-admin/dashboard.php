<?php
/**
 * Super Admin Dashboard Controller
 */

fn_require_login(10);

$user_data = fn_core_session_get_user_data($_SESSION['email']);

// Get all companies
$companies = fn_company_get_all(100, 0);
$total_companies = fn_company_count_all();

// Get system stats
$query = "SELECT COUNT(*) as total FROM users";
$total_users_result = fn_core_database_row($query, []);
$total_users = $total_users_result['total'];

$query = "SELECT COUNT(*) as total FROM vehicles";
$total_vehicles_result = fn_core_database_row($query, []);
$total_vehicles = $total_vehicles_result['total'];

$query = "SELECT COUNT(*) as total FROM crm_leads";
$total_leads_result = fn_core_database_row($query, []);
$total_leads = $total_leads_result['total'];

// Get companies by status
$query = "SELECT status, COUNT(*) as count FROM core_company GROUP BY status";
$companies_by_status = fn_core_database_rows($query, []);

$page_header = [
    'title' => 'Super Admin Dashboard',
    'subtitle' => 'Platform overview and management'
];

require BASE_PATH . 'views/super-admin/dashboard.php';
