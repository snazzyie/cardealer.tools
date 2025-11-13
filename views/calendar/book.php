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
                    <a href="/crm"><i class="bi bi-people me-2"></i> CRM</a>
                    <a href="/calendar" class="active"><i class="bi bi-calendar me-2"></i> Calendar</a>
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

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <form method="POST">
                                        <h5 class="mb-4">Customer Information</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="first_name" class="form-label">First Name *</label>
                                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="last_name" class="form-label">Last Name</label>
                                                <input type="text" class="form-control" id="last_name" name="last_name">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="email" class="form-label">Email *</label>
                                                <input type="email" class="form-control" id="email" name="email" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label">Phone *</label>
                                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <h5 class="mb-4">Appointment Details</h5>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="appointment_date" class="form-label">Date *</label>
                                                <input type="date" class="form-control" id="appointment_date" name="appointment_date"
                                                       min="<?= date('Y-m-d') ?>"
                                                       max="<?= date('Y-m-d', strtotime('+60 days')) ?>"
                                                       required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="appointment_time" class="form-label">Time *</label>
                                                <select class="form-select" id="appointment_time" name="appointment_time" required>
                                                    <option value="">Select time...</option>
                                                    <?php
                                                    for ($hour = 9; $hour <= 17; $hour++) {
                                                        for ($min = 0; $min < 60; $min += 30) {
                                                            $time = sprintf("%02d:%02d:00", $hour, $min);
                                                            $display = date('g:i A', strtotime($time));
                                                            echo "<option value=\"{$time}\">{$display}</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="appointment_type" class="form-label">Appointment Type *</label>
                                                <select class="form-select" id="appointment_type" name="appointment_type" required>
                                                    <option value="test_drive">Test Drive</option>
                                                    <option value="consultation">Consultation</option>
                                                    <option value="viewing">Vehicle Viewing</option>
                                                    <option value="collection">Vehicle Collection</option>
                                                    <option value="service">Service</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="vehicle_id" class="form-label">Vehicle</label>
                                                <select class="form-select" id="vehicle_id" name="vehicle_id">
                                                    <option value="">No specific vehicle</option>
                                                    <?php foreach ($vehicles as $v): ?>
                                                        <option value="<?= $v['vehicle_id'] ?>" <?= ($vehicle && $vehicle['vehicle_id'] == $v['vehicle_id']) ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($v['year'] . ' ' . $v['make'] . ' ' . $v['model']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="assigned_to" class="form-label">Assign to Sales Person</label>
                                            <select class="form-select" id="assigned_to" name="assigned_to">
                                                <option value="">Unassigned</option>
                                                <?php foreach ($users as $u): ?>
                                                    <option value="<?= $u['user_id'] ?>">
                                                        <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                                      placeholder="Any special requirements or notes..."></textarea>
                                        </div>

                                        <div class="d-flex justify-content-between mt-4">
                                            <a href="/calendar" class="btn btn-outline-secondary">Cancel</a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i> Book Appointment
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="card-title mb-3">Appointment Types</h6>
                                    <ul class="list-unstyled small">
                                        <li class="mb-2"><i class="bi bi-car-front text-primary me-2"></i> <strong>Test Drive:</strong> Experience the vehicle on the road</li>
                                        <li class="mb-2"><i class="bi bi-chat-dots text-info me-2"></i> <strong>Consultation:</strong> Discuss options and requirements</li>
                                        <li class="mb-2"><i class="bi bi-eye text-success me-2"></i> <strong>Viewing:</strong> See the vehicle in person</li>
                                        <li class="mb-2"><i class="bi bi-key text-warning me-2"></i> <strong>Collection:</strong> Pick up purchased vehicle</li>
                                        <li class="mb-2"><i class="bi bi-tools text-secondary me-2"></i> <strong>Service:</strong> Maintenance or repairs</li>
                                    </ul>
                                </div>
                            </div>

                            <?php if ($vehicle): ?>
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">Selected Vehicle</h6>
                                        <h5><?= htmlspecialchars($vehicle['year'] . ' ' . $vehicle['make'] . ' ' . $vehicle['model']) ?></h5>
                                        <p class="text-primary fw-bold mb-0">€<?= number_format($vehicle['price'], 0) ?></p>
                                    </div>
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
