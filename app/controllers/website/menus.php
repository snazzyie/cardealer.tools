<?php
/**
 * Website Menus Management Controller
 * Manage navigation menus for public website
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Only admin can manage menus
if ($permission < 2) {
    header("Location: /dash");
    exit;
}

$error = null;
$success = null;

// Handle menu item creation/update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_menu_item'])) {
    $menuId = !empty($_POST['menu_id']) ? intval($_POST['menu_id']) : null;
    $menuLocation = $_POST['menu_location'] ?? 'header';
    $menuLabel = trim($_POST['menu_label'] ?? '');
    $menuUrl = trim($_POST['menu_url'] ?? '');
    $menuOrder = intval($_POST['menu_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if (empty($menuLabel)) {
        $error = 'Menu label is required.';
    } elseif (empty($menuUrl)) {
        $error = 'Menu URL is required.';
    } else {
        $menuData = [
            'menu_location' => $menuLocation,
            'menu_label' => $menuLabel,
            'menu_url' => $menuUrl,
            'menu_order' => $menuOrder,
            'parent_id' => !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null,
            'is_active' => $isActive,
            'open_new_window' => isset($_POST['open_new_window']) ? 1 : 0
        ];

        if ($menuId) {
            // Update existing menu item
            $result = fn_core_update_row('website_menus', $menuId, $menuData, 'menu_id');
        } else {
            // Create new menu item
            $menuData['company_id'] = $company_id;
            $menuData['created_date'] = date('Y-m-d H:i:s');
            $result = fn_core_create_row('website_menus', $menuData, 'menu_id');
        }

        if ($result) {
            $success = $menuId ? 'Menu item updated successfully.' : 'Menu item created successfully.';
        } else {
            $error = 'Failed to save menu item.';
        }
    }
}

// Handle menu item deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_menu'])) {
    $menuId = intval($_POST['menu_id']);

    $result = fn_core_database_query(
        "DELETE FROM website_menus WHERE menu_id = ? AND company_id = ?",
        [$menuId, $company_id]
    );

    if ($result) {
        header("Location: /website/menus?deleted=1");
        exit;
    } else {
        $error = 'Failed to delete menu item.';
    }
}

// Handle reorder
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reorder_menus'])) {
    $menuOrders = $_POST['menu_order'] ?? [];

    foreach ($menuOrders as $menuId => $order) {
        fn_core_database_query(
            "UPDATE website_menus SET menu_order = ? WHERE menu_id = ? AND company_id = ?",
            [intval($order), intval($menuId), $company_id]
        );
    }

    header("Location: /website/menus?reordered=1");
    exit;
}

// Get menu locations
$menu_locations = [
    'header' => 'Header Menu',
    'footer' => 'Footer Menu',
    'mobile' => 'Mobile Menu'
];

// Get selected location
$selected_location = $_GET['location'] ?? 'header';

// Get menu items for selected location
$menu_items = fn_core_database_rows(
    "SELECT * FROM website_menus
     WHERE company_id = ? AND menu_location = ?
     ORDER BY menu_order ASC, menu_id ASC",
    [$company_id, $selected_location]
);

// Get all pages for URL suggestions
$pages = fn_core_database_rows(
    "SELECT page_id, page_title, page_slug FROM website_pages WHERE company_id = ? AND is_published = 1",
    [$company_id]
);

// Get menu item being edited
$edit_menu = null;
if (!empty($_GET['edit'])) {
    $edit_menu = fn_core_database_row(
        "SELECT * FROM website_menus WHERE menu_id = ? AND company_id = ?",
        [intval($_GET['edit']), $company_id]
    );
}

// Common URLs for suggestions
$common_urls = [
    '/' => 'Home',
    '/cars' => 'Vehicle Listings',
    '/about' => 'About Us',
    '/contact' => 'Contact',
    '/finance' => 'Finance Calculator',
    '/trade-in' => 'Trade-In Valuation'
];

// Page header data
$page_header = [
    'title' => 'Website Menus',
    'subtitle' => 'Manage navigation menus',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'Website' => '/website',
        'Menus' => ''
    ]
];

// Load view
require BASE_PATH . 'views/website/menus.php';
