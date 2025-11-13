<?php
/**
 * Email Template Form
 * Add or edit email templates
 */

// Security check
fn_check_security($user_id, $company_id);

// Get template ID if editing
$templateId = isset($_GET['id']) ? intval($_GET['id']) : null;
$isEdit = !empty($templateId);

// Get existing template data if editing
$template = null;
if ($isEdit) {
    $template = fn_email_template_get($templateId, $company_id);
    if (!$template) {
        header("Location: /email-templates?error=not_found");
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'template_name' => trim($_POST['template_name'] ?? ''),
        'template_slug' => trim($_POST['template_slug'] ?? ''),
        'template_subject' => trim($_POST['template_subject'] ?? ''),
        'template_body' => trim($_POST['template_body'] ?? ''),
        'template_variables' => trim($_POST['template_variables'] ?? ''),
        'is_active' => isset($_POST['is_active']) ? 1 : 0
    ];

    // Validate
    if (empty($data['template_name']) || empty($data['template_subject']) || empty($data['template_body'])) {
        $error = "Please fill in all required fields.";
    } else {
        // Auto-generate slug if empty
        if (empty($data['template_slug'])) {
            $data['template_slug'] = strtolower(str_replace(' ', '-', preg_replace('/[^a-z0-9 ]/i', '', $data['template_name'])));
        }

        if ($isEdit) {
            $success = fn_email_template_update($templateId, $company_id, $data);
            $redirectParam = $success ? 'updated=1' : 'error=update_failed';
        } else {
            $newTemplateId = fn_email_template_create($company_id, $data);
            $success = $newTemplateId !== false;
            $redirectParam = $success ? 'created=1' : 'error=create_failed';
        }

        if ($success) {
            header("Location: /email-templates?{$redirectParam}");
            exit;
        } else {
            $error = "Failed to save template.";
        }
    }
}

// Page header
$page_header = [
    'title' => $isEdit ? 'Edit Email Template' : 'Add Email Template',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Email Templates' => '/email-templates',
        $isEdit ? 'Edit Template' : 'Add Template' => ''
    ]
];

// Load view
require BASE_PATH . 'views/communications/email-template-form.php';
