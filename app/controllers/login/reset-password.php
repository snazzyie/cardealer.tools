<?php
/**
 * Reset Password Controller
 */

// If already logged in, redirect to dashboard
if (isset($_SESSION['email'])) {
    header("Location: /dash");
    exit;
}

$error = null;
$success = null;
$token = $_GET['token'] ?? '';

if (empty($token)) {
    header("Location: /login");
    exit;
}

// Verify token
$reset = fn_core_database_row(
    "SELECT pr.*, u.email, u.first_name
     FROM password_resets pr
     JOIN users u ON pr.user_id = u.user_id
     WHERE pr.token = ? AND pr.expiry > NOW()",
    [$token]
);

if (!$reset) {
    $error = 'Invalid or expired reset link. Please request a new one.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password)) {
        $error = 'Please enter a new password.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Update user password
        fn_core_database_query(
            "UPDATE users SET password_hash = ? WHERE user_id = ?",
            [$password_hash, $reset['user_id']]
        );

        // Delete used token
        fn_core_database_query(
            "DELETE FROM password_resets WHERE token = ?",
            [$token]
        );

        $success = 'Your password has been reset successfully. You can now login.';
    }
}

// Page title
$page_title = 'Reset Password';

// Load view
require BASE_PATH . 'views/login/reset-password.php';
