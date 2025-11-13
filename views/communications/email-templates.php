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
                        <a href="/email-template/new" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i> Add Template
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($_GET['created'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i> Email template created successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i> Email template updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i> Email template deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Templates Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Template Name</th>
                                        <th>Slug</th>
                                        <th>Subject</th>
                                        <th>Variables</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($templates)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-5">
                                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                                No email templates found. <a href="/email-template/new">Create your first template</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($templates as $template): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($template['template_name']) ?></strong>
                                                </td>
                                                <td>
                                                    <code><?= htmlspecialchars($template['template_slug']) ?></code>
                                                </td>
                                                <td><?= htmlspecialchars($template['template_subject']) ?></td>
                                                <td>
                                                    <?php if ($template['template_variables']): ?>
                                                        <small class="text-muted">
                                                            <?= htmlspecialchars(substr($template['template_variables'], 0, 50)) ?>
                                                            <?= strlen($template['template_variables']) > 50 ? '...' : '' ?>
                                                        </small>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($template['is_active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="/email-template/edit?id=<?= $template['template_id'] ?>"
                                                           class="btn btn-outline-primary" title="Edit">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button"
                                                                class="btn btn-outline-danger"
                                                                onclick="confirmDelete(<?= $template['template_id'] ?>, '<?= htmlspecialchars($template['template_name']) ?>')"
                                                                title="Delete">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Help Text -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Available Merge Tags</h5>
                    </div>
                    <div class="card-body">
                        <p>Use these merge tags in your email templates:</p>
                        <ul>
                            <li><code>{{customer_name}}</code> - Customer full name</li>
                            <li><code>{{customer_email}}</code> - Customer email</li>
                            <li><code>{{customer_phone}}</code> - Customer phone</li>
                            <li><code>{{vehicle_make}}</code> - Vehicle make</li>
                            <li><code>{{vehicle_model}}</code> - Vehicle model</li>
                            <li><code>{{vehicle_year}}</code> - Vehicle year</li>
                            <li><code>{{vehicle_price}}</code> - Vehicle price</li>
                            <li><code>{{company_name}}</code> - Your company name</li>
                            <li><code>{{company_phone}}</code> - Your company phone</li>
                            <li><code>{{company_email}}</code> - Your company email</li>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Delete Confirmation Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="delete_template_id" id="deleteTemplateId">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function confirmDelete(templateId, templateName) {
            if (confirm('Are you sure you want to delete "' + templateName + '"? This action cannot be undone.')) {
                document.getElementById('deleteTemplateId').value = templateId;
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
</body>
</html>
