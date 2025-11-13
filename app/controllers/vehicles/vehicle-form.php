<?php
/**
 * Vehicle Add/Edit Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Determine if we're editing or creating
$vehicle_id = null;
$vehicle_data = null;
$is_edit = false;

if (isset($_GET['id'])) {
    $vehicle_id = intval($_GET['id']);
    $vehicle_data = fn_vehicles_get($vehicle_id, $company_id);

    if (!$vehicle_data) {
        header("Location: /vehicles");
        exit;
    }

    $is_edit = true;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $required_fields = ['make', 'model', 'year', 'price', 'fuel_type', 'transmission', 'body_type'];
    $missing_fields = [];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }

    if (!empty($missing_fields)) {
        $error = 'Please fill in all required fields: ' . implode(', ', $missing_fields);
    } else {
        $data = [
            'make' => trim($_POST['make']),
            'model' => trim($_POST['model']),
            'year' => intval($_POST['year']),
            'registration' => trim($_POST['registration'] ?? ''),
            'vin' => trim($_POST['vin'] ?? ''),
            'price' => floatval($_POST['price']),
            'was_price' => !empty($_POST['was_price']) ? floatval($_POST['was_price']) : null,
            'trade_in_value' => !empty($_POST['trade_in_value']) ? floatval($_POST['trade_in_value']) : null,
            'vat_status' => $_POST['vat_status'] ?? 'vat_inclusive',
            'vat_amount' => !empty($_POST['vat_amount']) ? floatval($_POST['vat_amount']) : null,
            'mileage' => !empty($_POST['mileage']) ? intval($_POST['mileage']) : null,
            'mileage_unit' => $_POST['mileage_unit'] ?? 'km',
            'fuel_type' => $_POST['fuel_type'],
            'transmission' => $_POST['transmission'],
            'body_type' => $_POST['body_type'],
            'doors' => !empty($_POST['doors']) ? intval($_POST['doors']) : null,
            'seats' => !empty($_POST['seats']) ? intval($_POST['seats']) : null,
            'exterior_color' => trim($_POST['exterior_color'] ?? ''),
            'interior_color' => trim($_POST['interior_color'] ?? ''),
            'engine_size' => !empty($_POST['engine_size']) ? intval($_POST['engine_size']) : null,
            'engine_size_unit' => $_POST['engine_size_unit'] ?? 'cc',
            'power_hp' => !empty($_POST['power_hp']) ? intval($_POST['power_hp']) : null,
            'power_kw' => !empty($_POST['power_kw']) ? intval($_POST['power_kw']) : null,
            'co2_emissions' => !empty($_POST['co2_emissions']) ? intval($_POST['co2_emissions']) : null,
            'engine_code' => trim($_POST['engine_code'] ?? ''),
            'drivetrain' => !empty($_POST['drivetrain']) ? $_POST['drivetrain'] : null,
            'previous_owners' => !empty($_POST['previous_owners']) ? intval($_POST['previous_owners']) : null,
            'service_history' => $_POST['service_history'] ?? 'unknown',
            'nct_expiry' => !empty($_POST['nct_expiry']) ? $_POST['nct_expiry'] : null,
            'mot_expiry' => !empty($_POST['mot_expiry']) ? $_POST['mot_expiry'] : null,
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'available',
            'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            'is_premium' => isset($_POST['is_premium']) ? 1 : 0
        ];

        if ($is_edit) {
            // Update existing vehicle
            $result = fn_vehicles_update($vehicle_id, $company_id, $data);

            if ($result) {
                $success = 'Vehicle updated successfully.';
                // Refresh vehicle data
                $vehicle_data = fn_vehicles_get($vehicle_id, $company_id);
            } else {
                $error = 'Failed to update vehicle.';
            }
        } else {
            // Create new vehicle
            $new_vehicle_id = fn_vehicles_create($company_id, $data);

            if ($new_vehicle_id) {
                header("Location: /vehicles/edit/{$new_vehicle_id}?success=1");
                exit;
            } else {
                $error = 'Failed to create vehicle.';
            }
        }
    }
}

// Show success message if redirected from create
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = 'Vehicle created successfully. You can now add images.';
}

$page_header = [
    'title' => $is_edit ? 'Edit Vehicle' : 'Add New Vehicle',
    'subtitle' => $is_edit ? 'Update vehicle information' : 'Add a new vehicle to your inventory'
];

require BASE_PATH . 'views/vehicles/vehicle-form.php';
