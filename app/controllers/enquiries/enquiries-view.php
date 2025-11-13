<?php
/**
 * View Enquiry Details Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get enquiry ID
if (empty($_GET['id'])) {
    header("Location: /enquiries");
    exit;
}

$enquiry_id = intval($_GET['id']);
$enquiry_data = fn_core_database_row(
    "SELECT e.*, v.make, v.model, v.year, v.price, v.registration
     FROM enquiries e
     LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
     WHERE e.enquiry_id = ? AND e.company_id = ?",
    [$enquiry_id, $company_id]
);

if (!$enquiry_data) {
    header("Location: /enquiries");
    exit;
}

$error = null;
$success = null;

// Handle adding note
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_note'])) {
    $note = trim($_POST['note'] ?? '');
    if (!empty($note)) {
        $result = fn_core_database_query(
            "INSERT INTO enquiry_notes (enquiry_id, user_id, note, created_date) VALUES (?, ?, ?, NOW())",
            [$enquiry_id, $user_data['user_id'], $note]
        );

        if ($result) {
            $success = 'Note added successfully.';
        } else {
            $error = 'Failed to add note.';
        }
    }
}

// Handle sending email response
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_email'])) {
    $emailSubject = trim($_POST['email_subject'] ?? '');
    $emailBody = trim($_POST['email_body'] ?? '');

    if (!empty($emailSubject) && !empty($emailBody) && !empty($enquiry_data['email'])) {
        $emailData = [
            'to' => $enquiry_data['email'],
            'subject' => $emailSubject,
            'body' => $emailBody,
            'company_id' => $company_id
        ];

        $result = fn_core_email_send($emailData);

        if ($result) {
            // Update enquiry status to contacted
            fn_core_database_query(
                "UPDATE enquiries SET status = 'contacted', last_contact = NOW() WHERE enquiry_id = ?",
                [$enquiry_id]
            );

            // Add activity note
            fn_core_database_query(
                "INSERT INTO enquiry_notes (enquiry_id, user_id, note, created_date) VALUES (?, ?, ?, NOW())",
                [$enquiry_id, $user_data['user_id'], "Email sent: {$emailSubject}"]
            );

            $success = 'Email sent successfully.';
            // Refresh enquiry data
            $enquiry_data['status'] = 'contacted';
        } else {
            $error = 'Failed to send email.';
        }
    } else {
        $error = 'Email subject and body are required.';
    }
}

// Get enquiry notes
$notes = fn_core_database_rows(
    "SELECT n.*, u.first_name, u.last_name
     FROM enquiry_notes n
     LEFT JOIN users u ON n.user_id = u.user_id
     WHERE n.enquiry_id = ?
     ORDER BY n.created_date DESC",
    [$enquiry_id]
);

// Get vehicle info if associated
$vehicle = null;
if (!empty($enquiry_data['vehicle_id'])) {
    $vehicle = fn_vehicles_get($enquiry_data['vehicle_id'], $company_id);
}

// Page header data
$page_header = [
    'title' => 'Enquiry Details',
    'subtitle' => $enquiry_data['first_name'] . ' ' . $enquiry_data['last_name'],
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Enquiries' => '/enquiries',
        'View Enquiry' => ''
    ]
];

// Load view
require BASE_PATH . 'views/enquiries/enquiries-view.php';
