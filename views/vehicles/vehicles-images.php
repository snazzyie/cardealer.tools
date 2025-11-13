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
                    <a href="/vehicles" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Back to Vehicles
                    </a>
                </div>

                <!-- Success Messages -->
                <?php if (isset($_GET['uploaded'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i> Image uploaded successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i> Image deleted successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['primary_set'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i> Primary image updated!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-2"></i> <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Upload Form -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Upload New Image</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" class="row g-3">
                            <div class="col-md-8">
                                <input type="file" class="form-control" name="image" accept="image/*" required>
                                <small class="text-muted">Max 10MB. Formats: JPG, PNG, GIF, WebP</small>
                            </div>
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_primary" value="1" id="is_primary">
                                    <label class="form-check-label" for="is_primary">
                                        Set as primary
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-upload me-2"></i> Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Image Gallery -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Image Gallery (<?= count($images) ?>)</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($images)): ?>
                            <div class="text-center text-muted py-5">
                                <i class="bi bi-images fs-1 d-block mb-3"></i>
                                <p>No images uploaded yet. Upload your first image above.</p>
                            </div>
                        <?php else: ?>
                            <div class="row g-3">
                                <?php foreach ($images as $image): ?>
                                    <div class="col-md-3">
                                        <div class="card <?= $image['is_primary'] ? 'border-primary' : '' ?>">
                                            <img src="<?= htmlspecialchars($image['image_url']) ?>"
                                                 class="card-img-top"
                                                 alt="Vehicle Image"
                                                 style="height: 200px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <?php if ($image['is_primary']): ?>
                                                    <span class="badge bg-primary w-100 mb-2">Primary Image</span>
                                                <?php else: ?>
                                                    <a href="/vehicles/images?id=<?= $vehicleId ?>&set_primary=<?= $image['image_id'] ?>"
                                                       class="btn btn-sm btn-outline-primary w-100 mb-2">
                                                        Set as Primary
                                                    </a>
                                                <?php endif; ?>
                                                <a href="/vehicles/images?id=<?= $vehicleId ?>&delete=<?= $image['image_id'] ?>"
                                                   class="btn btn-sm btn-outline-danger w-100"
                                                   onclick="return confirm('Delete this image?')">
                                                    <i class="bi bi-trash me-1"></i> Delete
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
