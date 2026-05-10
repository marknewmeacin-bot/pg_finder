<?php
$pageTitle = 'Property Management';
require_once __DIR__ . '/includes/auth.php';
require_admin();
$pdo = getPDO();
$query = 'SELECT p.id, p.name, p.city, p.price, p.gender, p.rating, COUNT(pi.id) AS images_count FROM properties p LEFT JOIN property_images pi ON p.id = pi.property_id GROUP BY p.id ORDER BY p.id DESC';
$properties = $pdo->query($query)->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <p class="text-muted mb-1">Manage property inventory and media assets.</p>
    <h3 class="mb-0">Properties</h3>
  </div>
  <a href="<?php echo BASE_URL; ?>/admin/add-property.php" class="btn btn-primary"><i class="fas fa-plus me-2"></i>Add New Property</a>
</div>
<div class="card dashboard-card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>Name</th>
            <th>City</th>
            <th>Price</th>
            <th>Gender</th>
            <th>Rating</th>
            <th>Images</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($properties): ?>
            <?php foreach ($properties as $property): ?>
              <tr>
                <td><?php echo htmlspecialchars($property['name']); ?></td>
                <td><?php echo htmlspecialchars($property['city']); ?></td>
                <td>₹ <?php echo number_format($property['price'], 0); ?></td>
                <td><?php echo htmlspecialchars($property['gender']); ?></td>
                <td><?php echo htmlspecialchars($property['rating']); ?></td>
                <td><?php echo htmlspecialchars($property['images_count']); ?></td>
                <td>
                  <a class="btn btn-sm btn-outline-secondary me-1" href="<?php echo BASE_URL; ?>/admin/edit-property.php?id=<?php echo $property['id']; ?>">Edit</a>
                  <button class="btn btn-sm btn-outline-danger delete-action" data-url="<?php echo BASE_URL; ?>/admin/api/properties.php" data-confirm="Delete this property permanently?" data-type="json" data-action="delete" data-id="<?php echo $property['id']; ?>">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="7" class="text-center text-muted">No properties found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>