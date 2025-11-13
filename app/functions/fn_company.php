<?php
/**
 * Company Management Functions
 *
 * Handle dealer company operations
 */

/**
 * Get company by ID
 *
 * @param int $companyId Company ID
 * @return array|null Company data or null
 */
function fn_company_get($companyId) {
    $query = "SELECT * FROM core_company WHERE company_id = ?";
    return fn_core_database_row($query, [$companyId]);
}

/**
 * Get company by ID (alias for fn_company_get)
 *
 * @param int $companyId Company ID
 * @return array|null Company data or null
 */
function fn_company_get_by_id($companyId) {
    return fn_company_get($companyId);
}

/**
 * Get company by subdomain
 *
 * @param string $subdomain Subdomain
 * @return array|null Company data or null
 */
function fn_company_get_by_subdomain($subdomain) {
    $query = "SELECT * FROM core_company WHERE subdomain = ?";
    return fn_core_database_row($query, [$subdomain]);
}

/**
 * Get company by domain
 *
 * @param string $domain Domain name
 * @return array|null Company data or null
 */
function fn_company_get_by_domain($domain) {
    $query = "SELECT * FROM core_company WHERE domain = ?";
    return fn_core_database_row($query, [$domain]);
}

/**
 * Create new company
 *
 * @param array $data Company data
 * @return int|false Company ID or false on failure
 */
function fn_company_create($data) {
    $query = "INSERT INTO core_company (
        company_name,
        trading_name,
        company_email,
        company_phone,
        company_address,
        city,
        county,
        postcode,
        country,
        vat_number,
        company_registration,
        subdomain,
        domain,
        status,
        trial_ends_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $trialEnds = date('Y-m-d', strtotime('+14 days'));

    $params = [
        $data['company_name'] ?? '',
        $data['trading_name'] ?? '',
        $data['company_email'] ?? '',
        $data['company_phone'] ?? '',
        $data['company_address'] ?? '',
        $data['city'] ?? '',
        $data['county'] ?? '',
        $data['postcode'] ?? '',
        $data['country'] ?? 'Ireland',
        $data['vat_number'] ?? null,
        $data['company_registration'] ?? null,
        $data['subdomain'] ?? null,
        $data['domain'] ?? null,
        'trial',
        $trialEnds
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update company
 *
 * @param int $companyId Company ID
 * @param array $data Company data
 * @return bool Success status
 */
function fn_company_update($companyId, $data) {
    $query = "UPDATE core_company SET
        company_name = ?,
        trading_name = ?,
        company_email = ?,
        company_phone = ?,
        company_address = ?,
        city = ?,
        county = ?,
        postcode = ?,
        country = ?,
        vat_number = ?,
        company_registration = ?,
        subdomain = ?,
        domain = ?
    WHERE company_id = ?";

    $params = [
        $data['company_name'] ?? '',
        $data['trading_name'] ?? '',
        $data['company_email'] ?? '',
        $data['company_phone'] ?? '',
        $data['company_address'] ?? '',
        $data['city'] ?? '',
        $data['county'] ?? '',
        $data['postcode'] ?? '',
        $data['country'] ?? 'Ireland',
        $data['vat_number'] ?? null,
        $data['company_registration'] ?? null,
        $data['subdomain'] ?? null,
        $data['domain'] ?? null,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update company branding
 *
 * @param int $companyId Company ID
 * @param array $data Branding data
 * @return bool Success status
 */
function fn_company_update_branding($companyId, $data) {
    $query = "UPDATE core_company SET
        logo_url = ?,
        favicon_url = ?,
        primary_color = ?,
        secondary_color = ?,
        social_facebook = ?,
        social_instagram = ?,
        social_twitter = ?,
        social_linkedin = ?
    WHERE company_id = ?";

    $params = [
        $data['logo_url'] ?? null,
        $data['favicon_url'] ?? null,
        $data['primary_color'] ?? '#007bff',
        $data['secondary_color'] ?? '#6c757d',
        $data['social_facebook'] ?? null,
        $data['social_instagram'] ?? null,
        $data['social_twitter'] ?? null,
        $data['social_linkedin'] ?? null,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update company opening hours
 *
 * @param int $companyId Company ID
 * @param array $hours Opening hours array
 * @return bool Success status
 */
function fn_company_update_opening_hours($companyId, $hours) {
    $query = "UPDATE core_company SET opening_hours = ? WHERE company_id = ?";
    $params = [json_encode($hours), $companyId];
    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update company analytics
 *
 * @param int $companyId Company ID
 * @param array $data Analytics data
 * @return bool Success status
 */
function fn_company_update_analytics($companyId, $data) {
    $query = "UPDATE core_company SET
        google_analytics_id = ?,
        google_tag_manager_id = ?
    WHERE company_id = ?";

    $params = [
        $data['google_analytics_id'] ?? null,
        $data['google_tag_manager_id'] ?? null,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Update company status
 *
 * @param int $companyId Company ID
 * @param string $status Status (active, suspended, trial)
 * @return bool Success status
 */
function fn_company_update_status($companyId, $status) {
    $query = "UPDATE core_company SET status = ? WHERE company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$status, $companyId]);
}

/**
 * Check if subdomain is available
 *
 * @param string $subdomain Subdomain to check
 * @param int $excludeCompanyId Company ID to exclude from check
 * @return bool True if available
 */
function fn_company_subdomain_available($subdomain, $excludeCompanyId = null) {
    if ($excludeCompanyId) {
        $query = "SELECT COUNT(*) as count FROM core_company WHERE subdomain = ? AND company_id != ?";
        $result = fn_core_database_row($query, [$subdomain, $excludeCompanyId]);
    } else {
        $query = "SELECT COUNT(*) as count FROM core_company WHERE subdomain = ?";
        $result = fn_core_database_row($query, [$subdomain]);
    }

    return $result['count'] == 0;
}

/**
 * Check if domain is available
 *
 * @param string $domain Domain to check
 * @param int $excludeCompanyId Company ID to exclude from check
 * @return bool True if available
 */
function fn_company_domain_available($domain, $excludeCompanyId = null) {
    if ($excludeCompanyId) {
        $query = "SELECT COUNT(*) as count FROM core_company WHERE domain = ? AND company_id != ?";
        $result = fn_core_database_row($query, [$domain, $excludeCompanyId]);
    } else {
        $query = "SELECT COUNT(*) as count FROM core_company WHERE domain = ?";
        $result = fn_core_database_row($query, [$domain]);
    }

    return $result['count'] == 0;
}

/**
 * Get all companies (Super Admin only)
 *
 * @param int $limit Limit
 * @param int $offset Offset
 * @return array Companies
 */
function fn_company_get_all($limit = 50, $offset = 0) {
    $query = "SELECT * FROM core_company ORDER BY created_date DESC LIMIT ? OFFSET ?";
    return fn_core_database_rows($query, [$limit, $offset]);
}

/**
 * Count all companies
 *
 * @return int Count
 */
function fn_company_count_all() {
    $query = "SELECT COUNT(*) as count FROM core_company";
    $result = fn_core_database_row($query, []);
    return $result['count'];
}

/**
 * Get company users
 *
 * @param int $companyId Company ID
 * @return array Users
 */
function fn_company_get_users($companyId) {
    $query = "SELECT user_id, email, first_name, last_name, phone, user_type, user_role, last_login, created_date
              FROM users
              WHERE company_id = ?
              ORDER BY user_type DESC, created_date ASC";
    return fn_core_database_rows($query, [$companyId]);
}
