<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$loggedIn = !empty($_SESSION['user_id']);
$userName = $loggedIn ? htmlspecialchars($_SESSION['user_name']) : '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | ' . SITE_TITLE : SITE_TITLE; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>/css/styles.css">
  <script>window.APP_BASE_URL = '<?php echo BASE_URL; ?>';</script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>/index.php"><img src="<?php echo BASE_URL; ?>/images/pg1.jpg" alt="PG Finder" class="me-2" style="height: 40px; width: auto;">PG Finder</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav ms-auto align-items-center">
        <li class="nav-item"><a class="nav-link" href="index.php">Properties</a></li>
        <?php if ($loggedIn): ?>
      
        <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/shortlist.php"><i class="fas fa-heart text-danger"></i> Interested</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/logout.php">Logout</a></li>
          <li class="nav-item"><span class="nav-link disabled">Hi, <?php echo $userName; ?></span></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/login.php">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>/signup.php">Sign Up</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="py-5">