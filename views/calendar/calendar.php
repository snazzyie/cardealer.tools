<?php
// Group appointments by date
$appointments_by_date = [];
foreach ($appointments as $apt) {
    $date = date('Y-m-d', strtotime($apt['start_datetime']));
    if (!isset($appointments_by_date[$date])) {
        $appointments_by_date[$date] = [];
    }
    $appointments_by_date[$date][] = $apt;
}

// Calculate calendar data
$first_day = date('N', mktime(0, 0, 0, $month, 1, $year)); // 1=Monday, 7=Sunday
$days_in_month = date('t', mktime(0, 0, 0, $month, 1, $year));
$prev_month = $month == 1 ? 12 : $month - 1;
$prev_year = $month == 1 ? $year - 1 : $year;
$next_month = $month == 12 ? 1 : $month + 1;
$next_year = $month == 12 ? $year + 1 : $year;
?>
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
        .calendar { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #dee2e6; }
        .calendar-header { background: #007bff; color: white; padding: 10px; text-align: center; font-weight: 600; }
        .calendar-day { background: white; min-height: 100px; padding: 8px; position: relative; }
        .calendar-day.other-month { background: #f8f9fa; color: #adb5bd; }
        .calendar-day.today { background: #fff3cd; }
        .day-number { font-weight: 600; margin-bottom: 5px; }
        .appointment-pill { font-size: 0.75rem; background: #007bff; color: white; padding: 2px 6px; border-radius: 3px; margin-bottom: 2px; display: block; cursor: pointer; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .appointment-pill:hover { background: #0056b3; }
        .appointment-pill.confirmed { background: #28a745; }
        .appointment-pill.completed { background: #6c757d; }
        .appointment-pill.cancelled { background: #dc3545; }
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
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-muted mb-0"><?= $page_header['subtitle'] ?></h6>
                        <a href="/calendar/book" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Book Appointment
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Today's Appointments</h6>
                                    <h2 class="mb-0"><?= $stats['today'] ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">This Week</h6>
                                    <h2 class="mb-0"><?= $stats['this_week'] ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted mb-2">Completed This Month</h6>
                                    <h2 class="mb-0"><?= $stats['completed_month'] ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Calendar -->
                        <div class="col-lg-9">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-4">
                                    <!-- Month Navigation -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <a href="?month=<?= $prev_month ?>&year=<?= $prev_year ?>" class="btn btn-outline-primary">
                                            <i class="bi bi-chevron-left"></i>
                                        </a>
                                        <h4 class="mb-0"><?= date('F Y', mktime(0, 0, 0, $month, 1, $year)) ?></h4>
                                        <a href="?month=<?= $next_month ?>&year=<?= $next_year ?>" class="btn btn-outline-primary">
                                            <i class="bi bi-chevron-right"></i>
                                        </a>
                                    </div>

                                    <!-- Calendar Grid -->
                                    <div class="calendar">
                                        <!-- Headers -->
                                        <div class="calendar-header">Mon</div>
                                        <div class="calendar-header">Tue</div>
                                        <div class="calendar-header">Wed</div>
                                        <div class="calendar-header">Thu</div>
                                        <div class="calendar-header">Fri</div>
                                        <div class="calendar-header">Sat</div>
                                        <div class="calendar-header">Sun</div>

                                        <?php
                                        // Previous month padding
                                        for ($i = 1; $i < $first_day; $i++) {
                                            echo '<div class="calendar-day other-month"></div>';
                                        }

                                        // Current month days
                                        $today = date('Y-m-d');
                                        for ($day = 1; $day <= $days_in_month; $day++) {
                                            $current_date = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
                                            $is_today = $current_date === $today;
                                            $day_appointments = $appointments_by_date[$current_date] ?? [];

                                            echo '<div class="calendar-day' . ($is_today ? ' today' : '') . '">';
                                            echo '<div class="day-number">' . $day . '</div>';

                                            foreach ($day_appointments as $apt) {
                                                $status_class = $apt['status'];
                                                $time = date('H:i', strtotime($apt['start_datetime']));
                                                $title = $time . ' - ' . htmlspecialchars($apt['customer_first_name']);
                                                echo '<div class="appointment-pill ' . $status_class . '" title="' . htmlspecialchars($title) . '">';
                                                echo htmlspecialchars($time . ' ' . $apt['customer_first_name']);
                                                echo '</div>';
                                            }

                                            echo '</div>';
                                        }

                                        // Next month padding
                                        $remaining_days = 7 - (($first_day + $days_in_month - 1) % 7);
                                        if ($remaining_days < 7) {
                                            for ($i = 0; $i < $remaining_days; $i++) {
                                                echo '<div class="calendar-day other-month"></div>';
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Appointments -->
                        <div class="col-lg-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="mb-3">Upcoming Appointments</h6>

                                    <?php if (empty($upcoming)): ?>
                                        <p class="text-muted small">No upcoming appointments</p>
                                    <?php else: ?>
                                        <?php foreach ($upcoming as $apt): ?>
                                            <div class="border-bottom pb-2 mb-2">
                                                <div class="fw-bold"><?= htmlspecialchars($apt['customer_first_name'] . ' ' . $apt['customer_last_name']) ?></div>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar me-1"></i>
                                                    <?= date('D, M j', strtotime($apt['start_datetime'])) ?>
                                                    <?= date('g:i A', strtotime($apt['start_datetime'])) ?>
                                                </small>
                                                <?php if ($apt['make']): ?>
                                                    <div class="small text-muted">
                                                        <i class="bi bi-car-front me-1"></i>
                                                        <?= htmlspecialchars($apt['year'] . ' ' . $apt['make'] . ' ' . $apt['model']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
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
