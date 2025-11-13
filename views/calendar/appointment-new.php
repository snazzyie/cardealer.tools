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

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/calendar/appointment/new">
                        <div class="row">
                            <div class="col-lg-8">
                                <!-- Appointment Details -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Appointment Details</h5>

                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title *</label>
                                            <input type="text" class="form-control" id="title" name="title" required placeholder="e.g., Test Drive - BMW 3 Series">
                                        </div>

                                        <div class="mb-3">
                                            <label for="appointment_type" class="form-label">Type *</label>
                                            <select class="form-select" id="appointment_type" name="appointment_type" required>
                                                <option value="">Select type...</option>
                                                <option value="test-drive">Test Drive</option>
                                                <option value="viewing">Vehicle Viewing</option>
                                                <option value="service">Service Appointment</option>
                                                <option value="meeting">Meeting</option>
                                                <option value="delivery">Vehicle Delivery</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="appointment_date" class="form-label">Date *</label>
                                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="appointment_time" class="form-label">Time *</label>
                                                <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="duration" class="form-label">Duration (minutes)</label>
                                            <select class="form-select" id="duration" name="duration">
                                                <option value="15">15 minutes</option>
                                                <option value="30" selected>30 minutes</option>
                                                <option value="45">45 minutes</option>
                                                <option value="60">1 hour</option>
                                                <option value="90">1.5 hours</option>
                                                <option value="120">2 hours</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Additional information about this appointment..."></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer Information -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body p-4">
                                        <h5 class="mb-4">Customer Information</h5>

                                        <div class="mb-3">
                                            <label for="customer_id" class="form-label">Select Existing Customer</label>
                                            <select class="form-select" id="customer_id" name="customer_id">
                                                <option value="">New Customer</option>
                                                <?php if (!empty($customers)): ?>
                                                    <?php foreach ($customers as $c): ?>
                                                        <option value="<?= $c['customer_id'] ?>">
                                                            <?= htmlspecialchars($c['first_name'] . ' ' . $c['last_name']) ?> - <?= htmlspecialchars($c['email']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div id="newCustomerFields">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="customer_name" class="form-label">Customer Name *</label>
                                                    <input type="text" class="form-control" id="customer_name" name="customer_name">
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="customer_email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" id="customer_email" name="customer_email">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="customer_phone" class="form-label">Phone *</label>
                                                <input type="tel" class="form-control" id="customer_phone" name="customer_phone">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <!-- Assignment -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h6 class="mb-3">Assignment</h6>

                                        <div class="mb-3">
                                            <label for="assigned_to" class="form-label">Assign To</label>
                                            <select class="form-select" id="assigned_to" name="assigned_to">
                                                <?php if (!empty($users)): ?>
                                                    <?php foreach ($users as $u): ?>
                                                        <option value="<?= $u['user_id'] ?>" <?= $u['user_id'] == $user_data['user_id'] ? 'selected' : '' ?>>
                                                            <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="vehicle_id" class="form-label">Related Vehicle</label>
                                            <select class="form-select" id="vehicle_id" name="vehicle_id">
                                                <option value="">None</option>
                                                <?php if (!empty($vehicles)): ?>
                                                    <?php foreach ($vehicles as $v): ?>
                                                        <option value="<?= $v['vehicle_id'] ?>">
                                                            <?= htmlspecialchars($v['make'] . ' ' . $v['model'] . ' ' . $v['year']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="card border-0 shadow-sm mb-3">
                                    <div class="card-body">
                                        <h6 class="mb-3">Status</h6>

                                        <div class="mb-3">
                                            <label for="status" class="form-label">Appointment Status</label>
                                            <select class="form-select" id="status" name="status">
                                                <option value="scheduled" selected>Scheduled</option>
                                                <option value="confirmed">Confirmed</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="no-show">No Show</option>
                                            </select>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="send_confirmation" name="send_confirmation" checked>
                                            <label class="form-check-label" for="send_confirmation">
                                                Send confirmation email
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-2"></i> Create Appointment
                                            </button>
                                            <a href="/calendar" class="btn btn-outline-secondary">Cancel</a>
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
    <script>
        // Toggle new customer fields
        document.getElementById('customer_id').addEventListener('change', function() {
            const newCustomerFields = document.getElementById('newCustomerFields');
            const customerName = document.getElementById('customer_name');
            const customerPhone = document.getElementById('customer_phone');

            if (this.value) {
                newCustomerFields.style.display = 'none';
                customerName.required = false;
                customerPhone.required = false;
            } else {
                newCustomerFields.style.display = 'block';
                customerName.required = true;
                customerPhone.required = true;
            }
        });
    </script>
</body>
</html>
