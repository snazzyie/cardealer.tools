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
        .feature-item { cursor: move; }
        .feature-item:hover { background: #f8f9fa; }
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h6 class="text-muted mb-0"><?= $page_header['subtitle'] ?></h6>
                            <p class="small text-muted mb-0">Manage vehicle: <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model'] . ' ' . $vehicle['year']) ?></p>
                        </div>
                        <a href="/vehicles/edit/<?= $vehicle['vehicle_id'] ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to Vehicle
                        </a>
                    </div>

                    <?php if (isset($_GET['saved'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i> Features saved successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/vehicles/features?id=<?= $vehicle['vehicle_id'] ?>">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Selected Features -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Vehicle Features</h5>
                                        <p class="text-muted">Select the features that this vehicle has:</p>

                                        <div class="row">
                                            <?php
                                            $feature_categories = [
                                                'Safety' => [
                                                    'ABS', 'Airbags', 'Traction Control', 'Stability Control',
                                                    'Parking Sensors', 'Reversing Camera', 'Blind Spot Monitor',
                                                    'Lane Departure Warning', 'Adaptive Cruise Control'
                                                ],
                                                'Comfort' => [
                                                    'Air Conditioning', 'Climate Control', 'Heated Seats',
                                                    'Leather Seats', 'Electric Seats', 'Sunroof',
                                                    'Panoramic Roof', 'Cruise Control'
                                                ],
                                                'Entertainment' => [
                                                    'Bluetooth', 'USB Port', 'Apple CarPlay', 'Android Auto',
                                                    'Sat Nav', 'Premium Sound', 'DAB Radio', 'CD Player'
                                                ],
                                                'Exterior' => [
                                                    'Alloy Wheels', 'Fog Lights', 'LED Lights', 'Xenon Lights',
                                                    'Parking Sensors Rear', 'Parking Sensors Front',
                                                    'Roof Rails', 'Tow Bar'
                                                ],
                                                'Performance' => [
                                                    'Turbo', 'Sport Mode', 'Paddle Shifters',
                                                    'Launch Control', 'Adjustable Suspension'
                                                ]
                                            ];

                                            foreach ($feature_categories as $category => $features):
                                            ?>
                                                <div class="col-md-6 mb-4">
                                                    <h6 class="text-primary mb-3"><?= $category ?></h6>
                                                    <?php foreach ($features as $feature): ?>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox" name="features[]"
                                                                   value="<?= htmlspecialchars($feature) ?>"
                                                                   id="feature_<?= str_replace(' ', '_', $feature) ?>"
                                                                   <?= in_array($feature, $selected_features ?? []) ? 'checked' : '' ?>>
                                                            <label class="form-check-label" for="feature_<?= str_replace(' ', '_', $feature) ?>">
                                                                <?= htmlspecialchars($feature) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Features -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body p-4">
                                        <h6 class="mb-3">Custom Features</h6>
                                        <p class="small text-muted">Add any additional features not listed above (one per line):</p>
                                        <textarea class="form-control" name="custom_features" rows="5"
                                                  placeholder="e.g., Massage Seats&#10;Heated Steering Wheel&#10;Head-Up Display"><?= htmlspecialchars($custom_features ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Summary -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h6 class="mb-3">Features Summary</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">Selected Features:</span>
                                            <strong id="featureCount"><?= count($selected_features ?? []) ?></strong>
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Features are displayed on the vehicle listing page
                                        </small>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i> Save Features
                                            </button>
                                            <a href="/vehicles/edit/<?= $vehicle['vehicle_id'] ?>" class="btn btn-outline-secondary">
                                                Cancel
                                            </a>
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
    <script>
        // Update feature count
        document.querySelectorAll('input[name="features[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const count = document.querySelectorAll('input[name="features[]"]:checked').length;
                document.getElementById('featureCount').textContent = count;
            });
        });
    </script>
</body>
</html>
