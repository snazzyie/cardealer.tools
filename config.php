<?php
/**
 * Car Dealer SaaS - Main Configuration File
 *
 * This file contains all configuration settings for the application
 * including database, email, payment, and integration settings.
 */

return [
    // Application Domain
    'app_domain' => 'cardealer.tools',
    'app_name' => 'Car Dealer SaaS',
    'app_url' => 'https://cardealer.tools',

    // Database Configuration
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'dbname' => 'cardealer_saas',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8mb4'
    ],

    // Debug Settings (set to false in production)
    'debug_display' => true,
    'debug_session' => false,
    'debug_server' => false,

    // Postmark Email Configuration
    'postmarkapp' => [
        'server_api' => 'YOUR_POSTMARK_SERVER_API_TOKEN',
        'email_domain' => 'cardealer.tools',
        'from_email' => 'noreply@cardealer.tools',
        'from_name' => 'Car Dealer SaaS'
    ],

    // AWS S3 Configuration (Optional - for image storage)
    'aws_s3' => [
        'access_key' => '',
        'secret_key' => '',
        'region' => 'eu-west-1',
        'bucket' => 'cardealer-saas-uploads',
        'url' => 'https://s3.eu-west-1.amazonaws.com/cardealer-saas-uploads'
    ],

    // Stripe Payment Configuration
    'stripe' => [
        'publishable_key' => 'pk_test_YOUR_KEY_HERE',
        'secret_key' => 'sk_test_YOUR_KEY_HERE',
        'webhook_secret' => 'whsec_YOUR_WEBHOOK_SECRET'
    ],

    // Twilio SMS Configuration
    'twilio' => [
        'account_sid' => 'YOUR_TWILIO_ACCOUNT_SID',
        'auth_token' => 'YOUR_TWILIO_AUTH_TOKEN',
        'from_number' => '+353XXXXXXXXX'
    ],

    // WhatsApp Business API Configuration
    'whatsapp' => [
        'access_token' => 'YOUR_WHATSAPP_ACCESS_TOKEN',
        'phone_number_id' => 'YOUR_PHONE_NUMBER_ID',
        'business_account_id' => 'YOUR_BUSINESS_ACCOUNT_ID'
    ],

    // Google Calendar API Configuration
    'google_calendar' => [
        'client_id' => 'YOUR_GOOGLE_CLIENT_ID',
        'client_secret' => 'YOUR_GOOGLE_CLIENT_SECRET',
        'redirect_uri' => 'https://cardealer.tools/calendar/callback'
    ],

    // Google Analytics
    'google_analytics' => [
        'tracking_id' => 'UA-XXXXXXXXX-X'
    ],

    // File Upload Settings
    'uploads' => [
        'max_file_size' => 10485760, // 10MB in bytes
        'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'allowed_document_types' => ['pdf', 'doc', 'docx'],
        'vehicle_images_path' => __DIR__ . '/storage/uploads/vehicles/',
        'max_images_per_vehicle' => 60
    ],

    // Session Settings
    'session' => [
        'timeout' => 3600, // 1 hour in seconds
        'name' => 'cardealer_session',
        'secure' => false, // Set to true in production with HTTPS
        'httponly' => true
    ],

    // Pagination
    'pagination' => [
        'per_page' => 20,
        'max_per_page' => 100
    ],

    // Currency Settings
    'currency' => [
        'default' => 'EUR',
        'symbol' => '€',
        'supported' => ['EUR', 'GBP', 'USD']
    ],

    // VAT Settings (Ireland)
    'vat' => [
        'default_rate' => 23.00,
        'rates' => [
            'standard' => 23.00,
            'reduced' => 13.50,
            'zero' => 0.00
        ]
    ],

    // Timezone
    'timezone' => 'Europe/Dublin',

    // Subscription Plans (will be managed in database)
    'trial_period_days' => 14,

    // Security
    'password_min_length' => 8,
    'login_max_attempts' => 5,
    'login_lockout_duration' => 900, // 15 minutes

    // Cron Job Secret (for securing cron endpoints)
    'cron_secret' => 'CHANGE_THIS_TO_RANDOM_STRING',

    // Feature Flags (can be overridden per company)
    'features' => [
        'enable_whatsapp' => true,
        'enable_sms' => true,
        'enable_google_calendar' => true,
        'enable_deposits' => true,
        'enable_service_invoicing' => true,
        'enable_crm' => true
    ]
];
