# CAR DEALER SAAS - COMPREHENSIVE IMPLEMENTATION PLAN

## PROJECT OVERVIEW

A multi-tenant Car Dealer SaaS platform built on PHP 8.2+ that enables multiple car dealerships to manage their inventory, websites, and customer interactions through a single installation. Inspired by Happy Dealer's success in the Irish market.

---

## 1. CORE FEATURES (Based on Happy Dealer Research)

### 1.1 Vehicle Management System
- **Vehicle Inventory Management**
  - Add/Edit/Delete vehicles
  - Bulk import via CSV/XML
  - Image gallery management (30-50+ images per vehicle)
  - Video integration (YouTube/direct upload)
  - Vehicle status (Available, Reserved, Sold, Coming Soon)
  - Featured/Premium vehicle highlighting
  - Stock level tracking

- **Vehicle Information Fields**
  - Basic: Make, Model, Year, VIN, Registration
  - Pricing: Cash price, Trade-in value, VAT status
  - Specifications: Mileage, Fuel type, Transmission, Body type, Doors, Seats, Color (exterior/interior)
  - Engine: Engine size, Power (HP/kW), CO2 emissions, Engine code
  - Features: Safety features, Entertainment, Comfort, Technology
  - History: Service history, Previous owners, NCT/MOT status
  - Documents: Logbook, Service records, Warranty info

### 1.2 Advanced Search & Filtering
- **Frontend Search**
  - Make/Model cascading dropdowns (AJAX-powered)
  - Price range sliders (Cash & Monthly payment)
  - Year range selection
  - Body type multi-select (Saloon, SUV, Hatchback, Coupe, Estate, Van, MPV, Pickup)
  - Fuel type (Petrol, Diesel, Electric, Hybrid, Plug-in Hybrid)
  - Transmission (Manual, Automatic, Semi-Automatic)
  - Mileage range
  - Color filters
  - Features/Options filters
  - Sorting (Price, Year, Mileage, Date Added)

- **Search Results Display**
  - Grid/List view toggle
  - Lazy loading images
  - Save to shortlist/wishlist
  - Stock alert registration
  - Compare vehicles
  - Share via social media
  - Print-friendly layouts

### 1.3 Finance Calculator & Tools
- **Monthly Payment Calculator**
  - Adjustable deposit (0-50%)
  - Term length (12-84 months)
  - Interest rate customization per dealer
  - Multiple lender integration (AIB, Alphera, First Citizen Finance, etc.)
  - Hire Purchase vs PCP comparison
  - Real-time calculation without page reload

- **Budget-Based Vehicle Finder**
  - "What Car Suits Me?" tool
  - Monthly budget input → matching vehicles
  - Cash budget range selector
  - Affordability calculator

- **Finance Applications**
  - Online finance application forms
  - Document upload (ID, proof of address, bank statements)
  - Finance partner integration
  - Application status tracking
  - Decision notification system

### 1.4 Customer Engagement Features
- **Contact & Enquiries**
  - General enquiry forms
  - Vehicle-specific enquiry forms
  - Test drive booking
  - Part-exchange valuation
  - Trade-in calculator
  - Call-back request
  - WhatsApp integration
  - Live chat widget

- **Stock Alerts**
  - Email notification when matching vehicles arrive
  - Criteria: Make, Model, Price range, Year
  - Unsubscribe management
  - Alert frequency control

- **Wishlist/Shortlist**
  - Save favorite vehicles
  - Compare saved vehicles
  - Share wishlist via email/link
  - Wishlist expiry (30 days)

- **Customer Reviews & Testimonials**
  - Google Reviews integration
  - Manual testimonial management
  - Star ratings display
  - Customer photos
  - Staff-specific reviews

### 1.5 Dealer Website Features
- **Public-Facing Pages**
  - Homepage with featured vehicles
  - About Us / Our Story
  - Meet The Team (staff profiles)
  - Opening Hours & Location
  - Contact Us page with map
  - Finance Information page
  - Warranty Information
  - After-sales Services
  - Blog/News section
  - Careers page
  - Privacy Policy / Terms & Conditions

- **Service Booking**
  - Workshop appointment booking
  - Service packages display
  - MOT/NCT reminders
  - Tire services
  - Diagnostics booking
  - Service history for returning customers

- **Trade-In Portal**
  - "We Buy Cars" section
  - Sell your car form
  - Instant valuation tool
  - Image upload
  - Vehicle history check integration

### 1.6 Multi-Dealership (Multi-Tenant) Features
- **Dealer Account Management**
  - Dealer registration/onboarding
  - Company profile setup
  - Branding customization (logo, colors, fonts)
  - Custom domain mapping (karlgoodwinmotors.ie, etc.)
  - SSL certificate management

- **Subscription Plans**
  - Starter: Up to 25 vehicles, basic features
  - Professional: Up to 100 vehicles, advanced features
  - Enterprise: Unlimited vehicles, all features + API access
  - Monthly/Annual billing
  - Free trial period (14-30 days)

- **Dealer Dashboard**
  - Vehicle inventory overview
  - Enquiry management
  - Lead tracking
  - Analytics & reporting
  - Staff user management
  - Subscription management
  - Billing history

### 1.7 SEO & Marketing Tools
- **SEO Optimization**
  - Auto-generated meta titles/descriptions
  - Schema.org markup (Car, LocalBusiness, Review)
  - XML sitemap generation
  - SEO-friendly URLs (/cars/2022-bmw-5-series)
  - Open Graph tags
  - Canonical URLs

- **Social Media Integration**
  - Facebook page integration
  - Instagram feed display
  - Auto-post new vehicles to social media
  - Social sharing buttons

- **Email Marketing**
  - Newsletter subscription
  - New stock alerts
  - Special offers campaigns
  - Abandoned enquiry follow-up
  - Birthday/anniversary emails

- **Analytics**
  - Google Analytics integration
  - Google Tag Manager support
  - Conversion tracking
  - Heatmap integration (Hotjar, etc.)
  - Custom event tracking

### 1.8 Admin Features
- **Super Admin Dashboard**
  - All dealers overview
  - System-wide analytics
  - Subscription management
  - Payment processing
  - User management across all dealers
  - Feature flag management
  - Support ticket system

- **Dealer Admin Dashboard**
  - Vehicle CRUD operations
  - Enquiry inbox with status tracking
  - Finance applications dashboard
  - Customer database (GDPR-compliant)
  - Staff user permissions (Owner, Manager, Sales, Receptionist)
  - Reports (Sales, Enquiries, Website traffic, Popular vehicles)
  - Settings (Opening hours, Contact info, Social media links)

---

## 2. TECHNICAL ARCHITECTURE

### 2.1 Technology Stack

**Backend:**
- PHP 8.2+
- MySQL 8.0+
- Apache with mod_rewrite
- Composer for dependency management

**Frontend:**
- Custom Bootstrap 5 admin theme
- Vanilla JavaScript with jQuery
- AJAX for dynamic interactions
- CSS Grid/Flexbox for layouts

**Key Dependencies:**
```json
{
  "illuminate/collections": "^10.9",
  "stripe/stripe-php": "^10.12",
  "wildbit/postmark-php": "^6.0",
  "catfan/medoo": "^2.1",
  "intervention/image": "^2.7",
  "league/flysystem": "^3.0",
  "phpoffice/phpspreadsheet": "^1.29",
  "aws/aws-sdk-php": "^3.0",
  "google/apiclient": "^2.12",
  "ibericode/vat": "^2.0"
}
```

### 2.2 Project Structure

```
/cardealer.tools/
├── /public/                          # Web-accessible root
│   ├── index.php                     # Front controller
│   ├── /assets/
│   │   ├── /css/                     # Stylesheets
│   │   ├── /js/                      # JavaScript files
│   │   ├── /images/                  # Static images
│   │   ├── /fonts/                   # Web fonts
│   │   └── /vendor/                  # Third-party assets
│   └── /uploads/                     # Vehicle images (symlinked to storage)
│
├── /app/
│   ├── /controllers/                 # Business logic
│   │   ├── /login/                   # Authentication
│   │   ├── /dash/                    # Main dashboard
│   │   ├── /vehicles/                # Vehicle management
│   │   ├── /enquiries/               # Lead management
│   │   ├── /finance/                 # Finance applications
│   │   ├── /customers/               # Customer database
│   │   ├── /website/                 # Website settings
│   │   ├── /users/                   # Staff users
│   │   ├── /company/                 # Dealer company settings
│   │   ├── /reports/                 # Analytics & reports
│   │   ├── /subscriptions/           # Billing
│   │   ├── /super-admin/             # Super admin
│   │   ├── /webhook/                 # Webhooks
│   │   └── /cron/                    # Scheduled tasks
│   │
│   └── /functions/                   # Reusable functions
│       ├── fn_core_database.php      # Database helpers
│       ├── fn_core_router.php        # Routing
│       ├── fn_core_session.php       # Authentication
│       ├── fn_core_stripe.php        # Payments
│       ├── fn_core_email.php         # Email sending
│       ├── fn_core_settings.php      # Settings management
│       ├── fn_vehicles.php           # Vehicle operations
│       ├── fn_enquiries.php          # Enquiry handling
│       ├── fn_finance.php            # Finance calculations
│       ├── fn_search.php             # Search & filtering
│       ├── fn_seo.php                # SEO helpers
│       └── fn_images.php             # Image processing
│
├── /views/                           # View templates
│   ├── /_partials/                   # Reusable components
│   │   ├── html_head.php
│   │   ├── html_header.php
│   │   ├── html_sidebar.php
│   │   └── html_footer.php
│   ├── /public/                      # Public website views
│   │   ├── /home/
│   │   ├── /vehicles/
│   │   ├── /search/
│   │   ├── /about/
│   │   └── /contact/
│   └── /admin/                       # Admin panel views
│       ├── /dash/
│       ├── /vehicles/
│       ├── /enquiries/
│       └── /settings/
│
├── /storage/                         # File storage
│   ├── /uploads/                     # User uploads
│   │   └── /vehicles/                # Vehicle images
│   ├── /logs/                        # Application logs
│   └── /cache/                       # Cache files
│
├── /config.php                       # Configuration
├── /composer.json                    # Dependencies
└── /.htaccess                        # Apache config
```

### 2.3 Routing Structure

**Custom array-based routing in `/app/functions/fn_core_router.php`:**

```php
$routes = [
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

    // Enquiries
    '/enquiries' => 'enquiries/enquiries',
    '/enquiries/view' => 'enquiries/enquiries-view',
    '/enquiries/update-status' => 'enquiries/enquiries-update-status',

    // Finance Applications
    '/finance-applications' => 'finance/applications',
    '/finance-applications/view' => 'finance/applications-view',

    // Customers
    '/customers' => 'customers/customers',
    '/customers/view' => 'customers/customers-view',

    // Website Settings
    '/website' => 'website/settings',
    '/website/pages' => 'website/pages',
    '/website/menus' => 'website/menus',

    // Reports
    '/reports' => 'reports/index',
    '/reports/sales' => 'reports/sales',
    '/reports/enquiries' => 'reports/enquiries',
    '/reports/analytics' => 'reports/analytics',

    // Users
    '/users' => 'users/users',
    '/users/new' => 'users/users-new',
    '/users/edit' => 'users/users-edit',

    // Company Settings
    '/company' => 'company/company',
    '/company/branding' => 'company/branding',

    // Subscriptions
    '/subscriptions' => 'subscriptions/subscriptions',
    '/subscriptions/upgrade' => 'subscriptions/upgrade',
    '/subscriptions/checkout' => 'subscriptions/checkout',

    // Super Admin
    '/super-admin' => 'super-admin/dashboard',
    '/super-admin/dealers' => 'super-admin/dealers',
    '/super-admin/settings' => 'super-admin/settings',

    // Webhooks
    '/webhook/stripe' => 'webhook/stripe',

    // Cron Jobs
    '/cron/subscriptions' => 'cron/subscriptions',
    '/cron/stock-alerts' => 'cron/stock-alerts',
];
```

### 2.4 Database Schema

**Core Tables:**

```sql
-- Users & Authentication
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    phone VARCHAR(50),
    user_type TINYINT DEFAULT 1,
    -- 0=Guest, 1=Registered, 2=Company Created, 3=Paid, 10=Super Admin
    user_role ENUM('owner', 'manager', 'sales', 'receptionist') DEFAULT 'sales',
    email_verified TINYINT DEFAULT 0,
    email_activation_code VARCHAR(100),
    last_login DATETIME,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_company (company_id),
    INDEX idx_email (email)
);

-- Dealer Companies
CREATE TABLE core_company (
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
);

-- Vehicles
CREATE TABLE vehicles (
    vehicle_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,

    -- Basic Info
    make VARCHAR(100) NOT NULL,
    model VARCHAR(100) NOT NULL,
    year INT NOT NULL,
    registration VARCHAR(50),
    vin VARCHAR(100),

    -- Pricing
    price DECIMAL(10,2) NOT NULL,
    was_price DECIMAL(10,2),
    trade_in_value DECIMAL(10,2),
    vat_status ENUM('vat_inclusive', 'vat_exclusive', 'vat_margin') DEFAULT 'vat_inclusive',
    vat_amount DECIMAL(10,2),

    -- Specifications
    mileage INT,
    mileage_unit ENUM('km', 'miles') DEFAULT 'km',
    fuel_type ENUM('petrol', 'diesel', 'electric', 'hybrid', 'plug-in-hybrid', 'lpg', 'cng') NOT NULL,
    transmission ENUM('manual', 'automatic', 'semi-automatic') NOT NULL,
    body_type ENUM('saloon', 'suv', 'hatchback', 'coupe', 'estate', 'van', 'mpv', 'pickup', 'convertible') NOT NULL,
    doors TINYINT,
    seats TINYINT,
    exterior_color VARCHAR(50),
    interior_color VARCHAR(50),

    -- Engine Details
    engine_size INT,
    engine_size_unit ENUM('cc', 'L') DEFAULT 'cc',
    power_hp INT,
    power_kw INT,
    co2_emissions INT,
    engine_code VARCHAR(50),
    drivetrain ENUM('fwd', 'rwd', 'awd', '4wd'),

    -- History
    previous_owners TINYINT,
    service_history ENUM('full', 'partial', 'none', 'unknown'),
    nct_expiry DATE,
    mot_expiry DATE,

    -- Description & Features
    description TEXT,
    features JSON,

    -- Status
    status ENUM('available', 'reserved', 'sold', 'coming-soon') DEFAULT 'available',
    is_featured TINYINT DEFAULT 0,
    is_premium TINYINT DEFAULT 0,

    -- SEO
    slug VARCHAR(255) UNIQUE,
    meta_title VARCHAR(255),
    meta_description TEXT,

    -- Timestamps
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
);

-- Vehicle Images
CREATE TABLE vehicle_images (
    image_id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    image_order INT DEFAULT 0,
    is_primary TINYINT DEFAULT 0,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE CASCADE,
    INDEX idx_vehicle (vehicle_id)
);

-- Vehicle Features Library
CREATE TABLE vehicle_features (
    feature_id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('safety', 'entertainment', 'comfort', 'technology', 'exterior', 'interior') NOT NULL,
    feature_name VARCHAR(100) NOT NULL,
    feature_icon VARCHAR(50),
    UNIQUE KEY (category, feature_name)
);

-- Enquiries
CREATE TABLE enquiries (
    enquiry_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    vehicle_id INT,

    -- Customer Info
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),

    -- Enquiry Details
    enquiry_type ENUM('general', 'vehicle', 'test-drive', 'finance', 'trade-in', 'service') NOT NULL,
    message TEXT,
    preferred_contact_method ENUM('email', 'phone', 'whatsapp'),
    preferred_contact_time VARCHAR(100),

    -- Test Drive Specific
    test_drive_date DATE,
    test_drive_time TIME,

    -- Trade-In Specific
    trade_in_make VARCHAR(100),
    trade_in_model VARCHAR(100),
    trade_in_year INT,
    trade_in_mileage INT,
    trade_in_registration VARCHAR(50),

    -- Status & Assignment
    status ENUM('new', 'contacted', 'qualified', 'test-drive-booked', 'negotiating', 'won', 'lost') DEFAULT 'new',
    assigned_to INT,
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',

    -- Notes & Follow-up
    notes TEXT,
    next_followup_date DATE,

    -- Source Tracking
    source VARCHAR(100),
    ip_address VARCHAR(50),
    user_agent TEXT,

    -- Timestamps
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE SET NULL,
    INDEX idx_company (company_id),
    INDEX idx_status (status),
    INDEX idx_created_date (created_date)
);

-- Finance Applications
CREATE TABLE finance_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    vehicle_id INT,
    enquiry_id INT,

    -- Applicant Info
    title ENUM('mr', 'mrs', 'ms', 'miss', 'dr'),
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,

    -- Address
    address_line1 VARCHAR(255) NOT NULL,
    address_line2 VARCHAR(255),
    city VARCHAR(100) NOT NULL,
    county VARCHAR(100),
    postcode VARCHAR(20) NOT NULL,
    years_at_address INT,
    residential_status ENUM('owner', 'tenant', 'living-with-parents', 'other'),

    -- Employment
    employment_status ENUM('employed', 'self-employed', 'retired', 'student', 'unemployed') NOT NULL,
    employer_name VARCHAR(255),
    occupation VARCHAR(100),
    annual_income DECIMAL(10,2),
    years_employed INT,

    -- Finance Details
    vehicle_price DECIMAL(10,2) NOT NULL,
    deposit_amount DECIMAL(10,2) DEFAULT 0,
    loan_amount DECIMAL(10,2) NOT NULL,
    loan_term INT NOT NULL,
    monthly_payment DECIMAL(10,2),
    interest_rate DECIMAL(5,2),

    -- Banking
    bank_name VARCHAR(100),
    bank_account_years INT,

    -- Trade-In
    has_trade_in TINYINT DEFAULT 0,
    trade_in_value DECIMAL(10,2),
    outstanding_finance DECIMAL(10,2),

    -- Documents
    documents JSON,

    -- Status
    status ENUM('pending', 'submitted', 'approved', 'declined', 'completed') DEFAULT 'pending',
    lender VARCHAR(100),
    lender_reference VARCHAR(100),

    -- Timestamps
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id) ON DELETE SET NULL,
    INDEX idx_company (company_id),
    INDEX idx_status (status)
);

-- Customers
CREATE TABLE customers (
    customer_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,

    -- Basic Info
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),

    -- Address
    address_line1 VARCHAR(255),
    address_line2 VARCHAR(255),
    city VARCHAR(100),
    county VARCHAR(100),
    postcode VARCHAR(20),

    -- Customer Data
    date_of_birth DATE,
    customer_type ENUM('buyer', 'seller', 'service', 'enquiry') DEFAULT 'enquiry',
    source VARCHAR(100),

    -- Communication Preferences
    opt_in_email TINYINT DEFAULT 1,
    opt_in_sms TINYINT DEFAULT 0,
    opt_in_phone TINYINT DEFAULT 1,

    -- GDPR
    gdpr_consent TINYINT DEFAULT 0,
    gdpr_consent_date DATETIME,

    -- Timestamps
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_company (company_id),
    INDEX idx_email (email),
    UNIQUE KEY unique_company_email (company_id, email)
);

-- Stock Alerts
CREATE TABLE stock_alerts (
    alert_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    email VARCHAR(255) NOT NULL,

    -- Criteria
    make VARCHAR(100),
    model VARCHAR(100),
    min_price DECIMAL(10,2),
    max_price DECIMAL(10,2),
    min_year INT,
    max_year INT,
    fuel_type VARCHAR(50),
    body_type VARCHAR(50),

    -- Status
    is_active TINYINT DEFAULT 1,
    unsubscribe_token VARCHAR(100) UNIQUE,

    -- Timestamps
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_sent DATETIME,

    INDEX idx_company (company_id),
    INDEX idx_active (is_active)
);

-- Subscription Plans
CREATE TABLE core_plans (
    plan_id INT AUTO_INCREMENT PRIMARY KEY,
    plan_name VARCHAR(100) NOT NULL,
    plan_description TEXT,

    -- Pricing
    price_monthly DECIMAL(10,2) NOT NULL,
    price_annual DECIMAL(10,2),
    currency VARCHAR(3) DEFAULT 'EUR',

    -- Stripe
    stripe_product_id VARCHAR(100),
    stripe_price_monthly_id VARCHAR(100),
    stripe_price_annual_id VARCHAR(100),

    -- Limits
    max_vehicles INT DEFAULT 0,
    max_users INT DEFAULT 1,
    max_images_per_vehicle INT DEFAULT 30,

    -- Features
    features JSON,

    -- Status
    is_active TINYINT DEFAULT 1,
    sort_order INT DEFAULT 0,

    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Subscriptions
CREATE TABLE core_subscriptions (
    subscription_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    plan_id INT NOT NULL,
    user_id INT NOT NULL,

    -- Stripe
    stripe_subscription_id VARCHAR(100),
    stripe_customer_id VARCHAR(100),

    -- Status
    status ENUM('D', 'A', 'W', 'S') DEFAULT 'D',
    -- D=Unpaid, A=Active, W=Warning, S=Suspended

    -- Dates
    current_period_start DATE,
    current_period_end DATE,
    active_until DATE,
    trial_ends_at DATE,
    canceled_at DATETIME,

    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_company (company_id),
    INDEX idx_status (status)
);

-- Invoices
CREATE TABLE core_invoices (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    subscription_id INT,

    -- Stripe
    stripe_invoice_id VARCHAR(100),
    stripe_customer_id VARCHAR(100),

    -- Amounts
    subtotal DECIMAL(10,2) NOT NULL,
    tax DECIMAL(10,2) DEFAULT 0,
    total DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'EUR',

    -- URLs
    invoice_pdf_url VARCHAR(500),
    hosted_invoice_url VARCHAR(500),

    -- Status
    status ENUM('paid', 'unpaid', 'void') DEFAULT 'unpaid',
    paid_date DATETIME,

    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_company (company_id),
    INDEX idx_subscription (subscription_id)
);

-- Settings
CREATE TABLE core_settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    setting_name VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'number', 'boolean', 'json') DEFAULT 'text',
    setting_group VARCHAR(50),
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Side Menu (Dynamic Navigation)
CREATE TABLE core_side_menu (
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
);

-- Activity Logs
CREATE TABLE activity_logs (
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
);
```

---

## 3. MULTI-TENANT ARCHITECTURE

### 3.1 Tenant Isolation Strategy

**Database Level:**
- Single shared database
- All tables include `company_id` column
- All queries filtered by `company_id`
- Security function: `fn_check_security($user_id, $company_id)`

**Domain Mapping:**
- Custom domains per dealer (karlgoodwinmotors.ie)
- Subdomain fallback (karlgoodwin.cardealer.tools)
- Domain stored in `core_company.domain`
- Request routing based on HTTP_HOST

**Implementation:**
```php
// In /public/index.php
$domain = $_SERVER['HTTP_HOST'];
$company = fn_get_company_by_domain($domain);
$_SESSION['company_id'] = $company['company_id'];

// In every query
function fn_vehicles_get_all($company_id) {
    $query = "SELECT * FROM vehicles WHERE company_id = :company_id";
    return fn_core_database_rows($query, ['company_id' => $company_id]);
}
```

### 3.2 Data Isolation

**User Access Control:**
```php
// Security check in every controller
if (!fn_check_security($_SESSION['user_id'], $company_id)) {
    header("Location: /error/403");
    exit;
}

function fn_check_security($user_id, $company_id) {
    $user = fn_core_session_get_user_data_by_id($user_id);

    // Super admin can access everything
    if ($user['user_type'] == 10) {
        return true;
    }

    // User must belong to the company
    return $user['company_id'] == $company_id;
}
```

### 3.3 Asset Storage

**File Organization:**
```
/storage/uploads/
├── /company_1/
│   └── /vehicles/
│       ├── /123/
│       │   ├── image1.jpg
│       │   ├── image2.jpg
│       │   └── thumbnail_image1.jpg
│       └── /124/
├── /company_2/
│   └── /vehicles/
└── /company_3/
```

**S3 Bucket Structure (Optional):**
```
cardealer-saas-bucket/
├── company-1/vehicles/123/image1.jpg
├── company-1/vehicles/123/image2.jpg
├── company-2/vehicles/456/image1.jpg
```

---

## 4. SUBSCRIPTION TIERS

### 4.1 Plan Comparison

| Feature | Starter | Professional | Enterprise |
|---------|---------|--------------|------------|
| **Price (Monthly)** | €49 | €149 | €299 |
| **Price (Annual)** | €490 (€40/mo) | €1,490 (€124/mo) | €2,990 (€249/mo) |
| **Max Vehicles** | 25 | 100 | Unlimited |
| **Max Users** | 2 | 5 | 15 |
| **Images per Vehicle** | 20 | 40 | 60 |
| **Video per Vehicle** | ❌ | ✅ | ✅ |
| **Custom Domain** | ❌ | ✅ | ✅ |
| **Finance Calculator** | ✅ | ✅ | ✅ |
| **Stock Alerts** | ✅ | ✅ | ✅ |
| **Contact Forms** | ✅ | ✅ | ✅ |
| **Google Analytics** | ✅ | ✅ | ✅ |
| **Custom Branding** | Basic | Advanced | Full |
| **Email Support** | ✅ | ✅ | ✅ |
| **Priority Support** | ❌ | ✅ | ✅ |
| **Phone Support** | ❌ | ❌ | ✅ |
| **API Access** | ❌ | ❌ | ✅ |
| **White Label** | ❌ | ❌ | ✅ |
| **Bulk Import** | CSV | CSV/XML | CSV/XML/API |
| **SEO Tools** | Basic | Advanced | Advanced |
| **Social Media** | Manual | Auto-post | Auto-post + Schedule |

### 4.2 Feature Flags

```php
function fn_subscription_check_feature($company_id, $feature) {
    $subscription = fn_core_get_active_subscription($company_id);
    $plan = fn_core_get_plan($subscription['plan_id']);

    $features = json_decode($plan['features'], true);
    return isset($features[$feature]) && $features[$feature] === true;
}

// Usage
if (fn_subscription_check_feature($company_id, 'custom_domain')) {
    // Allow custom domain setup
}

if (fn_subscription_check_feature($company_id, 'api_access')) {
    // Enable API endpoints
}
```

---

## 5. IMPLEMENTATION ROADMAP

### Phase 1: Foundation (Weeks 1-2)

**Database & Core Infrastructure**
- [ ] Set up project structure
- [ ] Create config.php with database credentials
- [ ] Implement routing system (fn_core_router.php)
- [ ] Build database helper functions (fn_core_database.php)
- [ ] Create all database tables
- [ ] Set up session management (fn_core_session.php)
- [ ] Implement authentication system
- [ ] Create permission levels

**Basic Admin UI**
- [ ] Set up Bootstrap admin theme
- [ ] Create admin layout partials
- [ ] Build dashboard homepage
- [ ] Implement side menu system

### Phase 2: User & Company Management (Weeks 3-4)

**Multi-Tenancy Setup**
- [ ] Company registration flow
- [ ] Domain/subdomain mapping
- [ ] Company settings page
- [ ] Branding customization (logo, colors)
- [ ] User management (CRUD)
- [ ] User roles & permissions
- [ ] Security helper function

**Authentication Enhancements**
- [ ] Email verification
- [ ] Password reset flow
- [ ] Remember me functionality
- [ ] Session timeout handling

### Phase 3: Vehicle Management (Weeks 5-7)

**Core Vehicle CRUD**
- [ ] Vehicle add/edit/delete controllers
- [ ] Vehicle listing page with pagination
- [ ] Vehicle detail view
- [ ] Image upload & management
- [ ] Multiple image support (drag & drop)
- [ ] Image ordering & primary image
- [ ] Thumbnail generation
- [ ] Vehicle status management
- [ ] Featured/Premium flags

**Vehicle Features**
- [ ] Feature library setup
- [ ] Feature categories
- [ ] Bulk feature assignment
- [ ] Feature filtering

**CSV Import**
- [ ] CSV template creation
- [ ] Import validation
- [ ] Bulk vehicle import
- [ ] Import error handling
- [ ] Import history log

### Phase 4: Public Website (Weeks 8-10)

**Homepage**
- [ ] Featured vehicles slider
- [ ] Search widget
- [ ] Statistics (vehicles in stock, etc.)
- [ ] About section
- [ ] Testimonials section
- [ ] Call-to-action buttons

**Vehicle Search & Listing**
- [ ] Advanced search form
- [ ] AJAX make/model cascading
- [ ] Filter by price, year, mileage
- [ ] Filter by body type, fuel, transmission
- [ ] Grid/List view toggle
- [ ] Sorting options
- [ ] Pagination
- [ ] Results count
- [ ] SEO-friendly URLs

**Vehicle Detail Page**
- [ ] Image gallery (lightbox)
- [ ] Video integration
- [ ] Specifications table
- [ ] Features list
- [ ] Finance calculator widget
- [ ] Enquiry form
- [ ] Test drive booking
- [ ] Share buttons
- [ ] Similar vehicles
- [ ] Print functionality

**Static Pages**
- [ ] About Us
- [ ] Contact Us (with map)
- [ ] Finance Information
- [ ] Privacy Policy
- [ ] Terms & Conditions

### Phase 5: Finance & Enquiries (Weeks 11-12)

**Finance Calculator**
- [ ] Monthly payment calculator
- [ ] Adjustable deposit slider
- [ ] Term length selector
- [ ] Interest rate configuration
- [ ] Real-time AJAX calculation
- [ ] Budget-based vehicle finder

**Finance Applications**
- [ ] Multi-step application form
- [ ] Form validation
- [ ] Document upload
- [ ] Application dashboard
- [ ] Status tracking
- [ ] Admin approval workflow

**Enquiry System**
- [ ] General enquiry form
- [ ] Vehicle-specific enquiry
- [ ] Test drive booking
- [ ] Trade-in valuation form
- [ ] Enquiry inbox
- [ ] Status management (New, Contacted, Qualified, Won, Lost)
- [ ] Assign to user
- [ ] Notes & follow-up dates
- [ ] Email notifications

### Phase 6: Customer Management (Weeks 13-14)

**Customer Database**
- [ ] Customer CRUD
- [ ] Customer detail view
- [ ] Purchase history
- [ ] Enquiry history
- [ ] Communication log
- [ ] GDPR compliance tools
- [ ] Export customer data
- [ ] Delete customer data

**Stock Alerts**
- [ ] Alert registration form
- [ ] Alert criteria storage
- [ ] Cron job to match vehicles
- [ ] Email notification
- [ ] Unsubscribe functionality
- [ ] Alert management (admin)

**Wishlist/Shortlist**
- [ ] Add to wishlist button
- [ ] Wishlist page
- [ ] Remove from wishlist
- [ ] Share wishlist
- [ ] Session-based for guests
- [ ] Database-stored for users

### Phase 7: Billing & Subscriptions (Weeks 15-16)

**Stripe Integration**
- [ ] Stripe account setup
- [ ] Product/Price creation
- [ ] Checkout session
- [ ] Webhook endpoint (/webhook/stripe)
- [ ] Invoice.paid handler
- [ ] Subscription status updates
- [ ] Invoice storage
- [ ] Payment history

**Subscription Management**
- [ ] Plan selection page
- [ ] Upgrade/downgrade flow
- [ ] Plan comparison table
- [ ] Trial period handling
- [ ] Cancellation flow
- [ ] Renewal reminders
- [ ] Feature limit enforcement

**Billing Dashboard**
- [ ] Current plan display
- [ ] Usage statistics (vehicles, users)
- [ ] Invoice history
- [ ] Payment method management
- [ ] Billing address

### Phase 8: SEO & Marketing (Weeks 17-18)

**SEO Tools**
- [ ] Auto-generated meta tags
- [ ] Schema.org markup (Car, LocalBusiness)
- [ ] XML sitemap generation
- [ ] SEO-friendly URLs
- [ ] Open Graph tags
- [ ] Canonical URLs
- [ ] robots.txt
- [ ] sitemap.xml cron job

**Email Marketing**
- [ ] Newsletter subscription
- [ ] Email template system
- [ ] New stock alerts
- [ ] Postmark integration
- [ ] Email preferences
- [ ] Unsubscribe handling

**Social Media**
- [ ] Facebook integration
- [ ] Instagram feed
- [ ] Social sharing buttons
- [ ] Auto-post new vehicles (optional)

**Analytics**
- [ ] Google Analytics setup
- [ ] Google Tag Manager
- [ ] Conversion tracking
- [ ] Custom events
- [ ] Popular vehicles tracking

### Phase 9: Reports & Analytics (Week 19)

**Dealer Reports**
- [ ] Sales report (sold vehicles)
- [ ] Enquiry report (by status, source)
- [ ] Vehicle views report
- [ ] Popular searches
- [ ] Website traffic overview
- [ ] Lead conversion rates
- [ ] Finance application stats
- [ ] Export to CSV/PDF

**Super Admin Reports**
- [ ] All dealers overview
- [ ] Revenue report
- [ ] Active subscriptions
- [ ] Trial conversions
- [ ] Churn rate
- [ ] System health

### Phase 10: Super Admin Features (Week 20)

**Super Admin Dashboard**
- [ ] All dealers list
- [ ] Dealer detail view
- [ ] Impersonate dealer
- [ ] Subscription management
- [ ] Manual subscription activation
- [ ] System settings
- [ ] Feature flags
- [ ] Email template management
- [ ] Activity logs

**Support System**
- [ ] Support ticket system
- [ ] Ticket categories
- [ ] Ticket status
- [ ] Internal notes
- [ ] Email notifications

### Phase 11: Testing & Optimization (Weeks 21-22)

**Testing**
- [ ] User registration flow
- [ ] Vehicle CRUD operations
- [ ] Search & filtering
- [ ] Finance calculator accuracy
- [ ] Enquiry submission
- [ ] Stripe payment flow
- [ ] Email delivery
- [ ] Mobile responsiveness
- [ ] Browser compatibility
- [ ] Performance testing
- [ ] Security audit

**Optimization**
- [ ] Database indexing
- [ ] Query optimization
- [ ] Image compression
- [ ] Lazy loading
- [ ] Caching strategy
- [ ] CDN setup (optional)
- [ ] Error logging
- [ ] Monitoring setup

### Phase 12: Launch Preparation (Week 23)

**Documentation**
- [ ] User guide (dealers)
- [ ] Admin guide
- [ ] API documentation (if applicable)
- [ ] Setup instructions
- [ ] Troubleshooting guide

**Marketing Materials**
- [ ] Landing page
- [ ] Pricing page
- [ ] Features comparison
- [ ] Demo videos
- [ ] Screenshots
- [ ] Case studies (if available)

**Launch Checklist**
- [ ] Domain setup
- [ ] SSL certificates
- [ ] Email deliverability (SPF, DKIM)
- [ ] Backup system
- [ ] Monitoring alerts
- [ ] Support email setup
- [ ] Terms of Service
- [ ] Privacy Policy
- [ ] Cookie consent

---

## 6. KEY FUNCTIONS TO IMPLEMENT

### 6.1 Vehicle Functions (fn_vehicles.php)

```php
fn_vehicles_create($data)
fn_vehicles_update($vehicle_id, $data)
fn_vehicles_delete($vehicle_id, $company_id)
fn_vehicles_get_all($company_id, $offset, $limit, $filters)
fn_vehicles_get_by_id($vehicle_id, $company_id)
fn_vehicles_get_by_slug($slug, $company_id)
fn_vehicles_search($company_id, $criteria)
fn_vehicles_count($company_id, $filters)
fn_vehicles_mark_as_sold($vehicle_id, $company_id)
fn_vehicles_get_featured($company_id, $limit)
fn_vehicles_get_similar($vehicle_id, $limit)
fn_vehicles_generate_slug($make, $model, $year, $vehicle_id)
fn_vehicles_import_csv($file, $company_id)
```

### 6.2 Image Functions (fn_images.php)

```php
fn_images_upload($vehicle_id, $files)
fn_images_delete($image_id, $company_id)
fn_images_reorder($vehicle_id, $order_array)
fn_images_set_primary($image_id, $vehicle_id)
fn_images_get_by_vehicle($vehicle_id)
fn_images_generate_thumbnail($source, $destination, $width, $height)
fn_images_optimize($path)
```

### 6.3 Search Functions (fn_search.php)

```php
fn_search_get_makes($company_id)
fn_search_get_models_by_make($make, $company_id)
fn_search_get_years($company_id)
fn_search_get_price_range($company_id)
fn_search_apply_filters($query, $filters)
fn_search_build_query($company_id, $filters)
fn_search_save_search($company_id, $user_id, $criteria)
```

### 6.4 Finance Functions (fn_finance.php)

```php
fn_finance_calculate_monthly($price, $deposit, $term, $interest_rate)
fn_finance_calculate_total_cost($monthly, $term, $deposit)
fn_finance_get_vehicles_by_budget($monthly_budget, $company_id)
fn_finance_application_create($data)
fn_finance_application_update_status($app_id, $status)
fn_finance_application_get_all($company_id, $filters)
fn_finance_get_lenders($company_id)
```

### 6.5 Enquiry Functions (fn_enquiries.php)

```php
fn_enquiries_create($data)
fn_enquiries_update_status($enquiry_id, $status, $company_id)
fn_enquiries_assign($enquiry_id, $user_id, $company_id)
fn_enquiries_add_note($enquiry_id, $note, $user_id)
fn_enquiries_get_all($company_id, $filters)
fn_enquiries_get_by_id($enquiry_id, $company_id)
fn_enquiries_send_notification($enquiry_id)
fn_enquiries_count_by_status($company_id)
```

### 6.6 Customer Functions (fn_customers.php)

```php
fn_customers_create($data)
fn_customers_update($customer_id, $data, $company_id)
fn_customers_get_all($company_id, $offset, $limit)
fn_customers_get_by_id($customer_id, $company_id)
fn_customers_get_by_email($email, $company_id)
fn_customers_get_enquiries($customer_id)
fn_customers_get_purchases($customer_id)
fn_customers_export_data($customer_id, $company_id) // GDPR
fn_customers_delete_data($customer_id, $company_id) // GDPR
```

### 6.7 SEO Functions (fn_seo.php)

```php
fn_seo_generate_meta_title($vehicle)
fn_seo_generate_meta_description($vehicle)
fn_seo_generate_schema_car($vehicle)
fn_seo_generate_schema_local_business($company)
fn_seo_generate_sitemap($company_id)
fn_seo_submit_sitemap_to_google($company_id)
```

### 6.8 Email Functions (fn_core_email.php)

```php
fn_email_send($to, $subject, $body, $template)
fn_email_send_enquiry_notification($enquiry_id)
fn_email_send_stock_alert($alert_id, $vehicle_id)
fn_email_send_test_drive_confirmation($enquiry_id)
fn_email_send_finance_application_received($application_id)
fn_email_send_welcome($user_id)
fn_email_send_trial_ending($company_id, $days_left)
fn_email_send_invoice($invoice_id)
```

---

## 7. SECURITY CONSIDERATIONS

### 7.1 Authentication & Authorization
- Password hashing with `password_hash()` (bcrypt)
- Session regeneration on login
- Session timeout (1 hour)
- Email verification
- Rate limiting on login attempts
- CSRF token protection

### 7.2 Data Protection
- PDO prepared statements (SQL injection prevention)
- Input validation & sanitization
- XSS prevention (htmlspecialchars)
- File upload validation (type, size, extension)
- GDPR compliance (data export, right to be forgotten)
- Data encryption for sensitive fields

### 7.3 Multi-Tenant Security
- Company ID verification on every query
- `fn_check_security()` helper function
- No cross-tenant data access
- Super admin override with audit logging

### 7.4 File Security
- Upload directory outside public root
- File type whitelist (jpg, png, pdf only)
- File size limits
- Virus scanning (optional)
- Unique filenames (prevent overwrites)

---

## 8. PERFORMANCE OPTIMIZATION

### 8.1 Database
- Proper indexing (company_id, status, created_date)
- Query optimization (avoid N+1 problems)
- Pagination on all lists
- Connection pooling
- Query caching

### 8.2 Images
- Thumbnail generation (multiple sizes)
- Image compression (80% quality)
- Lazy loading on frontend
- CDN for image delivery (optional)
- WebP format support

### 8.3 Caching
- Session caching
- Query result caching (Redis/Memcached optional)
- Static page caching
- Browser caching headers

### 8.4 Frontend
- Minified CSS/JS
- Combined asset files
- Deferred JavaScript loading
- Optimized images
- Responsive images (srcset)

---

## 9. THIRD-PARTY INTEGRATIONS

### Required:
- **Stripe** - Payment processing
- **Postmark** - Transactional emails
- **Google Maps** - Location display
- **Google Analytics** - Traffic tracking

### Optional:
- **AWS S3** - Image storage
- **Twilio** - SMS notifications
- **Facebook/Instagram API** - Social media integration
- **Google Ads API** - Advertising integration
- **Zapier** - Workflow automation
- **DoneDeal/CarZone API** - Listing syndication

---

## 10. DEPLOYMENT & HOSTING

### Server Requirements:
- PHP 8.2+
- MySQL 8.0+
- Apache with mod_rewrite
- SSL certificate
- Composer
- 2GB+ RAM
- 50GB+ storage

### Recommended Hosting:
- VPS (DigitalOcean, Linode, AWS EC2)
- Managed WordPress hosting adapted for PHP
- Dedicated server for scale

### Backup Strategy:
- Daily database backups
- Weekly full server backups
- Offsite backup storage
- 30-day retention

---

## 11. NEXT STEPS

1. **Review & Approve Plan** - Confirm features and architecture
2. **Set Up Development Environment** - Local database, config
3. **Start Phase 1** - Foundation & core infrastructure
4. **Iterative Development** - Weekly progress reviews
5. **Testing at Each Phase** - Ensure quality
6. **Launch Preparation** - Marketing, documentation
7. **Soft Launch** - Beta dealers for feedback
8. **Official Launch** - Public availability

---

## 12. SUCCESS METRICS

### Technical KPIs:
- Page load time < 2 seconds
- 99.9% uptime
- Zero data breaches
- Mobile responsive (100% score)

### Business KPIs:
- 10 paying dealers within 3 months
- 50 paying dealers within 12 months
- 90% trial-to-paid conversion
- < 5% monthly churn
- €10K MRR within 6 months

---

## ESTIMATED TIMELINE: 23 Weeks (5.5 Months)

**Start Date:** TBD
**Soft Launch:** Week 22
**Official Launch:** Week 24

---

**Document Version:** 1.0
**Last Updated:** 2025-11-13
**Author:** Claude AI Assistant
**Project:** Car Dealer SaaS Platform
