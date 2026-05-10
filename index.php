<?php
$pageTitle = 'Browse PG Properties';
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/header.php';
?>
<div class="container">
  <section class="hero-section py-5 text-center text-white bg-primary rounded-4 shadow-sm">
    <div class="row align-items-center">
      <div class="col-lg-7">
        <h1 class="display-5 fw-bold">Find Your Ideal Student PG</h1>
        <p class="lead">Search verified PGs, explore amenities, shortlist favorites, and book with confidence.</p>
        <div class="d-flex justify-content-center gap-2 flex-wrap">
          <a href="#listing" class="btn btn-light btn-lg">Browse Properties</a>
          <a href="login.php" class="btn btn-outline-light btn-lg">Sign in</a>
        </div>
      </div>
      <div class="col-lg-5 mt-4 mt-lg-0">
        <img src="images/pg1.jpg" alt="Student PG" class="img-fluid rounded-4 shadow-lg" onerror="this.onerror=null;this.src='https://via.placeholder.com/800x500?text=PG+Finder'">
      </div>
    </div>
  </section>

  <section id="listing" class="my-5">
    <div id="reactPropertyApp"></div>
  </section>
</div>

<script src="https://unpkg.com/react@18/umd/react.development.js"></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.development.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
<script type="text/babel" src="react-app/propertyListing.jsx"></script>

<?php include __DIR__ . '/includes/footer.php'; ?>