<?php
/**
 * CRM Leads List Controller
 * Display all leads with filtering and search
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_lead_id'])) {
    $leadId = intval($_POST['delete_lead_id']);
    $success = fn_crm_delete_lead($leadId, $company_id);

    if ($success) {
        header("Location: /crm/leads?deleted=1");
        exit;
    } else {
        $error = "Failed to delete lead.";
    }
}

// Get filters
$filters = [];
if (!empty($_GET['status'])) {
    $filters['status'] = $_GET['status'];
}
if (!empty($_GET['source'])) {
    $filters['source'] = $_GET['source'];
}
if (!empty($_GET['assigned_to'])) {
    $filters['assigned_to'] = intval($_GET['assigned_to']);
}
if (!empty($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}

// Get leads
$leads = fn_crm_get_leads($company_id, $filters);

// Get stats
$stats = [
    'total' => fn_crm_count_leads($company_id),
    'new' => fn_crm_count_by_status($company_id, 'new'),
    'contacted' => fn_crm_count_by_status($company_id, 'contacted'),
    'qualified' => fn_crm_count_by_status($company_id, 'qualified'),
    'won' => fn_crm_count_by_status($company_id, 'won'),
    'lost' => fn_crm_count_by_status($company_id, 'lost')
];

// Get users for assignment filter
$users = fn_core_database_rows("SELECT user_id, first_name, last_name FROM users WHERE company_id = ? ORDER BY first_name", [$company_id]);

// Page header data
$page_header = [
    'title' => 'Leads',
    'subtitle' => 'Manage your sales leads',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'CRM' => '/crm',
        'Leads' => ''
    ]
];

// Load view
require BASE_PATH . 'views/crm/leads.php';
