<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>body{min-height:100vh;background:#f8f9fa}.sidebar{min-height:100vh;background:#fff;box-shadow:2px 0 5px rgba(0,0,0,0.1)}.sidebar a{color:#333;text-decoration:none;padding:12px 20px;display:block;transition:all 0.3s}.sidebar a:hover,.sidebar a.active{background:#e9ecef;color:#007bff}.navbar{background:#fff;box-shadow:0 2px 5px rgba(0,0,0,0.1)}</style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 sidebar p-0">
                <div class="p-3"><h4 class="text-primary mb-4">Car Dealer</h4></div>
                <nav><a href="/super-admin" class="active"><i class="bi bi-shield me-2"></i> Super Admin</a></nav>
            </div>
            <div class="col-md-10 p-0">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <h5 class="mb-0"><?= $page_header['title'] ?></h5>
                        <div class="d-flex align-items-center">
                            <span class="me-3"><?= htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']) ?></span>
                            <a href="/logout" class="btn btn-outline-danger btn-sm">Logout</a>
                        </div>
                    </div>
                </nav>
                <div class="container-fluid p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-2"><div class="card border-0 shadow-sm"><div class="card-body text-center"><h3 class="text-primary"><?= $stats['total_dealers'] ?? 0 ?></h3><small class="text-muted">Total Dealers</small></div></div></div>
                        <div class="col-md-2"><div class="card border-0 shadow-sm bg-success text-white"><div class="card-body text-center"><h3><?= $stats['active_subscriptions'] ?? 0 ?></h3><small>Active</small></div></div></div>
                        <div class="col-md-2"><div class="card border-0 shadow-sm bg-warning text-white"><div class="card-body text-center"><h3><?= $stats['trial_subscriptions'] ?? 0 ?></h3><small>Trials</small></div></div></div>
                        <div class="col-md-2"><div class="card border-0 shadow-sm bg-info text-white"><div class="card-body text-center"><h3><?= $stats['total_users'] ?? 0 ?></h3><small>Users</small></div></div></div>
                        <div class="col-md-2"><div class="card border-0 shadow-sm bg-secondary text-white"><div class="card-body text-center"><h3><?= $stats['total_vehicles'] ?? 0 ?></h3><small>Vehicles</small></div></div></div>
                        <div class="col-md-2"><div class="card border-0 shadow-sm bg-dark text-white"><div class="card-body text-center"><h3>€<?= number_format($stats['mrr'] ?? 0) ?></h3><small>MRR</small></div></div></div>
                    </div>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr><th>Company</th><th>Users</th><th>Vehicles</th><th>Leads</th><th>Plan</th><th>Status</th><th>Actions</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($dealers)): ?>
                                            <tr><td colspan="7" class="text-center text-muted py-5"><i class="bi bi-inbox fs-1 d-block mb-3"></i>No dealers found</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($dealers as $d): ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars($d['company_name']) ?></strong><br><small class="text-muted"><?= htmlspecialchars($d['company_email']) ?></small></td>
                                                    <td><?= $d['user_count'] ?? 0 ?></td>
                                                    <td><?= $d['vehicle_count'] ?? 0 ?></td>
                                                    <td><?= $d['lead_count'] ?? 0 ?></td>
                                                    <td><?= htmlspecialchars($d['plan_name'] ?? 'None') ?></td>
                                                    <td><span class="badge bg-<?= ($d['subscription_status'] ?? 'inactive') === 'active' ? 'success' : 'warning' ?>"><?= ucfirst($d['subscription_status'] ?? 'inactive') ?></span></td>
                                                    <td><a href="/super-admin/dealers/view?id=<?= $d['company_id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
