<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finance Calculator - <?= htmlspecialchars($company['company_name']) ?></title>
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
                <a class="nav-link active" href="/finance">Finance</a>
                <a class="nav-link" href="/contact">Contact</a>
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <h1 class="mb-4">Finance Calculator</h1>
        <div class="row">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="form-label">Vehicle Price (€)</label>
                            <input type="number" class="form-control" id="price" value="20000" min="0" step="100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deposit (€)</label>
                            <input type="number" class="form-control" id="deposit" value="2000" min="0" step="100">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Interest Rate (%)</label>
                            <input type="number" class="form-control" id="rate" value="7.9" min="0" max="100" step="0.1">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Term (months)</label>
                            <select class="form-select" id="term">
                                <option value="12">12 months</option>
                                <option value="24">24 months</option>
                                <option value="36" selected>36 months</option>
                                <option value="48">48 months</option>
                                <option value="60">60 months</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100" onclick="calculate()">Calculate</button>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm bg-primary text-white">
                    <div class="card-body p-4 text-center">
                        <h6 class="mb-3">Estimated Monthly Payment</h6>
                        <h1 class="display-3" id="result">€0</h1>
                        <p class="mb-0" id="details">Enter values to calculate</p>
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
    <script>
        function calculate() {
            const price = parseFloat(document.getElementById('price').value);
            const deposit = parseFloat(document.getElementById('deposit').value);
            const rate = parseFloat(document.getElementById('rate').value) / 100 / 12;
            const term = parseInt(document.getElementById('term').value);
            const principal = price - deposit;
            const payment = principal * rate * Math.pow(1 + rate, term) / (Math.pow(1 + rate, term) - 1);
            document.getElementById('result').textContent = '€' + payment.toFixed(2);
            document.getElementById('details').textContent = `Over ${term} months | Total: €${(payment * term).toFixed(2)}`;
        }
        calculate();
    </script>
</body>
</html>
