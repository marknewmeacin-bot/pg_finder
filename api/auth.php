<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
session_start();

$action = $_POST['action'] ?? '';
$response = ['success' => false, 'message' => 'Invalid request'];

if ($action === 'register') {
    $name = trim($_POST['name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        $response['message'] = 'Please fill all required fields.';
    } elseif ($password !== $confirm) {
        $response['message'] = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $response['message'] = 'Password must be at least 6 characters.';
    } else {
        $pdo = getPDO();
        $check = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) {
            $response['message'] = 'Email already registered.';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (name, email, password, phone) VALUES (?, ?, ?, ?)');
            $stmt->execute([$name, $email, $hashed, $phone]);
            $response = ['success' => true, 'message' => 'Registration successful. Redirecting...', 'redirect' => '/PGFinder/login.php'];
        }
    }
} elseif ($action === 'login') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!$email || !$password) {
        $response['message'] = 'Email and password are required.';
    } else {
        $pdo = getPDO();
        $stmt = $pdo->prepare('SELECT id, name, password FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $response = ['success' => true, 'message' => 'Login successful.', 'redirect' => '/PGFinder/index.php'];
        } else {
            $response['message'] = 'Invalid email or password.';
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode($response);
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
