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
        .vehicle-image { width: 80px; height: 60px; object-fit: cover; border-radius: 4px; }
        .status-badge { text-transform: capitalize; }
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
                        <h6 class="text-muted mb-0"><?= $page_header['subtitle'] ?></h6>
                        <a href="/vehicles/new" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Add Vehicle
                        </a>
                    </div>

                    <!-- Filters -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <form method="GET" action="/vehicles" class="row g-3">
                                <div class="col-md-3">
                                    <input type="text" class="form-control" name="search" placeholder="Search..."
                                           value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select" name="status">
                                        <option value="">All Status</option>
                                        <option value="available" <?= ($_GET['status'] ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
                                        <option value="reserved" <?= ($_GET['status'] ?? '') === 'reserved' ? 'selected' : '' ?>>Reserved</option>
                                        <option value="sold" <?= ($_GET['status'] ?? '') === 'sold' ? 'selected' : '' ?>>Sold</option>
                                        <option value="coming-soon" <?= ($_GET['status'] ?? '') === 'coming-soon' ? 'selected' : '' ?>>Coming Soon</option>
                                    </select>
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
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="bi bi-search me-2"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="/vehicles" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-x-circle me-2"></i> Clear
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Vehicles Table -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <?php if (empty($vehicles)): ?>
                                <div class="text-center py-5">
                                    <i class="bi bi-car-front text-muted" style="font-size: 4rem;"></i>
                                    <h5 class="text-muted mt-3">No vehicles found</h5>
                                    <p class="text-muted">Add your first vehicle to get started</p>
                                    <a href="/vehicles/new" class="btn btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-2"></i> Add Vehicle
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Vehicle</th>
                                                <th>Registration</th>
                                                <th>Price</th>
                                                <th>Mileage</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($vehicles as $vehicle): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($vehicle['primary_image'])): ?>
                                                            <img src="<?= htmlspecialchars($vehicle['primary_image']) ?>" class="vehicle-image" alt="Vehicle">
                                                        <?php else: ?>
                                                            <div class="vehicle-image bg-light d-flex align-items-center justify-content-center">
                                                                <i class="bi bi-car-front text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?></strong><br>
                                                        <small class="text-muted"><?= htmlspecialchars($vehicle['fuel_type']) ?> • <?= htmlspecialchars($vehicle['transmission']) ?></small>
                                                    </td>
                                                    <td><?= htmlspecialchars($vehicle['registration'] ?? 'N/A') ?></td>
                                                    <td>
                                                        <strong>€<?= number_format($vehicle['price'], 0) ?></strong>
                                                        <?php if ($vehicle['was_price']): ?>
                                                            <br><small class="text-muted"><s>€<?= number_format($vehicle['was_price'], 0) ?></s></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $vehicle['mileage'] ? number_format($vehicle['mileage']) . ' ' . $vehicle['mileage_unit'] : 'N/A' ?></td>
                                                    <td>
                                                        <?php
                                                        $statusColors = [
                                                            'available' => 'success',
                                                            'reserved' => 'warning',
                                                            'sold' => 'secondary',
                                                            'coming-soon' => 'info'
                                                        ];
                                                        $statusColor = $statusColors[$vehicle['status']] ?? 'secondary';
                                                        ?>
                                                        <span class="badge bg-<?= $statusColor ?> status-badge">
                                                            <?= htmlspecialchars($vehicle['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <a href="/vehicles/view/<?= $vehicle['vehicle_id'] ?>" class="btn btn-outline-primary" title="View">
                                                                <i class="bi bi-eye"></i>
                                                            </a>
                                                            <a href="/vehicles/edit/<?= $vehicle['vehicle_id'] ?>" class="btn btn-outline-secondary" title="Edit">
                                                                <i class="bi bi-pencil"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <div class="text-muted">
                                        Showing <?= count($vehicles) ?> vehicles
                                    </div>
                                    <nav>
                                        <ul class="pagination pagination-sm mb-0">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                                                </li>
                                            <?php endif; ?>
                                            <?php if (count($vehicles) === $limit): ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                                                </li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
