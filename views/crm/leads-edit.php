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
                    <h6 class="text-muted mb-4"><?= $page_header['subtitle'] ?></h6>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/crm/leads/edit?id=<?= $lead['lead_id'] ?>">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Contact Information -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Contact Information</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="first_name" class="form-label">First Name *</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" value="<?= htmlspecialchars($lead['first_name']) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="last_name" class="form-label">Last Name *</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name" value="<?= htmlspecialchars($lead['last_name']) ?>" required>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email *</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($lead['email']) ?>" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="tel" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($lead['phone'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($lead['address'] ?? '') ?></textarea>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control" id="city" name="city" value="<?= htmlspecialchars($lead['city'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="postcode" class="form-label">Postcode</label>
                                                <input type="text" class="form-control" id="postcode" name="postcode" value="<?= htmlspecialchars($lead['postcode'] ?? '') ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Lead Details -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Lead Details</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="source" class="form-label">Source *</label>
                                                <select class="form-select" id="source" name="source" required>
                                                    <option value="">Select source...</option>
                                                    <option value="website" <?= $lead['source'] === 'website' ? 'selected' : '' ?>>Website</option>
                                                    <option value="phone" <?= $lead['source'] === 'phone' ? 'selected' : '' ?>>Phone Call</option>
                                                    <option value="email" <?= $lead['source'] === 'email' ? 'selected' : '' ?>>Email</option>
                                                    <option value="walk-in" <?= $lead['source'] === 'walk-in' ? 'selected' : '' ?>>Walk-in</option>
                                                    <option value="referral" <?= $lead['source'] === 'referral' ? 'selected' : '' ?>>Referral</option>
                                                    <option value="social-media" <?= $lead['source'] === 'social-media' ? 'selected' : '' ?>>Social Media</option>
                                                    <option value="advertising" <?= $lead['source'] === 'advertising' ? 'selected' : '' ?>>Advertising</option>
                                                    <option value="other" <?= $lead['source'] === 'other' ? 'selected' : '' ?>>Other</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="interested_vehicle" class="form-label">Interested In</label>
                                                <input type="text" class="form-control" id="interested_vehicle" name="interested_vehicle" value="<?= htmlspecialchars($lead['interested_vehicle'] ?? '') ?>">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="budget_min" class="form-label">Budget Range (€)</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="budget_min" name="budget_min" value="<?= htmlspecialchars($lead['budget_min'] ?? '') ?>" placeholder="Min" step="1000">
                                                <span class="input-group-text">to</span>
                                                <input type="number" class="form-control" name="budget_max" value="<?= htmlspecialchars($lead['budget_max'] ?? '') ?>" placeholder="Max" step="1000">
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="4"><?= htmlspecialchars($lead['notes'] ?? '') ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Status & Assignment -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h6 class="mb-3">Status & Assignment</h6>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="new" <?= $lead['status'] === 'new' ? 'selected' : '' ?>>New</option>
                                                <option value="contacted" <?= $lead['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                                                <option value="qualified" <?= $lead['status'] === 'qualified' ? 'selected' : '' ?>>Qualified</option>
                                                <option value="won" <?= $lead['status'] === 'won' ? 'selected' : '' ?>>Won</option>
                                                <option value="lost" <?= $lead['status'] === 'lost' ? 'selected' : '' ?>>Lost</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="assigned_to" class="form-label">Assign To</label>
                                            <select class="form-select" id="assigned_to" name="assigned_to">
                                                <option value="">Unassigned</option>
                                                <?php if (!empty($users)): ?>
                                                    <?php foreach ($users as $u): ?>
                                                        <option value="<?= $u['user_id'] ?>" <?= ($lead['assigned_to'] ?? '') == $u['user_id'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="priority" class="form-label">Priority</label>
                                            <select class="form-select" id="priority" name="priority">
                                                <option value="low" <?= ($lead['priority'] ?? 'medium') === 'low' ? 'selected' : '' ?>>Low</option>
                                                <option value="medium" <?= ($lead['priority'] ?? 'medium') === 'medium' ? 'selected' : '' ?>>Medium</option>
                                                <option value="high" <?= ($lead['priority'] ?? 'medium') === 'high' ? 'selected' : '' ?>>High</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="follow_up_date" class="form-label">Follow-up Date</label>
                                            <input type="date" class="form-control" id="follow_up_date" name="follow_up_date" value="<?= htmlspecialchars($lead['follow_up_date'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i> Update Lead
                                            </button>
                                            <a href="/crm/leads/view?id=<?= $lead['lead_id'] ?>" class="btn btn-outline-secondary">Cancel</a>
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
</body>
</html>
