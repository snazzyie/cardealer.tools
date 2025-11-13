# Car Dealer SaaS - Complete Setup Guide

This guide will help you set up the Car Dealer SaaS platform from scratch.

## Prerequisites

- PHP 8.0 or higher
- MySQL 8.0 or higher
- Apache or Nginx web server
- Composer (optional, for future dependencies)

## Quick Start (Recommended)

### Step 1: Clone/Download the Project

```bash
cd /var/www/html
# Or your web server's document root
```

### Step 2: Configure Database

Edit `config.php` and update your database credentials:

```php
'database' => [
    'host' => 'localhost',
    'port' => 3306,
    'dbname' => 'cardealer_saas',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8mb4'
],
```

### Step 3: Create Database and Tables

Run the following commands in order:

```bash
# 1. Create database and all tables
mysql -u root -p < database_install.sql

# 2. Create demo company and admin user
mysql -u root -p < QUICKSTART.sql

# 3. Setup demo subdomain for public website
mysql -u root -p < setup-demo-subdomain.sql
```

### Step 4: Verify Database Setup

```bash
php verify-database.php
```

You should see:
```
✅ All required tables exist!
Companies in database: 1
```

### Step 5: Configure Web Server

#### For Apache (.htaccess already included):

Ensure `mod_rewrite` is enabled:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### For Nginx:

Add this to your server block:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
    fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
    fastcgi_index index.php;
    include fastcgi_params;
}
```

### Step 6: Set Permissions

```bash
# Make uploads directory writable
chmod -R 775 public/uploads
chown -R www-data:www-data public/uploads

# Make logs writable (if you create a logs directory)
mkdir -p storage/logs
chmod -R 775 storage/logs
```

### Step 7: Configure DNS (Local Development)

Add to `/etc/hosts`:

```
127.0.0.1   app.cardealer.dev
127.0.0.1   demo.cardealer.dev
```

Or use `.test` or `.local` domain for local development.

### Step 8: Test Login

1. Visit: `https://app.cardealer.dev/login`
2. Login with:
   - Email: `admin@cardealer.tools`
   - Password: `password`
3. You should be redirected to `/dash`

### Step 9: Test Public Website

1. Visit: `https://demo.cardealer.dev`
2. You should see the public dealer website
3. Should NOT get 404 or "DEPLOYMENT_NOT_FOUND" error

---

## Detailed Setup (Existing Database)

If you already have a database set up and need to apply schema fixes:

### Apply Schema Fixes

```bash
mysql -u root -p cardealer_saas < database_schema_fixes.sql
```

This file fixes:
- ✅ Enquiries table: Splits `customer_name` into `first_name` and `last_name`
- ✅ Enquiries table: Adds `last_contact` and `converted_to_lead_id` columns
- ✅ Users table: Adds `status` and `profile_image` columns
- ✅ Customers table: Adds `country`, `notes`, `driving_licence_number` columns
- ✅ Sales Invoices: Renames `total` to `total_amount`
- ✅ Sales Invoices: Adds `sale_price`, `trade_in_value`, `discount_amount`, `profit_margin`, `salesperson_id`
- ✅ CRM Leads: Renames `last_contact_date` to `last_contact`
- ✅ Calendar Appointments: Adds `customer_name`, `customer_email`, `customer_phone` for non-customer appointments

---

## Configuration

### 1. Email Configuration (Postmark)

Edit `config.php`:

```php
'postmarkapp' => [
    'server_api' => 'YOUR_POSTMARK_SERVER_API_TOKEN',
    'email_domain' => 'yourdomain.com',
    'from_email' => 'noreply@yourdomain.com',
    'from_name' => 'Your Dealership Name'
],
```

### 2. Stripe Configuration (Optional - for subscriptions)

```php
'stripe' => [
    'publishable_key' => 'pk_test_YOUR_KEY',
    'secret_key' => 'sk_test_YOUR_KEY',
],
```

### 3. AWS S3 Configuration (Optional - for image storage)

```php
'aws_s3' => [
    'access_key' => 'YOUR_AWS_ACCESS_KEY',
    'secret_key' => 'YOUR_AWS_SECRET_KEY',
    'region' => 'eu-west-1',
    'bucket' => 'your-bucket-name',
],
```

### 4. SMS Configuration (Optional - Twilio)

```php
'twilio' => [
    'account_sid' => 'YOUR_TWILIO_ACCOUNT_SID',
    'auth_token' => 'YOUR_TWILIO_AUTH_TOKEN',
    'phone_number' => '+353123456789',
],
```

---

## Default Login Credentials

After running `QUICKSTART.sql`:

- **Email**: `admin@cardealer.tools`
- **Password**: `password`
- **User Type**: 10 (Super Admin)
- **Company**: Demo Dealership Ltd

**⚠️ IMPORTANT**: Change these credentials immediately in production!

---

## Database Tables Overview

The platform includes **27 tables**:

### Core Tables
- `users` - User accounts and authentication
- `core_company` - Dealer companies
- `core_invoices` - Invoice tracking
- `core_side_menu` - Navigation menu items
- `core_user_permissions` - Permission system
- `core_system_settings` - System configuration
- `core_activity_log` - Audit trail

### Vehicle Management
- `vehicles` - Vehicle inventory
- `vehicle_images` - Vehicle photos
- `vehicle_features` - Feature catalog
- `vehicle_deposits` - Customer deposits

### CRM
- `crm_leads` - Sales leads
- `crm_activities` - Lead activity tracking
- `customers` - Customer database
- `enquiries` - Website enquiries

### Calendar
- `calendar_appointments` - Appointments and test drives

### Invoicing
- `sales_invoices` - Vehicle sales invoices
- `service_invoices` - Service/repair invoices
- `service_items` - Service invoice line items
- `service_catalog` - Parts and labor catalog

### Subscriptions
- `subscriptions` - SaaS subscriptions

### Communication
- `communications` - Message history
- `email_templates` - Email template library
- `stock_alerts` - Customer stock alert subscriptions

### Website
- `website_settings` - Public website configuration
- `website_pages` - Custom pages
- `website_menus` - Navigation menus

---

## Troubleshooting

### Issue: 404 Error on demo.cardealer.dev

**Solution:**
```sql
UPDATE core_company SET subdomain='demo' WHERE company_id=1;
```

### Issue: Cannot login - "Invalid credentials"

**Reset password to 'password':**
```sql
UPDATE users
SET password_hash='$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE email='admin@cardealer.tools';
```

### Issue: Database table missing

**Run verification:**
```bash
php verify-database.php
```

**Recreate all tables:**
```bash
mysql -u root -p cardealer_saas < database_install.sql
```

### Issue: SQL Error - Unknown column 'first_name' in enquiries

**Apply schema fixes:**
```bash
mysql -u root -p cardealer_saas < database_schema_fixes.sql
```

### Issue: SQL Error - Unknown column 'total_amount' in sales_invoices

Same fix as above - run `database_schema_fixes.sql`

### Issue: Calendar appointments not saving

Ensure calendar_appointments table has customer contact fields:
```sql
ALTER TABLE calendar_appointments
    ADD COLUMN customer_name VARCHAR(255) AFTER description,
    ADD COLUMN customer_email VARCHAR(255) AFTER customer_name,
    ADD COLUMN customer_phone VARCHAR(50) AFTER customer_email;
```

---

## Post-Installation Steps

### 1. Change Admin Password

1. Login as admin
2. Go to `/users/edit?id=1`
3. Change password
4. Update email address

### 2. Configure Company Details

1. Go to `/company/edit`
2. Update:
   - Company name
   - Email address
   - Phone number
   - Address
   - Subdomain (e.g., `yourcompany`)

### 3. Enable Public Website

1. Go to `/website/settings`
2. Toggle "Website Enabled"
3. Configure SEO settings
4. Add logo and branding

### 4. Add Vehicles

1. Go to `/vehicles/new`
2. Add vehicle details
3. Upload images at `/vehicles/images?id=X`
4. Mark images as primary

### 5. Create Additional Users

1. Go to `/users/new`
2. Create sales staff accounts
3. Assign appropriate roles:
   - Owner (full access)
   - Manager (full access except company settings)
   - Sales (CRM, vehicles, customers)
   - Receptionist (appointments, enquiries)

---

## Security Checklist

- [ ] Change default admin password
- [ ] Set `debug_display => false` in `config.php` for production
- [ ] Enable HTTPS/SSL certificate
- [ ] Configure firewall rules
- [ ] Set appropriate file permissions (755 for directories, 644 for files)
- [ ] Enable database backups (daily recommended)
- [ ] Configure fail2ban for brute force protection
- [ ] Update all API keys and secrets in `config.php`

---

## Next Steps

See `TESTING.md` for comprehensive testing guide covering:
- ✅ Vehicle Management (CRUD, images, import)
- ✅ CRM & Lead Management
- ✅ Calendar & Appointments
- ✅ Customer Management
- ✅ Enquiry System
- ✅ Invoicing (Sales & Service)
- ✅ Public Dealer Website
- ✅ Email Templates
- ✅ Stock Alerts
- ✅ Website Settings
- ✅ User Management
- ✅ Super Admin Features

---

## Support & Documentation

- **Testing Guide**: `TESTING.md` - Complete testing checklist
- **Database Schema**: `database_install.sql` - Full schema definition
- **Schema Fixes**: `database_schema_fixes.sql` - Migration script
- **Quick Start Data**: `QUICKSTART.sql` - Demo data
- **Verification**: `verify-database.php` - Database checker

---

**Last Updated**: November 2024
**Version**: 1.0.0
