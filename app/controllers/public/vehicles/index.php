<?php
/**
 * Public Vehicle Listings Controller
 */

// Get company from constant (set by public routing)
if (!defined('PUBLIC_SITE_COMPANY')) {
    http_response_code(500);
    echo '<h1>500 - Server Error</h1>';
    echo '<p>Company context not found.</p>';
    exit;
}

$company_data = PUBLIC_SITE_COMPANY;
$company_id = PUBLIC_SITE_COMPANY_ID;

// Get filters from query string
$filters = ['status' => 'available']; // Only show available vehicles on public site

if (isset($_GET['make'])) {
    $filters['make'] = $_GET['make'];
}
if (isset($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}
if (isset($_GET['min_price'])) {
    $min_price = floatval($_GET['min_price']);
}
if (isset($_GET['max_price'])) {
    $max_price = floatval($_GET['max_price']);
}
if (isset($_GET['fuel_type'])) {
    $fuel_type = $_GET['fuel_type'];
}
if (isset($_GET['transmission'])) {
    $transmission = $_GET['transmission'];
}
if (isset($_GET['body_type'])) {
    $body_type = $_GET['body_type'];
}

// Get vehicles with custom query for price filters
$query = "SELECT v.*,
          (SELECT image_url FROM vehicle_images WHERE vehicle_id = v.vehicle_id AND is_primary = 1 LIMIT 1) as primary_image
          FROM vehicles v
          WHERE v.company_id = ? AND v.status = 'available'";
$params = [$company_id];

if (!empty($filters['make'])) {
    $query .= " AND v.make = ?";
    $params[] = $filters['make'];
}

if (!empty($filters['search'])) {
    $query .= " AND (v.make LIKE ? OR v.model LIKE ? OR v.description LIKE ?)";
    $searchTerm = '%' . $filters['search'] . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

if (isset($min_price)) {
    $query .= " AND v.price >= ?";
    $params[] = $min_price;
}

if (isset($max_price)) {
    $query .= " AND v.price <= ?";
    $params[] = $max_price;
}

if (!empty($fuel_type)) {
    $query .= " AND v.fuel_type = ?";
    $params[] = $fuel_type;
}

if (!empty($transmission)) {
    $query .= " AND v.transmission = ?";
    $params[] = $transmission;
}

if (!empty($body_type)) {
    $query .= " AND v.body_type = ?";
    $params[] = $body_type;
}

$query .= " ORDER BY v.is_featured DESC, v.is_premium DESC, v.date_added DESC LIMIT 50";

$vehicles = fn_core_database_rows($query, $params);
$makes = fn_vehicles_get_makes($company_id);

$page_title = 'Quality Used Cars - ' . $company_data['company_name'];

require BASE_PATH . 'views/public/vehicles/index.php';
