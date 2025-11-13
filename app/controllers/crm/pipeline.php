<?php
/**
 * CRM Pipeline Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Handle AJAX status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $leadId = intval($_POST['lead_id']);
    $newStatus = $_POST['status'];

    $result = fn_crm_update_lead_status($leadId, $company_id, $newStatus);

    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

// Get pipeline data
$pipeline = fn_crm_get_pipeline($company_id);

// Get stats
$stats = [
    'total_leads' => fn_crm_count_by_status($company_id, 'new')
        + fn_crm_count_by_status($company_id, 'contacted')
        + fn_crm_count_by_status($company_id, 'qualified')
        + fn_crm_count_by_status($company_id, 'proposal')
        + fn_crm_count_by_status($company_id, 'negotiation'),
    'won' => fn_crm_count_by_status($company_id, 'won'),
    'lost' => fn_crm_count_by_status($company_id, 'lost'),
    'conversion_rate' => fn_crm_get_conversion_rate($company_id)
];

$page_header = [
    'title' => 'CRM Pipeline',
    'subtitle' => 'Manage your sales leads'
];

require BASE_PATH . 'views/crm/pipeline.php';
