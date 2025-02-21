<?php
require_once 'config.php';  // Add this line at the top
set_time_limit(0);
ini_set('memory_limit', '1024M');

function getMimeType($filename) {
    $mimeTypes = [
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'mp4' => 'video/mp4',
        'mp3' => 'audio/mpeg',
        'wav' => 'audio/wav',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'pdf' => 'application/pdf',
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'xls' => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        '7z' => 'application/x-7z-compressed'
    ];

    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return isset($mimeTypes[$ext]) ? $mimeTypes[$ext] : 'application/octet-stream';
}

function proxyDownload($url) {
    $ch = curl_init();
    
    // First request to get headers
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true,
        CURLOPT_NOBODY => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 10
    ]);
    
    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $headers = substr($response, 0, $headerSize);
    
    // Parse filename
    $fileName = '';
    if (preg_match('/Content-Disposition:.*filename=[\'"]*([^\"\']+)/i', $headers, $matches)) {
        $fileName = urldecode($matches[1]);
    } else {
        $fileName = basename(parse_url(curl_getinfo($ch, CURLINFO_EFFECTIVE_URL), PHP_URL_PATH));
    }
    
    // Basic filename sanitization
    $fileName = preg_replace('/[^a-zA-Z0-9\-\.\_\(\)\[\] ]/', '', $fileName);
    
    // Get content type
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    if (!$contentType || $contentType == 'application/octet-stream') {
        $contentType = getMimeType($fileName);
    }
    
    // Get file size
    $fileSize = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    
    // Set headers for download
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    if ($fileSize > 0) {
        header('Content-Length: ' . $fileSize);
    }
    header('Accept-Ranges: bytes');
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Second request to stream file
    curl_setopt_array($ch, [
        CURLOPT_NOBODY => false,
        CURLOPT_HEADER => false,
        CURLOPT_RETURNTRANSFER => false,
        CURLOPT_WRITEFUNCTION => function($curl, $data) {
            echo $data;
            flush();
            ob_flush();
            return strlen($data);
        }
    ]);
    
    curl_exec($ch);
    
    if (curl_errno($ch)) {
        header("HTTP/1.0 500 Internal Server Error");
        exit('Download failed: ' . curl_error($ch));
    }
    
    curl_close($ch);
    exit;
}

// Handle the request
if (isset($_GET['url'])) {
    $url = base64_decode($_GET['url']);
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        proxyDownload($url);
    } else {
        header("HTTP/1.0 400 Bad Request");
        exit('Invalid URL');
    }
} else if (isset($_GET['id'])) {
    $fileId = $_GET['id'];
    $url = "https://onenetly.com/download/{$fileId}/download";
    proxyDownload($url);
} else {
    header("HTTP/1.0 400 Bad Request");
    exit('No URL or ID provided');
}
