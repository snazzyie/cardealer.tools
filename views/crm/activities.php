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
        .activity-timeline { position: relative; padding-left: 40px; }
        .activity-item { position: relative; padding-bottom: 30px; }
        .activity-item:before { content: ''; position: absolute; left: -32px; top: 5px; width: 12px; height: 12px; border-radius: 50%; background: #007bff; }
        .activity-item:after { content: ''; position: absolute; left: -27px; top: 17px; width: 2px; bottom: 0; background: #e0e0e0; }
        .activity-item:last-child:after { display: none; }
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
                    <h6 class="text-muted mb-4"><?= $page_header['subtitle'] ?></h6>

                    <!-- Filter Bar -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <form method="GET" action="/crm/activities" class="row g-3">
                                <div class="col-md-3">
                                    <select class="form-select" name="activity_type">
                                        <option value="">All Types</option>
                                        <option value="Call" <?= ($_GET['activity_type'] ?? '') === 'Call' ? 'selected' : '' ?>>Call</option>
                                        <option value="Email" <?= ($_GET['activity_type'] ?? '') === 'Email' ? 'selected' : '' ?>>Email</option>
                                        <option value="Meeting" <?= ($_GET['activity_type'] ?? '') === 'Meeting' ? 'selected' : '' ?>>Meeting</option>
                                        <option value="Note" <?= ($_GET['activity_type'] ?? '') === 'Note' ? 'selected' : '' ?>>Note</option>
                                        <option value="Task" <?= ($_GET['activity_type'] ?? '') === 'Task' ? 'selected' : '' ?>>Task</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" name="user_id">
                                        <option value="">All Users</option>
                                        <?php if (!empty($users)): ?>
                                            <?php foreach ($users as $u): ?>
                                                <option value="<?= $u['user_id'] ?>" <?= ($_GET['user_id'] ?? '') == $u['user_id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_from" value="<?= htmlspecialchars($_GET['date_from'] ?? '') ?>" placeholder="From">
                                </div>
                                <div class="col-md-2">
                                    <input type="date" class="form-control" name="date_to" value="<?= htmlspecialchars($_GET['date_to'] ?? '') ?>" placeholder="To">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-funnel me-2"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Activity Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-activity fs-1 text-primary"></i>
                                    <h3 class="mt-2 mb-1"><?= $stats['total'] ?? 0 ?></h3>
                                    <small class="text-muted">Total Activities</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-telephone fs-1 text-success"></i>
                                    <h3 class="mt-2 mb-1"><?= $stats['calls'] ?? 0 ?></h3>
                                    <small class="text-muted">Calls</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-envelope fs-1 text-info"></i>
                                    <h3 class="mt-2 mb-1"><?= $stats['emails'] ?? 0 ?></h3>
                                    <small class="text-muted">Emails</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <i class="bi bi-calendar-event fs-1 text-warning"></i>
                                    <h3 class="mt-2 mb-1"><?= $stats['meetings'] ?? 0 ?></h3>
                                    <small class="text-muted">Meetings</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Timeline -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Activity Timeline</h5>

                            <?php if (empty($activities)): ?>
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                    No activities found
                                </div>
                            <?php else: ?>
                                <div class="activity-timeline">
                                    <?php
                                    $current_date = '';
                                    foreach ($activities as $activity):
                                        $activity_date = date('Y-m-d', strtotime($activity['created_date']));
                                        if ($current_date !== $activity_date):
                                            $current_date = $activity_date;
                                            ?>
                                            <h6 class="text-muted mt-4 mb-3">
                                                <?php
                                                $date = strtotime($activity['created_date']);
                                                $today = strtotime(date('Y-m-d'));
                                                $yesterday = strtotime('-1 day', $today);

                                                if (date('Y-m-d', $date) === date('Y-m-d', $today)) {
                                                    echo 'Today';
                                                } elseif (date('Y-m-d', $date) === date('Y-m-d', $yesterday)) {
                                                    echo 'Yesterday';
                                                } else {
                                                    echo date('l, d F Y', $date);
                                                }
                                                ?>
                                            </h6>
                                        <?php endif; ?>

                                        <div class="activity-item">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div>
                                                            <span class="badge bg-primary me-2">
                                                                <?php
                                                                $icons = [
                                                                    'Call' => 'telephone',
                                                                    'Email' => 'envelope',
                                                                    'Meeting' => 'calendar-event',
                                                                    'Note' => 'sticky',
                                                                    'Task' => 'check-circle'
                                                                ];
                                                                $icon = $icons[$activity['activity_type']] ?? 'activity';
                                                                ?>
                                                                <i class="bi bi-<?= $icon ?> me-1"></i>
                                                                <?= htmlspecialchars($activity['activity_type']) ?>
                                                            </span>
                                                            <?php if ($activity['lead_id']): ?>
                                                                <a href="/crm/leads/view?id=<?= $activity['lead_id'] ?>" class="badge bg-light text-dark text-decoration-none">
                                                                    <i class="bi bi-person me-1"></i>
                                                                    <?= htmlspecialchars($activity['lead_first_name'] . ' ' . $activity['lead_last_name']) ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                        <small class="text-muted"><?= date('H:i', strtotime($activity['created_date'])) ?></small>
                                                    </div>
                                                    <p class="mb-2"><?= nl2br(htmlspecialchars($activity['description'])) ?></p>
                                                    <small class="text-muted">
                                                        <i class="bi bi-person-circle me-1"></i>
                                                        <?= htmlspecialchars($activity['user_first_name'] . ' ' . $activity['user_last_name']) ?>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
