<?php
$metadata = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    try {
        $file = $_FILES['file'];
        
        // Basic validation
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('File upload failed');
        }

        // Extract metadata
        $metadata = [
            'basic' => [
                'Filename' => $file['name'],
                'File Size' => formatBytes($file['size']),
                'MIME Type' => $file['type'],
                'Last Modified' => date('Y-m-d H:i:s', filectime($file['tmp_name']))
            ]
        ];

        // Get image metadata
        if (strpos($file['type'], 'image/') === 0) {
            $exif = @exif_read_data($file['tmp_name']);
            if ($exif) {
                $metadata['image'] = [
                    'Dimensions' => $exif['COMPUTED']['Width'] . ' x ' . $exif['COMPUTED']['Height'],
                    'Camera Make' => $exif['Make'] ?? 'Unknown',
                    'Camera Model' => $exif['Model'] ?? 'Unknown',
                    'Taken Date' => $exif['DateTimeOriginal'] ?? 'Unknown',
                    'Exposure' => $exif['ExposureTime'] ?? 'Unknown',
                    'Aperture' => $exif['COMPUTED']['ApertureFNumber'] ?? 'Unknown',
                    'ISO' => $exif['ISOSpeedRatings'] ?? 'Unknown',
                    'Focal Length' => $exif['FocalLength'] ?? 'Unknown'
                ];
            }
        }

        // Get PDF metadata
        if ($file['type'] === 'application/pdf') {
            $pdf = new Imagick($file['tmp_name']);
            $metadata['document'] = [
                'Pages' => $pdf->getNumberImages(),
                'PDF Version' => $pdf->getImageProperties('pdf:Version'),
                'Author' => $pdf->getImageProperties('pdf:Author') ?? 'Unknown',
                'Creation Date' => $pdf->getImageProperties('pdf:CreationDate') ?? 'Unknown'
            ];
        }

        // Get audio/video metadata
        if (strpos($file['type'], 'audio/') === 0 || strpos($file['type'], 'video/') === 0) {
            $getID3 = new getID3;
            $fileInfo = $getID3->analyze($file['tmp_name']);
            $metadata['media'] = [
                'Duration' => $fileInfo['playtime_string'] ?? 'Unknown',
                'Bitrate' => ($fileInfo['bitrate']/1000) . ' kbps' ?? 'Unknown',
                'Format' => $fileInfo['fileformat'] ?? 'Unknown',
                'Audio Codec' => $fileInfo['audio']['codec'] ?? 'Unknown',
                'Sample Rate' => ($fileInfo['audio']['sample_rate']/1000) . ' kHz' ?? 'Unknown',
                'Channels' => $fileInfo['audio']['channels'] ?? 'Unknown'
            ];
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

function formatBytes($bytes) {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Metadata - Tools</title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
</head>
<body class="bg-gray-50">
    <?php require_once '../nav.php'; ?>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">File Metadata Viewer</h1>

            <div id="app">
                <?php if ($error): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded mb-6">
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <!-- Upload Section -->
                <form method="POST" enctype="multipart/form-data" class="mb-8">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Upload File</label>
                        <input type="file" name="file" required
                               class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-indigo-50 file:text-indigo-600
                                      hover:file:bg-indigo-100">
                    </div>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-300">
                        View Metadata
                    </button>
                </form>

                <!-- Results Section -->
                <?php if ($metadata): ?>
                <div class="space-y-6">
                    <?php foreach ($metadata as $section => $data): ?>
                    <div class="bg-gray-50 rounded p-4">
                        <h2 class="text-lg font-semibold capitalize mb-3"><?php echo htmlspecialchars($section); ?> Information</h2>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($data as $key => $value): ?>
                            <div>
                                <dt class="text-sm font-medium text-gray-500"><?php echo htmlspecialchars($key); ?></dt>
                                <dd class="mt-1 text-sm text-gray-900"><?php echo htmlspecialchars($value); ?></dd>
                            </div>
                            <?php endforeach; ?>
                        </dl>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Usage Tips -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Usage Tips</h2>
                <ul class="space-y-2 text-gray-600">
                    <li>• Supports various file types including images, PDFs, audio, and video files</li>
                    <li>• For images, displays EXIF data if available</li>
                    <li>• For PDFs, shows document properties and page count</li>
                    <li>• For audio/video files, displays duration, bitrate, and format information</li>
                    <li>• Maximum file size limit: <?php echo ini_get('upload_max_filesize'); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <?php require_once '../footer.php'; ?>
</body>
</html>