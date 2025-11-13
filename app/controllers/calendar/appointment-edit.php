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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['cancel_appointment'])) {
    // Basic validation
    $title = trim($_POST['title'] ?? '');
    $appointmentDate = $_POST['appointment_date'] ?? '';
    $appointmentTime = $_POST['appointment_time'] ?? '';

    if (empty($title)) {
        $error = 'Appointment title is required.';
    } elseif (empty($appointmentDate) || empty($appointmentTime)) {
        $error = 'Date and time are required.';
    } else {
        // Combine date and time
        $startDatetime = $appointmentDate . ' ' . $appointmentTime;

        // Calculate end time
        $duration = !empty($_POST['duration']) ? intval($_POST['duration']) : 60;
        $endDatetime = date('Y-m-d H:i:s', strtotime($startDatetime . ' +' . $duration . ' minutes'));

        // Get or create customer if email provided
        $customerId = $appointment_data['customer_id'];
        $customerEmail = trim($_POST['customer_email'] ?? '');
        if (!empty($customerEmail)) {
            $customerId = fn_calendar_get_or_create_customer($company_id, [
                'first_name' => trim($_POST['customer_first_name'] ?? ''),
                'last_name' => trim($_POST['customer_last_name'] ?? ''),
                'email' => $customerEmail,
                'phone' => trim($_POST['customer_phone'] ?? '')
            ]);
        }

        $data = [
            'customer_id' => $customerId,
            'vehicle_id' => !empty($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : null,
            'lead_id' => !empty($_POST['lead_id']) ? intval($_POST['lead_id']) : null,
            'appointment_type' => $_POST['appointment_type'] ?? 'test-drive',
            'title' => $title,
            'description' => trim($_POST['description'] ?? ''),
            'location' => trim($_POST['location'] ?? ''),
            'start_datetime' => $startDatetime,
            'end_datetime' => $endDatetime,
            'assigned_to' => !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : $appointment_data['assigned_to'],
            'status' => $_POST['status'] ?? 'scheduled',
            'notes' => trim($_POST['notes'] ?? '')
        ];

        $result = fn_calendar_update_appointment($appointment_id, $company_id, $data);

        if ($result) {
            $success = 'Appointment updated successfully.';
            // Refresh appointment data
            $appointment_data = fn_calendar_get_appointment($appointment_id, $company_id);
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
