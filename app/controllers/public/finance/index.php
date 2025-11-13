<?php
/**
 * Public Finance Calculator Page Controller
 */

// Get company from subdomain or domain
$company_id = fn_core_get_company_from_domain();

if (!$company_id) {
    header("Location: /");
    exit;
}

// Get company data
$company_data = fn_company_get($company_id);

if (!$company_data) {
    header("Location: /");
    exit;
}

// Get finance partners
$finance_partners = fn_core_database_rows(
    "SELECT * FROM finance_partners WHERE company_id = ? AND is_active = 1 ORDER BY display_order, partner_name",
    [$company_id]
);

// Default finance calculation parameters
$default_params = [
    'vehicle_price' => $_GET['price'] ?? 20000,
    'deposit' => $_GET['deposit'] ?? 2000,
    'term_months' => $_GET['term'] ?? 60,
    'interest_rate' => $_GET['rate'] ?? 7.9,
    'balloon_payment' => $_GET['balloon'] ?? 0
];

// Calculate finance details if parameters provided
$finance_calculation = null;
if (!empty($_GET['calculate'])) {
    $price = floatval($default_params['vehicle_price']);
    $deposit = floatval($default_params['deposit']);
    $term = intval($default_params['term_months']);
    $rate = floatval($default_params['interest_rate']);
    $balloon = floatval($default_params['balloon_payment']);

    $amount_to_finance = $price - $deposit;
    $monthly_rate = ($rate / 100) / 12;

    // Calculate monthly payment (PCP style with balloon)
    if ($balloon > 0) {
        $amount_financed = $amount_to_finance - $balloon;
        $monthly_payment = ($amount_financed * $monthly_rate * pow(1 + $monthly_rate, $term)) / (pow(1 + $monthly_rate, $term) - 1);
    } else {
        // Standard HP calculation
        $monthly_payment = ($amount_to_finance * $monthly_rate * pow(1 + $monthly_rate, $term)) / (pow(1 + $monthly_rate, $term) - 1);
    }

    $total_payable = ($monthly_payment * $term) + $deposit + $balloon;
    $total_interest = $total_payable - $price;

    $finance_calculation = [
        'vehicle_price' => $price,
        'deposit' => $deposit,
        'amount_to_finance' => $amount_to_finance,
        'term_months' => $term,
        'interest_rate' => $rate,
        'balloon_payment' => $balloon,
        'monthly_payment' => round($monthly_payment, 2),
        'total_payable' => round($total_payable, 2),
        'total_interest' => round($total_interest, 2)
    ];
}

// Get vehicle if ID provided
$vehicle = null;
if (!empty($_GET['vehicle_id'])) {
    $vehicle = fn_vehicles_get(intval($_GET['vehicle_id']), $company_id);
    if ($vehicle) {
        $default_params['vehicle_price'] = $vehicle['price'];
    }
}

// Page header data
$page_header = [
    'title' => 'Finance Calculator',
    'subtitle' => 'Calculate your monthly payments'
];

// Load view
require BASE_PATH . 'views/public/finance/index.php';
