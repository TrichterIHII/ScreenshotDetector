<?php
// upload.php - neben die HTML-Datei legen
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = 'screenshots/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    
    if (isset($_FILES['screenshot'])) {
        $filename = $uploadDir . 'screenshot_' . date('Y-m-d_H-i-s') . '.jpg';
        move_uploaded_file($_FILES['screenshot']['tmp_name'], $filename);
        echo json_encode(['success' => true]);
    }
}
?>
