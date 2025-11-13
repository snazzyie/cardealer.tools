<?php
/**
 * Core Router Functions
 *
 * Simple array-based routing system
 * Maps URL paths to controller files
 */

/**
 * Get all application routes
 *
 * @return array Routes array
 */
function fn_core_get_routes() {
    return [
        // Public Routes
        '/' => 'public/home/index',
        '/cars' => 'public/vehicles/search',
        '/cars/view' => 'public/vehicles/view',
        '/about' => 'public/about/index',
        '/contact' => 'public/contact/index',
        '/finance' => 'public/finance/index',
        '/enquiry/submit' => 'public/enquiry/submit',
        '/test-drive' => 'public/enquiry/test-drive',
        '/trade-in' => 'public/trade-in/index',
        '/stock-alert' => 'public/stock-alert/register',

        // Authentication
        '/login' => 'login/login',
        '/register' => 'login/register',
        '/logout' => 'login/logout',
        '/forgot-password' => 'login/forgot-password',
        '/reset-password' => 'login/reset-password',

        // Dealer Dashboard
        '/dash' => 'dash/index',

        // Vehicles
        '/vehicles' => 'vehicles/vehicles',
        '/vehicles/new' => 'vehicles/vehicles-new',
        '/vehicles/edit' => 'vehicles/vehicles-edit',
        '/vehicles/delete' => 'vehicles/vehicles-delete',
        '/vehicles/import' => 'vehicles/vehicles-import',
        '/vehicles/images' => 'vehicles/vehicles-images',
        '/vehicles/features' => 'vehicles/vehicles-features',

        // CRM & Leads
        '/crm' => 'crm/pipeline',
        '/crm/leads' => 'crm/leads',
        '/crm/leads/new' => 'crm/leads-new',
        '/crm/leads/edit' => 'crm/leads-edit',
        '/crm/leads/view' => 'crm/leads-view',
        '/crm/tasks' => 'crm/tasks',
        '/crm/activities' => 'crm/activities',

        // Calendar & Appointments
        '/calendar' => 'calendar/calendar',
        '/calendar/new' => 'calendar/appointment-new',
        '/calendar/edit' => 'calendar/appointment-edit',
        '/calendar/callback' => 'calendar/google-callback',

        // Enquiries
        '/enquiries' => 'enquiries/enquiries',
        '/enquiries/view' => 'enquiries/enquiries-view',
        '/enquiries/update-status' => 'enquiries/enquiries-update-status',

        // Finance Applications
        '/finance-applications' => 'finance/applications',
        '/finance-applications/view' => 'finance/applications-view',

        // Invoices
        '/invoices' => 'invoices/invoices',
        '/invoices/sales' => 'invoices/sales-invoices',
        '/invoices/sales/new' => 'invoices/sales-new',
        '/invoices/sales/view' => 'invoices/sales-view',
        '/invoices/service' => 'invoices/service-invoices',
        '/invoices/service/new' => 'invoices/service-new',
        '/invoices/service/view' => 'invoices/service-view',

        // Deposits
        '/deposits' => 'deposits/deposits',
        '/deposits/new' => 'deposits/deposit-new',
        '/deposits/refund' => 'deposits/deposit-refund',

        // Communications
        '/inbox' => 'inbox/inbox',
        '/inbox/view' => 'inbox/view',
        '/email-templates' => 'communications/templates',

        // Customers
        '/customers' => 'customers/customers',
        '/customers/view' => 'customers/customers-view',

        // Website Settings
        '/website' => 'website/settings',
        '/website/pages' => 'website/pages',
        '/website/menus' => 'website/menus',

        // Reports
        '/reports' => 'reports/reports',
        '/reports/sales' => 'reports/sales',
        '/reports/enquiries' => 'reports/enquiries',
        '/reports/analytics' => 'reports/analytics',

        // Users
        '/users' => 'users/users',
        '/users/new' => 'users/users-new',
        '/users/edit' => 'users/users-edit',
        '/users/delete' => 'users/users-delete',

        // Company Settings
        '/company' => 'company/company',
        '/company/edit' => 'company/company-edit',
        '/company/branding' => 'company/branding',

        // Subscriptions
        '/subscriptions' => 'subscriptions/manage',
        '/subscriptions/plans' => 'subscriptions/plans',
        '/subscriptions/manage' => 'subscriptions/manage',
        '/subscriptions/success' => 'subscriptions/success',
        '/subscriptions/webhook' => 'subscriptions/webhook',

        // Super Admin
        '/super-admin' => 'super-admin/dashboard',
        '/super-admin/dealers' => 'super-admin/dealers',
        '/super-admin/dealers/view' => 'super-admin/dealers-view',
        '/super-admin/settings' => 'super-admin/settings',

        // Webhooks
        '/webhook/stripe' => 'webhook/stripe',
        '/webhook/postmark' => 'webhook/postmark',
        '/webhook/whatsapp' => 'webhook/whatsapp',
        '/webhook/twilio' => 'webhook/twilio',

        // Cron Jobs
        '/cron/subscriptions' => 'cron/subscriptions',
        '/cron/stock-alerts' => 'cron/stock-alerts',
        '/cron/appointments' => 'cron/appointments',
        '/cron/reminders' => 'cron/reminders',

        // Error Pages
        '/error/403' => 'errors/403',
        '/error/404' => 'errors/404',
        '/error/500' => 'errors/500',
    ];
}

/**
 * Route incoming request to appropriate controller
 *
 * @param string $uri Current URI path
 */
function fn_core_route($uri) {
    $routes = fn_core_get_routes();

    // Check if exact route exists
    if (array_key_exists($uri, $routes)) {
        $controller = BASE_PATH . 'app/controllers/' . $routes[$uri] . '.php';

        if (file_exists($controller)) {
            require $controller;
            return;
        }
    }

    // 404 - Route not found
    header("HTTP/1.0 404 Not Found");
    if (file_exists(BASE_PATH . 'app/controllers/errors/404.php')) {
        require BASE_PATH . 'app/controllers/errors/404.php';
    } else {
        echo '<h1>404 - Page Not Found</h1>';
        echo '<p>The page you are looking for does not exist.</p>';
        echo '<p><a href="/">Return to homepage</a></p>';
    }
}

/**
 * Generate URL from route name
 *
 * @param string $path Route path
 * @param array $params Query parameters
 * @return string Full URL
 */
function fn_core_url($path = '', $params = []) {
    $config = require BASE_PATH . 'config.php';
    $url = $config['app_url'] . $path;

    if (!empty($params)) {
        $url .= '?' . http_build_query($params);
    }

    return $url;
}

/**
 * Redirect to a specific route
 *
 * @param string $path Route path
 * @param int $code HTTP status code
 */
function fn_core_redirect($path, $code = 302) {
    header("Location: $path", true, $code);
    exit;
}

/**
 * Check if current route matches
 *
 * @param string $route Route to check
 * @return bool True if matches
 */
function fn_core_is_current_route($route) {
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    return $uri === $route;
}
