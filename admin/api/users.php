<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function respond($success, $message) {
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $message]);
    exit;
}

$action = $_POST['action'] ?? null;
$id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
if (!$action || !$id) {
    respond(false, 'Invalid request.');
}
$pdo = getPDO();

if ($action === 'delete') {
    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
    $stmt->execute([$id]);
    respond(true, 'User deleted successfully.');
}

if ($action === 'toggle-block') {
    $stmt = $pdo->prepare('UPDATE users SET is_blocked = NOT is_blocked WHERE id = ?');
    $stmt->execute([$id]);
    respond(true, 'User block status updated.');
}

respond(false, 'Unsupported action.');
