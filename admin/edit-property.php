<?php
$pageTitle = 'Edit Property';
require_once __DIR__ . '/includes/auth.php';
require_admin();
$pdo = getPDO();
$propertyId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$propertyId) {
    header('Location: ' . BASE_URL . '/admin/properties.php');
    exit;
}
$propertyStmt = $pdo->prepare('SELECT * FROM properties WHERE id = ?');
$propertyStmt->execute([$propertyId]);
$property = $propertyStmt->fetch();
if (!$property) {
    header('Location: ' . BASE_URL . '/admin/properties.php');
    exit;
}
$amenities = $pdo->query('SELECT id, name FROM amenities ORDER BY name')->fetchAll();
$selectedAmenitiesStmt = $pdo->prepare('SELECT amenity_id FROM property_amenities WHERE property_id = ?');
$selectedAmenitiesStmt->execute([$propertyId]);
$selectedAmenities = array_column($selectedAmenitiesStmt->fetchAll(), 'amenity_id');
include __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <p class="text-muted mb-1">Update property details, rating and images.</p>
    <h3 class="mb-0">Edit Property</h3>
  </div>
  <a href="<?php echo BASE_URL; ?>/admin/properties.php" class="btn btn-outline-secondary">Back to Properties</a>
</div>
<div class="card dashboard-card">
  <div class="card-body">
    <form id="propertyForm" enctype="multipart/form-data">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="id" value="<?php echo $property['id']; ?>">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Property Name</label>
          <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($property['name']); ?>" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">City</label>
          <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($property['city']); ?>" required>
        </div>
        <div class="col-md-12">
          <label class="form-label">Address</label>
          <input type="text" name="address" class="form-control" value="<?php echo htmlspecialchars($property['address']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Price</label>
          <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($property['price']); ?>" required>
        </div>
        <div class="col-md-4">
          <label class="form-label">Gender</label>
          <select name="gender" class="form-select" required>
            <option value="Coed" <?php echo $property['gender'] === 'Coed' ? 'selected' : ''; ?>>Coed</option>
            <option value="Male" <?php echo $property['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo $property['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
          </select>
        </div>
        <div class="col-md-4">
          <label class="form-label">Rating</label>
          <input type="number" name="rating" class="form-control" step="0.1" min="1" max="5" value="<?php echo htmlspecialchars($property['rating']); ?>" required>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($property['description']); ?></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Replace Main Image</label>
          <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6">
          <label class="form-label">Add Additional Images</label>
          <input type="file" name="additional_images[]" class="form-control" accept="image/*" multiple>
        </div>
        <div class="col-12">
          <label class="form-label">Amenities</label>
          <div class="row g-2">
            <?php foreach ($amenities as $amenity): ?>
              <div class="col-md-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="amenities[]" value="<?php echo $amenity['id']; ?>" id="amenity-<?php echo $amenity['id']; ?>" <?php echo in_array($amenity['id'], $selectedAmenities) ? 'checked' : ''; ?>>
                  <label class="form-check-label" for="amenity-<?php echo $amenity['id']; ?>"><?php echo htmlspecialchars($amenity['name']); ?></label>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="mt-4 text-end">
        <button type="submit" class="btn btn-primary">Update Property</button>
      </div>
    </form>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>