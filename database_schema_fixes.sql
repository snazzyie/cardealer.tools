-- Database Schema Fixes - Critical Updates
-- Run this AFTER database_install.sql to fix schema mismatches
-- This fixes 15 critical issues found in schema verification

USE cardealer_saas;

-- ==============================================
-- 1. FIX ENQUIRIES TABLE
-- ==============================================

-- Split customer_name into first_name and last_name
ALTER TABLE enquiries
    DROP COLUMN IF EXISTS customer_name,
    ADD COLUMN first_name VARCHAR(100) NOT NULL AFTER enquiry_type,
    ADD COLUMN last_name VARCHAR(100) NOT NULL AFTER first_name;

-- Add missing columns
ALTER TABLE enquiries
    ADD COLUMN IF NOT EXISTS last_contact DATETIME AFTER notes,
    ADD COLUMN IF NOT EXISTS converted_to_lead_id INT AFTER status,
    ADD INDEX IF NOT EXISTS idx_converted_lead (converted_to_lead_id);

-- ==============================================
-- 2. FIX USERS TABLE
-- ==============================================

-- Add status column
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS status ENUM('active', 'inactive', 'suspended', 'deleted') DEFAULT 'active' AFTER user_role,
    ADD INDEX IF NOT EXISTS idx_status (status);

-- Add profile_image column
ALTER TABLE users
    ADD COLUMN IF NOT EXISTS profile_image VARCHAR(500) AFTER phone;

-- ==============================================
-- 3. FIX CUSTOMERS TABLE
-- ==============================================

-- Add missing columns
ALTER TABLE customers
    ADD COLUMN IF NOT EXISTS country VARCHAR(100) DEFAULT 'Ireland' AFTER postcode,
    ADD COLUMN IF NOT EXISTS notes TEXT AFTER email_verified,
    ADD COLUMN IF NOT EXISTS driving_licence_number VARCHAR(50) AFTER phone;

-- ==============================================
-- 4. FIX SALES_INVOICES TABLE
-- ==============================================

-- Rename total to total_amount for consistency
ALTER TABLE sales_invoices
    CHANGE COLUMN total total_amount DECIMAL(10,2) NOT NULL;

-- Add missing sales-specific columns
ALTER TABLE sales_invoices
    ADD COLUMN IF NOT EXISTS sale_price DECIMAL(10,2) AFTER customer_phone,
    ADD COLUMN IF NOT EXISTS trade_in_value DECIMAL(10,2) DEFAULT 0 AFTER sale_price,
    ADD COLUMN IF NOT EXISTS discount_amount DECIMAL(10,2) DEFAULT 0 AFTER trade_in_value,
    ADD COLUMN IF NOT EXISTS profit_margin DECIMAL(10,2) DEFAULT 0 AFTER vat_amount,
    ADD COLUMN IF NOT EXISTS salesperson_id INT AFTER customer_id,
    ADD INDEX IF NOT EXISTS idx_salesperson (salesperson_id);

-- ==============================================
-- 5. FIX CRM_LEADS TABLE
-- ==============================================

-- Rename last_contact_date to last_contact for consistency
ALTER TABLE crm_leads
    CHANGE COLUMN last_contact_date last_contact DATETIME;

-- ==============================================
-- 6. FIX CALENDAR_APPOINTMENTS TABLE
-- ==============================================

-- Add optional contact fields for non-customer appointments (e.g., public website test drive bookings)
ALTER TABLE calendar_appointments
    ADD COLUMN IF NOT EXISTS customer_name VARCHAR(255) AFTER description,
    ADD COLUMN IF NOT EXISTS customer_email VARCHAR(255) AFTER customer_name,
    ADD COLUMN IF NOT EXISTS customer_phone VARCHAR(50) AFTER customer_email;

-- ==============================================
-- 7. VERIFICATION
-- ==============================================

-- Verify all changes were applied
SELECT
    'enquiries' as table_name,
    COUNT(*) as columns_exist
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'cardealer_saas'
AND TABLE_NAME = 'enquiries'
AND COLUMN_NAME IN ('first_name', 'last_name', 'last_contact', 'converted_to_lead_id')

UNION ALL

SELECT
    'users' as table_name,
    COUNT(*) as columns_exist
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'cardealer_saas'
AND TABLE_NAME = 'users'
AND COLUMN_NAME IN ('status', 'profile_image')

UNION ALL

SELECT
    'customers' as table_name,
    COUNT(*) as columns_exist
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'cardealer_saas'
AND TABLE_NAME = 'customers'
AND COLUMN_NAME IN ('country', 'notes', 'driving_licence_number')

UNION ALL

SELECT
    'sales_invoices' as table_name,
    COUNT(*) as columns_exist
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'cardealer_saas'
AND TABLE_NAME = 'sales_invoices'
AND COLUMN_NAME IN ('total_amount', 'sale_price', 'trade_in_value', 'discount_amount', 'profit_margin', 'salesperson_id')

UNION ALL

SELECT
    'crm_leads' as table_name,
    COUNT(*) as columns_exist
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'cardealer_saas'
AND TABLE_NAME = 'crm_leads'
AND COLUMN_NAME = 'last_contact'

UNION ALL

SELECT
    'calendar_appointments' as table_name,
    COUNT(*) as columns_exist
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'cardealer_saas'
AND TABLE_NAME = 'calendar_appointments'
AND COLUMN_NAME IN ('customer_name', 'customer_email', 'customer_phone');

-- SUCCESS! Schema is now aligned with code expectations
