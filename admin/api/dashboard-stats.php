<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

$pdo = getPDO();
$stats = [
    'users' => (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'properties' => (int)$pdo->query('SELECT COUNT(*) FROM properties')->fetchColumn(),
    'interests' => (int)$pdo->query('SELECT COUNT(*) FROM interested_users')->fetchColumn(),
    'cities' => (int)$pdo->query('SELECT COUNT(DISTINCT city) FROM properties')->fetchColumn(),
];

echo json_encode(['success' => true, 'stats' => $stats]);
