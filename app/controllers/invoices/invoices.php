<?php
/**
 * Invoices List Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get invoice type from query
$type = $_GET['type'] ?? 'sales';

// Get invoices based on type
if ($type === 'service') {
    $invoices = fn_invoices_get_service($company_id);
} else {
    $invoices = fn_invoices_get_sales($company_id);
}

// Get stats
$stats = fn_invoices_get_stats($company_id);

$page_header = [
    'title' => 'Invoices',
    'subtitle' => 'Manage sales and service invoices'
];

require BASE_PATH . 'views/invoices/invoices.php';
