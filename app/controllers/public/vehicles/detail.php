<?php
/**
 * Public Vehicle Detail Controller
 */

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    header("Location: /vehicles");
    exit;
}

// Get company from subdomain or domain
$host = $_SERVER['HTTP_HOST'];
$company_data = null;

if (strpos($host, '.cardealer.tools') !== false) {
    $subdomain = explode('.', $host)[0];
    if ($subdomain !== 'cardealer' && $subdomain !== 'www') {
        $company_data = fn_company_get_by_subdomain($subdomain);
    }
} else {
    $company_data = fn_company_get_by_domain($host);
}

// Fallback to first company for development
if (!$company_data) {
    $companies = fn_company_get_all(1, 0);
    $company_data = $companies[0] ?? null;
}

if (!$company_data) {
    echo "No dealer configured for this domain.";
    exit;
}

$company_id = $company_data['company_id'];

// Get vehicle by slug
$query = "SELECT * FROM vehicles WHERE slug = ? AND company_id = ? AND status = 'available'";
$vehicle = fn_core_database_row($query, [$slug, $company_id]);

if (!$vehicle) {
    header("Location: /vehicles");
    exit;
}

// Get vehicle images
$images = fn_vehicles_get_images($vehicle['vehicle_id']);

// Handle enquiry form submission
$enquiry_success = false;
$enquiry_error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'enquiry') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (empty($firstName) || empty($email) || empty($phone)) {
        $enquiry_error = 'Please fill in all required fields.';
    } else {
        // Create lead in CRM
        $leadData = [
            'vehicle_id' => $vehicle['vehicle_id'],
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'source' => 'website',
            'status' => 'new',
            'notes' => $message
        ];

        $leadId = fn_crm_create_lead($company_id, $leadData);

        if ($leadId) {
            $enquiry_success = true;
            // TODO: Send email notification via Postmark
        } else {
            $enquiry_error = 'Failed to submit enquiry. Please try again.';
        }
    }
}

$page_title = $vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model'] . ' - ' . $company_data['company_name'];

require BASE_PATH . 'views/public/vehicles/detail.php';
