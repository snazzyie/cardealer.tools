<?php
/**
 * Create New Appointment Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];
$user_id = $user_data['user_id'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $title = trim($_POST['title'] ?? '');
    $customerEmail = trim($_POST['customer_email'] ?? '');
    $customerPhone = trim($_POST['customer_phone'] ?? '');
    $appointmentDate = $_POST['appointment_date'] ?? '';
    $appointmentTime = $_POST['appointment_time'] ?? '';

    if (empty($title)) {
        $error = 'Appointment title is required.';
    } elseif (empty($appointmentDate) || empty($appointmentTime)) {
        $error = 'Date and time are required.';
    } else {
        // Combine date and time
        $startDatetime = $appointmentDate . ' ' . $appointmentTime;

        // Calculate end time (default 1 hour duration)
        $duration = !empty($_POST['duration']) ? intval($_POST['duration']) : 60;
        $endDatetime = date('Y-m-d H:i:s', strtotime($startDatetime . ' +' . $duration . ' minutes'));

        // Get or create customer if email provided
        $customerId = null;
        if (!empty($customerEmail)) {
            $customerId = fn_calendar_get_or_create_customer($company_id, [
                'first_name' => trim($_POST['customer_first_name'] ?? ''),
                'last_name' => trim($_POST['customer_last_name'] ?? ''),
                'email' => $customerEmail,
                'phone' => $customerPhone
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
            'assigned_to' => $user_id,
            'status' => 'scheduled',
            'notes' => trim($_POST['notes'] ?? '')
        ];

        $new_appointment_id = fn_calendar_create_appointment($company_id, $data);

        if ($new_appointment_id) {
            header("Location: /calendar?created=1");
            exit;
        } else {
            $error = 'Failed to create appointment.';
        }
    }
}

// Get vehicles for selection
$vehicles = fn_core_database_rows(
    "SELECT vehicle_id, make, model, year, registration FROM vehicles WHERE company_id = ? AND status = 'available' ORDER BY created_date DESC",
    [$company_id]
);

// Get leads for linking
$leads = fn_core_database_rows(
    "SELECT lead_id, first_name, last_name, email, phone FROM crm_leads WHERE company_id = ? AND status NOT IN ('won', 'lost') ORDER BY created_date DESC LIMIT 100",
    [$company_id]
);

// Pre-fill from query parameters
$prefill = [];
if (!empty($_GET['lead_id'])) {
    $lead = fn_crm_get_lead(intval($_GET['lead_id']), $company_id);
    if ($lead) {
        $prefill['lead_id'] = $lead['lead_id'];
        $prefill['customer_name'] = $lead['first_name'] . ' ' . $lead['last_name'];
        $prefill['customer_email'] = $lead['email'];
        $prefill['customer_phone'] = $lead['phone'];
        $prefill['vehicle_id'] = $lead['vehicle_id'];
    }
}
if (!empty($_GET['vehicle_id'])) {
    $prefill['vehicle_id'] = intval($_GET['vehicle_id']);
}

// Page header data
$page_header = [
    'title' => 'New Appointment',
    'subtitle' => 'Schedule a new appointment',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Calendar' => '/calendar',
        'New Appointment' => ''
    ]
];

// Load view
require BASE_PATH . 'views/calendar/appointment-new.php';
