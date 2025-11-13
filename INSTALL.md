# Car Dealer SaaS - Installation Guide

Complete installation guide for production deployment.

## Prerequisites

- **PHP 8.0+** with extensions: pdo_mysql, mbstring, gd, curl, zip
- **MySQL 8.0+** or MariaDB 10.5+
- **Composer** for dependency management
- **Apache** or **Nginx** web server
- **SSL Certificate** (Let's Encrypt recommended)

---

## Step 1: Download & Extract

```bash
cd /var/www
git clone https://github.com/yourusername/cardealer.tools.git
cd cardealer.tools
```

---

## Step 2: Install Dependencies

```bash
composer install

# Required packages will be installed:
# - stripe/stripe-php
# - google/apiclient
# - tecnickcom/tcpdf
```

---

## Step 3: Database Setup

```bash
# Create database
mysql -u root -p
```

```sql
CREATE DATABASE cardealer_saas CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON cardealer_saas.* TO 'cardealer_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Import schema
mysql -u cardealer_user -p cardealer_saas < database_install.sql

# If upgrading from older version, run migrations
mysql -u cardealer_user -p cardealer_saas < database_migrations/001_add_video_url_to_vehicles.sql
```

---

## Step 4: Configuration

```bash
# Copy and configure
cp config.example.php config.php
nano config.php
```

### Required Configuration:

```php
// Database
'database' => [
    'host' => 'localhost',
    'dbname' => 'cardealer_saas',
    'username' => 'cardealer_user',
    'password' => 'YOUR_DB_PASSWORD',
],

// Application
'app_url' => 'https://yourdom ain.com',
'app_domain' => 'yourdomain.com',

// Email (Postmark)
'postmark' => [
    'api_token' => 'YOUR_POSTMARK_TOKEN',
    'from_email' => 'noreply@yourdomain.com',
],

// SMS (Twilio)
'twilio' => [
    'account_sid' => 'YOUR_TWILIO_SID',
    'auth_token' => 'YOUR_TWILIO_TOKEN',
    'from_number' => '+1234567890',
],

// WhatsApp Business API
'whatsapp' => [
    'access_token' => 'YOUR_WHATSAPP_TOKEN',
    'phone_number_id' => 'YOUR_PHONE_ID',
],

// Google Calendar
'google_calendar' => [
    'client_id' => 'YOUR_GOOGLE_CLIENT_ID',
    'client_secret' => 'YOUR_GOOGLE_SECRET',
    'redirect_uri' => 'https://yourdomain.com/calendar/google-callback',
],

// Stripe
'stripe' => [
    'secret_key' => 'sk_live_YOUR_KEY',
    'publishable_key' => 'pk_live_YOUR_KEY',
],
```

---

## Step 5: File Permissions

```bash
# Create upload directories
mkdir -p public/uploads/{vehicles,invoices,pdfs}
mkdir -p logs

# Set permissions
chmod -R 755 public/uploads
chmod -R 755 logs
chown -R www-data:www-data public/uploads logs

# On CentOS/RHEL
chown -R apache:apache public/uploads logs
```

---

## Step 6: Web Server Configuration

### Apache (with .htaccess)

```apache
<VirtualHost *:443>
    ServerName yourdomain.com
    DocumentRoot /var/www/cardealer.tools/public

    <Directory /var/www/cardealer.tools/public>
        AllowOverride All
        Require all granted
    </Directory>

    SSLEngine on
    SSLCertificateFile /path/to/cert.pem
    SSLCertificateKeyFile /path/to/key.pem
</VirtualHost>
```

### Nginx

```nginx
server {
    listen 443 ssl;
    server_name yourdomain.com;
    root /var/www/cardealer.tools/public;
    index index.php;

    ssl_certificate /path/to/cert.pem;
    ssl_certificate_key /path/to/key.pem;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.0-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

---

## Step 7: Create Super Admin User

```bash
# Generate password hash
php -r "echo password_hash('YourSecurePassword123', PASSWORD_BCRYPT);"
# Copy the output hash

mysql -u cardealer_user -p cardealer_saas
```

```sql
-- Create company
INSERT INTO core_company (company_name, company_email, subdomain, status, trial_ends_at, created_date)
VALUES ('Platform Admin', 'admin@yourdomain.com', 'admin', 'active', DATE_ADD(NOW(), INTERVAL 365 DAY), NOW());

-- Create super admin (replace PASTE_HASH_HERE with hash from above)
INSERT INTO users (company_id, email, password_hash, first_name, last_name, user_type, created_date)
VALUES (1, 'admin@yourdomain.com', 'PASTE_HASH_HERE', 'Admin', 'User', 10, NOW());

-- Verify
SELECT user_id, email, first_name, last_name, user_type FROM users;
SELECT company_id, company_name, status, trial_ends_at FROM core_company;
```

---

## Step 8: Setup Cron Jobs

```bash
# Make scripts executable
chmod +x cron/*.php

# Edit crontab
crontab -e

# Add these lines (adjust paths):
0 * * * * /usr/bin/php /var/www/cardealer.tools/cron/stock-alerts.php >> /var/www/cardealer.tools/logs/stock-alerts.log 2>&1
0 0 * * * /usr/bin/php /var/www/cardealer.tools/cron/subscription-check.php >> /var/www/cardealer.tools/logs/subscriptions.log 2>&1
```

---

## Step 9: Test Installation

```bash
# Test PHP syntax
php -l public/index.php

# Test database connection
php -r "require 'config.php'; \$config = require 'config.php'; echo 'Config loaded successfully';"

# Start development server (testing only)
php -S localhost:8000 -t public
```

Visit: `http://localhost:8000/login`

**Default credentials:**
- Email: `admin@yourdomain.com`
- Password: `YourSecurePassword123`

---

## Step 10: Production Checklist

### Security
- [ ] Change all default passwords
- [ ] Set `debug_display => false` in config.php
- [ ] Install SSL certificate
- [ ] Configure firewall (allow 80, 443 only)
- [ ] Set up automatic backups
- [ ] Configure fail2ban for brute-force protection

### Performance
- [ ] Enable PHP OpCache
- [ ] Configure MySQL query cache
- [ ] Set up CDN for static assets (optional)
- [ ] Enable gzip compression

### Monitoring
- [ ] Set up error logging
- [ ] Configure uptime monitoring
- [ ] Set up backup verification
- [ ] Monitor disk space for uploads

### API Keys
- [ ] Test Postmark email sending
- [ ] Test Twilio SMS sending
- [ ] Test WhatsApp messaging
- [ ] Test Google Calendar OAuth
- [ ] Test Stripe payments (use test mode first)

---

## Troubleshooting

### "Class not found" errors
```bash
composer dump-autoload
composer install --no-dev --optimize-autoloader
```

### Database connection failed
```bash
# Test MySQL connection
mysql -u cardealer_user -p cardealer_saas -e "SELECT 1;"

# Check PHP MySQL extension
php -m | grep -i mysql
```

### Upload directory not writable
```bash
sudo chown -R www-data:www-data public/uploads
sudo chmod -R 755 public/uploads
```

### 404 errors on all pages
- **Apache:** Enable mod_rewrite: `sudo a2enmod rewrite && sudo systemctl restart apache2`
- **Nginx:** Check config has `try_files` directive

### White screen / 500 errors
```bash
# Enable error display temporarily
nano config.php
# Set: 'debug_display' => true

# Check error logs
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/nginx/error.log
```

---

## Post-Installation

1. **Login** to admin dashboard
2. **Configure** email templates at `/email-templates`
3. **Add** first dealership company
4. **Import** vehicle inventory via CSV
5. **Test** all integrations (email, SMS, WhatsApp)
6. **Create** test appointments and invoices

---

## Support

For issues or questions:
- Check logs in `/logs` directory
- Review error logs in web server logs
- Test individual components (database, email, etc.)

---

## Updates

```bash
cd /var/www/cardealer.tools
git pull origin main
composer install
# Run any new database migrations
mysql -u cardealer_user -p cardealer_saas < database_migrations/NEW_MIGRATION.sql
```

---

**Installation complete! Your Car Dealer SaaS platform is ready for production.**
