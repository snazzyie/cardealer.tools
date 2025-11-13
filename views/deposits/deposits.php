<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container p-4">
        <div class="d-flex justify-content-between mb-4">
            <h2><?= $page_header['title'] ?></h2>
            <a href="/deposits/new" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> New Deposit</a>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Invoice</th><th>Customer</th><th>Amount</th><th>Date</th><th>Status</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($deposits)): ?>
                                <tr><td colspan="6" class="text-center text-muted py-5">No deposits found</td></tr>
                            <?php else: ?>
                                <?php foreach ($deposits as $d): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($d['invoice_number'] ?? 'N/A') ?></td>
                                        <td><?= htmlspecialchars($d['customer_name']) ?></td>
                                        <td>€<?= number_format($d['amount'], 2) ?></td>
                                        <td><?= date('d/m/Y', strtotime($d['deposit_date'])) ?></td>
                                        <td><span class="badge bg-<?= $d['status'] === 'paid' ? 'success' : 'warning' ?>"><?= ucfirst($d['status']) ?></span></td>
                                        <td><a href="/deposits/view?id=<?= $d['deposit_id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
