<?php
/**
 * Vehicles Listing Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get filters from query string
$filters = [];
if (isset($_GET['status'])) {
    $filters['status'] = $_GET['status'];
}
if (isset($_GET['make'])) {
    $filters['make'] = $_GET['make'];
}
if (isset($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;

// Get vehicles
$vehicles = fn_vehicles_get_all($company_id, $filters, $limit, $offset);
$makes = fn_vehicles_get_makes($company_id);

$page_header = [
    'title' => 'Vehicle Inventory',
    'subtitle' => 'Manage your vehicle stock'
];

require BASE_PATH . 'views/vehicles/vehicles.php';
