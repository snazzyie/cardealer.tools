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
                    <a href="/dash" class="active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="/vehicles"><i class="bi bi-car-front me-2"></i> Vehicles</a>
                    <a href="/crm"><i class="bi bi-people me-2"></i> CRM</a>
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
                    <?php if ($permission == 10): ?>
                        <hr>
                        <a href="/super-admin"><i class="bi bi-shield me-2"></i> Super Admin</a>
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

                    <?php if (!$company_id): ?>
                        <div class="alert alert-warning">
                            <h5 class="alert-heading">Welcome! Let's get started</h5>
                            <p>To begin using the platform, you need to set up your company profile.</p>
                            <a href="/company/edit" class="btn btn-primary">Set Up Company Profile</a>
                        </div>
                    <?php endif; ?>

                    <!-- Stats Cards -->
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card stat-card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Total Vehicles</h6>
                                            <h2 class="mb-0"><?= $stats['total_vehicles'] ?></h2>
                                        </div>
                                        <div class="text-primary" style="font-size: 3rem;">
                                            <i class="bi bi-car-front"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card stat-card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">Active Leads</h6>
                                            <h2 class="mb-0"><?= $stats['active_leads'] ?></h2>
                                        </div>
                                        <div class="text-success" style="font-size: 3rem;">
                                            <i class="bi bi-person-check"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card stat-card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">New Enquiries</h6>
                                            <h2 class="mb-0"><?= $stats['pending_enquiries'] ?></h2>
                                        </div>
                                        <div class="text-warning" style="font-size: 3rem;">
                                            <i class="bi bi-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card stat-card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="text-muted mb-2">This Month Sales</h6>
                                            <h2 class="mb-0"><?= $stats['this_month_sales'] ?></h2>
                                        </div>
                                        <div class="text-info" style="font-size: 3rem;">
                                            <i class="bi bi-graph-up"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title mb-4">Quick Actions</h5>
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <a href="/vehicles/new" class="btn btn-outline-primary w-100 py-3">
                                                <i class="bi bi-plus-circle me-2"></i> Add Vehicle
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="/crm/leads/new" class="btn btn-outline-success w-100 py-3">
                                                <i class="bi bi-person-plus me-2"></i> Add Lead
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="/calendar/new" class="btn btn-outline-info w-100 py-3">
                                                <i class="bi bi-calendar-plus me-2"></i> Schedule Appointment
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <a href="/invoices/sales/new" class="btn btn-outline-warning w-100 py-3">
                                                <i class="bi bi-file-earmark-plus me-2"></i> Create Invoice
                                            </a>
                                        </div>
                                    </div>
                                </div>
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
