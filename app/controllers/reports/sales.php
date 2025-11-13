<?php
/**
 * Sales Reports Controller
 * View sales performance and analytics
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
$group_by = $_GET['group_by'] ?? 'month'; // day, week, month, year

// Sales summary stats
$sales_summary = fn_core_database_row(
    "SELECT
        COUNT(*) as total_sales,
        SUM(total_amount) as total_revenue,
        AVG(total_amount) as average_sale,
        SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as paid_revenue,
        SUM(CASE WHEN payment_status = 'pending' THEN balance_due ELSE 0 END) as outstanding
     FROM sales_invoices
     WHERE company_id = ? AND sale_date BETWEEN ? AND ?",
    [$company_id, $date_from, $date_to]
);

// Sales by status
$sales_by_status = fn_core_database_rows(
    "SELECT payment_status, COUNT(*) as count, SUM(total_amount) as total
     FROM sales_invoices
     WHERE company_id = ? AND sale_date BETWEEN ? AND ?
     GROUP BY payment_status",
    [$company_id, $date_from, $date_to]
);

// Sales by payment method
$sales_by_method = fn_core_database_rows(
    "SELECT payment_method, COUNT(*) as count, SUM(total_amount) as total
     FROM sales_invoices
     WHERE company_id = ? AND sale_date BETWEEN ? AND ?
     GROUP BY payment_method",
    [$company_id, $date_from, $date_to]
);

// Top selling vehicles
$top_vehicles = fn_core_database_rows(
    "SELECT v.make, v.model, v.year, COUNT(si.invoice_id) as sales_count, SUM(si.total_amount) as total_revenue
     FROM sales_invoices si
     JOIN vehicles v ON si.vehicle_id = v.vehicle_id
     WHERE si.company_id = ? AND si.sale_date BETWEEN ? AND ?
     GROUP BY v.make, v.model, v.year
     ORDER BY sales_count DESC
     LIMIT 10",
    [$company_id, $date_from, $date_to]
);

// Sales trend data (for chart)
$trend_query = "SELECT ";
if ($group_by === 'day') {
    $trend_query .= "DATE(sale_date) as period, ";
} elseif ($group_by === 'week') {
    $trend_query .= "YEARWEEK(sale_date) as period, ";
} elseif ($group_by === 'month') {
    $trend_query .= "DATE_FORMAT(sale_date, '%Y-%m') as period, ";
} else {
    $trend_query .= "YEAR(sale_date) as period, ";
}
$trend_query .= "COUNT(*) as sales_count, SUM(total_amount) as revenue
                 FROM sales_invoices
                 WHERE company_id = ? AND sale_date BETWEEN ? AND ?
                 GROUP BY period
                 ORDER BY period";

$sales_trend = fn_core_database_rows($trend_query, [$company_id, $date_from, $date_to]);

// Sales by salesperson (if user tracking is implemented)
$sales_by_user = fn_core_database_rows(
    "SELECT u.first_name, u.last_name, COUNT(si.invoice_id) as sales_count, SUM(si.total_amount) as total_revenue
     FROM sales_invoices si
     LEFT JOIN users u ON si.created_by = u.user_id
     WHERE si.company_id = ? AND si.sale_date BETWEEN ? AND ?
     GROUP BY u.user_id
     ORDER BY total_revenue DESC",
    [$company_id, $date_from, $date_to]
);

// Comparison with previous period
$days_diff = (strtotime($date_to) - strtotime($date_from)) / 86400;
$prev_date_from = date('Y-m-d', strtotime($date_from . " -{$days_diff} days"));
$prev_date_to = date('Y-m-d', strtotime($date_to . " -{$days_diff} days"));

$previous_period = fn_core_database_row(
    "SELECT COUNT(*) as total_sales, SUM(total_amount) as total_revenue
     FROM sales_invoices
     WHERE company_id = ? AND sale_date BETWEEN ? AND ?",
    [$company_id, $prev_date_from, $prev_date_to]
);

// Calculate percentage changes
$revenue_change = 0;
$sales_change = 0;

if ($previous_period['total_revenue'] > 0) {
    $revenue_change = (($sales_summary['total_revenue'] - $previous_period['total_revenue']) / $previous_period['total_revenue']) * 100;
}

if ($previous_period['total_sales'] > 0) {
    $sales_change = (($sales_summary['total_sales'] - $previous_period['total_sales']) / $previous_period['total_sales']) * 100;
}

// Page header data
$page_header = [
    'title' => 'Sales Reports',
    'subtitle' => 'Sales performance and analytics',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Reports' => '/reports',
        'Sales' => ''
    ]
];

// Load view
require BASE_PATH . 'views/reports/sales.php';
