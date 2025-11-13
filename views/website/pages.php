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
        <div class="d-flex justify-content-between mb-4">
            <h2><?= $page_header['title'] ?></h2>
            <a href="/website/pages/new" class="btn btn-primary"><i class="bi bi-plus-circle me-2"></i> New Page</a>
        </div>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>Title</th><th>Slug</th><th>Status</th><th>Updated</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pages)): ?>
                                <tr><td colspan="5" class="text-center text-muted py-5">No pages found</td></tr>
                            <?php else: ?>
                                <?php foreach ($pages as $p): ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                                        <td><?= htmlspecialchars($p['slug']) ?></td>
                                        <td><span class="badge bg-<?= $p['status'] === 'published' ? 'success' : 'secondary' ?>"><?= ucfirst($p['status']) ?></span></td>
                                        <td><?= date('d/m/Y', strtotime($p['updated_date'])) ?></td>
                                        <td>
                                            <a href="/website/pages/edit?id=<?= $p['page_id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                            <a href="/<?= htmlspecialchars($p['slug']) ?>" class="btn btn-sm btn-outline-info" target="_blank"><i class="bi bi-eye"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
