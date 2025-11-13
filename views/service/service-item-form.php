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
                        <a href="/service/items" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to Items
                        </a>
                    </div>
                </div>

                <!-- Error Messages -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Item Type -->
                                    <div class="mb-3">
                                        <label for="item_type" class="form-label">Item Type <span class="text-danger">*</span></label>
                                        <select class="form-select" id="item_type" name="item_type" required onchange="toggleFields()">
                                            <option value="part" <?= (!isset($item) || $item['item_type'] === 'part') ? 'selected' : '' ?>>Part</option>
                                            <option value="service" <?= (isset($item) && $item['item_type'] === 'service') ? 'selected' : '' ?>>Labor/Service</option>
                                        </select>
                                    </div>

                                    <!-- Item Code -->
                                    <div class="mb-3">
                                        <label for="item_code" class="form-label">Item Code</label>
                                        <input type="text"
                                               class="form-control"
                                               id="item_code"
                                               name="item_code"
                                               value="<?= htmlspecialchars($item['item_code'] ?? '') ?>"
                                               placeholder="Leave blank to auto-generate">
                                        <small class="text-muted">Auto-generated if left blank</small>
                                    </div>

                                    <!-- Item Name -->
                                    <div class="mb-3">
                                        <label for="item_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               id="item_name"
                                               name="item_name"
                                               value="<?= htmlspecialchars($item['item_name'] ?? '') ?>"
                                               placeholder="e.g., Oil Filter, Full Service"
                                               required>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control"
                                                  id="description"
                                                  name="description"
                                                  rows="3"
                                                  placeholder="Optional description"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <!-- Pricing -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Pricing</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Unit Price -->
                                    <div class="mb-3">
                                        <label for="unit_price" class="form-label">Unit Price (€) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="number"
                                                   class="form-control"
                                                   id="unit_price"
                                                   name="unit_price"
                                                   value="<?= htmlspecialchars($item['unit_price'] ?? '') ?>"
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                        </div>
                                        <small class="text-muted">Selling price charged to customers</small>
                                    </div>

                                    <!-- Cost Price -->
                                    <div class="mb-3">
                                        <label for="cost_price" class="form-label">Cost Price (€)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">€</span>
                                            <input type="number"
                                                   class="form-control"
                                                   id="cost_price"
                                                   name="cost_price"
                                                   value="<?= htmlspecialchars($item['cost_price'] ?? '0') ?>"
                                                   step="0.01"
                                                   min="0">
                                        </div>
                                        <small class="text-muted">Your cost (for profit margin calculation)</small>
                                    </div>

                                    <!-- Estimated Time (for services) -->
                                    <div class="mb-3" id="estimated_time_field">
                                        <label for="estimated_time_minutes" class="form-label">Estimated Time (minutes)</label>
                                        <input type="number"
                                               class="form-control"
                                               id="estimated_time_minutes"
                                               name="estimated_time_minutes"
                                               value="<?= htmlspecialchars($item['estimated_time_minutes'] ?? '0') ?>"
                                               min="0">
                                        <small class="text-muted">Typical time to complete this service</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory (for parts only) -->
                            <div class="card mb-4" id="inventory_fields">
                                <div class="card-header">
                                    <h5 class="mb-0">Inventory</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Quantity in Stock -->
                                    <div class="mb-3">
                                        <label for="quantity_in_stock" class="form-label">Quantity in Stock</label>
                                        <input type="number"
                                               class="form-control"
                                               id="quantity_in_stock"
                                               name="quantity_in_stock"
                                               value="<?= htmlspecialchars($item['quantity_in_stock'] ?? '0') ?>"
                                               min="0">
                                    </div>

                                    <!-- Reorder Level -->
                                    <div class="mb-3">
                                        <label for="reorder_level" class="form-label">Reorder Level</label>
                                        <input type="number"
                                               class="form-control"
                                               id="reorder_level"
                                               name="reorder_level"
                                               value="<?= htmlspecialchars($item['reorder_level'] ?? '0') ?>"
                                               min="0">
                                        <small class="text-muted">Alert when stock falls below this level</small>
                                    </div>

                                    <!-- Supplier -->
                                    <div class="mb-3">
                                        <label for="supplier" class="form-label">Supplier</label>
                                        <input type="text"
                                               class="form-control"
                                               id="supplier"
                                               name="supplier"
                                               value="<?= htmlspecialchars($item['supplier'] ?? '') ?>"
                                               placeholder="Supplier name">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="/service/items" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <?= $isEdit ? 'Update Item' : 'Create Item' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Toggle fields based on item type
        function toggleFields() {
            const itemType = document.getElementById('item_type').value;
            const inventoryFields = document.getElementById('inventory_fields');
            const estimatedTimeField = document.getElementById('estimated_time_field');

            if (itemType === 'service') {
                // Hide inventory, show estimated time
                inventoryFields.style.display = 'none';
                estimatedTimeField.style.display = 'block';
            } else {
                // Show inventory, hide estimated time
                inventoryFields.style.display = 'block';
                estimatedTimeField.style.display = 'block'; // Can show for both
            }
        }

        // Run on page load
        toggleFields();
    </script>
</body>
</html>
