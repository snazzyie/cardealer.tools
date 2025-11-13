<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { min-height: 100vh; background: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar a { color: #333; text-decoration: none; padding: 12px 20px; display: block; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #e9ecef; color: #007bff; }
        .navbar { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .task-item { transition: all 0.3s; }
        .task-item:hover { background: #f8f9fa; }
        .task-completed { opacity: 0.6; text-decoration: line-through; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-primary mb-4">Car Dealer</h4>
                </div>
                <nav>
                    <a href="/dash"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="/vehicles"><i class="bi bi-car-front me-2"></i> Vehicles</a>
                    <a href="/crm" class="active"><i class="bi bi-people me-2"></i> CRM</a>
                    <a href="/calendar"><i class="bi bi-calendar me-2"></i> Calendar</a>
                    <a href="/enquiries"><i class="bi bi-envelope me-2"></i> Enquiries</a>
                    <a href="/customers"><i class="bi bi-person me-2"></i> Customers</a>
                    <a href="/invoices"><i class="bi bi-receipt me-2"></i> Invoices</a>
                    <a href="/inbox"><i class="bi bi-inbox me-2"></i> Inbox</a>
                    <a href="/reports"><i class="bi bi-graph-up me-2"></i> Reports</a>
                    <?php if ($permission >= 2): ?>
                        <hr>
                        <a href="/users"><i class="bi bi-people me-2"></i> Users</a>
                        <a href="/company"><i class="bi bi-building me-2"></i> Company</a>
                        <a href="/subscriptions"><i class="bi bi-credit-card me-2"></i> Subscription</a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-0">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <h5 class="mb-0"><?= $page_header['title'] ?></h5>
                        <div class="d-flex align-items-center">
                            <span class="me-3"><?= htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']) ?></span>
                            <img src="<?= fn_get_gravatar($user_data['email'], 40) ?>" class="rounded-circle me-3" alt="User">
                            <a href="/logout" class="btn btn-outline-danger btn-sm">Logout</a>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-muted mb-0"><?= $page_header['subtitle'] ?></h6>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal">
                            <i class="bi bi-plus-circle me-2"></i> Add Task
                        </button>
                    </div>

                    <?php if (isset($_GET['created']) || isset($_GET['updated']) || isset($_GET['deleted'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>
                            <?php if (isset($_GET['created'])): ?>Task created successfully!<?php endif; ?>
                            <?php if (isset($_GET['updated'])): ?>Task updated successfully!<?php endif; ?>
                            <?php if (isset($_GET['deleted'])): ?>Task deleted successfully!<?php endif; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Task Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h3 class="text-primary mb-1"><?= $stats['total'] ?? 0 ?></h3>
                                    <small class="text-muted">Total Tasks</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['pending'] ?? 0 ?></h3>
                                    <small>Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['overdue'] ?? 0 ?></h3>
                                    <small>Overdue</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['completed'] ?? 0 ?></h3>
                                    <small>Completed</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link <?= !isset($_GET['filter']) || $_GET['filter'] === 'all' ? 'active' : '' ?>" href="/crm/tasks">All Tasks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($_GET['filter'] ?? '') === 'my' ? 'active' : '' ?>" href="/crm/tasks?filter=my">My Tasks</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($_GET['filter'] ?? '') === 'pending' ? 'active' : '' ?>" href="/crm/tasks?filter=pending">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($_GET['filter'] ?? '') === 'overdue' ? 'active' : '' ?>" href="/crm/tasks?filter=overdue">Overdue</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($_GET['filter'] ?? '') === 'completed' ? 'active' : '' ?>" href="/crm/tasks?filter=completed">Completed</a>
                        </li>
                    </ul>

                    <!-- Tasks List -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <?php if (empty($tasks)): ?>
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No tasks found. <a href="#" data-bs-toggle="modal" data-bs-target="#taskModal">Create your first task</a>
                                </div>
                            <?php else: ?>
                                <?php foreach ($tasks as $task): ?>
                                    <div class="task-item border-bottom py-3 <?= $task['status'] === 'completed' ? 'task-completed' : '' ?>">
                                        <div class="row align-items-center">
                                            <div class="col-auto">
                                                <form method="POST" action="/crm/tasks/toggle" class="d-inline">
                                                    <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                                                    <button type="submit" class="btn btn-link p-0">
                                                        <i class="bi bi-<?= $task['status'] === 'completed' ? 'check-circle-fill text-success' : 'circle' ?> fs-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="col">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                                        <?php if ($task['description']): ?>
                                                            <p class="text-muted small mb-2"><?= htmlspecialchars($task['description']) ?></p>
                                                        <?php endif; ?>
                                                        <div class="small">
                                                            <?php if ($task['lead_id']): ?>
                                                                <span class="badge bg-light text-dark me-2">
                                                                    <i class="bi bi-person me-1"></i>
                                                                    <?= htmlspecialchars($task['lead_name']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if ($task['assigned_to']): ?>
                                                                <span class="badge bg-light text-dark me-2">
                                                                    <i class="bi bi-person-badge me-1"></i>
                                                                    <?= htmlspecialchars($task['assigned_first_name'] . ' ' . $task['assigned_last_name']) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                            <?php if ($task['due_date']): ?>
                                                                <?php
                                                                $is_overdue = strtotime($task['due_date']) < time() && $task['status'] !== 'completed';
                                                                ?>
                                                                <span class="badge bg-<?= $is_overdue ? 'danger' : 'info' ?> me-2">
                                                                    <i class="bi bi-calendar me-1"></i>
                                                                    <?= date('d M Y', strtotime($task['due_date'])) ?>
                                                                </span>
                                                            <?php endif; ?>
                                                            <span class="badge bg-<?= $task['priority'] === 'high' ? 'danger' : ($task['priority'] === 'medium' ? 'warning' : 'secondary') ?>">
                                                                <?= ucfirst($task['priority']) ?> Priority
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-outline-secondary" onclick="editTask(<?= htmlspecialchars(json_encode($task)) ?>)">
                                                            <i class="bi bi-pencil"></i>
                                                        </button>
                                                        <form method="POST" action="/crm/tasks/delete" class="d-inline">
                                                            <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
                                                            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this task?')">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="/crm/tasks/save" id="taskForm">
                    <input type="hidden" name="task_id" id="task_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalTitle">Add Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Due Date</label>
                                <input type="date" class="form-control" id="due_date" name="due_date">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select class="form-select" id="priority" name="priority">
                                    <option value="low">Low</option>
                                    <option value="medium" selected>Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="assigned_to" class="form-label">Assign To</label>
                            <select class="form-select" id="assigned_to" name="assigned_to">
                                <option value="">Unassigned</option>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $u): ?>
                                        <option value="<?= $u['user_id'] ?>"><?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="lead_id" class="form-label">Related Lead (Optional)</label>
                            <select class="form-select" id="lead_id" name="lead_id">
                                <option value="">None</option>
                                <?php if (!empty($leads)): ?>
                                    <?php foreach ($leads as $lead): ?>
                                        <option value="<?= $lead['lead_id'] ?>"><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editTask(task) {
            document.getElementById('taskModalTitle').textContent = 'Edit Task';
            document.getElementById('task_id').value = task.task_id;
            document.getElementById('title').value = task.title;
            document.getElementById('description').value = task.description || '';
            document.getElementById('due_date').value = task.due_date || '';
            document.getElementById('priority').value = task.priority;
            document.getElementById('assigned_to').value = task.assigned_to || '';
            document.getElementById('lead_id').value = task.lead_id || '';
            new bootstrap.Modal(document.getElementById('taskModal')).show();
        }

        document.getElementById('taskModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('taskModalTitle').textContent = 'Add Task';
            document.getElementById('taskForm').reset();
            document.getElementById('task_id').value = '';
        });
    </script>
</body>
</html>
