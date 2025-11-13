# Car Dealer SaaS - Complete Testing Guide

This guide provides step-by-step testing instructions for EVERY feature in the platform.

## Prerequisites

1. **Database Setup:**
   ```bash
   mysql -u root -p < database_install.sql
   mysql -u root -p < QUICKSTART.sql
   mysql -u root -p < setup-demo-subdomain.sql
   ```

2. **Verify Database:**
   ```bash
   php verify-database.php
   ```
   Should show all 27 tables as existing.

3. **Login Credentials:**
   - Email: `admin@cardealer.tools`
   - Password: `password`
   - User Type: 10 (Super Admin)

---

## Feature Testing Checklist

### ✅ 1. Authentication & Access

**Test Login:**
- [ ] Go to `https://app.cardealer.dev/login`
- [ ] Enter email: `admin@cardealer.tools`, password: `password`
- [ ] Should redirect to `/dash`
- [ ] Should see dashboard with stats

**Test Logout:**
- [ ] Click logout
- [ ] Should redirect to `/login`
- [ ] Session should be cleared

---

### ✅ 2. Company Management

**View Company Profile:**
- [ ] Go to `/company`
- [ ] Should display company name, email, address
- [ ] Should show subscription status
- [ ] Should show stats (users, vehicles, leads, customers)

**Edit Company:**
- [ ] Go to `/company/edit`
- [ ] Change company name
- [ ] Set subdomain to `demo`
- [ ] Click Save
- [ ] Should show success message

**Test Subdomain:**
- [ ] After setting subdomain to `demo`
- [ ] Visit `https://demo.cardealer.dev`
- [ ] Should show public dealer website
- [ ] Should NOT get 404 error

---

### ✅ 3. Vehicle Management

**Add New Vehicle:**
- [ ] Go to `/vehicles/new`
- [ ] Fill in: Make, Model, Year, Price, Fuel Type, Transmission, Body Type
- [ ] Click Save
- [ ] Should redirect to `/vehicles/edit?id=X&success=1`

**Edit Vehicle:**
- [ ] Go to `/vehicles/edit?id=1`
- [ ] Change price
- [ ] Click Save
- [ ] Should show success message

**Upload Vehicle Images:**
- [ ] Go to `/vehicles/images?id=1`
- [ ] Upload an image
- [ ] Should show image in gallery
- [ ] Click "Set as Primary"
- [ ] Should mark as primary

**View Vehicle List:**
- [ ] Go to `/vehicles`
- [ ] Should show all vehicles in table
- [ ] Search should work
- [ ] Filters should work

**Delete Vehicle:**
- [ ] Go to `/vehicles/delete?id=1`
- [ ] Confirm deletion
- [ ] Vehicle should be removed

---

### ✅ 4. CRM & Lead Management

**View Pipeline:**
- [ ] Go to `/crm`
- [ ] Should show Kanban board with 7 stages
- [ ] Stages: New, Contacted, Qualified, Proposal, Negotiation, Won, Lost

**Create Lead:**
- [ ] Go to `/crm/leads/new`
- [ ] Enter: First Name, Last Name, Email, Phone
- [ ] Select vehicle (optional)
- [ ] Select assigned staff
- [ ] Click Save
- [ ] Should redirect to leads list

**Edit Lead:**
- [ ] Go to `/crm/leads/edit?id=1`
- [ ] Change status to "Contacted"
- [ ] Add notes
- [ ] Click Save
- [ ] Should update successfully

**View Lead Details:**
- [ ] Go to `/crm/leads/view?id=1`
- [ ] Should show lead information
- [ ] Should show activity timeline
- [ ] Should show vehicle details (if assigned)

**Create Task:**
- [ ] Go to `/crm/tasks`
- [ ] Click "Add Task"
- [ ] Enter task title, due date
- [ ] Assign to lead
- [ ] Click Save
- [ ] Should appear in task list

---

### ✅ 5. Calendar & Appointments

**View Calendar:**
- [ ] Go to `/calendar`
- [ ] Should show monthly calendar grid
- [ ] Should show upcoming appointments

**Create Appointment:**
- [ ] Go to `/calendar/new`
- [ ] Select type: Test Drive / Service / Consultation
- [ ] Enter customer name, email, phone
- [ ] Select date and time
- [ ] Select vehicle (for test drives)
- [ ] Click Save
- [ ] Should appear on calendar

**Edit Appointment:**
- [ ] Go to `/calendar/edit?id=1`
- [ ] Change time
- [ ] Click Save
- [ ] Should update on calendar

**Cancel Appointment:**
- [ ] Go to `/calendar/edit?id=1`
- [ ] Click "Cancel Appointment"
- [ ] Status should change to "Cancelled"

---

### ✅ 6. Customer Management

**View Customers:**
- [ ] Go to `/customers`
- [ ] Should show list of all customers
- [ ] Search should work

**View Customer Profile:**
- [ ] Go to `/customers/view?id=1`
- [ ] Should show customer details
- [ ] Should show purchase history
- [ ] Should show enquiries
- [ ] Should show appointments

---

### ✅ 7. Enquiries

**View Enquiries:**
- [ ] Go to `/enquiries`
- [ ] Should show all website enquiries
- [ ] Should show status (New, Contacted, Converted, Lost)

**View Enquiry Details:**
- [ ] Go to `/enquiries/view?id=1`
- [ ] Should show enquiry information
- [ ] Should show vehicle they enquired about
- [ ] Should have "Convert to Lead" button

**Convert to Lead:**
- [ ] On enquiry details page
- [ ] Click "Convert to Lead"
- [ ] New lead should be created
- [ ] Enquiry status should change to "Converted"

---

### ✅ 8. Invoicing

**Create Sales Invoice:**
- [ ] Go to `/invoices/sales/new`
- [ ] Select customer (or create new)
- [ ] Select vehicle
- [ ] Enter sale price
- [ ] Add trade-in value (optional)
- [ ] Add deposit (optional)
- [ ] Click Generate Invoice
- [ ] Should create invoice with number like SALE-202411-0001

**View Invoice:**
- [ ] Go to `/invoices/sales/view?id=1`
- [ ] Should display invoice details
- [ ] Click "Download PDF"
- [ ] Should generate PDF

**Create Service Invoice:**
- [ ] Go to `/invoices/service/new`
- [ ] Select customer
- [ ] Enter vehicle registration
- [ ] Add service items (labor + parts)
- [ ] Click Generate Invoice
- [ ] Should create service invoice

---

### ✅ 9. Service Workshop

**Manage Service Catalog:**
- [ ] Go to `/service/items`
- [ ] Should show list of parts and labor items
- [ ] Click "Add Item"
- [ ] Enter item code (SVC-001 or PRT-001)
- [ ] Enter name, price
- [ ] Select type: Labor / Part
- [ ] Click Save

**Create Service Invoice:**
- [ ] Go to `/service/invoice/new`
- [ ] Select customer
- [ ] Enter vehicle details
- [ ] Add service items from catalog
- [ ] Should calculate labor + parts totals
- [ ] Click Generate
- [ ] Should create invoice

---

### ✅ 10. Deposits

**Record Deposit:**
- [ ] Go to `/deposits/new`
- [ ] Select customer
- [ ] Select vehicle
- [ ] Enter deposit amount
- [ ] Enter payment method
- [ ] Click Save
- [ ] Should record deposit

**Refund Deposit:**
- [ ] Go to `/deposits`
- [ ] Click "Refund" on a deposit
- [ ] Confirm refund
- [ ] Status should change to "Refunded"

---

### ✅ 11. Reports & Analytics

**Sales Reports:**
- [ ] Go to `/reports/sales`
- [ ] Select date range
- [ ] Should show sales statistics
- [ ] Should show revenue charts

**Enquiry Reports:**
- [ ] Go to `/reports/enquiries`
- [ ] Should show enquiry statistics
- [ ] Should show conversion rates
- [ ] Should show most enquired vehicles

**Analytics Dashboard:**
- [ ] Go to `/reports/analytics`
- [ ] Should show comprehensive stats
- [ ] Should show charts and graphs

---

### ✅ 12. User Management

**Add User:**
- [ ] Go to `/users/new`
- [ ] Enter: Email, First Name, Last Name, Password
- [ ] Select user type: 1=Guest, 2=Company, 3=Paid, 10=Super Admin
- [ ] Select role: Owner, Manager, Sales, Receptionist
- [ ] Click Save
- [ ] User should be created

**Edit User:**
- [ ] Go to `/users/edit?id=1`
- [ ] Change name or role
- [ ] Click Save
- [ ] Should update successfully

---

### ✅ 13. Super Admin Features

**View All Dealers:**
- [ ] Login as super admin (user_type=10)
- [ ] Go to `/super-admin/dealers`
- [ ] Should show list of all companies
- [ ] Should show stats for each

**View Dealer Details:**
- [ ] Go to `/super-admin/dealers/view?id=1`
- [ ] Should show company details
- [ ] Should show subscription info
- [ ] Should show statistics

**Switch to Company:**
- [ ] On dealer details page
- [ ] Click "Switch to This Company"
- [ ] Should redirect to `/dash`
- [ ] Should now be operating as that company
- [ ] Top bar should show "Switched to [Company Name]"

**Switch Back:**
- [ ] Click "Switch Back to Super Admin"
- [ ] OR go to `/super-admin/switch-back`
- [ ] Should return to super admin view

---

### ✅ 14. Public Dealer Website

**Homepage:**
- [ ] Visit `https://demo.cardealer.dev`
- [ ] Should show vehicle listings
- [ ] Should show company name and logo
- [ ] Should have search and filters

**Vehicle Detail Page:**
- [ ] Click on a vehicle
- [ ] Should show full vehicle details
- [ ] Should show all images in gallery
- [ ] Should show finance calculator
- [ ] Should have enquiry form

**Submit Enquiry:**
- [ ] On vehicle detail page
- [ ] Fill in: Name, Email, Phone, Message
- [ ] Click Submit
- [ ] Should show success message
- [ ] Enquiry should appear in admin `/enquiries`

**Book Test Drive:**
- [ ] Visit `https://demo.cardealer.dev/test-drive`
- [ ] Fill in form
- [ ] Select vehicle and date/time
- [ ] Click Submit
- [ ] Should appear in admin `/calendar`

**Contact Page:**
- [ ] Visit `https://demo.cardealer.dev/contact`
- [ ] Should show contact form
- [ ] Should show dealer's address, phone, email
- [ ] Submit form should create enquiry

---

### ✅ 15. Email Templates

**Create Template:**
- [ ] Go to `/email-templates`
- [ ] Click "New Template"
- [ ] Enter name, subject, body
- [ ] Use merge tags: {{first_name}}, {{vehicle}}, etc.
- [ ] Click Save
- [ ] Should save template

**Send Email with Template:**
- [ ] Go to an enquiry or lead
- [ ] Select email template
- [ ] Should merge customer data
- [ ] Send email

---

### ✅ 16. Stock Alerts

**View Stock Alerts:**
- [ ] Go to `/stock-alerts`
- [ ] Should show all customer stock alert subscriptions
- [ ] Should show criteria: make, model, price range, etc.

**Customer Subscribe (Public):**
- [ ] Visit `https://demo.cardealer.dev/stock-alert`
- [ ] Fill in: Email, criteria
- [ ] Click Subscribe
- [ ] Should send confirmation email
- [ ] Should appear in admin stock alerts

**Unsubscribe:**
- [ ] Use unsubscribe link from email
- [ ] Should remove subscription

---

### ✅ 17. Website Settings

**Configure Website:**
- [ ] Go to `/website/settings`
- [ ] Toggle "Website Enabled"
- [ ] Set homepage title and subtitle
- [ ] Set SEO meta tags
- [ ] Add social media links
- [ ] Add Google Analytics ID
- [ ] Click Save
- [ ] Visit public site to verify changes

---

### ✅ 18. Subscriptions

**View Plans:**
- [ ] Go to `/subscriptions/plans`
- [ ] Should show 3 plans: Starter, Professional, Enterprise
- [ ] Should show features and pricing

**Upgrade Plan:**
- [ ] Click "Choose Plan"
- [ ] Should redirect to Stripe checkout
- [ ] (Requires Stripe keys configured)

---

## Common Issues & Fixes

### Issue: 404 on demo.cardealer.dev
**Fix:**
```sql
UPDATE core_company SET subdomain='demo' WHERE company_id=1;
```

### Issue: No vehicles showing on public site
**Fix:**
1. Login to admin
2. Go to `/vehicles/new`
3. Add vehicles with status='available'
4. Set vehicle images

### Issue: Database table missing
**Fix:**
```bash
php verify-database.php  # Check which tables are missing
mysql -u root -p < database_install.sql  # Recreate all tables
```

### Issue: Cannot login
**Fix:**
```sql
-- Reset password to 'password'
UPDATE users SET password_hash='$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE email='admin@cardealer.tools';
```

---

## Testing Completion Checklist

- [ ] All 18 feature sections tested
- [ ] Admin dashboard fully functional
- [ ] Public dealer website accessible
- [ ] Subdomain routing working
- [ ] All CRUD operations working
- [ ] Forms submitting correctly
- [ ] No 404 errors on defined routes
- [ ] No SQL errors
- [ ] No PHP errors

---

## Performance Testing

1. **Load Time:**
   - Dashboard should load in < 2 seconds
   - Vehicle list should load in < 3 seconds
   - Public site should load in < 2 seconds

2. **Database Queries:**
   - Check for N+1 query problems
   - Use prepared statements (already implemented)

3. **Image Loading:**
   - Images should be optimized
   - Use lazy loading for galleries

---

## Security Testing

1. **Authentication:**
   - [ ] Cannot access `/dash` without login
   - [ ] Cannot access `/super-admin` without user_type=10
   - [ ] Session expires after timeout

2. **Authorization:**
   - [ ] Cannot view other company's data
   - [ ] Cannot edit other company's vehicles
   - [ ] Super admin can view all companies

3. **Input Validation:**
   - [ ] SQL injection protected (prepared statements)
   - [ ] XSS protection on forms
   - [ ] CSRF tokens where needed

---

## Next Steps After Testing

1. **Configure Integrations:**
   - Add Stripe keys to `config.php`
   - Add Postmark API key for emails
   - Add Google Calendar OAuth credentials
   - Add Twilio credentials for SMS
   - Add WhatsApp Business API tokens

2. **Customize Branding:**
   - Upload company logo
   - Set brand colors
   - Configure website settings

3. **Import Data:**
   - Import vehicles via CSV
   - Add team members
   - Create email templates

4. **Go Live:**
   - Set up custom domain DNS
   - Enable SSL certificate
   - Set `debug_display => false` in config.php
   - Set up cron jobs for automation

---

**Last Updated:** November 2024
**Version:** 1.0.0
