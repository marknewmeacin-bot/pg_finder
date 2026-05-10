<?php
$pageTitle = 'Add Property';
require_once __DIR__ . '/includes/auth.php';
require_admin();
$pdo = getPDO();
$amenities = $pdo->query('SELECT id, name FROM amenities ORDER BY name')->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <p class="text-muted mb-1">Add a new PG property and upload its images.</p>
    <h3 class="mb-0">Add Property</h3>
  </div>
  <a href="<?php echo BASE_URL; ?>/admin/properties.php" class="btn btn-outline-secondary">Back to Properties</a>
</div>
<div class="card dashboard-card">
  <div class="card-body">
    <form id="propertyForm" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Property Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">City</label>
          <input type="text" name="city" class="form-control" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Price</label>
          <input type="number" name="price" class="form-control" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select" required>
            <option value="Coed">Coed</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Rating</label>
          <input type="number" name="rating" class="form-control" step="0.1" min="1" max="5" value="4.5" required>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Main Image</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6">
          <label class="form-label">Additional Images</label>
          <input type="file" name="additional_images[]" class="form-control" accept="image/*" multiple>
        </div>
        <div class="col-12">
          <label class="form-label">Amenities</label>
          <div class="row g-2">
            <?php foreach ($amenities as $amenity): ?>
              <div class="col-md-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="amenities[]" value="<?php echo $amenity['id']; ?>" id="amenity-<?php echo $amenity['id']; ?>">
                  <label class="form-check-label" for="amenity-<?php echo $amenity['id']; ?>"><?php echo htmlspecialchars($amenity['name']); ?></label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary">Save Property</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>