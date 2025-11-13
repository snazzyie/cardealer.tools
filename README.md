# Car Dealer SaaS Platform

A comprehensive multi-tenant car dealership management platform built with PHP. Manage your dealership's inventory, customers, sales, and operations all in one place.

## ✅ **PLATFORM STATUS: PRODUCTION READY**

**All major features implemented and functional:**
- ✅ Multi-tenant architecture with company isolation
- ✅ Complete vehicle management with CSV import
- ✅ Full CRM with sales pipeline
- ✅ Google Calendar two-way sync (OAuth2)
- ✅ WhatsApp Business API integration
- ✅ Twilio SMS integration
- ✅ Postmark email automation
- ✅ Invoice generation with PDF export
- ✅ Subscription management (Stripe)
- ✅ Unified communications inbox
- ✅ Reports & analytics dashboard
- ✅ Super admin platform management

**Total Implementation:**
- 75+ PHP files
- 9,000+ lines of code
- 27 database tables
- 9 function libraries
- 18+ admin modules
- Full SaaS billing & communications

## 🚀 Complete Feature List

### Phase 1: Foundation
- **Authentication System** - Secure login, registration, session management
- **User Management** - 5-tier permission system (Guest, Registered, Company, Paid, Super Admin)
- **Dashboard** - Stats overview with vehicles, leads, enquiries, and sales
- **Error Pages** - Professional 403, 404, 500 error handling

### Phase 2: Company Management
- **Company Profiles** - Complete business information management
- **Multi-Tenancy** - Subdomain and custom domain support
- **Branding** - Logo, colors, social media customization
- **Trial System** - 14-day trial with status tracking

### Phase 3: Vehicle Inventory
- **Vehicle Management** - Complete CRUD with 36 specification fields
- **CSV Import** - Bulk import vehicles with flexible column mapping
- **Image Gallery** - Multiple images per vehicle with primary image selection
- **Search & Filters** - Advanced filtering by make, fuel, transmission, price, etc.
- **Status Tracking** - Available, reserved, sold, coming soon
- **Featured Listings** - Highlight premium vehicles

### Phase 4: Public Website
- **Vehicle Listings** - Branded public-facing vehicle catalog
- **Advanced Search** - Filter by make, fuel type, transmission, price range
- **Responsive Design** - Mobile-first Bootstrap 5 interface
- **Company Branding** - Automatic logo, colors, contact info display

### Phase 5: Enquiry System
- **Contact Forms** - Vehicle enquiry forms on detail pages
- **Auto Lead Creation** - Enquiries automatically become CRM leads
- **Customer Tracking** - Email, phone, vehicle interest captured

### Phase 6: CRM & Lead Management
- **Sales Pipeline** - 7-stage Kanban board (New → Won/Lost)
- **Lead Tracking** - Customer information, vehicle interest, notes
- **Staff Assignment** - Assign leads to sales team members
- **Conversion Analytics** - Win/loss tracking and conversion rates
- **Activity History** - Log all interactions with leads

### Phase 7: Calendar & Appointments
- **Monthly Calendar** - Visual calendar grid with all appointments
- **Appointment Booking** - Schedule test drives, consultations, viewings
- **Availability Checking** - Prevent double-booking
- **Customer Management** - Auto-create customer records
- **Staff Assignment** - Assign appointments to sales staff
- **Google Calendar Integration** - Full OAuth2 two-way sync with automatic token refresh
- **Calendar Invites** - Customers receive Google Calendar invites automatically

### Phase 8: Subscriptions & Billing
- **Subscription Plans** - Starter, Professional, Enterprise tiers
- **Stripe Integration** - Complete checkout and billing flow
- **Usage Limits** - Vehicle and user limits per plan
- **Trial Management** - 14-day free trial tracking
- **Upgrade/Downgrade** - Plan switching functionality
- **Webhook Handling** - Automatic subscription status updates
- **Usage Dashboard** - Real-time feature usage tracking
- **Cancellation Flow** - Self-service subscription cancellation

### Phase 9: Invoicing & Payments
- **Sales Invoices** - Vehicle sale invoices with trade-ins, deposits, VAT
- **Service Invoices** - Line-item service and repair invoices
- **Auto-Numbering** - Generate unique invoice numbers (SALE-202411-0001)
- **PDF Generation** - Professional PDF invoices with company branding
- **Email Invoices** - Send PDF invoices directly to customers
- **Payment Tracking** - Status management (pending, sent, paid, cancelled)
- **Revenue Analytics** - Sales, pending payments, service revenue
- **Stripe Ready** - Deposit recording system

### Phase 10: Communications & Automation
- **WhatsApp Business API** - Send messages, templates, media sharing
- **Twilio SMS Integration** - Two-way SMS messaging with delivery tracking
- **Postmark Email** - Transactional emails with tracking
- **Email Templates** - Customizable templates with merge tags
- **Unified Inbox** - All channels (email, SMS, WhatsApp) in one view
- **Automated Workflows** - Enquiry notifications, abandoned follow-ups, reminders
- **Communication Logging** - Full history of all customer interactions

### Phase 11: Finance Calculator
- **Interactive Widget** - Real-time payment calculations on vehicle pages
- **Amortization Formula** - Accurate monthly payment calculation
- **Adjustable Parameters** - Deposit, APR, term length sliders
- **Payment Breakdown** - Shows finance amount, interest, total payable
- **Mobile Responsive** - Works on all device sizes

### Phase 12: Reports & Analytics
- **Sales Reports** - Revenue tracking with date filtering
- **Enquiry Analytics** - Lead source and conversion tracking
- **Lead Pipeline Stats** - Breakdown by status (new, contacted, won, lost)
- **Popular Vehicles** - Most enquired and viewed vehicles
- **Appointment Reports** - Scheduled, completed, cancelled stats
- **Conversion Rates** - Win/loss percentage tracking
- **Date Range Filtering** - Custom date range analysis

### Phase 13: Super Admin Dashboard
- **Platform Management** - Oversee all dealer companies
- **System-Wide Stats** - Total companies, users, vehicles, leads
- **Company Status Tracking** - Trial, active, suspended counts
- **Trial Monitoring** - Track trial expiration dates
- **Impersonation** - Login as company users for support
- **Platform Health** - Overall system metrics

## 📋 Technical Stack

- **Backend:** PHP 8.0+ (Procedural, no framework)
- **Database:** MySQL 8.0+ with PDO
- **Frontend:** Bootstrap 5, Vanilla JS
- **Icons:** Bootstrap Icons
- **Architecture:** Multi-tenant with company isolation
- **Routing:** Simple array-based routing
- **Security:** Prepared statements, password hashing, CSRF protection

## 🗄️ Database Schema

27 tables including:
- `users` - User accounts with permission levels
- `core_company` - Dealer company profiles
- `vehicles` - Vehicle inventory (36 fields)
- `vehicle_images` - Image gallery
- `crm_leads` - Sales pipeline
- `crm_activities` - Lead interaction history
- `calendar_appointments` - Appointment scheduling
- `customers` - Customer database
- `sales_invoices` - Vehicle sale invoices
- `service_invoices` - Service/repair invoices
- `service_items` - Invoice line items
- `vehicle_deposits` - Stripe deposit tracking
- `subscriptions` - Stripe subscription management
- `enquiries` - Website enquiries
- `communications` - Unified inbox messages
- `email_templates` - Email automation templates

## 🚦 Installation

1. **Database Setup:**
   ```bash
   mysql -u root -p < database_install.sql
   ```

2. **Configuration:**
   Update `config.php` with your database credentials:
   ```php
   'database' => [
       'host' => 'localhost',
       'dbname' => 'cardealer_saas',
       'username' => 'your_username',
       'password' => 'your_password',
   ]
   ```

3. **Web Server:**
   Point your web server to the `public/` directory.
   Apache `.htaccess` file is included for URL rewriting.

4. **File Permissions:**
   ```bash
   chmod 755 app/
   chmod 755 public/
   ```

## 🔐 User Roles

- **0 - Guest:** Public access only
- **1 - Registered:** Basic dashboard access
- **2 - Company:** Full dealership management
- **3 - Paid:** Premium features (subscriptions)
- **10 - Super Admin:** Platform administration

## 🎨 Customization

Each company can customize:
- Logo and favicon
- Primary and secondary colors
- Social media links (Facebook, Instagram, Twitter, LinkedIn)
- Subdomain (dealer.cardealer.tools)
- Custom domain support

## 📊 Key Metrics

- **Total Files:** 75+ PHP files
- **Lines of Code:** ~9,000+ lines
- **Database Tables:** 27 tables
- **Function Libraries:** 9 modules (core, company, vehicles, CRM, calendar, invoices, subscriptions, communications, PDF)
- **Admin Views:** 18+ modules (dashboard, vehicles, CRM, calendar, invoices, subscriptions, reports, inbox, super admin)
- **Public Views:** 5 pages (home, vehicles, detail, finance calculator, finance partners)

## 🔗 Live Integrations

The platform includes full working integrations:
- **Postmark** - Transactional email with template system and merge tags
- **Stripe** - Payment processing, subscriptions, and deposit management
- **Google Calendar** - Full OAuth2 two-way sync with automatic token refresh
- **Twilio** - SMS messaging with delivery tracking and logging
- **WhatsApp Business API** - Cloud API integration with message templates
- **TCPDF** - Professional PDF generation for invoices and documents

## 📱 Multi-Tenant Architecture

Each dealer company operates independently with:
- Unique subdomain (dealer1.cardealer.tools)
- Custom domain support (www.dealer1.com)
- Isolated data (all queries filtered by company_id)
- Individual branding
- Separate user teams

## 🎯 Target Users

- Independent car dealers
- Small to medium dealership groups
- Used car dealers
- Vehicle brokers
- Dealerships wanting to digitize operations

## 💡 Business Model

- **Trial:** 14-day free trial for new dealers
- **Subscription:** Monthly/annual subscription plans
- **Multi-Location:** Support for dealer groups
- **White Label:** Custom branding per dealer

## 📈 Roadmap (Future Enhancements)

- Visual email template editor (drag & drop)
- SMS campaign management with scheduling
- Part-exchange valuation tool
- Vehicle import from Auto Trader / DoneDeal APIs
- Advanced analytics dashboard with charts
- Mobile app (iOS/Android)
- REST API for third-party integrations
- Multi-location management for dealer groups
- Customer portal for tracking purchases
- Integration marketplace

## 🛡️ Security Features

- Password hashing (bcrypt)
- Prepared SQL statements (PDO)
- Session timeout (1 hour)
- Session regeneration on login
- Company-based data isolation
- HTTPS ready
- Input sanitization
- XSS protection

## 📞 Support

For setup or customization support, contact your development team.

## 📄 License

Proprietary - All rights reserved

---

**Built with ❤️ for modern car dealerships**

Launch Date: November 2025
Version: 1.0.0
