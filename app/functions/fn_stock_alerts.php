<?php
/**
 * Stock Alerts Functions
 * Allow customers to subscribe to alerts for vehicles matching their criteria
 */

/**
 * Create stock alert subscription
 *
 * @param int $companyId Company ID
 * @param array $data Alert data
 * @return int|false Alert ID or false
 */
function fn_stock_alert_create($companyId, $data) {
    // Validate email
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    // Generate unsubscribe token
    $unsubscribeToken = bin2hex(random_bytes(32));

    $query = "INSERT INTO stock_alerts (
        company_id, email, make, model, min_price, max_price,
        min_year, max_year, fuel_type, body_type,
        is_active, unsubscribe_token
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?)";

    $params = [
        $companyId,
        strtolower(trim($data['email'])),
        $data['make'] ?? null,
        $data['model'] ?? null,
        !empty($data['min_price']) ? floatval($data['min_price']) : null,
        !empty($data['max_price']) ? floatval($data['max_price']) : null,
        !empty($data['min_year']) ? intval($data['min_year']) : null,
        !empty($data['max_year']) ? intval($data['max_year']) : null,
        $data['fuel_type'] ?? null,
        $data['body_type'] ?? null,
        $unsubscribeToken
    ];

    $alertId = fn_core_insert_row_no_redirect($query, $params);

    // Send confirmation email
    if ($alertId) {
        fn_stock_alert_send_confirmation($alertId, $companyId);
    }

    return $alertId;
}

/**
 * Get all stock alerts for a company
 *
 * @param int $companyId Company ID
 * @param bool $activeOnly Only active alerts
 * @return array Stock alerts
 */
function fn_stock_alerts_get_all($companyId, $activeOnly = false) {
    $query = "SELECT * FROM stock_alerts WHERE company_id = ?";
    $params = [$companyId];

    if ($activeOnly) {
        $query .= " AND is_active = 1";
    }

    $query .= " ORDER BY created_date DESC";

    return fn_core_database_rows($query, $params);
}

/**
 * Get stock alert by ID
 *
 * @param int $alertId Alert ID
 * @param int $companyId Company ID
 * @return array|false Alert data or false
 */
function fn_stock_alert_get($alertId, $companyId) {
    $query = "SELECT * FROM stock_alerts WHERE alert_id = ? AND company_id = ?";
    return fn_core_database_row($query, [$alertId, $companyId]);
}

/**
 * Get stock alert by unsubscribe token
 *
 * @param string $token Unsubscribe token
 * @return array|false Alert data or false
 */
function fn_stock_alert_get_by_token($token) {
    $query = "SELECT * FROM stock_alerts WHERE unsubscribe_token = ?";
    return fn_core_database_row($query, [$token]);
}

/**
 * Delete stock alert
 *
 * @param int $alertId Alert ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_stock_alert_delete($alertId, $companyId) {
    $query = "DELETE FROM stock_alerts WHERE alert_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$alertId, $companyId]);
}

/**
 * Unsubscribe from stock alert
 *
 * @param string $token Unsubscribe token
 * @return bool Success
 */
function fn_stock_alert_unsubscribe($token) {
    $query = "UPDATE stock_alerts SET is_active = 0 WHERE unsubscribe_token = ?";
    return fn_core_edit_row_no_redirect($query, [$token]);
}

/**
 * Check if vehicle matches any stock alerts and send notifications
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID
 * @return int Number of alerts notified
 */
function fn_stock_alert_check_vehicle($vehicleId, $companyId) {
    // Get vehicle details
    $vehicle = fn_vehicles_get_single($vehicleId, $companyId);

    if (!$vehicle) {
        return 0;
    }

    // Get all active alerts for this company
    $alerts = fn_stock_alerts_get_all($companyId, true);

    $notifiedCount = 0;

    foreach ($alerts as $alert) {
        // Check if vehicle matches alert criteria
        if (fn_stock_alert_matches_vehicle($alert, $vehicle)) {
            // Send notification email
            $success = fn_stock_alert_send_notification($alert, $vehicle, $companyId);

            if ($success) {
                // Update last_sent timestamp
                $query = "UPDATE stock_alerts SET last_sent = NOW() WHERE alert_id = ?";
                fn_core_edit_row_no_redirect($query, [$alert['alert_id']]);

                $notifiedCount++;
            }
        }
    }

    return $notifiedCount;
}

/**
 * Check if vehicle matches alert criteria
 *
 * @param array $alert Alert criteria
 * @param array $vehicle Vehicle data
 * @return bool Matches
 */
function fn_stock_alert_matches_vehicle($alert, $vehicle) {
    // Check make
    if (!empty($alert['make']) && strcasecmp($alert['make'], $vehicle['make']) !== 0) {
        return false;
    }

    // Check model
    if (!empty($alert['model']) && strcasecmp($alert['model'], $vehicle['model']) !== 0) {
        return false;
    }

    // Check min price
    if (!empty($alert['min_price']) && $vehicle['price'] < $alert['min_price']) {
        return false;
    }

    // Check max price
    if (!empty($alert['max_price']) && $vehicle['price'] > $alert['max_price']) {
        return false;
    }

    // Check min year
    if (!empty($alert['min_year']) && $vehicle['year'] < $alert['min_year']) {
        return false;
    }

    // Check max year
    if (!empty($alert['max_year']) && $vehicle['year'] > $alert['max_year']) {
        return false;
    }

    // Check fuel type
    if (!empty($alert['fuel_type']) && strcasecmp($alert['fuel_type'], $vehicle['fuel_type']) !== 0) {
        return false;
    }

    // Check body type
    if (!empty($alert['body_type']) && strcasecmp($alert['body_type'], $vehicle['body_type']) !== 0) {
        return false;
    }

    return true;
}

/**
 * Send confirmation email for new alert subscription
 *
 * @param int $alertId Alert ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_stock_alert_send_confirmation($alertId, $companyId) {
    $alert = fn_stock_alert_get($alertId, $companyId);
    $company = fn_company_get_by_id($companyId);

    if (!$alert || !$company) {
        return false;
    }

    $config = require BASE_PATH . 'config.php';
    $unsubscribeUrl = $config['app_url'] . '/stock-alert/unsubscribe?token=' . $alert['unsubscribe_token'];

    $subject = "Stock Alert Subscription Confirmed - " . $company['company_name'];

    $criteria = [];
    if ($alert['make']) $criteria[] = "Make: " . $alert['make'];
    if ($alert['model']) $criteria[] = "Model: " . $alert['model'];
    if ($alert['min_year']) $criteria[] = "Year from: " . $alert['min_year'];
    if ($alert['max_year']) $criteria[] = "Year to: " . $alert['max_year'];
    if ($alert['min_price']) $criteria[] = "Price from: €" . number_format($alert['min_price'], 0);
    if ($alert['max_price']) $criteria[] = "Price to: €" . number_format($alert['max_price'], 0);
    if ($alert['fuel_type']) $criteria[] = "Fuel: " . ucfirst($alert['fuel_type']);
    if ($alert['body_type']) $criteria[] = "Body: " . ucfirst($alert['body_type']);

    $criteriaText = !empty($criteria) ? implode(", ", $criteria) : "Any vehicle";

    $htmlBody = "
        <h2>Stock Alert Subscription Confirmed</h2>
        <p>Thank you for subscribing to stock alerts from {$company['company_name']}!</p>
        <p><strong>Your Alert Criteria:</strong><br>{$criteriaText}</p>
        <p>You will receive an email notification whenever a vehicle matching your criteria becomes available.</p>
        <p><a href='{$unsubscribeUrl}'>Unsubscribe from this alert</a></p>
    ";

    $textBody = "Stock Alert Subscription Confirmed\n\n"
        . "Thank you for subscribing to stock alerts from {$company['company_name']}!\n\n"
        . "Your Alert Criteria: {$criteriaText}\n\n"
        . "You will receive an email notification whenever a vehicle matching your criteria becomes available.\n\n"
        . "Unsubscribe: {$unsubscribeUrl}";

    return fn_email_send_postmark($alert['email'], $subject, $htmlBody, $textBody, $companyId);
}

/**
 * Send notification email for matching vehicle
 *
 * @param array $alert Alert data
 * @param array $vehicle Vehicle data
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_stock_alert_send_notification($alert, $vehicle, $companyId) {
    $company = fn_company_get_by_id($companyId);

    if (!$company) {
        return false;
    }

    $config = require BASE_PATH . 'config.php';
    $vehicleUrl = $config['app_url'] . '/cars/view?id=' . $vehicle['vehicle_id'];
    $unsubscribeUrl = $config['app_url'] . '/stock-alert/unsubscribe?token=' . $alert['unsubscribe_token'];

    $subject = "New Vehicle Alert: {$vehicle['year']} {$vehicle['make']} {$vehicle['model']} - {$company['company_name']}";

    $htmlBody = "
        <h2>New Vehicle Matches Your Alert!</h2>
        <p>Great news! A vehicle matching your criteria is now available at {$company['company_name']}.</p>
        <hr>
        <h3>{$vehicle['year']} {$vehicle['make']} {$vehicle['model']}</h3>
        <p>
            <strong>Price:</strong> €" . number_format($vehicle['price'], 2) . "<br>
            <strong>Mileage:</strong> " . number_format($vehicle['mileage']) . " km<br>
            <strong>Fuel Type:</strong> " . ucfirst($vehicle['fuel_type']) . "<br>
            <strong>Transmission:</strong> " . ucfirst($vehicle['transmission']) . "
        </p>
        <p><a href='{$vehicleUrl}' style='background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; display: inline-block;'>View Vehicle</a></p>
        <hr>
        <p style='font-size: 12px; color: #666;'>
            Contact us: {$company['company_phone']} | {$company['company_email']}<br>
            <a href='{$unsubscribeUrl}'>Unsubscribe from this alert</a>
        </p>
    ";

    $textBody = "New Vehicle Matches Your Alert!\n\n"
        . "Great news! A vehicle matching your criteria is now available at {$company['company_name']}.\n\n"
        . "{$vehicle['year']} {$vehicle['make']} {$vehicle['model']}\n"
        . "Price: €" . number_format($vehicle['price'], 2) . "\n"
        . "Mileage: " . number_format($vehicle['mileage']) . " km\n"
        . "Fuel Type: " . ucfirst($vehicle['fuel_type']) . "\n"
        . "Transmission: " . ucfirst($vehicle['transmission']) . "\n\n"
        . "View Vehicle: {$vehicleUrl}\n\n"
        . "Contact us: {$company['company_phone']} | {$company['company_email']}\n"
        . "Unsubscribe: {$unsubscribeUrl}";

    return fn_email_send_postmark($alert['email'], $subject, $htmlBody, $textBody, $companyId);
}

/**
 * Get stock alert statistics
 *
 * @param int $companyId Company ID
 * @return array Stats
 */
function fn_stock_alert_stats($companyId) {
    $query = "SELECT
        COUNT(*) as total_alerts,
        COUNT(CASE WHEN is_active = 1 THEN 1 END) as active_alerts,
        COUNT(CASE WHEN is_active = 0 THEN 1 END) as inactive_alerts,
        COUNT(CASE WHEN last_sent IS NOT NULL THEN 1 END) as notified_alerts
        FROM stock_alerts
        WHERE company_id = ?";

    return fn_core_database_row($query, [$companyId]);
}
