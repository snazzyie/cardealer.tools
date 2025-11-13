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
        .navbar { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <h4 class="mb-0 text-danger"><i class="bi bi-shield me-2"></i> Super Admin</h4>
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

        <!-- System Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <div class="card-body">
                        <h6 class="text-white-50 mb-2">Total Companies</h6>
                        <h2 class="mb-0"><?= $total_companies ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Users</h6>
                        <h2 class="mb-0"><?= $total_users ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Vehicles</h6>
                        <h2 class="mb-0"><?= $total_vehicles ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">Total Leads</h6>
                        <h2 class="mb-0"><?= $total_leads ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Companies by Status -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">Companies by Status</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($companies_by_status as $status): ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $badgeColors = ['trial' => 'warning', 'active' => 'success', 'suspended' => 'danger'];
                                                $color = $badgeColors[$status['status']] ?? 'secondary';
                                                ?>
                                                <span class="badge bg-<?= $color ?>"><?= ucfirst($status['status']) ?></span>
                                            </td>
                                            <td class="text-end"><strong><?= $status['count'] ?></strong></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- All Companies -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="mb-4">All Companies</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Domain/Subdomain</th>
                                <th>Status</th>
                                <th>Trial Ends</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $company): ?>
                                <tr>
                                    <td><?= $company['company_id'] ?></td>
                                    <td><strong><?= htmlspecialchars($company['company_name']) ?></strong></td>
                                    <td>
                                        <?php if ($company['subdomain']): ?>
                                            <small><?= htmlspecialchars($company['subdomain']) ?>.cardealer.tools</small>
                                        <?php elseif ($company['domain']): ?>
                                            <small><?= htmlspecialchars($company['domain']) ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">Not set</small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php
                                        $badgeColors = ['trial' => 'warning', 'active' => 'success', 'suspended' => 'danger'];
                                        $color = $badgeColors[$company['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $color ?>"><?= ucfirst($company['status']) ?></span>
                                    </td>
                                    <td>
                                        <?php if ($company['trial_ends_at']): ?>
                                            <?= date('d M Y', strtotime($company['trial_ends_at'])) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d M Y', strtotime($company['created_date'])) ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" title="View">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-secondary" title="Impersonate">
                                                <i class="bi bi-person-fill-gear"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
