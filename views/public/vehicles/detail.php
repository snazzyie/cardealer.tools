<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <meta name="description" content="<?= htmlspecialchars(substr(strip_tags($vehicle['description'] ?? ''), 0, 155)) ?>">
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
        .btn-primary { background-color: var(--primary-color); border-color: var(--primary-color); }
        .btn-primary:hover { opacity: 0.9; }
        .main-image { width: 100%; height: 500px; object-fit: cover; border-radius: 8px; }
        .thumbnail-image { width: 100%; height: 100px; object-fit: cover; border-radius: 4px; cursor: pointer; transition: opacity 0.3s; }
        .thumbnail-image:hover { opacity: 0.7; }
        .price-tag { font-size: 2.5rem; font-weight: bold; color: var(--primary-color); }
        .was-price { text-decoration: line-through; color: #6c757d; font-size: 1.5rem; }
        .spec-item { border-bottom: 1px solid #dee2e6; padding: 10px 0; }
        .spec-label { font-weight: 600; color: #6c757d; }
        .badge-featured { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
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

    <!-- Breadcrumb -->
    <div class="bg-white border-bottom py-3">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/vehicles">Vehicles</a></li>
                    <li class="breadcrumb-item active"><?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model']) ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Vehicle Details -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Left Column - Images & Description -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h1 class="h2 mb-2"><?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?></h1>
                                    <?php if ($vehicle['registration']): ?>
                                        <p class="text-muted mb-0">Reg: <?= htmlspecialchars($vehicle['registration']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php if ($vehicle['is_featured']): ?>
                                    <span class="badge badge-featured">Featured</span>
                                <?php endif; ?>
                            </div>

                            <!-- Main Image -->
                            <div class="mb-3">
                                <?php if (!empty($images)): ?>
                                    <img src="<?= htmlspecialchars($images[0]['image_url']) ?>" class="main-image" id="mainImage" alt="Vehicle">
                                <?php else: ?>
                                    <div class="main-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-car-front text-muted" style="font-size: 5rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Thumbnail Gallery -->
                            <?php if (count($images) > 1): ?>
                                <div class="row g-2">
                                    <?php foreach ($images as $image): ?>
                                        <div class="col-3">
                                            <img src="<?= htmlspecialchars($image['image_url']) ?>"
                                                 class="thumbnail-image"
                                                 onclick="document.getElementById('mainImage').src = this.src"
                                                 alt="Vehicle">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Description -->
                    <?php if ($vehicle['description']): ?>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-4">
                                <h5 class="mb-3">Description</h5>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($vehicle['description'])) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Full Specifications -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="mb-4">Full Specifications</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="spec-item">
                                        <div class="spec-label">Make</div>
                                        <div><?= htmlspecialchars($vehicle['make']) ?></div>
                                    </div>
                                    <div class="spec-item">
                                        <div class="spec-label">Model</div>
                                        <div><?= htmlspecialchars($vehicle['model']) ?></div>
                                    </div>
                                    <div class="spec-item">
                                        <div class="spec-label">Year</div>
                                        <div><?= htmlspecialchars($vehicle['year']) ?></div>
                                    </div>
                                    <div class="spec-item">
                                        <div class="spec-label">Fuel Type</div>
                                        <div><?= ucfirst(htmlspecialchars($vehicle['fuel_type'])) ?></div>
                                    </div>
                                    <div class="spec-item">
                                        <div class="spec-label">Transmission</div>
                                        <div><?= ucfirst(htmlspecialchars($vehicle['transmission'])) ?></div>
                                    </div>
                                    <div class="spec-item">
                                        <div class="spec-label">Body Type</div>
                                        <div><?= ucfirst(htmlspecialchars($vehicle['body_type'])) ?></div>
                                    </div>
                                    <?php if ($vehicle['mileage']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">Mileage</div>
                                            <div><?= number_format($vehicle['mileage']) ?> <?= $vehicle['mileage_unit'] ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($vehicle['exterior_color']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">Exterior Color</div>
                                            <div><?= htmlspecialchars($vehicle['exterior_color']) ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6">
                                    <?php if ($vehicle['doors']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">Doors</div>
                                            <div><?= $vehicle['doors'] ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($vehicle['seats']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">Seats</div>
                                            <div><?= $vehicle['seats'] ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($vehicle['engine_size']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">Engine Size</div>
                                            <div><?= $vehicle['engine_size'] ?> <?= $vehicle['engine_size_unit'] ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($vehicle['power_hp']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">Power</div>
                                            <div><?= $vehicle['power_hp'] ?> HP</div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($vehicle['co2_emissions']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">CO2 Emissions</div>
                                            <div><?= $vehicle['co2_emissions'] ?> g/km</div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($vehicle['previous_owners']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">Previous Owners</div>
                                            <div><?= $vehicle['previous_owners'] ?></div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="spec-item">
                                        <div class="spec-label">Service History</div>
                                        <div><?= ucfirst(htmlspecialchars($vehicle['service_history'])) ?></div>
                                    </div>
                                    <?php if ($vehicle['nct_expiry']): ?>
                                        <div class="spec-item">
                                            <div class="spec-label">NCT Expiry</div>
                                            <div><?= date('M Y', strtotime($vehicle['nct_expiry'])) ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Price & Enquiry -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            <?php if ($vehicle['was_price']): ?>
                                <div class="was-price mb-2">Was €<?= number_format($vehicle['was_price'], 0) ?></div>
                            <?php endif; ?>
                            <div class="price-tag mb-4">€<?= number_format($vehicle['price'], 0) ?></div>

                            <div class="d-grid gap-2 mb-4">
                                <a href="tel:<?= htmlspecialchars($company_data['company_phone'] ?? '') ?>" class="btn btn-primary btn-lg">
                                    <i class="bi bi-telephone me-2"></i> Call Us
                                </a>
                                <a href="#enquiry" class="btn btn-outline-primary">
                                    <i class="bi bi-envelope me-2"></i> Make Enquiry
                                </a>
                            </div>

                            <hr>

                            <h6 class="mb-3">Key Features</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> <?= ucfirst($vehicle['fuel_type']) ?></li>
                                <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> <?= ucfirst($vehicle['transmission']) ?></li>
                                <?php if ($vehicle['mileage']): ?>
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> <?= number_format($vehicle['mileage']) ?> <?= $vehicle['mileage_unit'] ?></li>
                                <?php endif; ?>
                                <?php if ($vehicle['service_history'] === 'full'): ?>
                                    <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i> Full Service History</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Finance Calculator -->
                    <?php include BASE_PATH . 'views/public/vehicles/finance-widget.php'; ?>
                </div>
            </div>

            <!-- Enquiry Form -->
            <div class="row mt-5" id="enquiry">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Make an Enquiry</h4>

                            <?php if ($enquiry_success): ?>
                                <div class="alert alert-success">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Thank you! We'll be in touch soon.
                                </div>
                            <?php endif; ?>

                            <?php if ($enquiry_error): ?>
                                <div class="alert alert-danger">
                                    <?= htmlspecialchars($enquiry_error) ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST">
                                <input type="hidden" name="action" value="enquiry">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">First Name *</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Phone *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="4"
                                              placeholder="I'm interested in the <?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?>..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send me-2"></i> Send Enquiry
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
