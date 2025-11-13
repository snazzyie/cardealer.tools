<?php
/**
 * Stripe Webhook Handler
 * This endpoint receives notifications from Stripe about subscription events
 */

// Get the raw POST body
$payload = @file_get_contents('php://input');
$signature = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

// Handle the webhook
$result = fn_subscriptions_handle_webhook($payload, $signature);

if ($result) {
    http_response_code(200);
    echo json_encode(['status' => 'success']);
} else {
    http_response_code(400);
    echo json_encode(['status' => 'error']);
}
