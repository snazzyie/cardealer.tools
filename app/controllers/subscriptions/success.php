<?php
/**
 * Subscription Success Page
 */

fn_require_login(2);

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

$session_id = $_GET['session_id'] ?? '';

$page_header = [
    'title' => 'Subscription Activated',
    'subtitle' => 'Welcome to your new plan!'
];

require BASE_PATH . 'views/subscriptions/success.php';
