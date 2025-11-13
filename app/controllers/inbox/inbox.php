<?php
/**
 * Unified Inbox Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get all communications from different sources
$query = "SELECT
    'enquiry' as type,
    e.enquiry_id as id,
    e.first_name,
    e.last_name,
    e.email,
    e.phone,
    e.message as content,
    e.created_date as date,
    v.make,
    v.model,
    v.year
FROM enquiries e
LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
WHERE e.company_id = ?

UNION ALL

SELECT
    'lead' as type,
    l.lead_id as id,
    l.first_name,
    l.last_name,
    l.email,
    l.phone,
    l.notes as content,
    l.created_date as date,
    v.make,
    v.model,
    v.year
FROM crm_leads l
LEFT JOIN vehicles v ON l.vehicle_id = v.vehicle_id
WHERE l.company_id = ? AND l.status = 'new'

ORDER BY date DESC
LIMIT 100";

$communications = fn_core_database_rows($query, [$company_id, $company_id]);

// Get stats
$enquiries_count = count(array_filter($communications, function($c) { return $c['type'] === 'enquiry'; }));
$leads_count = count(array_filter($communications, function($c) { return $c['type'] === 'lead'; }));

$page_header = [
    'title' => 'Unified Inbox',
    'subtitle' => 'All customer communications in one place'
];

require BASE_PATH . 'views/inbox/inbox.php';
