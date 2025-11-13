<?php
/**
 * Invoice Management Functions
 *
 * Handle sales invoices, service invoices, and payments
 */

/**
 * Get sales invoices
 *
 * @param int $companyId Company ID
 * @param array $filters Filters
 * @return array Invoices
 */
function fn_invoices_get_sales($companyId, $filters = []) {
    $query = "SELECT si.*, c.first_name, c.last_name, c.email,
              v.make, v.model, v.year, v.registration
              FROM sales_invoices si
              LEFT JOIN customers c ON si.customer_id = c.customer_id
              LEFT JOIN vehicles v ON si.vehicle_id = v.vehicle_id
              WHERE si.company_id = ?";

    $params = [$companyId];

    if (!empty($filters['status'])) {
        $query .= " AND si.status = ?";
        $params[] = $filters['status'];
    }

    $query .= " ORDER BY si.invoice_date DESC LIMIT 100";

    return fn_core_database_rows($query, $params);
}

/**
 * Get service invoices
 *
 * @param int $companyId Company ID
 * @param array $filters Filters
 * @return array Invoices
 */
function fn_invoices_get_service($companyId, $filters = []) {
    $query = "SELECT si.*, c.first_name, c.last_name, c.email, c.phone
              FROM service_invoices si
              LEFT JOIN customers c ON si.customer_id = c.customer_id
              WHERE si.company_id = ?";

    $params = [$companyId];

    if (!empty($filters['status'])) {
        $query .= " AND si.status = ?";
        $params[] = $filters['status'];
    }

    $query .= " ORDER BY si.invoice_date DESC LIMIT 100";

    return fn_core_database_rows($query, $params);
}

/**
 * Get sales invoice by ID
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @return array|null Invoice data
 */
function fn_invoices_get_sales_invoice($invoiceId, $companyId) {
    $query = "SELECT si.*, c.first_name, c.last_name, c.email, c.phone, c.address,
              v.make, v.model, v.year, v.registration, v.vin
              FROM sales_invoices si
              LEFT JOIN customers c ON si.customer_id = c.customer_id
              LEFT JOIN vehicles v ON si.vehicle_id = v.vehicle_id
              WHERE si.invoice_id = ? AND si.company_id = ?";

    return fn_core_database_row($query, [$invoiceId, $companyId]);
}

/**
 * Get service invoice by ID
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @return array|null Invoice data
 */
function fn_invoices_get_service_invoice($invoiceId, $companyId) {
    $query = "SELECT si.*, c.first_name, c.last_name, c.email, c.phone, c.address
              FROM service_invoices si
              LEFT JOIN customers c ON si.customer_id = c.customer_id
              WHERE si.invoice_id = ? AND si.company_id = ?";

    return fn_core_database_row($query, [$invoiceId, $companyId]);
}

/**
 * Create sales invoice
 *
 * @param int $companyId Company ID
 * @param array $data Invoice data
 * @return int|false Invoice ID or false
 */
function fn_invoices_create_sales($companyId, $data) {
    $query = "INSERT INTO sales_invoices (
        company_id, customer_id, vehicle_id, invoice_number,
        invoice_date, due_date, sale_price, deposit_amount,
        trade_in_value, discount_amount, vat_amount, total_amount,
        payment_method, status, notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Calculate total
    $salePrice = floatval($data['sale_price'] ?? 0);
    $depositAmount = floatval($data['deposit_amount'] ?? 0);
    $tradeInValue = floatval($data['trade_in_value'] ?? 0);
    $discountAmount = floatval($data['discount_amount'] ?? 0);
    $vatAmount = floatval($data['vat_amount'] ?? 0);

    $totalAmount = $salePrice - $tradeInValue - $discountAmount + $vatAmount;

    $params = [
        $companyId,
        $data['customer_id'],
        $data['vehicle_id'],
        $data['invoice_number'] ?? fn_invoices_generate_invoice_number($companyId, 'SALE'),
        $data['invoice_date'] ?? date('Y-m-d'),
        $data['due_date'] ?? date('Y-m-d', strtotime('+30 days')),
        $salePrice,
        $depositAmount,
        $tradeInValue,
        $discountAmount,
        $vatAmount,
        $totalAmount,
        $data['payment_method'] ?? 'bank_transfer',
        $data['status'] ?? 'pending',
        $data['notes'] ?? ''
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Create service invoice
 *
 * @param int $companyId Company ID
 * @param array $data Invoice data
 * @return int|false Invoice ID or false
 */
function fn_invoices_create_service($companyId, $data) {
    $query = "INSERT INTO service_invoices (
        company_id, customer_id, invoice_number, invoice_date,
        due_date, subtotal, vat_amount, total_amount,
        payment_method, status, notes, vehicle_registration
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $subtotal = floatval($data['subtotal'] ?? 0);
    $vatAmount = floatval($data['vat_amount'] ?? 0);
    $totalAmount = $subtotal + $vatAmount;

    $params = [
        $companyId,
        $data['customer_id'],
        $data['invoice_number'] ?? fn_invoices_generate_invoice_number($companyId, 'SVC'),
        $data['invoice_date'] ?? date('Y-m-d'),
        $data['due_date'] ?? date('Y-m-d', strtotime('+14 days')),
        $subtotal,
        $vatAmount,
        $totalAmount,
        $data['payment_method'] ?? 'card',
        $data['status'] ?? 'pending',
        $data['notes'] ?? '',
        $data['vehicle_registration'] ?? ''
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update invoice status
 *
 * @param int $invoiceId Invoice ID
 * @param int $companyId Company ID
 * @param string $status Status
 * @param string $type Type (sales or service)
 * @return bool Success
 */
function fn_invoices_update_status($invoiceId, $companyId, $status, $type = 'sales') {
    $table = $type === 'sales' ? 'sales_invoices' : 'service_invoices';
    $query = "UPDATE {$table} SET status = ? WHERE invoice_id = ? AND company_id = ?";

    $result = fn_core_edit_row_no_redirect($query, [$status, $invoiceId, $companyId]);

    // If marking as paid, update paid_date
    if ($result && $status === 'paid') {
        $query = "UPDATE {$table} SET paid_date = NOW() WHERE invoice_id = ? AND company_id = ?";
        fn_core_edit_row_no_redirect($query, [$invoiceId, $companyId]);
    }

    return $result;
}

/**
 * Generate invoice number
 *
 * @param int $companyId Company ID
 * @param string $prefix Prefix (SALE or SVC)
 * @return string Invoice number
 */
function fn_invoices_generate_invoice_number($companyId, $prefix = 'SALE') {
    $year = date('Y');
    $month = date('m');

    // Count invoices this month
    if ($prefix === 'SALE') {
        $query = "SELECT COUNT(*) as count FROM sales_invoices
                  WHERE company_id = ? AND YEAR(invoice_date) = ? AND MONTH(invoice_date) = ?";
    } else {
        $query = "SELECT COUNT(*) as count FROM service_invoices
                  WHERE company_id = ? AND YEAR(invoice_date) = ? AND MONTH(invoice_date) = ?";
    }

    $result = fn_core_database_row($query, [$companyId, $year, $month]);
    $count = $result['count'] + 1;

    return $prefix . '-' . $year . $month . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
}

/**
 * Add service item to invoice
 *
 * @param int $invoiceId Invoice ID
 * @param array $data Item data
 * @return int|false Item ID or false
 */
function fn_invoices_add_service_item($invoiceId, $data) {
    $query = "INSERT INTO service_items (
        invoice_id, description, quantity, unit_price, total_price
    ) VALUES (?, ?, ?, ?, ?)";

    $quantity = floatval($data['quantity'] ?? 1);
    $unitPrice = floatval($data['unit_price'] ?? 0);
    $totalPrice = $quantity * $unitPrice;

    return fn_core_insert_row_no_redirect($query, [
        $invoiceId,
        $data['description'] ?? '',
        $quantity,
        $unitPrice,
        $totalPrice
    ]);
}

/**
 * Get service items for invoice
 *
 * @param int $invoiceId Invoice ID
 * @return array Items
 */
function fn_invoices_get_service_items($invoiceId) {
    $query = "SELECT * FROM service_items WHERE invoice_id = ? ORDER BY item_id ASC";
    return fn_core_database_rows($query, [$invoiceId]);
}

/**
 * Get invoice statistics
 *
 * @param int $companyId Company ID
 * @return array Stats
 */
function fn_invoices_get_stats($companyId) {
    $stats = [];

    // Total sales this month
    $query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM sales_invoices
              WHERE company_id = ? AND MONTH(invoice_date) = MONTH(CURDATE()) AND YEAR(invoice_date) = YEAR(CURDATE())
              AND status = 'paid'";
    $result = fn_core_database_row($query, [$companyId]);
    $stats['sales_this_month'] = $result['total'];

    // Pending payments
    $query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM sales_invoices
              WHERE company_id = ? AND status IN ('pending', 'sent')";
    $result = fn_core_database_row($query, [$companyId]);
    $stats['pending_payments'] = $result['total'];

    // Service revenue this month
    $query = "SELECT COALESCE(SUM(total_amount), 0) as total FROM service_invoices
              WHERE company_id = ? AND MONTH(invoice_date) = MONTH(CURDATE()) AND YEAR(invoice_date) = YEAR(CURDATE())
              AND status = 'paid'";
    $result = fn_core_database_row($query, [$companyId]);
    $stats['service_revenue'] = $result['total'];

    return $stats;
}

/**
 * Record vehicle deposit via Stripe
 *
 * @param int $companyId Company ID
 * @param array $data Deposit data
 * @return int|false Deposit ID or false
 */
function fn_invoices_record_deposit($companyId, $data) {
    $query = "INSERT INTO vehicle_deposits (
        company_id, vehicle_id, customer_id, amount,
        stripe_payment_id, stripe_charge_id, status
    ) VALUES (?, ?, ?, ?, ?, ?, ?)";

    return fn_core_insert_row_no_redirect($query, [
        $companyId,
        $data['vehicle_id'],
        $data['customer_id'] ?? null,
        $data['amount'],
        $data['stripe_payment_id'] ?? null,
        $data['stripe_charge_id'] ?? null,
        $data['status'] ?? 'completed'
    ]);
}
