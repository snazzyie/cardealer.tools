<?php
/**
 * CRM Activities History Controller
 * Display timeline of all CRM activities
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
if (!empty($_GET['user_id'])) {
    $filters['user_id'] = intval($_GET['user_id']);
}
if (!empty($_GET['activity_type'])) {
    $filters['activity_type'] = $_GET['activity_type'];
}
if (!empty($_GET['lead_id'])) {
    $filters['lead_id'] = intval($_GET['lead_id']);
}
if (!empty($_GET['date_from'])) {
    $filters['date_from'] = $_GET['date_from'];
}
if (!empty($_GET['date_to'])) {
    $filters['date_to'] = $_GET['date_to'];
}

// Get activities
$activities = fn_crm_get_activities($company_id, $filters);

// Get activity stats
$stats = [
    'total_today' => fn_crm_count_activities_today($company_id),
    'total_week' => fn_crm_count_activities_week($company_id),
    'total_month' => fn_crm_count_activities_month($company_id),
    'calls' => fn_crm_count_activities_by_type($company_id, 'call'),
    'emails' => fn_crm_count_activities_by_type($company_id, 'email'),
    'meetings' => fn_crm_count_activities_by_type($company_id, 'meeting'),
    'notes' => fn_crm_count_activities_by_type($company_id, 'note')
];

// Get users for filter
$users = fn_core_database_rows(
    "SELECT user_id, first_name, last_name FROM users WHERE company_id = ? ORDER BY first_name",
    [$company_id]
);

// Activity types for filter
$activity_types = [
    'call' => 'Phone Call',
    'email' => 'Email',
    'meeting' => 'Meeting',
    'note' => 'Note',
    'status_change' => 'Status Change',
    'task_created' => 'Task Created',
    'task_completed' => 'Task Completed'
];

// Page header data
$page_header = [
    'title' => 'CRM Activities',
    'subtitle' => 'Activity timeline and history',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'CRM' => '/crm',
        'Activities' => ''
    ]
];

// Load view
require BASE_PATH . 'views/crm/activities.php';
