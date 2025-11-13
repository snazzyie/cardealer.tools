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
        .activity-item { border-left: 3px solid #007bff; padding-left: 15px; margin-bottom: 20px; }
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
                    <a href="/vehicles"><i class="bi bi-car-front me-2"></i> Vehicles</a>
                    <a href="/crm" class="active"><i class="bi bi-people me-2"></i> CRM</a>
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
                            <a href="/crm/leads" class="text-decoration-none text-muted">
                                <i class="bi bi-arrow-left me-2"></i> Back to Leads
                            </a>
                        </div>
                        <div>
                            <a href="/crm/leads/edit?id=<?= $lead['lead_id'] ?>" class="btn btn-primary">
                                <i class="bi bi-pencil me-2"></i> Edit Lead
                            </a>
                        </div>
                    </div>

                    <?php if (isset($_GET['updated'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i> Lead updated successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Lead Information -->
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                        <div>
                                            <h3><?= htmlspecialchars($lead['first_name'] . ' ' . $lead['last_name']) ?></h3>
                                            <?php
                                            $badge_colors = [
                                                'new' => 'primary',
                                                'contacted' => 'info',
                                                'qualified' => 'warning',
                                                'won' => 'success',
                                                'lost' => 'danger'
                                            ];
                                            $color = $badge_colors[$lead['status']] ?? 'secondary';
                                            ?>
                                            <span class="badge bg-<?= $color ?> me-2"><?= ucfirst($lead['status']) ?></span>
                                            <?php if ($lead['priority']): ?>
                                                <span class="badge bg-<?= $lead['priority'] === 'high' ? 'danger' : ($lead['priority'] === 'medium' ? 'warning' : 'secondary') ?>">
                                                    <?= ucfirst($lead['priority']) ?> Priority
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">Created</small><br>
                                            <strong><?= date('d M Y', strtotime($lead['created_date'])) ?></strong>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Contact Information</h6>
                                            <p class="mb-2">
                                                <i class="bi bi-envelope me-2"></i>
                                                <a href="mailto:<?= htmlspecialchars($lead['email']) ?>"><?= htmlspecialchars($lead['email']) ?></a>
                                            </p>
                                            <?php if ($lead['phone']): ?>
                                                <p class="mb-2">
                                                    <i class="bi bi-telephone me-2"></i>
                                                    <a href="tel:<?= htmlspecialchars($lead['phone']) ?>"><?= htmlspecialchars($lead['phone']) ?></a>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($lead['address']): ?>
                                                <p class="mb-2">
                                                    <i class="bi bi-geo-alt me-2"></i>
                                                    <?= nl2br(htmlspecialchars($lead['address'])) ?>
                                                    <?php if ($lead['city']): ?>
                                                        <br><?= htmlspecialchars($lead['city']) ?>
                                                    <?php endif; ?>
                                                    <?php if ($lead['postcode']): ?>
                                                        <?= htmlspecialchars($lead['postcode']) ?>
                                                    <?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted mb-3">Lead Details</h6>
                                            <p class="mb-2">
                                                <strong>Source:</strong> <?= ucfirst(str_replace('-', ' ', $lead['source'])) ?>
                                            </p>
                                            <?php if ($lead['interested_vehicle']): ?>
                                                <p class="mb-2">
                                                    <strong>Interested In:</strong> <?= htmlspecialchars($lead['interested_vehicle']) ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($lead['budget_min'] || $lead['budget_max']): ?>
                                                <p class="mb-2">
                                                    <strong>Budget:</strong>
                                                    <?php if ($lead['budget_min']): ?>€<?= number_format($lead['budget_min']) ?><?php endif; ?>
                                                    <?php if ($lead['budget_min'] && $lead['budget_max']): ?> - <?php endif; ?>
                                                    <?php if ($lead['budget_max']): ?>€<?= number_format($lead['budget_max']) ?><?php endif; ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($lead['assigned_to']): ?>
                                                <p class="mb-2">
                                                    <strong>Assigned To:</strong> <?= htmlspecialchars($lead['assigned_first_name'] . ' ' . $lead['assigned_last_name']) ?>
                                                </p>
                                            <?php endif; ?>
                                            <?php if ($lead['follow_up_date']): ?>
                                                <p class="mb-2">
                                                    <strong>Follow-up:</strong> <?= date('d M Y', strtotime($lead['follow_up_date'])) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if ($lead['notes']): ?>
                                        <hr>
                                        <h6 class="text-muted mb-3">Notes</h6>
                                        <p><?= nl2br(htmlspecialchars($lead['notes'])) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Activity Timeline -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <h5 class="mb-4">Activity Timeline</h5>

                                    <?php if (!empty($activities)): ?>
                                        <?php foreach ($activities as $activity): ?>
                                            <div class="activity-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong><?= htmlspecialchars($activity['activity_type']) ?></strong>
                                                        <p class="mb-1"><?= nl2br(htmlspecialchars($activity['description'])) ?></p>
                                                        <small class="text-muted">
                                                            by <?= htmlspecialchars($activity['user_first_name'] . ' ' . $activity['user_last_name']) ?>
                                                        </small>
                                                    </div>
                                                    <small class="text-muted"><?= date('d/m/Y H:i', strtotime($activity['created_date'])) ?></small>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted text-center py-4">No activities recorded yet</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <!-- Quick Actions -->
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="mb-3">Quick Actions</h6>
                                    <div class="d-grid gap-2">
                                        <a href="mailto:<?= htmlspecialchars($lead['email']) ?>" class="btn btn-outline-primary">
                                            <i class="bi bi-envelope me-2"></i> Send Email
                                        </a>
                                        <?php if ($lead['phone']): ?>
                                            <a href="tel:<?= htmlspecialchars($lead['phone']) ?>" class="btn btn-outline-success">
                                                <i class="bi bi-telephone me-2"></i> Call
                                            </a>
                                        <?php endif; ?>
                                        <a href="/calendar/new?lead_id=<?= $lead['lead_id'] ?>" class="btn btn-outline-info">
                                            <i class="bi bi-calendar-plus me-2"></i> Schedule Meeting
                                        </a>
                                        <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#activityModal">
                                            <i class="bi bi-plus-circle me-2"></i> Add Activity
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Convert to Customer -->
                            <?php if ($lead['status'] === 'won'): ?>
                                <div class="card border-0 shadow-sm bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="mb-3">Convert to Customer</h6>
                                        <p class="small">This lead has been marked as won. Convert to customer?</p>
                                        <a href="/customers/new?from_lead=<?= $lead['lead_id'] ?>" class="btn btn-light">
                                            <i class="bi bi-person-check me-2"></i> Convert Now
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Modal -->
    <div class="modal fade" id="activityModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="/crm/activities/add">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Activity</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="lead_id" value="<?= $lead['lead_id'] ?>">
                        <div class="mb-3">
                            <label for="activity_type" class="form-label">Type</label>
                            <select class="form-select" id="activity_type" name="activity_type" required>
                                <option value="Call">Call</option>
                                <option value="Email">Email</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Note">Note</option>
                                <option value="Task">Task</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Activity</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
