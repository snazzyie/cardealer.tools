<?php
/**
 * Enquiry Management Functions
 *
 * Handle customer enquiries, test drive requests, and general enquiries
 */

/**
 * Get all enquiries for a company
 *
 * @param int $companyId Company ID
 * @param array $filters Optional filters (status, type, search)
 * @param int $limit Limit
 * @param int $offset Offset
 * @return array Enquiries
 */
function fn_enquiries_get_all($companyId, $filters = [], $limit = 100, $offset = 0) {
    $query = "SELECT e.*, v.make, v.model, v.year, v.price
              FROM enquiries e
              LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
              WHERE e.company_id = ?";

    $params = [$companyId];

    // Apply filters
    if (!empty($filters['status'])) {
        $query .= " AND e.status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['type'])) {
        $query .= " AND e.enquiry_type = ?";
        $params[] = $filters['type'];
    }

    if (!empty($filters['search'])) {
        $query .= " AND (e.first_name LIKE ? OR e.last_name LIKE ? OR e.email LIKE ? OR e.phone LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $query .= " ORDER BY e.created_date DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    return fn_core_database_rows($query, $params);
}

/**
 * Get enquiry by ID
 *
 * @param int $enquiryId Enquiry ID
 * @param int $companyId Company ID (for security)
 * @return array|null Enquiry data
 */
function fn_enquiries_get($enquiryId, $companyId) {
    $query = "SELECT e.*, v.make, v.model, v.year, v.price, v.registration
              FROM enquiries e
              LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
              WHERE e.enquiry_id = ? AND e.company_id = ?";
    return fn_core_database_row($query, [$enquiryId, $companyId]);
}

/**
 * Create enquiry
 *
 * @param int $companyId Company ID
 * @param array $data Enquiry data
 * @return int|false Enquiry ID or false
 */
function fn_enquiries_create($companyId, $data) {
    $query = "INSERT INTO enquiries (
        company_id, vehicle_id, enquiry_type, first_name, last_name,
        email, phone, message, preferred_contact_method,
        preferred_contact_time, source, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['vehicle_id'] ?? null,
        $data['enquiry_type'] ?? 'general',
        $data['first_name'],
        $data['last_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? '',
        $data['message'] ?? '',
        $data['preferred_contact_method'] ?? 'email',
        $data['preferred_contact_time'] ?? null,
        $data['source'] ?? 'website',
        $data['status'] ?? 'new'
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update enquiry
 *
 * @param int $enquiryId Enquiry ID
 * @param int $companyId Company ID (for security)
 * @param array $data Enquiry data
 * @return bool Success
 */
function fn_enquiries_update($enquiryId, $companyId, $data) {
    $query = "UPDATE enquiries SET
        vehicle_id = ?, enquiry_type = ?, first_name = ?, last_name = ?,
        email = ?, phone = ?, message = ?, preferred_contact_method = ?,
        preferred_contact_time = ?, status = ?
    WHERE enquiry_id = ? AND company_id = ?";

    $params = [
        $data['vehicle_id'] ?? null,
        $data['enquiry_type'] ?? 'general',
        $data['first_name'],
        $data['last_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? '',
        $data['message'] ?? '',
        $data['preferred_contact_method'] ?? 'email',
        $data['preferred_contact_time'] ?? null,
        $data['status'] ?? 'new',
        $enquiryId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update enquiry status
 *
 * @param int $enquiryId Enquiry ID
 * @param int $companyId Company ID
 * @param string $status New status
 * @return bool Success
 */
function fn_enquiries_update_status($enquiryId, $companyId, $status) {
    $query = "UPDATE enquiries SET status = ? WHERE enquiry_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$status, $enquiryId, $companyId]);
}

/**
 * Delete enquiry
 *
 * @param int $enquiryId Enquiry ID
 * @param int $companyId Company ID (for security)
 * @return bool Success
 */
function fn_enquiries_delete($enquiryId, $companyId) {
    $query = "DELETE FROM enquiries WHERE enquiry_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$enquiryId, $companyId]);
}

/**
 * Convert enquiry to lead
 *
 * @param int $enquiryId Enquiry ID
 * @param int $companyId Company ID
 * @param int $userId User ID (who is converting)
 * @return int|false Lead ID or false
 */
function fn_enquiries_convert_to_lead($enquiryId, $companyId, $userId) {
    // Get enquiry data
    $enquiry = fn_enquiries_get($enquiryId, $companyId);

    if (!$enquiry) {
        return false;
    }

    // Create lead from enquiry
    $leadData = [
        'first_name' => $enquiry['first_name'],
        'last_name' => $enquiry['last_name'],
        'email' => $enquiry['email'],
        'phone' => $enquiry['phone'],
        'source' => 'website_enquiry',
        'status' => 'new',
        'vehicle_id' => $enquiry['vehicle_id'],
        'notes' => $enquiry['message']
    ];

    $leadId = fn_crm_create_lead($companyId, $leadData);

    if ($leadId) {
        // Update enquiry status
        $query = "UPDATE enquiries SET status = 'converted', converted_to_lead_id = ? WHERE enquiry_id = ? AND company_id = ?";
        fn_core_edit_row_no_redirect($query, [$leadId, $enquiryId, $companyId]);
    }

    return $leadId;
}

/**
 * Get enquiries count by status
 *
 * @param int $companyId Company ID
 * @param string $status Status
 * @return int Count
 */
function fn_enquiries_count_by_status($companyId, $status) {
    $query = "SELECT COUNT(*) as count FROM enquiries WHERE company_id = ? AND status = ?";
    $result = fn_core_database_row($query, [$companyId, $status]);
    return $result['count'] ?? 0;
}

/**
 * Get enquiries count by type
 *
 * @param int $companyId Company ID
 * @param string $type Enquiry type
 * @return int Count
 */
function fn_enquiries_count_by_type($companyId, $type) {
    $query = "SELECT COUNT(*) as count FROM enquiries WHERE company_id = ? AND enquiry_type = ?";
    $result = fn_core_database_row($query, [$companyId, $type]);
    return $result['count'] ?? 0;
}

/**
 * Get total enquiries count
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_enquiries_count_total($companyId) {
    $query = "SELECT COUNT(*) as count FROM enquiries WHERE company_id = ?";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Get enquiries statistics
 *
 * @param int $companyId Company ID
 * @return array Stats
 */
function fn_enquiries_get_stats($companyId) {
    return [
        'total' => fn_enquiries_count_total($companyId),
        'new' => fn_enquiries_count_by_status($companyId, 'new'),
        'contacted' => fn_enquiries_count_by_status($companyId, 'contacted'),
        'converted' => fn_enquiries_count_by_status($companyId, 'converted'),
        'test_drives' => fn_enquiries_count_by_type($companyId, 'test_drive'),
        'general' => fn_enquiries_count_by_type($companyId, 'general')
    ];
}

/**
 * Get recent enquiries
 *
 * @param int $companyId Company ID
 * @param int $limit Limit
 * @return array Enquiries
 */
function fn_enquiries_get_recent($companyId, $limit = 10) {
    $query = "SELECT e.*, v.make, v.model, v.year
              FROM enquiries e
              LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
              WHERE e.company_id = ?
              ORDER BY e.created_date DESC
              LIMIT ?";
    return fn_core_database_rows($query, [$companyId, $limit]);
}

/**
 * Get new (unread) enquiries count
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_enquiries_count_new($companyId) {
    return fn_enquiries_count_by_status($companyId, 'new');
}

/**
 * Mark enquiry as contacted
 *
 * @param int $enquiryId Enquiry ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_enquiries_mark_contacted($enquiryId, $companyId) {
    return fn_enquiries_update_status($enquiryId, $companyId, 'contacted');
}

/**
 * Search enquiries
 *
 * @param int $companyId Company ID
 * @param string $searchTerm Search term
 * @param int $limit Limit
 * @return array Enquiries
 */
function fn_enquiries_search($companyId, $searchTerm, $limit = 50) {
    $query = "SELECT e.*, v.make, v.model, v.year
              FROM enquiries e
              LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
              WHERE e.company_id = ?
              AND (e.first_name LIKE ? OR e.last_name LIKE ? OR e.email LIKE ? OR e.phone LIKE ? OR e.message LIKE ?)
              ORDER BY e.created_date DESC
              LIMIT ?";

    $term = '%' . $searchTerm . '%';
    return fn_core_database_rows($query, [$companyId, $term, $term, $term, $term, $term, $limit]);
}

/**
 * Get enquiries for a specific vehicle
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID
 * @return array Enquiries
 */
function fn_enquiries_get_by_vehicle($vehicleId, $companyId) {
    $query = "SELECT * FROM enquiries
              WHERE vehicle_id = ? AND company_id = ?
              ORDER BY created_date DESC";
    return fn_core_database_rows($query, [$vehicleId, $companyId]);
}

/**
 * Get enquiries by date range
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date (Y-m-d)
 * @param string $endDate End date (Y-m-d)
 * @return array Enquiries
 */
function fn_enquiries_get_by_date_range($companyId, $startDate, $endDate) {
    $query = "SELECT e.*, v.make, v.model, v.year
              FROM enquiries e
              LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
              WHERE e.company_id = ?
              AND DATE(e.created_date) BETWEEN ? AND ?
              ORDER BY e.created_date DESC";
    return fn_core_database_rows($query, [$companyId, $startDate, $endDate]);
}

/**
 * Get conversion rate (enquiries to leads)
 *
 * @param int $companyId Company ID
 * @return float Conversion rate percentage
 */
function fn_enquiries_get_conversion_rate($companyId) {
    $total = fn_enquiries_count_total($companyId);

    if ($total == 0) {
        return 0;
    }

    $converted = fn_enquiries_count_by_status($companyId, 'converted');
    return round(($converted / $total) * 100, 1);
}
