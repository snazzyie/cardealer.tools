<?php
/**
 * Delete Vehicle Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id || $permission < 2) {
    header("Location: /vehicles");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: /vehicles");
    exit;
}

$vehicle_id = intval($_GET['id']);

// Verify vehicle belongs to this company
$vehicle = fn_vehicles_get($vehicle_id, $company_id);

if (!$vehicle) {
    header("Location: /vehicles?error=not_found");
    exit;
}

// Delete vehicle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $result = fn_vehicles_delete($vehicle_id, $company_id);

    if ($result) {
        header("Location: /vehicles?deleted=1");
        exit;
    } else {
        header("Location: /vehicles?error=delete_failed");
        exit;
    }
}

// Show confirmation page
$page_header = [
    'title' => 'Delete Vehicle',
    'subtitle' => 'Confirm vehicle deletion'
];

require BASE_PATH . 'views/vehicles/vehicle-delete.php';
