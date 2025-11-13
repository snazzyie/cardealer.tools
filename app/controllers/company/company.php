<?php
/**
 * Company Profile View Controller
 * Display company information (read-only view)
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get company data
$company_data = fn_company_get($company_id);

if (!$company_data) {
    header("Location: /company/edit");
    exit;
}

// Get company statistics
$stats = [
    'total_users' => fn_core_count_rows_company('users', $company_id),
    'total_vehicles' => fn_core_count_rows_company('vehicles', $company_id),
    'total_leads' => fn_core_count_rows_company('crm_leads', $company_id),
    'total_customers' => fn_core_count_rows_company('customers', $company_id),
    'active_enquiries' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM enquiries WHERE company_id = ? AND status = 'new'",
        [$company_id]
    )['total'] ?? 0
];

// Get subscription info
$subscription = fn_core_database_row(
    "SELECT s.*, p.plan_name, p.price, p.billing_period
     FROM subscriptions s
     LEFT JOIN subscription_plans p ON s.plan_id = p.plan_id
     WHERE s.company_id = ?
     ORDER BY s.created_date DESC
     LIMIT 1",
    [$company_id]
);

// Calculate storage usage
$storage_used = fn_core_database_row(
    "SELECT
        COALESCE(SUM(LENGTH(image_data)), 0) as total_size
     FROM vehicle_images
     WHERE company_id = ?",
    [$company_id]
)['total_size'] ?? 0;

$storage_stats = [
    'used_mb' => round($storage_used / 1024 / 1024, 2),
    'limit_mb' => 500, // Default limit
    'percentage' => round(($storage_used / (500 * 1024 * 1024)) * 100, 1)
];

// Get recent activity
$recent_activity = fn_core_database_rows(
    "SELECT 'vehicle' as type, CONCAT('Added vehicle: ', make, ' ', model) as description, created_date
     FROM vehicles
     WHERE company_id = ?
     UNION ALL
     SELECT 'lead' as type, CONCAT('New lead: ', first_name, ' ', last_name) as description, created_date
     FROM crm_leads
     WHERE company_id = ?
     UNION ALL
     SELECT 'enquiry' as type, CONCAT('New enquiry from ', first_name, ' ', last_name) as description, created_date
     FROM enquiries
     WHERE company_id = ?
     ORDER BY created_date DESC
     LIMIT 10",
    [$company_id, $company_id, $company_id]
);

// Page header data
$page_header = [
    'title' => 'Company Profile',
    'subtitle' => $company_data['company_name'],
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Company Profile' => ''
    ]
];

// Load view
require BASE_PATH . 'views/company/company.php';
