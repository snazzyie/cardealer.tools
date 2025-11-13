<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .hero { min-height: 100vh; display: flex; align-items: center; color: #fff; }
        .feature-card { transition: transform 0.3s; }
        .feature-card:hover { transform: translateY(-10px); }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">Car Dealer SaaS</a>
            <div class="ms-auto">
                <a href="/login" class="btn btn-outline-light me-2">Sign In</a>
                <a href="/register" class="btn btn-light">Get Started</a>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-3 fw-bold mb-4">The Complete Car Dealership Management Platform</h1>
                    <p class="lead mb-4">Manage your inventory, leads, appointments, and sales all in one powerful platform. Built for modern car dealerships.</p>
                    <div class="d-flex gap-3">
                        <a href="/register" class="btn btn-light btn-lg">Start Free Trial</a>
                        <a href="#features" class="btn btn-outline-light btn-lg">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card shadow-lg border-0">
                        <div class="card-body p-5">
                            <h3 class="text-center mb-4">Key Features</h3>
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-car-front text-primary fs-3 me-3"></i>
                                <div>
                                    <h6>Vehicle Management</h6>
                                    <p class="text-muted mb-0">Complete inventory control with photos, specs, and pricing</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-people text-success fs-3 me-3"></i>
                                <div>
                                    <h6>CRM & Lead Tracking</h6>
                                    <p class="text-muted mb-0">Manage leads through your sales pipeline</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mb-3">
                                <i class="bi bi-calendar text-info fs-3 me-3"></i>
                                <div>
                                    <h6>Appointment Scheduling</h6>
                                    <p class="text-muted mb-0">Google Calendar integration for test drives</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start">
                                <i class="bi bi-receipt text-warning fs-3 me-3"></i>
                                <div>
                                    <h6>Invoicing & Payments</h6>
                                    <p class="text-muted mb-0">Stripe integration for deposits and payments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Everything You Need to Run Your Dealership</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-graph-up text-primary" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Sales Analytics</h5>
                            <p class="text-muted">Track your performance with detailed reports</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-envelope text-success" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Email Automation</h5>
                            <p class="text-muted">Postmark integration for customer communications</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card feature-card border-0 shadow h-100">
                        <div class="card-body text-center p-4">
                            <i class="bi bi-whatsapp text-info" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">WhatsApp Integration</h5>
                            <p class="text-muted">Chat with customers where they are</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 bg-dark text-white text-center">
        <div class="container">
            <p class="mb-0">&copy; 2025 Car Dealer SaaS. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
