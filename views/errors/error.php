<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-container {
            text-align: center;
            color: #fff;
        }
        .error-code {
            font-size: 8rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .error-card {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-container">
            <div class="error-code"><?= substr($page_title, 0, 3) ?></div>
            <div class="error-card">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h3 class="mb-3"><?= htmlspecialchars($page_title) ?></h3>
                        <p class="text-muted mb-4"><?= htmlspecialchars($error_message) ?></p>
                        <div class="d-grid gap-2">
                            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] >= 1): ?>
                                <a href="/dash" class="btn btn-primary">Go to Dashboard</a>
                            <?php else: ?>
                                <a href="/" class="btn btn-primary">Go to Home</a>
                            <?php endif; ?>
                            <a href="javascript:history.back()" class="btn btn-outline-secondary">Go Back</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
