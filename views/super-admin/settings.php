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
                <form method="POST" action="/super-admin/settings">
                    <div class="mb-3">
                        <label class="form-label">Platform Name</label>
                        <input type="text" class="form-control" name="platform_name" value="<?= htmlspecialchars($settings['platform_name'] ?? 'Car Dealer SaaS') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Support Email</label>
                        <input type="email" class="form-control" name="support_email" value="<?= htmlspecialchars($settings['support_email'] ?? '') ?>">
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="maintenance_mode" <?= ($settings['maintenance_mode'] ?? false) ? 'checked' : '' ?>>
                        <label class="form-check-label">Maintenance Mode</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
