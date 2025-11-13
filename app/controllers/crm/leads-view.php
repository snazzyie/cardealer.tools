<?php
/**
 * CRM View Lead Details Controller
 * Display lead details with activities, tasks, and notes
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get lead ID
if (empty($_GET['id'])) {
    header("Location: /crm/leads");
    exit;
}

$lead_id = intval($_GET['id']);
$lead_data = fn_crm_get_lead($lead_id, $company_id);

if (!$lead_data) {
    header("Location: /crm/leads");
    exit;
}

$error = null;
$success = null;

// Handle adding note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
    $note = trim($_POST['note'] ?? '');
    if (!empty($note)) {
        $noteData = [
            'lead_id' => $lead_id,
            'user_id' => $user_data['user_id'],
            'note' => $note,
            'created_date' => date('Y-m-d H:i:s')
        ];

        $result = fn_crm_add_note($noteData);
        if ($result) {
            $success = 'Note added successfully.';
        } else {
            $error = 'Failed to add note.';
        }
    }
}

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $newStatus = $_POST['status'];
    $result = fn_crm_update_lead_status($lead_id, $company_id, $newStatus);

    if ($result) {
        $success = 'Lead status updated successfully.';
        // Refresh lead data
        $lead_data = fn_crm_get_lead($lead_id, $company_id);
    } else {
        $error = 'Failed to update lead status.';
    }
}

// Get lead activities
$activities = fn_crm_get_lead_activities($lead_id);

// Get lead tasks
$tasks = fn_crm_get_lead_tasks($lead_id);

// Get lead notes
$notes = fn_crm_get_lead_notes($lead_id);

// Get assigned user info
$assigned_user = null;
if (!empty($lead_data['assigned_to'])) {
    $assigned_user = fn_core_database_row(
        "SELECT user_id, first_name, last_name, email FROM users WHERE user_id = ?",
        [$lead_data['assigned_to']]
    );
}

// Get vehicle info if associated
$vehicle = null;
if (!empty($lead_data['vehicle_id'])) {
    $vehicle = fn_core_database_row(
        "SELECT * FROM vehicles WHERE vehicle_id = ?",
        [$lead_data['vehicle_id']]
    );
}

// Page header data
$page_header = [
    'title' => 'Lead Details',
    'subtitle' => $lead_data['first_name'] . ' ' . $lead_data['last_name'],
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'CRM' => '/crm',
        'Leads' => '/crm/leads',
        'View Lead' => ''
    ]
];

// Load view
require BASE_PATH . 'views/crm/leads-view.php';
