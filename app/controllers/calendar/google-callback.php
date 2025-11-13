<?php
/**
 * Google Calendar OAuth2 Callback
 */

fn_require_login();

$code = $_GET['code'] ?? '';
$state = $_GET['state'] ?? ''; // User ID
$error = $_GET['error'] ?? '';

if ($error) {
    header("Location: /calendar/google-connect?error=auth_failed");
    exit;
}

if (!$code || !$state) {
    header("Location: /calendar/google-connect?error=missing_params");
    exit;
}

// Verify the user ID from state matches current user
$user_data = fn_core_session_get_user_data($_SESSION['email']);
$user_id = $user_data['user_id'];

if ($state != $user_id) {
    header("Location: /calendar/google-connect?error=invalid_state");
    exit;
}

// Handle OAuth callback
$success = fn_calendar_google_handle_callback($code, $user_id);

if ($success) {
    header("Location: /calendar/google-connect?success=connected");
} else {
    header("Location: /calendar/google-connect?error=token_exchange_failed");
}

exit;
