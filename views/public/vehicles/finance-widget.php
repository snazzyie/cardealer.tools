<!-- Finance Calculator Widget -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body p-4">
        <h5 class="mb-4"><i class="bi bi-calculator me-2"></i> Finance Calculator</h5>

        <div class="mb-3">
            <label for="vehicle_price" class="form-label">Vehicle Price</label>
            <div class="input-group">
                <span class="input-group-text">€</span>
                <input type="text" class="form-control" id="vehicle_price" value="<?= number_format($vehicle['price'], 0) ?>" readonly>
            </div>
        </div>

        <div class="mb-3">
            <label for="deposit_amount" class="form-label">Deposit Amount: <span id="deposit_display">€0</span></label>
            <input type="range" class="form-range" id="deposit_slider" min="0" max="<?= $vehicle['price'] * 0.5 ?>" value="<?= $vehicle['price'] * 0.1 ?>" step="100">
            <input type="hidden" id="deposit_amount" value="<?= $vehicle['price'] * 0.1 ?>">
            <small class="text-muted">0% - 50%</small>
        </div>

        <div class="mb-3">
            <label for="term_months" class="form-label">Term Length</label>
            <select class="form-select" id="term_months">
                <option value="12">12 months</option>
                <option value="24">24 months</option>
                <option value="36" selected>36 months</option>
                <option value="48">48 months</option>
                <option value="60">60 months</option>
                <option value="72">72 months</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="interest_rate" class="form-label">Interest Rate (APR %)</label>
            <input type="number" class="form-control" id="interest_rate" value="7.9" step="0.1" min="0" max="20">
        </div>

        <hr class="my-4">

        <div class="alert alert-primary">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Monthly Payment</h6>
                    <small>Estimated repayment</small>
                </div>
                <div class="text-end">
                    <h2 class="mb-0" id="monthly_payment">€0</h2>
                </div>
            </div>
        </div>

        <div class="small text-muted">
            <p class="mb-1"><strong>Amount to Finance:</strong> <span id="finance_amount">€0</span></p>
            <p class="mb-1"><strong>Total Interest:</strong> <span id="total_interest">€0</span></p>
            <p class="mb-1"><strong>Total Payable:</strong> <span id="total_payable">€0</span></p>
        </div>

        <div class="d-grid mt-3">
            <a href="/finance/apply?vehicle_id=<?= $vehicle['vehicle_id'] ?>" class="btn btn-success">
                <i class="bi bi-file-text me-2"></i> Apply for Finance
            </a>
        </div>

        <p class="text-muted small mt-3 mb-0">
            This is an estimate only. Final terms subject to approval. Representative APR may vary.
        </p>
    </div>
</div>

<script>
// Finance Calculator
const vehiclePrice = <?= $vehicle['price'] ?>;
const depositSlider = document.getElementById('deposit_slider');
const depositAmount = document.getElementById('deposit_amount');
const depositDisplay = document.getElementById('deposit_display');
const termMonths = document.getElementById('term_months');
const interestRate = document.getElementById('interest_rate');

function calculateFinance() {
    const deposit = parseFloat(depositSlider.value);
    const term = parseInt(termMonths.value);
    const apr = parseFloat(interestRate.value) / 100;

    const amountToFinance = vehiclePrice - deposit;
    const monthlyRate = apr / 12;

    // Calculate monthly payment using amortization formula
    let monthlyPayment;
    if (monthlyRate === 0) {
        monthlyPayment = amountToFinance / term;
    } else {
        monthlyPayment = amountToFinance * (monthlyRate * Math.pow(1 + monthlyRate, term)) / (Math.pow(1 + monthlyRate, term) - 1);
    }

    const totalPayable = deposit + (monthlyPayment * term);
    const totalInterest = totalPayable - vehiclePrice;

    // Update display
    depositDisplay.textContent = '€' + deposit.toLocaleString('en-IE', {maximumFractionDigits: 0});
    document.getElementById('monthly_payment').textContent = '€' + monthlyPayment.toLocaleString('en-IE', {maximumFractionDigits: 0});
    document.getElementById('finance_amount').textContent = '€' + amountToFinance.toLocaleString('en-IE', {maximumFractionDigits: 0});
    document.getElementById('total_interest').textContent = '€' + totalInterest.toLocaleString('en-IE', {maximumFractionDigits: 0});
    document.getElementById('total_payable').textContent = '€' + totalPayable.toLocaleString('en-IE', {maximumFractionDigits: 0});
}

// Event listeners
depositSlider.addEventListener('input', function() {
    depositAmount.value = this.value;
    calculateFinance();
});

termMonths.addEventListener('change', calculateFinance);
interestRate.addEventListener('input', calculateFinance);

// Initial calculation
calculateFinance();
</script>
