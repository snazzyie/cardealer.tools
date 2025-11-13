<?php
/**
 * Calendar & Appointment Functions
 *
 * Handle appointment scheduling and calendar management
 */

/**
 * Get appointments for a company
 *
 * @param int $companyId Company ID
 * @param array $filters Optional filters
 * @return array Appointments
 */
function fn_calendar_get_appointments($companyId, $filters = []) {
    $query = "SELECT a.*,
              c.first_name as customer_first_name, c.last_name as customer_last_name, c.email as customer_email, c.phone as customer_phone,
              v.make, v.model, v.year,
              u.first_name as staff_first_name, u.last_name as staff_last_name
              FROM calendar_appointments a
              LEFT JOIN customers c ON a.customer_id = c.customer_id
              LEFT JOIN vehicles v ON a.vehicle_id = v.vehicle_id
              LEFT JOIN users u ON a.assigned_to = u.user_id
              WHERE a.company_id = ?";

    $params = [$companyId];

    // Filter by date range
    if (!empty($filters['start_date'])) {
        $query .= " AND a.appointment_date >= ?";
        $params[] = $filters['start_date'];
    }

    if (!empty($filters['end_date'])) {
        $query .= " AND a.appointment_date <= ?";
        $params[] = $filters['end_date'];
    }

    // Filter by status
    if (!empty($filters['status'])) {
        $query .= " AND a.status = ?";
        $params[] = $filters['status'];
    }

    // Filter by type
    if (!empty($filters['appointment_type'])) {
        $query .= " AND a.appointment_type = ?";
        $params[] = $filters['appointment_type'];
    }

    $query .= " ORDER BY a.appointment_date ASC, a.appointment_time ASC";

    return fn_core_database_rows($query, $params);
}

/**
 * Get appointment by ID
 *
 * @param int $appointmentId Appointment ID
 * @param int $companyId Company ID
 * @return array|null Appointment data
 */
function fn_calendar_get_appointment($appointmentId, $companyId) {
    $query = "SELECT a.*,
              c.first_name as customer_first_name, c.last_name as customer_last_name, c.email as customer_email, c.phone as customer_phone,
              v.make, v.model, v.year, v.price
              FROM calendar_appointments a
              LEFT JOIN customers c ON a.customer_id = c.customer_id
              LEFT JOIN vehicles v ON a.vehicle_id = v.vehicle_id
              WHERE a.appointment_id = ? AND a.company_id = ?";

    return fn_core_database_row($query, [$appointmentId, $companyId]);
}

/**
 * Create appointment
 *
 * @param int $companyId Company ID
 * @param array $data Appointment data
 * @return int|false Appointment ID or false
 */
function fn_calendar_create_appointment($companyId, $data) {
    $query = "INSERT INTO calendar_appointments (
        company_id, customer_id, vehicle_id, appointment_type,
        appointment_date, appointment_time, duration_minutes,
        assigned_to, location, notes, status, google_calendar_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['customer_id'] ?? null,
        $data['vehicle_id'] ?? null,
        $data['appointment_type'] ?? 'test_drive',
        $data['appointment_date'],
        $data['appointment_time'],
        $data['duration_minutes'] ?? 30,
        $data['assigned_to'] ?? null,
        $data['location'] ?? '',
        $data['notes'] ?? '',
        $data['status'] ?? 'scheduled',
        $data['google_calendar_id'] ?? null
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update appointment
 *
 * @param int $appointmentId Appointment ID
 * @param int $companyId Company ID
 * @param array $data Appointment data
 * @return bool Success
 */
function fn_calendar_update_appointment($appointmentId, $companyId, $data) {
    $query = "UPDATE calendar_appointments SET
        customer_id = ?, vehicle_id = ?, appointment_type = ?,
        appointment_date = ?, appointment_time = ?, duration_minutes = ?,
        assigned_to = ?, location = ?, notes = ?, status = ?
    WHERE appointment_id = ? AND company_id = ?";

    $params = [
        $data['customer_id'] ?? null,
        $data['vehicle_id'] ?? null,
        $data['appointment_type'] ?? 'test_drive',
        $data['appointment_date'],
        $data['appointment_time'],
        $data['duration_minutes'] ?? 30,
        $data['assigned_to'] ?? null,
        $data['location'] ?? '',
        $data['notes'] ?? '',
        $data['status'] ?? 'scheduled',
        $appointmentId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update appointment status
 *
 * @param int $appointmentId Appointment ID
 * @param int $companyId Company ID
 * @param string $status Status
 * @return bool Success
 */
function fn_calendar_update_status($appointmentId, $companyId, $status) {
    $query = "UPDATE calendar_appointments SET status = ? WHERE appointment_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$status, $appointmentId, $companyId]);
}

/**
 * Delete appointment
 *
 * @param int $appointmentId Appointment ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_calendar_delete_appointment($appointmentId, $companyId) {
    $query = "DELETE FROM calendar_appointments WHERE appointment_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$appointmentId, $companyId]);
}

/**
 * Get appointments for a specific date
 *
 * @param int $companyId Company ID
 * @param string $date Date (Y-m-d)
 * @return array Appointments
 */
function fn_calendar_get_by_date($companyId, $date) {
    $filters = [
        'start_date' => $date,
        'end_date' => $date
    ];
    return fn_calendar_get_appointments($companyId, $filters);
}

/**
 * Get upcoming appointments
 *
 * @param int $companyId Company ID
 * @param int $days Number of days ahead
 * @return array Appointments
 */
function fn_calendar_get_upcoming($companyId, $days = 7) {
    $filters = [
        'start_date' => date('Y-m-d'),
        'end_date' => date('Y-m-d', strtotime("+{$days} days")),
        'status' => 'scheduled'
    ];
    return fn_calendar_get_appointments($companyId, $filters);
}

/**
 * Check if time slot is available
 *
 * @param int $companyId Company ID
 * @param string $date Date
 * @param string $time Time
 * @param int $duration Duration in minutes
 * @param int $excludeAppointmentId Exclude this appointment (for updates)
 * @return bool True if available
 */
function fn_calendar_check_availability($companyId, $date, $time, $duration = 30, $excludeAppointmentId = null) {
    $query = "SELECT COUNT(*) as count FROM calendar_appointments
              WHERE company_id = ?
              AND appointment_date = ?
              AND status IN ('scheduled', 'confirmed')
              AND (
                  (appointment_time <= ? AND DATE_ADD(CONCAT(appointment_date, ' ', appointment_time), INTERVAL duration_minutes MINUTE) > ?)
                  OR
                  (appointment_time < DATE_ADD(?, INTERVAL ? MINUTE) AND appointment_time >= ?)
              )";

    $params = [$companyId, $date, $time, $time, $time, $duration, $time];

    if ($excludeAppointmentId) {
        $query .= " AND appointment_id != ?";
        $params[] = $excludeAppointmentId;
    }

    $result = fn_core_database_row($query, $params);
    return $result['count'] == 0;
}

/**
 * Get appointment statistics
 *
 * @param int $companyId Company ID
 * @return array Stats
 */
function fn_calendar_get_stats($companyId) {
    $today = date('Y-m-d');

    $stats = [];

    // Today's appointments
    $query = "SELECT COUNT(*) as count FROM calendar_appointments
              WHERE company_id = ? AND appointment_date = ? AND status IN ('scheduled', 'confirmed')";
    $result = fn_core_database_row($query, [$companyId, $today]);
    $stats['today'] = $result['count'];

    // This week's appointments
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd = date('Y-m-d', strtotime('sunday this week'));
    $query = "SELECT COUNT(*) as count FROM calendar_appointments
              WHERE company_id = ? AND appointment_date BETWEEN ? AND ? AND status IN ('scheduled', 'confirmed')";
    $result = fn_core_database_row($query, [$companyId, $weekStart, $weekEnd]);
    $stats['this_week'] = $result['count'];

    // Completed this month
    $monthStart = date('Y-m-01');
    $query = "SELECT COUNT(*) as count FROM calendar_appointments
              WHERE company_id = ? AND appointment_date >= ? AND status = 'completed'";
    $result = fn_core_database_row($query, [$companyId, $monthStart]);
    $stats['completed_month'] = $result['count'];

    return $stats;
}

/**
 * Create or get customer
 *
 * @param int $companyId Company ID
 * @param array $data Customer data
 * @return int Customer ID
 */
function fn_calendar_get_or_create_customer($companyId, $data) {
    // Check if customer exists by email
    if (!empty($data['email'])) {
        $query = "SELECT customer_id FROM customers WHERE company_id = ? AND email = ?";
        $existing = fn_core_database_row($query, [$companyId, $data['email']]);

        if ($existing) {
            return $existing['customer_id'];
        }
    }

    // Create new customer
    $query = "INSERT INTO customers (company_id, first_name, last_name, email, phone)
              VALUES (?, ?, ?, ?, ?)";

    return fn_core_insert_row_no_redirect($query, [
        $companyId,
        $data['first_name'] ?? '',
        $data['last_name'] ?? '',
        $data['email'] ?? '',
        $data['phone'] ?? ''
    ]);
}
