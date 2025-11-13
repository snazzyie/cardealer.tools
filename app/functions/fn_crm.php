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

/**
 * ====================
 * TASK MANAGEMENT
 * ====================
 */

/**
 * Get all tasks for a user
 *
 * @param int $userId User ID
 * @param string $statusFilter Status filter (all, pending, completed)
 * @return array Tasks
 */
function fn_crm_get_user_tasks($userId, $statusFilter = 'all') {
    $query = "SELECT t.*, l.first_name as lead_first_name, l.last_name as lead_last_name,
              u.first_name as assigned_first_name, u.last_name as assigned_last_name
              FROM crm_tasks t
              LEFT JOIN crm_leads l ON t.lead_id = l.lead_id
              LEFT JOIN users u ON t.assigned_to = u.user_id
              WHERE t.assigned_to = ?";

    $params = [$userId];

    if ($statusFilter !== 'all') {
        $query .= " AND t.status = ?";
        $params[] = $statusFilter;
    }

    $query .= " ORDER BY t.due_date ASC, t.priority DESC, t.created_date DESC";

    return fn_core_database_rows($query, $params);
}

/**
 * Get all tasks for a company
 *
 * @param int $companyId Company ID
 * @param string $statusFilter Status filter (all, pending, completed)
 * @return array Tasks
 */
function fn_crm_get_company_tasks($companyId, $statusFilter = 'all') {
    $query = "SELECT t.*, l.first_name as lead_first_name, l.last_name as lead_last_name,
              u.first_name as assigned_first_name, u.last_name as assigned_last_name
              FROM crm_tasks t
              LEFT JOIN crm_leads l ON t.lead_id = l.lead_id
              LEFT JOIN users u ON t.assigned_to = u.user_id
              WHERE t.company_id = ?";

    $params = [$companyId];

    if ($statusFilter !== 'all') {
        $query .= " AND t.status = ?";
        $params[] = $statusFilter;
    }

    $query .= " ORDER BY t.due_date ASC, t.priority DESC, t.created_date DESC";

    return fn_core_database_rows($query, $params);
}

/**
 * Get task by ID
 *
 * @param int $taskId Task ID
 * @param int $companyId Company ID
 * @return array|null Task data
 */
function fn_crm_get_task($taskId, $companyId) {
    $query = "SELECT t.*, l.first_name as lead_first_name, l.last_name as lead_last_name,
              u.first_name as assigned_first_name, u.last_name as assigned_last_name
              FROM crm_tasks t
              LEFT JOIN crm_leads l ON t.lead_id = l.lead_id
              LEFT JOIN users u ON t.assigned_to = u.user_id
              WHERE t.task_id = ? AND t.company_id = ?";
    return fn_core_database_row($query, [$taskId, $companyId]);
}

/**
 * Create task
 *
 * @param array $data Task data (must include company_id)
 * @return int|false Task ID or false
 */
function fn_crm_create_task($data) {
    $query = "INSERT INTO crm_tasks (
        company_id, lead_id, assigned_to, task_type, title, description,
        due_date, priority, status, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $data['company_id'],
        $data['lead_id'] ?? null,
        $data['assigned_to'] ?? null,
        $data['task_type'] ?? 'call',
        $data['title'],
        $data['description'] ?? '',
        $data['due_date'] ?? null,
        $data['priority'] ?? 'medium',
        $data['status'] ?? 'pending',
        $data['created_by'] ?? null
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update task
 *
 * @param int $taskId Task ID
 * @param int $companyId Company ID
 * @param array $data Task data
 * @return bool Success
 */
function fn_crm_update_task($taskId, $companyId, $data) {
    $query = "UPDATE crm_tasks SET
        lead_id = ?, assigned_to = ?, task_type = ?, title = ?, description = ?,
        due_date = ?, priority = ?, status = ?
    WHERE task_id = ? AND company_id = ?";

    $params = [
        $data['lead_id'] ?? null,
        $data['assigned_to'] ?? null,
        $data['task_type'] ?? 'call',
        $data['title'],
        $data['description'] ?? '',
        $data['due_date'] ?? null,
        $data['priority'] ?? 'medium',
        $data['status'] ?? 'pending',
        $taskId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Delete task
 *
 * @param int $taskId Task ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_crm_delete_task($taskId, $companyId) {
    $query = "DELETE FROM crm_tasks WHERE task_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$taskId, $companyId]);
}

/**
 * Toggle task status (pending <-> completed)
 *
 * @param int $taskId Task ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_crm_toggle_task_status($taskId, $companyId) {
    // Get current status
    $task = fn_crm_get_task($taskId, $companyId);

    if (!$task) {
        return false;
    }

    $newStatus = ($task['status'] === 'completed') ? 'pending' : 'completed';
    $completedDate = ($newStatus === 'completed') ? date('Y-m-d H:i:s') : null;

    $query = "UPDATE crm_tasks SET status = ?, completed_date = ? WHERE task_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$newStatus, $completedDate, $taskId, $companyId]);
}

/**
 * Mark task as completed
 *
 * @param int $taskId Task ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_crm_complete_task($taskId, $companyId) {
    $query = "UPDATE crm_tasks SET status = 'completed', completed_date = NOW() WHERE task_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$taskId, $companyId]);
}

/**
 * Count user tasks by status
 *
 * @param int $userId User ID
 * @param string $status Status
 * @return int Count
 */
function fn_crm_count_user_tasks($userId, $status) {
    $query = "SELECT COUNT(*) as count FROM crm_tasks WHERE assigned_to = ? AND status = ?";
    $result = fn_core_database_row($query, [$userId, $status]);
    return $result['count'] ?? 0;
}

/**
 * Count company tasks by status
 *
 * @param int $companyId Company ID
 * @param string $status Status
 * @return int Count
 */
function fn_crm_count_company_tasks($companyId, $status) {
    $query = "SELECT COUNT(*) as count FROM crm_tasks WHERE company_id = ? AND status = ?";
    $result = fn_core_database_row($query, [$companyId, $status]);
    return $result['count'] ?? 0;
}

/**
 * Count overdue tasks
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_crm_count_overdue_tasks($companyId) {
    $query = "SELECT COUNT(*) as count FROM crm_tasks
              WHERE company_id = ?
              AND status = 'pending'
              AND due_date < CURDATE()";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Get overdue tasks
 *
 * @param int $companyId Company ID
 * @return array Overdue tasks
 */
function fn_crm_get_overdue_tasks($companyId) {
    $query = "SELECT t.*, l.first_name as lead_first_name, l.last_name as lead_last_name,
              u.first_name as assigned_first_name, u.last_name as assigned_last_name
              FROM crm_tasks t
              LEFT JOIN crm_leads l ON t.lead_id = l.lead_id
              LEFT JOIN users u ON t.assigned_to = u.user_id
              WHERE t.company_id = ?
              AND t.status = 'pending'
              AND t.due_date < CURDATE()
              ORDER BY t.due_date ASC";
    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Get tasks for a lead
 *
 * @param int $leadId Lead ID
 * @param int $companyId Company ID
 * @return array Tasks
 */
function fn_crm_get_lead_tasks($leadId, $companyId) {
    $query = "SELECT t.*, u.first_name as assigned_first_name, u.last_name as assigned_last_name
              FROM crm_tasks t
              LEFT JOIN users u ON t.assigned_to = u.user_id
              WHERE t.lead_id = ? AND t.company_id = ?
              ORDER BY t.due_date ASC, t.created_date DESC";
    return fn_core_database_rows($query, [$leadId, $companyId]);
}

/**
 * Get upcoming tasks for a user
 *
 * @param int $userId User ID
 * @param int $days Number of days ahead
 * @return array Tasks
 */
function fn_crm_get_upcoming_tasks($userId, $days = 7) {
    $query = "SELECT t.*, l.first_name as lead_first_name, l.last_name as lead_last_name
              FROM crm_tasks t
              LEFT JOIN crm_leads l ON t.lead_id = l.lead_id
              WHERE t.assigned_to = ?
              AND t.status = 'pending'
              AND t.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
              ORDER BY t.due_date ASC";
    return fn_core_database_rows($query, [$userId, $days]);
}

/**
 * ====================
 * ADDITIONAL CRM FUNCTIONS
 * ====================
 */

/**
 * Add note to lead (alias for fn_crm_add_activity with type 'note')
 *
 * @param array $noteData Note data (lead_id, user_id, note)
 * @return int|false Note ID or false
 */
function fn_crm_add_note($noteData) {
    $leadId = $noteData['lead_id'];
    $userId = $noteData['user_id'];
    $note = $noteData['note'];

    return fn_crm_add_activity($leadId, $userId, 'note', $note);
}

/**
 * Count total leads for a company
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_crm_count_leads($companyId) {
    $query = "SELECT COUNT(*) as count FROM crm_leads WHERE company_id = ?";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Get lead activities (wrapper for fn_crm_get_activities)
 *
 * @param int $leadId Lead ID
 * @return array Activities
 */
function fn_crm_get_lead_activities($leadId) {
    return fn_crm_get_activities($leadId);
}

/**
 * Get lead notes (activities of type 'note')
 *
 * @param int $leadId Lead ID
 * @return array Notes
 */
function fn_crm_get_lead_notes($leadId) {
    $query = "SELECT a.*, u.first_name, u.last_name
              FROM crm_activities a
              LEFT JOIN users u ON a.user_id = u.user_id
              WHERE a.lead_id = ? AND a.activity_type = 'note'
              ORDER BY a.activity_date DESC";
    return fn_core_database_rows($query, [$leadId]);
}

/**
 * Count activities by type for a company
 *
 * @param int $companyId Company ID
 * @param string $activityType Activity type (call, email, meeting, note)
 * @return int Count
 */
function fn_crm_count_activities_by_type($companyId, $activityType) {
    $query = "SELECT COUNT(*) as count
              FROM crm_activities a
              JOIN crm_leads l ON a.lead_id = l.lead_id
              WHERE l.company_id = ? AND a.activity_type = ?";
    $result = fn_core_database_row($query, [$companyId, $activityType]);
    return $result['count'] ?? 0;
}

/**
 * Count activities today for a company
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_crm_count_activities_today($companyId) {
    $today = date('Y-m-d');
    $query = "SELECT COUNT(*) as count
              FROM crm_activities a
              JOIN crm_leads l ON a.lead_id = l.lead_id
              WHERE l.company_id = ? AND DATE(a.activity_date) = ?";
    $result = fn_core_database_row($query, [$companyId, $today]);
    return $result['count'] ?? 0;
}

/**
 * Count activities this week for a company
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_crm_count_activities_week($companyId) {
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd = date('Y-m-d', strtotime('sunday this week'));
    $query = "SELECT COUNT(*) as count
              FROM crm_activities a
              JOIN crm_leads l ON a.lead_id = l.lead_id
              WHERE l.company_id = ?
              AND DATE(a.activity_date) BETWEEN ? AND ?";
    $result = fn_core_database_row($query, [$companyId, $weekStart, $weekEnd]);
    return $result['count'] ?? 0;
}

/**
 * Count activities this month for a company
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_crm_count_activities_month($companyId) {
    $monthStart = date('Y-m-01');
    $monthEnd = date('Y-m-t');
    $query = "SELECT COUNT(*) as count
              FROM crm_activities a
              JOIN crm_leads l ON a.lead_id = l.lead_id
              WHERE l.company_id = ?
              AND DATE(a.activity_date) BETWEEN ? AND ?";
    $result = fn_core_database_row($query, [$companyId, $monthStart, $monthEnd]);
    return $result['count'] ?? 0;
}
