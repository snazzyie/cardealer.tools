<?php
/**
 * Users List Controller
 * Manage team members and staff
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Only company admin (user_type 2) can manage users
if ($permission < 2) {
    header("Location: /dash");
    exit;
}

// Get all company users
$users = fn_core_database_rows(
    "SELECT user_id, first_name, last_name, email, user_type, user_role, status, created_date, last_login
     FROM users
     WHERE company_id = ?
     ORDER BY created_date DESC",
    [$company_id]
);

// Get user stats
$stats = [
    'total' => count($users),
    'active' => count(array_filter($users, fn($u) => $u['status'] === 'active')),
    'inactive' => count(array_filter($users, fn($u) => $u['status'] === 'inactive')),
    'admins' => count(array_filter($users, fn($u) => $u['user_type'] == 2))
];

// User roles for display
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
    'title' => 'Users',
    'subtitle' => 'Manage team members',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Users' => ''
    ]
];

// Load view
require BASE_PATH . 'views/users/users.php';
