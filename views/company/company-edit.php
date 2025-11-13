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
                    <a href="/reports"><i class="bi bi-graph-up me-2"></i> Reports</a>
                    <?php if ($permission >= 2): ?>
                        <hr>
                        <a href="/users"><i class="bi bi-people me-2"></i> Users</a>
                        <a href="/company" class="active"><i class="bi bi-building me-2"></i> Company</a>
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

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <form method="POST" action="/company/edit">
                                        <h5 class="mb-4">Company Information</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="company_name" class="form-label">Company Name *</label>
                                                <input type="text" class="form-control" id="company_name" name="company_name"
                                                       value="<?= htmlspecialchars($company_data['company_name'] ?? '') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="trading_name" class="form-label">Trading Name</label>
                                                <input type="text" class="form-control" id="trading_name" name="trading_name"
                                                       value="<?= htmlspecialchars($company_data['trading_name'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="company_email" class="form-label">Company Email</label>
                                                <input type="email" class="form-control" id="company_email" name="company_email"
                                                       value="<?= htmlspecialchars($company_data['company_email'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="company_phone" class="form-label">Company Phone</label>
                                                <input type="tel" class="form-control" id="company_phone" name="company_phone"
                                                       value="<?= htmlspecialchars($company_data['company_phone'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_address" class="form-label">Address</label>
                                            <textarea class="form-control" id="company_address" name="company_address" rows="2"><?= htmlspecialchars($company_data['company_address'] ?? '') ?></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control" id="city" name="city"
                                                       value="<?= htmlspecialchars($company_data['city'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="county" class="form-label">County</label>
                                                <input type="text" class="form-control" id="county" name="county"
                                                       value="<?= htmlspecialchars($company_data['county'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="postcode" class="form-label">Postcode</label>
                                                <input type="text" class="form-control" id="postcode" name="postcode"
                                                       value="<?= htmlspecialchars($company_data['postcode'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="country" class="form-label">Country</label>
                                            <select class="form-select" id="country" name="country">
                                                <option value="Ireland" <?= ($company_data['country'] ?? 'Ireland') === 'Ireland' ? 'selected' : '' ?>>Ireland</option>
                                                <option value="United Kingdom" <?= ($company_data['country'] ?? '') === 'United Kingdom' ? 'selected' : '' ?>>United Kingdom</option>
                                                <option value="Other" <?= ($company_data['country'] ?? '') === 'Other' ? 'selected' : '' ?>>Other</option>
                                            </select>
                                        </div>

                                        <hr class="my-4">

                                        <h5 class="mb-4">Business Details</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="vat_number" class="form-label">VAT Number</label>
                                                <input type="text" class="form-control" id="vat_number" name="vat_number"
                                                       value="<?= htmlspecialchars($company_data['vat_number'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="company_registration" class="form-label">Company Registration</label>
                                                <input type="text" class="form-control" id="company_registration" name="company_registration"
                                                       value="<?= htmlspecialchars($company_data['company_registration'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <h5 class="mb-4">Website Settings</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="subdomain" class="form-label">Subdomain</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="subdomain" name="subdomain"
                                                           value="<?= htmlspecialchars($company_data['subdomain'] ?? '') ?>"
                                                           placeholder="yourcompany">
                                                    <span class="input-group-text">.cardealer.tools</span>
                                                </div>
                                                <div class="form-text">Your unique subdomain for accessing the platform</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="domain" class="form-label">Custom Domain</label>
                                                <input type="text" class="form-control" id="domain" name="domain"
                                                       value="<?= htmlspecialchars($company_data['domain'] ?? '') ?>"
                                                       placeholder="www.yourcompany.com">
                                                <div class="form-text">Optional: Use your own domain name</div>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <a href="/dash" class="btn btn-outline-secondary">Cancel</a>
                                            <button type="submit" class="btn btn-primary">
                                                <?= $company_id ? 'Update Company' : 'Create Company' ?>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <?php if ($company_id): ?>
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Company Status</h6>
                                        <?php
                                        $statusColors = [
                                            'trial' => 'warning',
                                            'active' => 'success',
                                            'suspended' => 'danger'
                                        ];
                                        $statusColor = $statusColors[$company_data['status']] ?? 'secondary';
                                        ?>
                                        <span class="badge bg-<?= $statusColor ?> mb-3">
                                            <?= strtoupper($company_data['status']) ?>
                                        </span>

                                        <?php if ($company_data['status'] === 'trial'): ?>
                                            <p class="text-muted small mb-0">
                                                Trial ends: <?= date('d M Y', strtotime($company_data['trial_ends_at'])) ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Quick Links</h6>
                                        <div class="d-grid gap-2">
                                            <a href="/company/branding" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-palette me-2"></i> Customize Branding
                                            </a>
                                            <a href="/users" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-people me-2"></i> Manage Users
                                            </a>
                                            <a href="/subscriptions" class="btn btn-outline-primary btn-sm">
                                                <i class="bi bi-credit-card me-2"></i> Subscription Plans
                                            </a>
                                        </div>
                                    </div>
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
