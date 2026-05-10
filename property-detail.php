<?php
$pageTitle = 'Property Details';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
include __DIR__ . '/includes/header.php';

$propertyId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$propertyId) {
    header('Location: index.php');
    exit;
}

$pdo = getPDO();
$propertyStmt = $pdo->prepare('SELECT *, (SELECT image FROM property_images WHERE property_id = properties.id LIMIT 1) as image FROM properties WHERE id = ?');
$propertyStmt->execute([$propertyId]);
$property = $propertyStmt->fetch();
if (!$property) {
    echo '<div class="container"><div class="alert alert-warning">Property not found.</div></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$amenitiesStmt = $pdo->prepare('SELECT a.name FROM amenities a JOIN property_amenities pa ON a.id = pa.amenity_id WHERE pa.property_id = ?');
$amenitiesStmt->execute([$propertyId]);
$amenities = $amenitiesStmt->fetchAll(PDO::FETCH_COLUMN);

$similarStmt = $pdo->prepare('SELECT p.id, p.name, p.city, p.price, p.gender, p.rating, (SELECT image FROM property_images WHERE property_id = p.id LIMIT 1) as image FROM properties p WHERE city = ? AND id != ? LIMIT 3');
$similarStmt->execute([$property['city'], $propertyId]);
$similar = $similarStmt->fetchAll();

function getImageUrl($image) {
    if (empty($image)) {
        return BASE_URL . '/images/pg1.jpg';
    }
    $image = trim($image);
    if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
        return $image;
    }
    if (strpos($image, '/') === false) {
        return BASE_URL . '/images/' . $image;
    }
    return BASE_URL . '/' . ltrim($image, '/');
}

$userId = $_SESSION['user_id'] ?? null;
$interested = false;
if ($userId) {
    $checkInterest = $pdo->prepare('SELECT 1 FROM interested_users WHERE user_id = ? AND property_id = ?');
    $checkInterest->execute([$userId, $propertyId]);
    $interested = (bool) $checkInterest->fetchColumn();
}
?>
<div class="container">
  <div class="row gx-5">
    <div class="col-lg-7">
      <div class="card mb-4 shadow-sm">
        <div class="row g-0">
          <div class="col-12">
            <img src="<?php echo htmlspecialchars(getImageUrl($property['image'])); ?>" class="img-fluid rounded-top" alt="<?php echo htmlspecialchars($property['name']); ?>" onerror="this.src='https://via.placeholder.com/900x500?text=PG+Image'">
          </div>
        </div>
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
              <h2 class="h3 mb-1"><?php echo htmlspecialchars($property['name']); ?></h2>
              <p class="text-muted mb-1"><i class="fas fa-map-marker-alt me-2"></i><?php echo htmlspecialchars($property['city']); ?> &bull; <?php echo htmlspecialchars($property['address']); ?></p>
              <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($property['gender']); ?></span>
              <span class="badge bg-success"><i class="fas fa-star"></i> <?php echo htmlspecialchars($property['rating']); ?></span>
            </div>
            <div class="text-end">
              <h3 class="text-primary mb-1">₹ <?php echo number_format($property['price'], 0); ?></h3>
              <small class="text-muted">per month</small>
            </div>
          </div>
          <p class="lead"><?php echo nl2br(htmlspecialchars($property['description'])); ?></p>
          <div class="mb-3">
            <h5>Amenities</h5>
            <div class="d-flex flex-wrap gap-2">
              <?php foreach ($amenities as $amenity): ?>
                <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($amenity); ?></span>
              <?php endforeach; ?>
            </div>
          </div>
          <button class="btn btn-lg <?php echo $interested ? 'btn-outline-danger' : 'btn-danger'; ?>" id="detailInterestBtn" data-property-id="<?php echo $propertyId; ?>">
            <i class="fas fa-heart me-2"></i>
            <?php echo $interested ? 'Remove from Interested' : 'Mark as Interested'; ?>
          </button>
        </div>
      </div>
    </div>
    <div class="col-lg-5">
      <div class="card shadow-sm mb-4">
        <div class="card-body">
          <h5 class="card-title">Quick Info</h5>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>City:</strong> <?php echo htmlspecialchars($property['city']); ?></li>
            <li class="list-group-item"><strong>Price:</strong> ₹ <?php echo number_format($property['price'], 0); ?></li>
            <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars($property['gender']); ?></li>
            <li class="list-group-item"><strong>Rating:</strong> <?php echo htmlspecialchars($property['rating']); ?> / 5</li>
          </ul>
        </div>
      </div>
      <div class="card shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Similar Properties</h5>
          <?php if ($similar): ?>
            <?php foreach ($similar as $item): ?>
              <div class="card mb-3">
                <div class="row g-0">
                  <div class="col-4">
                    <img src="<?php echo htmlspecialchars(getImageUrl($item['image'])); ?>" class="img-fluid rounded-start" alt="<?php echo htmlspecialchars($item['name']); ?>" onerror="this.src='<?php echo BASE_URL; ?>/images/pg1.jpg'">
                  </div>
                  <div class="col-8">
                    <div class="card-body py-2">
                      <h6 class="card-title mb-1"><?php echo htmlspecialchars($item['name']); ?></h6>
                      <p class="card-text small text-muted mb-1"><?php echo htmlspecialchars($item['city']); ?></p>
                      <p class="card-text small mb-1"><strong>₹ <?php echo number_format($item['price'], 0); ?></strong></p>
                      <a href="property-detail.php?id=<?php echo $item['id']; ?>" class="stretched-link"></a>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p class="text-muted mb-0">No similar listings found.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>