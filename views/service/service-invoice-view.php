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
                    <h1 class="h2">Service Invoice: <?= htmlspecialchars($invoice['invoice_number']) ?></h1>
                    <div class="btn-toolbar">
                        <a href="/service/invoice/edit?id=<?= $invoice['service_invoice_id'] ?>" class="btn btn-outline-primary me-2">
                            <i class="bi bi-pencil me-1"></i> Edit
                        </a>
                        <a href="/service/invoices?generate_pdf=<?= $invoice['service_invoice_id'] ?>" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-file-pdf me-1"></i> Generate PDF
                        </a>
                        <a href="/service/invoices" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>

                <?php if (isset($_GET['created']) || isset($_GET['updated']) || isset($_GET['payment_updated'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php if (isset($_GET['created'])): ?>Invoice created successfully!<?php endif; ?>
                        <?php if (isset($_GET['updated'])): ?>Invoice updated successfully!<?php endif; ?>
                        <?php if (isset($_GET['payment_updated'])): ?>Payment updated successfully!<?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Invoice Details -->
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Invoice Details</h5>
                                <div>
                                    <?php if ($invoice['payment_status'] === 'paid'): ?>
                                        <span class="badge bg-success fs-6">Paid</span>
                                    <?php elseif ($invoice['payment_status'] === 'partial'): ?>
                                        <span class="badge bg-warning fs-6">Partial</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger fs-6">Unpaid</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6>Customer Information</h6>
                                        <p class="mb-1"><strong><?= htmlspecialchars($invoice['customer_name']) ?></strong></p>
                                        <?php if ($invoice['customer_email']): ?>
                                            <p class="mb-1"><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($invoice['customer_email']) ?></p>
                                        <?php endif; ?>
                                        <?php if ($invoice['customer_phone']): ?>
                                            <p class="mb-1"><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($invoice['customer_phone']) ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Service Information</h6>
                                        <p class="mb-1"><strong>Type:</strong> <?= htmlspecialchars($invoice['service_type'] ?? 'General Service') ?></p>
                                        <p class="mb-1"><strong>Date:</strong> <?= date('d/m/Y', strtotime($invoice['service_date'])) ?></p>
                                        <?php if ($invoice['vehicle_registration']): ?>
                                            <p class="mb-1"><strong>Vehicle:</strong> <?= htmlspecialchars($invoice['vehicle_registration']) ?></p>
                                        <?php endif; ?>
                                        <?php if ($invoice['mileage']): ?>
                                            <p class="mb-1"><strong>Mileage:</strong> <?= number_format($invoice['mileage']) ?> km</p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Line Items -->
                                <h6 class="mt-4">Services & Parts</h6>
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item</th>
                                            <th>Type</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($invoice['line_items'] as $item): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($item['item_name']) ?></strong>
                                                    <?php if (!empty($item['description'])): ?>
                                                        <br><small class="text-muted"><?= htmlspecialchars($item['description']) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($item['item_type'] === 'service'): ?>
                                                        <span class="badge bg-primary">Labor</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Part</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end"><?= number_format($item['quantity'], 1) ?></td>
                                                <td class="text-end">€<?= number_format($item['unit_price'], 2) ?></td>
                                                <td class="text-end"><strong>€<?= number_format($item['quantity'] * $item['unit_price'], 2) ?></strong></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                            <td class="text-end"><strong>€<?= number_format($invoice['subtotal'], 2) ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end">VAT (<?= number_format($invoice['vat_rate'], 0) ?>%):</td>
                                            <td class="text-end">€<?= number_format($invoice['vat_amount'], 2) ?></td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                            <td class="text-end"><strong class="fs-5">€<?= number_format($invoice['total'], 2) ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>

                                <?php if ($invoice['work_performed']): ?>
                                    <h6 class="mt-4">Work Performed</h6>
                                    <p><?= nl2br(htmlspecialchars($invoice['work_performed'])) ?></p>
                                <?php endif; ?>

                                <?php if ($invoice['notes']): ?>
                                    <h6 class="mt-4">Notes</h6>
                                    <p><?= nl2br(htmlspecialchars($invoice['notes'])) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Sidebar -->
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-header"><h5 class="mb-0">Payment Status</h5></div>
                            <div class="card-body">
                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="form-label">Payment Status</label>
                                        <select class="form-select" name="payment_status">
                                            <option value="unpaid" <?= $invoice['payment_status'] === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                            <option value="partial" <?= $invoice['payment_status'] === 'partial' ? 'selected' : '' ?>>Partial</option>
                                            <option value="paid" <?= $invoice['payment_status'] === 'paid' ? 'selected' : '' ?>>Paid</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Amount Paid (€)</label>
                                        <input type="number" class="form-control" name="paid_amount" value="<?= number_format($invoice['paid_amount'] ?? 0, 2, '.', '') ?>" step="0.01">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <select class="form-select" name="payment_method">
                                            <option value="">Select...</option>
                                            <option value="cash" <?= ($invoice['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Cash</option>
                                            <option value="card" <?= ($invoice['payment_method'] ?? '') === 'card' ? 'selected' : '' ?>>Card</option>
                                            <option value="bank_transfer" <?= ($invoice['payment_method'] ?? '') === 'bank_transfer' ? 'selected' : '' ?>>Bank Transfer</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="update_payment" class="btn btn-primary w-100">
                                        <i class="bi bi-check-circle me-2"></i> Update Payment
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
