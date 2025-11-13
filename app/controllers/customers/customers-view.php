<?php
/**
 * View Customer Details Controller
 * Display customer profile with purchase history
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get customer ID
if (empty($_GET['id'])) {
    header("Location: /customers");
    exit;
}

$customer_id = intval($_GET['id']);
$customer_data = fn_core_database_row(
    "SELECT * FROM customers WHERE customer_id = ? AND company_id = ?",
    [$customer_id, $company_id]
);

if (!$customer_data) {
    header("Location: /customers");
    exit;
}

$error = null;
$success = null;

// Handle customer update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_customer'])) {
    $updateData = [
        'first_name' => trim($_POST['first_name'] ?? ''),
        'last_name' => trim($_POST['last_name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'phone' => trim($_POST['phone'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'county' => trim($_POST['county'] ?? ''),
        'postcode' => trim($_POST['postcode'] ?? ''),
        'country' => trim($_POST['country'] ?? 'Ireland'),
        'notes' => trim($_POST['notes'] ?? '')
    ];

    if (empty($updateData['first_name']) || empty($updateData['last_name'])) {
        $error = 'First name and last name are required.';
    } else {
        $result = fn_core_update_row('customers', $customer_id, $updateData, 'customer_id');

        if ($result) {
            $success = 'Customer updated successfully.';
            // Refresh customer data
            $customer_data = fn_core_database_row(
                "SELECT * FROM customers WHERE customer_id = ? AND company_id = ?",
                [$customer_id, $company_id]
            );
        } else {
            $error = 'Failed to update customer.';
        }
    }
}

// Get customer purchase history
$purchases = fn_core_database_rows(
    "SELECT si.*, v.make, v.model, v.year, v.registration
     FROM sales_invoices si
     LEFT JOIN vehicles v ON si.vehicle_id = v.vehicle_id
     WHERE si.customer_id = ? AND si.company_id = ?
     ORDER BY si.sale_date DESC",
    [$customer_id, $company_id]
);

// Get customer service history
$service_history = fn_core_database_rows(
    "SELECT * FROM service_invoices
     WHERE customer_id = ? AND company_id = ?
     ORDER BY service_date DESC",
    [$customer_id, $company_id]
);

// Get customer enquiries
$enquiries = fn_core_database_rows(
    "SELECT e.*, v.make, v.model, v.year
     FROM enquiries e
     LEFT JOIN vehicles v ON e.vehicle_id = v.vehicle_id
     WHERE e.email = ? AND e.company_id = ?
     ORDER BY e.created_date DESC",
    [$customer_data['email'], $company_id]
);

// Get customer leads
$leads = fn_core_database_rows(
    "SELECT l.*, v.make, v.model, v.year
     FROM crm_leads l
     LEFT JOIN vehicles v ON l.vehicle_id = v.vehicle_id
     WHERE l.email = ? AND l.company_id = ?
     ORDER BY l.created_date DESC",
    [$customer_data['email'], $company_id]
);

// Calculate customer stats
$stats = [
    'total_purchases' => count($purchases),
    'total_spent' => array_sum(array_column($purchases, 'total_amount')),
    'service_visits' => count($service_history),
    'enquiries' => count($enquiries),
    'leads' => count($leads)
];

// Page header data
$page_header = [
    'title' => 'Customer Profile',
    'subtitle' => $customer_data['first_name'] . ' ' . $customer_data['last_name'],
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Customers' => '/customers',
        'View Customer' => ''
    ]
];

// Load view
require BASE_PATH . 'views/customers/customers-view.php';
