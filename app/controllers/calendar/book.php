<?php
/**
 * Book Appointment Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

$error = null;
$success = null;

// Get vehicle if specified
$vehicle_id = isset($_GET['vehicle_id']) ? intval($_GET['vehicle_id']) : null;
$vehicle = null;

if ($vehicle_id) {
    $vehicle = fn_vehicles_get($vehicle_id, $company_id);
}

// Get available time slots for today and next 30 days
$available_dates = [];
for ($i = 0; $i < 30; $i++) {
    $date = date('Y-m-d', strtotime("+{$i} days"));
    $available_dates[] = $date;
}

// Get company users for assignment
$users = fn_company_get_users($company_id);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $appointmentDate = $_POST['appointment_date'] ?? '';
    $appointmentTime = $_POST['appointment_time'] ?? '';
    $appointmentType = $_POST['appointment_type'] ?? 'test_drive';
    $vehicleId = !empty($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : null;
    $assignedTo = !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : null;
    $notes = trim($_POST['notes'] ?? '');

    if (empty($firstName) || empty($email) || empty($phone) || empty($appointmentDate) || empty($appointmentTime)) {
        $error = 'Please fill in all required fields.';
    } else {
        // Check availability
        if (!fn_calendar_check_availability($company_id, $appointmentDate, $appointmentTime, 30)) {
            $error = 'This time slot is not available. Please choose another time.';
        } else {
            // Create or get customer
            $customerId = fn_calendar_get_or_create_customer($company_id, [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone
            ]);

            // Create appointment
            $appointmentData = [
                'customer_id' => $customerId,
                'vehicle_id' => $vehicleId,
                'appointment_type' => $appointmentType,
                'appointment_date' => $appointmentDate,
                'appointment_time' => $appointmentTime,
                'duration_minutes' => 30,
                'assigned_to' => $assignedTo,
                'notes' => $notes,
                'status' => 'scheduled'
            ];

            $appointmentId = fn_calendar_create_appointment($company_id, $appointmentData);

            if ($appointmentId) {
                // TODO: Send confirmation email via Postmark
                // TODO: Add to Google Calendar

                $success = 'Appointment booked successfully!';
            } else {
                $error = 'Failed to book appointment. Please try again.';
            }
        }
    }
}

// Get available vehicles
$vehicles = fn_vehicles_get_all($company_id, ['status' => 'available'], 100, 0);

$page_header = [
    'title' => 'Book Appointment',
    'subtitle' => 'Schedule a test drive or consultation'
];

require BASE_PATH . 'views/calendar/book.php';
