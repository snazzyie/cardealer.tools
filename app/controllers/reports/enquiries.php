<?php
/**
 * Enquiries Reports Controller
 * View enquiry performance and conversion analytics
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get date range from filters
$date_from = $_GET['date_from'] ?? date('Y-m-01'); // First day of current month
$date_to = $_GET['date_to'] ?? date('Y-m-d'); // Today

// Enquiry summary stats
$enquiry_summary = fn_core_database_row(
    "SELECT
        COUNT(*) as total_enquiries,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_enquiries,
        SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted,
        SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) as converted,
        SUM(CASE WHEN status = 'not_interested' THEN 1 ELSE 0 END) as not_interested
     FROM enquiries
     WHERE company_id = ? AND created_date BETWEEN ? AND ?",
    [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
);

// Calculate conversion rate
$conversion_rate = 0;
if ($enquiry_summary['total_enquiries'] > 0) {
    $conversion_rate = ($enquiry_summary['converted'] / $enquiry_summary['total_enquiries']) * 100;
}

// Enquiries by source
$enquiries_by_source = fn_core_database_rows(
    "SELECT source, COUNT(*) as count,
     SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) as converted
     FROM enquiries
     WHERE company_id = ? AND created_date BETWEEN ? AND ?
     GROUP BY source
     ORDER BY count DESC",
    [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
);

// Enquiries by type
$enquiries_by_type = fn_core_database_rows(
    "SELECT enquiry_type, COUNT(*) as count,
     SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) as converted
     FROM enquiries
     WHERE company_id = ? AND created_date BETWEEN ? AND ?
     GROUP BY enquiry_type
     ORDER BY count DESC",
    [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
);

// Most enquired vehicles
$top_enquired_vehicles = fn_core_database_rows(
    "SELECT v.make, v.model, v.year, v.price, COUNT(e.enquiry_id) as enquiry_count,
     SUM(CASE WHEN e.status = 'converted' THEN 1 ELSE 0 END) as conversions
     FROM enquiries e
     JOIN vehicles v ON e.vehicle_id = v.vehicle_id
     WHERE e.company_id = ? AND e.created_date BETWEEN ? AND ?
     GROUP BY v.vehicle_id
     ORDER BY enquiry_count DESC
     LIMIT 10",
    [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
);

// Enquiry trend data (daily)
$enquiry_trend = fn_core_database_rows(
    "SELECT DATE(created_date) as date,
     COUNT(*) as total,
     SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) as converted
     FROM enquiries
     WHERE company_id = ? AND created_date BETWEEN ? AND ?
     GROUP BY DATE(created_date)
     ORDER BY date",
    [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
);

// Response time analysis
$response_times = fn_core_database_rows(
    "SELECT
        enquiry_id,
        created_date,
        last_contact,
        TIMESTAMPDIFF(HOUR, created_date, last_contact) as response_hours
     FROM enquiries
     WHERE company_id = ? AND created_date BETWEEN ? AND ? AND last_contact IS NOT NULL",
    [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
);

// Calculate average response time
$avg_response_time = 0;
if (!empty($response_times)) {
    $total_hours = array_sum(array_column($response_times, 'response_hours'));
    $avg_response_time = $total_hours / count($response_times);
}

// Comparison with previous period
$days_diff = (strtotime($date_to) - strtotime($date_from)) / 86400;
$prev_date_from = date('Y-m-d', strtotime($date_from . " -{$days_diff} days"));
$prev_date_to = date('Y-m-d', strtotime($date_to . " -{$days_diff} days"));

$previous_period = fn_core_database_row(
    "SELECT COUNT(*) as total_enquiries,
     SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) as converted
     FROM enquiries
     WHERE company_id = ? AND created_date BETWEEN ? AND ?",
    [$company_id, $prev_date_from . ' 00:00:00', $prev_date_to . ' 23:59:59']
);

// Calculate percentage changes
$enquiry_change = 0;
$conversion_change = 0;

if ($previous_period['total_enquiries'] > 0) {
    $enquiry_change = (($enquiry_summary['total_enquiries'] - $previous_period['total_enquiries']) / $previous_period['total_enquiries']) * 100;
}

$prev_conversion_rate = 0;
if ($previous_period['total_enquiries'] > 0) {
    $prev_conversion_rate = ($previous_period['converted'] / $previous_period['total_enquiries']) * 100;
    $conversion_change = $conversion_rate - $prev_conversion_rate;
}

// Page header data
$page_header = [
    'title' => 'Enquiry Reports',
    'subtitle' => 'Enquiry performance and conversion analytics',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Reports' => '/reports',
        'Enquiries' => ''
    ]
];

// Load view
require BASE_PATH . 'views/reports/enquiries.php';
