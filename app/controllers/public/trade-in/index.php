<?php
/**
 * Public Trade-In Valuation Page Controller
 */

// Get company from subdomain or domain
$company_id = fn_core_get_company_from_domain();

if (!$company_id) {
    header("Location: /");
    exit;
}

// Get company data
$company_data = fn_company_get($company_id);

if (!$company_data) {
    header("Location: /");
    exit;
}

$error = null;
$success = null;

// Handle trade-in form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $vehicleMake = trim($_POST['vehicle_make'] ?? '');
    $vehicleModel = trim($_POST['vehicle_model'] ?? '');
    $vehicleYear = trim($_POST['vehicle_year'] ?? '');
    $mileage = trim($_POST['mileage'] ?? '');
    $condition = $_POST['condition'] ?? '';
    $additionalInfo = trim($_POST['additional_info'] ?? '');

    if (empty($firstName) || empty($lastName)) {
        $error = 'First name and last name are required.';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Valid email address is required.';
    } elseif (empty($phone)) {
        $error = 'Phone number is required.';
    } elseif (empty($vehicleMake) || empty($vehicleModel) || empty($vehicleYear)) {
        $error = 'Vehicle details (make, model, year) are required.';
    } else {
        // Create trade-in enquiry
        $tradeInInfo = "Trade-In Request:\n\n";
        $tradeInInfo .= "Vehicle: {$vehicleYear} {$vehicleMake} {$vehicleModel}\n";
        $tradeInInfo .= "Mileage: {$mileage}\n";
        $tradeInInfo .= "Condition: {$condition}\n";
        $tradeInInfo .= "Additional Info: {$additionalInfo}\n";

        $enquiryData = [
            'company_id' => $company_id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'enquiry_type' => 'trade_in',
            'message' => $tradeInInfo,
            'source' => 'website_trade_in',
            'status' => 'new',
            'created_date' => date('Y-m-d H:i:s')
        ];

        $enquiry_id = fn_core_create_row('enquiries', $enquiryData, 'enquiry_id');

        if ($enquiry_id) {
            // Send notification email to company
            $emailBody = "New trade-in valuation request:\n\n";
            $emailBody .= "Customer: {$firstName} {$lastName}\n";
            $emailBody .= "Email: {$email}\n";
            $emailBody .= "Phone: {$phone}\n\n";
            $emailBody .= $tradeInInfo . "\n";
            $emailBody .= "View enquiry: " . fn_core_url("/enquiries/view?id={$enquiry_id}");

            $emailData = [
                'to' => $company_data['company_email'],
                'subject' => "Trade-In Valuation Request - {$vehicleYear} {$vehicleMake} {$vehicleModel}",
                'body' => $emailBody,
                'company_id' => $company_id
            ];
            fn_core_email_send($emailData);

            // Send auto-reply to customer
            $autoReplyData = [
                'to' => $email,
                'subject' => "Trade-In Valuation Request Received - " . $company_data['company_name'],
                'body' => "Hi {$firstName},\n\nThank you for your trade-in valuation request for your {$vehicleYear} {$vehicleMake} {$vehicleModel}.\n\nWe have received your details and one of our team will be in touch with you shortly to provide a valuation.\n\nBest regards,\n{$company_data['company_name']}\n{$company_data['company_phone']}",
                'company_id' => $company_id
            ];
            fn_core_email_send($autoReplyData);

            $success = 'Thank you! We will contact you soon with a valuation for your vehicle.';

            // Clear form
            $_POST = [];
        } else {
            $error = 'Failed to submit request. Please try again.';
        }
    }
}

// Page header data
$page_header = [
    'title' => 'Trade-In Valuation',
    'subtitle' => 'Get a valuation for your current vehicle'
];

// Load view
require BASE_PATH . 'views/public/trade-in/index.php';
