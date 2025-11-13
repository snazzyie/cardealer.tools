<?php
/**
 * Website Settings Controller
 * Manage public website settings
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Only admin can manage website settings
if ($permission < 2) {
    header("Location: /dash");
    exit;
}

$error = null;
$success = null;

// Handle settings update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $settingsData = [
        'website_enabled' => isset($_POST['website_enabled']) ? 1 : 0,
        'homepage_title' => trim($_POST['homepage_title'] ?? ''),
        'homepage_subtitle' => trim($_POST['homepage_subtitle'] ?? ''),
        'about_text' => trim($_POST['about_text'] ?? ''),
        'contact_email' => trim($_POST['contact_email'] ?? ''),
        'contact_phone' => trim($_POST['contact_phone'] ?? ''),
        'facebook_url' => trim($_POST['facebook_url'] ?? ''),
        'twitter_url' => trim($_POST['twitter_url'] ?? ''),
        'instagram_url' => trim($_POST['instagram_url'] ?? ''),
        'linkedin_url' => trim($_POST['linkedin_url'] ?? ''),
        'google_analytics_id' => trim($_POST['google_analytics_id'] ?? ''),
        'facebook_pixel_id' => trim($_POST['facebook_pixel_id'] ?? ''),
        'show_prices' => isset($_POST['show_prices']) ? 1 : 0,
        'show_financing' => isset($_POST['show_financing']) ? 1 : 0,
        'allow_enquiries' => isset($_POST['allow_enquiries']) ? 1 : 0,
        'allow_test_drives' => isset($_POST['allow_test_drives']) ? 1 : 0,
        'seo_title' => trim($_POST['seo_title'] ?? ''),
        'seo_description' => trim($_POST['seo_description'] ?? ''),
        'seo_keywords' => trim($_POST['seo_keywords'] ?? '')
    ];

    // Check if website settings exist
    $existing = fn_core_database_row(
        "SELECT setting_id FROM website_settings WHERE company_id = ?",
        [$company_id]
    );

    if ($existing) {
        // Update existing settings
        $result = fn_core_update_row('website_settings', $existing['setting_id'], $settingsData, 'setting_id');
    } else {
        // Create new settings
        $settingsData['company_id'] = $company_id;
        $settingsData['created_date'] = date('Y-m-d H:i:s');
        $result = fn_core_create_row('website_settings', $settingsData, 'setting_id');
    }

    if ($result) {
        $success = 'Website settings updated successfully.';
    } else {
        $error = 'Failed to update website settings.';
    }
}

// Get current website settings
$website_settings = fn_core_database_row(
    "SELECT * FROM website_settings WHERE company_id = ?",
    [$company_id]
);

// Default values if no settings exist
if (!$website_settings) {
    $website_settings = [
        'website_enabled' => 1,
        'homepage_title' => '',
        'homepage_subtitle' => '',
        'about_text' => '',
        'contact_email' => '',
        'contact_phone' => '',
        'facebook_url' => '',
        'twitter_url' => '',
        'instagram_url' => '',
        'linkedin_url' => '',
        'google_analytics_id' => '',
        'facebook_pixel_id' => '',
        'show_prices' => 1,
        'show_financing' => 1,
        'allow_enquiries' => 1,
        'allow_test_drives' => 1,
        'seo_title' => '',
        'seo_description' => '',
        'seo_keywords' => ''
    ];
}

// Get company data
$company_data = fn_company_get($company_id);

// Website URL
$website_url = '';
if (!empty($company_data['domain'])) {
    $website_url = 'https://' . $company_data['domain'];
} elseif (!empty($company_data['subdomain'])) {
    $website_url = 'https://' . $company_data['subdomain'] . '.cardealer.tools';
}

// Page header data
$page_header = [
    'title' => 'Website Settings',
    'subtitle' => 'Configure your public website',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Website Settings' => ''
    ]
];

// Load view
require BASE_PATH . 'views/website/settings.php';
