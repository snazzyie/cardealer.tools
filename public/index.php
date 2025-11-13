<?php
/**
 * Front Controller - Entry Point
 *
 * All requests are routed through this file
 */

// Define base path constant
const BASE_PATH = __DIR__ . '/../';

// Start session
session_start();

// Load configuration
$config = require BASE_PATH . 'config.php';

// Set timezone
date_default_timezone_set($config['timezone']);

// Error reporting (based on debug settings)
if ($config['debug_display']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Autoload Composer dependencies
if (file_exists(BASE_PATH . 'vendor/autoload.php')) {
    require BASE_PATH . 'vendor/autoload.php';
}

// Load core functions
require BASE_PATH . 'app/functions/fn_core_database.php';
require BASE_PATH . 'app/functions/fn_core_session.php';
require BASE_PATH . 'app/functions/fn_core_router.php';
require BASE_PATH . 'app/functions/fn_core_settings.php';
require BASE_PATH . 'app/functions/fn_core_email.php';
require BASE_PATH . 'app/functions/fn_core_stripe.php';
require BASE_PATH . 'app/functions/fn_company.php';
require BASE_PATH . 'app/functions/fn_vehicles.php';
require BASE_PATH . 'app/functions/fn_crm.php';
require BASE_PATH . 'app/functions/fn_calendar.php';
require BASE_PATH . 'app/functions/fn_invoices.php';
require BASE_PATH . 'app/functions/fn_service.php';
require BASE_PATH . 'app/functions/fn_pdf.php';
require BASE_PATH . 'app/functions/fn_stock_alerts.php';
require BASE_PATH . 'app/functions/fn_subscriptions.php';
require BASE_PATH . 'app/functions/fn_communications.php';
require BASE_PATH . 'app/functions/fn_enquiries.php';
require BASE_PATH . 'app/functions/fn_customers.php';
require BASE_PATH . 'app/functions/fn_users.php';
require BASE_PATH . 'app/functions/fn_deposits.php';
require BASE_PATH . 'app/functions/fn_website.php';
require BASE_PATH . 'app/functions/fn_reports.php';

// Initialize session
fn_core_session_initialise_session();

// Detect subdomain and determine if this is a public dealer site
$host = $_SERVER['HTTP_HOST'] ?? '';
$subdomain = null;
$is_public_site = false;
$public_company = null;

// Parse subdomain
if (preg_match('/^([a-z0-9\-]+)\.' . preg_quote($config['app_domain'], '/') . '$/i', $host, $matches)) {
    $subdomain = $matches[1];

    // Check if this is the admin subdomain (app.cardealer.tools)
    if ($subdomain === 'app' || $subdomain === 'admin') {
        // This is the admin dashboard
        $is_public_site = false;
    } else {
        // This is a dealer's public website
        $is_public_site = true;

        // Load company by subdomain
        $public_company = fn_company_get_by_subdomain($subdomain);

        if (!$public_company) {
            // Subdomain doesn't exist
            http_response_code(404);
            echo '<h1>404 - Dealership Not Found</h1>';
            echo '<p>The dealership you are looking for does not exist.</p>';
            echo '<p>Subdomain: ' . htmlspecialchars($subdomain) . '</p>';
            exit;
        }

        // Store company context in global variable for public controllers
        define('PUBLIC_SITE_COMPANY_ID', $public_company['company_id']);
        define('PUBLIC_SITE_COMPANY', $public_company);
    }
}

// Get current URI and route the request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route the request
if ($is_public_site && $public_company) {
    // Route to public dealer website
    fn_core_route_public($uri, $public_company);
} else {
    // Route to admin dashboard
    fn_core_route($uri);
}
