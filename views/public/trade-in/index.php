<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trade-In Valuation - <?= htmlspecialchars($company['company_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/"><?= htmlspecialchars($company['company_name']) ?></a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Home</a>
                <a class="nav-link" href="/stock">Stock</a>
                <a class="nav-link active" href="/trade-in">Trade-In</a>
                <a class="nav-link" href="/contact">Contact</a>
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <h1 class="mb-4">Get a Trade-In Valuation</h1>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="/trade-in/submit">
                            <h5 class="mb-3">Your Vehicle Details</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Make *</label>
                                    <input type="text" class="form-control" name="make" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Model *</label>
                                    <input type="text" class="form-control" name="model" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Year *</label>
                                    <input type="number" class="form-control" name="year" min="1990" max="<?= date('Y') + 1 ?>" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Registration</label>
                                    <input type="text" class="form-control" name="registration">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mileage *</label>
                                    <input type="number" class="form-control" name="mileage" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Condition</label>
                                <select class="form-select" name="condition">
                                    <option value="excellent">Excellent</option>
                                    <option value="good" selected>Good</option>
                                    <option value="fair">Fair</option>
                                    <option value="poor">Poor</option>
                                </select>
                            </div>
                            <h5 class="mb-3 mt-4">Your Contact Details</h5>
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone *</label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Additional Comments</label>
                                <textarea class="form-control" name="comments" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">Request Valuation</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-4">
                        <h5 class="mb-3">Why Trade In?</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Quick and easy process</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Fair market value</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Reduce your next purchase</li>
                            <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> No selling hassles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($company['company_name']) ?>. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
