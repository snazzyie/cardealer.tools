<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Test Drive - <?= htmlspecialchars($company['company_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/"><?= htmlspecialchars($company['company_name']) ?></a>
        </div>
    </nav>
    <div class="container py-5">
        <h1 class="mb-4">Book a Test Drive</h1>
        <?php if (isset($vehicle)): ?>
            <div class="alert alert-info">
                <strong>Vehicle:</strong> <?= htmlspecialchars($vehicle['make'] . ' ' . $vehicle['model'] . ' ' . $vehicle['year']) ?>
            </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="/enquiry/test-drive/submit">
                            <?php if (isset($vehicle)): ?>
                                <input type="hidden" name="vehicle_id" value="<?= $vehicle['vehicle_id'] ?>">
                            <?php endif; ?>
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
                                    <label class="form-label">Phone *</label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Preferred Date</label>
                                    <input type="date" class="form-control" name="preferred_date">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Preferred Time</label>
                                    <select class="form-select" name="preferred_time">
                                        <option value="morning">Morning (9am-12pm)</option>
                                        <option value="afternoon">Afternoon (12pm-5pm)</option>
                                        <option value="evening">Evening (5pm-7pm)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Additional Comments</label>
                                <textarea class="form-control" name="comments" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg">Book Test Drive</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
