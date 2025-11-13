<?php
/**
 * Core Session Functions
 *
 * Handles user authentication, session management, and permissions
 *
 * User Types:
 * 0 = Guest (not logged in)
 * 1 = Registered (needs company)
 * 2 = Company Created (needs subscription)
 * 3 = Paid Subscriber (full access)
 * 10 = Super Admin
 */

/**
 * Initialize user session
 * Sets default values if not already set
 */
function fn_core_session_initialise_session() {
    $config = require BASE_PATH . 'config.php';

    // Set default user type if not set
    if (!isset($_SESSION['user_type'])) {
        $_SESSION['user_type'] = 0; // Guest
    }

    // Check session timeout
    if (isset($_SESSION['last_activity'])) {
        $timeout = $config['session']['timeout'];
        if (time() - $_SESSION['last_activity'] > $timeout) {
            // Session expired
            session_unset();
            session_destroy();
            session_start();
            $_SESSION['user_type'] = 0;
            $_SESSION['message'] = 'Your session has expired. Please log in again.';
        }
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();
}

/**
 * Log user in
 *
 * @param string $email User email
 * @param string $password Plain text password
 * @return bool Success status
 */
function fn_core_session_log_me_in($email, $password) {
    // Get user from database
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $user = fn_core_database_row($query, ['email' => $email]);

    if (!$user) {
        return false;
    }

    // Verify password
    if (!password_verify($password, $user['password_hash'])) {
        return false;
    }

    // Set session variables
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['first_name'] = $user['first_name'];
    $_SESSION['last_name'] = $user['last_name'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['user_role'] = $user['user_role'];
    $_SESSION['company_id'] = $user['company_id'];
    $_SESSION['last_activity'] = time();

    // Regenerate session ID for security
    session_regenerate_id(true);

    // Update last login timestamp
    $updateQuery = "UPDATE users SET last_login = NOW() WHERE user_id = :user_id";
    fn_core_execute_query($updateQuery, ['user_id' => $user['user_id']]);

    return true;
}

/**
 * Log user out
 */
function fn_core_session_logout() {
    session_unset();
    session_destroy();
    session_start();
    $_SESSION['user_type'] = 0;
    header("Location: /login");
    exit;
}

/**
 * Register new user
 *
 * @param array $data User registration data
 * @return int|false User ID or false on failure
 */
function fn_core_session_register_user($data) {
    // Check if email already exists
    $checkQuery = "SELECT user_id FROM users WHERE email = :email LIMIT 1";
    $existing = fn_core_database_row($checkQuery, ['email' => $data['email']]);

    if ($existing) {
        return false; // Email already registered
    }

    // Hash password
    $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

    // Generate email verification code
    $activationCode = bin2hex(random_bytes(25));

    // Insert user
    $query = "INSERT INTO users (
        email,
        password_hash,
        first_name,
        last_name,
        phone,
        user_type,
        email_activation_code,
        created_date
    ) VALUES (
        :email,
        :password_hash,
        :first_name,
        :last_name,
        :phone,
        1,
        :activation_code,
        NOW()
    )";

    $params = [
        'email' => $data['email'],
        'password_hash' => $passwordHash,
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'phone' => $data['phone'] ?? null,
        'activation_code' => $activationCode
    ];

    $userId = fn_core_insert_row_no_redirect($query, $params);

    // TODO: Send welcome email with activation link
    // fn_email_send_welcome($userId);

    return $userId;
}

/**
 * Get user data by email
 *
 * @param string $email User email
 * @return array|false User data or false
 */
function fn_core_session_get_user_data($email) {
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    return fn_core_database_row($query, ['email' => $email]);
}

/**
 * Get user data by ID
 *
 * @param int $userId User ID
 * @return array|false User data or false
 */
function fn_core_session_get_user_data_by_id($userId) {
    $query = "SELECT * FROM users WHERE user_id = :user_id LIMIT 1";
    return fn_core_database_row($query, ['user_id' => $userId]);
}

/**
 * Get all users for a company
 *
 * @param int $companyId Company ID
 * @param int $offset Pagination offset
 * @param int $limit Pagination limit
 * @return array Users array
 */
function fn_core_session_all_users_company_id($companyId, $offset = 0, $limit = 20) {
    $query = "SELECT * FROM users
              WHERE company_id = :company_id
              ORDER BY created_date DESC
              LIMIT :limit OFFSET :offset";

    $pdo = fn_core_database_connection();
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':company_id', $companyId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

/**
 * Reset password
 *
 * @param string $email User email
 * @return bool Success status
 */
function fn_core_session_reset_password($email) {
    // Check if user exists
    $user = fn_core_session_get_user_data($email);
    if (!$user) {
        return false;
    }

    // Generate reset code
    $resetCode = bin2hex(random_bytes(25));

    // Update user with reset code
    $query = "UPDATE users
              SET email_activation_code = :reset_code
              WHERE email = :email";

    fn_core_execute_query($query, [
        'reset_code' => $resetCode,
        'email' => $email
    ]);

    // TODO: Send password reset email
    // fn_email_send_password_reset($user['user_id'], $resetCode);

    return true;
}

/**
 * Update password with reset code
 *
 * @param string $resetCode Reset code
 * @param string $newPassword New password
 * @return bool Success status
 */
function fn_core_session_update_password_with_code($resetCode, $newPassword) {
    // Find user with reset code
    $query = "SELECT user_id FROM users WHERE email_activation_code = :reset_code LIMIT 1";
    $user = fn_core_database_row($query, ['reset_code' => $resetCode]);

    if (!$user) {
        return false;
    }

    // Hash new password
    $passwordHash = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update password and clear reset code
    $updateQuery = "UPDATE users
                    SET password_hash = :password_hash,
                        email_activation_code = NULL
                    WHERE user_id = :user_id";

    fn_core_execute_query($updateQuery, [
        'password_hash' => $passwordHash,
        'user_id' => $user['user_id']
    ]);

    return true;
}

/**
 * Check if user has permission to access resource
 *
 * @param int $userId User ID
 * @param int $companyId Company ID to check against
 * @return bool True if user has access
 */
function fn_check_security($userId, $companyId) {
    $user = fn_core_session_get_user_data_by_id($userId);

    if (!$user) {
        return false;
    }

    // Super admin can access everything
    if ($user['user_type'] == 10) {
        return true;
    }

    // User must belong to the company
    return $user['company_id'] == $companyId;
}

/**
 * Require login - redirect if not logged in
 *
 * @param int $minimumUserType Minimum user type required (default 1)
 */
function fn_require_login($minimumUserType = 1) {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] < $minimumUserType) {
        header("Location: /login");
        exit;
    }
}

/**
 * Require subscription - redirect if not paid
 */
function fn_require_subscription() {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] < 3) {
        header("Location: /subscriptions");
        exit;
    }
}

/**
 * Require super admin access
 */
function fn_require_super_admin() {
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 10) {
        header("Location: /error/403");
        exit;
    }
}

/**
 * Get gravatar URL for user
 *
 * @param string $email User email
 * @param int $size Image size
 * @return string Gravatar URL
 */
function fn_get_gravatar($email, $size = 80) {
    $hash = md5(strtolower(trim($email)));
    return "https://www.gravatar.com/avatar/$hash?s=$size&d=mp";
}
