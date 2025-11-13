<?php
/**
 * CRM Tasks List Controller
 * Manage tasks and follow-ups
 */

fn_require_login();

$user_data = fn_core_session_get_user_data($_SESSION['email']);
$company_id = $user_data['company_id'];
$permission = $user_data['user_type'];
$user_id = $user_data['user_id'];

if (!$company_id) {
    header("Location: /company/edit");
    exit;
}

// Handle task completion toggle
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_task'])) {
    $taskId = intval($_POST['task_id']);
    $result = fn_crm_toggle_task_status($taskId, $company_id);

    if ($result) {
        header("Location: /crm/tasks?updated=1");
        exit;
    } else {
        $error = "Failed to update task.";
    }
}

// Handle task deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_task'])) {
    $taskId = intval($_POST['task_id']);
    $result = fn_crm_delete_task($taskId, $company_id);

    if ($result) {
        header("Location: /crm/tasks?deleted=1");
        exit;
    } else {
        $error = "Failed to delete task.";
    }
}

// Handle new task creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_task'])) {
    $taskData = [
        'company_id' => $company_id,
        'lead_id' => !empty($_POST['lead_id']) ? intval($_POST['lead_id']) : null,
        'assigned_to' => !empty($_POST['assigned_to']) ? intval($_POST['assigned_to']) : $user_id,
        'task_type' => $_POST['task_type'] ?? 'call',
        'title' => trim($_POST['title'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'due_date' => !empty($_POST['due_date']) ? $_POST['due_date'] : null,
        'priority' => $_POST['priority'] ?? 'medium',
        'status' => 'pending',
        'created_by' => $user_id
    ];

    if (!empty($taskData['title'])) {
        $result = fn_crm_create_task($taskData);
        if ($result) {
            header("Location: /crm/tasks?created=1");
            exit;
        } else {
            $error = "Failed to create task.";
        }
    } else {
        $error = "Task title is required.";
    }
}

// Get filter options
$filter = $_GET['filter'] ?? 'my';
$status_filter = $_GET['status'] ?? 'all';

// Get tasks based on filter
if ($filter === 'my') {
    $tasks = fn_crm_get_user_tasks($user_id, $status_filter);
} else {
    $tasks = fn_crm_get_company_tasks($company_id, $status_filter);
}

// Get task stats
$stats = [
    'my_pending' => fn_crm_count_user_tasks($user_id, 'pending'),
    'my_completed' => fn_crm_count_user_tasks($user_id, 'completed'),
    'company_pending' => fn_crm_count_company_tasks($company_id, 'pending'),
    'company_completed' => fn_crm_count_company_tasks($company_id, 'completed'),
    'overdue' => fn_crm_count_overdue_tasks($company_id)
];

// Get leads for task assignment
$leads = fn_core_database_rows(
    "SELECT lead_id, first_name, last_name FROM crm_leads WHERE company_id = ? AND status NOT IN ('won', 'lost') ORDER BY created_date DESC LIMIT 100",
    [$company_id]
);

// Get users for task assignment
$users = fn_core_database_rows(
    "SELECT user_id, first_name, last_name FROM users WHERE company_id = ? ORDER BY first_name",
    [$company_id]
);

// Page header data
$page_header = [
    'title' => 'CRM Tasks',
    'subtitle' => 'Manage your tasks and follow-ups',
    'breadcrumbs' => [
        'Dashboard' => '/dash',
        'CRM' => '/crm',
        'Tasks' => ''
    ]
];

// Load view
require BASE_PATH . 'views/crm/tasks.php';
