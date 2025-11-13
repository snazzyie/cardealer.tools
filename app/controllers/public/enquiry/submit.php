<?php
/**
 * Public Enquiry Submission Controller
 * Handle general vehicle enquiries
 */

// Get company from subdomain or domain
$company_id = fn_core_get_company_from_domain();

if (!$company_id) {
    header("Location: /");
    exit;
}

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
$message = trim($_POST['message'] ?? '');
$vehicleId = !empty($_POST['vehicle_id']) ? intval($_POST['vehicle_id']) : null;

if (empty($firstName) || empty($lastName)) {
    $error = 'First name and last name are required.';
} elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = 'Valid email address is required.';
} elseif (empty($phone)) {
    $error = 'Phone number is required.';
} else {
    // Create enquiry
    $enquiryData = [
        'company_id' => $company_id,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'vehicle_id' => $vehicleId,
        'enquiry_type' => 'general',
        'message' => $message,
        'source' => 'website',
        'status' => 'new',
        'created_date' => date('Y-m-d H:i:s')
    ];

    $enquiry_id = fn_core_create_row('enquiries', $enquiryData, 'enquiry_id');

    if ($enquiry_id) {
        // Get company data for emails
        $company_data = fn_company_get($company_id);

        // Get vehicle details if provided
        $vehicle = null;
        if ($vehicleId) {
            $vehicle = fn_vehicles_get($vehicleId, $company_id);
        }

        // Send notification email to company
        $vehicleInfo = $vehicle ? "{$vehicle['year']} {$vehicle['make']} {$vehicle['model']}" : 'General enquiry';
        $emailBody = "New enquiry received from {$firstName} {$lastName}\n\n";
        $emailBody .= "Vehicle: {$vehicleInfo}\n";
        $emailBody .= "Email: {$email}\n";
        $emailBody .= "Phone: {$phone}\n\n";
        $emailBody .= "Message:\n{$message}\n\n";
        $emailBody .= "View enquiry: " . fn_core_url("/enquiries/view?id={$enquiry_id}");

        $emailData = [
            'to' => $company_data['company_email'],
            'subject' => "New Vehicle Enquiry - {$vehicleInfo}",
            'body' => $emailBody,
            'company_id' => $company_id
        ];
        fn_core_email_send($emailData);

        // Send auto-reply to customer
        $autoReplyData = [
            'to' => $email,
            'subject' => "Thank you for your enquiry - " . $company_data['company_name'],
            'body' => "Hi {$firstName},\n\nThank you for your enquiry about " . ($vehicle ? "{$vehicle['year']} {$vehicle['make']} {$vehicle['model']}" : "our vehicles") . ".\n\nWe have received your message and one of our team will be in touch with you shortly.\n\nBest regards,\n{$company_data['company_name']}\n{$company_data['company_phone']}",
            'company_id' => $company_id
        ];
        fn_core_email_send($autoReplyData);

        // Redirect with success
        if ($vehicleId && $vehicle) {
            header("Location: /cars/view?id={$vehicleId}&enquiry_sent=1");
        } else {
            header("Location: /cars?enquiry_sent=1");
        }
        exit;
    } else {
        $error = 'Failed to submit enquiry. Please try again.';
    }
}

// If there's an error, redirect back with error
if ($error) {
    $redirect = $_POST['return_url'] ?? '/cars';
    header("Location: {$redirect}?error=" . urlencode($error));
    exit;
}
