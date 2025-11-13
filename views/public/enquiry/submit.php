<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiry Sent - <?= htmlspecialchars($company['company_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-5">
                        <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
                        <h2 class="mt-4 mb-3">Thank You!</h2>
                        <p class="text-muted mb-4">Your enquiry has been received. We'll get back to you as soon as possible.</p>
                        <a href="/" class="btn btn-primary">Back to Home</a>
                        <a href="/stock" class="btn btn-outline-secondary">Browse Stock</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
