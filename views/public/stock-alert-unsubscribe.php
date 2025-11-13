<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <?php if ($success): ?>
                            <i class="bi bi-check-circle text-success" style="font-size: 4rem;"></i>
                            <h2 class="mt-3">Unsubscribed Successfully</h2>
                            <p class="text-muted">
                                <?= isset($already_unsubscribed) ? 'You were already unsubscribed from this stock alert.' : 'You have been unsubscribed from this stock alert.' ?>
                            </p>
                            <p>You will no longer receive notifications for this alert.</p>
                        <?php else: ?>
                            <i class="bi bi-x-circle text-danger" style="font-size: 4rem;"></i>
                            <h2 class="mt-3">Error</h2>
                            <p class="text-danger"><?= htmlspecialchars($error) ?></p>
                        <?php endif; ?>
                        <a href="/" class="btn btn-primary mt-3">Return to Homepage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
