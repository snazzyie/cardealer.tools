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
        $query .= " AND DATE(a.start_datetime) >= ?";
        $params[] = $filters['start_date'];
    }

    if (!empty($filters['end_date'])) {
        $query .= " AND DATE(a.start_datetime) <= ?";
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

    $query .= " ORDER BY a.start_datetime ASC";

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
        company_id, customer_id, lead_id, vehicle_id, appointment_type,
        title, description, location, start_datetime, end_datetime,
        assigned_to, status, notes, google_calendar_event_id
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['customer_id'] ?? null,
        $data['lead_id'] ?? null,
        $data['vehicle_id'] ?? null,
        $data['appointment_type'] ?? 'test-drive',
        $data['title'] ?? '',
        $data['description'] ?? '',
        $data['location'] ?? '',
        $data['start_datetime'],
        $data['end_datetime'],
        $data['assigned_to'] ?? null,
        $data['status'] ?? 'scheduled',
        $data['notes'] ?? '',
        $data['google_calendar_event_id'] ?? null
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
        customer_id = ?, lead_id = ?, vehicle_id = ?, appointment_type = ?,
        title = ?, description = ?, location = ?,
        start_datetime = ?, end_datetime = ?,
        assigned_to = ?, status = ?, notes = ?
    WHERE appointment_id = ? AND company_id = ?";

    $params = [
        $data['customer_id'] ?? null,
        $data['lead_id'] ?? null,
        $data['vehicle_id'] ?? null,
        $data['appointment_type'] ?? 'test-drive',
        $data['title'] ?? '',
        $data['description'] ?? '',
        $data['location'] ?? '',
        $data['start_datetime'],
        $data['end_datetime'],
        $data['assigned_to'] ?? null,
        $data['status'] ?? 'scheduled',
        $data['notes'] ?? '',
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
 * @param string $startDatetime Start datetime (Y-m-d H:i:s)
 * @param string $endDatetime End datetime (Y-m-d H:i:s)
 * @param int $excludeAppointmentId Exclude this appointment (for updates)
 * @return bool True if available
 */
function fn_calendar_check_availability($companyId, $startDatetime, $endDatetime, $excludeAppointmentId = null) {
    $query = "SELECT COUNT(*) as count FROM calendar_appointments
              WHERE company_id = ?
              AND status IN ('scheduled', 'confirmed')
              AND (
                  (start_datetime < ? AND end_datetime > ?)
                  OR
                  (start_datetime >= ? AND start_datetime < ?)
              )";

    $params = [$companyId, $endDatetime, $startDatetime, $startDatetime, $endDatetime];

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
              WHERE company_id = ? AND DATE(start_datetime) = ? AND status IN ('scheduled', 'confirmed')";
    $result = fn_core_database_row($query, [$companyId, $today]);
    $stats['today'] = $result['count'];

    // This week's appointments
    $weekStart = date('Y-m-d', strtotime('monday this week'));
    $weekEnd = date('Y-m-d', strtotime('sunday this week'));
    $query = "SELECT COUNT(*) as count FROM calendar_appointments
              WHERE company_id = ? AND DATE(start_datetime) BETWEEN ? AND ? AND status IN ('scheduled', 'confirmed')";
    $result = fn_core_database_row($query, [$companyId, $weekStart, $weekEnd]);
    $stats['this_week'] = $result['count'];

    // Completed this month
    $monthStart = date('Y-m-01');
    $query = "SELECT COUNT(*) as count FROM calendar_appointments
              WHERE company_id = ? AND DATE(start_datetime) >= ? AND status = 'completed'";
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

/**
 * ====================
 * GOOGLE CALENDAR INTEGRATION
 * ====================
 */

/**
 * Get Google Calendar OAuth2 URL
 *
 * @param int $userId User ID
 * @return string Authorization URL
 */
function fn_calendar_google_get_auth_url($userId) {
    global $config;

    if (!isset($config['google_calendar'])) {
        return false;
    }

    $client = new Google_Client();
    $client->setClientId($config['google_calendar']['client_id']);
    $client->setClientSecret($config['google_calendar']['client_secret']);
    $client->setRedirectUri($config['google_calendar']['redirect_uri']);
    $client->setScopes([Google_Service_Calendar::CALENDAR]);
    $client->setAccessType('offline');
    $client->setPrompt('consent');
    $client->setState($userId);

    return $client->createAuthUrl();
}

/**
 * Handle Google Calendar OAuth2 callback
 *
 * @param string $code Authorization code
 * @param int $userId User ID
 * @return bool Success
 */
function fn_calendar_google_handle_callback($code, $userId) {
    global $config;

    if (!isset($config['google_calendar'])) {
        return false;
    }

    try {
        $client = new Google_Client();
        $client->setClientId($config['google_calendar']['client_id']);
        $client->setClientSecret($config['google_calendar']['client_secret']);
        $client->setRedirectUri($config['google_calendar']['redirect_uri']);

        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            error_log("Google Calendar OAuth error: " . $token['error']);
            return false;
        }

        // Store tokens in database
        $query = "UPDATE users SET
                  google_calendar_access_token = ?,
                  google_calendar_refresh_token = ?,
                  google_calendar_expires_at = ?
                  WHERE user_id = ?";

        $expiresAt = date('Y-m-d H:i:s', time() + $token['expires_in']);

        fn_core_edit_row_no_redirect($query, [
            $token['access_token'],
            $token['refresh_token'] ?? null,
            $expiresAt,
            $userId
        ]);

        return true;

    } catch (Exception $e) {
        error_log("Google Calendar callback error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get Google Calendar client with fresh token
 *
 * @param int $userId User ID
 * @return Google_Client|false Client or false
 */
function fn_calendar_google_get_client($userId) {
    global $config;

    if (!isset($config['google_calendar'])) {
        return false;
    }

    // Get user's tokens
    $query = "SELECT google_calendar_access_token, google_calendar_refresh_token, google_calendar_expires_at
              FROM users WHERE user_id = ?";
    $user = fn_core_database_row($query, [$userId]);

    if (!$user || !$user['google_calendar_access_token']) {
        return false;
    }

    try {
        $client = new Google_Client();
        $client->setClientId($config['google_calendar']['client_id']);
        $client->setClientSecret($config['google_calendar']['client_secret']);
        $client->setAccessType('offline');

        // Set tokens
        $token = [
            'access_token' => $user['google_calendar_access_token'],
            'refresh_token' => $user['google_calendar_refresh_token'],
            'expires_in' => strtotime($user['google_calendar_expires_at']) - time()
        ];

        $client->setAccessToken($token);

        // Refresh if expired
        if ($client->isAccessTokenExpired()) {
            if ($user['google_calendar_refresh_token']) {
                $newToken = $client->fetchAccessTokenWithRefreshToken($user['google_calendar_refresh_token']);

                if (!isset($newToken['error'])) {
                    // Update database with new token
                    $query = "UPDATE users SET
                              google_calendar_access_token = ?,
                              google_calendar_expires_at = ?
                              WHERE user_id = ?";

                    $expiresAt = date('Y-m-d H:i:s', time() + $newToken['expires_in']);

                    fn_core_edit_row_no_redirect($query, [
                        $newToken['access_token'],
                        $expiresAt,
                        $userId
                    ]);
                } else {
                    error_log("Google Calendar token refresh error: " . $newToken['error']);
                    return false;
                }
            } else {
                return false;
            }
        }

        return $client;

    } catch (Exception $e) {
        error_log("Google Calendar client error: " . $e->getMessage());
        return false;
    }
}

/**
 * Sync appointment to Google Calendar (create or update)
 *
 * @param int $appointmentId Appointment ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_calendar_google_sync_appointment($appointmentId, $companyId) {
    $appointment = fn_calendar_get_appointment($appointmentId, $companyId);

    if (!$appointment || !$appointment['assigned_to']) {
        return false;
    }

    $client = fn_calendar_google_get_client($appointment['assigned_to']);

    if (!$client) {
        return false;
    }

    try {
        $service = new Google_Service_Calendar($client);

        // Prepare event data
        $event = new Google_Service_Calendar_Event([
            'summary' => fn_calendar_get_event_title($appointment),
            'description' => fn_calendar_get_event_description($appointment),
            'location' => $appointment['location'] ?? '',
            'start' => [
                'dateTime' => $appointment['start_datetime'],
                'timeZone' => 'Europe/Dublin',
            ],
            'end' => [
                'dateTime' => $appointment['end_datetime'],
                'timeZone' => 'Europe/Dublin',
            ],
            'reminders' => [
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'email', 'minutes' => 24 * 60],
                    ['method' => 'popup', 'minutes' => 30],
                ],
            ],
        ]);

        // Add attendees if customer email exists
        if (!empty($appointment['customer_email'])) {
            $event->setAttendees([
                ['email' => $appointment['customer_email']]
            ]);
        }

        if ($appointment['google_calendar_event_id']) {
            // Update existing event
            $updatedEvent = $service->events->update('primary', $appointment['google_calendar_event_id'], $event);
        } else {
            // Create new event
            $createdEvent = $service->events->insert('primary', $event);

            // Save Google Calendar event ID
            $query = "UPDATE calendar_appointments SET
                      google_calendar_event_id = ?,
                      google_calendar_synced = 1,
                      last_synced = NOW()
                      WHERE appointment_id = ? AND company_id = ?";

            fn_core_edit_row_no_redirect($query, [
                $createdEvent->getId(),
                $appointmentId,
                $companyId
            ]);
        }

        // Mark as synced
        $query = "UPDATE calendar_appointments SET google_calendar_synced = 1, last_synced = NOW()
                  WHERE appointment_id = ? AND company_id = ?";
        fn_core_edit_row_no_redirect($query, [$appointmentId, $companyId]);

        return true;

    } catch (Exception $e) {
        error_log("Google Calendar sync error: " . $e->getMessage());
        return false;
    }
}

/**
 * Delete event from Google Calendar
 *
 * @param string $eventId Google Calendar event ID
 * @param int $userId User ID
 * @return bool Success
 */
function fn_calendar_google_delete_event($eventId, $userId) {
    $client = fn_calendar_google_get_client($userId);

    if (!$client) {
        return false;
    }

    try {
        $service = new Google_Service_Calendar($client);
        $service->events->delete('primary', $eventId);
        return true;

    } catch (Exception $e) {
        error_log("Google Calendar delete error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get event title for Google Calendar
 *
 * @param array $appointment Appointment data
 * @return string Title
 */
function fn_calendar_get_event_title($appointment) {
    $typeLabels = [
        'test-drive' => 'Test Drive',
        'service' => 'Service Appointment',
        'consultation' => 'Sales Consultation',
        'vehicle-viewing' => 'Vehicle Viewing',
        'other' => 'Appointment'
    ];

    $type = $typeLabels[$appointment['appointment_type']] ?? 'Appointment';

    if ($appointment['customer_first_name']) {
        $title = $type . ' - ' . $appointment['customer_first_name'] . ' ' . $appointment['customer_last_name'];
    } else {
        $title = $type;
    }

    if ($appointment['make'] && $appointment['model']) {
        $title .= ' (' . $appointment['year'] . ' ' . $appointment['make'] . ' ' . $appointment['model'] . ')';
    }

    return $title;
}

/**
 * Get event description for Google Calendar
 *
 * @param array $appointment Appointment data
 * @return string Description
 */
function fn_calendar_get_event_description($appointment) {
    $description = [];

    if ($appointment['customer_email']) {
        $description[] = 'Email: ' . $appointment['customer_email'];
    }

    if ($appointment['customer_phone']) {
        $description[] = 'Phone: ' . $appointment['customer_phone'];
    }

    if ($appointment['make'] && $appointment['model']) {
        $description[] = 'Vehicle: ' . $appointment['year'] . ' ' . $appointment['make'] . ' ' . $appointment['model'];
    }

    if ($appointment['notes']) {
        $description[] = "\nNotes: " . $appointment['notes'];
    }

    return implode("\n", $description);
}

/**
 * Check if user has Google Calendar connected
 *
 * @param int $userId User ID
 * @return bool Connected
 */
function fn_calendar_google_is_connected($userId) {
    $query = "SELECT google_calendar_access_token FROM users WHERE user_id = ?";
    $user = fn_core_database_row($query, [$userId]);

    return !empty($user['google_calendar_access_token']);
}

/**
 * Disconnect Google Calendar
 *
 * @param int $userId User ID
 * @return bool Success
 */
function fn_calendar_google_disconnect($userId) {
    $query = "UPDATE users SET
              google_calendar_access_token = NULL,
              google_calendar_refresh_token = NULL,
              google_calendar_expires_at = NULL
              WHERE user_id = ?";

    return fn_core_edit_row_no_redirect($query, [$userId]);
}
