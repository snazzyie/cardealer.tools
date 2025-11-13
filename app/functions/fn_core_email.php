<?php
/**
 * Core Email Functions
 *
 * Handle email sending via Postmark
 */

/**
 * Send email via Postmark
 *
 * @param string $to Recipient email
 * @param string $subject Email subject
 * @param string $body Email body (HTML)
 * @param string $template Template slug (optional)
 * @return bool Success status
 */
function fn_email_send($to, $subject, $body, $template = null) {
    $config = require BASE_PATH . 'config.php';

    // TODO: Implement Postmark email sending
    // This is a placeholder for now

    return true;
}

/**
 * Send welcome email to new user
 *
 * @param int $userId User ID
 * @return bool Success status
 */
function fn_email_send_welcome($userId) {
    // TODO: Implement welcome email
    return true;
}

/**
 * Send password reset email
 *
 * @param int $userId User ID
 * @param string $resetCode Reset code
 * @return bool Success status
 */
function fn_email_send_password_reset($userId, $resetCode) {
    // TODO: Implement password reset email
    return true;
}

/**
 * Send enquiry notification
 *
 * @param int $enquiryId Enquiry ID
 * @return bool Success status
 */
function fn_email_send_enquiry_notification($enquiryId) {
    // TODO: Implement enquiry notification
    return true;
}

/**
 * Send email (core wrapper function)
 *
 * @param array $emailData Email data array with keys: to, subject, body, from (optional), company_id (optional)
 * @return bool Success status
 */
function fn_core_email_send($emailData) {
    // Extract parameters
    $to = $emailData['to'] ?? '';
    $subject = $emailData['subject'] ?? '';
    $body = $emailData['body'] ?? '';
    $from = $emailData['from'] ?? null;

    if (empty($to) || empty($subject) || empty($body)) {
        error_log("fn_core_email_send: Missing required parameters");
        return false;
    }

    // Use the existing email send function
    return fn_email_send($to, $subject, $body);
}
