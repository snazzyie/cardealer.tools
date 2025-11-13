<?php
/**
 * Customer Management Functions
 *
 * Handle customer records and customer data
 */

/**
 * Get all customers for a company
 *
 * @param int $companyId Company ID
 * @param array $filters Optional filters (search, filter type)
 * @param int $limit Limit
 * @param int $offset Offset
 * @return array Customers
 */
function fn_customers_get_all($companyId, $filters = [], $limit = 100, $offset = 0) {
    $query = "SELECT DISTINCT
        c.customer_id,
        c.first_name,
        c.last_name,
        c.email,
        c.phone,
        c.address_line1,
        c.address_line2,
        c.city,
        c.county,
        c.postcode,
        c.country,
        c.notes,
        c.created_date,
        COUNT(DISTINCT si.invoice_id) as purchase_count,
        SUM(si.total_amount) as total_spent
    FROM customers c
    LEFT JOIN sales_invoices si ON c.customer_id = si.customer_id
    WHERE c.company_id = ?";

    $params = [$companyId];

    // Apply search filter
    if (!empty($filters['search'])) {
        $query .= " AND (c.first_name LIKE ? OR c.last_name LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $query .= " GROUP BY c.customer_id";

    // Apply purchase filter
    if (!empty($filters['filter'])) {
        if ($filters['filter'] === 'with_purchases') {
            $query .= " HAVING purchase_count > 0";
        } elseif ($filters['filter'] === 'no_purchases') {
            $query .= " HAVING purchase_count = 0";
        }
    }

    $query .= " ORDER BY c.created_date DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    return fn_core_database_rows($query, $params);
}

/**
 * Get customer by ID
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID (for security)
 * @return array|null Customer data
 */
function fn_customers_get($customerId, $companyId) {
    $query = "SELECT * FROM customers WHERE customer_id = ? AND company_id = ?";
    return fn_core_database_row($query, [$customerId, $companyId]);
}

/**
 * Get customer by email
 *
 * @param string $email Email address
 * @param int $companyId Company ID
 * @return array|null Customer data
 */
function fn_customers_get_by_email($email, $companyId) {
    $query = "SELECT * FROM customers WHERE email = ? AND company_id = ?";
    return fn_core_database_row($query, [$email, $companyId]);
}

/**
 * Create customer
 *
 * @param int $companyId Company ID
 * @param array $data Customer data
 * @return int|false Customer ID or false
 */
function fn_customers_create($companyId, $data) {
    // Check if customer already exists by email
    if (!empty($data['email'])) {
        $existing = fn_customers_get_by_email($data['email'], $companyId);
        if ($existing) {
            return $existing['customer_id'];
        }
    }

    $query = "INSERT INTO customers (
        company_id, first_name, last_name, email, phone,
        address_line1, address_line2, city, county, postcode, country,
        date_of_birth, driving_licence_number, notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['first_name'],
        $data['last_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? '',
        $data['address_line1'] ?? null,
        $data['address_line2'] ?? null,
        $data['city'] ?? null,
        $data['county'] ?? null,
        $data['postcode'] ?? null,
        $data['country'] ?? 'Ireland',
        $data['date_of_birth'] ?? null,
        $data['driving_licence_number'] ?? null,
        $data['notes'] ?? null
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update customer
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID (for security)
 * @param array $data Customer data
 * @return bool Success
 */
function fn_customers_update($customerId, $companyId, $data) {
    $query = "UPDATE customers SET
        first_name = ?, last_name = ?, email = ?, phone = ?,
        address_line1 = ?, address_line2 = ?, city = ?, county = ?,
        postcode = ?, country = ?, date_of_birth = ?,
        driving_licence_number = ?, notes = ?
    WHERE customer_id = ? AND company_id = ?";

    $params = [
        $data['first_name'],
        $data['last_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? '',
        $data['address_line1'] ?? null,
        $data['address_line2'] ?? null,
        $data['city'] ?? null,
        $data['county'] ?? null,
        $data['postcode'] ?? null,
        $data['country'] ?? 'Ireland',
        $data['date_of_birth'] ?? null,
        $data['driving_licence_number'] ?? null,
        $data['notes'] ?? null,
        $customerId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Delete customer
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID (for security)
 * @return bool Success
 */
function fn_customers_delete($customerId, $companyId) {
    $query = "DELETE FROM customers WHERE customer_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$customerId, $companyId]);
}

/**
 * Get customer purchase history
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID
 * @return array Purchase history
 */
function fn_customers_get_purchase_history($customerId, $companyId) {
    $query = "SELECT si.*, v.make, v.model, v.year, v.registration
              FROM sales_invoices si
              LEFT JOIN vehicles v ON si.vehicle_id = v.vehicle_id
              WHERE si.customer_id = ? AND si.company_id = ?
              ORDER BY si.sale_date DESC";
    return fn_core_database_rows($query, [$customerId, $companyId]);
}

/**
 * Get customer service history
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID
 * @return array Service history
 */
function fn_customers_get_service_history($customerId, $companyId) {
    $query = "SELECT * FROM service_invoices
              WHERE customer_id = ? AND company_id = ?
              ORDER BY service_date DESC";
    return fn_core_database_rows($query, [$customerId, $companyId]);
}

/**
 * Get customer enquiries
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID
 * @return array Enquiries
 */
function fn_customers_get_enquiries($customerId, $companyId) {
    $query = "SELECT e.*, v.make, v.model, v.year
              FROM enquiries e
              LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
              WHERE e.company_id = ?
              AND (e.email = (SELECT email FROM customers WHERE customer_id = ? AND company_id = ?)
                   OR e.phone = (SELECT phone FROM customers WHERE customer_id = ? AND company_id = ?))
              ORDER BY e.created_date DESC";
    return fn_core_database_rows($query, [$companyId, $customerId, $companyId, $customerId, $companyId]);
}

/**
 * Get customer appointments
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID
 * @return array Appointments
 */
function fn_customers_get_appointments($customerId, $companyId) {
    $query = "SELECT a.*, v.make, v.model, v.year
              FROM calendar_appointments a
              LEFT JOIN vehicles v ON a.vehicle_id = v.vehicle_id
              WHERE a.customer_id = ? AND a.company_id = ?
              ORDER BY a.start_datetime DESC";
    return fn_core_database_rows($query, [$customerId, $companyId]);
}

/**
 * Get customer statistics
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID
 * @return array Stats
 */
function fn_customers_get_stats($customerId, $companyId) {
    $stats = [];

    // Total spent
    $query = "SELECT SUM(total_amount) as total FROM sales_invoices
              WHERE customer_id = ? AND company_id = ?";
    $result = fn_core_database_row($query, [$customerId, $companyId]);
    $stats['total_spent'] = $result['total'] ?? 0;

    // Purchase count
    $query = "SELECT COUNT(*) as count FROM sales_invoices
              WHERE customer_id = ? AND company_id = ?";
    $result = fn_core_database_row($query, [$customerId, $companyId]);
    $stats['purchase_count'] = $result['count'] ?? 0;

    // Service count
    $query = "SELECT COUNT(*) as count FROM service_invoices
              WHERE customer_id = ? AND company_id = ?";
    $result = fn_core_database_row($query, [$customerId, $companyId]);
    $stats['service_count'] = $result['count'] ?? 0;

    // Enquiry count
    $query = "SELECT COUNT(*) as count FROM enquiries
              WHERE company_id = ?
              AND (email = (SELECT email FROM customers WHERE customer_id = ? AND company_id = ?)
                   OR phone = (SELECT phone FROM customers WHERE customer_id = ? AND company_id = ?))";
    $result = fn_core_database_row($query, [$companyId, $customerId, $companyId, $customerId, $companyId]);
    $stats['enquiry_count'] = $result['count'] ?? 0;

    return $stats;
}

/**
 * Get total customers count
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_customers_count_total($companyId) {
    $query = "SELECT COUNT(*) as count FROM customers WHERE company_id = ?";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Get customers with purchases count
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_customers_count_with_purchases($companyId) {
    $query = "SELECT COUNT(DISTINCT customer_id) as count FROM sales_invoices WHERE company_id = ?";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Get new customers this month
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_customers_count_this_month($companyId) {
    $query = "SELECT COUNT(*) as count FROM customers
              WHERE company_id = ?
              AND created_date >= DATE_FORMAT(NOW(), '%Y-%m-01')";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}

/**
 * Search customers
 *
 * @param int $companyId Company ID
 * @param string $searchTerm Search term
 * @param int $limit Limit
 * @return array Customers
 */
function fn_customers_search($companyId, $searchTerm, $limit = 50) {
    $query = "SELECT * FROM customers
              WHERE company_id = ?
              AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR phone LIKE ?)
              ORDER BY first_name, last_name
              LIMIT ?";

    $term = '%' . $searchTerm . '%';
    return fn_core_database_rows($query, [$companyId, $term, $term, $term, $term, $limit]);
}

/**
 * Get or create customer (useful for enquiries/appointments)
 *
 * @param int $companyId Company ID
 * @param array $data Customer data
 * @return int Customer ID
 */
function fn_customers_get_or_create($companyId, $data) {
    // Try to find existing customer by email
    if (!empty($data['email'])) {
        $existing = fn_customers_get_by_email($data['email'], $companyId);
        if ($existing) {
            return $existing['customer_id'];
        }
    }

    // Create new customer
    return fn_customers_create($companyId, $data);
}

/**
 * Get top customers by spending
 *
 * @param int $companyId Company ID
 * @param int $limit Limit
 * @return array Customers
 */
function fn_customers_get_top_by_spending($companyId, $limit = 10) {
    $query = "SELECT c.*, SUM(si.total_amount) as total_spent, COUNT(si.invoice_id) as purchase_count
              FROM customers c
              INNER JOIN sales_invoices si ON c.customer_id = si.customer_id
              WHERE c.company_id = ?
              GROUP BY c.customer_id
              ORDER BY total_spent DESC
              LIMIT ?";
    return fn_core_database_rows($query, [$companyId, $limit]);
}

/**
 * Get customer lifetime value
 *
 * @param int $customerId Customer ID
 * @param int $companyId Company ID
 * @return float Lifetime value
 */
function fn_customers_get_lifetime_value($customerId, $companyId) {
    $query = "SELECT SUM(total_amount) as total FROM sales_invoices
              WHERE customer_id = ? AND company_id = ?";
    $result = fn_core_database_row($query, [$customerId, $companyId]);
    return floatval($result['total'] ?? 0);
}

/**
 * Get recent customers
 *
 * @param int $companyId Company ID
 * @param int $limit Limit
 * @return array Customers
 */
function fn_customers_get_recent($companyId, $limit = 10) {
    $query = "SELECT * FROM customers
              WHERE company_id = ?
              ORDER BY created_date DESC
              LIMIT ?";
    return fn_core_database_rows($query, [$companyId, $limit]);
}
