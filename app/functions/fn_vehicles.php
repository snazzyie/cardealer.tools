<?php
/**
 * Vehicle Management Functions
 *
 * Handle vehicle inventory operations
 */

/**
 * Get all vehicles for a company
 *
 * @param int $companyId Company ID
 * @param array $filters Optional filters (status, make, model, etc.)
 * @param int $limit Limit
 * @param int $offset Offset
 * @return array Vehicles
 */
function fn_vehicles_get_all($companyId, $filters = [], $limit = 50, $offset = 0) {
    $query = "SELECT v.*,
              (SELECT image_url FROM vehicle_images WHERE vehicle_id = v.vehicle_id AND is_primary = 1 LIMIT 1) as primary_image
              FROM vehicles v
              WHERE v.company_id = ?";

    $params = [$companyId];

    // Apply filters
    if (!empty($filters['status'])) {
        $query .= " AND v.status = ?";
        $params[] = $filters['status'];
    }

    if (!empty($filters['make'])) {
        $query .= " AND v.make = ?";
        $params[] = $filters['make'];
    }

    if (!empty($filters['search'])) {
        $query .= " AND (v.make LIKE ? OR v.model LIKE ? OR v.registration LIKE ? OR v.vin LIKE ?)";
        $searchTerm = '%' . $filters['search'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    $query .= " ORDER BY v.date_added DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;

    return fn_core_database_rows($query, $params);
}

/**
 * Get vehicle by ID
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID (for security)
 * @return array|null Vehicle data
 */
function fn_vehicles_get($vehicleId, $companyId) {
    $query = "SELECT * FROM vehicles WHERE vehicle_id = ? AND company_id = ?";
    return fn_core_database_row($query, [$vehicleId, $companyId]);
}

/**
 * Get vehicle images
 *
 * @param int $vehicleId Vehicle ID
 * @return array Images
 */
function fn_vehicles_get_images($vehicleId) {
    $query = "SELECT * FROM vehicle_images WHERE vehicle_id = ? ORDER BY image_order ASC, is_primary DESC";
    return fn_core_database_rows($query, [$vehicleId]);
}

/**
 * Create vehicle
 *
 * @param int $companyId Company ID
 * @param array $data Vehicle data
 * @return int|false Vehicle ID or false
 */
function fn_vehicles_create($companyId, $data) {
    $query = "INSERT INTO vehicles (
        company_id, make, model, year, registration, vin,
        price, was_price, trade_in_value, vat_status, vat_amount,
        mileage, mileage_unit, fuel_type, transmission, body_type,
        doors, seats, exterior_color, interior_color,
        engine_size, engine_size_unit, power_hp, power_kw, co2_emissions,
        engine_code, drivetrain, previous_owners, service_history,
        nct_expiry, mot_expiry, description, features, status,
        is_featured, is_premium, slug
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Generate slug
    $slug = fn_vehicles_generate_slug($data['make'], $data['model'], $data['year'], $data['registration']);

    $params = [
        $companyId,
        $data['make'],
        $data['model'],
        $data['year'],
        $data['registration'] ?? null,
        $data['vin'] ?? null,
        $data['price'],
        $data['was_price'] ?? null,
        $data['trade_in_value'] ?? null,
        $data['vat_status'] ?? 'vat_inclusive',
        $data['vat_amount'] ?? null,
        $data['mileage'] ?? null,
        $data['mileage_unit'] ?? 'km',
        $data['fuel_type'],
        $data['transmission'],
        $data['body_type'],
        $data['doors'] ?? null,
        $data['seats'] ?? null,
        $data['exterior_color'] ?? null,
        $data['interior_color'] ?? null,
        $data['engine_size'] ?? null,
        $data['engine_size_unit'] ?? 'cc',
        $data['power_hp'] ?? null,
        $data['power_kw'] ?? null,
        $data['co2_emissions'] ?? null,
        $data['engine_code'] ?? null,
        $data['drivetrain'] ?? null,
        $data['previous_owners'] ?? null,
        $data['service_history'] ?? 'unknown',
        $data['nct_expiry'] ?? null,
        $data['mot_expiry'] ?? null,
        $data['description'] ?? null,
        !empty($data['features']) ? json_encode($data['features']) : null,
        $data['status'] ?? 'available',
        $data['is_featured'] ?? 0,
        $data['is_premium'] ?? 0,
        $slug
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update vehicle
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID (for security)
 * @param array $data Vehicle data
 * @return bool Success
 */
function fn_vehicles_update($vehicleId, $companyId, $data) {
    $query = "UPDATE vehicles SET
        make = ?, model = ?, year = ?, registration = ?, vin = ?,
        price = ?, was_price = ?, trade_in_value = ?, vat_status = ?, vat_amount = ?,
        mileage = ?, mileage_unit = ?, fuel_type = ?, transmission = ?, body_type = ?,
        doors = ?, seats = ?, exterior_color = ?, interior_color = ?,
        engine_size = ?, engine_size_unit = ?, power_hp = ?, power_kw = ?, co2_emissions = ?,
        engine_code = ?, drivetrain = ?, previous_owners = ?, service_history = ?,
        nct_expiry = ?, mot_expiry = ?, description = ?, features = ?, status = ?,
        is_featured = ?, is_premium = ?
    WHERE vehicle_id = ? AND company_id = ?";

    $params = [
        $data['make'],
        $data['model'],
        $data['year'],
        $data['registration'] ?? null,
        $data['vin'] ?? null,
        $data['price'],
        $data['was_price'] ?? null,
        $data['trade_in_value'] ?? null,
        $data['vat_status'] ?? 'vat_inclusive',
        $data['vat_amount'] ?? null,
        $data['mileage'] ?? null,
        $data['mileage_unit'] ?? 'km',
        $data['fuel_type'],
        $data['transmission'],
        $data['body_type'],
        $data['doors'] ?? null,
        $data['seats'] ?? null,
        $data['exterior_color'] ?? null,
        $data['interior_color'] ?? null,
        $data['engine_size'] ?? null,
        $data['engine_size_unit'] ?? 'cc',
        $data['power_hp'] ?? null,
        $data['power_kw'] ?? null,
        $data['co2_emissions'] ?? null,
        $data['engine_code'] ?? null,
        $data['drivetrain'] ?? null,
        $data['previous_owners'] ?? null,
        $data['service_history'] ?? 'unknown',
        $data['nct_expiry'] ?? null,
        $data['mot_expiry'] ?? null,
        $data['description'] ?? null,
        !empty($data['features']) ? json_encode($data['features']) : null,
        $data['status'] ?? 'available',
        $data['is_featured'] ?? 0,
        $data['is_premium'] ?? 0,
        $vehicleId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Delete vehicle
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID (for security)
 * @return bool Success
 */
function fn_vehicles_delete($vehicleId, $companyId) {
    $query = "DELETE FROM vehicles WHERE vehicle_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$vehicleId, $companyId]);
}

/**
 * Add vehicle image
 *
 * @param int $vehicleId Vehicle ID
 * @param string $imageUrl Image URL
 * @param int $imageOrder Order
 * @param bool $isPrimary Is primary image
 * @return int|false Image ID or false
 */
function fn_vehicles_add_image($vehicleId, $imageUrl, $imageOrder = 0, $isPrimary = false) {
    // If setting as primary, unset other primary images
    if ($isPrimary) {
        $updateQuery = "UPDATE vehicle_images SET is_primary = 0 WHERE vehicle_id = ?";
        fn_core_edit_row_no_redirect($updateQuery, [$vehicleId]);
    }

    $query = "INSERT INTO vehicle_images (vehicle_id, image_url, image_order, is_primary) VALUES (?, ?, ?, ?)";
    return fn_core_insert_row_no_redirect($query, [$vehicleId, $imageUrl, $imageOrder, $isPrimary ? 1 : 0]);
}

/**
 * Delete vehicle image
 *
 * @param int $imageId Image ID
 * @return bool Success
 */
function fn_vehicles_delete_image($imageId) {
    $query = "DELETE FROM vehicle_images WHERE image_id = ?";
    return fn_core_delete_row_no_redirect($query, [$imageId]);
}

/**
 * Set primary image
 *
 * @param int $vehicleId Vehicle ID
 * @param int $imageId Image ID
 * @return bool Success
 */
function fn_vehicles_set_primary_image($vehicleId, $imageId) {
    // Unset all primary images for this vehicle
    $updateQuery = "UPDATE vehicle_images SET is_primary = 0 WHERE vehicle_id = ?";
    fn_core_edit_row_no_redirect($updateQuery, [$vehicleId]);

    // Set new primary
    $query = "UPDATE vehicle_images SET is_primary = 1 WHERE image_id = ? AND vehicle_id = ?";
    return fn_core_edit_row_no_redirect($query, [$imageId, $vehicleId]);
}

/**
 * Generate unique slug
 *
 * @param string $make Make
 * @param string $model Model
 * @param int $year Year
 * @param string $registration Registration
 * @return string Slug
 */
function fn_vehicles_generate_slug($make, $model, $year, $registration) {
    $slug = strtolower($make . '-' . $model . '-' . $year);
    if ($registration) {
        $slug .= '-' . strtolower(str_replace(' ', '', $registration));
    }
    $slug = preg_replace('/[^a-z0-9-]/', '', $slug);
    $slug = preg_replace('/-+/', '-', $slug);

    // Ensure uniqueness
    $originalSlug = $slug;
    $counter = 1;
    while (!fn_vehicles_slug_available($slug)) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

/**
 * Check if slug is available
 *
 * @param string $slug Slug
 * @return bool True if available
 */
function fn_vehicles_slug_available($slug) {
    $query = "SELECT COUNT(*) as count FROM vehicles WHERE slug = ?";
    $result = fn_core_database_row($query, [$slug]);
    return $result['count'] == 0;
}

/**
 * Get unique makes for a company
 *
 * @param int $companyId Company ID
 * @return array Makes
 */
function fn_vehicles_get_makes($companyId) {
    $query = "SELECT DISTINCT make FROM vehicles WHERE company_id = ? ORDER BY make ASC";
    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Update vehicle status
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID
 * @param string $status Status
 * @return bool Success
 */
function fn_vehicles_update_status($vehicleId, $companyId, $status) {
    $query = "UPDATE vehicles SET status = ? WHERE vehicle_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$status, $vehicleId, $companyId]);
}

/**
 * Mark vehicle as sold
 *
 * @param int $vehicleId Vehicle ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_vehicles_mark_sold($vehicleId, $companyId) {
    $query = "UPDATE vehicles SET status = 'sold', date_sold = NOW() WHERE vehicle_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$vehicleId, $companyId]);
}

/**
 * Helper function for delete_row without redirect
 */
function fn_core_delete_row_no_redirect($query, $params) {
    try {
        $db = fn_core_database_connection();
        $stmt = $db->prepare($query);
        return $stmt->execute($params);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}
