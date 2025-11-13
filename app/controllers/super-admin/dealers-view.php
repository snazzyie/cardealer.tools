<?php
/**
 * Super Admin - View Dealer Details Controller
 * View specific dealer with company switching capability
 */

// Super admin check (user_type 3)
fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$permission = $user_data['user_type'];

if ($permission < 3) {
    header("Location: /dash");
    exit;
}

// Get company ID
if (empty($_GET['id'])) {
    header("Location: /super-admin/dealers");
    exit;
}

$view_company_id = intval($_GET['id']);

// Get company data
$company_data = fn_company_get($view_company_id);

if (!$company_data) {
    header("Location: /super-admin/dealers");
    exit;
}

$error = null;
$success = null;

// Handle company switching (super admin can impersonate)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['switch_to_company'])) {
    $result = fn_super_admin_switch_to_company($view_company_id);

    if ($result) {
        header("Location: /dash?switched=1");
        exit;
    } else {
        $error = 'Failed to switch to company. Super admin access required.';
    }
}

// Handle subscription update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subscription'])) {
    $newStatus = $_POST['subscription_status'];
    $validStatuses = ['trialing', 'active', 'cancelled', 'expired'];

    if (in_array($newStatus, $validStatuses)) {
        $result = fn_core_database_query(
            "UPDATE subscriptions SET status = ? WHERE company_id = ?",
            [$newStatus, $view_company_id]
        );

        if ($result) {
            $success = 'Subscription status updated successfully.';
        } else {
            $error = 'Failed to update subscription status.';
        }
    }
}

// Get company users
$users = fn_core_database_rows(
    "SELECT user_id, first_name, last_name, email, user_type, user_role, status, created_date, last_login
     FROM users
     WHERE company_id = ?
     ORDER BY user_type DESC, created_date ASC",
    [$view_company_id]
);

// Get subscription info
$subscription = fn_core_database_row(
    "SELECT s.*, sp.plan_name, sp.price, sp.billing_period, sp.features
     FROM subscriptions s
     LEFT JOIN subscription_plans sp ON s.plan_id = sp.plan_id
     WHERE s.company_id = ?
     ORDER BY s.created_date DESC
     LIMIT 1",
    [$view_company_id]
);

// Get company stats
$stats = [
    'total_users' => count($users),
    'active_users' => count(array_filter($users, fn($u) => $u['status'] === 'active')),
    'total_vehicles' => fn_core_count_rows_company('vehicles', $view_company_id),
    'available_vehicles' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM vehicles WHERE company_id = ? AND status = 'available'",
        [$view_company_id]
    )['total'] ?? 0,
    'total_leads' => fn_core_count_rows_company('crm_leads', $view_company_id),
    'active_leads' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM crm_leads WHERE company_id = ? AND status NOT IN ('won', 'lost')",
        [$view_company_id]
    )['total'] ?? 0,
    'total_enquiries' => fn_core_count_rows_company('enquiries', $view_company_id),
    'total_customers' => fn_core_count_rows_company('customers', $view_company_id),
    'total_sales' => fn_core_database_row(
        "SELECT COUNT(*) as total FROM sales_invoices WHERE company_id = ?",
        [$view_company_id]
    )['total'] ?? 0,
    'total_revenue' => fn_core_database_row(
        "SELECT SUM(total_amount) as total FROM sales_invoices WHERE company_id = ?",
        [$view_company_id]
    )['total'] ?? 0
];

// Get recent activity
$recent_activity = fn_core_database_rows(
    "SELECT 'vehicle' as type, CONCAT('Added vehicle: ', make, ' ', model) as description, created_date, NULL as user_name
     FROM vehicles
     WHERE company_id = ?
     UNION ALL
     SELECT 'lead' as type, CONCAT('New lead: ', first_name, ' ', last_name) as description, created_date, NULL as user_name
     FROM crm_leads
     WHERE company_id = ?
     UNION ALL
     SELECT 'user' as type, CONCAT('User login: ', first_name, ' ', last_name) as description, last_login, CONCAT(first_name, ' ', last_name) as user_name
     FROM users
     WHERE company_id = ? AND last_login IS NOT NULL
     ORDER BY created_date DESC
     LIMIT 20",
    [$view_company_id, $view_company_id, $view_company_id]
);

// Page header data
$page_header = [
    'title' => 'Dealer Details',
    'subtitle' => $company_data['company_name'],
    'breadcrumbs' => [
        'Super Admin' => '/super-admin',
        'Dealers' => '/super-admin/dealers',
        'View Dealer' => ''
    ]
];

// Load view
require BASE_PATH . 'views/super-admin/dealers-view.php';
