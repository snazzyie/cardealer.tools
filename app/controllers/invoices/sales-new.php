<?php
/**
 * Create New Sales Invoice Controller
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
    $salePrice = floatval($_POST['sale_price'] ?? 0);
    $saleDate = $_POST['sale_date'] ?? date('Y-m-d');

    if (empty($vehicleId)) {
        $error = 'Vehicle is required.';
    } elseif ($salePrice <= 0) {
        $error = 'Sale price is required.';
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
                    'city' => trim($_POST['customer_city'] ?? ''),
                    'county' => trim($_POST['customer_county'] ?? ''),
                    'postcode' => trim($_POST['customer_postcode'] ?? ''),
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
                // Calculate amounts
                $depositAmount = !empty($_POST['deposit_amount']) ? floatval($_POST['deposit_amount']) : 0;
                $tradeInValue = !empty($_POST['trade_in_value']) ? floatval($_POST['trade_in_value']) : 0;
                $discount = !empty($_POST['discount']) ? floatval($_POST['discount']) : 0;
                $fees = !empty($_POST['fees']) ? floatval($_POST['fees']) : 0;

                $subtotal = $salePrice - $discount;
                $totalAmount = $subtotal + $fees;
                $amountPaid = $depositAmount + $tradeInValue;
                $balanceDue = $totalAmount - $amountPaid;

                // Create invoice
                $invoiceData = [
                    'company_id' => $company_id,
                    'customer_id' => $customerId,
                    'vehicle_id' => $vehicleId,
                    'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'sale_date' => $saleDate,
                    'sale_price' => $salePrice,
                    'discount' => $discount,
                    'fees' => $fees,
                    'total_amount' => $totalAmount,
                    'deposit_amount' => $depositAmount,
                    'trade_in_value' => $tradeInValue,
                    'amount_paid' => $amountPaid,
                    'balance_due' => $balanceDue,
                    'payment_status' => $balanceDue <= 0 ? 'paid' : 'pending',
                    'payment_method' => $_POST['payment_method'] ?? 'cash',
                    'status' => 'completed',
                    'notes' => trim($_POST['notes'] ?? ''),
                    'created_by' => $user_data['user_id'],
                    'created_date' => date('Y-m-d H:i:s')
                ];

                $invoice_id = fn_core_create_row('sales_invoices', $invoiceData, 'invoice_id');

                if ($invoice_id) {
                    // Update vehicle status to sold
                    fn_core_database_query(
                        "UPDATE vehicles SET status = 'sold', sold_date = ? WHERE vehicle_id = ?",
                        [$saleDate, $vehicleId]
                    );

                    header("Location: /invoices/sales/view?id={$invoice_id}&created=1");
                    exit;
                } else {
                    $error = 'Failed to create invoice.';
                }
            }
        }
    }
}

// Get available vehicles
$vehicles = fn_core_database_rows(
    "SELECT vehicle_id, make, model, year, registration, price
     FROM vehicles
     WHERE company_id = ? AND status = 'available'
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
    $vehicle = fn_vehicles_get(intval($_GET['vehicle_id']), $company_id);
    if ($vehicle) {
        $prefill['vehicle_id'] = $vehicle['vehicle_id'];
        $prefill['sale_price'] = $vehicle['price'];
    }
}
if (!empty($_GET['customer_id'])) {
    $prefill['customer_id'] = intval($_GET['customer_id']);
}

// Page header data
$page_header = [
    'title' => 'Create Sales Invoice',
    'subtitle' => 'Record a vehicle sale',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Invoices' => '/invoices',
        'Sales' => '/invoices/sales',
        'New Invoice' => ''
    ]
];

// Load view
require BASE_PATH . 'views/invoices/sales-new.php';
