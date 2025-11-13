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
                    <a href="/enquiries"><i class="bi bi-envelope me-2"></i> Enquiries</a>
                    <a href="/customers" class="active"><i class="bi bi-person me-2"></i> Customers</a>
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
                    <div class="d-flex justify-content-between mb-4">
                        <a href="/customers" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-2"></i> Back to Customers</a>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body p-4">
                                    <h3 class="mb-4"><?= htmlspecialchars($customer['first_name'] . ' ' . $customer['last_name']) ?></h3>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted">Contact Information</h6>
                                            <p><i class="bi bi-envelope me-2"></i><a href="mailto:<?= htmlspecialchars($customer['email']) ?>"><?= htmlspecialchars($customer['email']) ?></a></p>
                                            <?php if ($customer['phone']): ?>
                                                <p><i class="bi bi-telephone me-2"></i><a href="tel:<?= htmlspecialchars($customer['phone']) ?>"><?= htmlspecialchars($customer['phone']) ?></a></p>
                                            <?php endif; ?>
                                            <?php if ($customer['address']): ?>
                                                <p><i class="bi bi-geo-alt me-2"></i><?= nl2br(htmlspecialchars($customer['address'])) ?></p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <h6 class="text-muted">Customer Since</h6>
                                            <p><?= date('d M Y', strtotime($customer['created_date'])) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="mb-4">Purchase History</h5>
                                    <?php if (empty($purchases)): ?>
                                        <p class="text-muted text-center py-4">No purchases yet</p>
                                    <?php else: ?>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Invoice #</th>
                                                        <th>Vehicle</th>
                                                        <th>Amount</th>
                                                        <th>Date</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($purchases as $p): ?>
                                                        <tr>
                                                            <td><?= htmlspecialchars($p['invoice_number']) ?></td>
                                                            <td><?= htmlspecialchars($p['vehicle_info']) ?></td>
                                                            <td>€<?= number_format($p['total_amount'], 2) ?></td>
                                                            <td><?= date('d/m/Y', strtotime($p['sale_date'])) ?></td>
                                                            <td><span class="badge bg-success"><?= ucfirst($p['status']) ?></span></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-3">Total Spent</h6>
                                    <h2 class="text-primary">€<?= number_format($total_spent ?? 0, 2) ?></h2>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body text-center">
                                    <h6 class="text-muted mb-3">Total Purchases</h6>
                                    <h2 class="text-success"><?= count($purchases ?? []) ?></h2>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="mb-3">Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="mailto:<?= htmlspecialchars($customer['email']) ?>" class="btn btn-outline-primary"><i class="bi bi-envelope me-2"></i> Send Email</a>
                                        <?php if ($customer['phone']): ?>
                                            <a href="tel:<?= htmlspecialchars($customer['phone']) ?>" class="btn btn-outline-success"><i class="bi bi-telephone me-2"></i> Call</a>
                                        <?php endif; ?>
                                        <a href="/invoices/sales/new?customer_id=<?= $customer['customer_id'] ?>" class="btn btn-outline-info"><i class="bi bi-file-earmark-plus me-2"></i> New Invoice</a>
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
