<?php
/**
 * Create New User Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Only company admin (user_type 2) can create users
if ($permission < 2) {
    header("Location: /dash");
    exit;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $userRole = $_POST['user_role'] ?? 'staff';

    if (empty($firstName) || empty($lastName)) {
        $error = 'First name and last name are required.';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Valid email address is required.';
    } elseif (empty($password) || strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    } else {
        // Check if email already exists
        $existing = fn_core_database_row(
            "SELECT user_id FROM users WHERE email = ?",
            [$email]
        );

        if ($existing) {
            $error = 'This email address is already registered.';
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Create user
            $userData = [
                'company_id' => $company_id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'password' => $hashedPassword,
                'user_type' => 1, // Company user
                'user_role' => $userRole,
                'status' => 'active',
                'created_date' => date('Y-m-d H:i:s')
            ];

            $new_user_id = fn_core_create_row('users', $userData, 'user_id');

            if ($new_user_id) {
                // Send welcome email
                $welcomeEmail = [
                    'to' => $email,
                    'subject' => 'Welcome to the Team',
                    'body' => "Hi {$firstName},\n\nYour account has been created. You can login with your email and password.\n\nLogin URL: " . fn_core_url('/login'),
                    'company_id' => $company_id
                ];
                fn_core_email_send($welcomeEmail);

                header("Location: /users?created=1");
                exit;
            } else {
                $error = 'Failed to create user.';
            }
        }
    }
}

// User roles
$user_roles = [
    'owner' => 'Owner',
    'admin' => 'Administrator',
    'manager' => 'Manager',
    'sales' => 'Sales Person',
    'service' => 'Service Advisor',
    'staff' => 'Staff Member'
];

// Page header data
$page_header = [
    'title' => 'Create New User',
    'subtitle' => 'Add a new team member',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Users' => '/users',
        'New User' => ''
    ]
];

// Load view
require BASE_PATH . 'views/users/users-new.php';
