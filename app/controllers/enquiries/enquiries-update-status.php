<?php
/**
 * Update Enquiry Status Controller
 * AJAX endpoint for quick status updates
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];

if (!$company_id) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'No company access']);
    exit;
}

// This is an AJAX endpoint
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$enquiry_id = intval($_POST['enquiry_id'] ?? 0);
$new_status = $_POST['status'] ?? '';

if (empty($enquiry_id) || empty($new_status)) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit;
}

// Validate status
$valid_statuses = ['new', 'contacted', 'interested', 'not_interested', 'converted', 'spam'];
if (!in_array($new_status, $valid_statuses)) {
    echo json_encode(['success' => false, 'error' => 'Invalid status']);
    exit;
}

// Verify enquiry belongs to company
$enquiry = fn_core_database_row(
    "SELECT enquiry_id FROM enquiries WHERE enquiry_id = ? AND company_id = ?",
    [$enquiry_id, $company_id]
);

if (!$enquiry) {
    echo json_encode(['success' => false, 'error' => 'Enquiry not found']);
    exit;
}

// Update status
$result = fn_core_database_query(
    "UPDATE enquiries SET status = ?, last_contact = NOW() WHERE enquiry_id = ?",
    [$new_status, $enquiry_id]
);

if ($result) {
    // Add activity note
    fn_core_database_query(
        "INSERT INTO enquiry_notes (enquiry_id, user_id, note, created_date) VALUES (?, ?, ?, NOW())",
        [$enquiry_id, $user_data['user_id'], "Status changed to: {$new_status}"]
    );

    echo json_encode([
        'success' => true,
        'message' => 'Status updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Failed to update status'
    ]);
}
