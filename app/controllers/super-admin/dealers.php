<?php
/**
 * Super Admin - Dealers List Controller
 * View all dealers/companies in the system
 */

// Super admin check (user_type 3)
fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$permission = $user_data['user_type'];

if ($permission < 3) {
    header("Location: /dash");
    exit;
}

// Get search/filter parameters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? 'all';

// Build query
$query = "SELECT
    c.*,
    COUNT(DISTINCT u.user_id) as user_count,
    COUNT(DISTINCT v.vehicle_id) as vehicle_count,
    COUNT(DISTINCT l.lead_id) as lead_count,
    s.status as subscription_status,
    s.plan_id as subscription_plan_id,
    s.trial_ends_at
FROM core_company c
LEFT JOIN users u ON c.company_id = u.company_id AND u.status = 'active'
LEFT JOIN vehicles v ON c.company_id = v.company_id
LEFT JOIN crm_leads l ON c.company_id = l.company_id
LEFT JOIN subscriptions s ON c.company_id = s.company_id
WHERE 1=1";

$params = [];

// Apply search
if (!empty($search)) {
    $query .= " AND (c.company_name LIKE ? OR c.trading_name LIKE ? OR c.company_email LIKE ? OR c.subdomain LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $params[] = $searchTerm;
}

$query .= " GROUP BY c.company_id";

// Apply status filter after GROUP BY
if ($status_filter === 'active') {
    $query .= " HAVING subscription_status = 'active'";
} elseif ($status_filter === 'trial') {
    $query .= " HAVING subscription_status = 'trialing'";
} elseif ($status_filter === 'cancelled') {
    $query .= " HAVING subscription_status IN ('cancelled', 'expired')";
}

$query .= " ORDER BY c.created_date DESC";

$dealers = fn_core_database_rows($query, $params);

// Add plan names to dealers
$plans = fn_subscriptions_get_plans();
$plansMap = [];
foreach ($plans as $plan) {
    $plansMap[$plan['plan_id']] = $plan['name'];
}

foreach ($dealers as &$dealer) {
    if (!empty($dealer['subscription_plan_id'])) {
        $dealer['plan_name'] = $plansMap[$dealer['subscription_plan_id']] ?? 'Unknown';
    } else {
        $dealer['plan_name'] = 'No Plan';
    }
}
unset($dealer);

// Get platform stats
$stats = [
    'total_dealers' => fn_core_database_row("SELECT COUNT(*) as total FROM core_company", [])['total'] ?? 0,
    'active_subscriptions' => fn_core_database_row(
        "SELECT COUNT(DISTINCT company_id) as total FROM subscriptions WHERE status = 'active'",
        []
    )['total'] ?? 0,
    'trial_subscriptions' => fn_core_database_row(
        "SELECT COUNT(DISTINCT company_id) as total FROM subscriptions WHERE status = 'trialing'",
        []
    )['total'] ?? 0,
    'total_users' => fn_core_database_row("SELECT COUNT(*) as total FROM users WHERE status = 'active'", [])['total'] ?? 0,
    'total_vehicles' => fn_core_database_row("SELECT COUNT(*) as total FROM vehicles", [])['total'] ?? 0,
    'mrr' => 0
];

// Calculate MRR from active subscriptions
$activeSubscriptions = fn_core_database_rows(
    "SELECT plan_id FROM subscriptions WHERE status = 'active'",
    []
);

$plansById = [];
foreach ($plans as $plan) {
    $plansById[$plan['plan_id']] = $plan;
}

$mrr = 0;
foreach ($activeSubscriptions as $sub) {
    if (isset($plansById[$sub['plan_id']]) && $plansById[$sub['plan_id']]['interval'] === 'month') {
        $mrr += $plansById[$sub['plan_id']]['price'];
    }
}
$stats['mrr'] = $mrr;

// Page header data
$page_header = [
    'title' => 'Dealers Management',
    'subtitle' => 'Manage all dealers on the platform',
    'breadcrumbs' => [
        'Super Admin' => '/super-admin',
        'Dealers' => ''
    ]
];

// Load view
require BASE_PATH . 'views/super-admin/dealers.php';
