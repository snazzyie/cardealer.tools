<?php
/**
 * CSV Vehicle Import
 * Bulk import vehicles from CSV file
 */

fn_require_login(2); // Requires company level

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

$success = '';
$error = '';
$import_results = null;

// Handle CSV upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $tmpName = $file['tmp_name'];
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);

        if (strtolower($fileType) !== 'csv') {
            $error = "Please upload a CSV file";
        } else {
            // Process CSV
            $import_results = fn_vehicles_import_csv($tmpName, $company_id);

            if ($import_results['success']) {
                $success = "Successfully imported {$import_results['imported']} vehicles. {$import_results['skipped']} skipped.";
            } else {
                $error = "Import failed: " . $import_results['error'];
            }
        }
    } else {
        $error = "File upload error";
    }
}

$page_header = [
    'title' => 'Import Vehicles',
    'subtitle' => 'Bulk import vehicles from CSV file'
];

require BASE_PATH . 'views/vehicles/vehicles-import.php';
