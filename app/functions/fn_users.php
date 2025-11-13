<?php
/**
 * User Management Functions
 *
 * Handle user accounts, team members, and staff management
 */

/**
 * Get all users for a company
 *
 * @param int $companyId Company ID
 * @param array $filters Optional filters (status, user_type, role)
 * @return array Users
 */
function fn_users_get_all($companyId, $filters = []) {
    $query = "SELECT user_id, first_name, last_name, email, user_type, user_role,
              status, created_date, last_login, phone, profile_image
              FROM users
              WHERE company_id = ?";

    $params = [$companyId];

    // Apply filters
    if (!empty($filters['status'])) {
        $query .= " AND status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['user_type'])) {
        $query .= " AND user_type = ?";
        $params[] = $filters['user_type'];
    }

    if (!empty($filters['user_role'])) {
        $query .= " AND user_role = ?";
        $params[] = $filters['user_role'];
    }

    $query .= " ORDER BY created_date DESC";

    return fn_core_database_rows($query, $params);
}

/**
 * Get user by ID
 *
 * @param int $userId User ID
 * @param int $companyId Company ID (for security)
 * @return array|null User data
 */
function fn_users_get($userId, $companyId) {
    $query = "SELECT user_id, first_name, last_name, email, user_type, user_role,
              status, created_date, last_login, phone, profile_image
              FROM users
              WHERE user_id = ? AND company_id = ?";
    return fn_core_database_row($query, [$userId, $companyId]);
}

/**
 * Get user by email
 *
 * @param string $email Email address
 * @param int $companyId Company ID
 * @return array|null User data
 */
function fn_users_get_by_email($email, $companyId) {
    $query = "SELECT user_id, first_name, last_name, email, user_type, user_role,
              status, created_date, last_login, phone, profile_image
              FROM users
              WHERE email = ? AND company_id = ?";
    return fn_core_database_row($query, [$email, $companyId]);
}

/**
 * Create user
 *
 * @param int $companyId Company ID
 * @param array $data User data
 * @return int|false User ID or false
 */
function fn_users_create($companyId, $data) {
    // Check if email already exists in this company
    $existing = fn_users_get_by_email($data['email'], $companyId);
    if ($existing) {
        return false;
    }

    // Hash password
    $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (
        company_id, first_name, last_name, email, password_hash,
        user_type, user_role, phone, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['first_name'],
        $data['last_name'] ?? '',
        $data['email'],
        $passwordHash,
        $data['user_type'] ?? 1, // Default: regular user
        $data['user_role'] ?? 'staff',
        $data['phone'] ?? null,
        $data['status'] ?? 'active'
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update user
 *
 * @param int $userId User ID
 * @param int $companyId Company ID (for security)
 * @param array $data User data
 * @return bool Success
 */
function fn_users_update($userId, $companyId, $data) {
    $query = "UPDATE users SET
        first_name = ?, last_name = ?, email = ?,
        user_type = ?, user_role = ?, phone = ?, status = ?
    WHERE user_id = ? AND company_id = ?";

    $params = [
        $data['first_name'],
        $data['last_name'] ?? '',
        $data['email'],
        $data['user_type'] ?? 1,
        $data['user_role'] ?? 'staff',
        $data['phone'] ?? null,
        $data['status'] ?? 'active',
        $userId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update user password
 *
 * @param int $userId User ID
 * @param int $companyId Company ID
 * @param string $newPassword New password
 * @return bool Success
 */
function fn_users_update_password($userId, $companyId, $newPassword) {
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);

    $query = "UPDATE users SET password_hash = ? WHERE user_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$passwordHash, $userId, $companyId]);
}

/**
 * Update user status
 *
 * @param int $userId User ID
 * @param int $companyId Company ID
 * @param string $status Status (active, inactive, suspended)
 * @return bool Success
 */
function fn_users_update_status($userId, $companyId, $status) {
    $query = "UPDATE users SET status = ? WHERE user_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$status, $userId, $companyId]);
}

/**
 * Delete user (soft delete by setting status to inactive)
 *
 * @param int $userId User ID
 * @param int $companyId Company ID (for security)
 * @return bool Success
 */
function fn_users_delete($userId, $companyId) {
    // Soft delete - set status to inactive
    return fn_users_update_status($userId, $companyId, 'inactive');
}

/**
 * Hard delete user (permanent deletion)
 *
 * @param int $userId User ID
 * @param int $companyId Company ID (for security)
 * @return bool Success
 */
function fn_users_hard_delete($userId, $companyId) {
    $query = "DELETE FROM users WHERE user_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$userId, $companyId]);
}

/**
 * Get active users count
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_users_count_active($companyId) {
    $query = "SELECT COUNT(*) as count FROM users WHERE company_id = ? AND status = 'active'";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Get total users count
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_users_count_total($companyId) {
    $query = "SELECT COUNT(*) as count FROM users WHERE company_id = ?";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Get admins count
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_users_count_admins($companyId) {
    $query = "SELECT COUNT(*) as count FROM users WHERE company_id = ? AND user_type >= 2";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Get users by role
 *
 * @param int $companyId Company ID
 * @param string $role User role
 * @return array Users
 */
function fn_users_get_by_role($companyId, $role) {
    $query = "SELECT user_id, first_name, last_name, email, user_type, user_role, status
              FROM users
              WHERE company_id = ? AND user_role = ? AND status = 'active'
              ORDER BY first_name, last_name";
    return fn_core_database_rows($query, [$companyId, $role]);
}

/**
 * Get sales users
 *
 * @param int $companyId Company ID
 * @return array Sales users
 */
function fn_users_get_sales_team($companyId) {
    $query = "SELECT user_id, first_name, last_name, email
              FROM users
              WHERE company_id = ?
              AND user_role IN ('sales', 'manager', 'admin', 'owner')
              AND status = 'active'
              ORDER BY first_name, last_name";
    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Get service users
 *
 * @param int $companyId Company ID
 * @return array Service users
 */
function fn_users_get_service_team($companyId) {
    $query = "SELECT user_id, first_name, last_name, email
              FROM users
              WHERE company_id = ?
              AND user_role IN ('service', 'manager', 'admin', 'owner')
              AND status = 'active'
              ORDER BY first_name, last_name";
    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Update last login timestamp
 *
 * @param int $userId User ID
 * @return bool Success
 */
function fn_users_update_last_login($userId) {
    $query = "UPDATE users SET last_login = NOW() WHERE user_id = ?";
    return fn_core_edit_row_no_redirect($query, [$userId]);
}

/**
 * Check if user has permission
 *
 * @param int $userId User ID
 * @param int $companyId Company ID
 * @param int $requiredLevel Required permission level (1=user, 2=admin, 3=super admin)
 * @return bool Has permission
 */
function fn_users_has_permission($userId, $companyId, $requiredLevel = 1) {
    $user = fn_users_get($userId, $companyId);

    if (!$user || $user['status'] !== 'active') {
        return false;
    }

    return $user['user_type'] >= $requiredLevel;
}

/**
 * Get user statistics
 *
 * @param int $userId User ID
 * @param int $companyId Company ID
 * @return array Stats
 */
function fn_users_get_stats($userId, $companyId) {
    $stats = [];

    // Leads assigned
    $query = "SELECT COUNT(*) as count FROM crm_leads
              WHERE assigned_to = ? AND company_id = ?";
    $result = fn_core_database_row($query, [$userId, $companyId]);
    $stats['leads_assigned'] = $result['count'] ?? 0;

    // Tasks assigned
    $query = "SELECT COUNT(*) as count FROM crm_tasks
              WHERE assigned_to = ? AND company_id = ?";
    $result = fn_core_database_row($query, [$userId, $companyId]);
    $stats['tasks_assigned'] = $result['count'] ?? 0;

    // Appointments assigned
    $query = "SELECT COUNT(*) as count FROM calendar_appointments
              WHERE assigned_to = ? AND company_id = ?";
    $result = fn_core_database_row($query, [$userId, $companyId]);
    $stats['appointments_assigned'] = $result['count'] ?? 0;

    return $stats;
}

/**
 * Search users
 *
 * @param int $companyId Company ID
 * @param string $searchTerm Search term
 * @param int $limit Limit
 * @return array Users
 */
function fn_users_search($companyId, $searchTerm, $limit = 50) {
    $query = "SELECT user_id, first_name, last_name, email, user_type, user_role, status
              FROM users
              WHERE company_id = ?
              AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)
              ORDER BY first_name, last_name
              LIMIT ?";

    $term = '%' . $searchTerm . '%';
    return fn_core_database_rows($query, [$companyId, $term, $term, $term, $limit]);
}

/**
 * Get user role label
 *
 * @param string $role User role code
 * @return string Role label
 */
function fn_users_get_role_label($role) {
    $roles = [
        'owner' => 'Owner',
        'admin' => 'Administrator',
        'manager' => 'Manager',
        'sales' => 'Sales Person',
        'service' => 'Service Advisor',
        'staff' => 'Staff Member'
    ];

    return $roles[$role] ?? 'Staff Member';
}

/**
 * Get available user roles
 *
 * @return array User roles
 */
function fn_users_get_available_roles() {
    return [
        'owner' => 'Owner',
        'admin' => 'Administrator',
        'manager' => 'Manager',
        'sales' => 'Sales Person',
        'service' => 'Service Advisor',
        'staff' => 'Staff Member'
    ];
}

/**
 * Update user profile image
 *
 * @param int $userId User ID
 * @param int $companyId Company ID
 * @param string $imageUrl Image URL
 * @return bool Success
 */
function fn_users_update_profile_image($userId, $companyId, $imageUrl) {
    $query = "UPDATE users SET profile_image = ? WHERE user_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$imageUrl, $userId, $companyId]);
}

/**
 * Check if email is available
 *
 * @param string $email Email address
 * @param int $companyId Company ID
 * @param int $excludeUserId Exclude this user ID (for updates)
 * @return bool True if available
 */
function fn_users_email_available($email, $companyId, $excludeUserId = null) {
    $query = "SELECT COUNT(*) as count FROM users WHERE email = ? AND company_id = ?";
    $params = [$email, $companyId];

    if ($excludeUserId) {
        $query .= " AND user_id != ?";
        $params[] = $excludeUserId;
    }

    $result = fn_core_database_row($query, $params);
    return ($result['count'] ?? 0) == 0;
}

/**
 * Activate user account
 *
 * @param int $userId User ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_users_activate($userId, $companyId) {
    return fn_users_update_status($userId, $companyId, 'active');
}

/**
 * Deactivate user account
 *
 * @param int $userId User ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_users_deactivate($userId, $companyId) {
    return fn_users_update_status($userId, $companyId, 'inactive');
}

/**
 * Suspend user account
 *
 * @param int $userId User ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_users_suspend($userId, $companyId) {
    return fn_users_update_status($userId, $companyId, 'suspended');
}
