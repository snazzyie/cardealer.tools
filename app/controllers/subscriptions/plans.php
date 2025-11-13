<?php
/**
 * Subscription Plans Page
 */

fn_require_login(2);

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

$company_data = fn_company_get($company_id);
$current_subscription = fn_subscriptions_get_subscription($company_id);
$plans = fn_subscriptions_get_plans();

// Handle plan selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['plan_id'])) {
    $selectedPlanId = $_POST['plan_id'];
    $selectedPlan = null;

    foreach ($plans as $plan) {
        if ($plan['plan_id'] === $selectedPlanId) {
            $selectedPlan = $plan;
            break;
        }
    }

    if ($selectedPlan) {
        // Create Stripe checkout session
        $session = fn_subscriptions_create_checkout_session(
            $company_id,
            $selectedPlan['plan_id'],
            $selectedPlan['stripe_price_id']
        );

        if ($session) {
            // Redirect to Stripe Checkout
            header("Location: " . $session->url);
            exit;
        } else {
            $error = "Failed to create checkout session. Please try again.";
        }
    }
}

$page_header = [
    'title' => 'Subscription Plans',
    'subtitle' => 'Choose the perfect plan for your dealership'
];

require BASE_PATH . 'views/subscriptions/plans.php';
