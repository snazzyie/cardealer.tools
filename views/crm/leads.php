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
        .stat-card { transition: transform 0.3s; }
        .stat-card:hover { transform: translateY(-5px); }
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
                        <a href="/crm/leads/new" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Add Lead
                        </a>
                    </div>

                    <?php if (isset($_GET['deleted'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i> Lead deleted successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['created'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i> Lead created successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Stats Cards -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-2">
                            <div class="card stat-card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['total'] ?></h3>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card stat-card border-0 shadow-sm bg-primary text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['new'] ?></h3>
                                    <small>New</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card stat-card border-0 shadow-sm bg-info text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['contacted'] ?></h3>
                                    <small>Contacted</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card stat-card border-0 shadow-sm bg-warning text-dark">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['qualified'] ?></h3>
                                    <small>Qualified</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card stat-card border-0 shadow-sm bg-success text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['won'] ?></h3>
                                    <small>Won</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card stat-card border-0 shadow-sm bg-danger text-white">
                                <div class="card-body text-center">
                                    <h3 class="mb-1"><?= $stats['lost'] ?></h3>
                                    <small>Lost</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <form method="GET" action="/crm/leads" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search leads..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="new" <?= ($_GET['status'] ?? '') === 'new' ? 'selected' : '' ?>>New</option>
                                        <option value="contacted" <?= ($_GET['status'] ?? '') === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                                        <option value="qualified" <?= ($_GET['status'] ?? '') === 'qualified' ? 'selected' : '' ?>>Qualified</option>
                                        <option value="won" <?= ($_GET['status'] ?? '') === 'won' ? 'selected' : '' ?>>Won</option>
                                        <option value="lost" <?= ($_GET['status'] ?? '') === 'lost' ? 'selected' : '' ?>>Lost</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="source">
                                        <option value="">All Sources</option>
                                        <option value="website" <?= ($_GET['source'] ?? '') === 'website' ? 'selected' : '' ?>>Website</option>
                                        <option value="phone" <?= ($_GET['source'] ?? '') === 'phone' ? 'selected' : '' ?>>Phone</option>
                                        <option value="email" <?= ($_GET['source'] ?? '') === 'email' ? 'selected' : '' ?>>Email</option>
                                        <option value="walk-in" <?= ($_GET['source'] ?? '') === 'walk-in' ? 'selected' : '' ?>>Walk-in</option>
                                        <option value="referral" <?= ($_GET['source'] ?? '') === 'referral' ? 'selected' : '' ?>>Referral</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select" name="assigned_to">
                                        <option value="">All Assigned</option>
                                        <?php foreach ($users as $u): ?>
                                            <option value="<?= $u['user_id'] ?>" <?= ($_GET['assigned_to'] ?? '') == $u['user_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-funnel me-2"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Leads Table -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Status</th>
                                            <th>Source</th>
                                            <th>Assigned To</th>
                                            <th>Interest</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($leads)): ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted py-5">
                                                    <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                    No leads found. <a href="/crm/leads/new">Add your first lead</a>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($leads as $lead): ?>
                                                <tr>
                                                    <td>
                                                        <a href="/crm/leads/view?id=<?= $lead['lead_id'] ?>" class="text-decoration-none">
                                                            <strong><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></strong>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <div><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($lead['email']) ?></div>
                                                        <?php if ($lead['phone']): ?>
                                                            <div><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($lead['phone']) ?></div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $badge_colors = [
                                                            'new' => 'primary',
                                                            'contacted' => 'info',
                                                            'qualified' => 'warning',
                                                            'won' => 'success',
                                                            'lost' => 'danger'
                                                        ];
                                                        $color = $badge_colors[$lead['status']] ?? 'secondary';
                                                        ?>
                                                        <span class="badge bg-<?= $color ?>"><?= ucfirst($lead['status']) ?></span>
                                                    </td>
                                                    <td><?= ucfirst($lead['source']) ?></td>
                                                    <td>
                                                        <?php if ($lead['assigned_to']): ?>
                                                            <?= htmlspecialchars($lead['assigned_first_name'] . ' ' . $lead['assigned_last_name']) ?>
                                                        <?php else: ?>
                                                            <span class="text-muted">Unassigned</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($lead['interested_vehicle']): ?>
                                                            <small><?= htmlspecialchars($lead['interested_vehicle']) ?></small>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($lead['created_date'])) ?></td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="/crm/leads/view?id=<?= $lead['lead_id'] ?>" class="btn btn-outline-primary" title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="/crm/leads/edit?id=<?= $lead['lead_id'] ?>" class="btn btn-outline-secondary" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-outline-danger" onclick="deleteLead(<?= $lead['lead_id'] ?>)" title="Delete">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <form method="POST" id="deleteForm">
        <input type="hidden" name="delete_lead_id" id="deleteLeadId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteLead(leadId) {
            if (confirm('Are you sure you want to delete this lead? This action cannot be undone.')) {
                document.getElementById('deleteLeadId').value = leadId;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>
