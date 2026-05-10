<?php
/**
 * Quick verification script - Run this to verify the complete setup
 * Access: /verify-setup.php
 */

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';

$checks = [
    'database' => false,
    'properties' => false,
    'property_images' => false,
    'image_files' => false,
    'image_paths' => false
];

$messages = [];

try {
    // Check database connection
    $pdo = getPDO();
    $checks['database'] = true;
    $messages[] = "✓ Database connection successful";
} catch (Exception $e) {
    $messages[] = "✗ Database connection failed: " . $e->getMessage();
    die(json_encode(['status' => 'error', 'checks' => $checks, 'messages' => $messages]));
}

try {
    // Check properties table
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM properties');
    $count = $stmt->fetch()['count'];
    $checks['properties'] = $count > 0;
    $messages[] = $checks['properties'] ? "✓ Properties found: $count" : "✗ No properties in database";
} catch (Exception $e) {
    $messages[] = "✗ Error checking properties: " . $e->getMessage();
}

try {
    // Check property_images table
    $stmt = $pdo->query('SELECT COUNT(*) as count FROM property_images');
    $count = $stmt->fetch()['count'];
    $checks['property_images'] = $count > 0;
    $messages[] = $checks['property_images'] ? "✓ Property images found: $count" : "✗ No images linked in database";
} catch (Exception $e) {
    $messages[] = "✗ Error checking property_images: " . $e->getMessage();
}

// Check image files
$imagesDir = __DIR__ . '/images';
$requiredImages = ['pg1.jpg', 'pg2.jpg', 'pg3.jpg', 'pg4.jpg', 'pg5.jpg', 'pg6.jpg'];
$missingFiles = [];

foreach ($requiredImages as $img) {
    if (!file_exists($imagesDir . '/' . $img)) {
        $missingFiles[] = $img;
    }
}

$checks['image_files'] = count($missingFiles) === 0;
if ($checks['image_files']) {
    $messages[] = "✓ All image files present in /images folder";
} else {
    $messages[] = "✗ Missing image files: " . implode(', ', $missingFiles);
}

// Check if image paths in database match actual files
try {
    $stmt = $pdo->query('SELECT DISTINCT image FROM property_images WHERE image IS NOT NULL');
    $dbPaths = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $pathErrors = [];
    foreach ($dbPaths as $path) {
        $fullPath = __DIR__ . '/' . ltrim($path, '/');
        if (!file_exists($fullPath)) {
            $pathErrors[] = $path;
        }
    }
    
    $checks['image_paths'] = count($pathErrors) === 0;
    if ($checks['image_paths']) {
        $messages[] = "✓ All image paths in database point to existing files";
    } else {
        $messages[] = "✗ Some image paths don't exist: " . implode(', ', $pathErrors);
    }
} catch (Exception $e) {
    $messages[] = "⚠ Warning: Could not verify image paths: " . $e->getMessage();
}

// Test API
try {
    $url = BASE_URL . '/api/properties.php?action=list';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if ($data['success'] && !empty($data['data'][0]['image'])) {
            $messages[] = "✓ API returns images with proper paths";
        } else {
            $messages[] = "⚠ API working but image field may be empty";
        }
    } else {
        $messages[] = "⚠ API returned HTTP $httpCode";
    }
} catch (Exception $e) {
    $messages[] = "⚠ Could not test API: " . $e->getMessage();
}

$allPassed = array_reduce($checks, function($carry, $item) {
    return $carry && $item;
}, true);

// Return appropriate response
header('Content-Type: application/json');
echo json_encode([
    'status' => $allPassed ? 'success' : 'failed',
    'summary' => [
        'database' => $checks['database'] ? 'OK' : 'FAIL',
        'properties' => $checks['properties'] ? 'OK' : 'FAIL',
        'images_table' => $checks['property_images'] ? 'OK' : 'FAIL',
        'image_files' => $checks['image_files'] ? 'OK' : 'FAIL',
        'image_paths' => $checks['image_paths'] ? 'OK' : 'FAIL'
    ],
    'messages' => $messages,
    'recommendation' => $allPassed ? 'All checks passed! Images should display correctly.' : 'Some checks failed. Follow the messages above to fix the issues.'
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
