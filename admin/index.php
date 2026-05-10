<?php
require_once __DIR__ . '/includes/auth.php';
if (admin_is_logged_in()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
} else {
    header('Location: ' . BASE_URL . '/admin/login.php');
}
exit;
