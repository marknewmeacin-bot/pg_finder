<?php
$pageTitle = 'Sign Up';
require_once __DIR__ . '/includes/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$registerError = $_SESSION['register_error'] ?? null;
unset($_SESSION['register_error']);
include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
      <div class="card shadow-sm border-0">
        <div class="card-body p-4">
          <h3 class="mb-3">Create Your Account</h3>
          <?php if ($registerError): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($registerError); ?></div>
          <?php endif; ?>
          <form id="signupForm" action="api/auth.php" method="post" novalidate>
            <input type="hidden" name="action" value="register">
            <div class="mb-3">
              <label for="name" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
              <label for="phone" class="form-label">Phone</label>
              <input type="tel" class="form-control" id="phone" name="phone" placeholder="Optional">
            </div>
            <div class="mb-3">
              <label for="password" class="form-label">Password</label>
              <input type="password" class="form-control" id="password" name="password" required minlength="6">
            </div>
            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm Password</label>
              <input type="password" class="form-control" id="confirm_password" name="confirm_password" required minlength="6">
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
          </form>
          <div class="mt-3 text-center">
            <small>Already registered? <a href="login.php">Login here</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>