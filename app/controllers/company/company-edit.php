<?php
/**
 * Company Profile Edit Controller
 */

fn_require_login(2);

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

$error = null;
$success = null;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $companyName = trim($_POST['company_name'] ?? '');
    $tradingName = trim($_POST['trading_name'] ?? '');
    $companyEmail = trim($_POST['company_email'] ?? '');
    $companyPhone = trim($_POST['company_phone'] ?? '');
    $companyAddress = trim($_POST['company_address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $county = trim($_POST['county'] ?? '');
    $postcode = trim($_POST['postcode'] ?? '');
    $country = trim($_POST['country'] ?? 'Ireland');
    $vatNumber = trim($_POST['vat_number'] ?? '');
    $companyRegistration = trim($_POST['company_registration'] ?? '');
    $subdomain = trim($_POST['subdomain'] ?? '');
    $domain = trim($_POST['domain'] ?? '');

    if (empty($companyName)) {
        $error = 'Company name is required.';
    } else {
        // Check subdomain availability if changed
        if (!empty($subdomain)) {
            if (!fn_company_subdomain_available($subdomain, $company_id)) {
                $error = 'This subdomain is already taken.';
            }
        }

        // Check domain availability if changed
        if (!empty($domain) && empty($error)) {
            if (!fn_company_domain_available($domain, $company_id)) {
                $error = 'This domain is already registered to another company.';
            }
        }

        if (empty($error)) {
            $data = [
                'company_name' => $companyName,
                'trading_name' => $tradingName,
                'company_email' => $companyEmail,
                'company_phone' => $companyPhone,
                'company_address' => $companyAddress,
                'city' => $city,
                'county' => $county,
                'postcode' => $postcode,
                'country' => $country,
                'vat_number' => $vatNumber,
                'company_registration' => $companyRegistration,
                'subdomain' => $subdomain,
                'domain' => $domain
            ];

            if ($company_id) {
                // Update existing company
                $result = fn_company_update($company_id, $data);
                if ($result) {
                    $success = 'Company profile updated successfully.';
                } else {
                    $error = 'Failed to update company profile.';
                }
            } else {
                // Create new company
                $newCompanyId = fn_company_create($data);
                if ($newCompanyId) {
                    // Update user's company_id and user_type to 2 (company level)
                    $updateUser = "UPDATE users SET company_id = ?, user_type = 2, user_role = 'owner' WHERE user_id = ?";
                    fn_core_edit_row_no_redirect($updateUser, [$newCompanyId, $user_data['user_id']]);

                    // Refresh session
                    $_SESSION['company_id'] = $newCompanyId;
                    fn_core_session_initialise_session();

                    $success = 'Company profile created successfully!';
                    $company_id = $newCompanyId;

                    // Refresh user data
                    $user_data = fn_core_session_get_user_data($_SESSION['email']);
                } else {
                    $error = 'Failed to create company profile.';
                }
            }
        }
    }
}

// Get company data if exists
$company_data = $company_id ? fn_company_get($company_id) : null;

$page_header = [
    'title' => $company_id ? 'Edit Company Profile' : 'Set Up Company Profile',
    'subtitle' => $company_id ? 'Update your company information' : 'Create your company profile to get started'
];

require BASE_PATH . 'views/company/company-edit.php';
