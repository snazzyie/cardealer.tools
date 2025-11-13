<?php
/**
 * Register Controller
 */

// If already logged in, redirect to dashboard
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] >= 1) {
    header("Location: /dash");
    exit;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Validation
    if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Passwords do not match.';
    } else {
        // Attempt registration
        $userId = fn_core_session_register_user([
            'email' => $email,
            'password' => $password,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone
        ]);

        if ($userId) {
            // Auto-login after registration
            fn_core_session_log_me_in($email, $password);
            header("Location: /dash");
            exit;
        } else {
            $error = 'This email address is already registered.';
        }
    }
}

// Load register view
require BASE_PATH . 'views/login/register.php';
