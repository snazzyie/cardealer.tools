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
                    <a href="/invoices" class="active"><i class="bi bi-receipt me-2"></i> Invoices</a>
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

                    <!-- Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                <div class="card-body">
                                    <h6 class="text-white-50 mb-2">Sales This Month</h6>
                                    <h2 class="mb-0">€<?= number_format($stats['sales_this_month'], 0) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Pending Payments</h6>
                                    <h2 class="mb-0 text-warning">€<?= number_format($stats['pending_payments'], 0) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Service Revenue</h6>
                                    <h2 class="mb-0 text-success">€<?= number_format($stats['service_revenue'], 0) ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-4">
                        <li class="nav-item">
                            <a class="nav-link <?= $type === 'sales' ? 'active' : '' ?>" href="?type=sales">
                                <i class="bi bi-car-front me-2"></i> Sales Invoices
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $type === 'service' ? 'active' : '' ?>" href="?type=service">
                                <i class="bi bi-tools me-2"></i> Service Invoices
                            </a>
                        </li>
                    </ul>

                    <!-- Invoices Table -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <?php if (empty($invoices)): ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                                    <h5 class="text-muted mt-3">No invoices yet</h5>
                                    <p class="text-muted">Create your first invoice to get started</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Customer</th>
                                                <?php if ($type === 'sales'): ?>
                                                    <th>Vehicle</th>
                                                <?php endif; ?>
                                                <th>Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($invoices as $invoice): ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars($invoice['invoice_number']) ?></strong></td>
                                                    <td><?= htmlspecialchars($invoice['first_name'] . ' ' . $invoice['last_name']) ?></td>
                                                    <?php if ($type === 'sales'): ?>
                                                        <td><?= htmlspecialchars(($invoice['year'] ?? '') . ' ' . ($invoice['make'] ?? '') . ' ' . ($invoice['model'] ?? '')) ?></td>
                                                    <?php endif; ?>
                                                    <td><?= date('d M Y', strtotime($invoice['invoice_date'])) ?></td>
                                                    <td><strong>€<?= number_format($invoice['total_amount'], 2) ?></strong></td>
                                                    <td>
                                                        <?php
                                                        $statusColors = [
                                                            'pending' => 'warning',
                                                            'sent' => 'info',
                                                            'paid' => 'success',
                                                            'cancelled' => 'danger'
                                                        ];
                                                        $statusColor = $statusColors[$invoice['status']] ?? 'secondary';
                                                        ?>
                                                        <span class="badge bg-<?= $statusColor ?>">
                                                            <?= ucfirst($invoice['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-outline-primary" title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </button>
                                                            <button class="btn btn-outline-secondary" title="Download PDF">
                                                                <i class="bi bi-download"></i>
                                                            </button>
                                                        </div>
                                                    </td>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
