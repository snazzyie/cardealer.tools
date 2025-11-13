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
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <i class="bi bi-exclamation-triangle text-warning fs-1 mb-3"></i>
                        <h3 class="mb-3">Refund Deposit</h3>
                        <p class="text-muted mb-4">Are you sure you want to refund this deposit?</p>
                        <?php if (isset($deposit)): ?>
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5><?= htmlspecialchars($deposit['customer_name']) ?></h5>
                                    <p class="mb-0">Amount: €<?= number_format($deposit['amount'], 2) ?></p>
                                </div>
                            </div>
                            <form method="POST" action="/deposits/refund?id=<?= $deposit['deposit_id'] ?>">
                                <div class="mb-4">
                                    <label class="form-label">Refund Reason</label>
                                    <textarea class="form-control" name="refund_reason" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-warning btn-lg">Confirm Refund</button>
                                <a href="/deposits" class="btn btn-outline-secondary btn-lg">Cancel</a>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
