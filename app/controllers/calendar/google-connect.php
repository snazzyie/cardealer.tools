<?php
/**
 * Google Calendar Connection Settings
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$user_id = $user_data['user_id'];
$permission = $user_data['user_type'];

// Handle disconnect
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'disconnect') {
    fn_calendar_google_disconnect($user_id);
    header("Location: /calendar/google-connect?success=disconnected");
    exit;
}

// Check connection status
$is_connected = fn_calendar_google_is_connected($user_id);

// Get OAuth URL
$auth_url = null;
if (!$is_connected) {
    $auth_url = fn_calendar_google_get_auth_url($user_id);
}

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

$page_header = [
    'title' => 'Google Calendar Settings',
    'subtitle' => 'Connect your Google Calendar for automatic appointment sync'
];

require BASE_PATH . 'views/calendar/google-connect.php';
