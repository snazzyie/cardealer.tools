<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { min-height: 100vh; background: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar a { color: #333; text-decoration: none; padding: 12px 20px; display: block; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #e9ecef; color: #007bff; }
        .navbar { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-primary mb-4">Car Dealer</h4>
                </div>
                <nav>
                    <a href="/dash"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="/vehicles" class="active"><i class="bi bi-car-front me-2"></i> Vehicles</a>
                    <a href="/crm"><i class="bi bi-people me-2"></i> CRM</a>
                    <a href="/calendar"><i class="bi bi-calendar me-2"></i> Calendar</a>
                    <a href="/enquiries"><i class="bi bi-envelope me-2"></i> Enquiries</a>
                    <a href="/customers"><i class="bi bi-person me-2"></i> Customers</a>
                    <a href="/invoices"><i class="bi bi-receipt me-2"></i> Invoices</a>
                    <a href="/inbox"><i class="bi bi-inbox me-2"></i> Inbox</a>
                    <a href="/reports"><i class="bi bi-graph-up me-2"></i> Reports</a>
                    <?php if ($permission >= 2): ?>
                        <hr>
                        <a href="/users"><i class="bi bi-people me-2"></i> Users</a>
                        <a href="/company"><i class="bi bi-building me-2"></i> Company</a>
                        <a href="/subscriptions"><i class="bi bi-credit-card me-2"></i> Subscription</a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-0">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <h5 class="mb-0"><?= $page_header['title'] ?></h5>
                        <div class="d-flex align-items-center">
                            <span class="me-3"><?= htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']) ?></span>
                            <img src="<?= fn_get_gravatar($user_data['email'], 40) ?>" class="rounded-circle me-3" alt="User">
                            <a href="/logout" class="btn btn-outline-danger btn-sm">Logout</a>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid p-4">
                    <h6 class="text-muted mb-4"><?= $page_header['subtitle'] ?></h6>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= $is_edit ? "/vehicles/edit/{$vehicle_id}" : '/vehicles/new' ?>">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Basic Information -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Basic Information</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="make" class="form-label">Make *</label>
                                                <input type="text" class="form-control" id="make" name="make"
                                                       value="<?= htmlspecialchars($vehicle_data['make'] ?? '') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="model" class="form-label">Model *</label>
                                                <input type="text" class="form-control" id="model" name="model"
                                                       value="<?= htmlspecialchars($vehicle_data['model'] ?? '') ?>" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="year" class="form-label">Year *</label>
                                                <input type="number" class="form-control" id="year" name="year" min="1900" max="2099"
                                                       value="<?= htmlspecialchars($vehicle_data['year'] ?? date('Y')) ?>" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="registration" class="form-label">Registration</label>
                                                <input type="text" class="form-control" id="registration" name="registration"
                                                       value="<?= htmlspecialchars($vehicle_data['registration'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="vin" class="form-label">VIN</label>
                                                <input type="text" class="form-control" id="vin" name="vin"
                                                       value="<?= htmlspecialchars($vehicle_data['vin'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Pricing</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="price" class="form-label">Sale Price (€) *</label>
                                                <input type="number" class="form-control" id="price" name="price" step="0.01"
                                                       value="<?= htmlspecialchars($vehicle_data['price'] ?? '') ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="was_price" class="form-label">Was Price (€)</label>
                                                <input type="number" class="form-control" id="was_price" name="was_price" step="0.01"
                                                       value="<?= htmlspecialchars($vehicle_data['was_price'] ?? '') ?>">
                                                <div class="form-text">Original price if vehicle is on sale</div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="vat_status" class="form-label">VAT Status</label>
                                                <select class="form-select" id="vat_status" name="vat_status">
                                                    <option value="vat_inclusive" <?= ($vehicle_data['vat_status'] ?? 'vat_inclusive') === 'vat_inclusive' ? 'selected' : '' ?>>VAT Inclusive</option>
                                                    <option value="vat_exclusive" <?= ($vehicle_data['vat_status'] ?? '') === 'vat_exclusive' ? 'selected' : '' ?>>VAT Exclusive</option>
                                                    <option value="vat_margin" <?= ($vehicle_data['vat_status'] ?? '') === 'vat_margin' ? 'selected' : '' ?>>Margin Scheme</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="vat_amount" class="form-label">VAT Amount (€)</label>
                                                <input type="number" class="form-control" id="vat_amount" name="vat_amount" step="0.01"
                                                       value="<?= htmlspecialchars($vehicle_data['vat_amount'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="trade_in_value" class="form-label">Trade-In Value (€)</label>
                                                <input type="number" class="form-control" id="trade_in_value" name="trade_in_value" step="0.01"
                                                       value="<?= htmlspecialchars($vehicle_data['trade_in_value'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Specifications -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Specifications</h5>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="fuel_type" class="form-label">Fuel Type *</label>
                                                <select class="form-select" id="fuel_type" name="fuel_type" required>
                                                    <option value="">Select...</option>
                                                    <option value="petrol" <?= ($vehicle_data['fuel_type'] ?? '') === 'petrol' ? 'selected' : '' ?>>Petrol</option>
                                                    <option value="diesel" <?= ($vehicle_data['fuel_type'] ?? '') === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                                                    <option value="electric" <?= ($vehicle_data['fuel_type'] ?? '') === 'electric' ? 'selected' : '' ?>>Electric</option>
                                                    <option value="hybrid" <?= ($vehicle_data['fuel_type'] ?? '') === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                                    <option value="plug-in-hybrid" <?= ($vehicle_data['fuel_type'] ?? '') === 'plug-in-hybrid' ? 'selected' : '' ?>>Plug-in Hybrid</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="transmission" class="form-label">Transmission *</label>
                                                <select class="form-select" id="transmission" name="transmission" required>
                                                    <option value="">Select...</option>
                                                    <option value="manual" <?= ($vehicle_data['transmission'] ?? '') === 'manual' ? 'selected' : '' ?>>Manual</option>
                                                    <option value="automatic" <?= ($vehicle_data['transmission'] ?? '') === 'automatic' ? 'selected' : '' ?>>Automatic</option>
                                                    <option value="semi-automatic" <?= ($vehicle_data['transmission'] ?? '') === 'semi-automatic' ? 'selected' : '' ?>>Semi-Automatic</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="body_type" class="form-label">Body Type *</label>
                                                <select class="form-select" id="body_type" name="body_type" required>
                                                    <option value="">Select...</option>
                                                    <option value="saloon" <?= ($vehicle_data['body_type'] ?? '') === 'saloon' ? 'selected' : '' ?>>Saloon</option>
                                                    <option value="hatchback" <?= ($vehicle_data['body_type'] ?? '') === 'hatchback' ? 'selected' : '' ?>>Hatchback</option>
                                                    <option value="suv" <?= ($vehicle_data['body_type'] ?? '') === 'suv' ? 'selected' : '' ?>>SUV</option>
                                                    <option value="estate" <?= ($vehicle_data['body_type'] ?? '') === 'estate' ? 'selected' : '' ?>>Estate</option>
                                                    <option value="coupe" <?= ($vehicle_data['body_type'] ?? '') === 'coupe' ? 'selected' : '' ?>>Coupe</option>
                                                    <option value="convertible" <?= ($vehicle_data['body_type'] ?? '') === 'convertible' ? 'selected' : '' ?>>Convertible</option>
                                                    <option value="mpv" <?= ($vehicle_data['body_type'] ?? '') === 'mpv' ? 'selected' : '' ?>>MPV</option>
                                                    <option value="van" <?= ($vehicle_data['body_type'] ?? '') === 'van' ? 'selected' : '' ?>>Van</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label for="doors" class="form-label">Doors</label>
                                                <input type="number" class="form-control" id="doors" name="doors" min="2" max="7"
                                                       value="<?= htmlspecialchars($vehicle_data['doors'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="seats" class="form-label">Seats</label>
                                                <input type="number" class="form-control" id="seats" name="seats" min="2" max="9"
                                                       value="<?= htmlspecialchars($vehicle_data['seats'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="exterior_color" class="form-label">Exterior Color</label>
                                                <input type="text" class="form-control" id="exterior_color" name="exterior_color"
                                                       value="<?= htmlspecialchars($vehicle_data['exterior_color'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label for="interior_color" class="form-label">Interior Color</label>
                                                <input type="text" class="form-control" id="interior_color" name="interior_color"
                                                       value="<?= htmlspecialchars($vehicle_data['interior_color'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="mileage" class="form-label">Mileage</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="mileage" name="mileage"
                                                           value="<?= htmlspecialchars($vehicle_data['mileage'] ?? '') ?>">
                                                    <select class="form-select" name="mileage_unit" style="max-width: 100px;">
                                                        <option value="km" <?= ($vehicle_data['mileage_unit'] ?? 'km') === 'km' ? 'selected' : '' ?>>KM</option>
                                                        <option value="miles" <?= ($vehicle_data['mileage_unit'] ?? '') === 'miles' ? 'selected' : '' ?>>Miles</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="engine_size" class="form-label">Engine Size</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="engine_size" name="engine_size"
                                                           value="<?= htmlspecialchars($vehicle_data['engine_size'] ?? '') ?>">
                                                    <select class="form-select" name="engine_size_unit" style="max-width: 90px;">
                                                        <option value="cc" <?= ($vehicle_data['engine_size_unit'] ?? 'cc') === 'cc' ? 'selected' : '' ?>>CC</option>
                                                        <option value="L" <?= ($vehicle_data['engine_size_unit'] ?? '') === 'L' ? 'selected' : '' ?>>L</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="co2_emissions" class="form-label">CO2 Emissions (g/km)</label>
                                                <input type="number" class="form-control" id="co2_emissions" name="co2_emissions"
                                                       value="<?= htmlspecialchars($vehicle_data['co2_emissions'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Description</h5>
                                        <textarea class="form-control" name="description" rows="6"
                                                  placeholder="Enter detailed description of the vehicle..."><?= htmlspecialchars($vehicle_data['description'] ?? '') ?></textarea>
                                    </div>
                                </div>

                                <!-- Video URL -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Video URL</h5>
                                        <input type="url" class="form-control" name="video_url"
                                               placeholder="YouTube or Vimeo URL (e.g., https://www.youtube.com/watch?v=...)"
                                               value="<?= htmlspecialchars($vehicle_data['video_url'] ?? '') ?>">
                                        <small class="text-muted mt-2 d-block">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Paste a YouTube or Vimeo URL to display a video on the vehicle page
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Status & Options -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h6 class="mb-3">Status & Options</h6>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="available" <?= ($vehicle_data['status'] ?? 'available') === 'available' ? 'selected' : '' ?>>Available</option>
                                                <option value="reserved" <?= ($vehicle_data['status'] ?? '') === 'reserved' ? 'selected' : '' ?>>Reserved</option>
                                                <option value="sold" <?= ($vehicle_data['status'] ?? '') === 'sold' ? 'selected' : '' ?>>Sold</option>
                                                <option value="coming-soon" <?= ($vehicle_data['status'] ?? '') === 'coming-soon' ? 'selected' : '' ?>>Coming Soon</option>
                                            </select>
                                        </div>

                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                                   <?= ($vehicle_data['is_featured'] ?? 0) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_featured">
                                                Featured Vehicle
                                            </label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="is_premium" name="is_premium"
                                                   <?= ($vehicle_data['is_premium'] ?? 0) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="is_premium">
                                                Premium Listing
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Service History -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h6 class="mb-3">History & Tests</h6>

                                        <div class="mb-3">
                                            <label for="service_history" class="form-label">Service History</label>
                                            <select class="form-select" id="service_history" name="service_history">
                                                <option value="full" <?= ($vehicle_data['service_history'] ?? '') === 'full' ? 'selected' : '' ?>>Full</option>
                                                <option value="partial" <?= ($vehicle_data['service_history'] ?? '') === 'partial' ? 'selected' : '' ?>>Partial</option>
                                                <option value="none" <?= ($vehicle_data['service_history'] ?? '') === 'none' ? 'selected' : '' ?>>None</option>
                                                <option value="unknown" <?= ($vehicle_data['service_history'] ?? 'unknown') === 'unknown' ? 'selected' : '' ?>>Unknown</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="previous_owners" class="form-label">Previous Owners</label>
                                            <input type="number" class="form-control" id="previous_owners" name="previous_owners" min="0"
                                                   value="<?= htmlspecialchars($vehicle_data['previous_owners'] ?? '') ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="nct_expiry" class="form-label">NCT Expiry</label>
                                            <input type="date" class="form-control" id="nct_expiry" name="nct_expiry"
                                                   value="<?= htmlspecialchars($vehicle_data['nct_expiry'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i>
                                                <?= $is_edit ? 'Update Vehicle' : 'Create Vehicle' ?>
                                            </button>
                                            <a href="/vehicles" class="btn btn-outline-secondary">Cancel</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
