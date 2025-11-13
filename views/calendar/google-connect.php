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
        .google-logo { height: 40px; }
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
                    <a href="/finance-partners"><i class="bi bi-credit-card me-2"></i> Finance</a>
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

                    <?php if ($success === 'connected'): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="bi bi-check-circle me-2"></i>
                            <strong>Success!</strong> Your Google Calendar has been connected successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($success === 'disconnected'): ?>
                        <div class="alert alert-info alert-dismissible fade show">
                            <i class="bi bi-info-circle me-2"></i>
                            Your Google Calendar has been disconnected.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Error:</strong> Failed to connect Google Calendar. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-5">
                                    <div class="text-center mb-4">
                                        <img src="https://www.gstatic.com/images/branding/product/2x/calendar_2020q4_48dp.png" alt="Google Calendar" class="google-logo mb-3">
                                        <h3>Google Calendar Integration</h3>
                                    </div>

                                    <?php if ($is_connected): ?>
                                        <!-- Connected State -->
                                        <div class="alert alert-success mb-4">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-check-circle-fill fs-2 me-3"></i>
                                                <div>
                                                    <strong>Connected</strong>
                                                    <p class="mb-0 small">Your Google Calendar is synced with your appointments</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h5 class="mb-3">How It Works</h5>
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="bi bi-check-circle text-success me-2"></i>
                                                    New appointments automatically appear in your Google Calendar
                                                </li>
                                                <li class="mb-2">
                                                    <i class="bi bi-check-circle text-success me-2"></i>
                                                    Updates and cancellations are synced in real-time
                                                </li>
                                                <li class="mb-2">
                                                    <i class="bi bi-check-circle text-success me-2"></i>
                                                    Customer details are included in event descriptions
                                                </li>
                                                <li class="mb-2">
                                                    <i class="bi bi-check-circle text-success me-2"></i>
                                                    Automatic email reminders from Google
                                                </li>
                                            </ul>
                                        </div>

                                        <form method="POST" action="/calendar/google-connect">
                                            <input type="hidden" name="action" value="disconnect">
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-x-circle me-2"></i> Disconnect Google Calendar
                                            </button>
                                        </form>

                                    <?php else: ?>
                                        <!-- Not Connected State -->
                                        <div class="mb-4">
                                            <h5 class="mb-3">Why Connect Google Calendar?</h5>
                                            <ul class="list-unstyled">
                                                <li class="mb-3">
                                                    <div class="d-flex">
                                                        <i class="bi bi-check-circle text-primary me-3 fs-5"></i>
                                                        <div>
                                                            <strong>Two-Way Sync</strong>
                                                            <p class="text-muted small mb-0">Appointments automatically sync between your dashboard and Google Calendar</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="mb-3">
                                                    <div class="d-flex">
                                                        <i class="bi bi-bell text-primary me-3 fs-5"></i>
                                                        <div>
                                                            <strong>Smart Reminders</strong>
                                                            <p class="text-muted small mb-0">Get email and mobile notifications before appointments</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="mb-3">
                                                    <div class="d-flex">
                                                        <i class="bi bi-phone text-primary me-3 fs-5"></i>
                                                        <div>
                                                            <strong>Mobile Access</strong>
                                                            <p class="text-muted small mb-0">View appointments on your phone via Google Calendar app</p>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li class="mb-3">
                                                    <div class="d-flex">
                                                        <i class="bi bi-people text-primary me-3 fs-5"></i>
                                                        <div>
                                                            <strong>Customer Invites</strong>
                                                            <p class="text-muted small mb-0">Customers receive calendar invites automatically</p>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>

                                        <?php if ($auth_url): ?>
                                            <div class="d-grid">
                                                <a href="<?= htmlspecialchars($auth_url) ?>" class="btn btn-primary btn-lg">
                                                    <i class="bi bi-google me-2"></i> Connect Google Calendar
                                                </a>
                                            </div>
                                            <p class="text-muted text-center mt-3 small">
                                                You'll be redirected to Google to authorize access. We only access your calendar data.
                                            </p>
                                        <?php else: ?>
                                            <div class="alert alert-warning">
                                                <i class="bi bi-exclamation-triangle me-2"></i>
                                                Google Calendar integration is not configured. Please contact your administrator.
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h6 class="mb-3"><i class="bi bi-shield-check me-2"></i> Privacy & Security</h6>
                                    <p class="small text-muted mb-0">
                                        We use OAuth 2.0 for secure authentication. We only read and write calendar events - we never access your emails or other Google services.
                                    </p>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="mb-3"><i class="bi bi-question-circle me-2"></i> Need Help?</h6>
                                    <p class="small text-muted">
                                        Having trouble connecting? Make sure you've enabled the Google Calendar API in your Google Cloud Console.
                                    </p>
                                    <a href="/company" class="btn btn-sm btn-outline-primary">Contact Support</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
