<?php
/**
 * Enquiries List Controller
 * Display all enquiries with filtering
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_enquiry_id'])) {
    $enquiryId = intval($_POST['delete_enquiry_id']);
    $success = fn_core_database_query(
        "DELETE FROM enquiries WHERE enquiry_id = ? AND company_id = ?",
        [$enquiryId, $company_id]
    );

    if ($success) {
        header("Location: /enquiries?deleted=1");
        exit;
    } else {
        $error = "Failed to delete enquiry.";
    }
}

// Handle convert to lead
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convert_to_lead'])) {
    $enquiryId = intval($_POST['enquiry_id']);

    // Get enquiry data
    $enquiry = fn_core_database_row(
        "SELECT * FROM enquiries WHERE enquiry_id = ? AND company_id = ?",
        [$enquiryId, $company_id]
    );

    if ($enquiry) {
        // Create lead from enquiry
        $leadData = [
            'company_id' => $company_id,
            'first_name' => $enquiry['first_name'],
            'last_name' => $enquiry['last_name'],
            'email' => $enquiry['email'],
            'phone' => $enquiry['phone'],
            'source' => 'website_enquiry',
            'status' => 'new',
            'vehicle_id' => $enquiry['vehicle_id'],
            'notes' => $enquiry['message'],
            'created_by' => $user_data['user_id']
        ];

        $new_lead_id = fn_crm_create_lead($leadData);

        if ($new_lead_id) {
            // Update enquiry status
            fn_core_database_query(
                "UPDATE enquiries SET status = 'converted', converted_to_lead_id = ? WHERE enquiry_id = ?",
                [$new_lead_id, $enquiryId]
            );

            header("Location: /crm/leads/view?id={$new_lead_id}&from_enquiry=1");
            exit;
        } else {
            $error = "Failed to convert enquiry to lead.";
        }
    }
}

// Get filters
$filters = [];
if (!empty($_GET['status'])) {
    $filters['status'] = $_GET['status'];
}
if (!empty($_GET['type'])) {
    $filters['type'] = $_GET['type'];
}
if (!empty($_GET['search'])) {
    $filters['search'] = $_GET['search'];
}

// Build query
$query = "SELECT e.*, v.make, v.model, v.year, v.price
          FROM enquiries e
          LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
          WHERE e.company_id = ?";
$params = [$company_id];

if (!empty($filters['status'])) {
    $query .= " AND e.status = ?";
    $params[] = $filters['status'];
}

if (!empty($filters['type'])) {
    $query .= " AND e.enquiry_type = ?";
    $params[] = $filters['type'];
}

if (!empty($filters['search'])) {
    $query .= " AND (e.first_name LIKE ? OR e.last_name LIKE ? OR e.email LIKE ? OR e.phone LIKE ?)";
    $searchTerm = '%' . $filters['search'] . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$query .= " ORDER BY e.created_date DESC";

$enquiries = fn_core_database_rows($query, $params);

// Get stats
$stats = [
    'total' => fn_core_count_rows_company('enquiries', $company_id),
    'new' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM enquiries WHERE company_id = ? AND status = 'new'",
        [$company_id]
    )['total'] ?? 0,
    'contacted' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM enquiries WHERE company_id = ? AND status = 'contacted'",
        [$company_id]
    )['total'] ?? 0,
    'converted' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM enquiries WHERE company_id = ? AND status = 'converted'",
        [$company_id]
    )['total'] ?? 0
];

// Page header data
$page_header = [
    'title' => 'Enquiries',
    'subtitle' => 'Manage customer enquiries',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Enquiries' => ''
    ]
];

// Load view
require BASE_PATH . 'views/enquiries/enquiries.php';
