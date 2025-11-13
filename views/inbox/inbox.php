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
        .message-card { cursor: pointer; transition: all 0.2s; }
        .message-card:hover { box-shadow: 0 4px 8px rgba(0,0,0,0.15); }
        .message-type { text-transform: uppercase; font-size: 0.75rem; font-weight: 600; }
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
                    <a href="/inbox" class="active"><i class="bi bi-inbox me-2"></i> Inbox</a>
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
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Total Messages</h6>
                                    <h2 class="mb-0"><?= count($communications) ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Enquiries</h6>
                                    <h2 class="mb-0 text-primary"><?= $enquiries_count ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">New Leads</h6>
                                    <h2 class="mb-0 text-success"><?= $leads_count ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <?php if (empty($communications)): ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                                    <h5 class="text-muted mt-3">No messages yet</h5>
                                    <p class="text-muted">Customer enquiries and messages will appear here</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach ($communications as $comm): ?>
                                        <div class="list-group-item message-card">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <?php
                                                        $typeColors = [
                                                            'enquiry' => 'primary',
                                                            'lead' => 'success',
                                                            'email' => 'info',
                                                            'sms' => 'warning'
                                                        ];
                                                        $typeColor = $typeColors[$comm['type']] ?? 'secondary';
                                                        ?>
                                                        <span class="badge bg-<?= $typeColor ?> message-type me-2">
                                                            <?= $comm['type'] ?>
                                                        </span>
                                                        <strong><?= htmlspecialchars($comm['first_name'] . ' ' . $comm['last_name']) ?></strong>
                                                    </div>

                                                    <div class="mb-2">
                                                        <?php if ($comm['email']): ?>
                                                            <span class="text-muted me-3">
                                                                <i class="bi bi-envelope me-1"></i>
                                                                <?= htmlspecialchars($comm['email']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                        <?php if ($comm['phone']): ?>
                                                            <span class="text-muted">
                                                                <i class="bi bi-telephone me-1"></i>
                                                                <?= htmlspecialchars($comm['phone']) ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>

                                                    <?php if ($comm['content']): ?>
                                                        <p class="mb-2 text-muted">
                                                            <?= htmlspecialchars(substr($comm['content'], 0, 150)) ?>
                                                            <?= strlen($comm['content']) > 150 ? '...' : '' ?>
                                                        </p>
                                                    <?php endif; ?>

                                                    <?php if ($comm['make']): ?>
                                                        <small class="text-muted">
                                                            <i class="bi bi-car-front me-1"></i>
                                                            Interested in: <?= htmlspecialchars($comm['year'] . ' ' . $comm['make'] . ' ' . $comm['model']) ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>

                                                <div class="text-end">
                                                    <small class="text-muted">
                                                        <?= date('d M Y, g:i A', strtotime($comm['date'])) ?>
                                                    </small>
                                                    <div class="mt-2">
                                                        <?php if ($comm['type'] === 'enquiry'): ?>
                                                            <a href="/enquiries/view/<?= $comm['id'] ?>" class="btn btn-sm btn-outline-primary">
                                                                <i class="bi bi-eye me-1"></i> View
                                                            </a>
                                                        <?php elseif ($comm['type'] === 'lead'): ?>
                                                            <a href="/crm/leads/view/<?= $comm['id'] ?>" class="btn btn-sm btn-outline-success">
                                                                <i class="bi bi-eye me-1"></i> View
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
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
