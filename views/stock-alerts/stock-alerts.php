<!DOCTYPE html>
<html lang="en">
<?php require BASE_PATH . 'views/partials/head.php'; ?>
<body>
    <?php require BASE_PATH . 'views/partials/navbar.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <?php require BASE_PATH . 'views/partials/sidebar.php'; ?>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?= htmlspecialchars($page_header['title']) ?></h1>
                </div>

                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        Stock alert deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h6>Total Alerts</h6>
                                <h3><?= $stats['total_alerts'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h6>Active Alerts</h6>
                                <h3><?= $stats['active_alerts'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h6>Inactive</h6>
                                <h3><?= $stats['inactive_alerts'] ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h6>Notified</h6>
                                <h3><?= $stats['notified_alerts'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alerts Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Criteria</th>
                                        <th>Created</th>
                                        <th>Last Sent</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($alerts)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                No stock alerts found.
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($alerts as $alert): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($alert['email']) ?></td>
                                                <td>
                                                    <?php
                                                    $criteria = [];
                                                    if ($alert['make']) $criteria[] = $alert['make'];
                                                    if ($alert['model']) $criteria[] = $alert['model'];
                                                    if ($alert['min_price']) $criteria[] = "€" . number_format($alert['min_price'], 0) . "+";
                                                    if ($alert['max_price']) $criteria[] = "€" . number_format($alert['max_price'], 0) . "-";
                                                    if ($alert['fuel_type']) $criteria[] = ucfirst($alert['fuel_type']);
                                                    echo !empty($criteria) ? implode(", ", $criteria) : "Any";
                                                    ?>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($alert['created_date'])) ?></td>
                                                <td><?= $alert['last_sent'] ? date('d/m/Y H:i', strtotime($alert['last_sent'])) : '-' ?></td>
                                                <td>
                                                    <?php if ($alert['is_active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Delete this stock alert?');">
                                                        <input type="hidden" name="delete_alert_id" value="<?= $alert['alert_id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
