<?php
/**
 * Refund Deposit Controller
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Must be POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /deposits");
    exit;
}

// Get deposit ID
if (empty($_POST['deposit_id'])) {
    header("Location: /deposits?error=missing_id");
    exit;
}

$deposit_id = intval($_POST['deposit_id']);

// Get deposit data
$deposit = fn_core_database_row(
    "SELECT d.*, v.vehicle_id, c.first_name, c.last_name, c.email
     FROM deposits d
     LEFT JOIN vehicles v ON d.vehicle_id = v.vehicle_id
     LEFT JOIN customers c ON d.customer_id = c.customer_id
     WHERE d.deposit_id = ? AND d.company_id = ?",
    [$deposit_id, $company_id]
);

if (!$deposit) {
    header("Location: /deposits?error=not_found");
    exit;
}

// Check if deposit is already refunded or completed
if ($deposit['status'] !== 'active') {
    header("Location: /deposits?error=invalid_status");
    exit;
}

$error = null;
$success = null;

// Process refund
$refundAmount = floatval($_POST['refund_amount'] ?? $deposit['amount']);
$refundReason = trim($_POST['refund_reason'] ?? '');
$refundMethod = $_POST['refund_method'] ?? $deposit['payment_method'];

// Validate refund amount
if ($refundAmount > $deposit['amount']) {
    $error = 'Refund amount cannot exceed deposit amount.';
} elseif ($refundAmount <= 0) {
    $error = 'Refund amount must be greater than 0.';
} else {
    // Update deposit status
    $updateData = [
        'status' => 'refunded',
        'refund_amount' => $refundAmount,
        'refund_date' => date('Y-m-d'),
        'refund_reason' => $refundReason,
        'refund_method' => $refundMethod,
        'refunded_by' => $user_data['user_id']
    ];

    $result = fn_core_update_row('deposits', $deposit_id, $updateData, 'deposit_id');

    if ($result) {
        // Update vehicle status back to available if it was reserved
        if ($deposit['vehicle_id']) {
            fn_core_database_query(
                "UPDATE vehicles SET status = 'available' WHERE vehicle_id = ? AND status = 'reserved'",
                [$deposit['vehicle_id']]
            );
        }

        // Send refund confirmation email
        if (!empty($deposit['email'])) {
            $emailBody = "Dear {$deposit['first_name']},\n\n";
            $emailBody .= "We confirm that we have processed a refund of €" . number_format($refundAmount, 2) . " for your deposit.\n\n";
            $emailBody .= "Deposit Reference: {$deposit['deposit_reference']}\n";
            $emailBody .= "Refund Method: {$refundMethod}\n";
            if (!empty($refundReason)) {
                $emailBody .= "Reason: {$refundReason}\n";
            }
            $emailBody .= "\nThe refund should appear in your account within 3-5 business days.\n\n";
            $emailBody .= "Best regards,\n" . fn_company_get($company_id)['company_name'];

            fn_core_email_send([
                'to' => $deposit['email'],
                'subject' => 'Deposit Refund Confirmation',
                'body' => $emailBody,
                'company_id' => $company_id
            ]);
        }

        header("Location: /deposits?refunded=1");
        exit;
    } else {
        header("Location: /deposits?error=refund_failed");
        exit;
    }
}

// If there's an error, redirect back
if ($error) {
    header("Location: /deposits?error=" . urlencode($error));
    exit;
}
