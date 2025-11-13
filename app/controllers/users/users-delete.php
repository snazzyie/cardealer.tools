<?php
/**
 * Delete User Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Only company admin (user_type 2) can delete users
if ($permission < 2) {
    header("Location: /dash");
    exit;
}

// Must be POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /users");
    exit;
}

// Get user ID
if (empty($_POST['user_id'])) {
    header("Location: /users");
    exit;
}

$delete_user_id = intval($_POST['user_id']);

// Cannot delete yourself
if ($delete_user_id === $user_data['user_id']) {
    header("Location: /users?error=cannot_delete_self");
    exit;
}

// Verify user belongs to company
$delete_user = fn_core_database_row(
    "SELECT user_id, email FROM users WHERE user_id = ? AND company_id = ?",
    [$delete_user_id, $company_id]
);

if (!$delete_user) {
    header("Location: /users?error=user_not_found");
    exit;
}

// Soft delete - set status to 'deleted' instead of actually deleting
// This preserves referential integrity for leads, tasks, etc.
$result = fn_core_database_query(
    "UPDATE users SET status = 'deleted', email = CONCAT(email, '_deleted_', ?) WHERE user_id = ?",
    [time(), $delete_user_id]
);

if ($result) {
    header("Location: /users?deleted=1");
} else {
    header("Location: /users?error=delete_failed");
}
exit;
