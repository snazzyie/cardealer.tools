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
        .partner-card { transition: all 0.3s; cursor: pointer; height: 100%; }
        .partner-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
        .partner-logo { height: 60px; object-fit: contain; }
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
                    <a href="/finance-partners" class="active"><i class="bi bi-credit-card me-2"></i> Finance</a>
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

                    <div class="alert alert-info mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Simplified Finance Process:</strong> Share these partner links with customers. They can apply directly with the finance company. Use CRM to track which customers are interested in finance.
                    </div>

                    <!-- Finance Partners Grid -->
                    <div class="row g-4 mb-4">
                        <?php foreach ($finance_partners as $partner): ?>
                            <div class="col-md-6">
                                <a href="<?= htmlspecialchars($partner['url']) ?>" target="_blank" class="text-decoration-none">
                                    <div class="card partner-card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="<?= htmlspecialchars($partner['logo']) ?>" alt="<?= htmlspecialchars($partner['name']) ?>" class="partner-logo me-3">
                                                <div class="flex-grow-1">
                                                    <h5 class="mb-1"><?= htmlspecialchars($partner['name']) ?></h5>
                                                    <p class="text-muted mb-0 small"><?= htmlspecialchars($partner['description']) ?></p>
                                                </div>
                                                <i class="bi bi-arrow-right-circle fs-3 text-primary"></i>
                                            </div>

                                            <div class="d-flex gap-2 flex-wrap">
                                                <?php foreach ($partner['types'] as $type): ?>
                                                    <span class="badge bg-light text-dark"><?= htmlspecialchars($type) ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- How It Works -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4">How to Use Finance Partners</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="bi bi-1-circle-fill fs-2 text-primary"></i>
                                        </div>
                                        <div>
                                            <h6>Discuss with Customer</h6>
                                            <p class="text-muted small">Use the finance calculator on vehicle pages to show monthly payments. Determine customer's budget and preferred terms.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="bi bi-2-circle-fill fs-2 text-primary"></i>
                                        </div>
                                        <div>
                                            <h6>Share Partner Link</h6>
                                            <p class="text-muted small">Send customer the appropriate finance partner link via email or WhatsApp. Customer applies directly with the lender.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="bi bi-3-circle-fill fs-2 text-primary"></i>
                                        </div>
                                        <div>
                                            <h6>Track in CRM</h6>
                                            <p class="text-muted small">Add notes to the lead in CRM about finance interest. Follow up after a few days to check application status.</p>
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
