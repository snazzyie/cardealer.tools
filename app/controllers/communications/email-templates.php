<?php
/**
 * Email Templates Management
 * CRUD interface for email templates
 */

// Security check
fn_check_security($user_id, $company_id);

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_template_id'])) {
    $templateId = intval($_POST['delete_template_id']);
    $success = fn_email_template_delete($templateId, $company_id);

    if ($success) {
        header("Location: /email-templates?deleted=1");
        exit;
    } else {
        $error = "Failed to delete template.";
    }
}

// Get all templates
$templates = fn_email_templates_get_all($company_id);

// Page header
$page_header = [
    'title' => 'Email Templates',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Email Templates' => ''
    ]
];

// Load view
require BASE_PATH . 'views/communications/email-templates.php';
