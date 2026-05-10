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
$userId = filter_var($_POST['user_id'] ?? '', FILTER_VALIDATE_INT);
$propertyId = filter_var($_POST['property_id'] ?? '', FILTER_VALIDATE_INT);
if ($action !== 'delete' || !$userId || !$propertyId) {
    respond(false, 'Invalid request.');
}
$pdo = getPDO();
$stmt = $pdo->prepare('DELETE FROM interested_users WHERE user_id = ? AND property_id = ?');
$stmt->execute([$userId, $propertyId]);
respond(true, 'Interest record removed successfully.');
