<?php
// upload.php - Einfacher Screenshot-Receiver

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'screenshots/';
    
    // Ordner erstellen falls nicht vorhanden
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    if (isset($_FILES['screenshot'])) {
        // Multipart Upload
        $filename = $uploadDir . 'screenshot_' . date('Y-m-d_H-i-s') . '.jpg';
        
        if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $filename)) {
            echo json_encode([
                'success' => true,
                'message' => 'Screenshot empfangen',
                'filename' => basename($filename),
                'size' => filesize($filename)
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Upload fehlgeschlagen']);
        }
    } else {
        // JSON/Base64 Upload
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (isset($input['image'])) {
            $imageData = base64_decode($input['image']);
            $filename = $uploadDir . 'screenshot_' . $input['timestamp'] . '.jpg';
            
            if (file_put_contents($filename, $imageData)) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Screenshot empfangen (Base64)',
                    'filename' => basename($filename),
                    'size' => strlen($imageData)
                ]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Speichern fehlgeschlagen']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Keine Bilddaten empfangen']);
        }
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Nur POST erlaubt']);
}
?>
