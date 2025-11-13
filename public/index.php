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

// Initialize session
fn_core_session_initialise_session();

// Get current URI and route the request
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Route the request
fn_core_route($uri);
