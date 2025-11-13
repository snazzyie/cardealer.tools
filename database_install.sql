-- Car Dealer SaaS - Database Installation Script
-- Run this script to create all necessary database tables

-- Users & Authentication
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(50),
    user_type TINYINT DEFAULT 1,
    user_role ENUM('owner', 'manager', 'sales', 'receptionist') DEFAULT 'sales',
    email_verified TINYINT DEFAULT 0,
    email_activation_code VARCHAR(100),
    last_login DATETIME,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dealer Companies
CREATE TABLE IF NOT EXISTS core_company (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(255) NOT NULL,
    trading_name VARCHAR(255),
    company_email VARCHAR(255),
    company_phone VARCHAR(50),
    company_address TEXT,
    city VARCHAR(100),
    county VARCHAR(100),
    postcode VARCHAR(20),
    country VARCHAR(100) DEFAULT 'Ireland',
    vat_number VARCHAR(50),
    company_registration VARCHAR(50),
    plan_id INT,
    domain VARCHAR(255),
    subdomain VARCHAR(100),
    logo_url VARCHAR(500),
    favicon_url VARCHAR(500),
    primary_color VARCHAR(10) DEFAULT '#007bff',
    secondary_color VARCHAR(10) DEFAULT '#6c757d',
    opening_hours JSON,
    social_facebook VARCHAR(255),
    social_instagram VARCHAR(255),
    social_twitter VARCHAR(255),
    social_linkedin VARCHAR(255),
    google_analytics_id VARCHAR(50),
    google_tag_manager_id VARCHAR(50),
    status ENUM('active', 'suspended', 'trial') DEFAULT 'trial',
    trial_ends_at DATE,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_domain (domain),
    INDEX idx_subdomain (subdomain)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vehicles
CREATE TABLE IF NOT EXISTS vehicles (
    vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    make VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    registration VARCHAR(50),
    vin VARCHAR(100),
    price DECIMAL(10,2) NOT NULL,
    was_price DECIMAL(10,2),
    trade_in_value DECIMAL(10,2),
    vat_status ENUM('vat_inclusive', 'vat_exclusive', 'vat_margin') DEFAULT 'vat_inclusive',
    vat_amount DECIMAL(10,2),
    mileage INT,
    mileage_unit ENUM('km', 'miles') DEFAULT 'km',
    fuel_type ENUM('petrol', 'diesel', 'electric', 'hybrid', 'plug-in-hybrid', 'lpg', 'cng') NOT NULL,
    transmission ENUM('manual', 'automatic', 'semi-automatic') NOT NULL,
    body_type ENUM('saloon', 'suv', 'hatchback', 'coupe', 'estate', 'van', 'mpv', 'pickup', 'convertible') NOT NULL,
    doors TINYINT,
    seats TINYINT,
    exterior_color VARCHAR(50),
    interior_color VARCHAR(50),
    engine_size INT,
    engine_size_unit ENUM('cc', 'L') DEFAULT 'cc',
    power_hp INT,
    power_kw INT,
    co2_emissions INT,
    engine_code VARCHAR(50),
    drivetrain ENUM('fwd', 'rwd', 'awd', '4wd'),
    previous_owners TINYINT,
    service_history ENUM('full', 'partial', 'none', 'unknown'),
    nct_expiry DATE,
    mot_expiry DATE,
    description TEXT,
    features JSON,
    status ENUM('available', 'reserved', 'sold', 'coming-soon') DEFAULT 'available',
    is_featured TINYINT DEFAULT 0,
    is_premium TINYINT DEFAULT 0,
    slug VARCHAR(255) UNIQUE,
    meta_title VARCHAR(255),
    meta_description TEXT,
    date_added DATETIME DEFAULT CURRENT_TIMESTAMP,
    date_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    date_sold DATETIME,
    INDEX idx_company (company_id),
    INDEX idx_make_model (make, model),
    INDEX idx_price (price),
    INDEX idx_year (year),
    INDEX idx_status (status),
    INDEX idx_slug (slug),
    FULLTEXT idx_search (make, model, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vehicle Images
CREATE TABLE IF NOT EXISTS vehicle_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    image_order INT DEFAULT 0,
    is_primary TINYINT DEFAULT 0,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE CASCADE,
    INDEX idx_vehicle (vehicle_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vehicle Features Library
CREATE TABLE IF NOT EXISTS vehicle_features (
    feature_id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('safety', 'entertainment', 'comfort', 'technology', 'exterior', 'interior') NOT NULL,
    feature_name VARCHAR(100) NOT NULL,
    feature_icon VARCHAR(50),
    UNIQUE KEY (category, feature_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Enquiries
CREATE TABLE IF NOT EXISTS enquiries (
    enquiry_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    vehicle_id INT,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    enquiry_type ENUM('general', 'vehicle', 'test-drive', 'finance', 'trade-in', 'service') NOT NULL,
    message TEXT,
    preferred_contact_method ENUM('email', 'phone', 'whatsapp'),
    preferred_contact_time VARCHAR(100),
    test_drive_date DATE,
    test_drive_time TIME,
    trade_in_make VARCHAR(100),
    trade_in_model VARCHAR(100),
    trade_in_year INT,
    trade_in_mileage INT,
    trade_in_registration VARCHAR(50),
    status ENUM('new', 'contacted', 'qualified', 'test-drive-booked', 'negotiating', 'won', 'lost') DEFAULT 'new',
    assigned_to INT,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    notes TEXT,
    next_followup_date DATE,
    source VARCHAR(100),
    ip_address VARCHAR(50),
    user_agent TEXT,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE SET NULL,
    INDEX idx_company (company_id),
    INDEX idx_status (status),
    INDEX idx_created_date (created_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Customers
CREATE TABLE IF NOT EXISTS customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    city VARCHAR(100),
    county VARCHAR(100),
    postcode VARCHAR(20),
    date_of_birth DATE,
    customer_type ENUM('buyer', 'seller', 'service', 'enquiry') DEFAULT 'enquiry',
    source VARCHAR(100),
    opt_in_email TINYINT DEFAULT 1,
    opt_in_sms TINYINT DEFAULT 0,
    opt_in_phone TINYINT DEFAULT 1,
    gdpr_consent TINYINT DEFAULT 0,
    gdpr_consent_date DATETIME,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    INDEX idx_email (email),
    UNIQUE KEY unique_company_email (company_id, email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Leads
CREATE TABLE IF NOT EXISTS crm_leads (
    lead_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT,
    vehicle_id INT,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    whatsapp_number VARCHAR(50),
    status ENUM('new', 'contacted', 'qualified', 'test-drive-booked', 'negotiating', 'won', 'lost') DEFAULT 'new',
    lead_source VARCHAR(100),
    lead_score INT DEFAULT 0,
    assigned_to INT,
    assigned_date DATETIME,
    interested_vehicle_type VARCHAR(100),
    budget_min DECIMAL(10,2),
    budget_max DECIMAL(10,2),
    timeframe VARCHAR(50),
    trade_in_interest TINYINT DEFAULT 0,
    finance_interest TINYINT DEFAULT 0,
    first_contact_date DATETIME,
    last_contact_date DATETIME,
    next_followup_date DATETIME,
    won_date DATETIME,
    lost_date DATETIME,
    lost_reason VARCHAR(255),
    tags JSON,
    custom_fields JSON,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE SET NULL,
    INDEX idx_company (company_id),
    INDEX idx_status (status),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_lead_score (lead_score)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Activities
CREATE TABLE IF NOT EXISTS crm_activities (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    lead_id INT,
    customer_id INT,
    user_id INT NOT NULL,
    activity_type ENUM('call', 'email', 'whatsapp', 'sms', 'meeting', 'note', 'task') NOT NULL,
    subject VARCHAR(255),
    description TEXT,
    activity_date DATETIME NOT NULL,
    duration_minutes INT,
    call_direction ENUM('inbound', 'outbound'),
    call_outcome ENUM('answered', 'voicemail', 'no-answer', 'busy'),
    email_opened TINYINT DEFAULT 0,
    email_clicked TINYINT DEFAULT 0,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lead_id) REFERENCES crm_leads(lead_id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    INDEX idx_company (company_id),
    INDEX idx_lead (lead_id),
    INDEX idx_customer (customer_id),
    INDEX idx_activity_date (activity_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CRM Tasks
CREATE TABLE IF NOT EXISTS crm_tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    lead_id INT,
    customer_id INT,
    assigned_to INT NOT NULL,
    created_by INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    task_type ENUM('call', 'email', 'meeting', 'follow-up', 'other') NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    due_date DATE NOT NULL,
    due_time TIME,
    completed_date DATETIME,
    status ENUM('pending', 'in-progress', 'completed', 'cancelled') DEFAULT 'pending',
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (lead_id) REFERENCES crm_leads(lead_id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    INDEX idx_company (company_id),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_due_date (due_date),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Calendar Appointments
CREATE TABLE IF NOT EXISTS calendar_appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT,
    lead_id INT,
    vehicle_id INT,
    assigned_to INT NOT NULL,
    appointment_type ENUM('test-drive', 'service', 'consultation', 'vehicle-viewing', 'other') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    timezone VARCHAR(50) DEFAULT 'Europe/Dublin',
    status ENUM('scheduled', 'confirmed', 'completed', 'no-show', 'cancelled') DEFAULT 'scheduled',
    google_calendar_event_id VARCHAR(255),
    google_calendar_synced TINYINT DEFAULT 0,
    last_synced DATETIME,
    reminder_sent TINYINT DEFAULT 0,
    reminder_sent_date DATETIME,
    notes TEXT,
    outcome TEXT,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL,
    FOREIGN KEY (lead_id) REFERENCES crm_leads(lead_id) ON DELETE SET NULL,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE SET NULL,
    INDEX idx_company (company_id),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_start_datetime (start_datetime),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sales Invoices
CREATE TABLE IF NOT EXISTS sales_invoices (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT NOT NULL,
    vehicle_id INT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255),
    customer_phone VARCHAR(50),
    customer_address TEXT,
    line_items JSON NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    vat_rate DECIMAL(5,2) DEFAULT 23.00,
    vat_amount DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    deposit_amount DECIMAL(10,2) DEFAULT 0,
    balance_due DECIMAL(10,2) NOT NULL,
    payment_status ENUM('unpaid', 'partial', 'paid', 'refunded') DEFAULT 'unpaid',
    payment_method VARCHAR(50),
    paid_amount DECIMAL(10,2) DEFAULT 0,
    paid_date DATETIME,
    invoice_pdf_url VARCHAR(500),
    notes TEXT,
    terms TEXT,
    status ENUM('draft', 'sent', 'paid', 'void') DEFAULT 'draft',
    created_by INT,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE SET NULL,
    INDEX idx_company (company_id),
    INDEX idx_customer (customer_id),
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_payment_status (payment_status),
    INDEX idx_invoice_date (invoice_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Service Invoices
CREATE TABLE IF NOT EXISTS service_invoices (
    service_invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT NOT NULL,
    vehicle_registration VARCHAR(50),
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255),
    customer_phone VARCHAR(50),
    service_type VARCHAR(100),
    service_date DATE,
    mileage INT,
    technician_id INT,
    line_items JSON NOT NULL,
    labor_total DECIMAL(10,2) DEFAULT 0,
    parts_total DECIMAL(10,2) DEFAULT 0,
    subtotal DECIMAL(10,2) NOT NULL,
    vat_rate DECIMAL(5,2) DEFAULT 23.00,
    vat_amount DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    payment_status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
    payment_method VARCHAR(50),
    paid_amount DECIMAL(10,2) DEFAULT 0,
    paid_date DATETIME,
    invoice_pdf_url VARCHAR(500),
    work_performed TEXT,
    notes TEXT,
    status ENUM('draft', 'sent', 'paid', 'void') DEFAULT 'draft',
    created_by INT,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    INDEX idx_company (company_id),
    INDEX idx_customer (customer_id),
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_payment_status (payment_status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Vehicle Deposits
CREATE TABLE IF NOT EXISTS vehicle_deposits (
    deposit_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    customer_id INT,
    lead_id INT,
    deposit_amount DECIMAL(10,2) NOT NULL,
    deposit_type ENUM('fixed', 'percentage') DEFAULT 'fixed',
    deposit_percentage DECIMAL(5,2),
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),
    stripe_payment_intent_id VARCHAR(255),
    stripe_charge_id VARCHAR(255),
    payment_method VARCHAR(50),
    status ENUM('pending', 'paid', 'refunded', 'applied', 'expired') DEFAULT 'pending',
    paid_date DATETIME,
    refund_date DATETIME,
    refund_amount DECIMAL(10,2),
    refund_reason TEXT,
    expires_at DATETIME,
    applied_to_invoice_id INT,
    applied_date DATETIME,
    receipt_pdf_url VARCHAR(500),
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id),
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE SET NULL,
    FOREIGN KEY (lead_id) REFERENCES crm_leads(lead_id) ON DELETE SET NULL,
    FOREIGN KEY (applied_to_invoice_id) REFERENCES sales_invoices(invoice_id) ON DELETE SET NULL,
    INDEX idx_company (company_id),
    INDEX idx_vehicle (vehicle_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoice Payments
CREATE TABLE IF NOT EXISTS invoice_payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    invoice_id INT,
    service_invoice_id INT,
    payment_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'bank-transfer', 'finance', 'stripe') NOT NULL,
    payment_date DATETIME NOT NULL,
    stripe_payment_intent_id VARCHAR(255),
    stripe_charge_id VARCHAR(255),
    reference_number VARCHAR(100),
    notes TEXT,
    created_by INT,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (invoice_id) REFERENCES sales_invoices(invoice_id) ON DELETE CASCADE,
    FOREIGN KEY (service_invoice_id) REFERENCES service_invoices(service_invoice_id) ON DELETE CASCADE,
    INDEX idx_company (company_id),
    INDEX idx_invoice (invoice_id),
    INDEX idx_service_invoice (service_invoice_id),
    INDEX idx_payment_date (payment_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Communications
CREATE TABLE IF NOT EXISTS communications (
    communication_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT,
    lead_id INT,
    channel ENUM('email', 'whatsapp', 'sms', 'internal-note') NOT NULL,
    direction ENUM('inbound', 'outbound') NOT NULL,
    from_address VARCHAR(255),
    to_address VARCHAR(255),
    subject VARCHAR(255),
    message TEXT NOT NULL,
    status ENUM('open', 'pending', 'resolved') DEFAULT 'open',
    assigned_to INT,
    sent_date DATETIME,
    delivered_date DATETIME,
    read_date DATETIME,
    replied TINYINT DEFAULT 0,
    postmark_message_id VARCHAR(255),
    whatsapp_message_id VARCHAR(255),
    twilio_message_sid VARCHAR(255),
    attachments JSON,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    FOREIGN KEY (lead_id) REFERENCES crm_leads(lead_id) ON DELETE CASCADE,
    INDEX idx_company (company_id),
    INDEX idx_customer (customer_id),
    INDEX idx_lead (lead_id),
    INDEX idx_channel (channel),
    INDEX idx_status (status),
    INDEX idx_created_date (created_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email Templates
CREATE TABLE IF NOT EXISTS email_templates (
    template_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    template_name VARCHAR(100) NOT NULL,
    template_slug VARCHAR(100) NOT NULL,
    category ENUM('transactional', 'marketing', 'crm', 'service') DEFAULT 'transactional',
    subject VARCHAR(255) NOT NULL,
    body_html TEXT NOT NULL,
    body_text TEXT,
    merge_tags JSON,
    is_active TINYINT DEFAULT 1,
    is_system TINYINT DEFAULT 0,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    INDEX idx_template_slug (template_slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Service Items Library
CREATE TABLE IF NOT EXISTS service_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    item_type ENUM('service', 'part') NOT NULL,
    item_code VARCHAR(50),
    item_name VARCHAR(255) NOT NULL,
    description TEXT,
    unit_price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2),
    estimated_time_minutes INT,
    quantity_in_stock INT DEFAULT 0,
    reorder_level INT,
    supplier VARCHAR(255),
    is_active TINYINT DEFAULT 1,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    INDEX idx_item_type (item_type),
    INDEX idx_item_code (item_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Stock Alerts
CREATE TABLE IF NOT EXISTS stock_alerts (
    alert_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    make VARCHAR(100),
    model VARCHAR(100),
    min_price DECIMAL(10,2),
    max_price DECIMAL(10,2),
    min_year INT,
    max_year INT,
    fuel_type VARCHAR(50),
    body_type VARCHAR(50),
    is_active TINYINT DEFAULT 1,
    unsubscribe_token VARCHAR(100) UNIQUE,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_sent DATETIME,
    INDEX idx_company (company_id),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subscription Plans
CREATE TABLE IF NOT EXISTS core_plans (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,
    plan_name VARCHAR(100) NOT NULL,
    plan_description TEXT,
    price_monthly DECIMAL(10,2) NOT NULL,
    price_annual DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'EUR',
    stripe_product_id VARCHAR(100),
    stripe_price_monthly_id VARCHAR(100),
    stripe_price_annual_id VARCHAR(100),
    max_vehicles INT DEFAULT 0,
    max_users INT DEFAULT 1,
    max_images_per_vehicle INT DEFAULT 30,
    features JSON,
    is_active TINYINT DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Subscriptions
CREATE TABLE IF NOT EXISTS subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    plan_id VARCHAR(50) NOT NULL,
    stripe_subscription_id VARCHAR(100),
    stripe_customer_id VARCHAR(100),
    status ENUM('active', 'trialing', 'past_due', 'cancelled', 'incomplete', 'incomplete_expired') DEFAULT 'trialing',
    current_period_start DATETIME,
    current_period_end DATETIME,
    cancel_at_period_end TINYINT DEFAULT 0,
    cancelled_at DATETIME,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_date DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    INDEX idx_status (status),
    INDEX idx_stripe_subscription (stripe_subscription_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Invoices (Subscription)
CREATE TABLE IF NOT EXISTS core_invoices (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    subscription_id INT,
    stripe_invoice_id VARCHAR(100),
    stripe_customer_id VARCHAR(100),
    subtotal DECIMAL(10,2) NOT NULL,
    tax DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',
    invoice_pdf_url VARCHAR(500),
    hosted_invoice_url VARCHAR(500),
    status ENUM('paid', 'unpaid', 'void') DEFAULT 'unpaid',
    paid_date DATETIME,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    INDEX idx_subscription (subscription_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Settings
CREATE TABLE IF NOT EXISTS core_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_name VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    setting_group VARCHAR(50),
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Side Menu
CREATE TABLE IF NOT EXISTS core_side_menu (
    menu_id INT AUTO_INCREMENT PRIMARY KEY,
    menu_name VARCHAR(100) NOT NULL,
    menu_url VARCHAR(255),
    menu_icon VARCHAR(50),
    parent_id INT DEFAULT 0,
    permission_level TINYINT DEFAULT 1,
    sort_order INT DEFAULT 0,
    is_active TINYINT DEFAULT 1,
    INDEX idx_parent (parent_id),
    INDEX idx_permission (permission_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Activity Logs
CREATE TABLE IF NOT EXISTS activity_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(50),
    user_agent TEXT,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    INDEX idx_user (user_id),
    INDEX idx_created (created_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Finance Applications
CREATE TABLE IF NOT EXISTS finance_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    vehicle_id INT,
    enquiry_id INT,
    title ENUM('mr', 'mrs', 'ms', 'miss', 'dr'),
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    county VARCHAR(100),
    postcode VARCHAR(20) NOT NULL,
    years_at_address INT,
    residential_status ENUM('owner', 'tenant', 'living-with-parents', 'other'),
    employment_status ENUM('employed', 'self-employed', 'retired', 'student', 'unemployed') NOT NULL,
    employer_name VARCHAR(255),
    occupation VARCHAR(100),
    annual_income DECIMAL(10,2),
    years_employed INT,
    vehicle_price DECIMAL(10,2) NOT NULL,
    deposit_amount DECIMAL(10,2) DEFAULT 0,
    loan_amount DECIMAL(10,2) NOT NULL,
    loan_term INT NOT NULL,
    monthly_payment DECIMAL(10,2),
    interest_rate DECIMAL(5,2),
    bank_name VARCHAR(100),
    bank_account_years INT,
    has_trade_in TINYINT DEFAULT 0,
    trade_in_value DECIMAL(10,2),
    outstanding_finance DECIMAL(10,2),
    documents JSON,
    status ENUM('pending', 'submitted', 'approved', 'declined', 'completed') DEFAULT 'pending',
    lender VARCHAR(100),
    lender_reference VARCHAR(100),
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE SET NULL,
    INDEX idx_company (company_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert Default Settings
INSERT INTO core_settings (setting_name, setting_value, setting_type, setting_group) VALUES
('website_name', 'Car Dealer SaaS', 'text', 'general'),
('website_tagline', 'Complete Car Dealership Management Software', 'text', 'general'),
('contact_email', 'support@cardealer.tools', 'text', 'contact'),
('trial_period_days', '14', 'number', 'subscriptions'),
('default_currency', 'EUR', 'text', 'general'),
('default_vat_rate', '23.00', 'number', 'invoicing')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);

-- Insert Default Menu Items
INSERT INTO core_side_menu (menu_name, menu_url, menu_icon, parent_id, permission_level, sort_order) VALUES
('Dashboard', '/dash', 'home', 0, 1, 1),
('Vehicles', '/vehicles', 'car', 0, 1, 2),
('CRM', '/crm', 'users', 0, 1, 3),
('Calendar', '/calendar', 'calendar', 0, 1, 4),
('Enquiries', '/enquiries', 'mail', 0, 1, 5),
('Customers', '/customers', 'user', 0, 1, 6),
('Invoices', '/invoices', 'file-text', 0, 1, 7),
('Inbox', '/inbox', 'message-square', 0, 1, 8),
('Reports', '/reports', 'bar-chart', 0, 1, 9),
('Users', '/users', 'users', 0, 2, 10),
('Company', '/company', 'briefcase', 0, 2, 11),
('Subscription', '/subscriptions', 'credit-card', 0, 2, 12),
('Super Admin', '/super-admin', 'shield', 0, 10, 99)
ON DUPLICATE KEY UPDATE menu_name=VALUES(menu_name);

-- Insert Default Plans
INSERT INTO core_plans (plan_name, plan_description, price_monthly, price_annual, max_vehicles, max_users, max_images_per_vehicle, sort_order) VALUES
('Starter', 'Perfect for small dealerships', 49.00, 490.00, 25, 2, 20, 1),
('Professional', 'For growing dealerships', 149.00, 1490.00, 100, 5, 40, 2),
('Enterprise', 'Unlimited everything', 299.00, 2990.00, 999999, 15, 60, 3)
ON DUPLICATE KEY UPDATE plan_name=VALUES(plan_name);

-- Database installation complete!
