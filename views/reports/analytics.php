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
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <h3 class="text-primary"><?= $stats['page_views'] ?? 0 ?></h3>
                        <small class="text-muted">Page Views</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-success text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['unique_visitors'] ?? 0 ?></h3>
                        <small>Unique Visitors</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-info text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['avg_time'] ?? '0:00' ?></h3>
                        <small>Avg. Session</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm bg-warning text-white">
                    <div class="card-body text-center">
                        <h3><?= $stats['bounce_rate'] ?? 0 ?>%</h3>
                        <small>Bounce Rate</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="mb-4">Top Pages</h5>
                        <div class="list-group list-group-flush">
                            <?php foreach (($top_pages ?? []) as $page): ?>
                                <div class="list-group-item d-flex justify-content-between">
                                    <span><?= htmlspecialchars($page['url']) ?></span>
                                    <span class="badge bg-primary"><?= $page['views'] ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h5 class="mb-4">Traffic Sources</h5>
                        <canvas id="trafficChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
