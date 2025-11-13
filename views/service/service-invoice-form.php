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
                    <a href="/service/invoices" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Back to Invoices
                    </a>
                </div>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" id="invoiceForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header"><h5 class="mb-0">Customer Details</h5></div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Customer <span class="text-danger">*</span></label>
                                        <select class="form-select" name="customer_id" required onchange="populateCustomer(this)">
                                            <option value="">Select Customer...</option>
                                            <?php foreach ($customers as $cust): ?>
                                                <option value="<?= $cust['customer_id'] ?>"
                                                        data-name="<?= htmlspecialchars($cust['first_name'] . ' ' . $cust['last_name']) ?>"
                                                        data-email="<?= htmlspecialchars($cust['email']) ?>"
                                                        data-phone="<?= htmlspecialchars($cust['phone']) ?>"
                                                        <?= (isset($invoice) && $invoice['customer_id'] == $cust['customer_id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cust['first_name'] . ' ' . $cust['last_name']) ?> - <?= htmlspecialchars($cust['email']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="customer_name" id="customer_name" value="<?= htmlspecialchars($invoice['customer_name'] ?? '') ?>">
                                    <input type="hidden" name="customer_email" id="customer_email" value="<?= htmlspecialchars($invoice['customer_email'] ?? '') ?>">
                                    <input type="hidden" name="customer_phone" id="customer_phone" value="<?= htmlspecialchars($invoice['customer_phone'] ?? '') ?>">

                                    <div class="mb-3">
                                        <label class="form-label">Vehicle Registration</label>
                                        <input type="text" class="form-control" name="vehicle_registration" value="<?= htmlspecialchars($invoice['vehicle_registration'] ?? '') ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Mileage</label>
                                        <input type="number" class="form-control" name="mileage" value="<?= htmlspecialchars($invoice['mileage'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header"><h5 class="mb-0">Service Details</h5></div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Service Type</label>
                                        <input type="text" class="form-control" name="service_type" value="<?= htmlspecialchars($invoice['service_type'] ?? 'General Service') ?>">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Invoice Date</label>
                                            <input type="date" class="form-control" name="invoice_date" value="<?= htmlspecialchars($invoice['invoice_date'] ?? date('Y-m-d')) ?>" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Service Date</label>
                                            <input type="date" class="form-control" name="service_date" value="<?= htmlspecialchars($invoice['service_date'] ?? date('Y-m-d')) ?>">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Technician</label>
                                        <select class="form-select" name="technician_id">
                                            <option value="">Select Technician...</option>
                                            <?php foreach ($technicians as $tech): ?>
                                                <option value="<?= $tech['user_id'] ?>" <?= (isset($invoice) && $invoice['technician_id'] == $tech['user_id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($tech['first_name'] . ' ' . $tech['last_name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Line Items -->
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Line Items</h5>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addLineItem()">
                                <i class="bi bi-plus-circle me-1"></i> Add Item
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="lineItemsContainer">
                                <?php if (isset($invoice['line_items']) && is_array($invoice['line_items'])): ?>
                                    <?php foreach ($invoice['line_items'] as $idx => $item): ?>
                                        <div class="row mb-2 line-item">
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" name="item_name[]" value="<?= htmlspecialchars($item['item_name']) ?>" placeholder="Item name" required>
                                                <input type="hidden" name="item_id[]" value="<?= htmlspecialchars($item['item_id'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-2">
                                                <select class="form-select" name="item_type[]">
                                                    <option value="part" <?= ($item['item_type'] ?? 'part') === 'part' ? 'selected' : '' ?>>Part</option>
                                                    <option value="service" <?= ($item['item_type'] ?? 'part') === 'service' ? 'selected' : '' ?>>Labor</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="quantity[]" value="<?= htmlspecialchars($item['quantity']) ?>" step="0.1" min="0" placeholder="Qty" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="number" class="form-control" name="unit_price[]" value="<?= htmlspecialchars($item['unit_price']) ?>" step="0.01" min="0" placeholder="Price" required>
                                            </div>
                                            <div class="col-md-2">
                                                <input type="text" class="form-control" name="item_description[]" value="<?= htmlspecialchars($item['description'] ?? '') ?>" placeholder="Description">
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-danger btn-sm" onclick="removeLineItem(this)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Notes & Actions -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header"><h5 class="mb-0">Work Performed</h5></div>
                                <div class="card-body">
                                    <textarea class="form-control" name="work_performed" rows="4"><?= htmlspecialchars($invoice['work_performed'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header"><h5 class="mb-0">Notes</h5></div>
                                <div class="card-body">
                                    <textarea class="form-control" name="notes" rows="4"><?= htmlspecialchars($invoice['notes'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="/service/invoices" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i><?= $isEdit ? 'Update Invoice' : 'Create Invoice' ?>
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
        function populateCustomer(select) {
            const option = select.options[select.selectedIndex];
            document.getElementById('customer_name').value = option.getAttribute('data-name') || '';
            document.getElementById('customer_email').value = option.getAttribute('data-email') || '';
            document.getElementById('customer_phone').value = option.getAttribute('data-phone') || '';
        }

        function addLineItem() {
            const container = document.getElementById('lineItemsContainer');
            const html = `
                <div class="row mb-2 line-item">
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="item_name[]" placeholder="Item name" required>
                        <input type="hidden" name="item_id[]" value="">
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" name="item_type[]">
                            <option value="part">Part</option>
                            <option value="service">Labor</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="quantity[]" value="1" step="0.1" min="0" placeholder="Qty" required>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="unit_price[]" step="0.01" min="0" placeholder="Price" required>
                    </div>
                    <div class="col-md-2">
                        <input type="text" class="form-control" name="item_description[]" placeholder="Description">
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeLineItem(this)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function removeLineItem(btn) {
            btn.closest('.line-item').remove();
        }

        // Add first line item if none exist
        window.addEventListener('DOMContentLoaded', function() {
            if (document.querySelectorAll('.line-item').length === 0) {
                addLineItem();
            }
        });
    </script>
</body>
</html>
