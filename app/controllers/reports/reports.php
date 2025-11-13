<?php
/**
 * Reports & Analytics Controller
 */

fn_require_login(2);

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Date range from query or default to this month
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-t');

// Sales Report
$query = "SELECT COUNT(*) as total_sales, SUM(total_amount) as revenue
          FROM sales_invoices
          WHERE company_id = ? AND invoice_date BETWEEN ? AND ? AND status = 'paid'";
$sales_report = fn_core_database_row($query, [$company_id, $start_date, $end_date]);

// Enquiries Report
$query = "SELECT COUNT(*) as total_enquiries,
          SUM(CASE WHEN created_date >= ? THEN 1 ELSE 0 END) as period_enquiries
          FROM enquiries WHERE company_id = ?";
$enquiries_report = fn_core_database_row($query, [$start_date, $company_id]);

// Leads Report
$query = "SELECT status, COUNT(*) as count
          FROM crm_leads
          WHERE company_id = ? AND created_date BETWEEN ? AND ?
          GROUP BY status";
$leads_by_status = fn_core_database_rows($query, [$company_id, $start_date, $end_date]);

// Vehicle Views (mock data - would need tracking table)
$vehicle_views = [
    'total' => 0,
    'this_period' => 0
];

// Popular Vehicles
$query = "SELECT v.*, COUNT(e.enquiry_id) as enquiry_count
          FROM vehicles v
          LEFT JOIN enquiries e ON v.vehicle_id = e.vehicle_id AND e.created_date BETWEEN ? AND ?
          WHERE v.company_id = ?
          GROUP BY v.vehicle_id
          ORDER BY enquiry_count DESC
          LIMIT 10";
$popular_vehicles = fn_core_database_rows($query, [$start_date, $end_date, $company_id]);

// Appointments Report
$query = "SELECT COUNT(*) as total_appointments,
          SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
          SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
          FROM calendar_appointments
          WHERE company_id = ? AND appointment_date BETWEEN ? AND ?";
$appointments_report = fn_core_database_row($query, [$company_id, $start_date, $end_date]);

// Conversion Rate
$conversion_rate = fn_crm_get_conversion_rate($company_id);

$page_header = [
    'title' => 'Reports & Analytics',
    'subtitle' => 'Business insights and performance metrics'
];

require BASE_PATH . 'views/reports/reports.php';
