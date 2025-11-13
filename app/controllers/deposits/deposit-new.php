<?php
/**
 * Create New Deposit Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $vehicleId = intval($_POST['vehicle_id'] ?? 0);
    $customerId = !empty($_POST['customer_id']) ? intval($_POST['customer_id']) : null;
    $amount = floatval($_POST['amount'] ?? 0);
    $paymentMethod = $_POST['payment_method'] ?? 'cash';

    if (empty($vehicleId)) {
        $error = 'Vehicle is required.';
    } elseif ($amount <= 0) {
        $error = 'Deposit amount must be greater than 0.';
    } else {
        // Get vehicle details
        $vehicle = fn_vehicles_get($vehicleId, $company_id);

        if (!$vehicle) {
            $error = 'Vehicle not found.';
        } else {
            // If customer_id not provided, create customer from form data
            if (!$customerId) {
                $customerData = [
                    'company_id' => $company_id,
                    'first_name' => trim($_POST['customer_first_name'] ?? ''),
                    'last_name' => trim($_POST['customer_last_name'] ?? ''),
                    'email' => trim($_POST['customer_email'] ?? ''),
                    'phone' => trim($_POST['customer_phone'] ?? ''),
                    'address' => trim($_POST['customer_address'] ?? ''),
                    'created_date' => date('Y-m-d H:i:s')
                ];

                if (empty($customerData['first_name']) || empty($customerData['last_name'])) {
                    $error = 'Customer first name and last name are required.';
                } else {
                    $customerId = fn_core_create_row('customers', $customerData, 'customer_id');

                    if (!$customerId) {
                        $error = 'Failed to create customer.';
                    }
                }
            }

            if (empty($error) && $customerId) {
                // Create deposit
                $depositData = [
                    'company_id' => $company_id,
                    'customer_id' => $customerId,
                    'vehicle_id' => $vehicleId,
                    'deposit_reference' => 'DEP-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'amount' => $amount,
                    'payment_method' => $paymentMethod,
                    'payment_date' => date('Y-m-d'),
                    'status' => 'active',
                    'notes' => trim($_POST['notes'] ?? ''),
                    'created_by' => $user_data['user_id'],
                    'created_date' => date('Y-m-d H:i:s')
                ];

                $deposit_id = fn_core_create_row('deposits', $depositData, 'deposit_id');

                if ($deposit_id) {
                    // Update vehicle status to reserved
                    fn_core_database_query(
                        "UPDATE vehicles SET status = 'reserved' WHERE vehicle_id = ?",
                        [$vehicleId]
                    );

                    // Send confirmation email to customer
                    $customer = fn_core_database_row(
                        "SELECT * FROM customers WHERE customer_id = ?",
                        [$customerId]
                    );

                    if (!empty($customer['email'])) {
                        $emailBody = "Dear {$customer['first_name']},\n\n";
                        $emailBody .= "We have received your deposit of €" . number_format($amount, 2) . " for:\n\n";
                        $emailBody .= "{$vehicle['year']} {$vehicle['make']} {$vehicle['model']}\n";
                        $emailBody .= "Registration: {$vehicle['registration']}\n\n";
                        $emailBody .= "Deposit Reference: {$depositData['deposit_reference']}\n\n";
                        $emailBody .= "The vehicle has been reserved for you. We will contact you shortly to complete the sale.\n\n";
                        $emailBody .= "Best regards,\n" . fn_company_get($company_id)['company_name'];

                        fn_core_email_send([
                            'to' => $customer['email'],
                            'subject' => 'Deposit Confirmation',
                            'body' => $emailBody,
                            'company_id' => $company_id
                        ]);
                    }

                    header("Location: /deposits?created=1");
                    exit;
                } else {
                    $error = 'Failed to create deposit.';
                }
            }
        }
    }
}

// Get available vehicles (including reserved ones for deposit tracking)
$vehicles = fn_core_database_rows(
    "SELECT vehicle_id, make, model, year, registration, price, status
     FROM vehicles
     WHERE company_id = ? AND status IN ('available', 'reserved')
     ORDER BY created_date DESC",
    [$company_id]
);

// Get customers
$customers = fn_core_database_rows(
    "SELECT customer_id, first_name, last_name, email, phone
     FROM customers
     WHERE company_id = ?
     ORDER BY created_date DESC",
    [$company_id]
);

// Pre-fill from query parameters
$prefill = [];
if (!empty($_GET['vehicle_id'])) {
    $prefill['vehicle_id'] = intval($_GET['vehicle_id']);
}
if (!empty($_GET['customer_id'])) {
    $prefill['customer_id'] = intval($_GET['customer_id']);
}

// Page header data
$page_header = [
    'title' => 'New Deposit',
    'subtitle' => 'Record a customer deposit',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Deposits' => '/deposits',
        'New Deposit' => ''
    ]
];

// Load view
require BASE_PATH . 'views/deposits/deposit-new.php';
