<?php
// config.php - central configuration for database and app settings

define('DB_HOST', 'localhost');
define('DB_NAME', 'pgfinder');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_TITLE', 'PG Finder');
define('BASE_URL', '/PGFinder'); // adjust if deployed to a subfolder

// Common content security headers to help mitigate XSS
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("Referrer-Policy: strict-origin-when-cross-origin");
