-- Setup Demo Subdomain for Public Website Testing
-- Run this after QUICKSTART.sql to enable the public website at demo.cardealer.dev

-- Update the first company to have subdomain='demo'
UPDATE core_company SET subdomain = 'demo' WHERE company_id = 1;

-- Verify
SELECT company_id, company_name, subdomain, domain, status FROM core_company WHERE company_id = 1;

-- SUCCESS! You can now access the public dealer website at:
-- https://demo.cardealer.dev
