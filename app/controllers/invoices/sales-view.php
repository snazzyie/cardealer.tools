<?php
/**
 * View Sales Invoice Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Get invoice ID
if (empty($_GET['id'])) {
    header("Location: /invoices/sales");
    exit;
}

$invoice_id = intval($_GET['id']);

// Get invoice with related data
$invoice_data = fn_core_database_row(
    "SELECT si.*, v.make, v.model, v.year, v.registration, v.vin,
     c.first_name, c.last_name, c.email, c.phone, c.address, c.city, c.county, c.postcode,
     u.first_name as created_by_first_name, u.last_name as created_by_last_name
     FROM sales_invoices si
     LEFT JOIN vehicles v ON si.vehicle_id = v.vehicle_id
     LEFT JOIN customers c ON si.customer_id = c.customer_id
     LEFT JOIN users u ON si.created_by = u.user_id
     WHERE si.invoice_id = ? AND si.company_id = ?",
    [$invoice_id, $company_id]
);

if (!$invoice_data) {
    header("Location: /invoices/sales");
    exit;
}

$error = null;
$success = null;

// Handle payment recording
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['record_payment'])) {
    $paymentAmount = floatval($_POST['payment_amount'] ?? 0);
    $paymentMethod = $_POST['payment_method'] ?? 'cash';
    $paymentDate = $_POST['payment_date'] ?? date('Y-m-d');

    if ($paymentAmount > 0) {
        // Update invoice
        $newAmountPaid = $invoice_data['amount_paid'] + $paymentAmount;
        $newBalanceDue = $invoice_data['balance_due'] - $paymentAmount;
        $newPaymentStatus = $newBalanceDue <= 0 ? 'paid' : 'partial';

        $result = fn_core_database_query(
            "UPDATE sales_invoices
             SET amount_paid = ?, balance_due = ?, payment_status = ?, last_payment_date = ?
             WHERE invoice_id = ?",
            [$newAmountPaid, $newBalanceDue, $newPaymentStatus, $paymentDate, $invoice_id]
        );

        if ($result) {
            // Record payment transaction
            fn_core_database_query(
                "INSERT INTO invoice_payments (invoice_id, invoice_type, amount, payment_method, payment_date, created_date)
                 VALUES (?, 'sales', ?, ?, ?, NOW())",
                [$invoice_id, $paymentAmount, $paymentMethod, $paymentDate]
            );

            $success = 'Payment recorded successfully.';
            // Refresh invoice data
            $invoice_data = fn_core_database_row(
                "SELECT si.*, v.make, v.model, v.year, v.registration, v.vin,
                 c.first_name, c.last_name, c.email, c.phone, c.address, c.city, c.county, c.postcode,
                 u.first_name as created_by_first_name, u.last_name as created_by_last_name
                 FROM sales_invoices si
                 LEFT JOIN vehicles v ON si.vehicle_id = v.vehicle_id
                 LEFT JOIN customers c ON si.customer_id = c.customer_id
                 LEFT JOIN users u ON si.created_by = u.user_id
                 WHERE si.invoice_id = ? AND si.company_id = ?",
                [$invoice_id, $company_id]
            );
        } else {
            $error = 'Failed to record payment.';
        }
    } else {
        $error = 'Payment amount must be greater than 0.';
    }
}

// Get payment history
$payments = fn_core_database_rows(
    "SELECT * FROM invoice_payments
     WHERE invoice_id = ? AND invoice_type = 'sales'
     ORDER BY payment_date DESC",
    [$invoice_id]
);

// Get company data for invoice display
$company_data = fn_company_get($company_id);

// Page header data
$page_header = [
    'title' => 'Sales Invoice',
    'subtitle' => $invoice_data['invoice_number'],
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Invoices' => '/invoices',
        'Sales' => '/invoices/sales',
        'View Invoice' => ''
    ]
];

// Load view
require BASE_PATH . 'views/invoices/sales-view.php';
