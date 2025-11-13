<?php
/**
 * Subscriptions Functions
 * Handles Stripe subscription management
 */

/**
 * Get all subscription plans
 */
function fn_subscriptions_get_plans() {
    return [
        [
            'plan_id' => 'starter',
            'name' => 'Starter Plan',
            'price' => 49.00,
            'currency' => 'EUR',
            'interval' => 'month',
            'features' => [
                'Up to 25 vehicles',
                'Basic CRM',
                'Website with subdomain',
                'Email support'
            ],
            'stripe_price_id' => 'price_starter_monthly' // Replace with real Stripe Price ID
        ],
        [
            'plan_id' => 'professional',
            'name' => 'Professional Plan',
            'price' => 99.00,
            'currency' => 'EUR',
            'interval' => 'month',
            'features' => [
                'Up to 100 vehicles',
                'Advanced CRM with pipeline',
                'Website with custom domain',
                'Calendar & appointments',
                'Invoice generation',
                'Priority email support'
            ],
            'stripe_price_id' => 'price_professional_monthly',
            'popular' => true
        ],
        [
            'plan_id' => 'enterprise',
            'name' => 'Enterprise Plan',
            'price' => 199.00,
            'currency' => 'EUR',
            'interval' => 'month',
            'features' => [
                'Unlimited vehicles',
                'Full CRM suite',
                'Multiple websites',
                'White-label options',
                'API access',
                'Dedicated support',
                'Custom integrations'
            ],
            'stripe_price_id' => 'price_enterprise_monthly'
        ]
    ];
}

/**
 * Get company subscription details
 */
function fn_subscriptions_get_subscription($companyId) {
    $query = "SELECT * FROM subscriptions WHERE company_id = ? AND status IN ('active', 'trialing') ORDER BY created_date DESC LIMIT 1";
    return fn_core_database_row($query, [$companyId]);
}

/**
 * Create Stripe checkout session
 */
function fn_subscriptions_create_checkout_session($companyId, $planId, $stripePriceId) {
    global $config;

    $company = fn_company_get($companyId);

    try {
        \Stripe\Stripe::setApiKey($config['stripe']['secret_key']);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $stripePriceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => 'https://' . $config['app_domain'] . '/subscriptions/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => 'https://' . $config['app_domain'] . '/subscriptions/plans',
            'client_reference_id' => $companyId,
            'customer_email' => $company['contact_email'],
            'metadata' => [
                'company_id' => $companyId,
                'plan_id' => $planId
            ]
        ]);

        return $session;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        error_log("Stripe error: " . $e->getMessage());
        return false;
    }
}

/**
 * Save subscription to database
 */
function fn_subscriptions_create_subscription($companyId, $data) {
    $query = "INSERT INTO subscriptions
              (company_id, stripe_subscription_id, stripe_customer_id, plan_id, status, current_period_start, current_period_end, created_date)
              VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";

    $params = [
        $companyId,
        $data['stripe_subscription_id'],
        $data['stripe_customer_id'],
        $data['plan_id'],
        $data['status'],
        $data['current_period_start'],
        $data['current_period_end']
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update subscription status
 */
function fn_subscriptions_update_status($companyId, $stripeSubscriptionId, $status) {
    $query = "UPDATE subscriptions SET status = ?, updated_date = NOW() WHERE company_id = ? AND stripe_subscription_id = ?";
    return fn_core_edit_row_no_redirect($query, [$status, $companyId, $stripeSubscriptionId]);
}

/**
 * Cancel subscription
 */
function fn_subscriptions_cancel($companyId, $stripeSubscriptionId) {
    global $config;

    try {
        \Stripe\Stripe::setApiKey($config['stripe']['secret_key']);

        $subscription = \Stripe\Subscription::retrieve($stripeSubscriptionId);
        $subscription->cancel();

        // Update database
        fn_subscriptions_update_status($companyId, $stripeSubscriptionId, 'cancelled');

        // Update company status
        $query = "UPDATE core_company SET status = 'suspended' WHERE company_id = ?";
        fn_core_edit_row_no_redirect($query, [$companyId]);

        return true;

    } catch (\Stripe\Exception\ApiErrorException $e) {
        error_log("Stripe error: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if company has active subscription
 */
function fn_subscriptions_is_active($companyId) {
    $query = "SELECT COUNT(*) as count FROM subscriptions
              WHERE company_id = ? AND status IN ('active', 'trialing')";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] > 0;
}

/**
 * Get subscription usage limits
 */
function fn_subscriptions_get_limits($planId) {
    $limits = [
        'starter' => [
            'max_vehicles' => 25,
            'max_users' => 3,
            'custom_domain' => false,
            'api_access' => false
        ],
        'professional' => [
            'max_vehicles' => 100,
            'max_users' => 10,
            'custom_domain' => true,
            'api_access' => false
        ],
        'enterprise' => [
            'max_vehicles' => 999999,
            'max_users' => 999999,
            'custom_domain' => true,
            'api_access' => true
        ]
    ];

    return $limits[$planId] ?? $limits['starter'];
}

/**
 * Check if company can add more vehicles
 */
function fn_subscriptions_can_add_vehicle($companyId) {
    $subscription = fn_subscriptions_get_subscription($companyId);

    if (!$subscription) {
        // No active subscription, check trial status
        $company = fn_company_get($companyId);
        if ($company['status'] === 'trial') {
            return true; // Allow during trial
        }
        return false;
    }

    $limits = fn_subscriptions_get_limits($subscription['plan_id']);
    $currentCount = fn_core_count_rows_company('vehicles', $companyId);

    return $currentCount < $limits['max_vehicles'];
}

/**
 * Handle Stripe webhook
 */
function fn_subscriptions_handle_webhook($payload, $signature) {
    global $config;

    try {
        \Stripe\Stripe::setApiKey($config['stripe']['secret_key']);
        $event = \Stripe\Webhook::constructEvent($payload, $signature, $config['stripe']['webhook_secret']);

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                fn_subscriptions_handle_checkout_completed($session);
                break;

            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                fn_subscriptions_handle_subscription_updated($subscription);
                break;

            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                fn_subscriptions_handle_subscription_deleted($subscription);
                break;

            case 'invoice.payment_succeeded':
                $invoice = $event->data->object;
                fn_subscriptions_handle_payment_succeeded($invoice);
                break;

            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                fn_subscriptions_handle_payment_failed($invoice);
                break;
        }

        return true;

    } catch (\Exception $e) {
        error_log("Webhook error: " . $e->getMessage());
        return false;
    }
}

/**
 * Handle successful checkout
 */
function fn_subscriptions_handle_checkout_completed($session) {
    $companyId = $session->client_reference_id;

    // Retrieve subscription details
    \Stripe\Stripe::setApiKey($GLOBALS['config']['stripe']['secret_key']);
    $subscription = \Stripe\Subscription::retrieve($session->subscription);

    // Save to database
    fn_subscriptions_create_subscription($companyId, [
        'stripe_subscription_id' => $subscription->id,
        'stripe_customer_id' => $subscription->customer,
        'plan_id' => $session->metadata->plan_id,
        'status' => $subscription->status,
        'current_period_start' => date('Y-m-d H:i:s', $subscription->current_period_start),
        'current_period_end' => date('Y-m-d H:i:s', $subscription->current_period_end)
    ]);

    // Update company status
    $query = "UPDATE core_company SET status = 'active' WHERE company_id = ?";
    fn_core_edit_row_no_redirect($query, [$companyId]);
}

/**
 * Handle subscription update
 */
function fn_subscriptions_handle_subscription_updated($subscription) {
    $query = "SELECT company_id FROM subscriptions WHERE stripe_subscription_id = ?";
    $result = fn_core_database_row($query, [$subscription->id]);

    if ($result) {
        fn_subscriptions_update_status($result['company_id'], $subscription->id, $subscription->status);
    }
}

/**
 * Handle subscription deletion
 */
function fn_subscriptions_handle_subscription_deleted($subscription) {
    $query = "SELECT company_id FROM subscriptions WHERE stripe_subscription_id = ?";
    $result = fn_core_database_row($query, [$subscription->id]);

    if ($result) {
        fn_subscriptions_cancel($result['company_id'], $subscription->id);
    }
}

/**
 * Handle successful payment
 */
function fn_subscriptions_handle_payment_succeeded($invoice) {
    // Log successful payment
    error_log("Payment succeeded for subscription: " . $invoice->subscription);
}

/**
 * Handle failed payment
 */
function fn_subscriptions_handle_payment_failed($invoice) {
    // Log failed payment and potentially notify company
    error_log("Payment failed for subscription: " . $invoice->subscription);

    // TODO: Send email notification to company about failed payment
}
