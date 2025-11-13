-- Car Dealer SaaS - Quick Start SQL
-- Run these commands after importing database_install.sql

-- Create initial demo company
INSERT INTO core_company (
    company_name,
    company_email,
    subdomain,
    status,
    trial_ends_at,
    created_date
) VALUES (
    'Demo Dealership',
    'admin@cardealer.tools',
    'demo',
    'trial',
    DATE_ADD(NOW(), INTERVAL 14 DAY),
    NOW()
);

-- Create super admin user
-- First generate password hash: php -r "echo password_hash('password', PASSWORD_BCRYPT);"
-- Default password below is: password
-- CHANGE THIS IMMEDIATELY AFTER FIRST LOGIN!

INSERT INTO users (
    company_id,
    email,
    password_hash,
    first_name,
    last_name,
    user_type,
    permission_level,
    created_date
) VALUES (
    1,
    'admin@cardealer.tools',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
    'Super',
    'Admin',
    3,
    10,
    NOW()
);

-- Verify installation
SELECT user_id, email, first_name, last_name, permission_level FROM users;
SELECT company_id, company_name, subdomain, status, trial_ends_at FROM core_company;

-- SUCCESS! You can now login at /login
-- Email: admin@cardealer.tools
-- Password: password
-- CHANGE PASSWORD IMMEDIATELY!
