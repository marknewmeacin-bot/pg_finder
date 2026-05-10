<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
session_start();

action:
$action = $_GET['action'] ?? 'list';
$pdo = getPDO();

if ($action === 'list') {
    $city = trim($_GET['city'] ?? '');
    $gender = trim($_GET['gender'] ?? '');
    $maxBudget = filter_var($_GET['maxBudget'] ?? '', FILTER_VALIDATE_FLOAT);

    $query = 'SELECT p.id, p.name, p.city, p.price, p.gender, p.rating, p.description, (SELECT image FROM property_images WHERE property_id = p.id LIMIT 1) as image FROM properties p WHERE 1=1';
    $params = [];
    if ($city) {
        $query .= ' AND city = ?';
        $params[] = $city;
    }
    if ($gender) {
        $query .= ' AND gender = ?';
        $params[] = $gender;
    }
    if ($maxBudget !== false && $maxBudget !== null) {
        $query .= ' AND price <= ?';
        $params[] = $maxBudget;
    }
    $query .= ' ORDER BY p.rating DESC, p.price ASC';
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $properties = $stmt->fetchAll();
    
    // Ensure image paths are absolute URLs
    foreach ($properties as &$property) {
        if (!empty($property['image'])) {
            $image = trim($property['image']);
            if (strpos($image, 'http://') === 0 || strpos($image, 'https://') === 0) {
                $property['image'] = $image;
            } elseif (strpos($image, '/') === false) {
                $property['image'] = BASE_URL . '/images/' . $image;
            } else {
                $property['image'] = BASE_URL . '/' . ltrim($image, '/');
            }
        } else {
            $property['image'] = BASE_URL . '/images/pg1.jpg';
        }
    }
    
    echo json_encode(['success' => true, 'data' => $properties]);
    exit;
}

if ($action === 'detail') {
    $id = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Invalid property id']);
        exit;
    }
    $stmt = $pdo->prepare('SELECT * FROM properties WHERE id = ?');
    $stmt->execute([$id]);
    $property = $stmt->fetch();
    if (!$property) {
        echo json_encode(['success' => false, 'message' => 'Property not found']);
        exit;
    }
    $amenitiesStmt = $pdo->prepare('SELECT a.name FROM amenities a JOIN property_amenities pa ON a.id = pa.amenity_id WHERE pa.property_id = ?');
    $amenitiesStmt->execute([$id]);
    $property['amenities'] = $amenitiesStmt->fetchAll(PDO::FETCH_COLUMN);
    echo json_encode(['success' => true, 'data' => $property]);
    exit;
}

http_response_code(400);
echo json_encode(['success' => false, 'message' => 'Action not found']);
