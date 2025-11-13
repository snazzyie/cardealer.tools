<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .plan-card { transition: all 0.3s; cursor: pointer; }
        .plan-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        .plan-card.popular { border: 3px solid #667eea; position: relative; }
        .popular-badge { position: absolute; top: -15px; right: 20px; background: #667eea; color: white; padding: 5px 15px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; }
        .feature-list { list-style: none; padding: 0; }
        .feature-list li { padding: 10px 0; border-bottom: 1px solid #eee; }
        .feature-list li:last-child { border-bottom: none; }
        .feature-list i { color: #28a745; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container py-5">
        <!-- Header -->
        <div class="text-center text-white mb-5">
            <h1 class="display-4 fw-bold mb-3">Choose Your Plan</h1>
            <p class="lead">Select the perfect plan for your dealership</p>
            <?php if ($company_data['status'] === 'trial'): ?>
                <div class="badge bg-warning text-dark fs-6 mt-2">
                    <i class="bi bi-clock me-2"></i>
                    Trial ends: <?= date('d M Y', strtotime($company_data['trial_ends_at'])) ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <!-- Pricing Cards -->
        <div class="row g-4 mb-4">
            <?php foreach ($plans as $plan): ?>
                <div class="col-md-4">
                    <form method="POST" action="/subscriptions/plans">
                        <input type="hidden" name="plan_id" value="<?= $plan['plan_id'] ?>">

                        <div class="card plan-card border-0 shadow-lg h-100 <?= isset($plan['popular']) && $plan['popular'] ? 'popular' : '' ?>" onclick="this.closest('form').submit()">
                            <?php if (isset($plan['popular']) && $plan['popular']): ?>
                                <div class="popular-badge">MOST POPULAR</div>
                            <?php endif; ?>

                            <div class="card-body p-4">
                                <h3 class="card-title text-center mb-3"><?= htmlspecialchars($plan['name']) ?></h3>

                                <div class="text-center mb-4">
                                    <h1 class="display-4 fw-bold">
                                        €<?= number_format($plan['price'], 0) ?>
                                    </h1>
                                    <p class="text-muted">per <?= $plan['interval'] ?></p>
                                </div>

                                <ul class="feature-list">
                                    <?php foreach ($plan['features'] as $feature): ?>
                                        <li>
                                            <i class="bi bi-check-circle-fill"></i>
                                            <?= htmlspecialchars($feature) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>

                                <button type="submit" class="btn btn-primary w-100 mt-4 py-3 fw-bold">
                                    <?php if ($current_subscription && $current_subscription['plan_id'] === $plan['plan_id']): ?>
                                        <i class="bi bi-check-circle me-2"></i> Current Plan
                                    <?php else: ?>
                                        Select Plan <i class="bi bi-arrow-right ms-2"></i>
                                    <?php endif; ?>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Current Subscription Info -->
        <?php if ($current_subscription): ?>
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-2">Current Subscription</h5>
                            <p class="text-muted mb-0">
                                You are currently on the <strong><?= ucfirst($current_subscription['plan_id']) ?></strong> plan
                                (<?= ucfirst($current_subscription['status']) ?>)
                            </p>
                            <?php if ($current_subscription['current_period_end']): ?>
                                <small class="text-muted">
                                    Renews on: <?= date('d M Y', strtotime($current_subscription['current_period_end'])) ?>
                                </small>
                            <?php endif; ?>
                        </div>
                        <div>
                            <a href="/subscriptions/manage" class="btn btn-outline-primary">
                                <i class="bi bi-gear me-2"></i> Manage Subscription
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Back to Dashboard -->
        <div class="text-center mt-4">
            <a href="/dash" class="btn btn-light">
                <i class="bi bi-arrow-left me-2"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
