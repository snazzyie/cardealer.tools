<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>body{min-height:100vh;background:#f8f9fa}</style>
</head>
<body>
    <div class="container-fluid p-4">
        <div class="d-flex justify-content-between mb-4">
            <a href="/super-admin/dealers" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i> Back</a>
            <form method="POST" action="/super-admin/switch-company">
                <input type="hidden" name="company_id" value="<?= $dealer['company_id'] ?>">
                <button type="submit" class="btn btn-warning"><i class="bi bi-arrow-left-right me-2"></i> Switch to Company</button>
            </form>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <h3 class="mb-4"><?= htmlspecialchars($dealer['company_name']) ?></h3>
                        <p><strong>Email:</strong> <?= htmlspecialchars($dealer['company_email']) ?></p>
                        <p><strong>Phone:</strong> <?= htmlspecialchars($dealer['company_phone'] ?? 'N/A') ?></p>
                        <p><strong>Subdomain:</strong> <?= htmlspecialchars($dealer['subdomain'] ?? 'N/A') ?></p>
                        <p><strong>Created:</strong> <?= date('d/m/Y', strtotime($dealer['created_date'])) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-3">Subscription Status</h6>
                        <h3><span class="badge bg-<?= ($dealer['subscription_status'] ?? 'inactive') === 'active' ? 'success' : 'warning' ?>"><?= ucfirst($dealer['subscription_status'] ?? 'inactive') ?></span></h3>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="mb-3">Statistics</h6>
                        <p><strong>Users:</strong> <?= $dealer['user_count'] ?? 0 ?></p>
                        <p><strong>Vehicles:</strong> <?= $dealer['vehicle_count'] ?? 0 ?></p>
                        <p><strong>Leads:</strong> <?= $dealer['lead_count'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
