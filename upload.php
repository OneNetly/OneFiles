<?php
require_once 'config.php';

header('Content-Type: application/json');

// Handle CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['size'] > MAX_FILE_SIZE) {
        echo json_encode([
            'success' => false,
            'error' => 'File size exceeds the limit of 100MB'
        ]);
        exit;
    }

    $ch = curl_init(API_URL . '?action=upload');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'X-Api-Key: ' . API_KEY,
            'Accept: application/json'
        ],
        CURLOPT_POSTFIELDS => [
            'file' => new CURLFile($_FILES['file']['tmp_name'], $_FILES['file']['type'], $_FILES['file']['name'])
        ]
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode === 200) {
        echo $response;
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Upload failed. Please try again.'
        ]);
    }
    exit;
}

echo json_encode([
    'success' => false,
    'error' => 'Invalid request'
]);
