<!DOCTYPE html>
<html lang="en">
<?php require BASE_PATH . 'views/partials/head.php'; ?>
<body>
    <?php require BASE_PATH . 'views/partials/navbar.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <?php require BASE_PATH . 'views/partials/sidebar.php'; ?>

            <main class="col-md-10 ms-sm-auto px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2"><?= htmlspecialchars($page_header['title']) ?></h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="/email-templates" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i> Back to Templates
                        </a>
                    </div>
                </div>

                <!-- Error Messages -->
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Template Details</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Template Name -->
                                    <div class="mb-3">
                                        <label for="template_name" class="form-label">Template Name <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               id="template_name"
                                               name="template_name"
                                               value="<?= htmlspecialchars($template['template_name'] ?? '') ?>"
                                               placeholder="e.g., Welcome Email"
                                               required>
                                    </div>

                                    <!-- Template Slug -->
                                    <div class="mb-3">
                                        <label for="template_slug" class="form-label">Template Slug</label>
                                        <input type="text"
                                               class="form-control"
                                               id="template_slug"
                                               name="template_slug"
                                               value="<?= htmlspecialchars($template['template_slug'] ?? '') ?>"
                                               placeholder="e.g., welcome-email (auto-generated if left blank)">
                                        <small class="text-muted">Used for code references. Leave blank to auto-generate.</small>
                                    </div>

                                    <!-- Subject -->
                                    <div class="mb-3">
                                        <label for="template_subject" class="form-label">Email Subject <span class="text-danger">*</span></label>
                                        <input type="text"
                                               class="form-control"
                                               id="template_subject"
                                               name="template_subject"
                                               value="<?= htmlspecialchars($template['template_subject'] ?? '') ?>"
                                               placeholder="e.g., Welcome to {{company_name}}"
                                               required>
                                        <small class="text-muted">You can use merge tags like {{customer_name}}</small>
                                    </div>

                                    <!-- Email Body -->
                                    <div class="mb-3">
                                        <label for="template_body" class="form-label">Email Body (HTML) <span class="text-danger">*</span></label>
                                        <textarea class="form-control font-monospace"
                                                  id="template_body"
                                                  name="template_body"
                                                  rows="15"
                                                  required><?= htmlspecialchars($template['template_body'] ?? '') ?></textarea>
                                        <small class="text-muted">HTML formatting supported. Use merge tags for dynamic content.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-4">
                            <!-- Settings -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Settings</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Active Status -->
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_active"
                                               name="is_active"
                                               <?= (!isset($template) || $template['is_active']) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Available Variables -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Merge Tags</h5>
                                </div>
                                <div class="card-body">
                                    <small>
                                        <strong>Customer:</strong><br>
                                        <code>{{customer_name}}</code><br>
                                        <code>{{customer_email}}</code><br>
                                        <code>{{customer_phone}}</code><br><br>

                                        <strong>Vehicle:</strong><br>
                                        <code>{{vehicle_make}}</code><br>
                                        <code>{{vehicle_model}}</code><br>
                                        <code>{{vehicle_year}}</code><br>
                                        <code>{{vehicle_price}}</code><br><br>

                                        <strong>Company:</strong><br>
                                        <code>{{company_name}}</code><br>
                                        <code>{{company_phone}}</code><br>
                                        <code>{{company_email}}</code><br>
                                    </small>
                                </div>
                            </div>

                            <!-- Template Variables (JSON) -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Custom Variables</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="template_variables" class="form-label">JSON Variables</label>
                                        <textarea class="form-control font-monospace"
                                                  id="template_variables"
                                                  name="template_variables"
                                                  rows="4"><?= htmlspecialchars($template['template_variables'] ?? '') ?></textarea>
                                        <small class="text-muted">Optional JSON object for custom variables</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <a href="/email-templates" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle me-2"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-2"></i>
                                    <?= $isEdit ? 'Update Template' : 'Create Template' ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        (function() {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
