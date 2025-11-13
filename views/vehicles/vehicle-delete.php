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
                    <a href="/vehicles" class="active"><i class="bi bi-car-front me-2"></i> Vehicles</a>
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
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-5 text-center">
                                    <div class="mb-4">
                                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 4rem;"></i>
                                    </div>

                                    <h3 class="mb-3">Delete Vehicle</h3>

                                    <?php if (isset($error)): ?>
                                        <div class="alert alert-danger">
                                            <?= htmlspecialchars($error) ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (isset($vehicle)): ?>
                                        <div class="card bg-light mb-4">
                                            <div class="card-body">
                                                <h5><?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?></h5>
                                                <p class="text-muted mb-0">
                                                    <?= htmlspecialchars($vehicle['year']) ?>
                                                    <?php if ($vehicle['registration']): ?>
                                                        • <?= htmlspecialchars($vehicle['registration']) ?>
                                                    <?php endif; ?>
                                                    <?php if ($vehicle['price']): ?>
                                                        • €<?= number_format($vehicle['price'], 2) ?>
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        </div>

                                        <p class="text-muted mb-4">
                                            Are you sure you want to delete this vehicle? This action cannot be undone.
                                        </p>

                                        <?php if ($has_enquiries ?? false): ?>
                                            <div class="alert alert-warning text-start">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                <strong>Warning:</strong> This vehicle has associated enquiries.
                                                Deleting it will also remove all related data.
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($has_invoices ?? false): ?>
                                            <div class="alert alert-danger text-start">
                                                <i class="bi bi-x-circle me-2"></i>
                                                <strong>Cannot Delete:</strong> This vehicle has associated sales invoices.
                                                Please archive it instead or contact support.
                                            </div>
                                        <?php endif; ?>

                                        <form method="POST" action="/vehicles/delete?id=<?= $vehicle['vehicle_id'] ?>">
                                            <div class="mb-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="confirmDelete" name="confirm" required>
                                                    <label class="form-check-label" for="confirmDelete">
                                                        I understand this action cannot be undone
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="d-grid gap-2">
                                                <?php if (!($has_invoices ?? false)): ?>
                                                    <button type="submit" class="btn btn-danger btn-lg">
                                                        <i class="bi bi-trash me-2"></i> Delete Vehicle
                                                    </button>
                                                <?php endif; ?>
                                                <a href="/vehicles" class="btn btn-outline-secondary btn-lg">
                                                    Cancel
                                                </a>
                                            </div>
                                        </form>
                                    <?php endif; ?>
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
