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
                    <div class="d-flex justify-content-between mb-4">
                        <a href="/enquiries" class="text-decoration-none text-muted"><i class="bi bi-arrow-left me-2"></i> Back</a>
                        <form method="POST" action="/enquiries/update-status" class="d-inline">
                            <input type="hidden" name="enquiry_id" value="<?= $enquiry['enquiry_id'] ?>">
                            <select class="form-select d-inline-block w-auto" name="status" onchange="this.form.submit()">
                                <option value="pending" <?= $enquiry['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="responded" <?= $enquiry['status'] === 'responded' ? 'selected' : '' ?>>Responded</option>
                                <option value="closed" <?= $enquiry['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
                            </select>
                        </form>
                    </div>
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body p-4">
                                    <h4 class="mb-3"><?= htmlspecialchars($enquiry['name']) ?></h4>
                                    <p><i class="bi bi-envelope me-2"></i><a href="mailto:<?= htmlspecialchars($enquiry['email']) ?>"><?= htmlspecialchars($enquiry['email']) ?></a></p>
                                    <?php if ($enquiry['phone']): ?>
                                        <p><i class="bi bi-telephone me-2"></i><a href="tel:<?= htmlspecialchars($enquiry['phone']) ?>"><?= htmlspecialchars($enquiry['phone']) ?></a></p>
                                    <?php endif; ?>
                                    <hr>
                                    <h6 class="text-muted">Message</h6>
                                    <p><?= nl2br(htmlspecialchars($enquiry['message'])) ?></p>
                                    <?php if ($enquiry['vehicle_id']): ?>
                                        <hr>
                                        <h6 class="text-muted">Interested Vehicle</h6>
                                        <p><?= htmlspecialchars($enquiry['vehicle_make'] . ' ' . $enquiry['vehicle_model'] . ' ' . $enquiry['vehicle_year']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3">Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="mailto:<?= htmlspecialchars($enquiry['email']) ?>" class="btn btn-outline-primary"><i class="bi bi-envelope me-2"></i> Send Email</a>
                                        <?php if ($enquiry['phone']): ?>
                                            <a href="tel:<?= htmlspecialchars($enquiry['phone']) ?>" class="btn btn-outline-success"><i class="bi bi-telephone me-2"></i> Call</a>
                                        <?php endif; ?>
                                        <a href="/crm/leads/new?from_enquiry=<?= $enquiry['enquiry_id'] ?>" class="btn btn-outline-info"><i class="bi bi-person-plus me-2"></i> Convert to Lead</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="mb-3">Details</h6>
                                    <p class="mb-2"><strong>Type:</strong> <?= ucfirst($enquiry['enquiry_type']) ?></p>
                                    <p class="mb-2"><strong>Status:</strong> <span class="badge bg-<?= $enquiry['status'] === 'pending' ? 'warning' : 'success' ?>"><?= ucfirst($enquiry['status']) ?></span></p>
                                    <p class="mb-0"><strong>Received:</strong> <?= date('d/m/Y H:i', strtotime($enquiry['created_date'])) ?></p>
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
