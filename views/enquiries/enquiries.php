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
            <div class="col-md-2 sidebar p-0">
                <div class="p-3"><h4 class="text-primary mb-4">Car Dealer</h4></div>
                <nav>
                    <a href="/dash"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="/vehicles"><i class="bi bi-car-front me-2"></i> Vehicles</a>
                    <a href="/crm"><i class="bi bi-people me-2"></i> CRM</a>
                    <a href="/calendar"><i class="bi bi-calendar me-2"></i> Calendar</a>
                    <a href="/enquiries" class="active"><i class="bi bi-envelope me-2"></i> Enquiries</a>
                    <a href="/customers"><i class="bi bi-person me-2"></i> Customers</a>
                    <a href="/invoices"><i class="bi bi-receipt me-2"></i> Invoices</a>
                    <a href="/inbox"><i class="bi bi-inbox me-2"></i> Inbox</a>
                    <a href="/reports"><i class="bi bi-graph-up me-2"></i> Reports</a>
                </nav>
            </div>
            <div class="col-md-10 p-0">
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
                <div class="container-fluid p-4">
                    <h6 class="text-muted mb-4"><?= $page_header['subtitle'] ?></h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <h3 class="text-primary"><?= $stats['total'] ?? 0 ?></h3>
                                    <small class="text-muted">Total</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-warning text-white">
                                <div class="card-body text-center">
                                    <h3><?= $stats['pending'] ?? 0 ?></h3>
                                    <small>Pending</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-success text-white">
                                <div class="card-body text-center">
                                    <h3><?= $stats['responded'] ?? 0 ?></h3>
                                    <small>Responded</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 shadow-sm bg-info text-white">
                                <div class="card-body text-center">
                                    <h3><?= $stats['today'] ?? 0 ?></h3>
                                    <small>Today</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact</th>
                                            <th>Vehicle</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($enquiries)): ?>
                                            <tr><td colspan="7" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>No enquiries found
                                            </td></tr>
                                        <?php else: ?>
                                            <?php foreach ($enquiries as $enq): ?>
                                                <tr>
                                                    <td><strong><?= htmlspecialchars($enq['name']) ?></strong></td>
                                                    <td>
                                                        <div><i class="bi bi-envelope me-1"></i><?= htmlspecialchars($enq['email']) ?></div>
                                                        <?php if ($enq['phone']): ?>
                                                            <div><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($enq['phone']) ?></div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($enq['vehicle_info'] ?? '-') ?></td>
                                                    <td><?= ucfirst($enq['enquiry_type']) ?></td>
                                                    <td>
                                                        <span class="badge bg-<?= $enq['status'] === 'pending' ? 'warning' : ($enq['status'] === 'responded' ? 'success' : 'secondary') ?>">
                                                            <?= ucfirst($enq['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($enq['created_date'])) ?></td>
                                                    <td>
                                                        <a href="/enquiries/view?id=<?= $enq['enquiry_id'] ?>" class="btn btn-sm btn-outline-primary">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
