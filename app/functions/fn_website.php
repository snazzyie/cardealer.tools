<?php
/**
 * Website Settings & Management Functions
 *
 * Handle public website settings, pages, menus, and configurations
 */

/**
 * Get website settings for a company
 *
 * @param int $companyId Company ID
 * @return array|null Website settings
 */
function fn_website_get_settings($companyId) {
    $query = "SELECT * FROM website_settings WHERE company_id = ?";
    return fn_core_database_row($query, [$companyId]);
}

/**
 * Create website settings
 *
 * @param int $companyId Company ID
 * @param array $data Settings data
 * @return int|false Settings ID or false
 */
function fn_website_create_settings($companyId, $data) {
    $query = "INSERT INTO website_settings (
        company_id, website_enabled, homepage_title, homepage_subtitle,
        about_text, contact_email, contact_phone,
        facebook_url, twitter_url, instagram_url, linkedin_url,
        google_analytics_id, facebook_pixel_id,
        show_prices, show_financing, allow_enquiries, allow_test_drives,
        seo_title, seo_description, seo_keywords
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['website_enabled'] ?? 1,
        $data['homepage_title'] ?? '',
        $data['homepage_subtitle'] ?? '',
        $data['about_text'] ?? '',
        $data['contact_email'] ?? '',
        $data['contact_phone'] ?? '',
        $data['facebook_url'] ?? '',
        $data['twitter_url'] ?? '',
        $data['instagram_url'] ?? '',
        $data['linkedin_url'] ?? '',
        $data['google_analytics_id'] ?? '',
        $data['facebook_pixel_id'] ?? '',
        $data['show_prices'] ?? 1,
        $data['show_financing'] ?? 1,
        $data['allow_enquiries'] ?? 1,
        $data['allow_test_drives'] ?? 1,
        $data['seo_title'] ?? '',
        $data['seo_description'] ?? '',
        $data['seo_keywords'] ?? ''
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update website settings
 *
 * @param int $companyId Company ID
 * @param array $data Settings data
 * @return bool Success
 */
function fn_website_update_settings($companyId, $data) {
    // Check if settings exist
    $existing = fn_website_get_settings($companyId);

    if ($existing) {
        // Update existing settings
        $query = "UPDATE website_settings SET
            website_enabled = ?, homepage_title = ?, homepage_subtitle = ?,
            about_text = ?, contact_email = ?, contact_phone = ?,
            facebook_url = ?, twitter_url = ?, instagram_url = ?, linkedin_url = ?,
            google_analytics_id = ?, facebook_pixel_id = ?,
            show_prices = ?, show_financing = ?, allow_enquiries = ?, allow_test_drives = ?,
            seo_title = ?, seo_description = ?, seo_keywords = ?
        WHERE company_id = ?";

        $params = [
            $data['website_enabled'] ?? 1,
            $data['homepage_title'] ?? '',
            $data['homepage_subtitle'] ?? '',
            $data['about_text'] ?? '',
            $data['contact_email'] ?? '',
            $data['contact_phone'] ?? '',
            $data['facebook_url'] ?? '',
            $data['twitter_url'] ?? '',
            $data['instagram_url'] ?? '',
            $data['linkedin_url'] ?? '',
            $data['google_analytics_id'] ?? '',
            $data['facebook_pixel_id'] ?? '',
            $data['show_prices'] ?? 1,
            $data['show_financing'] ?? 1,
            $data['allow_enquiries'] ?? 1,
            $data['allow_test_drives'] ?? 1,
            $data['seo_title'] ?? '',
            $data['seo_description'] ?? '',
            $data['seo_keywords'] ?? '',
            $companyId
        ];

        return fn_core_edit_row_no_redirect($query, $params);
    } else {
        // Create new settings
        return fn_website_create_settings($companyId, $data) !== false;
    }
}

/**
 * Check if website is enabled
 *
 * @param int $companyId Company ID
 * @return bool Website enabled
 */
function fn_website_is_enabled($companyId) {
    $settings = fn_website_get_settings($companyId);
    return !empty($settings['website_enabled']);
}

/**
 * Enable website
 *
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_website_enable($companyId) {
    $query = "UPDATE website_settings SET website_enabled = 1 WHERE company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$companyId]);
}

/**
 * Disable website
 *
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_website_disable($companyId) {
    $query = "UPDATE website_settings SET website_enabled = 0 WHERE company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$companyId]);
}

/**
 * ====================
 * WEBSITE PAGES
 * ====================
 */

/**
 * Get all website pages for a company
 *
 * @param int $companyId Company ID
 * @param bool $publishedOnly Only get published pages
 * @return array Pages
 */
function fn_website_get_pages($companyId, $publishedOnly = false) {
    $query = "SELECT * FROM website_pages WHERE company_id = ?";
    $params = [$companyId];

    if ($publishedOnly) {
        $query .= " AND status = 'published'";
    }

    $query .= " ORDER BY page_order ASC, title ASC";

    return fn_core_database_rows($query, $params);
}

/**
 * Get page by ID
 *
 * @param int $pageId Page ID
 * @param int $companyId Company ID
 * @return array|null Page data
 */
function fn_website_get_page($pageId, $companyId) {
    $query = "SELECT * FROM website_pages WHERE page_id = ? AND company_id = ?";
    return fn_core_database_row($query, [$pageId, $companyId]);
}

/**
 * Get page by slug
 *
 * @param string $slug Page slug
 * @param int $companyId Company ID
 * @return array|null Page data
 */
function fn_website_get_page_by_slug($slug, $companyId) {
    $query = "SELECT * FROM website_pages WHERE slug = ? AND company_id = ? AND status = 'published'";
    return fn_core_database_row($query, [$slug, $companyId]);
}

/**
 * Create website page
 *
 * @param int $companyId Company ID
 * @param array $data Page data
 * @return int|false Page ID or false
 */
function fn_website_create_page($companyId, $data) {
    // Generate slug if not provided
    if (empty($data['slug'])) {
        $data['slug'] = fn_website_generate_slug($data['title'], $companyId);
    }

    $query = "INSERT INTO website_pages (
        company_id, title, slug, content, meta_title, meta_description,
        status, page_order, show_in_menu, created_by
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['title'],
        $data['slug'],
        $data['content'] ?? '',
        $data['meta_title'] ?? $data['title'],
        $data['meta_description'] ?? '',
        $data['status'] ?? 'draft',
        $data['page_order'] ?? 0,
        $data['show_in_menu'] ?? 0,
        $data['created_by'] ?? null
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update website page
 *
 * @param int $pageId Page ID
 * @param int $companyId Company ID
 * @param array $data Page data
 * @return bool Success
 */
function fn_website_update_page($pageId, $companyId, $data) {
    $query = "UPDATE website_pages SET
        title = ?, slug = ?, content = ?, meta_title = ?, meta_description = ?,
        status = ?, page_order = ?, show_in_menu = ?
    WHERE page_id = ? AND company_id = ?";

    $params = [
        $data['title'],
        $data['slug'],
        $data['content'] ?? '',
        $data['meta_title'] ?? $data['title'],
        $data['meta_description'] ?? '',
        $data['status'] ?? 'draft',
        $data['page_order'] ?? 0,
        $data['show_in_menu'] ?? 0,
        $pageId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Delete website page
 *
 * @param int $pageId Page ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_website_delete_page($pageId, $companyId) {
    $query = "DELETE FROM website_pages WHERE page_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$pageId, $companyId]);
}

/**
 * Publish page
 *
 * @param int $pageId Page ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_website_publish_page($pageId, $companyId) {
    $query = "UPDATE website_pages SET status = 'published' WHERE page_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$pageId, $companyId]);
}

/**
 * Unpublish page
 *
 * @param int $pageId Page ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_website_unpublish_page($pageId, $companyId) {
    $query = "UPDATE website_pages SET status = 'draft' WHERE page_id = ? AND company_id = ?";
    return fn_core_edit_row_no_redirect($query, [$pageId, $companyId]);
}

/**
 * ====================
 * WEBSITE MENUS
 * ====================
 */

/**
 * Get all menu items for a company
 *
 * @param int $companyId Company ID
 * @param string $menuLocation Menu location (header, footer)
 * @return array Menu items
 */
function fn_website_get_menu_items($companyId, $menuLocation = 'header') {
    $query = "SELECT * FROM website_menu_items
              WHERE company_id = ? AND menu_location = ? AND is_active = 1
              ORDER BY menu_order ASC";
    return fn_core_database_rows($query, [$companyId, $menuLocation]);
}

/**
 * Get menu item by ID
 *
 * @param int $menuItemId Menu item ID
 * @param int $companyId Company ID
 * @return array|null Menu item data
 */
function fn_website_get_menu_item($menuItemId, $companyId) {
    $query = "SELECT * FROM website_menu_items WHERE menu_item_id = ? AND company_id = ?";
    return fn_core_database_row($query, [$menuItemId, $companyId]);
}

/**
 * Create menu item
 *
 * @param int $companyId Company ID
 * @param array $data Menu item data
 * @return int|false Menu item ID or false
 */
function fn_website_create_menu_item($companyId, $data) {
    $query = "INSERT INTO website_menu_items (
        company_id, menu_location, label, url, page_id,
        parent_id, menu_order, target, is_active
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $params = [
        $companyId,
        $data['menu_location'] ?? 'header',
        $data['label'],
        $data['url'] ?? '',
        $data['page_id'] ?? null,
        $data['parent_id'] ?? null,
        $data['menu_order'] ?? 0,
        $data['target'] ?? '_self',
        $data['is_active'] ?? 1
    ];

    return fn_core_insert_row_no_redirect($query, $params);
}

/**
 * Update menu item
 *
 * @param int $menuItemId Menu item ID
 * @param int $companyId Company ID
 * @param array $data Menu item data
 * @return bool Success
 */
function fn_website_update_menu_item($menuItemId, $companyId, $data) {
    $query = "UPDATE website_menu_items SET
        menu_location = ?, label = ?, url = ?, page_id = ?,
        parent_id = ?, menu_order = ?, target = ?, is_active = ?
    WHERE menu_item_id = ? AND company_id = ?";

    $params = [
        $data['menu_location'] ?? 'header',
        $data['label'],
        $data['url'] ?? '',
        $data['page_id'] ?? null,
        $data['parent_id'] ?? null,
        $data['menu_order'] ?? 0,
        $data['target'] ?? '_self',
        $data['is_active'] ?? 1,
        $menuItemId,
        $companyId
    ];

    return fn_core_edit_row_no_redirect($query, $params);
}

/**
 * Delete menu item
 *
 * @param int $menuItemId Menu item ID
 * @param int $companyId Company ID
 * @return bool Success
 */
function fn_website_delete_menu_item($menuItemId, $companyId) {
    $query = "DELETE FROM website_menu_items WHERE menu_item_id = ? AND company_id = ?";
    return fn_core_delete_row_no_redirect($query, [$menuItemId, $companyId]);
}

/**
 * Reorder menu items
 *
 * @param int $companyId Company ID
 * @param array $orderData Array of menu item IDs in order
 * @return bool Success
 */
function fn_website_reorder_menu_items($companyId, $orderData) {
    foreach ($orderData as $order => $menuItemId) {
        $query = "UPDATE website_menu_items SET menu_order = ? WHERE menu_item_id = ? AND company_id = ?";
        fn_core_edit_row_no_redirect($query, [$order, $menuItemId, $companyId]);
    }
    return true;
}

/**
 * ====================
 * HELPER FUNCTIONS
 * ====================
 */

/**
 * Generate unique slug for page
 *
 * @param string $title Page title
 * @param int $companyId Company ID
 * @return string Slug
 */
function fn_website_generate_slug($title, $companyId) {
    $slug = strtolower(trim($title));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');

    // Ensure uniqueness
    $originalSlug = $slug;
    $counter = 1;
    while (!fn_website_slug_available($slug, $companyId)) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
    }

    return $slug;
}

/**
 * Check if slug is available
 *
 * @param string $slug Slug
 * @param int $companyId Company ID
 * @param int $excludePageId Exclude this page ID (for updates)
 * @return bool True if available
 */
function fn_website_slug_available($slug, $companyId, $excludePageId = null) {
    $query = "SELECT COUNT(*) as count FROM website_pages WHERE slug = ? AND company_id = ?";
    $params = [$slug, $companyId];

    if ($excludePageId) {
        $query .= " AND page_id != ?";
        $params[] = $excludePageId;
    }

    $result = fn_core_database_row($query, $params);
    return ($result['count'] ?? 0) == 0;
}

/**
 * Get website URL for company
 *
 * @param int $companyId Company ID
 * @return string Website URL
 */
function fn_website_get_url($companyId) {
    $company = fn_company_get($companyId);

    if (!$company) {
        return '';
    }

    if (!empty($company['domain'])) {
        return 'https://' . $company['domain'];
    } elseif (!empty($company['subdomain'])) {
        return 'https://' . $company['subdomain'] . '.cardealer.tools';
    }

    return '';
}

/**
 * Get pages for menu display
 *
 * @param int $companyId Company ID
 * @return array Pages that should show in menu
 */
function fn_website_get_menu_pages($companyId) {
    $query = "SELECT page_id, title, slug FROM website_pages
              WHERE company_id = ?
              AND status = 'published'
              AND show_in_menu = 1
              ORDER BY page_order ASC, title ASC";
    return fn_core_database_rows($query, [$companyId]);
}

/**
 * Get published pages count
 *
 * @param int $companyId Company ID
 * @return int Count
 */
function fn_website_count_published_pages($companyId) {
    $query = "SELECT COUNT(*) as count FROM website_pages
              WHERE company_id = ? AND status = 'published'";
    $result = fn_core_database_row($query, [$companyId]);
    return $result['count'] ?? 0;
}
