<?php
/**
 * Edit User Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get user ID
if (empty($_GET['id'])) {
    header("Location: /users");
    exit;
}

$edit_user_id = intval($_GET['id']);

// Users can edit their own profile, or admins can edit any user
$can_edit = ($edit_user_id === $user_data['user_id']) || ($permission >= 2);

if (!$can_edit) {
    header("Location: /dash");
    exit;
}

// Get user to edit
$edit_user_data = fn_core_database_row(
    "SELECT * FROM users WHERE user_id = ? AND company_id = ?",
    [$edit_user_id, $company_id]
);

if (!$edit_user_data) {
    header("Location: /users");
    exit;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($firstName) || empty($lastName)) {
        $error = 'First name and last name are required.';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Valid email address is required.';
    } else {
        // Check if email is taken by another user
        $existing = fn_core_database_row(
            "SELECT user_id FROM users WHERE email = ? AND user_id != ?",
            [$email, $edit_user_id]
        );

        if ($existing) {
            $error = 'This email address is already in use.';
        } else {
            $updateData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => trim($_POST['phone'] ?? '')
            ];

            // Only admins can change role and status
            if ($permission >= 2) {
                $updateData['user_role'] = $_POST['user_role'] ?? 'staff';
                $updateData['status'] = $_POST['status'] ?? 'active';
            }

            // Handle password change
            if (!empty($_POST['new_password'])) {
                if (strlen($_POST['new_password']) < 8) {
                    $error = 'Password must be at least 8 characters long.';
                } else {
                    $updateData['password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                }
            }

            if (empty($error)) {
                $result = fn_core_update_row('users', $edit_user_id, $updateData, 'user_id');

                if ($result) {
                    $success = 'User updated successfully.';

                    // If user edited their own email, update session
                    if ($edit_user_id === $user_data['user_id'] && $email !== $edit_user_data['email']) {
                        $_SESSION['email'] = $email;
                    }

                    // Refresh user data
                    $edit_user_data = fn_core_database_row(
                        "SELECT * FROM users WHERE user_id = ? AND company_id = ?",
                        [$edit_user_id, $company_id]
                    );
                } else {
                    $error = 'Failed to update user.';
                }
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
    'title' => 'Edit User',
    'subtitle' => 'Update user information',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Users' => '/users',
        'Edit User' => ''
    ]
];

// Load view
require BASE_PATH . 'views/users/users-edit.php';
