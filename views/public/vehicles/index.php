<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-color: <?= $company_data['primary_color'] ?? '#007bff' ?>;
            --secondary-color: <?= $company_data['secondary_color'] ?? '#6c757d' ?>;
        }
        body { background: #f8f9fa; }
        .navbar { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar-brand { color: var(--primary-color) !important; font-weight: bold; }
        .vehicle-card { transition: transform 0.3s; height: 100%; }
        .vehicle-card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
        .vehicle-image { height: 200px; object-fit: cover; }
        .vehicle-image-placeholder { height: 200px; background: #e9ecef; display: flex; align-items: center; justify-content: center; }
        .price-tag { font-size: 1.5rem; font-weight: bold; color: var(--primary-color); }
        .was-price { text-decoration: line-through; color: #6c757d; font-size: 1rem; }
        .badge-featured { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { opacity: 0.9; }
        .filter-sidebar { background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <?php if (!empty($company_data['logo_url'])): ?>
                <a class="navbar-brand" href="/vehicles">
                    <img src="<?= htmlspecialchars($company_data['logo_url']) ?>" alt="Logo" height="40">
                </a>
            <?php else: ?>
                <a class="navbar-brand" href="/vehicles">
                    <?= htmlspecialchars($company_data['company_name']) ?>
                </a>
            <?php endif; ?>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/vehicles">Browse Stock</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tel:<?= htmlspecialchars($company_data['company_phone'] ?? '') ?>">
                            <i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($company_data['company_phone'] ?? '') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-5" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 mb-3">Find Your Perfect Car</h1>
                    <p class="lead">Browse our selection of quality used vehicles</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <h3><?= count($vehicles) ?></h3>
                    <p>Vehicles in Stock</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Search & Filters -->
    <section class="py-4 bg-white border-bottom">
        <div class="container">
            <form method="GET" action="/vehicles" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Search..."
                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="make">
                        <option value="">All Makes</option>
                        <?php foreach ($makes as $make): ?>
                            <option value="<?= htmlspecialchars($make['make']) ?>"
                                    <?= ($_GET['make'] ?? '') === $make['make'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($make['make']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="fuel_type">
                        <option value="">Fuel Type</option>
                        <option value="petrol" <?= ($_GET['fuel_type'] ?? '') === 'petrol' ? 'selected' : '' ?>>Petrol</option>
                        <option value="diesel" <?= ($_GET['fuel_type'] ?? '') === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                        <option value="electric" <?= ($_GET['fuel_type'] ?? '') === 'electric' ? 'selected' : '' ?>>Electric</option>
                        <option value="hybrid" <?= ($_GET['fuel_type'] ?? '') === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" name="transmission">
                        <option value="">Transmission</option>
                        <option value="manual" <?= ($_GET['transmission'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual</option>
                        <option value="automatic" <?= ($_GET['transmission'] ?? '') === 'automatic' ? 'selected' : '' ?>>Automatic</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-2"></i> Search
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Vehicle Listings -->
    <section class="py-5">
        <div class="container">
            <?php if (empty($vehicles)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-car-front text-muted" style="font-size: 5rem;"></i>
                    <h3 class="mt-4">No vehicles found</h3>
                    <p class="text-muted">Try adjusting your search filters</p>
                    <a href="/vehicles" class="btn btn-primary mt-3">Clear Filters</a>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($vehicles as $vehicle): ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card vehicle-card border-0 shadow-sm h-100">
                                <?php if ($vehicle['is_featured']): ?>
                                    <div class="position-absolute top-0 start-0 m-3">
                                        <span class="badge badge-featured">Featured</span>
                                    </div>
                                <?php endif; ?>

                                <a href="/vehicles/<?= htmlspecialchars($vehicle['slug']) ?>" class="text-decoration-none">
                                    <?php if (!empty($vehicle['primary_image'])): ?>
                                        <img src="<?= htmlspecialchars($vehicle['primary_image']) ?>" class="card-img-top vehicle-image" alt="Vehicle">
                                    <?php else: ?>
                                        <div class="vehicle-image-placeholder">
                                            <i class="bi bi-car-front text-muted" style="font-size: 3rem;"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="card-body">
                                        <h5 class="card-title text-dark">
                                            <?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?>
                                        </h5>

                                        <div class="mb-3">
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="bi bi-fuel-pump me-1"></i> <?= ucfirst($vehicle['fuel_type']) ?>
                                            </span>
                                            <span class="badge bg-light text-dark me-2">
                                                <i class="bi bi-gear me-1"></i> <?= ucfirst($vehicle['transmission']) ?>
                                            </span>
                                            <?php if ($vehicle['mileage']): ?>
                                                <span class="badge bg-light text-dark">
                                                    <i class="bi bi-speedometer me-1"></i> <?= number_format($vehicle['mileage']) ?> <?= $vehicle['mileage_unit'] ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="mb-2">
                                            <?php if ($vehicle['was_price']): ?>
                                                <div class="was-price">Was €<?= number_format($vehicle['was_price'], 0) ?></div>
                                            <?php endif; ?>
                                            <div class="price-tag">€<?= number_format($vehicle['price'], 0) ?></div>
                                        </div>

                                        <div class="d-grid">
                                            <span class="btn btn-primary">
                                                View Details <i class="bi bi-arrow-right ms-2"></i>
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><?= htmlspecialchars($company_data['company_name']) ?></h5>
                    <?php if ($company_data['company_address']): ?>
                        <p class="text-white-50"><?= nl2br(htmlspecialchars($company_data['company_address'])) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <?php if ($company_data['company_phone']): ?>
                        <p class="text-white-50">
                            <i class="bi bi-telephone me-2"></i>
                            <a href="tel:<?= htmlspecialchars($company_data['company_phone']) ?>" class="text-white-50 text-decoration-none">
                                <?= htmlspecialchars($company_data['company_phone']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    <?php if ($company_data['company_email']): ?>
                        <p class="text-white-50">
                            <i class="bi bi-envelope me-2"></i>
                            <a href="mailto:<?= htmlspecialchars($company_data['company_email']) ?>" class="text-white-50 text-decoration-none">
                                <?= htmlspecialchars($company_data['company_email']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="d-flex gap-3">
                        <?php if ($company_data['social_facebook']): ?>
                            <a href="<?= htmlspecialchars($company_data['social_facebook']) ?>" class="text-white-50 fs-4" target="_blank">
                                <i class="bi bi-facebook"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($company_data['social_instagram']): ?>
                            <a href="<?= htmlspecialchars($company_data['social_instagram']) ?>" class="text-white-50 fs-4" target="_blank">
                                <i class="bi bi-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if ($company_data['social_twitter']): ?>
                            <a href="<?= htmlspecialchars($company_data['social_twitter']) ?>" class="text-white-50 fs-4" target="_blank">
                                <i class="bi bi-twitter"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <hr class="my-4 bg-white">
            <div class="text-center text-white-50">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($company_data['company_name']) ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
