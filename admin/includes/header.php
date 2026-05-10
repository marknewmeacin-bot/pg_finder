<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../includes/config.php';
$adminName = $_SESSION['admin_name'] ?? 'Admin';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Admin - PG Finder' : 'Admin - PG Finder'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin/css/admin.css">
  <script>window.ADMIN_BASE_URL = '<?php echo BASE_URL; ?>/admin';</script>
</head>
<body>
<div class="admin-shell d-flex">
  <aside class="admin-sidebar bg-dark text-white">
    <div class="sidebar-brand p-4 text-center border-bottom border-secondary">
      <h4 class="mb-1">PG Finder Admin</h4>
      <small>Control Center</small>
    </div>
    <nav class="nav flex-column p-3">
      <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/admin/dashboard.php"><i class="fas fa-chart-pie me-2"></i>Dashboard</a>
      <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/admin/properties.php"><i class="fas fa-building me-2"></i>Properties</a>
      <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/admin/users.php"><i class="fas fa-users me-2"></i>Users</a>
      <a class="nav-link text-white" href="<?php echo BASE_URL; ?>/admin/interests.php"><i class="fas fa-heart me-2"></i>Interests</a>
      <a class="nav-link text-white mt-3" href="<?php echo BASE_URL; ?>/admin/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
    </nav>
  </aside>
  <main class="admin-main flex-grow-1">
    <header class="admin-topbar d-flex justify-content-between align-items-center px-4 py-3 border-bottom bg-white">
      <div>
        <h1 class="h5 mb-0"><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Admin Dashboard'; ?></h1>
      </div>
      <div class="text-end">
        <span class="text-muted small">Signed in as <?php echo htmlspecialchars($adminName); ?></span>
      </div>
    </header>
    <div class="admin-content p-4">