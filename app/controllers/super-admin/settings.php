<?php
/**
 * Super Admin - Platform Settings Controller
 * Manage global platform settings
 */

// Super admin check (user_type 3)
fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$permission = $user_data['user_type'];

if ($permission < 3) {
    header("Location: /dash");
    exit;
}

$error = null;
$success = null;

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $setting_key = $_POST['setting_key'] ?? '';
    $setting_value = $_POST['setting_value'] ?? '';

    if (!empty($setting_key)) {
        // Update or insert setting
        $existing = fn_core_database_row(
            "SELECT setting_id FROM platform_settings WHERE setting_key = ?",
            [$setting_key]
        );

        if ($existing) {
            $result = fn_core_database_query(
                "UPDATE platform_settings SET setting_value = ?, updated_date = NOW() WHERE setting_key = ?",
                [$setting_value, $setting_key]
            );
        } else {
            $result = fn_core_database_query(
                "INSERT INTO platform_settings (setting_key, setting_value, created_date) VALUES (?, ?, NOW())",
                [$setting_key, $setting_value]
            );
        }

        if ($result) {
            $success = 'Setting updated successfully.';
        } else {
            $error = 'Failed to update setting.';
        }
    }
}

// Get all platform settings
$all_settings = fn_core_database_rows(
    "SELECT * FROM platform_settings ORDER BY setting_key",
    []
);

// Convert to key-value array
$settings = [];
foreach ($all_settings as $setting) {
    $settings[$setting['setting_key']] = $setting['setting_value'];
}

// Default settings structure
$setting_groups = [
    'General' => [
        'platform_name' => $settings['platform_name'] ?? 'Car Dealer SaaS',
        'platform_email' => $settings['platform_email'] ?? '',
        'support_email' => $settings['support_email'] ?? '',
        'platform_url' => $settings['platform_url'] ?? ''
    ],
    'Features' => [
        'allow_registrations' => $settings['allow_registrations'] ?? '1',
        'trial_days' => $settings['trial_days'] ?? '14',
        'max_users_per_dealer' => $settings['max_users_per_dealer'] ?? '10',
        'max_vehicles_per_dealer' => $settings['max_vehicles_per_dealer'] ?? '1000',
        'storage_limit_mb' => $settings['storage_limit_mb'] ?? '500'
    ],
    'Email' => [
        'smtp_enabled' => $settings['smtp_enabled'] ?? '0',
        'smtp_host' => $settings['smtp_host'] ?? '',
        'smtp_port' => $settings['smtp_port'] ?? '587',
        'smtp_username' => $settings['smtp_username'] ?? '',
        'smtp_from_name' => $settings['smtp_from_name'] ?? 'Car Dealer SaaS'
    ],
    'Stripe' => [
        'stripe_enabled' => $settings['stripe_enabled'] ?? '0',
        'stripe_mode' => $settings['stripe_mode'] ?? 'test',
        'stripe_webhook_secret' => $settings['stripe_webhook_secret'] ?? ''
    ],
    'Maintenance' => [
        'maintenance_mode' => $settings['maintenance_mode'] ?? '0',
        'maintenance_message' => $settings['maintenance_message'] ?? 'The platform is currently undergoing maintenance.'
    ]
];

// Get platform statistics
$platform_stats = [
    'total_companies' => fn_core_database_row("SELECT COUNT(*) as total FROM companies", [])['total'] ?? 0,
    'active_companies' => fn_core_database_row(
        "SELECT COUNT(DISTINCT s.company_id) as total FROM subscriptions s WHERE s.status = 'active'",
        []
    )['total'] ?? 0,
    'total_users' => fn_core_database_row("SELECT COUNT(*) as total FROM users", [])['total'] ?? 0,
    'total_vehicles' => fn_core_database_row("SELECT COUNT(*) as total FROM vehicles", [])['total'] ?? 0,
    'total_leads' => fn_core_database_row("SELECT COUNT(*) as total FROM crm_leads", [])['total'] ?? 0,
    'total_revenue' => fn_core_database_row(
        "SELECT SUM(sp.price) as total
         FROM subscriptions s
         JOIN subscription_plans sp ON s.plan_id = sp.plan_id
         WHERE s.status = 'active'",
        []
    )['total'] ?? 0
];

// Get recent signups
$recent_signups = fn_core_database_rows(
    "SELECT c.company_id, c.company_name, c.created_date, u.first_name, u.last_name, u.email
     FROM companies c
     LEFT JOIN users u ON c.company_id = u.company_id AND u.user_type = 2
     ORDER BY c.created_date DESC
     LIMIT 10",
    []
);

// Page header data
$page_header = [
    'title' => 'Platform Settings',
    'subtitle' => 'Manage global platform configuration',
    'breadcrumbs' => [
        'Super Admin' => '/super-admin',
        'Settings' => ''
    ]
];

// Load view
require BASE_PATH . 'views/super-admin/settings.php';
