<?php
/**
 * Public Test Drive Booking Controller
 */

// Get company from constant (set by public routing)
if (!defined('PUBLIC_SITE_COMPANY')) {
    http_response_code(500);
    echo '<h1>500 - Server Error</h1>';
    echo '<p>Company context not found.</p>';
    exit;
}

$company_data = PUBLIC_SITE_COMPANY;
$company_id = PUBLIC_SITE_COMPANY_ID;

// Must be POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /");
    exit;
}

$error = null;
$success = null;

// Basic validation
$firstName = trim($_POST['first_name'] ?? '');
$lastName = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$vehicleId = !empty($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : null;
$preferredDate = $_POST['preferred_date'] ?? '';
$preferredTime = $_POST['preferred_time'] ?? '';
$message = trim($_POST['message'] ?? '');

if (empty($firstName) || empty($lastName)) {
    $error = 'First name and last name are required.';
} elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Valid email address is required.';
} elseif (empty($phone)) {
    $error = 'Phone number is required.';
} elseif (empty($vehicleId)) {
    $error = 'Vehicle selection is required.';
} elseif (empty($preferredDate)) {
    $error = 'Preferred date is required.';
} else {
    // Get vehicle details
    $vehicle = fn_vehicles_get($vehicleId, $company_id);

    if (!$vehicle) {
        $error = 'Vehicle not found.';
    } else {
        // Create enquiry
        $enquiryData = [
            'company_id' => $company_id,
            'vehicle_id' => $vehicleId,
            'enquiry_type' => 'test-drive',
            'first_name' => $firstName,
            'last_name' => $lastName,
            'customer_email' => $email,
            'customer_phone' => $phone,
            'test_drive_date' => $preferredDate,
            'test_drive_time' => $preferredTime,
            'message' => $message,
            'source' => 'website',
            'status' => 'new'
        ];

        $enquiry_id = fn_core_create_row('enquiries', $enquiryData, 'enquiry_id');

        if ($enquiry_id) {
            // Create appointment/calendar entry
            $appointmentData = [
                'company_id' => $company_id,
                'title' => "Test Drive: {$vehicle['year']} {$vehicle['make']} {$vehicle['model']}",
                'description' => "Test drive appointment for {$firstName} {$lastName}",
                'customer_name' => "{$firstName} {$lastName}",
                'customer_email' => $email,
                'customer_phone' => $phone,
                'appointment_type' => 'test-drive',
                'vehicle_id' => $vehicleId,
                'start_datetime' => $preferredDate . ' ' . ($preferredTime ?: '10:00:00'),
                'end_datetime' => date('Y-m-d H:i:s', strtotime($preferredDate . ' ' . ($preferredTime ?: '10:00:00') . ' +1 hour')),
                'assigned_to' => 1, // Default to first user
                'status' => 'scheduled',
                'notes' => $message
            ];

            fn_calendar_create_appointment($company_id, $appointmentData);

            // Send notification to company
            $emailBody = "New test drive booking:\n\n";
            $emailBody .= "Customer: {$firstName} {$lastName}\n";
            $emailBody .= "Email: {$email}\n";
            $emailBody .= "Phone: {$phone}\n";
            $emailBody .= "Vehicle: {$vehicle['year']} {$vehicle['make']} {$vehicle['model']}\n";
            $emailBody .= "Preferred Date: {$preferredDate}\n";
            $emailBody .= "Preferred Time: {$preferredTime}\n\n";
            $emailBody .= "Message: {$message}\n\n";
            $emailBody .= "View enquiry: " . fn_core_url("/enquiries/view?id={$enquiry_id}");

            $emailData = [
                'to' => $company_data['company_email'],
                'subject' => "New Test Drive Booking - {$vehicle['make']} {$vehicle['model']}",
                'body' => $emailBody,
                'company_id' => $company_id
            ];
            fn_core_email_send($emailData);

            // Send confirmation to customer
            $autoReplyData = [
                'to' => $email,
                'subject' => "Test Drive Booking Confirmed - " . $company_data['company_name'],
                'body' => "Hi {$firstName},\n\nThank you for booking a test drive of our {$vehicle['year']} {$vehicle['make']} {$vehicle['model']}.\n\nYour preferred date: {$preferredDate}" . ($preferredTime ? " at {$preferredTime}" : "") . "\n\nWe will contact you shortly to confirm the exact time.\n\nBest regards,\n{$company_data['company_name']}\n{$company_data['company_phone']}",
                'company_id' => $company_id
            ];
            fn_core_email_send($autoReplyData);

            // Redirect with success
            header("Location: /cars/view?id={$vehicleId}&test_drive_booked=1");
            exit;
        } else {
            $error = 'Failed to submit booking. Please try again.';
        }
    }
}

// If there's an error, redirect back with error
if ($error) {
    $redirect = $_POST['return_url'] ?? '/cars';
    header("Location: {$redirect}?error=" . urlencode($error));
    exit;
}
