<!DOCTYPE html>
<html lang="en">
<?php require BASE_PATH . 'views/partials/head.php'; ?>
<body>
    <?php require BASE_PATH . 'views/partials/navbar.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <?php require BASE_PATH . 'views/partials/sidebar.php'; ?>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <!-- Page Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?= htmlspecialchars($page_header['title']) ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/service/item/new" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Add Service Item
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($_GET['created'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> Service item created successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> Service item updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i> Service item deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Low Stock Alert -->
                <?php if (!empty($low_stock_items)): ?>
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Low Stock Alert:</strong> <?= count($low_stock_items) ?> item(s) are low in stock.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filter Tabs -->
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link <?= $itemType === 'all' ? 'active' : '' ?>" href="/service/items?type=all">
                            All Items (<?= count($service_items) ?>)
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $itemType === 'service' ? 'active' : '' ?>" href="/service/items?type=service">
                            Labor/Services
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $itemType === 'part' ? 'active' : '' ?>" href="/service/items?type=part">
                            Parts
                        </a>
                    </li>
                </ul>

                <!-- Service Items Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Unit Price</th>
                                        <th>Stock</th>
                                        <th>Supplier</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($service_items)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                No service items found. <a href="/service/item/new">Add your first item</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($service_items as $item): ?>
                                            <tr <?= $item['quantity_in_stock'] <= $item['reorder_level'] && $item['item_type'] === 'part' ? 'class="table-warning"' : '' ?>>
                                                <td>
                                                    <strong><?= htmlspecialchars($item['item_code']) ?></strong>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                                        <?php if ($item['description']): ?>
                                                            <br><small class="text-muted"><?= htmlspecialchars($item['description']) ?></small>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php if ($item['item_type'] === 'service'): ?>
                                                        <span class="badge bg-primary">
                                                            <i class="bi bi-wrench me-1"></i> Labor
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">
                                                            <i class="bi bi-box me-1"></i> Part
                                                        </span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <strong>€<?= number_format($item['unit_price'], 2) ?></strong>
                                                    <?php if ($item['estimated_time_minutes'] > 0): ?>
                                                        <br><small class="text-muted"><?= $item['estimated_time_minutes'] ?> min</small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($item['item_type'] === 'part'): ?>
                                                        <span class="<?= $item['quantity_in_stock'] <= $item['reorder_level'] ? 'text-danger fw-bold' : '' ?>">
                                                            <?= $item['quantity_in_stock'] ?>
                                                            <?php if ($item['reorder_level'] > 0): ?>
                                                                <small class="text-muted">(min: <?= $item['reorder_level'] ?>)</small>
                                                            <?php endif; ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">N/A</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($item['supplier'] ?? '-') ?></td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="/service/item/edit?id=<?= $item['item_id'] ?>"
                                                           class="btn btn-outline-primary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button"
                                                                class="btn btn-outline-danger"
                                                                onclick="confirmDelete(<?= $item['item_id'] ?>, '<?= htmlspecialchars($item['item_name']) ?>')"
                                                                title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
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

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="delete_item_id" id="deleteItemId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(itemId, itemName) {
            if (confirm('Are you sure you want to delete "' + itemName + '"? This action cannot be undone.')) {
                document.getElementById('deleteItemId').value = itemId;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>
