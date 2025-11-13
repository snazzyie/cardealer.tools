<?php
/**
 * Finance Partners - Links to External Finance Companies
 * Simplified approach - no data collection, just links to partners
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

$company_data = fn_company_get($company_id);

// Finance partners (configured per company or system-wide)
$finance_partners = [
    [
        'name' => 'Alphera Financial Services',
        'logo' => 'https://www.alphera.ie/assets/images/logo.png',
        'url' => 'https://www.alphera.ie',
        'description' => 'Flexible car finance solutions',
        'types' => ['HP', 'PCP', 'Lease']
    ],
    [
        'name' => 'AIB Finance',
        'logo' => 'https://aib.ie/content/dam/aib/logos/aib-logo.png',
        'url' => 'https://aib.ie/our-products/loans/car-loans',
        'description' => 'Competitive rates from Ireland\'s leading bank',
        'types' => ['Car Loan', 'Personal Loan']
    ],
    [
        'name' => 'Bank of Ireland Finance',
        'logo' => 'https://www.bankofireland.com/fs/img/boi-logo.png',
        'url' => 'https://personalbanking.bankofireland.com/borrow/car-finance/',
        'description' => 'Car loans from Bank of Ireland',
        'types' => ['Car Loan']
    ],
    [
        'name' => 'First Citizen Finance',
        'logo' => 'https://www.firstcitizen.ie/assets/logo.png',
        'url' => 'https://www.firstcitizen.ie',
        'description' => 'Specialist motor finance provider',
        'types' => ['HP', 'PCP']
    ]
];

$page_header = [
    'title' => 'Finance Partners',
    'subtitle' => 'Connect customers with our trusted finance partners'
];

require BASE_PATH . 'views/finance/finance-partners.php';
