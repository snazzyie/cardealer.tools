<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body>
    <div class="container p-4">
        <h2 class="mb-4"><?= $page_header['title'] ?></h2>
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="/website/settings">
                    <div class="mb-3">
                        <label class="form-label">Website Title *</label>
                        <input type="text" class="form-control" name="website_title" value="<?= htmlspecialchars($settings['website_title'] ?? '') ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea class="form-control" name="meta_description" rows="3"><?= htmlspecialchars($settings['meta_description'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Primary Color</label>
                        <input type="color" class="form-control" name="primary_color" value="<?= htmlspecialchars($settings['primary_color'] ?? '#007bff') ?>">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="enable_bookings" <?= ($settings['enable_bookings'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label">Enable Online Bookings</label>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="show_prices" <?= ($settings['show_prices'] ?? true) ? 'checked' : '' ?>>
                        <label class="form-check-label">Show Prices on Website</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
