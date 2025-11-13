<?php
/**
 * Public Homepage Controller
 */

// If logged in, redirect to dashboard
if (isset($_SESSION['user_type']) && $_SESSION['user_type'] >= 1) {
    header("Location: /dash");
    exit;
}

$page_title = 'Welcome to Car Dealer SaaS';

require BASE_PATH . 'views/public/home/index.php';
