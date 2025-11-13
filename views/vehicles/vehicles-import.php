<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_header['title'] ?> - Car Dealer SaaS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { min-height: 100vh; background: #f8f9fa; }
        .sidebar { min-height: 100vh; background: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.1); }
        .sidebar a { color: #333; text-decoration: none; padding: 12px 20px; display: block; transition: all 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #e9ecef; color: #007bff; }
        .navbar { background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .upload-area { border: 2px dashed #dee2e6; border-radius: 8px; padding: 3rem; text-align: center; transition: all 0.3s; }
        .upload-area:hover { border-color: #007bff; background: #f8f9fa; }
        .csv-template { background: #f8f9fa; padding: 1rem; border-radius: 4px; font-family: monospace; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar p-0">
                <div class="p-3">
                    <h4 class="text-primary mb-4">Car Dealer</h4>
                </div>
                <nav>
                    <a href="/dash"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a href="/vehicles" class="active"><i class="bi bi-car-front me-2"></i> Vehicles</a>
                    <a href="/crm"><i class="bi bi-people me-2"></i> CRM</a>
                    <a href="/calendar"><i class="bi bi-calendar me-2"></i> Calendar</a>
                    <a href="/enquiries"><i class="bi bi-envelope me-2"></i> Enquiries</a>
                    <a href="/customers"><i class="bi bi-person me-2"></i> Customers</a>
                    <a href="/invoices"><i class="bi bi-receipt me-2"></i> Invoices</a>
                    <a href="/inbox"><i class="bi bi-inbox me-2"></i> Inbox</a>
                    <a href="/reports"><i class="bi bi-graph-up me-2"></i> Reports</a>
                    <?php if ($permission >= 2): ?>
                        <hr>
                        <a href="/users"><i class="bi bi-people me-2"></i> Users</a>
                        <a href="/company"><i class="bi bi-building me-2"></i> Company</a>
                        <a href="/subscriptions"><i class="bi bi-credit-card me-2"></i> Subscription</a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-0">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container-fluid">
                        <h5 class="mb-0"><?= $page_header['title'] ?></h5>
                        <div class="d-flex align-items-center">
                            <span class="me-3"><?= htmlspecialchars($user_data['first_name'] . ' ' . $user_data['last_name']) ?></span>
                            <img src="<?= fn_get_gravatar($user_data['email'], 40) ?>" class="rounded-circle me-3" alt="User">
                            <a href="/logout" class="btn btn-outline-danger btn-sm">Logout</a>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="text-muted mb-0"><?= $page_header['subtitle'] ?></h6>
                        <a href="/vehicles" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to Vehicles
                        </a>
                    </div>

                    <!-- Success/Error Messages -->
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            <?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Import Results -->
                    <?php if (!empty($import_results)): ?>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="bi bi-clipboard-data text-primary me-2"></i>
                                    Import Results
                                </h5>
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="p-3 bg-success bg-opacity-10 rounded">
                                            <h2 class="text-success mb-0"><?= $import_results['imported'] ?? 0 ?></h2>
                                            <small class="text-muted">Imported</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                                            <h2 class="text-warning mb-0"><?= $import_results['skipped'] ?? 0 ?></h2>
                                            <small class="text-muted">Skipped</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="p-3 bg-info bg-opacity-10 rounded">
                                            <h2 class="text-info mb-0"><?= $import_results['total'] ?? 0 ?></h2>
                                            <small class="text-muted">Total Rows</small>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($import_results['errors'])): ?>
                                    <div class="mt-4">
                                        <h6 class="text-danger">Errors:</h6>
                                        <ul class="list-unstyled">
                                            <?php foreach ($import_results['errors'] as $error): ?>
                                                <li class="text-danger">
                                                    <i class="bi bi-x-circle me-2"></i>
                                                    <?= htmlspecialchars($error) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Upload Form -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title mb-4">
                                <i class="bi bi-upload text-primary me-2"></i>
                                Upload CSV File
                            </h5>

                            <form method="POST" action="/vehicles/import" enctype="multipart/form-data">
                                <div class="upload-area mb-4">
                                    <i class="bi bi-file-earmark-spreadsheet text-primary" style="font-size: 3rem;"></i>
                                    <h5 class="mt-3 mb-2">Choose CSV file to upload</h5>
                                    <p class="text-muted mb-3">Select a CSV file containing vehicle data</p>
                                    <input type="file" class="form-control w-50 mx-auto" name="csv_file" accept=".csv" required>
                                </div>

                                <div class="d-flex justify-content-center gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-upload me-2"></i>
                                        Import Vehicles
                                    </button>
                                    <a href="/vehicles" class="btn btn-outline-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- CSV Format Instructions -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="bi bi-info-circle text-info me-2"></i>
                                CSV Format Requirements
                            </h5>

                            <p class="text-muted mb-3">
                                Your CSV file should contain the following columns (first row as headers):
                            </p>

                            <div class="table-responsive mb-3">
                                <table class="table table-sm table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Column Name</th>
                                            <th>Required</th>
                                            <th>Description</th>
                                            <th>Example</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><code>make</code></td>
                                            <td><span class="badge bg-danger">Required</span></td>
                                            <td>Vehicle manufacturer</td>
                                            <td>BMW, Mercedes, Audi</td>
                                        </tr>
                                        <tr>
                                            <td><code>model</code></td>
                                            <td><span class="badge bg-danger">Required</span></td>
                                            <td>Vehicle model</td>
                                            <td>3 Series, C-Class, A4</td>
                                        </tr>
                                        <tr>
                                            <td><code>year</code></td>
                                            <td><span class="badge bg-danger">Required</span></td>
                                            <td>Year of manufacture</td>
                                            <td>2020, 2021, 2022</td>
                                        </tr>
                                        <tr>
                                            <td><code>price</code></td>
                                            <td><span class="badge bg-danger">Required</span></td>
                                            <td>Sale price in Euro</td>
                                            <td>25000, 30000</td>
                                        </tr>
                                        <tr>
                                            <td><code>registration</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Vehicle registration number</td>
                                            <td>201-D-12345</td>
                                        </tr>
                                        <tr>
                                            <td><code>mileage</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Vehicle mileage</td>
                                            <td>50000, 75000</td>
                                        </tr>
                                        <tr>
                                            <td><code>fuel_type</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Fuel type</td>
                                            <td>Petrol, Diesel, Electric, Hybrid</td>
                                        </tr>
                                        <tr>
                                            <td><code>transmission</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Transmission type</td>
                                            <td>Manual, Automatic</td>
                                        </tr>
                                        <tr>
                                            <td><code>body_type</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Body style</td>
                                            <td>Saloon, SUV, Hatchback, Coupe</td>
                                        </tr>
                                        <tr>
                                            <td><code>colour</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Exterior color</td>
                                            <td>Black, White, Blue, Red</td>
                                        </tr>
                                        <tr>
                                            <td><code>doors</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Number of doors</td>
                                            <td>2, 4, 5</td>
                                        </tr>
                                        <tr>
                                            <td><code>engine_size</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Engine size in liters</td>
                                            <td>2.0, 3.0, 1.5</td>
                                        </tr>
                                        <tr>
                                            <td><code>description</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Vehicle description</td>
                                            <td>Full service history, one owner</td>
                                        </tr>
                                        <tr>
                                            <td><code>status</code></td>
                                            <td><span class="badge bg-secondary">Optional</span></td>
                                            <td>Vehicle status</td>
                                            <td>available, reserved, sold, coming-soon</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info mb-3">
                                <i class="bi bi-lightbulb me-2"></i>
                                <strong>Example CSV format:</strong>
                            </div>

                            <div class="csv-template">
make,model,year,price,registration,mileage,fuel_type,transmission,body_type,colour,doors,engine_size,description,status<br>
BMW,3 Series,2020,25000,201-D-12345,50000,Petrol,Automatic,Saloon,Black,4,2.0,Full service history,available<br>
Mercedes,C-Class,2021,32000,211-D-54321,30000,Diesel,Automatic,Saloon,Silver,4,2.1,One owner,available<br>
Audi,A4,2019,22000,191-D-99999,65000,Diesel,Manual,Saloon,Blue,4,2.0,Great condition,available
                            </div>

                            <div class="alert alert-warning mt-3">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <strong>Important Notes:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>The first row must contain column headers exactly as shown above</li>
                                    <li>Required fields (make, model, year, price) must have values for each vehicle</li>
                                    <li>Mileage unit will default to 'km' if not specified</li>
                                    <li>Status will default to 'available' if not specified</li>
                                    <li>Duplicate registrations will be skipped</li>
                                    <li>Invalid data rows will be skipped and logged</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // File input preview
        document.querySelector('input[type="file"]').addEventListener('change', function(e) {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                const fileSize = (this.files[0].size / 1024).toFixed(2);
                console.log(`Selected file: ${fileName} (${fileSize} KB)`);
            }
        });
    </script>
</body>
</html>
