<?php
/**
 * CRM & Lead Management Functions
 *
 * Handle lead management and sales pipeline
 */

/**
 * Get all leads for a company
 *
 * @param int $companyId Company ID
 * @param array $filters Optional filters
 * @param int $limit Limit
 * @param int $offset Offset
 * @return array Leads
 */
function fn_crm_get_leads($companyId, $filters = [], $limit = 100, $offset = 0) {
    $query = "SELECT l.*, v.make, v.model, v.year, v.price,
              u.first_name as assigned_first_name, u.last_name as assigned_last_name
              FROM crm_leads l
              LEFT JOIN vehicles v ON l.vehicle_id = v.vehicle_id
              LEFT JOIN users u ON l.assigned_to = u.user_id
              WHERE l.company_id = ?";

    $params = [$companyId];

    // Apply filters
    if (!empty($filters['status'])) {
        $query .= " AND l.status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['source'])) {
        $query .= " AND l.source = ?";
        $params[] = $filters['source'];
    }

    if (!empty($filters['assigned_to'])) {
        $query .= " AND l.assigned_to = ?";
        $params[] = $filters['assigned_to'];
    }

    if (!empty($filters['search'])) {
        $query .= " AND (l.first_name LIKE ? OR l.last_name LIKE ? OR l.email LIKE ? OR l.phone LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $query .= " ORDER BY l.last_contact DESC, l.created_date DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    return fn_core_database_rows($query, $params);
}

/**
 * Get leads grouped by pipeline stage
 *
 * @param int $companyId Company ID
 * @return array Leads grouped by status
 */
function fn_crm_get_pipeline($companyId) {
    $statuses = ['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'won', 'lost'];
    $pipeline = [];

    foreach ($statuses as $status) {
        $query = "SELECT l.*, v.make, v.model, v.year, v.price,
                  u.first_name as assigned_first_name, u.last_name as assigned_last_name
                  FROM crm_leads l
                  LEFT JOIN vehicles v ON l.vehicle_id = v.vehicle_id
                  LEFT JOIN users u ON l.assigned_to = u.user_id
                  WHERE l.company_id = ? AND l.status = ?
                  ORDER BY l.created_date DESC";

        $pipeline[$status] = fn_core_database_rows($query, [$companyId, $status]);
    }

    return $pipeline;
}

/**
 * Get lead by ID
 *
 * @param int $leadId Lead ID
 * @param int $companyId Company ID (for security)
 * @return array|null Lead data
 */
function fn_crm_get_lead($leadId, $companyId) {
    $query = "SELECT l.*, v.make, v.model, v.year, v.price
              FROM crm_leads l
              LEFT JOIN vehicles v ON l.vehicle_id = v.vehicle_id
              WHERE l.lead_id = ? AND l.company_id = ?";
    return fn_core_database_row($query, [$leadId, $companyId]);
}

/**
 * Create lead
 *
 * @param int $companyId Company ID
 * @param array $data Lead data
 * @return int|false Lead ID or false
 */
function fn_crm_create_lead($companyId, $data) {
    $query = "INSERT INTO crm_leads (
        company_id, vehicle_id, first_name, last_name, email, phone,
        source, status, assigned_to, notes, last_contact
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $params = [
        $companyId,
        $data['vehicle_id'] ?? null,
        $data['first_name'],
        $data['last_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? '',
        $data['source'] ?? 'website',
        $data['status'] ?? 'new',
        $data['assigned_to'] ?? null,
        $data['notes'] ?? ''
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update lead
 *
 * @param int $leadId Lead ID
 * @param int $companyId Company ID (for security)
 * @param array $data Lead data
 * @return bool Success
 */
function fn_crm_update_lead($leadId, $companyId, $data) {
    $query = "UPDATE crm_leads SET
        vehicle_id = ?, first_name = ?, last_name = ?, email = ?, phone = ?,
        source = ?, status = ?, assigned_to = ?, notes = ?, last_contact = NOW()
    WHERE lead_id = ? AND company_id = ?";

    $params = [
        $data['vehicle_id'] ?? null,
        $data['first_name'],
        $data['last_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? '',
        $data['source'] ?? 'website',
        $data['status'] ?? 'new',
        $data['assigned_to'] ?? null,
        $data['notes'] ?? '',
        $leadId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update lead status
 *
 * @param int $leadId Lead ID
 * @param int $companyId Company ID
 * @param string $status New status
 * @return bool Success
 */
function fn_crm_update_lead_status($leadId, $companyId, $status) {
    $query = "UPDATE crm_leads SET status = ?, last_contact = NOW() WHERE lead_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$status, $leadId, $companyId]);
}

/**
 * Delete lead
 *
 * @param int $leadId Lead ID
 * @param int $companyId Company ID (for security)
 * @return bool Success
 */
function fn_crm_delete_lead($leadId, $companyId) {
    $query = "DELETE FROM crm_leads WHERE lead_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$leadId, $companyId]);
}

/**
 * Add lead activity/note
 *
 * @param int $leadId Lead ID
 * @param int $userId User ID
 * @param string $activityType Activity type
 * @param string $notes Notes
 * @return int|false Activity ID or false
 */
function fn_crm_add_activity($leadId, $userId, $activityType, $notes) {
    $query = "INSERT INTO crm_activities (lead_id, user_id, activity_type, notes, activity_date)
              VALUES (?, ?, ?, ?, NOW())";
    return fn_core_insert_row_no_redirect($query, [$leadId, $userId, $activityType, $notes]);
}

/**
 * Get lead activities
 *
 * @param int $leadId Lead ID
 * @return array Activities
 */
function fn_crm_get_activities($leadId) {
    $query = "SELECT a.*, u.first_name, u.last_name
              FROM crm_activities a
              LEFT JOIN users u ON a.user_id = u.user_id
              WHERE a.lead_id = ?
              ORDER BY a.activity_date DESC";
    return fn_core_database_rows($query, [$leadId]);
}

/**
 * Get lead count by status
 *
 * @param int $companyId Company ID
 * @param string $status Status
 * @return int Count
 */
function fn_crm_count_by_status($companyId, $status) {
    $query = "SELECT COUNT(*) as count FROM crm_leads WHERE company_id = ? AND status = ?";
    $result = fn_core_database_row($query, [$companyId, $status]);
    return $result['count'];
}

/**
 * Get conversion rate
 *
 * @param int $companyId Company ID
 * @return float Conversion rate percentage
 */
function fn_crm_get_conversion_rate($companyId) {
    $total = fn_crm_count_by_status($companyId, 'won')
        + fn_crm_count_by_status($companyId, 'lost');

    if ($total == 0) {
        return 0;
    }

    $won = fn_crm_count_by_status($companyId, 'won');
    return round(($won / $total) * 100, 1);
}
