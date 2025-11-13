<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - <?= htmlspecialchars($company['company_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/"><?= htmlspecialchars($company['company_name']) ?></a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="/">Home</a>
                <a class="nav-link" href="/stock">Stock</a>
                <a class="nav-link" href="/about">About</a>
                <a class="nav-link active" href="/contact">Contact</a>
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <h1 class="mb-4">Contact Us</h1>
        <?php if (isset($_GET['sent'])): ?>
            <div class="alert alert-success">Thank you! Your message has been sent. We'll get back to you soon.</div>
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="/contact/submit">
                            <div class="mb-3">
                                <label class="form-label">Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" name="phone">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject</label>
                                <input type="text" class="form-control" name="subject">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Message *</label>
                                <textarea class="form-control" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-3">Get In Touch</h5>
                        <p><i class="bi bi-telephone me-2"></i><?= htmlspecialchars($company['company_phone'] ?? 'N/A') ?></p>
                        <p><i class="bi bi-envelope me-2"></i><?= htmlspecialchars($company['company_email']) ?></p>
                        <?php if ($company['address']): ?>
                            <p><i class="bi bi-geo-alt me-2"></i><?= nl2br(htmlspecialchars($company['address'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> <?= htmlspecialchars($company['company_name']) ?>. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
