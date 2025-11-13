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
        .color-preview { width: 50px; height: 50px; border-radius: 8px; border: 2px solid #dee2e6; display: inline-block; vertical-align: middle; }
        .logo-preview { max-width: 200px; max-height: 100px; border: 1px solid #dee2e6; padding: 10px; border-radius: 8px; }
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
                                    <form method="POST" action="/company/branding" id="brandingForm">
                                        <h5 class="mb-4">Logo & Images</h5>

                                        <div class="mb-4">
                                            <label for="logo_url" class="form-label">Logo URL</label>
                                            <input type="url" class="form-control" id="logo_url" name="logo_url"
                                                   value="<?= htmlspecialchars($company_data['logo_url'] ?? '') ?>"
                                                   placeholder="https://example.com/logo.png">
                                            <div class="form-text">Upload your logo and paste the URL here</div>
                                            <?php if (!empty($company_data['logo_url'])): ?>
                                                <div class="mt-2">
                                                    <img src="<?= htmlspecialchars($company_data['logo_url']) ?>" class="logo-preview" alt="Logo preview">
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mb-4">
                                            <label for="favicon_url" class="form-label">Favicon URL</label>
                                            <input type="url" class="form-control" id="favicon_url" name="favicon_url"
                                                   value="<?= htmlspecialchars($company_data['favicon_url'] ?? '') ?>"
                                                   placeholder="https://example.com/favicon.ico">
                                            <div class="form-text">Small icon shown in browser tabs (16x16 or 32x32 pixels)</div>
                                        </div>

                                        <hr class="my-4">

                                        <h5 class="mb-4">Brand Colors</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="primary_color" class="form-label">Primary Color</label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color"
                                                           value="<?= htmlspecialchars($company_data['primary_color'] ?? '#007bff') ?>">
                                                    <input type="text" class="form-control" id="primary_color_hex"
                                                           value="<?= htmlspecialchars($company_data['primary_color'] ?? '#007bff') ?>" readonly>
                                                </div>
                                                <div class="form-text">Main brand color used for buttons and highlights</div>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="secondary_color" class="form-label">Secondary Color</label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color" id="secondary_color" name="secondary_color"
                                                           value="<?= htmlspecialchars($company_data['secondary_color'] ?? '#6c757d') ?>">
                                                    <input type="text" class="form-control" id="secondary_color_hex"
                                                           value="<?= htmlspecialchars($company_data['secondary_color'] ?? '#6c757d') ?>" readonly>
                                                </div>
                                                <div class="form-text">Secondary accent color</div>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <h5 class="mb-4">Social Media</h5>

                                        <div class="mb-3">
                                            <label for="social_facebook" class="form-label">
                                                <i class="bi bi-facebook text-primary me-2"></i> Facebook URL
                                            </label>
                                            <input type="url" class="form-control" id="social_facebook" name="social_facebook"
                                                   value="<?= htmlspecialchars($company_data['social_facebook'] ?? '') ?>"
                                                   placeholder="https://facebook.com/yourpage">
                                        </div>

                                        <div class="mb-3">
                                            <label for="social_instagram" class="form-label">
                                                <i class="bi bi-instagram text-danger me-2"></i> Instagram URL
                                            </label>
                                            <input type="url" class="form-control" id="social_instagram" name="social_instagram"
                                                   value="<?= htmlspecialchars($company_data['social_instagram'] ?? '') ?>"
                                                   placeholder="https://instagram.com/yourpage">
                                        </div>

                                        <div class="mb-3">
                                            <label for="social_twitter" class="form-label">
                                                <i class="bi bi-twitter text-info me-2"></i> Twitter/X URL
                                            </label>
                                            <input type="url" class="form-control" id="social_twitter" name="social_twitter"
                                                   value="<?= htmlspecialchars($company_data['social_twitter'] ?? '') ?>"
                                                   placeholder="https://twitter.com/yourpage">
                                        </div>

                                        <div class="mb-3">
                                            <label for="social_linkedin" class="form-label">
                                                <i class="bi bi-linkedin text-primary me-2"></i> LinkedIn URL
                                            </label>
                                            <input type="url" class="form-control" id="social_linkedin" name="social_linkedin"
                                                   value="<?= htmlspecialchars($company_data['social_linkedin'] ?? '') ?>"
                                                   placeholder="https://linkedin.com/company/yourpage">
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <a href="/company" class="btn btn-outline-secondary">Back to Company</a>
                                            <button type="submit" class="btn btn-primary">Save Branding</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Branding Tips</h6>
                                    <ul class="small text-muted">
                                        <li class="mb-2">Your logo should be a transparent PNG for best results</li>
                                        <li class="mb-2">Recommended logo size: 200x60 pixels</li>
                                        <li class="mb-2">Choose colors that match your existing brand</li>
                                        <li class="mb-2">Social media links help customers find you</li>
                                        <li class="mb-2">Favicon appears in browser tabs and bookmarks</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sync color picker with hex input
        document.getElementById('primary_color').addEventListener('input', function(e) {
            document.getElementById('primary_color_hex').value = e.target.value;
        });

        document.getElementById('secondary_color').addEventListener('input', function(e) {
            document.getElementById('secondary_color_hex').value = e.target.value;
        });
    </script>
</body>
</html>
