<?php require BASE_PATH . 'views/header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?></h2>
        <p class="text-muted"><?= htmlspecialchars($vehicle['registration'] ?? 'No Registration') ?></p>
    </div>
    <div class="col-md-4 text-end">
        <a href="/vehicles/edit/<?= $vehicle_id ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit Vehicle
        </a>
        <a href="/vehicles/images?id=<?= $vehicle_id ?>" class="btn btn-outline-secondary">
            <i class="bi bi-images"></i> Manage Images
        </a>
    </div>
</div>

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        Vehicle updated successfully!
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Vehicle Images -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Images (<?= count($images) ?>)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($images)): ?>
                    <p class="text-muted">No images uploaded yet.</p>
                    <a href="/vehicles/images?id=<?= $vehicle_id ?>" class="btn btn-sm btn-primary">Upload Images</a>
                <?php else: ?>
                    <div class="row g-2">
                        <?php foreach ($images as $image): ?>
                            <div class="col-6">
                                <div class="position-relative">
                                    <img src="<?= htmlspecialchars($image['image_url']) ?>"
                                         class="img-fluid rounded"
                                         alt="Vehicle Image">
                                    <?php if ($image['is_primary']): ?>
                                        <span class="badge bg-primary position-absolute top-0 start-0 m-2">Primary</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-3">
                        <a href="/vehicles/images?id=<?= $vehicle_id ?>" class="btn btn-sm btn-outline-primary">Manage Images</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Vehicle Details -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Vehicle Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="40%">Status</th>
                            <td>
                                <?php
                                $status_badges = [
                                    'available' => 'success',
                                    'sold' => 'danger',
                                    'reserved' => 'warning',
                                    'pending' => 'info'
                                ];
                                $badge_class = $status_badges[$vehicle['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $badge_class ?>">
                                    <?= ucfirst($vehicle['status']) ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Registration</th>
                            <td><?= htmlspecialchars($vehicle['registration'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>VIN</th>
                            <td><?= htmlspecialchars($vehicle['vin'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Mileage</th>
                            <td><?= number_format($vehicle['mileage']) ?> km</td>
                        </tr>
                        <tr>
                            <th>Fuel Type</th>
                            <td><?= htmlspecialchars($vehicle['fuel_type'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Transmission</th>
                            <td><?= htmlspecialchars($vehicle['transmission'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Engine Size</th>
                            <td><?= htmlspecialchars($vehicle['engine_size'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Body Type</th>
                            <td><?= htmlspecialchars($vehicle['body_type'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Color</th>
                            <td><?= htmlspecialchars($vehicle['color'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Doors</th>
                            <td><?= htmlspecialchars($vehicle['doors'] ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <th>Seats</th>
                            <td><?= htmlspecialchars($vehicle['seats'] ?? 'N/A') ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Pricing -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Pricing</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tbody>
                        <tr>
                            <th width="40%">Sale Price</th>
                            <td class="text-end">
                                <strong class="text-success">€<?= number_format($vehicle['price'], 2) ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <th>Cost Price</th>
                            <td class="text-end">€<?= number_format($vehicle['cost_price'] ?? 0, 2) ?></td>
                        </tr>
                        <?php if (isset($vehicle['cost_price']) && $vehicle['cost_price'] > 0): ?>
                            <tr>
                                <th>Profit Margin</th>
                                <td class="text-end">
                                    €<?= number_format($vehicle['price'] - $vehicle['cost_price'], 2) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                        <?php if ($vehicle['previous_price'] ?? 0 > 0): ?>
                            <tr>
                                <th>Previous Price</th>
                                <td class="text-end text-decoration-line-through">
                                    €<?= number_format($vehicle['previous_price'], 2) ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Features</h5>
                <a href="/vehicles/features?id=<?= $vehicle_id ?>" class="btn btn-sm btn-outline-primary">Edit Features</a>
            </div>
            <div class="card-body">
                <?php if (empty($features)): ?>
                    <p class="text-muted">No features added yet.</p>
                <?php else: ?>
                    <ul class="list-unstyled mb-0">
                        <?php foreach ($features as $feature): ?>
                            <li class="mb-1">
                                <i class="bi bi-check-circle text-success"></i>
                                <?= htmlspecialchars($feature['feature_name']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Description -->
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Description</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($vehicle['description'])): ?>
                    <p><?= nl2br(htmlspecialchars($vehicle['description'])) ?></p>
                <?php else: ?>
                    <p class="text-muted">No description available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <a href="/vehicles" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Back to Vehicles
        </a>
        <a href="/vehicles/edit/<?= $vehicle_id ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit Vehicle
        </a>
        <a href="/vehicles/delete?id=<?= $vehicle_id ?>"
           class="btn btn-danger"
           onclick="return confirm('Are you sure you want to delete this vehicle?')">
            <i class="bi bi-trash"></i> Delete Vehicle
        </a>
    </div>
</div>

<?php require BASE_PATH . 'views/footer.php'; ?>
