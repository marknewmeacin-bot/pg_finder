<?php
$pageTitle = 'Interest Management';
require_once __DIR__ . '/includes/auth.php';
require_admin();
$pdo = getPDO();
$filterProperty = trim($_GET['property'] ?? '');
$filterCity = trim($_GET['city'] ?? '');
$where = [];
$params = [];
if ($filterProperty !== '') {
    $where[] = 'p.id = ?';
    $params[] = $filterProperty;
}
if ($filterCity !== '') {
    $where[] = 'p.city = ?';
    $params[] = $filterCity;
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';
$query = "SELECT iu.user_id, iu.property_id, iu.added_at, u.name AS user_name, u.email, p.name AS property_name, p.city FROM interested_users iu JOIN users u ON iu.user_id = u.id JOIN properties p ON iu.property_id = p.id $whereSql ORDER BY iu.added_at DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$interests = $stmt->fetchAll();
$cities = $pdo->query('SELECT DISTINCT city FROM properties ORDER BY city')->fetchAll(PDO::FETCH_COLUMN);
$properties = $pdo->query('SELECT id, name FROM properties ORDER BY name')->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <p class="text-muted mb-1">Review user interest records, remove false entries and filter by location.</p>
    <h3 class="mb-0">Interests</h3>
  </div>
  <div class="d-flex gap-2">
    <select id="interestFilter" class="form-select" style="min-width: 200px;">
      <option value="">All Properties</option>
      <?php foreach ($properties as $property): ?>
        <option value="<?php echo $property['id']; ?>" <?php echo $filterProperty == $property['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($property['name']); ?></option>
      <?php endforeach; ?>
    </select>
    <select class="form-select" onchange="location.href='?city=' + encodeURIComponent(this.value) + '&property=' + encodeURIComponent('<?php echo htmlspecialchars($filterProperty); ?>');" style="min-width: 160px;">
      <option value="">All Cities</option>
      <?php foreach ($cities as $city): ?>
        <option value="<?php echo htmlspecialchars($city); ?>" <?php echo $filterCity === $city ? 'selected' : ''; ?>><?php echo htmlspecialchars($city); ?></option>
      <?php endforeach; ?>
    </select>
  </div>
</div>
<div class="card dashboard-card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>User</th>
            <th>Email</th>
            <th>Property</th>
            <th>City</th>
            <th>Added At</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($interests): ?>
            <?php foreach ($interests as $interest): ?>
              <tr>
                <td><?php echo htmlspecialchars($interest['user_name']); ?></td>
                <td><?php echo htmlspecialchars($interest['email']); ?></td>
                <td><?php echo htmlspecialchars($interest['property_name']); ?></td>
                <td><?php echo htmlspecialchars($interest['city']); ?></td>
                <td><?php echo htmlspecialchars($interest['added_at']); ?></td>
                <td>
                  <button class="btn btn-sm btn-outline-danger delete-action" data-url="<?php echo BASE_URL; ?>/admin/api/interests.php" data-action="delete" data-user="<?php echo $interest['user_id']; ?>" data-property="<?php echo $interest['property_id']; ?>" data-confirm="Remove this interest record?">Remove</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center text-muted">No interest records found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>