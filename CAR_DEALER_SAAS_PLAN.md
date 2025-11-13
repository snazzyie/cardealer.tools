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

### 1.8 CRM & Lead Management
- **Lead Pipeline**
  - Kanban board view (New, Contacted, Qualified, Negotiating, Won, Lost)
  - Drag-and-drop lead status updates
  - Lead scoring system
  - Lead source tracking (Website, Phone, Walk-in, Referral, Facebook, etc.)
  - Automated lead assignment rules
  - Lead activity timeline
  - Lead conversion tracking

- **Contact Management**
  - Unified customer/lead database
  - Contact history (calls, emails, meetings, notes)
  - Contact segmentation & tagging
  - Duplicate detection & merging
  - Bulk actions (assign, tag, export)
  - Custom fields for contacts

- **Communication Tracking**
  - Email tracking (sent, opened, clicked)
  - Call logging
  - WhatsApp message history
  - SMS message history
  - Meeting notes
  - Follow-up reminders
  - Communication templates

- **Task Management**
  - Task creation & assignment
  - Task due dates & priorities
  - Task categories (Call, Email, Meeting, Follow-up)
  - Task completion tracking
  - Overdue task alerts
  - Daily task digest emails

### 1.9 Calendar & Appointments
- **Appointment Booking**
  - Test drive scheduling
  - Service booking
  - Sales consultation booking
  - Vehicle viewing appointments
  - Public booking widget (embed on website)
  - Availability management per user
  - Buffer time between appointments
  - Booking confirmation emails

- **Google Calendar Integration**
  - Two-way sync with Google Calendar
  - Automatic event creation in Google Calendar
  - Update/delete sync
  - Multi-user calendar support
  - Calendar sharing
  - Conflict detection
  - Time zone handling

- **Calendar Features**
  - Day/Week/Month view
  - Color-coded by appointment type
  - Drag-and-drop rescheduling
  - Appointment reminders (email, SMS, WhatsApp)
  - Recurring appointments
  - No-show tracking
  - Cancellation management

### 1.10 Invoicing & Payments
- **Vehicle Sale Invoices**
  - Automatic invoice generation on sale
  - Invoice templates (customizable)
  - Line items (vehicle, extras, delivery, trade-in deduction)
  - VAT calculation (standard, margin, exempt)
  - Deposit tracking
  - Balance due calculation
  - Payment status (Unpaid, Partial, Paid)
  - Invoice PDF generation
  - Email invoice to customer

- **Service Invoices**
  - Manual invoice creation
  - Service item library (oil change, tire replacement, diagnostics, etc.)
  - Parts inventory tracking
  - Labor time tracking
  - Hourly rates per technician
  - Multiple payment methods
  - Partial payment support
  - Invoice history per customer

- **Deposit Management (Stripe)**
  - Take deposits to reserve vehicles
  - Configurable deposit amounts (fixed or percentage)
  - Stripe Payment Intent API
  - Deposit refund workflow
  - Deposit applied to final invoice
  - Deposit expiry (auto-release after X days)
  - Deposit receipt email

- **Payment Processing**
  - Stripe Terminal integration (card readers)
  - Cash payments
  - Bank transfer
  - Finance company payments
  - Split payments (deposit + balance)
  - Payment history log
  - Receipt generation

### 1.11 Communication Hub
- **Postmark CRM Integration**
  - Automated email workflows
  - Trigger emails on lead status change
  - Abandoned enquiry follow-up (auto-email after 24h)
  - New stock alerts (matching saved searches)
  - Birthday/anniversary emails
  - Service reminder emails (NCT/MOT due)
  - Payment reminder emails
  - Email templates with merge tags
  - Email analytics (open rate, click rate)
  - Suppression list management

- **WhatsApp Integration**
  - WhatsApp Business API integration
  - Send messages to customers
  - Template messages (approved by WhatsApp)
  - Receive messages from customers
  - WhatsApp chat widget on website
  - Message templates (enquiry response, appointment confirmation, etc.)
  - WhatsApp notification for new enquiries
  - WhatsApp notification for appointments
  - Media sharing (vehicle images, documents)

- **SMS Integration (Twilio)**
  - SMS notifications
  - Appointment reminders via SMS
  - Test drive confirmations
  - Payment reminders
  - Two-way SMS conversations
  - SMS templates
  - SMS delivery tracking
  - Opt-out management

- **Unified Inbox**
  - All communication channels in one place
  - Email, WhatsApp, SMS in single conversation view
  - Assign conversations to staff
  - Internal notes on conversations
  - Conversation status (Open, Pending, Resolved)
  - Search conversations
  - Filter by channel, date, staff

### 1.12 Admin Features
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
  - CRM lead pipeline
  - Enquiry inbox with status tracking
  - Finance applications dashboard
  - Calendar & appointments
  - Invoice management
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
  "ibericode/vat": "^2.0",
  "twilio/sdk": "^7.2",
  "netflie/whatsapp-cloud-api": "^2.0",
  "spatie/laravel-google-calendar": "^3.5",
  "spatie/browsershot": "^3.59"
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

-- CRM Leads (Enhanced from enquiries)
CREATE TABLE crm_leads (
    lead_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT,
    vehicle_id INT,

    -- Lead Info
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50),
    whatsapp_number VARCHAR(50),

    -- Lead Status
    status ENUM('new', 'contacted', 'qualified', 'test-drive-booked', 'negotiating', 'won', 'lost') DEFAULT 'new',
    lead_source VARCHAR(100),
    lead_score INT DEFAULT 0,

    -- Assignment
    assigned_to INT,
    assigned_date DATETIME,

    -- Lead Details
    interested_vehicle_type VARCHAR(100),
    budget_min DECIMAL(10,2),
    budget_max DECIMAL(10,2),
    timeframe VARCHAR(50),
    trade_in_interest TINYINT DEFAULT 0,
    finance_interest TINYINT DEFAULT 0,

    -- Tracking
    first_contact_date DATETIME,
    last_contact_date DATETIME,
    next_followup_date DATETIME,
    won_date DATETIME,
    lost_date DATETIME,
    lost_reason VARCHAR(255),

    -- Meta
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
);

-- CRM Activities (Calls, Emails, Meetings, Notes)
CREATE TABLE crm_activities (
    activity_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    lead_id INT,
    customer_id INT,
    user_id INT NOT NULL,

    -- Activity Details
    activity_type ENUM('call', 'email', 'whatsapp', 'sms', 'meeting', 'note', 'task') NOT NULL,
    subject VARCHAR(255),
    description TEXT,

    -- Date/Time
    activity_date DATETIME NOT NULL,
    duration_minutes INT,

    -- Call Specific
    call_direction ENUM('inbound', 'outbound'),
    call_outcome ENUM('answered', 'voicemail', 'no-answer', 'busy'),

    -- Email Specific
    email_opened TINYINT DEFAULT 0,
    email_clicked TINYINT DEFAULT 0,

    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (lead_id) REFERENCES crm_leads(lead_id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    INDEX idx_company (company_id),
    INDEX idx_lead (lead_id),
    INDEX idx_customer (customer_id),
    INDEX idx_activity_date (activity_date)
);

-- CRM Tasks
CREATE TABLE crm_tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    lead_id INT,
    customer_id INT,
    assigned_to INT NOT NULL,
    created_by INT NOT NULL,

    -- Task Details
    title VARCHAR(255) NOT NULL,
    description TEXT,
    task_type ENUM('call', 'email', 'meeting', 'follow-up', 'other') NOT NULL,
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',

    -- Dates
    due_date DATE NOT NULL,
    due_time TIME,
    completed_date DATETIME,

    -- Status
    status ENUM('pending', 'in-progress', 'completed', 'cancelled') DEFAULT 'pending',

    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (lead_id) REFERENCES crm_leads(lead_id) ON DELETE CASCADE,
    FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
    INDEX idx_company (company_id),
    INDEX idx_assigned_to (assigned_to),
    INDEX idx_due_date (due_date),
    INDEX idx_status (status)
);

-- Calendar Appointments
CREATE TABLE calendar_appointments (
    appointment_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT,
    lead_id INT,
    vehicle_id INT,
    assigned_to INT NOT NULL,

    -- Appointment Details
    appointment_type ENUM('test-drive', 'service', 'consultation', 'vehicle-viewing', 'other') NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),

    -- Date/Time
    start_datetime DATETIME NOT NULL,
    end_datetime DATETIME NOT NULL,
    timezone VARCHAR(50) DEFAULT 'Europe/Dublin',

    -- Status
    status ENUM('scheduled', 'confirmed', 'completed', 'no-show', 'cancelled') DEFAULT 'scheduled',

    -- Google Calendar
    google_calendar_event_id VARCHAR(255),
    google_calendar_synced TINYINT DEFAULT 0,
    last_synced DATETIME,

    -- Reminders
    reminder_sent TINYINT DEFAULT 0,
    reminder_sent_date DATETIME,

    -- Notes
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
);

-- Sales Invoices
CREATE TABLE sales_invoices (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT NOT NULL,
    vehicle_id INT,

    -- Invoice Details
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE,

    -- Customer Info (snapshot at time of invoice)
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255),
    customer_phone VARCHAR(50),
    customer_address TEXT,

    -- Invoice Line Items (stored as JSON)
    line_items JSON NOT NULL,

    -- Amounts
    subtotal DECIMAL(10,2) NOT NULL,
    vat_rate DECIMAL(5,2) DEFAULT 23.00,
    vat_amount DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,
    deposit_amount DECIMAL(10,2) DEFAULT 0,
    balance_due DECIMAL(10,2) NOT NULL,

    -- Payment
    payment_status ENUM('unpaid', 'partial', 'paid', 'refunded') DEFAULT 'unpaid',
    payment_method VARCHAR(50),
    paid_amount DECIMAL(10,2) DEFAULT 0,
    paid_date DATETIME,

    -- Documents
    invoice_pdf_url VARCHAR(500),

    -- Notes
    notes TEXT,
    terms TEXT,

    -- Status
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
);

-- Service Invoices
CREATE TABLE service_invoices (
    service_invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT NOT NULL,
    vehicle_registration VARCHAR(50),

    -- Invoice Details
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    invoice_date DATE NOT NULL,
    due_date DATE,

    -- Customer Info
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255),
    customer_phone VARCHAR(50),

    -- Service Details
    service_type VARCHAR(100),
    service_date DATE,
    mileage INT,
    technician_id INT,

    -- Line Items (services & parts)
    line_items JSON NOT NULL,

    -- Amounts
    labor_total DECIMAL(10,2) DEFAULT 0,
    parts_total DECIMAL(10,2) DEFAULT 0,
    subtotal DECIMAL(10,2) NOT NULL,
    vat_rate DECIMAL(5,2) DEFAULT 23.00,
    vat_amount DECIMAL(10,2) NOT NULL,
    total DECIMAL(10,2) NOT NULL,

    -- Payment
    payment_status ENUM('unpaid', 'partial', 'paid') DEFAULT 'unpaid',
    payment_method VARCHAR(50),
    paid_amount DECIMAL(10,2) DEFAULT 0,
    paid_date DATETIME,

    -- Documents
    invoice_pdf_url VARCHAR(500),

    -- Notes
    work_performed TEXT,
    notes TEXT,

    -- Status
    status ENUM('draft', 'sent', 'paid', 'void') DEFAULT 'draft',

    created_by INT,
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
    INDEX idx_company (company_id),
    INDEX idx_customer (customer_id),
    INDEX idx_invoice_number (invoice_number),
    INDEX idx_payment_status (payment_status)
);

-- Vehicle Deposits
CREATE TABLE vehicle_deposits (
    deposit_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    vehicle_id INT NOT NULL,
    customer_id INT,
    lead_id INT,

    -- Deposit Details
    deposit_amount DECIMAL(10,2) NOT NULL,
    deposit_type ENUM('fixed', 'percentage') DEFAULT 'fixed',
    deposit_percentage DECIMAL(5,2),

    -- Customer Info
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(50),

    -- Payment Info (Stripe)
    stripe_payment_intent_id VARCHAR(255),
    stripe_charge_id VARCHAR(255),
    payment_method VARCHAR(50),

    -- Status
    status ENUM('pending', 'paid', 'refunded', 'applied', 'expired') DEFAULT 'pending',
    paid_date DATETIME,
    refund_date DATETIME,
    refund_amount DECIMAL(10,2),
    refund_reason TEXT,
    expires_at DATETIME,

    -- Invoice Application
    applied_to_invoice_id INT,
    applied_date DATETIME,

    -- Documents
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
);

-- Invoice Payments
CREATE TABLE invoice_payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    invoice_id INT,
    service_invoice_id INT,

    -- Payment Details
    payment_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cash', 'card', 'bank-transfer', 'finance', 'stripe') NOT NULL,
    payment_date DATETIME NOT NULL,

    -- Stripe (if applicable)
    stripe_payment_intent_id VARCHAR(255),
    stripe_charge_id VARCHAR(255),

    -- Reference
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
);

-- Communications (Unified inbox for emails, WhatsApp, SMS)
CREATE TABLE communications (
    communication_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,
    customer_id INT,
    lead_id INT,

    -- Communication Details
    channel ENUM('email', 'whatsapp', 'sms', 'internal-note') NOT NULL,
    direction ENUM('inbound', 'outbound') NOT NULL,
    from_address VARCHAR(255),
    to_address VARCHAR(255),
    subject VARCHAR(255),
    message TEXT NOT NULL,

    -- Status
    status ENUM('open', 'pending', 'resolved') DEFAULT 'open',
    assigned_to INT,

    -- Tracking
    sent_date DATETIME,
    delivered_date DATETIME,
    read_date DATETIME,
    replied TINYINT DEFAULT 0,

    -- External IDs
    postmark_message_id VARCHAR(255),
    whatsapp_message_id VARCHAR(255),
    twilio_message_sid VARCHAR(255),

    -- Attachments
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
);

-- Email Templates
CREATE TABLE email_templates (
    template_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT,

    -- Template Details
    template_name VARCHAR(100) NOT NULL,
    template_slug VARCHAR(100) NOT NULL,
    category ENUM('transactional', 'marketing', 'crm', 'service') DEFAULT 'transactional',
    subject VARCHAR(255) NOT NULL,
    body_html TEXT NOT NULL,
    body_text TEXT,

    -- Merge Tags (available variables)
    merge_tags JSON,

    -- Status
    is_active TINYINT DEFAULT 1,
    is_system TINYINT DEFAULT 0,

    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_company (company_id),
    INDEX idx_template_slug (template_slug)
);

-- Service Items Library
CREATE TABLE service_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL,

    -- Item Details
    item_type ENUM('service', 'part') NOT NULL,
    item_code VARCHAR(50),
    item_name VARCHAR(255) NOT NULL,
    description TEXT,

    -- Pricing
    unit_price DECIMAL(10,2) NOT NULL,
    cost_price DECIMAL(10,2),

    -- Service Specific
    estimated_time_minutes INT,

    -- Part Specific
    quantity_in_stock INT DEFAULT 0,
    reorder_level INT,
    supplier VARCHAR(255),

    -- Status
    is_active TINYINT DEFAULT 1,

    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_company (company_id),
    INDEX idx_item_type (item_type),
    INDEX idx_item_code (item_code)
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

## 5. IMPLEMENTATION ROADMAP (15 PHASES - 29 WEEKS)

### Phase 1: Foundation (Weeks 1-2)

**Database & Core Infrastructure**
- [ ] Set up project structure (/app, /public, /views, /storage)
- [ ] Create config.php with database credentials
- [ ] Implement routing system (fn_core_router.php)
- [ ] Build database helper functions (fn_core_database.php)
- [ ] Create all 30+ database tables (core + new tables)
- [ ] Set up session management (fn_core_session.php)
- [ ] Implement authentication system (login, register, logout)
- [ ] Create permission levels (0=Guest, 1=Registered, 2=Company, 3=Paid, 10=Super Admin)

**Basic Admin UI**
- [ ] Set up Bootstrap 5 admin theme
- [ ] Create admin layout partials (header, sidebar, footer)
- [ ] Build dashboard homepage with widgets
- [ ] Implement dynamic side menu system

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

### Phase 6: CRM & Lead Management (Weeks 13-15)

**Lead Pipeline**
- [ ] CRM leads database (crm_leads table)
- [ ] Kanban board view (drag-and-drop columns)
- [ ] Lead CRUD operations
- [ ] Lead status management (New, Contacted, Qualified, Negotiating, Won, Lost)
- [ ] Lead scoring system
- [ ] Lead source tracking
- [ ] Automated lead assignment rules
- [ ] Lead conversion tracking

**Contact Management**
- [ ] Enhanced customer database with lead history
- [ ] Contact segmentation & tagging
- [ ] Duplicate detection & merging
- [ ] Bulk actions (assign, tag, export)
- [ ] Custom fields for contacts
- [ ] Contact activity timeline

**Activities & Tasks**
- [ ] CRM activities (calls, emails, meetings, notes)
- [ ] Activity logging from all interactions
- [ ] Task creation & assignment
- [ ] Task due dates & priorities
- [ ] Task completion tracking
- [ ] Overdue task alerts
- [ ] Daily task digest emails

**Stock Alerts & Wishlist**
- [ ] Stock alert registration form
- [ ] Alert criteria storage
- [ ] Cron job to match vehicles
- [ ] Email notification
- [ ] Unsubscribe functionality
- [ ] Wishlist/Shortlist functionality

### Phase 7: Calendar & Appointments (Weeks 16-17)

**Appointment Booking System**
- [ ] Calendar appointments table
- [ ] Test drive scheduling
- [ ] Service booking
- [ ] Sales consultation booking
- [ ] Public booking widget (embed on website)
- [ ] Availability management per user
- [ ] Buffer time between appointments
- [ ] Booking confirmation emails

**Google Calendar Integration**
- [ ] Google Calendar API setup
- [ ] OAuth2 authentication for users
- [ ] Two-way sync with Google Calendar
- [ ] Automatic event creation
- [ ] Update/delete sync
- [ ] Multi-user calendar support
- [ ] Conflict detection
- [ ] Time zone handling

**Calendar Interface**
- [ ] Day/Week/Month view
- [ ] Color-coded by appointment type
- [ ] Drag-and-drop rescheduling
- [ ] Appointment reminders (email, SMS, WhatsApp)
- [ ] Recurring appointments
- [ ] No-show tracking
- [ ] Cancellation management

### Phase 8: Billing & Subscriptions (Weeks 18-19)

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

### Phase 9: Invoicing & Payments (Weeks 20-22)

**Vehicle Sale Invoices**
- [ ] Sales invoices table
- [ ] Automatic invoice generation on vehicle sale
- [ ] Invoice templates (customizable HTML)
- [ ] Line items (vehicle, extras, delivery, trade-in deduction)
- [ ] VAT calculation (standard 23%, margin, exempt)
- [ ] Deposit tracking & application
- [ ] Balance due calculation
- [ ] Invoice PDF generation (Browsershot)
- [ ] Email invoice to customer

**Service Invoices**
- [ ] Service invoices table
- [ ] Service items library (services & parts)
- [ ] Manual invoice creation
- [ ] Parts inventory tracking
- [ ] Labor time tracking
- [ ] Hourly rates per technician
- [ ] Multiple payment methods
- [ ] Partial payment support
- [ ] Invoice history per customer

**Deposit Management**
- [ ] Vehicle deposits table
- [ ] Take deposits via Stripe (Payment Intent API)
- [ ] Configurable deposit amounts (fixed or percentage)
- [ ] Deposit refund workflow
- [ ] Deposit applied to final invoice
- [ ] Deposit expiry (auto-release after X days)
- [ ] Deposit receipt email
- [ ] Mark vehicle as "Reserved" when deposit paid

**Payment Processing**
- [ ] Invoice payments table
- [ ] Stripe Terminal integration (card readers)
- [ ] Cash payment recording
- [ ] Bank transfer tracking
- [ ] Finance company payments
- [ ] Split payments (deposit + balance)
- [ ] Payment history log
- [ ] Receipt generation & email

**Invoice Management**
- [ ] Invoice list view (sales + service)
- [ ] Invoice detail view
- [ ] Send invoice via email
- [ ] Mark invoice as paid
- [ ] Void invoice
- [ ] Invoice numbering system
- [ ] Invoice export to PDF/Excel

### Phase 10: Communications Hub (Weeks 23-24)

**Postmark CRM Integration**
- [ ] Email templates table
- [ ] Automated email workflows
- [ ] Trigger emails on lead status change
- [ ] Abandoned enquiry follow-up (24h auto-email)
- [ ] New stock alerts (matching saved searches)
- [ ] Birthday/anniversary emails
- [ ] Service reminder emails (NCT/MOT due)
- [ ] Payment reminder emails
- [ ] Email templates with merge tags
- [ ] Email analytics (open rate, click rate via Postmark)
- [ ] Suppression list management

**WhatsApp Integration**
- [ ] WhatsApp Business API setup
- [ ] Send messages to customers
- [ ] Template messages (WhatsApp approved)
- [ ] Receive messages webhook
- [ ] WhatsApp chat widget on website
- [ ] Message templates (enquiry response, appointment confirmation)
- [ ] WhatsApp notification for new enquiries
- [ ] WhatsApp appointment reminders
- [ ] Media sharing (vehicle images, documents)

**SMS Integration (Twilio)**
- [ ] Twilio account setup
- [ ] SMS notifications
- [ ] Appointment reminders via SMS
- [ ] Test drive confirmations
- [ ] Payment reminders
- [ ] Two-way SMS conversations
- [ ] SMS templates
- [ ] SMS delivery tracking
- [ ] Opt-out management

**Unified Inbox**
- [ ] Communications table
- [ ] All channels in one view (Email, WhatsApp, SMS)
- [ ] Single conversation thread per customer
- [ ] Assign conversations to staff
- [ ] Internal notes on conversations
- [ ] Conversation status (Open, Pending, Resolved)
- [ ] Search conversations
- [ ] Filter by channel, date, staff
- [ ] Real-time notifications

### Phase 11: SEO & Marketing (Weeks 25-26)

**SEO Tools**
- [ ] Auto-generated meta tags
- [ ] Schema.org markup (Car, LocalBusiness, Review)
- [ ] XML sitemap generation
- [ ] SEO-friendly URLs with slugs
- [ ] Open Graph tags (Facebook)
- [ ] Twitter Card tags
- [ ] Canonical URLs
- [ ] robots.txt
- [ ] sitemap.xml cron job (daily regeneration)

**Email Marketing**
- [ ] Newsletter subscription
- [ ] Email template system
- [ ] New stock alerts (weekly digest)
- [ ] Postmark integration (already done in Phase 10)
- [ ] Email preferences center
- [ ] Unsubscribe handling

**Social Media**
- [ ] Facebook Page integration
- [ ] Instagram feed display
- [ ] Social sharing buttons
- [ ] Auto-post new vehicles to Facebook (optional)

**Analytics**
- [ ] Google Analytics setup
- [ ] Google Tag Manager integration
- [ ] Conversion tracking (enquiries, test drives, finance apps)
- [ ] Custom events (vehicle views, searches)
- [ ] Popular vehicles tracking
- [ ] Dashboard widget with key metrics

### Phase 12: Reports & Analytics (Week 27)

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

### Phase 13: Super Admin Features (Week 28)

**Super Admin Dashboard**
- [ ] All dealers list with search/filter
- [ ] Dealer detail view (subscription, usage, activity)
- [ ] Impersonate dealer (login as dealer)
- [ ] Subscription management (manual activation, extend trial)
- [ ] System settings (site name, support email, currency)
- [ ] Feature flags (enable/disable per dealer)
- [ ] Email template management (system-wide)
- [ ] Activity logs (audit trail)
- [ ] Database backup tools

**Support System**
- [ ] Support ticket system
- [ ] Ticket categories (Billing, Technical, Feature Request, Bug)
- [ ] Ticket status (Open, In Progress, Waiting, Resolved, Closed)
- [ ] Internal notes (not visible to dealer)
- [ ] Email notifications
- [ ] Ticket priority
- [ ] Canned responses

**System Monitoring**
- [ ] System health dashboard
- [ ] Server resources (CPU, memory, disk)
- [ ] Database size tracking
- [ ] Error logs viewer
- [ ] Email delivery monitoring
- [ ] API usage tracking

### Phase 14: Testing & Optimization (Weeks 29-30)

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

**Testing (Continued)**
- [ ] CRM lead pipeline functionality
- [ ] Calendar appointments & Google Calendar sync
- [ ] Invoice generation (sales & service)
- [ ] Deposit payments via Stripe
- [ ] WhatsApp & SMS integration
- [ ] Email workflows automation
- [ ] Unified inbox functionality
- [ ] Multi-user/multi-tenant isolation
- [ ] Security audit (SQL injection, XSS, CSRF)

**Optimization**
- [ ] Database indexing (all 30+ tables)
- [ ] Query optimization (slow query log)
- [ ] Image compression & lazy loading
- [ ] Caching strategy (session, query, page)
- [ ] CDN setup for static assets (optional)
- [ ] Error logging & monitoring (Sentry/Bugsnag)
- [ ] Load testing (Apache Bench / Load Impact)
- [ ] Code cleanup & documentation

### Phase 15: Launch Preparation (Week 31)

**Documentation**
- [ ] User guide for dealers (PDF + online)
- [ ] Admin guide (vehicle management, CRM, invoicing)
- [ ] API documentation (Enterprise tier)
- [ ] Setup instructions (domain, SSL, email)
- [ ] Troubleshooting guide (common issues)
- [ ] Video tutorials (YouTube)

**Marketing Materials**
- [ ] Landing page (features, pricing, testimonials)
- [ ] Pricing page (3-tier comparison)
- [ ] Features page (detailed)
- [ ] Demo videos (2-3 minutes overview)
- [ ] Screenshots (dashboard, CRM, calendar, invoicing)
- [ ] Case studies (beta dealers if available)
- [ ] Press release draft

**Launch Checklist**
- [ ] Domain setup (DNS, nameservers)
- [ ] SSL certificates (Let's Encrypt/Cloudflare)
- [ ] Email deliverability (SPF, DKIM, DMARC records)
- [ ] Backup system (automated daily backups)
- [ ] Monitoring alerts (uptime, server health)
- [ ] Support email setup (support@cardealer.tools)
- [ ] Terms of Service (legal review)
- [ ] Privacy Policy (GDPR compliant)
- [ ] Cookie consent banner
- [ ] Payment processing (live Stripe keys)
- [ ] Postmark account (live API keys)
- [ ] Twilio account (live credentials)
- [ ] WhatsApp Business API (production access)
- [ ] Google Calendar API (production limits)
- [ ] Social media accounts (Twitter, LinkedIn, Facebook)
- [ ] Beta tester feedback implemented
- [ ] Load testing completed
- [ ] Final security audit

---

## 6. IMPLEMENTATION TIMELINE SUMMARY

| Phase | Duration | Cumulative | Deliverable |
|-------|----------|------------|-------------|
| **Phase 1** | 2 weeks | Week 2 | Foundation & Authentication |
| **Phase 2** | 2 weeks | Week 4 | Users & Company Management |
| **Phase 3** | 3 weeks | Week 7 | Vehicle Management System |
| **Phase 4** | 3 weeks | Week 10 | Public Website & Search |
| **Phase 5** | 2 weeks | Week 12 | Finance & Enquiries |
| **Phase 6** | 3 weeks | Week 15 | **CRM & Lead Management** |
| **Phase 7** | 2 weeks | Week 17 | **Calendar & Appointments** |
| **Phase 8** | 2 weeks | Week 19 | Billing & Subscriptions |
| **Phase 9** | 3 weeks | Week 22 | **Invoicing & Payments** |
| **Phase 10** | 2 weeks | Week 24 | **Communications Hub** |
| **Phase 11** | 2 weeks | Week 26 | SEO & Marketing |
| **Phase 12** | 1 week | Week 27 | Reports & Analytics |
| **Phase 13** | 1 week | Week 28 | Super Admin Features |
| **Phase 14** | 2 weeks | Week 30 | Testing & Optimization |
| **Phase 15** | 1 week | Week 31 | Launch Preparation |

**TOTAL: 31 weeks (approximately 7.5 months)**

**NEW FEATURES ADDED:**
- ✅ Phase 6: CRM for managing leads with pipeline view
- ✅ Phase 7: Calendar with Google Calendar integration
- ✅ Phase 9: Stripe deposits + Vehicle & Service invoicing
- ✅ Phase 10: Postmark automation + WhatsApp + SMS + Unified Inbox

---

## 7. MVP OPTION (Faster Launch - 16 Weeks)

If you want to launch faster with core features:

**MVP Includes:**
- Phase 1: Foundation ✅
- Phase 2: Users & Companies ✅
- Phase 3: Vehicle Management ✅
- Phase 4: Public Website ✅
- Phase 5: Enquiries only (skip finance applications) ✅
- Phase 6: Basic CRM (lead pipeline + tasks) ✅
- Phase 8: Billing & Subscriptions ✅
- Phase 14: Testing ✅

**MVP Excludes** (can add post-launch):
- Calendar & Appointments
- Invoicing & Payments
- Communications Hub (WhatsApp, SMS)
- Advanced SEO & Marketing
- Reports & Analytics

**MVP Timeline: 16 weeks (4 months)**

---

---

## 8. KEY FUNCTIONS TO IMPLEMENT

### 8.1 Vehicle Functions (fn_vehicles.php)

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

### 8.8 Email Functions (fn_core_email.php)

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

### 8.9 CRM Functions (fn_crm.php)

```php
// Lead Management
fn_crm_lead_create($data)
fn_crm_lead_update($lead_id, $data, $company_id)
fn_crm_lead_delete($lead_id, $company_id)
fn_crm_lead_get_all($company_id, $filters, $offset, $limit)
fn_crm_lead_get_by_id($lead_id, $company_id)
fn_crm_lead_get_by_status($company_id, $status)
fn_crm_lead_update_status($lead_id, $status, $company_id)
fn_crm_lead_assign($lead_id, $user_id, $company_id)
fn_crm_lead_convert_to_customer($lead_id)
fn_crm_lead_mark_as_won($lead_id, $company_id)
fn_crm_lead_mark_as_lost($lead_id, $reason, $company_id)
fn_crm_lead_calculate_score($lead_id)

// Activities
fn_crm_activity_create($lead_id, $activity_type, $data)
fn_crm_activity_get_by_lead($lead_id)
fn_crm_activity_get_by_customer($customer_id)
fn_crm_activity_get_recent($company_id, $limit)

// Tasks
fn_crm_task_create($data)
fn_crm_task_update($task_id, $data, $company_id)
fn_crm_task_complete($task_id, $company_id)
fn_crm_task_get_by_user($user_id, $filters)
fn_crm_task_get_overdue($user_id)
fn_crm_task_send_daily_digest($user_id)

// Pipeline
fn_crm_pipeline_get_counts($company_id)
fn_crm_pipeline_get_leads_by_stage($company_id, $status)
```

### 8.10 Calendar Functions (fn_calendar.php)

```php
// Appointments
fn_calendar_appointment_create($data)
fn_calendar_appointment_update($appointment_id, $data, $company_id)
fn_calendar_appointment_delete($appointment_id, $company_id)
fn_calendar_appointment_get_all($company_id, $start_date, $end_date, $user_id)
fn_calendar_appointment_get_by_id($appointment_id, $company_id)
fn_calendar_appointment_get_by_user($user_id, $start_date, $end_date)
fn_calendar_appointment_update_status($appointment_id, $status, $company_id)
fn_calendar_appointment_check_conflicts($user_id, $start_datetime, $end_datetime)

// Google Calendar Integration
fn_calendar_google_sync_appointment($appointment_id)
fn_calendar_google_create_event($appointment_data)
fn_calendar_google_update_event($google_event_id, $appointment_data)
fn_calendar_google_delete_event($google_event_id)
fn_calendar_google_auth_url($user_id)
fn_calendar_google_handle_callback($code, $user_id)

// Availability
fn_calendar_get_available_slots($user_id, $date, $duration_minutes)
fn_calendar_set_working_hours($user_id, $working_hours)

// Reminders
fn_calendar_send_appointment_reminder($appointment_id, $method)
fn_calendar_send_upcoming_reminders() // Cron job
```

### 8.11 Invoice Functions (fn_invoices.php)

```php
// Sales Invoices
fn_invoice_create_for_vehicle_sale($vehicle_id, $customer_id, $data)
fn_invoice_get_all($company_id, $filters, $offset, $limit)
fn_invoice_get_by_id($invoice_id, $company_id)
fn_invoice_get_by_customer($customer_id, $company_id)
fn_invoice_update($invoice_id, $data, $company_id)
fn_invoice_delete($invoice_id, $company_id)
fn_invoice_mark_as_sent($invoice_id, $company_id)
fn_invoice_mark_as_paid($invoice_id, $payment_data, $company_id)
fn_invoice_void($invoice_id, $company_id)
fn_invoice_generate_pdf($invoice_id)
fn_invoice_send_email($invoice_id)
fn_invoice_generate_number($company_id)

// Service Invoices
fn_service_invoice_create($customer_id, $data)
fn_service_invoice_update($service_invoice_id, $data, $company_id)
fn_service_invoice_get_all($company_id, $filters, $offset, $limit)
fn_service_invoice_get_by_id($service_invoice_id, $company_id)
fn_service_invoice_mark_as_paid($service_invoice_id, $payment_data)
fn_service_invoice_generate_pdf($service_invoice_id)
fn_service_invoice_send_email($service_invoice_id)

// Service Items
fn_service_item_create($data)
fn_service_item_update($item_id, $data, $company_id)
fn_service_item_get_all($company_id, $item_type)
fn_service_item_get_by_id($item_id, $company_id)
fn_service_item_update_stock($item_id, $quantity, $company_id)

// Invoice Payments
fn_invoice_payment_add($invoice_id, $payment_data)
fn_invoice_payment_get_history($invoice_id)
fn_invoice_calculate_balance($invoice_id)
```

### 8.12 Deposit Functions (fn_deposits.php)

```php
// Vehicle Deposits
fn_deposit_create($vehicle_id, $customer_data, $deposit_amount)
fn_deposit_process_stripe_payment($deposit_id, $payment_intent_id)
fn_deposit_get_by_vehicle($vehicle_id, $company_id)
fn_deposit_get_by_id($deposit_id, $company_id)
fn_deposit_refund($deposit_id, $reason, $company_id)
fn_deposit_apply_to_invoice($deposit_id, $invoice_id, $company_id)
fn_deposit_check_expired() // Cron job
fn_deposit_mark_vehicle_reserved($vehicle_id, $company_id)
fn_deposit_release_vehicle($vehicle_id, $company_id)
fn_deposit_generate_receipt($deposit_id)
fn_deposit_send_receipt_email($deposit_id)

// Stripe Payment Intents
fn_deposit_create_payment_intent($amount, $currency, $metadata)
fn_deposit_confirm_payment_intent($payment_intent_id)
fn_deposit_cancel_payment_intent($payment_intent_id)
```

### 8.13 Communication Functions (fn_communications.php)

```php
// Unified Inbox
fn_communication_create($channel, $direction, $data)
fn_communication_get_all($company_id, $filters, $offset, $limit)
fn_communication_get_by_customer($customer_id, $company_id)
fn_communication_get_by_lead($lead_id, $company_id)
fn_communication_update_status($communication_id, $status, $company_id)
fn_communication_assign($communication_id, $user_id, $company_id)

// Email (Postmark)
fn_email_send_via_postmark($to, $subject, $body, $template_id)
fn_email_send_with_template($template_slug, $to, $merge_data)
fn_email_track_open($postmark_message_id)
fn_email_track_click($postmark_message_id, $link_url)
fn_email_get_analytics($date_range, $company_id)

// Email Templates
fn_email_template_create($data)
fn_email_template_update($template_id, $data, $company_id)
fn_email_template_get_all($company_id, $category)
fn_email_template_get_by_slug($template_slug, $company_id)
fn_email_template_render($template_id, $merge_data)

// WhatsApp
fn_whatsapp_send_message($to, $message, $company_id)
fn_whatsapp_send_template($to, $template_name, $parameters)
fn_whatsapp_send_media($to, $media_url, $caption)
fn_whatsapp_receive_message_webhook($data)
fn_whatsapp_mark_as_read($message_id)

// SMS (Twilio)
fn_sms_send($to, $message, $company_id)
fn_sms_receive_webhook($data)
fn_sms_get_delivery_status($twilio_sid)
fn_sms_handle_optout($phone_number)

// Automated Workflows
fn_workflow_abandoned_enquiry_followup() // Cron job
fn_workflow_lead_status_change_email($lead_id, $old_status, $new_status)
fn_workflow_birthday_email() // Cron job
fn_workflow_service_reminder() // Cron job
fn_workflow_payment_reminder() // Cron job
```

---

## 9. SECURITY CONSIDERATIONS

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

## 10. PERFORMANCE OPTIMIZATION

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

## 11. THIRD-PARTY INTEGRATIONS

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

### Required (NEW):
- **Twilio** - SMS messaging
- **WhatsApp Business API** - WhatsApp integration
- **Google Calendar API** - Calendar synchronization

---

## 12. DEPLOYMENT & HOSTING

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

## 13. NEXT STEPS

1. **Review & Approve Plan** - Confirm features and architecture
2. **Set Up Development Environment** - Local database, config
3. **Start Phase 1** - Foundation & core infrastructure
4. **Iterative Development** - Weekly progress reviews
5. **Testing at Each Phase** - Ensure quality
6. **Launch Preparation** - Marketing, documentation
7. **Soft Launch** - Beta dealers for feedback
8. **Official Launch** - Public availability

---

## 14. SUCCESS METRICS

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

## ESTIMATED TIMELINE: 31 Weeks (7.5 Months)

**Start Date:** TBD
**Soft Launch:** Week 30
**Official Launch:** Week 32

**Timeline Options:**
- **Full Launch (31 weeks):** All features including CRM, Calendar, Invoicing, Communications Hub
- **MVP Launch (16 weeks):** Core features only, add advanced features post-launch

---

## NEW FEATURES SUMMARY

**Added to Original Plan:**
1. **CRM & Lead Management** (Phase 6)
   - Kanban pipeline view
   - Lead scoring & assignment
   - Activity tracking
   - Task management

2. **Calendar & Appointments** (Phase 7)
   - Google Calendar two-way sync
   - Public booking widget
   - Appointment reminders (Email, SMS, WhatsApp)

3. **Invoicing & Payments** (Phase 9)
   - Vehicle sale invoices (automatic generation)
   - Service invoices (manual entry)
   - Stripe deposit system (reserve vehicles)
   - Payment tracking & receipts

4. **Communications Hub** (Phase 10)
   - Postmark email automation & workflows
   - WhatsApp Business API integration
   - Twilio SMS integration
   - Unified inbox (Email, WhatsApp, SMS)

**Database Impact:**
- Added 14 new tables (crm_leads, calendar_appointments, sales_invoices, service_invoices, vehicle_deposits, invoice_payments, communications, email_templates, crm_activities, crm_tasks, service_items, and more)
- Total tables: 30+ (up from 16 original)

**Function Libraries Added:**
- fn_crm.php (lead & task management)
- fn_calendar.php (appointments & Google Calendar sync)
- fn_invoices.php (sales & service invoicing)
- fn_deposits.php (Stripe deposits)
- fn_communications.php (email, WhatsApp, SMS, unified inbox)

---

**Document Version:** 2.0
**Last Updated:** 2025-11-13
**Author:** Claude AI Assistant
**Project:** Car Dealer SaaS Platform
**Status:** Extended with CRM, Calendar, Invoicing & Communications features
