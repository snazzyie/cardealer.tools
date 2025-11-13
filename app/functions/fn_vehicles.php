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
 * ====================
 * CSV IMPORT
 * ====================
 */

/**
 * Import vehicles from CSV file
 *
 * @param string $filePath Path to CSV file
 * @param int $companyId Company ID
 * @return array Import results
 */
function fn_vehicles_import_csv($filePath, $companyId) {
    $results = [
        'success' => false,
        'imported' => 0,
        'skipped' => 0,
        'errors' => [],
        'error' => ''
    ];

    if (!file_exists($filePath)) {
        $results['error'] = 'File not found';
        return $results;
    }

    try {
        $file = fopen($filePath, 'r');

        if (!$file) {
            $results['error'] = 'Could not open file';
            return $results;
        }

        // Read header row
        $header = fgetcsv($file);

        if (!$header) {
            $results['error'] = 'Invalid CSV file - no header row';
            fclose($file);
            return $results;
        }

        // Expected columns (flexible mapping)
        $columnMap = fn_vehicles_get_csv_column_map($header);

        $lineNumber = 1;

        // Read data rows
        while (($row = fgetcsv($file)) !== false) {
            $lineNumber++;

            try {
                $vehicleData = fn_vehicles_map_csv_row($row, $columnMap);

                // Validate required fields
                if (empty($vehicleData['make']) || empty($vehicleData['model']) || empty($vehicleData['price'])) {
                    $results['skipped']++;
                    $results['errors'][] = "Line {$lineNumber}: Missing required fields (make, model, price)";
                    continue;
                }

                // Create vehicle
                $vehicleId = fn_vehicles_create($companyId, $vehicleData);

                if ($vehicleId) {
                    $results['imported']++;
                } else {
                    $results['skipped']++;
                    $results['errors'][] = "Line {$lineNumber}: Failed to create vehicle";
                }

            } catch (Exception $e) {
                $results['skipped']++;
                $results['errors'][] = "Line {$lineNumber}: " . $e->getMessage();
            }
        }

        fclose($file);

        $results['success'] = true;

        return $results;

    } catch (Exception $e) {
        $results['error'] = $e->getMessage();
        return $results;
    }
}

/**
 * Map CSV header to column indexes
 *
 * @param array $header Header row
 * @return array Column map
 */
function fn_vehicles_get_csv_column_map($header) {
    $map = [];

    // Normalize header values
    $header = array_map('strtolower', $header);
    $header = array_map('trim', $header);

    // Map common column names
    $mappings = [
        'make' => ['make', 'manufacturer', 'brand'],
        'model' => ['model'],
        'year' => ['year', 'reg_year', 'registration_year'],
        'registration' => ['registration', 'reg', 'reg_number', 'plate'],
        'price' => ['price', 'sale_price', 'retail_price'],
        'mileage' => ['mileage', 'odometer', 'miles', 'km'],
        'fuel_type' => ['fuel', 'fuel_type', 'fueltype'],
        'transmission' => ['transmission', 'gearbox'],
        'body_type' => ['body_type', 'bodytype', 'body', 'type'],
        'color' => ['color', 'colour', 'exterior_color'],
        'doors' => ['doors', 'door_count'],
        'seats' => ['seats', 'seat_count'],
        'engine_size' => ['engine_size', 'engine', 'cc'],
        'vin' => ['vin', 'chassis', 'chassis_number'],
        'description' => ['description', 'desc', 'details']
    ];

    foreach ($mappings as $field => $aliases) {
        foreach ($aliases as $alias) {
            $index = array_search($alias, $header);
            if ($index !== false) {
                $map[$field] = $index;
                break;
            }
        }
    }

    return $map;
}

/**
 * Map CSV row to vehicle data
 *
 * @param array $row CSV row
 * @param array $columnMap Column map
 * @return array Vehicle data
 */
function fn_vehicles_map_csv_row($row, $columnMap) {
    $data = [];

    if (isset($columnMap['make'])) $data['make'] = trim($row[$columnMap['make']]);
    if (isset($columnMap['model'])) $data['model'] = trim($row[$columnMap['model']]);
    if (isset($columnMap['year'])) $data['year'] = intval($row[$columnMap['year']]);
    if (isset($columnMap['registration'])) $data['registration'] = trim($row[$columnMap['registration']]);
    if (isset($columnMap['price'])) {
        $price = preg_replace('/[^0-9.]/', '', $row[$columnMap['price']]);
        $data['price'] = floatval($price);
    }
    if (isset($columnMap['mileage'])) {
        $mileage = preg_replace('/[^0-9]/', '', $row[$columnMap['mileage']]);
        $data['mileage'] = intval($mileage);
    }
    if (isset($columnMap['fuel_type'])) {
        $fuelType = strtolower(trim($row[$columnMap['fuel_type']]));
        $validFuels = ['petrol', 'diesel', 'electric', 'hybrid', 'plug-in-hybrid'];
        $data['fuel_type'] = in_array($fuelType, $validFuels) ? $fuelType : 'petrol';
    }
    if (isset($columnMap['transmission'])) {
        $transmission = strtolower(trim($row[$columnMap['transmission']]));
        $validTrans = ['manual', 'automatic', 'semi-automatic'];
        $data['transmission'] = in_array($transmission, $validTrans) ? $transmission : 'manual';
    }
    if (isset($columnMap['body_type'])) {
        $bodyType = strtolower(trim($row[$columnMap['body_type']]));
        $validBodies = ['saloon', 'suv', 'hatchback', 'coupe', 'estate', 'van', 'mpv', 'pickup'];
        $data['body_type'] = in_array($bodyType, $validBodies) ? $bodyType : 'saloon';
    }
    if (isset($columnMap['color'])) $data['exterior_color'] = trim($row[$columnMap['color']]);
    if (isset($columnMap['doors'])) $data['doors'] = intval($row[$columnMap['doors']]);
    if (isset($columnMap['seats'])) $data['seats'] = intval($row[$columnMap['seats']]);
    if (isset($columnMap['engine_size'])) $data['engine_size'] = intval($row[$columnMap['engine_size']]);
    if (isset($columnMap['vin'])) $data['vin'] = trim($row[$columnMap['vin']]);
    if (isset($columnMap['description'])) $data['description'] = trim($row[$columnMap['description']]);

    // Defaults for required fields if missing
    if (!isset($data['fuel_type'])) $data['fuel_type'] = 'petrol';
    if (!isset($data['transmission'])) $data['transmission'] = 'manual';
    if (!isset($data['body_type'])) $data['body_type'] = 'saloon';
    if (!isset($data['status'])) $data['status'] = 'available';

    return $data;
}

/**
 * Generate CSV template for download
 *
 * @return string CSV content
 */
function fn_vehicles_generate_csv_template() {
    $headers = [
        'Make', 'Model', 'Year', 'Registration', 'Price', 'Mileage',
        'Fuel_Type', 'Transmission', 'Body_Type', 'Color', 'Doors', 'Seats',
        'Engine_Size', 'VIN', 'Description'
    ];

    $sampleData = [
        'BMW', 'X5', '2020', '201-D-12345', '45000', '35000',
        'Diesel', 'Automatic', 'SUV', 'Black', '5', '7',
        '3000', 'WBA1234567890', 'Excellent condition, full service history'
    ];

    $output = implode(',', $headers) . "\n";
    $output .= implode(',', $sampleData) . "\n";

    return $output;
}

/**
 * ====================
 * VEHICLE IMAGES
 * ====================
 */

/**
 * Get all images for a vehicle
 *
 * @param int $vehicleId Vehicle ID
 * @return array Images
 */
function fn_vehicle_images_get_all($vehicleId) {
    $query = "SELECT * FROM vehicle_images WHERE vehicle_id = ? ORDER BY is_primary DESC, image_order ASC";
    return fn_core_database_rows($query, [$vehicleId]);
}

/**
 * Add image to vehicle
 *
 * @param int $vehicleId Vehicle ID
 * @param string $imageUrl Image URL
 * @param bool $isPrimary Is primary image
 * @param int $imageOrder Display order
 * @return int|false Image ID or false
 */
function fn_vehicle_image_add($vehicleId, $imageUrl, $isPrimary = false, $imageOrder = 0) {
    // If setting as primary, unset other primary images
    if ($isPrimary) {
        $query = "UPDATE vehicle_images SET is_primary = 0 WHERE vehicle_id = ?";
        fn_core_edit_row_no_redirect($query, [$vehicleId]);
    }

    $query = "INSERT INTO vehicle_images (vehicle_id, image_url, image_order, is_primary) VALUES (?, ?, ?, ?)";
    return fn_core_insert_row_no_redirect($query, [$vehicleId, $imageUrl, $imageOrder, $isPrimary ? 1 : 0]);
}

/**
 * Delete vehicle image
 *
 * @param int $imageId Image ID
 * @param int $vehicleId Vehicle ID (for security)
 * @return bool Success
 */
function fn_vehicle_image_delete($imageId, $vehicleId) {
    // Get image URL to delete file
    $image = fn_core_database_row("SELECT image_url FROM vehicle_images WHERE image_id = ? AND vehicle_id = ?", [$imageId, $vehicleId]);

    if ($image) {
        // Delete file if it exists
        $filePath = BASE_PATH . 'public' . $image['image_url'];
        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        // Delete from database
        $query = "DELETE FROM vehicle_images WHERE image_id = ? AND vehicle_id = ?";
        return fn_core_edit_row_no_redirect($query, [$imageId, $vehicleId]);
    }

    return false;
}

/**
 * Set image as primary
 *
 * @param int $imageId Image ID
 * @param int $vehicleId Vehicle ID
 * @return bool Success
 */
function fn_vehicle_image_set_primary($imageId, $vehicleId) {
    // Unset all primary images for this vehicle
    $query = "UPDATE vehicle_images SET is_primary = 0 WHERE vehicle_id = ?";
    fn_core_edit_row_no_redirect($query, [$vehicleId]);

    // Set new primary
    $query = "UPDATE vehicle_images SET is_primary = 1 WHERE image_id = ? AND vehicle_id = ?";
    return fn_core_edit_row_no_redirect($query, [$imageId, $vehicleId]);
}

/**
 * Reorder vehicle images
 *
 * @param int $vehicleId Vehicle ID
 * @param array $imageOrder Array of image IDs in desired order
 * @return bool Success
 */
function fn_vehicle_images_reorder($vehicleId, $imageOrder) {
    foreach ($imageOrder as $order => $imageId) {
        $query = "UPDATE vehicle_images SET image_order = ? WHERE image_id = ? AND vehicle_id = ?";
        fn_core_edit_row_no_redirect($query, [$order, $imageId, $vehicleId]);
    }
    return true;
}

/**
 * Upload and save vehicle image
 *
 * @param int $vehicleId Vehicle ID
 * @param array $file $_FILES array element
 * @param bool $isPrimary Set as primary image
 * @return array|false Array with success, image_id, image_url or false
 */
function fn_vehicle_image_upload($vehicleId, $file, $isPrimary = false) {
    // Validate file
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return ['success' => false, 'error' => 'No file uploaded'];
    }

    // Check file type
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
    $fileType = mime_content_type($file['tmp_name']);

    if (!in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, GIF, and WebP allowed.'];
    }

    // Check file size (max 10MB)
    if ($file['size'] > 10 * 1024 * 1024) {
        return ['success' => false, 'error' => 'File too large. Maximum 10MB allowed.'];
    }

    // Create upload directory if it doesn't exist
    $uploadDir = BASE_PATH . 'public/uploads/vehicles/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Generate unique filename
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'vehicle_' . $vehicleId . '_' . time() . '_' . uniqid() . '.' . $extension;
    $uploadPath = $uploadDir . $filename;
    $webPath = '/uploads/vehicles/' . $filename;

    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        // Get next order number
        $maxOrder = fn_core_database_row("SELECT MAX(image_order) as max_order FROM vehicle_images WHERE vehicle_id = ?", [$vehicleId]);
        $imageOrder = ($maxOrder && isset($maxOrder['max_order'])) ? $maxOrder['max_order'] + 1 : 0;

        // Save to database
        $imageId = fn_vehicle_image_add($vehicleId, $webPath, $isPrimary, $imageOrder);

        if ($imageId) {
            return [
                'success' => true,
                'image_id' => $imageId,
                'image_url' => $webPath,
                'filename' => $filename
            ];
        } else {
            // Delete uploaded file if database insert failed
            @unlink($uploadPath);
            return ['success' => false, 'error' => 'Failed to save image to database'];
        }
    } else {
        return ['success' => false, 'error' => 'Failed to move uploaded file'];
    }
}
