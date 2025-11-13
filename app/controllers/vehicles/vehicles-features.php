<?php
/**
 * Vehicle Features Management Controller
 * Manage features for a specific vehicle
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get vehicle ID
if (empty($_GET['id'])) {
    header("Location: /vehicles");
    exit;
}

$vehicle_id = intval($_GET['id']);
$vehicle_data = fn_vehicles_get($vehicle_id, $company_id);

if (!$vehicle_data) {
    header("Location: /vehicles");
    exit;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get selected features
    $selected_features = $_POST['features'] ?? [];

    // Update vehicle features
    $result = fn_vehicles_update_features($vehicle_id, $company_id, $selected_features);

    if ($result) {
        $success = 'Vehicle features updated successfully.';
    } else {
        $error = 'Failed to update vehicle features.';
    }
}

// Get all available features grouped by category
$feature_categories = [
    'Safety' => [
        'ABS',
        'Airbags',
        'Traction Control',
        'Stability Control',
        'Lane Departure Warning',
        'Blind Spot Monitoring',
        'Rear View Camera',
        'Parking Sensors',
        'Adaptive Cruise Control',
        'Emergency Brake Assist'
    ],
    'Comfort' => [
        'Air Conditioning',
        'Climate Control',
        'Heated Seats',
        'Ventilated Seats',
        'Leather Seats',
        'Electric Seats',
        'Memory Seats',
        'Sunroof',
        'Panoramic Roof',
        'Cruise Control'
    ],
    'Technology' => [
        'Bluetooth',
        'USB Port',
        'Apple CarPlay',
        'Android Auto',
        'Navigation System',
        'Touchscreen',
        'Premium Sound System',
        'DAB Radio',
        'Keyless Entry',
        'Start/Stop Button'
    ],
    'Exterior' => [
        'Alloy Wheels',
        'LED Headlights',
        'Xenon Headlights',
        'Fog Lights',
        'Roof Rails',
        'Tow Bar',
        'Metallic Paint',
        'Electric Mirrors',
        'Heated Mirrors',
        'Privacy Glass'
    ],
    'Performance' => [
        'Turbo',
        'Sport Mode',
        'Paddle Shifters',
        'All-Wheel Drive',
        'Limited Slip Differential',
        'Sport Suspension',
        'Performance Brakes'
    ]
];

// Get current vehicle features
$current_features = fn_vehicles_get_features($vehicle_id);

// Page header data
$page_header = [
    'title' => 'Vehicle Features',
    'subtitle' => $vehicle_data['make'] . ' ' . $vehicle_data['model'] . ' (' . $vehicle_data['year'] . ')',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Vehicles' => '/vehicles',
        'Features' => ''
    ]
];

// Load view
require BASE_PATH . 'views/vehicles/vehicles-features.php';
