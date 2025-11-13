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
        .pipeline-container { display: flex; gap: 15px; overflow-x: auto; padding-bottom: 20px; }
        .pipeline-column { min-width: 280px; flex: 0 0 280px; background: #fff; border-radius: 8px; padding: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .pipeline-header { font-weight: 600; margin-bottom: 15px; padding-bottom: 10px; border-bottom: 2px solid #dee2e6; display: flex; justify-content: space-between; align-items: center; }
        .lead-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 12px; margin-bottom: 10px; cursor: pointer; transition: all 0.2s; }
        .lead-card:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.15); transform: translateY(-2px); }
        .lead-name { font-weight: 600; margin-bottom: 5px; }
        .lead-detail { font-size: 0.875rem; color: #6c757d; margin-bottom: 3px; }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
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
                    <a href="/crm" class="active"><i class="bi bi-people me-2"></i> CRM</a>
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-muted mb-0"><?= $page_header['subtitle'] ?></h6>
                        <a href="/crm/leads/new" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Add Lead
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card stat-card border-0">
                                <div class="card-body">
                                    <h6 class="text-white-50 mb-2">Active Leads</h6>
                                    <h2 class="mb-0"><?= $stats['total_leads'] ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Won</h6>
                                    <h2 class="mb-0 text-success"><?= $stats['won'] ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Lost</h6>
                                    <h2 class="mb-0 text-danger"><?= $stats['lost'] ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Conversion Rate</h6>
                                    <h2 class="mb-0"><?= $stats['conversion_rate'] ?>%</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pipeline -->
                    <div class="pipeline-container">
                        <!-- New Leads -->
                        <div class="pipeline-column">
                            <div class="pipeline-header">
                                <span><i class="bi bi-inbox text-primary me-2"></i> New</span>
                                <span class="badge bg-primary"><?= count($pipeline['new']) ?></span>
                            </div>
                            <?php foreach ($pipeline['new'] as $lead): ?>
                                <div class="lead-card" onclick="window.location='/crm/leads/view/<?= $lead['lead_id'] ?>'">
                                    <div class="lead-name"><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></div>
                                    <?php if ($lead['email']): ?>
                                        <div class="lead-detail"><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($lead['email']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['phone']): ?>
                                        <div class="lead-detail"><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($lead['phone']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['make']): ?>
                                        <div class="lead-detail"><i class="bi bi-car-front me-1"></i> <?= htmlspecialchars($lead['year'] . ' ' . $lead['make'] . ' ' . $lead['model']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['assigned_first_name']): ?>
                                        <div class="lead-detail mt-2"><i class="bi bi-person me-1"></i> <?= htmlspecialchars($lead['assigned_first_name']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Contacted -->
                        <div class="pipeline-column">
                            <div class="pipeline-header">
                                <span><i class="bi bi-chat text-info me-2"></i> Contacted</span>
                                <span class="badge bg-info"><?= count($pipeline['contacted']) ?></span>
                            </div>
                            <?php foreach ($pipeline['contacted'] as $lead): ?>
                                <div class="lead-card" onclick="window.location='/crm/leads/view/<?= $lead['lead_id'] ?>'">
                                    <div class="lead-name"><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></div>
                                    <?php if ($lead['email']): ?>
                                        <div class="lead-detail"><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($lead['email']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['make']): ?>
                                        <div class="lead-detail"><i class="bi bi-car-front me-1"></i> <?= htmlspecialchars($lead['year'] . ' ' . $lead['make'] . ' ' . $lead['model']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Qualified -->
                        <div class="pipeline-column">
                            <div class="pipeline-header">
                                <span><i class="bi bi-check-circle text-success me-2"></i> Qualified</span>
                                <span class="badge bg-success"><?= count($pipeline['qualified']) ?></span>
                            </div>
                            <?php foreach ($pipeline['qualified'] as $lead): ?>
                                <div class="lead-card" onclick="window.location='/crm/leads/view/<?= $lead['lead_id'] ?>'">
                                    <div class="lead-name"><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></div>
                                    <?php if ($lead['email']): ?>
                                        <div class="lead-detail"><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($lead['email']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['make']): ?>
                                        <div class="lead-detail"><i class="bi bi-car-front me-1"></i> <?= htmlspecialchars($lead['year'] . ' ' . $lead['make'] . ' ' . $lead['model']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Proposal -->
                        <div class="pipeline-column">
                            <div class="pipeline-header">
                                <span><i class="bi bi-file-text text-warning me-2"></i> Proposal</span>
                                <span class="badge bg-warning"><?= count($pipeline['proposal']) ?></span>
                            </div>
                            <?php foreach ($pipeline['proposal'] as $lead): ?>
                                <div class="lead-card" onclick="window.location='/crm/leads/view/<?= $lead['lead_id'] ?>'">
                                    <div class="lead-name"><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></div>
                                    <?php if ($lead['price']): ?>
                                        <div class="lead-detail"><i class="bi bi-currency-euro me-1"></i> €<?= number_format($lead['price'], 0) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['make']): ?>
                                        <div class="lead-detail"><i class="bi bi-car-front me-1"></i> <?= htmlspecialchars($lead['year'] . ' ' . $lead['make'] . ' ' . $lead['model']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Negotiation -->
                        <div class="pipeline-column">
                            <div class="pipeline-header">
                                <span><i class="bi bi-currency-exchange text-secondary me-2"></i> Negotiation</span>
                                <span class="badge bg-secondary"><?= count($pipeline['negotiation']) ?></span>
                            </div>
                            <?php foreach ($pipeline['negotiation'] as $lead): ?>
                                <div class="lead-card" onclick="window.location='/crm/leads/view/<?= $lead['lead_id'] ?>'">
                                    <div class="lead-name"><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></div>
                                    <?php if ($lead['price']): ?>
                                        <div class="lead-detail"><i class="bi bi-currency-euro me-1"></i> €<?= number_format($lead['price'], 0) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['make']): ?>
                                        <div class="lead-detail"><i class="bi bi-car-front me-1"></i> <?= htmlspecialchars($lead['year'] . ' ' . $lead['make'] . ' ' . $lead['model']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Won -->
                        <div class="pipeline-column">
                            <div class="pipeline-header">
                                <span><i class="bi bi-trophy text-success me-2"></i> Won</span>
                                <span class="badge bg-success"><?= count($pipeline['won']) ?></span>
                            </div>
                            <?php foreach ($pipeline['won'] as $lead): ?>
                                <div class="lead-card" onclick="window.location='/crm/leads/view/<?= $lead['lead_id'] ?>'">
                                    <div class="lead-name"><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></div>
                                    <?php if ($lead['price']): ?>
                                        <div class="lead-detail"><i class="bi bi-currency-euro me-1"></i> €<?= number_format($lead['price'], 0) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['make']): ?>
                                        <div class="lead-detail"><i class="bi bi-car-front me-1"></i> <?= htmlspecialchars($lead['year'] . ' ' . $lead['make'] . ' ' . $lead['model']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Lost -->
                        <div class="pipeline-column">
                            <div class="pipeline-header">
                                <span><i class="bi bi-x-circle text-danger me-2"></i> Lost</span>
                                <span class="badge bg-danger"><?= count($pipeline['lost']) ?></span>
                            </div>
                            <?php foreach ($pipeline['lost'] as $lead): ?>
                                <div class="lead-card" onclick="window.location='/crm/leads/view/<?= $lead['lead_id'] ?>'">
                                    <div class="lead-name"><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></div>
                                    <?php if ($lead['email']): ?>
                                        <div class="lead-detail"><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($lead['email']) ?></div>
                                    <?php endif; ?>
                                    <?php if ($lead['make']): ?>
                                        <div class="lead-detail"><i class="bi bi-car-front me-1"></i> <?= htmlspecialchars($lead['year'] . ' ' . $lead['make'] . ' ' . $lead['model']) ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
