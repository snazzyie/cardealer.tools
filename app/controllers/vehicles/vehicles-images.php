<?php
/**
 * Vehicle Images Management
 * Upload, reorder, and manage multiple vehicle images
 */

// Security check
fn_check_security($user_id, $company_id);

// Get vehicle ID
$vehicleId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if (empty($vehicleId)) {
    header("Location: /vehicles?error=no_id");
    exit;
}

// Get vehicle
$vehicle = fn_vehicles_get_single($vehicleId, $company_id);

if (!$vehicle) {
    header("Location: /vehicles?error=not_found");
    exit;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $isPrimary = isset($_POST['is_primary']) && $_POST['is_primary'] == '1';
    $result = fn_vehicle_image_upload($vehicleId, $_FILES['image'], $isPrimary);

    if ($result['success']) {
        header("Location: /vehicles/images?id={$vehicleId}&uploaded=1");
        exit;
    } else {
        $error = $result['error'] ?? 'Upload failed';
    }
}

// Handle delete
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $imageId = intval($_GET['delete']);
    $success = fn_vehicle_image_delete($imageId, $vehicleId);

    if ($success) {
        header("Location: /vehicles/images?id={$vehicleId}&deleted=1");
        exit;
    }
}

// Handle set primary
if (isset($_GET['set_primary']) && !empty($_GET['set_primary'])) {
    $imageId = intval($_GET['set_primary']);
    $success = fn_vehicle_image_set_primary($imageId, $vehicleId);

    if ($success) {
        header("Location: /vehicles/images?id={$vehicleId}&primary_set=1");
        exit;
    }
}

// Get all images
$images = fn_vehicle_images_get_all($vehicleId);

// Page header
$page_header = [
    'title' => 'Manage Images - ' . $vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model'],
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Vehicles' => '/vehicles',
        'Manage Images' => ''
    ]
];

// Load view
require BASE_PATH . 'views/vehicles/vehicles-images.php';
