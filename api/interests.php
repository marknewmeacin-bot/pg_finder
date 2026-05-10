<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
session_start();

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Authentication required']);
    exit;
}

$action = $_POST['action'] ?? '';
$propertyId = filter_var($_POST['property_id'] ?? '', FILTER_VALIDATE_INT);
$userId = $_SESSION['user_id'];
$pdo = getPDO();

if ($action === 'toggle' && $propertyId) {
    $exists = $pdo->prepare('SELECT 1 FROM interested_users WHERE user_id = ? AND property_id = ?');
    $exists->execute([$userId, $propertyId]);
    if ($exists->fetch()) {
        $delete = $pdo->prepare('DELETE FROM interested_users WHERE user_id = ? AND property_id = ?');
        $delete->execute([$userId, $propertyId]);
        echo json_encode(['success' => true, 'status' => 'removed', 'message' => 'Removed from interested list']);
    } else {
        $insert = $pdo->prepare('INSERT INTO interested_users (user_id, property_id) VALUES (?, ?)');
        $insert->execute([$userId, $propertyId]);
        echo json_encode(['success' => true, 'status' => 'added', 'message' => 'Added to interested list']);
    }
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Invalid request']);
