<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/auth.php';
require_admin();
$pdo = getPDO();

total_users:;
$stats = [];
$stats['users'] = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
$stats['properties'] = $pdo->query('SELECT COUNT(*) FROM properties')->fetchColumn();
$stats['interests'] = $pdo->query('SELECT COUNT(*) FROM interested_users')->fetchColumn();
$stats['cities'] = $pdo->query('SELECT COUNT(DISTINCT city) FROM properties')->fetchColumn();

$recentActivitiesStmt = $pdo->prepare('SELECT u.name AS user_name, p.name AS property_name, p.city, iu.added_at FROM interested_users iu JOIN users u ON iu.user_id = u.id JOIN properties p ON iu.property_id = p.id ORDER BY iu.added_at DESC LIMIT 6');
$recentActivitiesStmt->execute();
$recentActivities = $recentActivitiesStmt->fetchAll();

$topPropertiesStmt = $pdo->query('SELECT p.id, p.name, p.city, COUNT(iu.user_id) AS interest_count FROM properties p LEFT JOIN interested_users iu ON p.id = iu.property_id GROUP BY p.id ORDER BY interest_count DESC LIMIT 5');
$topProperties = $topPropertiesStmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="container-fluid">
  <div class="row admin-summary row-cols-1 row-cols-md-2 row-cols-xl-4 mb-4">
    <div class="col">
      <div class="card dashboard-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="text-muted">Total Users</h6>
            <h3><?php echo $stats['users']; ?></h3>
          </div>
          <div class="display-6 text-primary"><i class="fas fa-users"></i></div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card dashboard-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="text-muted">Total Properties</h6>
            <h3><?php echo $stats['properties']; ?></h3>
          </div>
          <div class="display-6 text-success"><i class="fas fa-building"></i></div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card dashboard-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="text-muted">Total Interests</h6>
            <h3><?php echo $stats['interests']; ?></h3>
          </div>
          <div class="display-6 text-warning"><i class="fas fa-heart"></i></div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card dashboard-card p-3">
        <div class="d-flex align-items-center justify-content-between">
          <div>
            <h6 class="text-muted">Cities Covered</h6>
            <h3><?php echo $stats['cities']; ?></h3>
          </div>
          <div class="display-6 text-info"><i class="fas fa-city"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-lg-7">
      <div class="card dashboard-card">
        <div class="card-body">
          <h5 class="card-title">Recent Interest Activity</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead>
                <tr>
                  <th>User</th>
                  <th>Property</th>
                  <th>City</th>
                  <th>Added</th>
                </tr>
              </thead>
              <tbody>
                <?php if ($recentActivities): ?>
                  <?php foreach ($recentActivities as $activity): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($activity['user_name']); ?></td>
                      <td><?php echo htmlspecialchars($activity['property_name']); ?></td>
                      <td><?php echo htmlspecialchars($activity['city']); ?></td>
                      <td><?php echo htmlspecialchars($activity['added_at']); ?></td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="4" class="text-center text-muted">No recent activity.</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card dashboard-card">
        <div class="card-body">
          <h5 class="card-title">Top Properties by Interest</h5>
          <ul class="list-group list-group-flush">
            <?php if ($topProperties): ?>
              <?php foreach ($topProperties as $property): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong><?php echo htmlspecialchars($property['name']); ?></strong>
                    <div class="text-muted small"><?php echo htmlspecialchars($property['city']); ?></div>
                  </div>
                  <span class="badge bg-primary rounded-pill"><?php echo $property['interest_count']; ?></span>
                </li>
              <?php endforeach; ?>
            <?php else: ?>
              <li class="list-group-item text-center text-muted">No property interest data available.</li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>