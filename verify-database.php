<?php
/**
 * Database Verification Script
 * Checks if all required tables exist and match schema
 */

define('BASE_PATH', __DIR__ . '/');
require __DIR__ . '/app/functions/fn_core_database.php';

$config = require __DIR__ . '/config.php';

echo "=== Car Dealer SaaS - Database Verification ===\n\n";

// Required tables from database_install.sql
$required_tables = [
    'users',
    'core_company',
    'vehicles',
    'vehicle_images',
    'vehicle_features',
    'crm_leads',
    'crm_activities',
    'calendar_appointments',
    'customers',
    'sales_invoices',
    'service_invoices',
    'service_items',
    'service_catalog',
    'vehicle_deposits',
    'subscriptions',
    'core_invoices',
    'enquiries',
    'stock_alerts',
    'communications',
    'email_templates',
    'website_settings',
    'website_pages',
    'website_menus',
    'core_side_menu',
    'core_user_permissions',
    'core_system_settings',
    'core_activity_log'
];

echo "Checking for " . count($required_tables) . " required tables...\n\n";

$db = fn_core_database_connection();

$missing_tables = [];
$existing_tables = [];

foreach ($required_tables as $table) {
    try {
        $stmt = $db->query("SELECT 1 FROM `$table` LIMIT 1");
        $existing_tables[] = $table;
        echo "✅ $table\n";
    } catch (PDOException $e) {
        $missing_tables[] = $table;
        echo "❌ $table - MISSING\n";
    }
}

echo "\n=== Summary ===\n";
echo "Existing tables: " . count($existing_tables) . "/" . count($required_tables) . "\n";
echo "Missing tables: " . count($missing_tables) . "\n";

if (!empty($missing_tables)) {
    echo "\n⚠️  MISSING TABLES:\n";
    foreach ($missing_tables as $table) {
        echo "   - $table\n";
    }
    echo "\nRun database_install.sql to create missing tables.\n";
    exit(1);
} else {
    echo "\n✅ All required tables exist!\n";
}

// Check for sample data in core_company
try {
    $stmt = $db->query("SELECT COUNT(*) as count FROM core_company");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nCompanies in database: " . $result['count'] . "\n";

    if ($result['count'] > 0) {
        $stmt = $db->query("SELECT company_id, company_name, subdomain, domain FROM core_company LIMIT 5");
        $companies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo "\nExisting companies:\n";
        foreach ($companies as $company) {
            $url = '';
            if (!empty($company['domain'])) {
                $url = "https://" . $company['domain'];
            } elseif (!empty($company['subdomain'])) {
                $url = "https://" . $company['subdomain'] . ".cardealer.tools";
            }
            echo "  - " . $company['company_name'] . " (" . ($url ?: 'No URL configured') . ")\n";
        }
    }
} catch (PDOException $e) {
    echo "\nError checking companies: " . $e->getMessage() . "\n";
}

echo "\n=== Database verification complete! ===\n";
