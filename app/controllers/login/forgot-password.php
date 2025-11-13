<?php
/**
 * Forgot Password Controller
 */

// If already logged in, redirect to dashboard
if (isset($_SESSION['email'])) {
    header("Location: /dash");
    exit;
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // Check if user exists
        $user = fn_core_database_row(
            "SELECT user_id, first_name FROM users WHERE email = ?",
            [$email]
        );

        if ($user) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store token (you should create a password_resets table for this)
            fn_core_database_query(
                "INSERT INTO password_resets (user_id, token, expiry, created_date)
                 VALUES (?, ?, ?, NOW())
                 ON DUPLICATE KEY UPDATE token = ?, expiry = ?, created_date = NOW()",
                [$user['user_id'], $token, $expiry, $token, $expiry]
            );

            // Send reset email
            $reset_link = fn_core_url("/reset-password?token=$token");
            $email_body = "Hi {$user['first_name']},\n\n";
            $email_body .= "You requested to reset your password. Click the link below to reset it:\n\n";
            $email_body .= "$reset_link\n\n";
            $email_body .= "This link will expire in 1 hour.\n\n";
            $email_body .= "If you didn't request this, please ignore this email.\n\n";
            $email_body .= "Thanks,\nCar Dealer SaaS";

            fn_core_email_send([
                'to' => $email,
                'subject' => 'Reset Your Password',
                'body' => $email_body
            ]);

            $success = 'Password reset instructions have been sent to your email.';
        } else {
            // Don't reveal if email exists or not (security best practice)
            $success = 'If that email address exists, password reset instructions have been sent.';
        }
    }
}

// Page title
$page_title = 'Forgot Password';

// Load view
require BASE_PATH . 'views/login/forgot-password.php';
