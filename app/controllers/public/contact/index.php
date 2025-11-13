<?php
/**
 * Public Contact Page Controller
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

$error = null;
$success = null;

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? 'General Enquiry');
    $message = trim($_POST['message'] ?? '');

    if (empty($name)) {
        $error = 'Name is required.';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Valid email address is required.';
    } elseif (empty($message)) {
        $error = 'Message is required.';
    } else {
        // Split name into first and last
        $nameParts = explode(' ', $name, 2);
        $firstName = $nameParts[0];
        $lastName = $nameParts[1] ?? '';

        // Create enquiry
        $enquiryData = [
            'company_id' => $company_id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'enquiry_type' => 'contact',
            'subject' => $subject,
            'message' => $message,
            'source' => 'website_contact',
            'status' => 'new',
            'created_date' => date('Y-m-d H:i:s')
        ];

        $enquiry_id = fn_core_create_row('enquiries', $enquiryData, 'enquiry_id');

        if ($enquiry_id) {
            // Send notification email to company
            $emailData = [
                'to' => $company_data['company_email'],
                'subject' => "New Contact Form Submission: {$subject}",
                'body' => "You have received a new contact form submission.\n\nName: {$name}\nEmail: {$email}\nPhone: {$phone}\n\nMessage:\n{$message}",
                'company_id' => $company_id
            ];
            fn_core_email_send($emailData);

            // Send auto-reply to customer
            $autoReplyData = [
                'to' => $email,
                'subject' => "Thank you for contacting " . $company_data['company_name'],
                'body' => "Hi {$firstName},\n\nThank you for getting in touch with us. We have received your message and will get back to you as soon as possible.\n\nBest regards,\n{$company_data['company_name']}",
                'company_id' => $company_id
            ];
            fn_core_email_send($autoReplyData);

            $success = 'Thank you for your message. We will be in touch soon.';

            // Clear form
            $_POST = [];
        } else {
            $error = 'Failed to send message. Please try again.';
        }
    }
}

// Get opening hours
$opening_hours = fn_core_database_rows(
    "SELECT * FROM company_opening_hours WHERE company_id = ? ORDER BY day_of_week",
    [$company_id]
);

// Page header data
$page_header = [
    'title' => 'Contact Us',
    'subtitle' => 'Get in touch with ' . $company_data['company_name']
];

// Load view
require BASE_PATH . 'views/public/contact/index.php';
