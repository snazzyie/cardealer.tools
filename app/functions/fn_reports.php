<?php
/**
 * Reports & Analytics Functions
 *
 * Generate business reports and analytics data
 */

/**
 * ====================
 * SALES REPORTS
 * ====================
 */

/**
 * Get sales report for date range
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date (Y-m-d)
 * @param string $endDate End date (Y-m-d)
 * @return array Sales report data
 */
function fn_reports_get_sales($companyId, $startDate, $endDate) {
    $query = "SELECT
        COUNT(*) as total_sales,
        SUM(total_amount) as total_revenue,
        AVG(total_amount) as average_sale_value,
        SUM(profit_margin) as total_profit,
        MIN(total_amount) as min_sale,
        MAX(total_amount) as max_sale
    FROM sales_invoices
    WHERE company_id = ?
    AND invoice_date BETWEEN ? AND ?
    AND status = 'paid'";

    return fn_core_database_row($query, [$companyId, $startDate, $endDate]);
}

/**
 * Get sales by month
 *
 * @param int $companyId Company ID
 * @param int $months Number of months to look back
 * @return array Monthly sales data
 */
function fn_reports_get_sales_by_month($companyId, $months = 12) {
    $query = "SELECT
        DATE_FORMAT(invoice_date, '%Y-%m') as month,
        DATE_FORMAT(invoice_date, '%b %Y') as month_label,
        COUNT(*) as sale_count,
        SUM(total_amount) as revenue
    FROM sales_invoices
    WHERE company_id = ?
    AND invoice_date >= DATE_SUB(NOW(), INTERVAL ? MONTH)
    AND status = 'paid'
    GROUP BY DATE_FORMAT(invoice_date, '%Y-%m')
    ORDER BY month ASC";

    return fn_core_database_rows($query, [$companyId, $months]);
}

/**
 * Get sales by vehicle type
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Sales by vehicle type
 */
function fn_reports_get_sales_by_vehicle_type($companyId, $startDate, $endDate) {
    $query = "SELECT
        v.body_type,
        COUNT(*) as sale_count,
        SUM(si.total_amount) as revenue
    FROM sales_invoices si
    INNER JOIN vehicles v ON si.vehicle_id = v.vehicle_id
    WHERE si.company_id = ?
    AND si.invoice_date BETWEEN ? AND ?
    AND si.status = 'paid'
    GROUP BY v.body_type
    ORDER BY sale_count DESC";

    return fn_core_database_rows($query, [$companyId, $startDate, $endDate]);
}

/**
 * Get sales by make
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Sales by make
 */
function fn_reports_get_sales_by_make($companyId, $startDate, $endDate) {
    $query = "SELECT
        v.make,
        COUNT(*) as sale_count,
        SUM(si.total_amount) as revenue
    FROM sales_invoices si
    INNER JOIN vehicles v ON si.vehicle_id = v.vehicle_id
    WHERE si.company_id = ?
    AND si.invoice_date BETWEEN ? AND ?
    AND si.status = 'paid'
    GROUP BY v.make
    ORDER BY sale_count DESC
    LIMIT 10";

    return fn_core_database_rows($query, [$companyId, $startDate, $endDate]);
}

/**
 * Get top selling vehicles
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @param int $limit Limit
 * @return array Top selling vehicles
 */
function fn_reports_get_top_selling_vehicles($companyId, $startDate, $endDate, $limit = 10) {
    $query = "SELECT
        v.make, v.model, v.year,
        COUNT(*) as sale_count,
        SUM(si.total_amount) as revenue
    FROM sales_invoices si
    INNER JOIN vehicles v ON si.vehicle_id = v.vehicle_id
    WHERE si.company_id = ?
    AND si.invoice_date BETWEEN ? AND ?
    AND si.status = 'paid'
    GROUP BY v.make, v.model, v.year
    ORDER BY sale_count DESC
    LIMIT ?";

    return fn_core_database_rows($query, [$companyId, $startDate, $endDate, $limit]);
}

/**
 * ====================
 * ENQUIRY REPORTS
 * ====================
 */

/**
 * Get enquiries report
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Enquiries report data
 */
function fn_reports_get_enquiries($companyId, $startDate, $endDate) {
    $query = "SELECT
        COUNT(*) as total_enquiries,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_enquiries,
        SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted,
        SUM(CASE WHEN status = 'converted' THEN 1 ELSE 0 END) as converted,
        SUM(CASE WHEN enquiry_type = 'test_drive' THEN 1 ELSE 0 END) as test_drive_requests
    FROM enquiries
    WHERE company_id = ?
    AND created_date BETWEEN ? AND ?";

    return fn_core_database_row($query, [$companyId, $startDate, $endDate]);
}

/**
 * Get enquiries by month
 *
 * @param int $companyId Company ID
 * @param int $months Number of months
 * @return array Monthly enquiry data
 */
function fn_reports_get_enquiries_by_month($companyId, $months = 12) {
    $query = "SELECT
        DATE_FORMAT(created_date, '%Y-%m') as month,
        DATE_FORMAT(created_date, '%b %Y') as month_label,
        COUNT(*) as enquiry_count
    FROM enquiries
    WHERE company_id = ?
    AND created_date >= DATE_SUB(NOW(), INTERVAL ? MONTH)
    GROUP BY DATE_FORMAT(created_date, '%Y-%m')
    ORDER BY month ASC";

    return fn_core_database_rows($query, [$companyId, $months]);
}

/**
 * Get enquiries by source
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Enquiries by source
 */
function fn_reports_get_enquiries_by_source($companyId, $startDate, $endDate) {
    $query = "SELECT
        source,
        COUNT(*) as enquiry_count
    FROM enquiries
    WHERE company_id = ?
    AND created_date BETWEEN ? AND ?
    GROUP BY source
    ORDER BY enquiry_count DESC";

    return fn_core_database_rows($query, [$companyId, $startDate, $endDate]);
}

/**
 * Get most enquired vehicles
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @param int $limit Limit
 * @return array Most enquired vehicles
 */
function fn_reports_get_most_enquired_vehicles($companyId, $startDate, $endDate, $limit = 10) {
    $query = "SELECT
        v.make, v.model, v.year, v.price,
        COUNT(e.enquiry_id) as enquiry_count
    FROM vehicles v
    LEFT JOIN enquiries e ON v.vehicle_id = e.vehicle_id AND e.created_date BETWEEN ? AND ?
    WHERE v.company_id = ?
    GROUP BY v.vehicle_id
    HAVING enquiry_count > 0
    ORDER BY enquiry_count DESC
    LIMIT ?";

    return fn_core_database_rows($query, [$startDate, $endDate, $companyId, $limit]);
}

/**
 * ====================
 * LEAD & CRM REPORTS
 * ====================
 */

/**
 * Get leads report
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Leads report data
 */
function fn_reports_get_leads($companyId, $startDate, $endDate) {
    $query = "SELECT
        COUNT(*) as total_leads,
        SUM(CASE WHEN status = 'new' THEN 1 ELSE 0 END) as new_leads,
        SUM(CASE WHEN status = 'contacted' THEN 1 ELSE 0 END) as contacted,
        SUM(CASE WHEN status = 'qualified' THEN 1 ELSE 0 END) as qualified,
        SUM(CASE WHEN status = 'proposal' THEN 1 ELSE 0 END) as proposal,
        SUM(CASE WHEN status = 'negotiation' THEN 1 ELSE 0 END) as negotiation,
        SUM(CASE WHEN status = 'won' THEN 1 ELSE 0 END) as won,
        SUM(CASE WHEN status = 'lost' THEN 1 ELSE 0 END) as lost
    FROM crm_leads
    WHERE company_id = ?
    AND created_date BETWEEN ? AND ?";

    return fn_core_database_row($query, [$companyId, $startDate, $endDate]);
}

/**
 * Get conversion rate (leads to sales)
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return float Conversion rate percentage
 */
function fn_reports_get_conversion_rate($companyId, $startDate, $endDate) {
    $query = "SELECT
        COUNT(*) as total_closed,
        SUM(CASE WHEN status = 'won' THEN 1 ELSE 0 END) as won
    FROM crm_leads
    WHERE company_id = ?
    AND created_date BETWEEN ? AND ?
    AND status IN ('won', 'lost')";

    $result = fn_core_database_row($query, [$companyId, $startDate, $endDate]);

    if (!$result || $result['total_closed'] == 0) {
        return 0;
    }

    return round(($result['won'] / $result['total_closed']) * 100, 1);
}

/**
 * Get sales by user/salesperson
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Sales by user
 */
function fn_reports_get_sales_by_user($companyId, $startDate, $endDate) {
    $query = "SELECT
        u.user_id,
        u.first_name,
        u.last_name,
        COUNT(si.invoice_id) as sale_count,
        SUM(si.total_amount) as total_revenue
    FROM users u
    LEFT JOIN sales_invoices si ON u.user_id = si.salesperson_id
        AND si.invoice_date BETWEEN ? AND ?
        AND si.status = 'paid'
    WHERE u.company_id = ?
    AND u.user_role IN ('sales', 'manager', 'admin', 'owner')
    AND u.status = 'active'
    GROUP BY u.user_id
    ORDER BY total_revenue DESC";

    return fn_core_database_rows($query, [$startDate, $endDate, $companyId]);
}

/**
 * ====================
 * INVENTORY REPORTS
 * ====================
 */

/**
 * Get inventory report
 *
 * @param int $companyId Company ID
 * @return array Inventory report data
 */
function fn_reports_get_inventory($companyId) {
    $query = "SELECT
        COUNT(*) as total_vehicles,
        SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
        SUM(CASE WHEN status = 'sold' THEN 1 ELSE 0 END) as sold,
        SUM(CASE WHEN status = 'reserved' THEN 1 ELSE 0 END) as reserved,
        SUM(CASE WHEN status = 'available' THEN price ELSE 0 END) as total_inventory_value,
        AVG(CASE WHEN status = 'available' THEN price ELSE NULL END) as average_price
    FROM vehicles
    WHERE company_id = ?";

    return fn_core_database_row($query, [$companyId]);
}

/**
 * Get inventory by make
 *
 * @param int $companyId Company ID
 * @return array Inventory by make
 */
function fn_reports_get_inventory_by_make($companyId) {
    $query = "SELECT
        make,
        COUNT(*) as vehicle_count,
        SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available,
        SUM(CASE WHEN status = 'available' THEN price ELSE 0 END) as total_value
    FROM vehicles
    WHERE company_id = ?
    GROUP BY make
    ORDER BY vehicle_count DESC";

    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Get inventory by body type
 *
 * @param int $companyId Company ID
 * @return array Inventory by body type
 */
function fn_reports_get_inventory_by_body_type($companyId) {
    $query = "SELECT
        body_type,
        COUNT(*) as vehicle_count,
        SUM(CASE WHEN status = 'available' THEN 1 ELSE 0 END) as available
    FROM vehicles
    WHERE company_id = ?
    GROUP BY body_type
    ORDER BY vehicle_count DESC";

    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Get aging inventory (vehicles not sold after X days)
 *
 * @param int $companyId Company ID
 * @param int $days Days threshold
 * @return array Aging inventory
 */
function fn_reports_get_aging_inventory($companyId, $days = 90) {
    $query = "SELECT
        vehicle_id, make, model, year, price, registration,
        DATEDIFF(NOW(), date_added) as days_in_inventory
    FROM vehicles
    WHERE company_id = ?
    AND status = 'available'
    AND DATEDIFF(NOW(), date_added) >= ?
    ORDER BY days_in_inventory DESC";

    return fn_core_database_rows($query, [$companyId, $days]);
}

/**
 * ====================
 * APPOINTMENT REPORTS
 * ====================
 */

/**
 * Get appointments report
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Appointments report data
 */
function fn_reports_get_appointments($companyId, $startDate, $endDate) {
    $query = "SELECT
        COUNT(*) as total_appointments,
        SUM(CASE WHEN status = 'scheduled' THEN 1 ELSE 0 END) as scheduled,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(CASE WHEN status = 'no_show' THEN 1 ELSE 0 END) as no_shows
    FROM calendar_appointments
    WHERE company_id = ?
    AND DATE(start_datetime) BETWEEN ? AND ?";

    return fn_core_database_row($query, [$companyId, $startDate, $endDate]);
}

/**
 * Get appointments by type
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Appointments by type
 */
function fn_reports_get_appointments_by_type($companyId, $startDate, $endDate) {
    $query = "SELECT
        appointment_type,
        COUNT(*) as appointment_count
    FROM calendar_appointments
    WHERE company_id = ?
    AND DATE(start_datetime) BETWEEN ? AND ?
    GROUP BY appointment_type
    ORDER BY appointment_count DESC";

    return fn_core_database_rows($query, [$companyId, $startDate, $endDate]);
}

/**
 * ====================
 * SERVICE REPORTS
 * ====================
 */

/**
 * Get service revenue report
 *
 * @param int $companyId Company ID
 * @param string $startDate Start date
 * @param string $endDate End date
 * @return array Service revenue data
 */
function fn_reports_get_service_revenue($companyId, $startDate, $endDate) {
    $query = "SELECT
        COUNT(*) as total_invoices,
        SUM(total) as total_revenue,
        SUM(labor_total) as labor_revenue,
        SUM(parts_total) as parts_revenue,
        AVG(total) as average_invoice_value
    FROM service_invoices
    WHERE company_id = ?
    AND invoice_date BETWEEN ? AND ?
    AND payment_status = 'paid'";

    return fn_core_database_row($query, [$companyId, $startDate, $endDate]);
}

/**
 * ====================
 * DASHBOARD STATISTICS
 * ====================
 */

/**
 * Get dashboard statistics
 *
 * @param int $companyId Company ID
 * @return array Dashboard stats
 */
function fn_reports_get_dashboard_stats($companyId) {
    $stats = [];

    // This month dates
    $monthStart = date('Y-m-01');
    $monthEnd = date('Y-m-t');
    $today = date('Y-m-d');

    // Sales this month
    $salesQuery = "SELECT COUNT(*) as count, SUM(total_amount) as revenue
                   FROM sales_invoices
                   WHERE company_id = ? AND invoice_date BETWEEN ? AND ? AND status = 'paid'";
    $salesData = fn_core_database_row($salesQuery, [$companyId, $monthStart, $monthEnd]);
    $stats['sales_this_month'] = $salesData['count'] ?? 0;
    $stats['revenue_this_month'] = $salesData['revenue'] ?? 0;

    // New enquiries this month
    $enquiriesQuery = "SELECT COUNT(*) as count FROM enquiries
                       WHERE company_id = ? AND created_date BETWEEN ? AND ?";
    $enquiriesData = fn_core_database_row($enquiriesQuery, [$companyId, $monthStart, $monthEnd]);
    $stats['enquiries_this_month'] = $enquiriesData['count'] ?? 0;

    // Active leads
    $leadsQuery = "SELECT COUNT(*) as count FROM crm_leads
                   WHERE company_id = ? AND status NOT IN ('won', 'lost')";
    $leadsData = fn_core_database_row($leadsQuery, [$companyId]);
    $stats['active_leads'] = $leadsData['count'] ?? 0;

    // Available vehicles
    $vehiclesQuery = "SELECT COUNT(*) as count FROM vehicles
                      WHERE company_id = ? AND status = 'available'";
    $vehiclesData = fn_core_database_row($vehiclesQuery, [$companyId]);
    $stats['available_vehicles'] = $vehiclesData['count'] ?? 0;

    // Today's appointments
    $appointmentsQuery = "SELECT COUNT(*) as count FROM calendar_appointments
                          WHERE company_id = ? AND DATE(start_datetime) = ? AND status IN ('scheduled', 'confirmed')";
    $appointmentsData = fn_core_database_row($appointmentsQuery, [$companyId, $today]);
    $stats['todays_appointments'] = $appointmentsData['count'] ?? 0;

    return $stats;
}

/**
 * Get year-over-year comparison
 *
 * @param int $companyId Company ID
 * @return array YoY comparison data
 */
function fn_reports_get_year_over_year($companyId) {
    $thisYear = date('Y');
    $lastYear = $thisYear - 1;

    $query = "SELECT
        YEAR(invoice_date) as year,
        COUNT(*) as sale_count,
        SUM(total_amount) as revenue
    FROM sales_invoices
    WHERE company_id = ?
    AND YEAR(invoice_date) IN (?, ?)
    AND status = 'paid'
    GROUP BY YEAR(invoice_date)";

    return fn_core_database_rows($query, [$companyId, $lastYear, $thisYear]);
}

/**
 * Export report data to CSV format
 *
 * @param array $data Report data
 * @param array $headers Column headers
 * @return string CSV content
 */
function fn_reports_export_csv($data, $headers) {
    $output = implode(',', $headers) . "\n";

    foreach ($data as $row) {
        $values = array_map(function($value) {
            // Escape values with commas or quotes
            if (strpos($value, ',') !== false || strpos($value, '"') !== false) {
                return '"' . str_replace('"', '""', $value) . '"';
            }
            return $value;
        }, array_values($row));

        $output .= implode(',', $values) . "\n";
    }

    return $output;
}
