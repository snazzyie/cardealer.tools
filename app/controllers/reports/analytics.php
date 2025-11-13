<?php
/**
 * Analytics Dashboard Controller
 * Comprehensive business analytics and insights
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

// === KEY PERFORMANCE INDICATORS ===

// Sales KPIs
$sales_kpis = fn_core_database_row(
    "SELECT
        COUNT(*) as total_sales,
        SUM(total_amount) as total_revenue,
        AVG(total_amount) as average_sale_value,
        SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_sales
     FROM sales_invoices
     WHERE company_id = ? AND sale_date BETWEEN ? AND ?",
    [$company_id, $date_from, $date_to]
);

// Lead KPIs
$lead_kpis = fn_core_database_row(
    "SELECT
        COUNT(*) as total_leads,
        SUM(CASE WHEN status = 'won' THEN 1 ELSE 0 END) as won_leads,
        SUM(CASE WHEN status = 'lost' THEN 1 ELSE 0 END) as lost_leads,
        SUM(CASE WHEN status NOT IN ('won', 'lost') THEN 1 ELSE 0 END) as active_leads
     FROM crm_leads
     WHERE company_id = ? AND created_date BETWEEN ? AND ?",
    [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
);

// Calculate lead conversion rate
$lead_conversion_rate = 0;
if ($lead_kpis['total_leads'] > 0) {
    $lead_conversion_rate = ($lead_kpis['won_leads'] / $lead_kpis['total_leads']) * 100;
}

// Enquiry KPIs
$enquiry_kpis = fn_core_database_row(
    "SELECT
        COUNT(*) as total_enquiries,
        SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) as converted,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_enquiries
     FROM enquiries
     WHERE company_id = ? AND created_date BETWEEN ? AND ?",
    [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
);

// Inventory KPIs
$inventory_kpis = fn_core_database_row(
    "SELECT
        COUNT(*) as total_vehicles,
        SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
        SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) as sold,
        SUM(CASE WHEN status = 'reserved' THEN 1 ELSE 0 END) as reserved,
        SUM(CASE WHEN status = 'available' THEN price ELSE 0 END) as inventory_value
     FROM vehicles
     WHERE company_id = ?",
    [$company_id]
);

// === FUNNEL ANALYSIS ===
$funnel = [
    'website_visitors' => 0, // Would need Google Analytics integration
    'enquiries' => $enquiry_kpis['total_enquiries'],
    'leads' => $lead_kpis['total_leads'],
    'test_drives' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM appointments
         WHERE company_id = ? AND appointment_type = 'test_drive'
         AND start_time BETWEEN ? AND ?",
        [$company_id, $date_from . ' 00:00:00', $date_to . ' 23:59:59']
    )['total'] ?? 0,
    'sales' => $sales_kpis['total_sales']
];

// === REVENUE BREAKDOWN ===
$revenue_by_month = fn_core_database_rows(
    "SELECT
        DATE_FORMAT(sale_date, '%Y-%m') as month,
        COUNT(*) as sales_count,
        SUM(total_amount) as revenue
     FROM sales_invoices
     WHERE company_id = ? AND sale_date >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
     GROUP BY month
     ORDER BY month",
    [$company_id]
);

// === CUSTOMER INSIGHTS ===
$customer_stats = fn_core_database_row(
    "SELECT
        COUNT(DISTINCT c.customer_id) as total_customers,
        COUNT(DISTINCT CASE WHEN si.invoice_id IS NOT NULL THEN c.customer_id END) as customers_with_purchases,
        AVG(purchase_count) as avg_purchases_per_customer
     FROM customers c
     LEFT JOIN (
         SELECT customer_id, COUNT(*) as purchase_count
         FROM sales_invoices
         WHERE company_id = ?
         GROUP BY customer_id
     ) si ON c.customer_id = si.customer_id
     WHERE c.company_id = ?",
    [$company_id, $company_id]
);

// === VEHICLE PERFORMANCE ===
$vehicle_performance = fn_core_database_rows(
    "SELECT
        v.body_type,
        v.fuel_type,
        COUNT(*) as stock_count,
        AVG(v.price) as avg_price,
        COUNT(si.invoice_id) as sold_count
     FROM vehicles v
     LEFT JOIN sales_invoices si ON v.vehicle_id = si.vehicle_id AND si.sale_date BETWEEN ? AND ?
     WHERE v.company_id = ?
     GROUP BY v.body_type, v.fuel_type
     ORDER BY sold_count DESC",
    [$date_from, $date_to, $company_id]
);

// === ACTIVITY TIMELINE ===
$recent_activities = fn_core_database_rows(
    "SELECT 'sale' as activity_type, sale_date as activity_date, CONCAT('Sale: €', FORMAT(total_amount, 2)) as description
     FROM sales_invoices
     WHERE company_id = ?
     UNION ALL
     SELECT 'lead' as activity_type, created_date as activity_date, CONCAT('New lead: ', first_name, ' ', last_name) as description
     FROM crm_leads
     WHERE company_id = ?
     UNION ALL
     SELECT 'enquiry' as activity_type, created_date as activity_date, CONCAT('Enquiry from ', first_name, ' ', last_name) as description
     FROM enquiries
     WHERE company_id = ?
     ORDER BY activity_date DESC
     LIMIT 20",
    [$company_id, $company_id, $company_id]
);

// === GOALS & TARGETS ===
$monthly_target = 50000; // This could be configurable per company
$target_achievement = 0;
if ($monthly_target > 0) {
    $target_achievement = ($sales_kpis['total_revenue'] / $monthly_target) * 100;
}

// Page header data
$page_header = [
    'title' => 'Analytics Dashboard',
    'subtitle' => 'Comprehensive business insights',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Reports' => '/reports',
        'Analytics' => ''
    ]
];

// Load view
require BASE_PATH . 'views/reports/analytics.php';
