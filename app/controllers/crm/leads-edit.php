<?php
/**
 * CRM Edit Lead Controller
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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    if (empty($firstName) || empty($lastName)) {
        $error = 'First name and last name are required.';
    } elseif (empty($email) && empty($phone)) {
        $error = 'Email or phone number is required.';
    } else {
        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'source' => $_POST['source'] ?? 'manual',
            'status' => $_POST['status'] ?? 'new',
            'assigned_to' => !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : null,
            'vehicle_id' => !empty($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : null,
            'notes' => trim($_POST['notes'] ?? '')
        ];

        $result = fn_crm_update_lead($lead_id, $company_id, $data);

        if ($result) {
            $success = 'Lead updated successfully.';
            // Refresh lead data
            $lead_data = fn_crm_get_lead($lead_id, $company_id);
        } else {
            $error = 'Failed to update lead.';
        }
    }
}

// Get vehicles for selection
$vehicles = fn_core_database_rows(
    "SELECT vehicle_id, make, model, year, price FROM vehicles WHERE company_id = ? ORDER BY created_date DESC",
    [$company_id]
);

// Get users for assignment
$users = fn_core_database_rows(
    "SELECT user_id, first_name, last_name FROM users WHERE company_id = ? ORDER BY first_name",
    [$company_id]
);

// Page header data
$page_header = [
    'title' => 'Edit Lead',
    'subtitle' => 'Update lead information',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'CRM' => '/crm',
        'Leads' => '/crm/leads',
        'Edit Lead' => ''
    ]
];

// Load view
require BASE_PATH . 'views/crm/leads-edit.php';
