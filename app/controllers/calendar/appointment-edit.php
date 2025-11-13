<?php
/**
 * Edit Appointment Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get appointment ID
if (empty($_GET['id'])) {
    header("Location: /calendar");
    exit;
}

$appointment_id = intval($_GET['id']);
$appointment_data = fn_calendar_get_appointment($appointment_id, $company_id);

if (!$appointment_data) {
    header("Location: /calendar");
    exit;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $title = trim($_POST['title'] ?? '');
    $customerName = trim($_POST['customer_name'] ?? '');
    $startDate = $_POST['start_date'] ?? '';
    $startTime = $_POST['start_time'] ?? '';

    if (empty($title)) {
        $error = 'Appointment title is required.';
    } elseif (empty($customerName)) {
        $error = 'Customer name is required.';
    } elseif (empty($startDate) || empty($startTime)) {
        $error = 'Start date and time are required.';
    } else {
        // Combine date and time
        $startDateTime = $startDate . ' ' . $startTime;

        // Calculate end time
        $duration = !empty($_POST['duration']) ? intval($_POST['duration']) : 60;
        $endDateTime = date('Y-m-d H:i:s', strtotime($startDateTime . ' +' . $duration . ' minutes'));

        $data = [
            'title' => $title,
            'description' => trim($_POST['description'] ?? ''),
            'customer_name' => $customerName,
            'customer_email' => trim($_POST['customer_email'] ?? ''),
            'customer_phone' => trim($_POST['customer_phone'] ?? ''),
            'appointment_type' => $_POST['appointment_type'] ?? 'test_drive',
            'vehicle_id' => !empty($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : null,
            'lead_id' => !empty($_POST['lead_id']) ? intval($_POST['lead_id']) : null,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'status' => $_POST['status'] ?? 'scheduled',
            'location' => trim($_POST['location'] ?? ''),
            'notes' => trim($_POST['notes'] ?? '')
        ];

        $result = fn_calendar_update_appointment($appointment_id, $company_id, $data);

        if ($result) {
            $success = 'Appointment updated successfully.';
            // Refresh appointment data
            $appointment_data = fn_calendar_get_appointment($appointment_id, $company_id);

            // Send update notification if email provided and status changed
            if (!empty($data['customer_email']) && $data['status'] !== $appointment_data['status']) {
                fn_calendar_send_appointment_update($appointment_id);
            }
        } else {
            $error = 'Failed to update appointment.';
        }
    }
}

// Handle cancellation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_appointment'])) {
    $result = fn_calendar_cancel_appointment($appointment_id, $company_id);

    if ($result) {
        // Send cancellation email
        if (!empty($appointment_data['customer_email'])) {
            fn_calendar_send_appointment_cancellation($appointment_id);
        }

        header("Location: /calendar?cancelled=1");
        exit;
    } else {
        $error = 'Failed to cancel appointment.';
    }
}

// Get vehicles for selection
$vehicles = fn_core_database_rows(
    "SELECT vehicle_id, make, model, year, registration FROM vehicles WHERE company_id = ? ORDER BY created_date DESC",
    [$company_id]
);

// Get leads for linking
$leads = fn_core_database_rows(
    "SELECT lead_id, first_name, last_name, email, phone FROM crm_leads WHERE company_id = ? ORDER BY created_date DESC LIMIT 100",
    [$company_id]
);

// Page header data
$page_header = [
    'title' => 'Edit Appointment',
    'subtitle' => 'Update appointment details',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Calendar' => '/calendar',
        'Edit Appointment' => ''
    ]
];

// Load view
require BASE_PATH . 'views/calendar/appointment-edit.php';
