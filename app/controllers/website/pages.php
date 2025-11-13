<?php
/**
 * Website Pages Management Controller
 * Manage custom pages for public website
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Only admin can manage pages
if ($permission < 2) {
    header("Location: /dash");
    exit;
}

$error = null;
$success = null;

// Handle page creation/update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_page'])) {
    $pageId = !empty($_POST['page_id']) ? intval($_POST['page_id']) : null;
    $pageTitle = trim($_POST['page_title'] ?? '');
    $pageSlug = trim($_POST['page_slug'] ?? '');
    $pageContent = trim($_POST['page_content'] ?? '');
    $isPublished = isset($_POST['is_published']) ? 1 : 0;

    if (empty($pageTitle)) {
        $error = 'Page title is required.';
    } elseif (empty($pageSlug)) {
        $error = 'Page slug is required.';
    } else {
        // Generate slug if not provided
        if (empty($pageSlug)) {
            $pageSlug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $pageTitle));
        }

        // Check slug uniqueness
        $existingSlug = fn_core_database_row(
            "SELECT page_id FROM website_pages WHERE company_id = ? AND page_slug = ?" . ($pageId ? " AND page_id != ?" : ""),
            $pageId ? [$company_id, $pageSlug, $pageId] : [$company_id, $pageSlug]
        );

        if ($existingSlug) {
            $error = 'This page slug is already in use.';
        } else {
            $pageData = [
                'page_title' => $pageTitle,
                'page_slug' => $pageSlug,
                'page_content' => $pageContent,
                'meta_title' => trim($_POST['meta_title'] ?? $pageTitle),
                'meta_description' => trim($_POST['meta_description'] ?? ''),
                'is_published' => $isPublished
            ];

            if ($pageId) {
                // Update existing page
                $result = fn_core_update_row('website_pages', $pageId, $pageData, 'page_id');
            } else {
                // Create new page
                $pageData['company_id'] = $company_id;
                $pageData['created_date'] = date('Y-m-d H:i:s');
                $pageData['created_by'] = $user_data['user_id'];
                $result = fn_core_create_row('website_pages', $pageData, 'page_id');
            }

            if ($result) {
                $success = $pageId ? 'Page updated successfully.' : 'Page created successfully.';
            } else {
                $error = 'Failed to save page.';
            }
        }
    }
}

// Handle page deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_page'])) {
    $pageId = intval($_POST['page_id']);

    $result = fn_core_database_query(
        "DELETE FROM website_pages WHERE page_id = ? AND company_id = ?",
        [$pageId, $company_id]
    );

    if ($result) {
        header("Location: /website/pages?deleted=1");
        exit;
    } else {
        $error = 'Failed to delete page.';
    }
}

// Get all pages
$pages = fn_core_database_rows(
    "SELECT p.*, u.first_name, u.last_name
     FROM website_pages p
     LEFT JOIN users u ON p.created_by = u.user_id
     WHERE p.company_id = ?
     ORDER BY p.created_date DESC",
    [$company_id]
);

// Get page being edited
$edit_page = null;
if (!empty($_GET['edit'])) {
    $edit_page = fn_core_database_row(
        "SELECT * FROM website_pages WHERE page_id = ? AND company_id = ?",
        [intval($_GET['edit']), $company_id]
    );
}

// Page stats
$stats = [
    'total_pages' => count($pages),
    'published' => count(array_filter($pages, fn($p) => $p['is_published'] == 1)),
    'draft' => count(array_filter($pages, fn($p) => $p['is_published'] == 0))
];

// Page header data
$page_header = [
    'title' => 'Website Pages',
    'subtitle' => 'Manage custom pages',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Website' => '/website',
        'Pages' => ''
    ]
];

// Load view
require BASE_PATH . 'views/website/pages.php';
