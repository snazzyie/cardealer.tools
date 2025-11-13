<?php
/**
 * View Message Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$user_id = $user_data['user_id'];

// Get message ID from URL
$message_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$message_id) {
    header("Location: /inbox");
    exit;
}

// Get message details
$message = fn_core_database_row(
    "SELECT * FROM communications WHERE communication_id = ? AND company_id = ?",
    [$message_id, $company_id]
);

if (!$message) {
    header("Location: /inbox?error=not_found");
    exit;
}

// Mark as read
fn_core_database_query(
    "UPDATE communications SET is_read = 1 WHERE communication_id = ? AND company_id = ?",
    [$message_id, $company_id]
);

// Page header data
$page_header = [
    'title' => htmlspecialchars($message['subject']),
    'subtitle' => 'Message Details',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Inbox' => '/inbox',
        'View Message' => ''
    ]
];

// Load view
require BASE_PATH . 'views/inbox/view.php';
