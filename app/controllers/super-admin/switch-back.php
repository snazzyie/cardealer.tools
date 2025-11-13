<?php
/**
 * Super Admin - Switch Back to Super Admin Account
 * Return from company impersonation mode
 */

fn_require_login();

// Verify we're in super admin mode
if (!fn_is_super_admin_mode()) {
    header("Location: /dash");
    exit;
}

// Switch back to super admin
$result = fn_super_admin_switch_back();

if ($result) {
    header("Location: /super-admin/dealers?switched_back=1");
    exit;
} else {
    header("Location: /dash?error=switch_back_failed");
    exit;
}
