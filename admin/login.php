<?php
$pageTitle = 'Admin Login';
require_once __DIR__ . '/includes/auth.php';
if (admin_is_logged_in()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}
$loginError = $_SESSION['admin_login_error'] ?? null;
unset($_SESSION['admin_login_error']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Login | PG Finder</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/admin/css/admin.css">
</head>
<body class="login-page">
  <div class="login-shell d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card card shadow-sm border-0 p-4 w-100" style="max-width: 420px;">
      <div class="text-center mb-4">
        <h2 class="h4 mb-1">Admin Portal</h2>
        <p class="text-muted">Secure login for PG Finder administrators.</p>
      </div>
      <?php if ($loginError): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($loginError); ?></div>
      <?php endif; ?>
      <form action="<?php echo BASE_URL; ?>/admin/api/auth.php" method="post" novalidate>
        <input type="hidden" name="action" value="login">
        <div class="mb-3">
          <label class="form-label" for="email">Email address</label>
          <input type="email" name="email" id="email" class="form-control" required autocomplete="username">
        </div>
        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
      <div class="mt-4 text-center text-muted small">
        Use the admin credentials from database.sql sample admin account.
      </div>
    </div>
  </div>
</body>
</html>