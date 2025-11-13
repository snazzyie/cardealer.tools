<?php
/**
 * Edit Vehicle Controller
 * Routes to vehicle-form.php with id parameter for editing
 */

if (!isset($_GET['id'])) {
    header("Location: /vehicles");
    exit;
}

require __DIR__ . '/vehicle-form.php';
