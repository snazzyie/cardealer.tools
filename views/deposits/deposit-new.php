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
        <h2 class="mb-4"><?= $page_header['title'] ?></h2>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="/deposits/new">
                    <div class="mb-3">
                        <label class="form-label">Customer *</label>
                        <select class="form-select" name="customer_id" required>
                            <option value="">Select customer...</option>
                            <?php foreach ($customers ?? [] as $c): ?>
                                <option value="<?= $c['customer_id'] ?>"><?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vehicle</label>
                        <select class="form-select" name="vehicle_id">
                            <option value="">Select vehicle...</option>
                            <?php foreach ($vehicles ?? [] as $v): ?>
                                <option value="<?= $v['vehicle_id'] ?>"><?= htmlspecialchars($v['make'] . ' ' . $v['model'] . ' ' . $v['year']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deposit Amount (€) *</label>
                            <input type="number" class="form-control" name="amount" step="0.01" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Deposit Date *</label>
                            <input type="date" class="form-control" name="deposit_date" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" name="payment_method">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Deposit</button>
                    <a href="/deposits" class="btn btn-outline-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
