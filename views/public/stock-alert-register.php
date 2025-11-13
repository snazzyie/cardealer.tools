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
            <div class="col-md-8">
                <?php if (isset($success) && $success): ?>
                    <div class="alert alert-success">
                        <h4>✓ Stock Alert Created!</h4>
                        <p>You'll receive an email notification when a vehicle matching your criteria becomes available. Please check your email for confirmation.</p>
                        <a href="/cars" class="btn btn-primary">Browse Current Stock</a>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="mb-0">Create Stock Alert</h3>
                        </div>
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                            <?php endif; ?>

                            <p>Get notified when vehicles matching your criteria become available.</p>

                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Email Address *</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Make</label>
                                        <input type="text" class="form-control" name="make" placeholder="e.g., Ford">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Model</label>
                                        <input type="text" class="form-control" name="model" placeholder="e.g., Focus">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Min Price (€)</label>
                                        <input type="number" class="form-control" name="min_price" min="0" step="100">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Max Price (€)</label>
                                        <input type="number" class="form-control" name="max_price" min="0" step="100">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Min Year</label>
                                        <input type="number" class="form-control" name="min_year" min="1980" max="<?= date('Y') + 1 ?>">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Max Year</label>
                                        <input type="number" class="form-control" name="max_year" min="1980" max="<?= date('Y') + 1 ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fuel Type</label>
                                        <select class="form-select" name="fuel_type">
                                            <option value="">Any</option>
                                            <option value="petrol">Petrol</option>
                                            <option value="diesel">Diesel</option>
                                            <option value="electric">Electric</option>
                                            <option value="hybrid">Hybrid</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Body Type</label>
                                        <select class="form-select" name="body_type">
                                            <option value="">Any</option>
                                            <option value="sedan">Sedan</option>
                                            <option value="suv">SUV</option>
                                            <option value="hatchback">Hatchback</option>
                                            <option value="coupe">Coupe</option>
                                            <option value="van">Van</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">Create Stock Alert</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
