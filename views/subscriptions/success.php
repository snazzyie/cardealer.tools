<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; }
        .success-icon { font-size: 5rem; color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5 text-center">
                        <div class="success-icon mb-4">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>

                        <h1 class="display-5 fw-bold mb-3">Subscription Activated!</h1>
                        <p class="lead text-muted mb-4">
                            Thank you for subscribing. Your account has been upgraded and you now have access to all premium features.
                        </p>

                        <div class="alert alert-success mb-4">
                            <i class="bi bi-info-circle me-2"></i>
                            A confirmation email has been sent to your registered email address.
                        </div>

                        <div class="d-grid gap-3 d-md-flex justify-content-center">
                            <a href="/dash" class="btn btn-primary btn-lg px-5">
                                <i class="bi bi-speedometer2 me-2"></i> Go to Dashboard
                            </a>
                            <a href="/subscriptions/manage" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="bi bi-gear me-2"></i> Manage Subscription
                            </a>
                        </div>

                        <hr class="my-5">

                        <h5 class="mb-4">What's Next?</h5>
                        <div class="row text-start">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-1-circle-fill fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6>Add Your Vehicles</h6>
                                        <p class="text-muted small">Start building your inventory by adding vehicles to your system.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-2-circle-fill fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6>Customize Your Branding</h6>
                                        <p class="text-muted small">Upload your logo and set your brand colors.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-3-circle-fill fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6>Invite Your Team</h6>
                                        <p class="text-muted small">Add team members and assign them to leads.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="bi bi-4-circle-fill fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6>Launch Your Website</h6>
                                        <p class="text-muted small">Your public website is ready to go live!</p>
                                    </div>
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
