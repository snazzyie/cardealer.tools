<?php
/**
 * Deposit Management Functions
 *
 * Handle customer deposits for vehicle purchases
 */

/**
 * Get all deposits for a company
 *
 * @param int $companyId Company ID
 * @param array $filters Optional filters (status, search)
 * @param int $limit Limit
 * @param int $offset Offset
 * @return array Deposits
 */
function fn_deposits_get_all($companyId, $filters = [], $limit = 100, $offset = 0) {
    $query = "SELECT d.*, v.make, v.model, v.year, v.registration,
              c.first_name as customer_first_name, c.last_name as customer_last_name,
              c.email as customer_email, c.phone as customer_phone
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.company_id = ?";

    $params = [$companyId];

    // Apply filters
    if (!empty($filters['status'])) {
        $query .= " AND d.status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['search'])) {
        $query .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR c.email LIKE ? OR d.deposit_reference LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $query .= " ORDER BY d.created_date DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    return fn_core_database_rows($query, $params);
}

/**
 * Get deposit by ID
 *
 * @param int $depositId Deposit ID
 * @param int $companyId Company ID (for security)
 * @return array|null Deposit data
 */
function fn_deposits_get($depositId, $companyId) {
    $query = "SELECT d.*,
              v.make, v.model, v.year, v.registration, v.price,
              c.first_name as customer_first_name, c.last_name as customer_last_name,
              c.email as customer_email, c.phone as customer_phone,
              c.address_line1, c.city, c.postcode
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.deposit_id = ? AND d.company_id = ?";
    return fn_core_database_row($query, [$depositId, $companyId]);
}

/**
 * Get deposit by reference
 *
 * @param string $reference Deposit reference
 * @param int $companyId Company ID
 * @return array|null Deposit data
 */
function fn_deposits_get_by_reference($reference, $companyId) {
    $query = "SELECT d.*,
              v.make, v.model, v.year, v.registration,
              c.first_name as customer_first_name, c.last_name as customer_last_name
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.deposit_reference = ? AND d.company_id = ?";
    return fn_core_database_row($query, [$reference, $companyId]);
}

/**
 * Create deposit
 *
 * @param int $companyId Company ID
 * @param array $data Deposit data
 * @return int|false Deposit ID or false
 */
function fn_deposits_create($companyId, $data) {
    // Generate unique deposit reference
    $depositReference = fn_deposits_generate_reference($companyId);

    $query = "INSERT INTO deposits (
        company_id, customer_id, vehicle_id, deposit_reference,
        amount, payment_method, payment_reference,
        notes, status, expiry_date, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['customer_id'],
        $data['vehicle_id'] ?? null,
        $depositReference,
        $data['amount'],
        $data['payment_method'] ?? 'cash',
        $data['payment_reference'] ?? null,
        $data['notes'] ?? null,
        $data['status'] ?? 'active',
        $data['expiry_date'] ?? null,
        $data['created_by'] ?? null
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update deposit
 *
 * @param int $depositId Deposit ID
 * @param int $companyId Company ID (for security)
 * @param array $data Deposit data
 * @return bool Success
 */
function fn_deposits_update($depositId, $companyId, $data) {
    $query = "UPDATE deposits SET
        customer_id = ?, vehicle_id = ?, amount = ?,
        payment_method = ?, payment_reference = ?,
        notes = ?, status = ?, expiry_date = ?
    WHERE deposit_id = ? AND company_id = ?";

    $params = [
        $data['customer_id'],
        $data['vehicle_id'] ?? null,
        $data['amount'],
        $data['payment_method'] ?? 'cash',
        $data['payment_reference'] ?? null,
        $data['notes'] ?? null,
        $data['status'] ?? 'active',
        $data['expiry_date'] ?? null,
        $depositId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update deposit status
 *
 * @param int $depositId Deposit ID
 * @param int $companyId Company ID
 * @param string $status New status
 * @return bool Success
 */
function fn_deposits_update_status($depositId, $companyId, $status) {
    $query = "UPDATE deposits SET status = ? WHERE deposit_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$status, $depositId, $companyId]);
}

/**
 * Delete deposit
 *
 * @param int $depositId Deposit ID
 * @param int $companyId Company ID (for security)
 * @return bool Success
 */
function fn_deposits_delete($depositId, $companyId) {
    $query = "DELETE FROM deposits WHERE deposit_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$depositId, $companyId]);
}

/**
 * Mark deposit as completed (applied to purchase)
 *
 * @param int $depositId Deposit ID
 * @param int $companyId Company ID
 * @param int $invoiceId Sales invoice ID
 * @return bool Success
 */
function fn_deposits_mark_completed($depositId, $companyId, $invoiceId = null) {
    $query = "UPDATE deposits SET
              status = 'completed',
              completed_date = NOW(),
              applied_to_invoice_id = ?
              WHERE deposit_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$invoiceId, $depositId, $companyId]);
}

/**
 * Refund deposit
 *
 * @param int $depositId Deposit ID
 * @param int $companyId Company ID
 * @param float $refundAmount Refund amount
 * @param string $refundReason Refund reason
 * @param int $refundedBy User ID who processed refund
 * @return bool Success
 */
function fn_deposits_refund($depositId, $companyId, $refundAmount, $refundReason = null, $refundedBy = null) {
    $query = "UPDATE deposits SET
              status = 'refunded',
              refund_amount = ?,
              refund_reason = ?,
              refunded_by = ?,
              refunded_date = NOW()
              WHERE deposit_id = ? AND company_id = ?";

    return fn_core_edit_row_no_redirect($query, [
        $refundAmount,
        $refundReason,
        $refundedBy,
        $depositId,
        $companyId
    ]);
}

/**
 * Mark deposit as expired
 *
 * @param int $depositId Deposit ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_deposits_mark_expired($depositId, $companyId) {
    return fn_deposits_update_status($depositId, $companyId, 'expired');
}

/**
 * Generate unique deposit reference
 *
 * @param int $companyId Company ID
 * @return string Deposit reference (e.g., DEP-202411-0001)
 */
function fn_deposits_generate_reference($companyId) {
    $yearMonth = date('Ym');
    $prefix = "DEP-{$yearMonth}";

    // Get last deposit reference for this month
    $query = "SELECT deposit_reference FROM deposits
              WHERE company_id = ?
              AND deposit_reference LIKE ?
              ORDER BY deposit_id DESC LIMIT 1";

    $lastDeposit = fn_core_database_row($query, [$companyId, "{$prefix}-%"]);

    if ($lastDeposit && preg_match('/(\d+)$/', $lastDeposit['deposit_reference'], $matches)) {
        $number = intval($matches[1]) + 1;
    } else {
        $number = 1;
    }

    return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
}

/**
 * Get deposits count by status
 *
 * @param int $companyId Company ID
 * @param string $status Status
 * @return int Count
 */
function fn_deposits_count_by_status($companyId, $status) {
    $query = "SELECT COUNT(*) as count FROM deposits WHERE company_id = ? AND status = ?";
    $result = fn_core_database_row($query, [$companyId, $status]);
    return $result['count'] ?? 0;
}

/**
 * Get total deposits value by status
 *
 * @param int $companyId Company ID
 * @param string $status Status
 * @return float Total value
 */
function fn_deposits_total_by_status($companyId, $status) {
    $query = "SELECT SUM(amount) as total FROM deposits WHERE company_id = ? AND status = ?";
    $result = fn_core_database_row($query, [$companyId, $status]);
    return floatval($result['total'] ?? 0);
}

/**
 * Get deposits statistics
 *
 * @param int $companyId Company ID
 * @return array Stats
 */
function fn_deposits_get_stats($companyId) {
    return [
        'total_deposits' => fn_deposits_count_by_status($companyId, 'active') +
                           fn_deposits_count_by_status($companyId, 'completed') +
                           fn_deposits_count_by_status($companyId, 'refunded'),
        'active_deposits' => fn_deposits_count_by_status($companyId, 'active'),
        'completed' => fn_deposits_count_by_status($companyId, 'completed'),
        'refunded' => fn_deposits_count_by_status($companyId, 'refunded'),
        'expired' => fn_deposits_count_by_status($companyId, 'expired'),
        'total_value' => fn_deposits_total_by_status($companyId, 'active'),
        'completed_value' => fn_deposits_total_by_status($companyId, 'completed'),
        'refunded_value' => fn_deposits_total_by_status($companyId, 'refunded')
    ];
}

/**
 * Get deposits for a customer
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID
 * @return array Deposits
 */
function fn_deposits_get_by_customer($customerId, $companyId) {
    $query = "SELECT d.*, v.make, v.model, v.year, v.registration
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              WHERE d.customer_id = ? AND d.company_id = ?
              ORDER BY d.created_date DESC";
    return fn_core_database_rows($query, [$customerId, $companyId]);
}

/**
 * Get deposits for a vehicle
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID
 * @return array Deposits
 */
function fn_deposits_get_by_vehicle($vehicleId, $companyId) {
    $query = "SELECT d.*,
              c.first_name as customer_first_name, c.last_name as customer_last_name,
              c.email as customer_email
              FROM deposits d
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.vehicle_id = ? AND d.company_id = ?
              ORDER BY d.created_date DESC";
    return fn_core_database_rows($query, [$vehicleId, $companyId]);
}

/**
 * Get expired deposits
 *
 * @param int $companyId Company ID
 * @return array Deposits
 */
function fn_deposits_get_expired($companyId) {
    $query = "SELECT d.*, v.make, v.model, v.year,
              c.first_name as customer_first_name, c.last_name as customer_last_name
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.company_id = ?
              AND d.status = 'active'
              AND d.expiry_date IS NOT NULL
              AND d.expiry_date < CURDATE()
              ORDER BY d.expiry_date ASC";
    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Get deposits expiring soon
 *
 * @param int $companyId Company ID
 * @param int $days Number of days ahead
 * @return array Deposits
 */
function fn_deposits_get_expiring_soon($companyId, $days = 7) {
    $query = "SELECT d.*, v.make, v.model, v.year,
              c.first_name as customer_first_name, c.last_name as customer_last_name,
              c.email as customer_email, c.phone as customer_phone
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.company_id = ?
              AND d.status = 'active'
              AND d.expiry_date IS NOT NULL
              AND d.expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY)
              ORDER BY d.expiry_date ASC";
    return fn_core_database_rows($query, [$companyId, $days]);
}

/**
 * Get recent deposits
 *
 * @param int $companyId Company ID
 * @param int $limit Limit
 * @return array Deposits
 */
function fn_deposits_get_recent($companyId, $limit = 10) {
    $query = "SELECT d.*, v.make, v.model, v.year,
              c.first_name as customer_first_name, c.last_name as customer_last_name
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.company_id = ?
              ORDER BY d.created_date DESC
              LIMIT ?";
    return fn_core_database_rows($query, [$companyId, $limit]);
}

/**
 * Search deposits
 *
 * @param int $companyId Company ID
 * @param string $searchTerm Search term
 * @param int $limit Limit
 * @return array Deposits
 */
function fn_deposits_search($companyId, $searchTerm, $limit = 50) {
    $query = "SELECT d.*, v.make, v.model, v.year,
              c.first_name as customer_first_name, c.last_name as customer_last_name
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.company_id = ?
              AND (c.first_name LIKE ? OR c.last_name LIKE ? OR c.email LIKE ?
                   OR d.deposit_reference LIKE ? OR d.payment_reference LIKE ?)
              ORDER BY d.created_date DESC
              LIMIT ?";

    $term = '%' . $searchTerm . '%';
    return fn_core_database_rows($query, [$companyId, $term, $term, $term, $term, $term, $limit]);
}

/**
 * Get deposits by date range
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date (Y-m-d)
 * @param string $endDate End date (Y-m-d)
 * @return array Deposits
 */
function fn_deposits_get_by_date_range($companyId, $startDate, $endDate) {
    $query = "SELECT d.*, v.make, v.model, v.year,
              c.first_name as customer_first_name, c.last_name as customer_last_name
              FROM deposits d
              LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
              LEFT JOIN customers c ON d.customer_id = c.customer_id
              WHERE d.company_id = ?
              AND DATE(d.created_date) BETWEEN ? AND ?
              ORDER BY d.created_date DESC";
    return fn_core_database_rows($query, [$companyId, $startDate, $endDate]);
}

/**
 * Check if vehicle has active deposit
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID
 * @return bool True if has active deposit
 */
function fn_deposits_vehicle_has_active($vehicleId, $companyId) {
    $query = "SELECT COUNT(*) as count FROM deposits
              WHERE vehicle_id = ? AND company_id = ? AND status = 'active'";
    $result = fn_core_database_row($query, [$vehicleId, $companyId]);
    return ($result['count'] ?? 0) > 0;
}
