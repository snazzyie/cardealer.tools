<?php
/**
 * Calendar Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get current month and year from query params or use current
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// Calculate date range for the month
$start_date = date('Y-m-01', mktime(0, 0, 0, $month, 1, $year));
$end_date = date('Y-m-t', mktime(0, 0, 0, $month, 1, $year));

// Get appointments for the month
$appointments = fn_calendar_get_appointments($company_id, [
    'start_date' => $start_date,
    'end_date' => $end_date
]);

// Get stats
$stats = fn_calendar_get_stats($company_id);

// Get upcoming appointments
$upcoming = fn_calendar_get_upcoming($company_id, 7);

// Get company users for assignment
$users = fn_company_get_users($company_id);

// Get available vehicles
$vehicles = fn_vehicles_get_all($company_id, ['status' => 'available'], 100, 0);

$page_header = [
    'title' => 'Calendar & Appointments',
    'subtitle' => 'Manage your appointment schedule'
];

require BASE_PATH . 'views/calendar/calendar.php';
