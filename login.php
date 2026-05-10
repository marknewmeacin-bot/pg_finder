<?php
$pageTitle = 'Login';
require_once __DIR__ . '/includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$loginError = $_SESSION['login_error'] ?? null;
unset($_SESSION['login_error']);
include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h3 class="mb-3">Student Login</h3>
          <?php if ($loginError): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($loginError); ?></div>
          <?php endif; ?>
          <form id="loginForm" action="api/auth.php" method="post" novalidate>
            <input type="hidden" name="action" value="login">
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
          <div class="mt-3 text-center">
            <small>New here? <a href="signup.php">Create an account</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>