<?php
/**
 * View Vehicle Details Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$user_id = $user_data['user_id'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get vehicle ID from URL
$vehicle_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$vehicle_id) {
    header("Location: /vehicles");
    exit;
}

// Get vehicle details
$vehicle = fn_vehicles_get($vehicle_id, $company_id);

if (!$vehicle) {
    header("Location: /vehicles?error=not_found");
    exit;
}

// Get vehicle images
$images = fn_vehicles_get_images($vehicle_id);

// Get vehicle features
$features = fn_vehicles_get_features($vehicle_id);

// Page header data
$page_header = [
    'title' => $vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model'],
    'subtitle' => 'Vehicle Details',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Vehicles' => '/vehicles',
        'View Vehicle' => ''
    ]
];

// Load view
require BASE_PATH . 'views/vehicles/vehicles-view.php';
