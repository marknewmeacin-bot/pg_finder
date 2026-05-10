<?php
$pageTitle = 'User Management';
require_once __DIR__ . '/includes/auth.php';
require_admin();
$pdo = getPDO();
$search = trim($_GET['search'] ?? '');
$condition = '';
$params = [];
if ($search !== '') {
    $condition = 'WHERE name LIKE ? OR email LIKE ?';
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}
$query = "SELECT id, name, email, phone, created_at, is_blocked FROM users $condition ORDER BY id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll();
include __DIR__ . '/includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <p class="text-muted mb-1">Search, block or remove students from the platform.</p>
    <h3 class="mb-0">Users</h3>
  </div>
  <form class="d-flex" method="get" style="max-width: 320px;">
    <input type="search" name="search" id="userSearch" class="form-control me-2" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit" class="btn btn-outline-primary">Search</button>
  </form>
</div>
<div class="card dashboard-card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Joined</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($users): ?>
            <?php foreach ($users as $user): ?>
              <tr class="user-row">
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                <td>
                  <?php if ($user['is_blocked']): ?>
                    <span class="badge bg-danger">Blocked</span>
                  <?php else: ?>
                    <span class="badge bg-success">Active</span>
                  <?php endif; ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-outline-secondary delete-action" data-url="<?php echo BASE_URL; ?>/admin/api/users.php" data-action="toggle-block" data-id="<?php echo $user['id']; ?>" data-confirm="Toggle user block status?">Toggle Block</button>
                  <button class="btn btn-sm btn-outline-danger delete-action" data-url="<?php echo BASE_URL; ?>/admin/api/users.php" data-action="delete" data-id="<?php echo $user['id']; ?>" data-confirm="Delete this user permanently?">Delete</button>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center text-muted">No users found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>