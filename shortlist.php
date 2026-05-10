<?php
$pageTitle = 'My Interested PGs';
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$pdo = getPDO();
$stmt = $pdo->prepare('SELECT p.* FROM properties p JOIN interested_users i ON p.id = i.property_id WHERE i.user_id = ? ORDER BY i.added_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$properties = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h1 class="h3">Your Shortlisted Properties</h1>
      <p class="mb-0 text-muted">Review and manage the PGs you marked as interested.</p>
    </div>
  </div>

  <?php if (empty($properties)): ?>
    <div class="alert alert-info">You have not marked any PG as interested yet. Browse available properties to add them to your shortlist.</div>
  <?php else: ?>
    <div class="row g-4">
      <?php foreach ($properties as $property): ?>
        <div class="col-md-6">
          <div class="card shadow-sm h-100">
            <img src="<?php echo htmlspecialchars($property['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($property['name']); ?>" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x450?text=PG+Image'">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($property['name']); ?></h5>
              <p class="card-text text-muted mb-1"><?php echo htmlspecialchars($property['city']); ?> &bull; ₹ <?php echo number_format($property['price'], 0); ?></p>
              <p class="mb-3 small text-muted"><?php echo htmlspecialchars($property['gender']); ?> | Rating <?php echo htmlspecialchars($property['rating']); ?></p>
              <div class="mt-auto d-flex justify-content-between align-items-center">
                <a href="property-detail.php?id=<?php echo $property['id']; ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                <button class="btn btn-danger btn-sm interest-toggle-btn" data-property-id="<?php echo $property['id']; ?>">Remove</button>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>