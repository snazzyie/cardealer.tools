<?php
/**
 * Public About Page Controller
 */

// Get company from constant (set by public routing)
if (!defined('PUBLIC_SITE_COMPANY')) {
    http_response_code(500);
    echo '<h1>500 - Server Error</h1>';
    echo '<p>Company context not found.</p>';
    exit;
}

$company_data = PUBLIC_SITE_COMPANY;
$company_id = PUBLIC_SITE_COMPANY_ID;

// Get page content from database (if custom pages feature exists)
$page_content = fn_core_database_row(
    "SELECT * FROM website_pages WHERE company_id = ? AND page_slug = 'about' AND is_published = 1",
    [$company_id]
);

// Get team members
$team_members = fn_core_database_rows(
    "SELECT user_id, first_name, last_name, user_role, phone, email
     FROM users
     WHERE company_id = ? AND status = 'active' AND user_type >= 1
     ORDER BY user_type DESC, first_name ASC",
    [$company_id]
);

// Get company stats
$stats = [
    'vehicles_in_stock' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM vehicles WHERE company_id = ? AND status = 'available'",
        [$company_id]
    )['total'] ?? 0,
    'years_in_business' => $company_data['established_year'] ? (date('Y') - $company_data['established_year']) : null,
    'happy_customers' => fn_core_count_rows_company('customers', $company_id)
];

// Page header data
$page_header = [
    'title' => 'About Us',
    'subtitle' => $company_data['company_name']
];

// Load view
require BASE_PATH . 'views/public/about/index.php';
