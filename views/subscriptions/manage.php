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
        .usage-bar { height: 10px; border-radius: 10px; background: #e9ecef; overflow: hidden; }
        .usage-fill { height: 100%; background: linear-gradient(90deg, #667eea 0%, #764ba2 100%); transition: width 0.3s; }
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
                    <a href="/reports"><i class="bi bi-graph-up me-2"></i> Reports</a>
                    <?php if ($permission >= 2): ?>
                        <hr>
                        <a href="/users"><i class="bi bi-people me-2"></i> Users</a>
                        <a href="/company"><i class="bi bi-building me-2"></i> Company</a>
                        <a href="/subscriptions" class="active"><i class="bi bi-credit-card me-2"></i> Subscription</a>
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

                    <?php if ($success === 'cancelled'): ?>
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle me-2"></i>
                            Your subscription has been cancelled successfully.
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if ($subscription): ?>
                        <!-- Active Subscription -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-8">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <div class="d-flex justify-content-between align-items-start mb-4">
                                            <div>
                                                <h5 class="mb-2">Current Plan</h5>
                                                <h2 class="text-primary mb-0"><?= ucfirst($subscription['plan_id']) ?> Plan</h2>
                                            </div>
                                            <span class="badge bg-<?= $subscription['status'] === 'active' ? 'success' : 'warning' ?> fs-6">
                                                <?= ucfirst($subscription['status']) ?>
                                            </span>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <p class="text-muted mb-1">Subscription ID</p>
                                                <p class="fw-bold"><?= htmlspecialchars($subscription['stripe_subscription_id']) ?></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="text-muted mb-1">Current Period</p>
                                                <p class="fw-bold">
                                                    <?= date('d M Y', strtotime($subscription['current_period_start'])) ?> -
                                                    <?= date('d M Y', strtotime($subscription['current_period_end'])) ?>
                                                </p>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="d-flex gap-2">
                                            <a href="/subscriptions/plans" class="btn btn-primary">
                                                <i class="bi bi-arrow-up-circle me-2"></i> Upgrade Plan
                                            </a>
                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                                <i class="bi bi-x-circle me-2"></i> Cancel Subscription
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Usage</h5>

                                        <!-- Vehicles Usage -->
                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Vehicles</span>
                                                <span class="fw-bold">
                                                    <?= $vehicle_count ?> / <?= $limits['max_vehicles'] === 999999 ? '∞' : $limits['max_vehicles'] ?>
                                                </span>
                                            </div>
                                            <?php
                                            $vehicle_percentage = $limits['max_vehicles'] === 999999 ? 0 : ($vehicle_count / $limits['max_vehicles']) * 100;
                                            ?>
                                            <div class="usage-bar">
                                                <div class="usage-fill" style="width: <?= min($vehicle_percentage, 100) ?>%"></div>
                                            </div>
                                        </div>

                                        <!-- Users Usage -->
                                        <div class="mb-4">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-muted">Team Members</span>
                                                <span class="fw-bold">
                                                    <?= $user_count ?> / <?= $limits['max_users'] === 999999 ? '∞' : $limits['max_users'] ?>
                                                </span>
                                            </div>
                                            <?php
                                            $user_percentage = $limits['max_users'] === 999999 ? 0 : ($user_count / $limits['max_users']) * 100;
                                            ?>
                                            <div class="usage-bar">
                                                <div class="usage-fill" style="width: <?= min($user_percentage, 100) ?>%"></div>
                                            </div>
                                        </div>

                                        <!-- Features -->
                                        <div>
                                            <h6 class="mb-3">Features</h6>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="bi bi-<?= $limits['custom_domain'] ? 'check-circle-fill text-success' : 'x-circle text-muted' ?> me-2"></i>
                                                    Custom Domain
                                                </li>
                                                <li class="mb-2">
                                                    <i class="bi bi-<?= $limits['api_access'] ? 'check-circle-fill text-success' : 'x-circle text-muted' ?> me-2"></i>
                                                    API Access
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- No Active Subscription -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-5 text-center">
                                <i class="bi bi-credit-card text-muted" style="font-size: 4rem;"></i>
                                <h4 class="mt-4 mb-3">No Active Subscription</h4>
                                <p class="text-muted mb-4">
                                    <?php if ($company_data['status'] === 'trial'): ?>
                                        You are currently on a free trial. Subscribe to continue using all features after your trial ends.
                                    <?php else: ?>
                                        Subscribe to a plan to access all features and start managing your dealership.
                                    <?php endif; ?>
                                </p>
                                <a href="/subscriptions/plans" class="btn btn-primary btn-lg">
                                    <i class="bi bi-arrow-right-circle me-2"></i> View Plans
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Subscription</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="/subscriptions/manage">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="cancel">
                        <p>Are you sure you want to cancel your subscription?</p>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Your account will be downgraded and access to premium features will be lost at the end of the current billing period.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Subscription</button>
                        <button type="submit" class="btn btn-danger">Yes, Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
