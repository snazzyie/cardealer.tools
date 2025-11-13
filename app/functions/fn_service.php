<?php
/**
 * Service Workshop Functions
 * Manage service items, labor, parts, and service invoices
 */

/**
 * Get all service items for a company
 *
 * @param int $companyId Company ID
 * @param string $itemType Filter by type (service, part, or all)
 * @return array Service items
 */
function fn_service_items_get_all($companyId, $itemType = 'all') {
    $query = "SELECT * FROM service_items WHERE company_id = ?";
    $params = [$companyId];

    if ($itemType !== 'all') {
        $query .= " AND item_type = ?";
        $params[] = $itemType;
    }

    $query .= " AND is_active = 1 ORDER BY item_name ASC";

    return fn_core_database_rows($query, $params);
}

/**
 * Get service item by ID
 *
 * @param int $itemId Item ID
 * @param int $companyId Company ID
 * @return array|false Service item data or false
 */
function fn_service_item_get($itemId, $companyId) {
    $query = "SELECT * FROM service_items WHERE item_id = ? AND company_id = ?";
    return fn_core_database_row($query, [$itemId, $companyId]);
}

/**
 * Create service item
 *
 * @param int $companyId Company ID
 * @param array $data Item data
 * @return int|false Item ID or false
 */
function fn_service_item_create($companyId, $data) {
    // Validate required fields
    if (empty($data['item_name']) || empty($data['item_type']) || !isset($data['unit_price'])) {
        return false;
    }

    // Generate item code if not provided
    if (empty($data['item_code'])) {
        $data['item_code'] = fn_service_generate_item_code($data['item_type'], $companyId);
    }

    $query = "INSERT INTO service_items (
        company_id, item_type, item_code, item_name, description,
        unit_price, cost_price, estimated_time_minutes,
        quantity_in_stock, reorder_level, supplier, is_active
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['item_type'],
        $data['item_code'],
        $data['item_name'],
        $data['description'] ?? null,
        $data['unit_price'],
        $data['cost_price'] ?? 0,
        $data['estimated_time_minutes'] ?? 0,
        $data['quantity_in_stock'] ?? 0,
        $data['reorder_level'] ?? 0,
        $data['supplier'] ?? null,
        $data['is_active'] ?? 1
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update service item
 *
 * @param int $itemId Item ID
 * @param int $companyId Company ID
 * @param array $data Updated data
 * @return bool Success
 */
function fn_service_item_update($itemId, $companyId, $data) {
    $query = "UPDATE service_items SET
        item_type = ?, item_code = ?, item_name = ?, description = ?,
        unit_price = ?, cost_price = ?, estimated_time_minutes = ?,
        quantity_in_stock = ?, reorder_level = ?, supplier = ?, is_active = ?
        WHERE item_id = ? AND company_id = ?";

    $params = [
        $data['item_type'],
        $data['item_code'],
        $data['item_name'],
        $data['description'] ?? null,
        $data['unit_price'],
        $data['cost_price'] ?? 0,
        $data['estimated_time_minutes'] ?? 0,
        $data['quantity_in_stock'] ?? 0,
        $data['reorder_level'] ?? 0,
        $data['supplier'] ?? null,
        $data['is_active'] ?? 1,
        $itemId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Delete service item (soft delete)
 *
 * @param int $itemId Item ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_service_item_delete($itemId, $companyId) {
    $query = "UPDATE service_items SET is_active = 0 WHERE item_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$itemId, $companyId]);
}

/**
 * Generate unique item code
 *
 * @param string $itemType Item type (service or part)
 * @param int $companyId Company ID
 * @return string Item code
 */
function fn_service_generate_item_code($itemType, $companyId) {
    $prefix = ($itemType === 'service') ? 'SVC' : 'PRT';

    // Get last item code
    $query = "SELECT item_code FROM service_items
              WHERE company_id = ? AND item_type = ?
              ORDER BY item_id DESC LIMIT 1";
    $lastItem = fn_core_database_row($query, [$companyId, $itemType]);

    if ($lastItem && preg_match('/(\d+)$/', $lastItem['item_code'], $matches)) {
        $number = intval($matches[1]) + 1;
    } else {
        $number = 1;
    }

    return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
}

/**
 * Get low stock items
 *
 * @param int $companyId Company ID
 * @return array Low stock items
 */
function fn_service_items_low_stock($companyId) {
    $query = "SELECT * FROM service_items
              WHERE company_id = ?
              AND item_type = 'part'
              AND is_active = 1
              AND quantity_in_stock <= reorder_level
              ORDER BY quantity_in_stock ASC";

    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Update stock quantity
 *
 * @param int $itemId Item ID
 * @param int $companyId Company ID
 * @param int $quantityChange Quantity to add (positive) or remove (negative)
 * @return bool Success
 */
function fn_service_item_update_stock($itemId, $companyId, $quantityChange) {
    $query = "UPDATE service_items
              SET quantity_in_stock = quantity_in_stock + ?
              WHERE item_id = ? AND company_id = ?";

    return fn_core_edit_row_no_redirect($query, [$quantityChange, $itemId, $companyId]);
}

// =====================================================
// SERVICE INVOICES
// =====================================================

/**
 * Get all service invoices for a company
 *
 * @param int $companyId Company ID
 * @param array $filters Optional filters (status, payment_status, customer_id)
 * @return array Service invoices
 */
function fn_service_invoices_get_all($companyId, $filters = []) {
    $query = "SELECT si.*, c.first_name, c.last_name, c.email
              FROM service_invoices si
              LEFT JOIN customers c ON si.customer_id = c.customer_id
              WHERE si.company_id = ?";
    $params = [$companyId];

    if (!empty($filters['status'])) {
        $query .= " AND si.status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['payment_status'])) {
        $query .= " AND si.payment_status = ?";
        $params[] = $filters['payment_status'];
    }

    if (!empty($filters['customer_id'])) {
        $query .= " AND si.customer_id = ?";
        $params[] = $filters['customer_id'];
    }

    $query .= " ORDER BY si.invoice_date DESC, si.created_date DESC";

    return fn_core_database_rows($query, $params);
}

/**
 * Get service invoice by ID
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @return array|false Invoice data or false
 */
function fn_service_invoice_get($invoiceId, $companyId) {
    $query = "SELECT si.*,
              c.first_name, c.last_name, c.email, c.phone, c.address_line1, c.city, c.postcode,
              u.first_name as tech_first_name, u.last_name as tech_last_name
              FROM service_invoices si
              LEFT JOIN customers c ON si.customer_id = c.customer_id
              LEFT JOIN users u ON si.technician_id = u.user_id
              WHERE si.service_invoice_id = ? AND si.company_id = ?";

    $invoice = fn_core_database_row($query, [$invoiceId, $companyId]);

    if ($invoice && !empty($invoice['line_items'])) {
        $invoice['line_items'] = json_decode($invoice['line_items'], true);
    }

    return $invoice;
}

/**
 * Create service invoice
 *
 * @param int $companyId Company ID
 * @param array $data Invoice data
 * @return int|false Invoice ID or false
 */
function fn_service_invoice_create($companyId, $data) {
    // Validate required fields
    if (empty($data['customer_id']) || empty($data['invoice_date'])) {
        return false;
    }

    // Generate invoice number
    $data['invoice_number'] = fn_service_generate_invoice_number($companyId);

    // Calculate totals from line items
    $totals = fn_service_calculate_invoice_totals($data['line_items'] ?? []);

    $query = "INSERT INTO service_invoices (
        company_id, customer_id, vehicle_registration, invoice_number,
        invoice_date, due_date, customer_name, customer_email, customer_phone,
        service_type, service_date, mileage, technician_id,
        line_items, labor_total, parts_total, subtotal,
        vat_rate, vat_amount, total, payment_status,
        work_performed, notes, status, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['customer_id'],
        $data['vehicle_registration'] ?? null,
        $data['invoice_number'],
        $data['invoice_date'],
        $data['due_date'] ?? null,
        $data['customer_name'],
        $data['customer_email'] ?? null,
        $data['customer_phone'] ?? null,
        $data['service_type'] ?? 'General Service',
        $data['service_date'] ?? $data['invoice_date'],
        $data['mileage'] ?? null,
        $data['technician_id'] ?? null,
        json_encode($data['line_items'] ?? []),
        $totals['labor_total'],
        $totals['parts_total'],
        $totals['subtotal'],
        $data['vat_rate'] ?? 23.00,
        $totals['vat_amount'],
        $totals['total'],
        'unpaid',
        $data['work_performed'] ?? null,
        $data['notes'] ?? null,
        $data['status'] ?? 'draft',
        $data['created_by'] ?? null
    ];

    $invoiceId = fn_core_insert_row_no_redirect($query, $params);

    // Update stock for parts used
    if ($invoiceId && !empty($data['line_items'])) {
        fn_service_update_stock_from_invoice($data['line_items'], $companyId);
    }

    return $invoiceId;
}

/**
 * Update service invoice
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @param array $data Updated data
 * @return bool Success
 */
function fn_service_invoice_update($invoiceId, $companyId, $data) {
    // Calculate totals from line items
    $totals = fn_service_calculate_invoice_totals($data['line_items'] ?? []);

    $query = "UPDATE service_invoices SET
        customer_id = ?, vehicle_registration = ?, invoice_date = ?,
        due_date = ?, customer_name = ?, customer_email = ?, customer_phone = ?,
        service_type = ?, service_date = ?, mileage = ?, technician_id = ?,
        line_items = ?, labor_total = ?, parts_total = ?, subtotal = ?,
        vat_rate = ?, vat_amount = ?, total = ?,
        work_performed = ?, notes = ?, status = ?
        WHERE service_invoice_id = ? AND company_id = ?";

    $params = [
        $data['customer_id'],
        $data['vehicle_registration'] ?? null,
        $data['invoice_date'],
        $data['due_date'] ?? null,
        $data['customer_name'],
        $data['customer_email'] ?? null,
        $data['customer_phone'] ?? null,
        $data['service_type'] ?? 'General Service',
        $data['service_date'] ?? $data['invoice_date'],
        $data['mileage'] ?? null,
        $data['technician_id'] ?? null,
        json_encode($data['line_items'] ?? []),
        $totals['labor_total'],
        $totals['parts_total'],
        $totals['subtotal'],
        $data['vat_rate'] ?? 23.00,
        $totals['vat_amount'],
        $totals['total'],
        $data['work_performed'] ?? null,
        $data['notes'] ?? null,
        $data['status'] ?? 'draft',
        $invoiceId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Delete service invoice
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_service_invoice_delete($invoiceId, $companyId) {
    $query = "DELETE FROM service_invoices WHERE service_invoice_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$invoiceId, $companyId]);
}

/**
 * Generate unique service invoice number
 *
 * @param int $companyId Company ID
 * @return string Invoice number (e.g., SVC-202411-0001)
 */
function fn_service_generate_invoice_number($companyId) {
    $yearMonth = date('Ym');
    $prefix = "SVC-{$yearMonth}";

    // Get last invoice number for this month
    $query = "SELECT invoice_number FROM service_invoices
              WHERE company_id = ?
              AND invoice_number LIKE ?
              ORDER BY service_invoice_id DESC LIMIT 1";

    $lastInvoice = fn_core_database_row($query, [$companyId, "{$prefix}-%"]);

    if ($lastInvoice && preg_match('/(\d+)$/', $lastInvoice['invoice_number'], $matches)) {
        $number = intval($matches[1]) + 1;
    } else {
        $number = 1;
    }

    return $prefix . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
}

/**
 * Calculate invoice totals from line items
 *
 * @param array $lineItems Line items array
 * @return array Totals (labor_total, parts_total, subtotal, vat_amount, total)
 */
function fn_service_calculate_invoice_totals($lineItems) {
    $laborTotal = 0;
    $partsTotal = 0;

    foreach ($lineItems as $item) {
        $lineTotal = ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);

        if (($item['item_type'] ?? 'part') === 'service') {
            $laborTotal += $lineTotal;
        } else {
            $partsTotal += $lineTotal;
        }
    }

    $subtotal = $laborTotal + $partsTotal;
    $vatAmount = $subtotal * 0.23; // 23% VAT
    $total = $subtotal + $vatAmount;

    return [
        'labor_total' => round($laborTotal, 2),
        'parts_total' => round($partsTotal, 2),
        'subtotal' => round($subtotal, 2),
        'vat_amount' => round($vatAmount, 2),
        'total' => round($total, 2)
    ];
}

/**
 * Update stock quantities from invoice line items
 *
 * @param array $lineItems Line items
 * @param int $companyId Company ID
 * @return void
 */
function fn_service_update_stock_from_invoice($lineItems, $companyId) {
    foreach ($lineItems as $item) {
        // Only update stock for parts (not services/labor)
        if (($item['item_type'] ?? 'part') === 'part' && !empty($item['item_id'])) {
            $quantity = -abs($item['quantity'] ?? 0); // Negative = remove from stock
            fn_service_item_update_stock($item['item_id'], $companyId, $quantity);
        }
    }
}

/**
 * Update invoice payment status
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @param string $paymentStatus Payment status (unpaid, partial, paid)
 * @param float $paidAmount Amount paid
 * @param string $paymentMethod Payment method
 * @return bool Success
 */
function fn_service_invoice_update_payment($invoiceId, $companyId, $paymentStatus, $paidAmount, $paymentMethod = null) {
    $query = "UPDATE service_invoices SET
        payment_status = ?,
        paid_amount = ?,
        payment_method = ?,
        paid_date = NOW(),
        status = IF(? = 'paid', 'paid', status)
        WHERE service_invoice_id = ? AND company_id = ?";

    $params = [$paymentStatus, $paidAmount, $paymentMethod, $paymentStatus, $invoiceId, $companyId];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Get service invoice stats for a company
 *
 * @param int $companyId Company ID
 * @return array Stats
 */
function fn_service_invoice_stats($companyId) {
    $query = "SELECT
        COUNT(*) as total_invoices,
        SUM(CASE WHEN payment_status = 'paid' THEN total ELSE 0 END) as total_revenue,
        SUM(CASE WHEN payment_status = 'unpaid' THEN total ELSE 0 END) as outstanding,
        SUM(CASE WHEN payment_status = 'partial' THEN (total - paid_amount) ELSE 0 END) as partial_outstanding,
        AVG(total) as average_invoice_value
        FROM service_invoices
        WHERE company_id = ?";

    return fn_core_database_row($query, [$companyId]);
}

/**
 * Get revenue by month
 *
 * @param int $companyId Company ID
 * @param int $months Number of months to look back
 * @return array Monthly revenue data
 */
function fn_service_revenue_by_month($companyId, $months = 12) {
    $query = "SELECT
        DATE_FORMAT(invoice_date, '%Y-%m') as month,
        SUM(total) as revenue,
        COUNT(*) as invoice_count
        FROM service_invoices
        WHERE company_id = ?
        AND invoice_date >= DATE_SUB(NOW(), INTERVAL ? MONTH)
        AND payment_status = 'paid'
        GROUP BY DATE_FORMAT(invoice_date, '%Y-%m')
        ORDER BY month DESC";

    return fn_core_database_rows($query, [$companyId, $months]);
}
