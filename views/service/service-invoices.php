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
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/service/invoice/new" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Create Service Invoice
                        </a>
                    </div>
                </div>

                <?php if (isset($_GET['created']) || isset($_GET['updated']) || isset($_GET['deleted'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>
                        <?php if (isset($_GET['created'])): ?>Invoice created successfully!<?php endif; ?>
                        <?php if (isset($_GET['updated'])): ?>Invoice updated successfully!<?php endif; ?>
                        <?php if (isset($_GET['deleted'])): ?>Invoice deleted successfully!<?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <?php if ($stats): ?>
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h6>Total Revenue</h6>
                                    <h3>€<?= number_format($stats['total_revenue'], 2) ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h6>Total Invoices</h6>
                                    <h3><?= number_format($stats['total_invoices']) ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h6>Outstanding</h6>
                                    <h3>€<?= number_format($stats['outstanding'], 2) ?></h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h6>Average Invoice</h6>
                                    <h3>€<?= number_format($stats['average_invoice_value'], 2) ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Invoices Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Service Type</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($invoices)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                No service invoices found. <a href="/service/invoice/new">Create your first invoice</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($invoices as $inv): ?>
                                            <tr>
                                                <td>
                                                    <a href="/service/invoice/view?id=<?= $inv['service_invoice_id'] ?>" class="text-decoration-none">
                                                        <strong><?= htmlspecialchars($inv['invoice_number']) ?></strong>
                                                    </a>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($inv['invoice_date'])) ?></td>
                                                <td>
                                                    <?= htmlspecialchars($inv['customer_name']) ?>
                                                    <?php if ($inv['vehicle_registration']): ?>
                                                        <br><small class="text-muted"><?= htmlspecialchars($inv['vehicle_registration']) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($inv['service_type'] ?? 'General Service') ?></td>
                                                <td><strong>€<?= number_format($inv['total'], 2) ?></strong></td>
                                                <td>
                                                    <?php if ($inv['payment_status'] === 'paid'): ?>
                                                        <span class="badge bg-success">Paid</span>
                                                    <?php elseif ($inv['payment_status'] === 'partial'): ?>
                                                        <span class="badge bg-warning">Partial</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Unpaid</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($inv['status'] === 'paid'): ?>
                                                        <span class="badge bg-success">Paid</span>
                                                    <?php elseif ($inv['status'] === 'sent'): ?>
                                                        <span class="badge bg-info">Sent</span>
                                                    <?php elseif ($inv['status'] === 'void'): ?>
                                                        <span class="badge bg-secondary">Void</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-warning">Draft</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="/service/invoice/view?id=<?= $inv['service_invoice_id'] ?>" class="btn btn-outline-primary" title="View">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="/service/invoice/edit?id=<?= $inv['service_invoice_id'] ?>" class="btn btn-outline-secondary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <?php if ($inv['invoice_pdf_url']): ?>
                                                            <a href="<?= htmlspecialchars($inv['invoice_pdf_url']) ?>" class="btn btn-outline-info" title="Download PDF" target="_blank">
                                                                <i class="bi bi-file-pdf"></i>
                                                            </a>
                                                        <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
