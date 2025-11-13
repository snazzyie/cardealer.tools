<?php
/**
 * Login Controller
 */

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] >= 1) {
    header("Location: /dash");
    exit;
}

$error = null;
$message = $_SESSION['message'] ?? null;
unset($_SESSION['message']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Please enter both email and password.';
    } else {
        // Attempt login
        $success = fn_core_session_log_me_in($email, $password);

        if ($success) {
            // Redirect to dashboard
            header("Location: /dash");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}

// Load login view
require BASE_PATH . 'views/login/login.php';
