<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

action:
$action = $_POST['action'] ?? null;
if ($action === 'login') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        $_SESSION['admin_login_error'] = 'Email and password are required.';
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }

    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, name, email, password FROM admin WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();

    if (!$admin || !password_verify($password, $admin['password'])) {
        $_SESSION['admin_login_error'] = 'Invalid admin credentials.';
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_name'] = $admin['name'];
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

http_response_code(400);
echo 'Bad request';
