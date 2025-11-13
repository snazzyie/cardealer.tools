<?php
/**
 * Communications Functions
 * Handles WhatsApp, SMS, Email automation and unified inbox
 */

/**
 * ====================
 * WHATSAPP INTEGRATION
 * ====================
 */

/**
 * Send WhatsApp message
 *
 * @param string $to Phone number (international format)
 * @param string $message Message text
 * @param int $companyId Company ID
 * @return array|false Result or false
 */
function fn_whatsapp_send_message($to, $message, $companyId) {
    global $config;

    if (!isset($config['whatsapp'])) {
        return false;
    }

    try {
        $url = "https://graph.facebook.com/v17.0/{$config['whatsapp']['phone_number_id']}/messages";

        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'text',
            'text' => ['body' => $message]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $config['whatsapp']['access_token'],
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);

            // Log to communications table
            fn_communications_log($companyId, [
                'channel' => 'whatsapp',
                'direction' => 'outbound',
                'to_address' => $to,
                'message' => $message,
                'whatsapp_message_id' => $result['messages'][0]['id'] ?? null,
                'status' => 'sent'
            ]);

            return $result;
        }

        error_log("WhatsApp send error: " . $response);
        return false;

    } catch (Exception $e) {
        error_log("WhatsApp exception: " . $e->getMessage());
        return false;
    }
}

/**
 * Send WhatsApp template message
 *
 * @param string $to Phone number
 * @param string $templateName Template name
 * @param array $parameters Template parameters
 * @param int $companyId Company ID
 * @return array|false
 */
function fn_whatsapp_send_template($to, $templateName, $parameters, $companyId) {
    global $config;

    if (!isset($config['whatsapp'])) {
        return false;
    }

    try {
        $url = "https://graph.facebook.com/v17.0/{$config['whatsapp']['phone_number_id']}/messages";

        $data = [
            'messaging_product' => 'whatsapp',
            'to' => $to,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => 'en'],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => $parameters
                    ]
                ]
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $config['whatsapp']['access_token'],
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            return json_decode($response, true);
        }

        return false;

    } catch (Exception $e) {
        error_log("WhatsApp template error: " . $e->getMessage());
        return false;
    }
}

/**
 * ====================
 * SMS (TWILIO) INTEGRATION
 * ====================
 */

/**
 * Send SMS via Twilio
 *
 * @param string $to Phone number
 * @param string $message Message text
 * @param int $companyId Company ID
 * @return array|false
 */
function fn_sms_send($to, $message, $companyId) {
    global $config;

    if (!isset($config['twilio'])) {
        return false;
    }

    try {
        $url = "https://api.twilio.com/2010-04-01/Accounts/{$config['twilio']['account_sid']}/Messages.json";

        $data = [
            'From' => $config['twilio']['from_number'],
            'To' => $to,
            'Body' => $message
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, $config['twilio']['account_sid'] . ':' . $config['twilio']['auth_token']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 201) {
            $result = json_decode($response, true);

            // Log to communications table
            fn_communications_log($companyId, [
                'channel' => 'sms',
                'direction' => 'outbound',
                'to_address' => $to,
                'message' => $message,
                'twilio_message_sid' => $result['sid'] ?? null,
                'status' => 'sent'
            ]);

            return $result;
        }

        error_log("SMS send error: " . $response);
        return false;

    } catch (Exception $e) {
        error_log("SMS exception: " . $e->getMessage());
        return false;
    }
}

/**
 * ====================
 * EMAIL (POSTMARK) INTEGRATION
 * ====================
 */

/**
 * Send email via Postmark
 *
 * @param string $to Recipient email
 * @param string $subject Subject
 * @param string $htmlBody HTML body
 * @param string $textBody Plain text body
 * @param int $companyId Company ID
 * @return array|false
 */
function fn_email_send_postmark($to, $subject, $htmlBody, $textBody = '', $companyId = null) {
    global $config;

    if (!isset($config['postmarkapp'])) {
        return false;
    }

    try {
        $url = "https://api.postmarkapp.com/email";

        $data = [
            'From' => $config['postmarkapp']['from_email'],
            'To' => $to,
            'Subject' => $subject,
            'HtmlBody' => $htmlBody,
            'TextBody' => $textBody ?: strip_tags($htmlBody),
            'MessageStream' => 'outbound'
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
            'X-Postmark-Server-Token: ' . $config['postmarkapp']['server_api']
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            $result = json_decode($response, true);

            // Log to communications if company ID provided
            if ($companyId) {
                fn_communications_log($companyId, [
                    'channel' => 'email',
                    'direction' => 'outbound',
                    'to_address' => $to,
                    'subject' => $subject,
                    'message' => $htmlBody,
                    'postmark_message_id' => $result['MessageID'] ?? null,
                    'status' => 'sent'
                ]);
            }

            return $result;
        }

        error_log("Postmark send error: " . $response);
        return false;

    } catch (Exception $e) {
        error_log("Postmark exception: " . $e->getMessage());
        return false;
    }
}

/**
 * Send email with template
 *
 * @param string $templateSlug Template slug
 * @param string $to Recipient email
 * @param array $mergeData Data to merge
 * @param int $companyId Company ID
 * @return array|false
 */
function fn_email_send_with_template($templateSlug, $to, $mergeData, $companyId) {
    $template = fn_email_template_get_by_slug($templateSlug, $companyId);

    if (!$template) {
        return false;
    }

    // Merge data into template
    $subject = fn_email_template_merge($template['subject'], $mergeData);
    $htmlBody = fn_email_template_merge($template['body_html'], $mergeData);
    $textBody = fn_email_template_merge($template['body_text'], $mergeData);

    return fn_email_send_postmark($to, $subject, $htmlBody, $textBody, $companyId);
}

/**
 * Merge data into template
 *
 * @param string $template Template string
 * @param array $data Merge data
 * @return string Merged string
 */
function fn_email_template_merge($template, $data) {
    foreach ($data as $key => $value) {
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }
    return $template;
}

/**
 * ====================
 * EMAIL TEMPLATE MANAGEMENT
 * ====================
 */

/**
 * Get email template by slug
 *
 * @param string $slug Template slug
 * @param int $companyId Company ID (null for system templates)
 * @return array|null Template data
 */
function fn_email_template_get_by_slug($slug, $companyId = null) {
    $query = "SELECT * FROM email_templates WHERE template_slug = ? AND (company_id = ? OR company_id IS NULL) ORDER BY company_id DESC LIMIT 1";
    return fn_core_database_row($query, [$slug, $companyId]);
}

/**
 * Get email template by ID
 *
 * @param int $templateId Template ID
 * @param int $companyId Company ID
 * @return array|false Template data
 */
function fn_email_template_get($templateId, $companyId) {
    $query = "SELECT * FROM email_templates WHERE template_id = ? AND company_id = ?";
    return fn_core_database_row($query, [$templateId, $companyId]);
}

/**
 * Delete email template
 *
 * @param int $templateId Template ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_email_template_delete($templateId, $companyId) {
    $query = "DELETE FROM email_templates WHERE template_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$templateId, $companyId]);
}

/**
 * Get all email templates
 *
 * @param int $companyId Company ID
 * @param string $category Optional category filter
 * @return array Templates
 */
function fn_email_templates_get_all($companyId, $category = null) {
    $query = "SELECT * FROM email_templates WHERE (company_id = ? OR company_id IS NULL) AND is_active = 1";
    $params = [$companyId];

    if ($category) {
        $query .= " AND category = ?";
        $params[] = $category;
    }

    $query .= " ORDER BY template_name ASC";

    return fn_core_database_rows($query, $params);
}

/**
 * Create email template
 *
 * @param int $companyId Company ID
 * @param array $data Template data
 * @return int|false Template ID
 */
function fn_email_template_create($companyId, $data) {
    $query = "INSERT INTO email_templates (company_id, template_name, template_slug, category, subject, body_html, body_text, merge_tags, is_active)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    return fn_core_insert_row_no_redirect($query, [
        $companyId,
        $data['template_name'],
        $data['template_slug'],
        $data['category'] ?? 'transactional',
        $data['subject'],
        $data['body_html'],
        $data['body_text'] ?? '',
        json_encode($data['merge_tags'] ?? []),
        $data['is_active'] ?? 1
    ]);
}

/**
 * Update email template
 *
 * @param int $templateId Template ID
 * @param int $companyId Company ID
 * @param array $data Template data
 * @return bool Success
 */
function fn_email_template_update($templateId, $companyId, $data) {
    $query = "UPDATE email_templates SET
              template_name = ?, subject = ?, body_html = ?, body_text = ?,
              category = ?, is_active = ?
              WHERE template_id = ? AND company_id = ?";

    return fn_core_edit_row_no_redirect($query, [
        $data['template_name'],
        $data['subject'],
        $data['body_html'],
        $data['body_text'] ?? '',
        $data['category'] ?? 'transactional',
        $data['is_active'] ?? 1,
        $templateId,
        $companyId
    ]);
}

/**
 * ====================
 * UNIFIED INBOX
 * ====================
 */

/**
 * Log communication to unified inbox
 *
 * @param int $companyId Company ID
 * @param array $data Communication data
 * @return int|false Communication ID
 */
function fn_communications_log($companyId, $data) {
    $query = "INSERT INTO communications (
        company_id, customer_id, lead_id, channel, direction,
        from_address, to_address, subject, message, status,
        postmark_message_id, whatsapp_message_id, twilio_message_sid,
        sent_date, created_date
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

    return fn_core_insert_row_no_redirect($query, [
        $companyId,
        $data['customer_id'] ?? null,
        $data['lead_id'] ?? null,
        $data['channel'],
        $data['direction'],
        $data['from_address'] ?? '',
        $data['to_address'] ?? '',
        $data['subject'] ?? '',
        $data['message'],
        $data['status'] ?? 'open',
        $data['postmark_message_id'] ?? null,
        $data['whatsapp_message_id'] ?? null,
        $data['twilio_message_sid'] ?? null
    ]);
}

/**
 * Get all communications for unified inbox
 *
 * @param int $companyId Company ID
 * @param array $filters Filters
 * @return array Communications
 */
function fn_communications_get_all($companyId, $filters = []) {
    $query = "SELECT c.*, cust.first_name, cust.last_name, cust.email as customer_email
              FROM communications c
              LEFT JOIN customers cust ON c.customer_id = cust.customer_id
              WHERE c.company_id = ?";

    $params = [$companyId];

    if (!empty($filters['channel'])) {
        $query .= " AND c.channel = ?";
        $params[] = $filters['channel'];
    }

    if (!empty($filters['status'])) {
        $query .= " AND c.status = ?";
        $params[] = $filters['status'];
    }

    $query .= " ORDER BY c.created_date DESC LIMIT 100";

    return fn_core_database_rows($query, $params);
}

/**
 * ====================
 * AUTOMATED WORKFLOWS
 * ====================
 */

/**
 * Send enquiry notification
 *
 * @param int $enquiryId Enquiry ID
 * @return bool Success
 */
function fn_workflow_enquiry_notification($enquiryId) {
    $query = "SELECT e.*, c.company_name, c.company_email
              FROM enquiries e
              JOIN core_company c ON e.company_id = c.company_id
              WHERE e.enquiry_id = ?";

    $enquiry = fn_core_database_row($query, [$enquiryId]);

    if (!$enquiry) {
        return false;
    }

    $subject = "New Enquiry from " . $enquiry['first_name'] . ' ' . $enquiry['last_name'];
    $body = "You have received a new enquiry:\n\n";
    $body .= "Name: " . $enquiry['first_name'] . ' ' . $enquiry['last_name'] . "\n";
    $body .= "Email: " . $enquiry['email'] . "\n";
    $body .= "Phone: " . $enquiry['phone'] . "\n";
    $body .= "Message: " . $enquiry['message'] . "\n";

    return fn_email_send_postmark($enquiry['company_email'], $subject, nl2br($body), $body, $enquiry['company_id']);
}

/**
 * Send appointment reminder
 *
 * @param int $appointmentId Appointment ID
 * @param string $method Reminder method (email, sms, whatsapp)
 * @return bool Success
 */
function fn_workflow_appointment_reminder($appointmentId, $method = 'email') {
    // Implementation for appointment reminders
    return true;
}

/**
 * Send abandoned enquiry follow-up (cron job)
 * Finds enquiries older than 24h with no response
 */
function fn_workflow_abandoned_enquiry_followup() {
    $query = "SELECT e.*, c.company_email
              FROM enquiries e
              JOIN core_company c ON e.company_id = c.company_id
              WHERE e.status = 'new'
              AND e.created_date < DATE_SUB(NOW(), INTERVAL 24 HOUR)
              AND e.created_date > DATE_SUB(NOW(), INTERVAL 48 HOUR)";

    $enquiries = fn_core_database_rows($query, []);

    foreach ($enquiries as $enquiry) {
        // Send follow-up email
        $subject = "Following up on your enquiry";
        $body = "Hi " . $enquiry['first_name'] . ",\n\n";
        $body .= "We noticed you enquired about a vehicle yesterday. We'd love to help you.\n\n";
        $body .= "Please let us know if you have any questions.\n\n";
        $body .= "Best regards,\n" . $enquiry['company_name'];

        fn_email_send_postmark($enquiry['email'], $subject, nl2br($body), $body, $enquiry['company_id']);
    }

    return count($enquiries);
}
