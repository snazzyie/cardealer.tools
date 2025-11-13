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
                    <a href="/crm"><i class="bi bi-people me-2"></i> CRM</a>
                    <a href="/calendar"><i class="bi bi-calendar me-2"></i> Calendar</a>
                    <a href="/enquiries"><i class="bi bi-envelope me-2"></i> Enquiries</a>
                    <a href="/customers"><i class="bi bi-person me-2"></i> Customers</a>
                    <a href="/invoices"><i class="bi bi-receipt me-2"></i> Invoices</a>
                    <a href="/inbox"><i class="bi bi-inbox me-2"></i> Inbox</a>
                    <a href="/reports" class="active"><i class="bi bi-graph-up me-2"></i> Reports</a>
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-muted mb-0"><?= $page_header['subtitle'] ?></h6>
                        <form method="GET" class="d-flex gap-2">
                            <input type="date" class="form-control form-control-sm" name="start_date" value="<?= $start_date ?>">
                            <input type="date" class="form-control form-control-sm" name="end_date" value="<?= $end_date ?>">
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                        </form>
                    </div>

                    <!-- Key Metrics -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Total Sales</h6>
                                    <h2 class="mb-0"><?= $sales_report['total_sales'] ?? 0 ?></h2>
                                    <small class="text-success">€<?= number_format($sales_report['revenue'] ?? 0, 0) ?> revenue</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Enquiries</h6>
                                    <h2 class="mb-0"><?= $enquiries_report['period_enquiries'] ?? 0 ?></h2>
                                    <small class="text-muted">This period</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Appointments</h6>
                                    <h2 class="mb-0"><?= $appointments_report['total_appointments'] ?? 0 ?></h2>
                                    <small class="text-success"><?= $appointments_report['completed'] ?? 0 ?> completed</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Conversion Rate</h6>
                                    <h2 class="mb-0"><?= $conversion_rate ?>%</h2>
                                    <small class="text-muted">Lead to sale</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reports Row -->
                    <div class="row">
                        <!-- Leads by Status -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="mb-4">Leads by Status</h5>
                                    <?php if (empty($leads_by_status)): ?>
                                        <p class="text-muted">No leads in this period</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Status</th>
                                                        <th class="text-end">Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($leads_by_status as $lead): ?>
                                                        <tr>
                                                            <td><?= ucfirst($lead['status']) ?></td>
                                                            <td class="text-end"><strong><?= $lead['count'] ?></strong></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Popular Vehicles -->
                        <div class="col-md-6 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="mb-4">Most Enquired Vehicles</h5>
                                    <?php if (empty($popular_vehicles)): ?>
                                        <p class="text-muted">No data available</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Vehicle</th>
                                                        <th class="text-end">Enquiries</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (array_slice($popular_vehicles, 0, 10) as $vehicle): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?></td>
                                                            <td class="text-end"><strong><?= $vehicle['enquiry_count'] ?></strong></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Export Options -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-3">Export Reports</h6>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary" disabled>
                                    <i class="bi bi-download me-2"></i> Export to CSV
                                </button>
                                <button class="btn btn-outline-danger" disabled>
                                    <i class="bi bi-file-pdf me-2"></i> Export to PDF
                                </button>
                            </div>
                            <small class="text-muted d-block mt-2">Export functionality coming soon</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
