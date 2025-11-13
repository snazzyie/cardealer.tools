<?php
/**
 * Company Branding Controller
 */

fn_require_login(2);

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'logo_url' => trim($_POST['logo_url'] ?? ''),
        'favicon_url' => trim($_POST['favicon_url'] ?? ''),
        'primary_color' => trim($_POST['primary_color'] ?? '#007bff'),
        'secondary_color' => trim($_POST['secondary_color'] ?? '#6c757d'),
        'social_facebook' => trim($_POST['social_facebook'] ?? ''),
        'social_instagram' => trim($_POST['social_instagram'] ?? ''),
        'social_twitter' => trim($_POST['social_twitter'] ?? ''),
        'social_linkedin' => trim($_POST['social_linkedin'] ?? '')
    ];

    $result = fn_company_update_branding($company_id, $data);

    if ($result) {
        $success = 'Branding updated successfully.';
    } else {
        $error = 'Failed to update branding.';
    }
}

// Get company data
$company_data = fn_company_get($company_id);

$page_header = [
    'title' => 'Company Branding',
    'subtitle' => 'Customize your company\'s visual identity'
];

require BASE_PATH . 'views/company/company-branding.php';
