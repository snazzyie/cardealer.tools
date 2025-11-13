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
                <form method="POST" action="/website/menus">
                    <p class="text-muted mb-4">Manage navigation menu items (one per line, format: Label|URL)</p>
                    <div class="mb-3">
                        <label class="form-label">Main Menu Items</label>
                        <textarea class="form-control" name="menu_items" rows="10" placeholder="Home|/&#10;Stock|/stock&#10;About|/about&#10;Contact|/contact"><?= htmlspecialchars($menu_items ?? '') ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Menu</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
