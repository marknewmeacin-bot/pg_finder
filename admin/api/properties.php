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

function sanitize($value) {
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

$action = $_POST['action'] ?? null;
if (!$action) {
    respond(false, 'Action is required.');
}

$pdo = getPDO();

if ($action === 'add' || $action === 'edit') {
    $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
    $name = sanitize($_POST['name'] ?? '');
    $city = sanitize($_POST['city'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $price = filter_var($_POST['price'] ?? '', FILTER_VALIDATE_FLOAT);
    $gender = sanitize($_POST['gender'] ?? 'Coed');
    $rating = filter_var($_POST['rating'] ?? '', FILTER_VALIDATE_FLOAT);
    $description = sanitize($_POST['description'] ?? '');
    $amenities = $_POST['amenities'] ?? [];
    if (!$name || !$city || !$address || $price === false || $rating === false || !$description) {
        respond(false, 'Please fill all required fields.');
    }
    if (!in_array($gender, ['Male', 'Female', 'Coed'], true)) {
        $gender = 'Coed';
    }
    if ($rating < 1) $rating = 1;
    if ($rating > 5) $rating = 5;

    if ($action === 'add') {
        $stmt = $pdo->prepare('INSERT INTO properties (name, city, address, price, gender, rating, description) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$name, $city, $address, $price, $gender, $rating, $description]);
        $propertyId = $pdo->lastInsertId();
    } else {
        if (!$id) {
            respond(false, 'Invalid property ID.');
        }
        $stmt = $pdo->prepare('UPDATE properties SET name = ?, city = ?, address = ?, price = ?, gender = ?, rating = ?, description = ? WHERE id = ?');
        $stmt->execute([$name, $city, $address, $price, $gender, $rating, $description, $id]);
        $propertyId = $id;
        $pdo->prepare('DELETE FROM property_amenities WHERE property_id = ?')->execute([$propertyId]);
    }

    if (!empty($amenities) && is_array($amenities)) {
        $stmt = $pdo->prepare('INSERT IGNORE INTO property_amenities (property_id, amenity_id) VALUES (?, ?)');
        foreach ($amenities as $amenityId) {
            $amenityId = filter_var($amenityId, FILTER_VALIDATE_INT);
            if ($amenityId) {
                $stmt->execute([$propertyId, $amenityId]);
            }
        }
    }

    $uploadPath = __DIR__ . '/../../images';
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $savedImage = false;
    if (!empty($_FILES['image']['tmp_name'])) {
        $file = $_FILES['image'];
        if ($file['error'] === UPLOAD_ERR_OK && in_array(mime_content_type($file['tmp_name']), $allowedTypes, true)) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'property_' . $propertyId . '_' . time() . '.' . $ext;
            move_uploaded_file($file['tmp_name'], $uploadPath . '/' . $filename);
            $pdo->prepare('INSERT INTO property_images (property_id, image) VALUES (?, ?)')->execute([$propertyId, $filename]);
            $savedImage = true;
        }
    }
    if (!empty($_FILES['additional_images']['tmp_name'])) {
        foreach ($_FILES['additional_images']['tmp_name'] as $index => $tmpName) {
            if (!$tmpName) continue;
            $type = mime_content_type($tmpName);
            if (!in_array($type, $allowedTypes, true)) {
                continue;
            }
            $origName = $_FILES['additional_images']['name'][$index];
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            $filename = 'property_' . $propertyId . '_extra_' . time() . '_' . $index . '.' . $ext;
            move_uploaded_file($tmpName, $uploadPath . '/' . $filename);
            $pdo->prepare('INSERT INTO property_images (property_id, image) VALUES (?, ?)')->execute([$propertyId, $filename]);
        }
    }

    respond(true, $action === 'add' ? 'Property added successfully.' : 'Property updated successfully.');
}

if ($action === 'delete') {
    $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
    if (!$id) {
        respond(false, 'Invalid property ID.');
    }
    $stmt = $pdo->prepare('DELETE FROM properties WHERE id = ?');
    $stmt->execute([$id]);
    respond(true, 'Property deleted successfully.');
}

respond(false, 'Unsupported action.');
