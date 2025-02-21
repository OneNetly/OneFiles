<?php
require_once 'config.php';

$fileId = $_GET['id'] ?? null;
$fileInfo = null;

if ($fileId) {
    // Get file info from API using list action
    $ch = curl_init(API_URL . '?action=list');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'X-Api-Key: ' . API_KEY,
            'Accept: application/json'
        ]
    ]);
    
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    
    if ($data['success'] && !empty($data['data']['files'])) {
        // Find the specific file in the list
        foreach ($data['data']['files'] as $file) {
            if ($file['file_id'] === $fileId) {
                $fileInfo = $file;
                break;
            }
        }
    }
    curl_close($ch);
}

function getFileNameExcerpt($fileName, $maxLength = 30) {
    if (strlen($fileName) <= $maxLength) {
        return $fileName;
    }
    
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);
    $nameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
    
    $maxBaseLength = $maxLength - strlen($extension) - 4; // -4 for "..." and "."
    $truncatedName = substr($nameWithoutExt, 0, $maxBaseLength) . '...';
    
    return $truncatedName . '.' . $extension;
}

// Add SEO meta data
$fileName = $fileInfo ? htmlspecialchars($fileInfo['file_name']) : 'File';
$fileSize = $fileInfo ? number_format($fileInfo['size'] / 1024 / 1024, 2) . 'MB' : '';

// Enhanced meta data
$meta = [
    'title' => $fileInfo ? "Download {$fileName} ({$fileSize}) - " . SITE_NAME : 'Download File - ' . SITE_NAME,
    'description' => $fileInfo 
        ? "Download {$fileName} securely from " . SITE_NAME . ". File size: {$fileSize}. Fast and reliable file sharing service."
        : 'Download your file securely from ' . SITE_NAME . '. Fast and reliable file sharing service.',
    'keywords' => 'download file, file sharing, secure download, ' . ($fileInfo ? strtolower(pathinfo($fileInfo['file_name'], PATHINFO_EXTENSION)) : '') . ' file',
    'canonical' => BASE_URL . '/download.php?id=' . $fileId,
    'type' => 'article',
    'modified_time' => $fileInfo ? date('c', strtotime($fileInfo['created_at'])) : date('c'),
    'robots' => $fileInfo ? 'index, follow' : 'noindex, follow',
    'og_image' => $fileInfo && in_array(strtolower(pathinfo($fileInfo['file_name'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp']) 
        ? BASE_URL . '/dl.php?id=' . $fileId 
        : BASE_URL . '/assets/images/default-file.png'
];

// Add structured data for breadcrumbs and download
$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => BASE_URL
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Download',
            'item' => $meta['canonical']
        ]
    ]
];

if ($fileInfo) {
    $downloadStructuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'DownloadAction',
        'name' => $fileInfo['file_name'],
        'encodingFormat' => pathinfo($fileInfo['file_name'], PATHINFO_EXTENSION),
        'contentSize' => $fileInfo['size'],
        'datePublished' => date('c', strtotime($fileInfo['created_at'])),
        'url' => $meta['canonical']
    ];
}

$pageTitle = $meta['title'];
include 'includes/header.php';
?>

<!-- Add structured data -->
<script type="application/ld+json"><?php echo json_encode($structuredData); ?></script>
<?php if ($fileInfo): ?>
<script type="application/ld+json"><?php echo json_encode($downloadStructuredData); ?></script>
<?php endif; ?>

<!-- Add breadcrumbs navigation -->
<nav class="bg-gray-100 py-2 px-4" aria-label="Breadcrumb">
    <ol class="flex text-sm">
        <li>
            <a href="<?php echo BASE_URL; ?>" class="text-gray-600 hover:text-gray-900">Home</a>
            <span class="mx-2 text-gray-500">/</span>
        </li>
        <li class="text-gray-900">Download</li>
    </ol>
</nav>

<main class="min-h-screen flex items-center justify-center p-4">
    <?php if ($fileInfo): ?>
        <article class="bg-white rounded-lg shadow-sm p-8 max-w-md w-full">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Download File</h1>
                <p class="text-gray-600 mt-2" title="<?php echo htmlspecialchars($fileInfo['file_name']); ?>">
                    <?php echo htmlspecialchars(getFileNameExcerpt($fileInfo['file_name'])); ?>
                </p>
            </div>
            
            <?php
            // Check if file is an image
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $extension = strtolower(pathinfo($fileInfo['file_name'], PATHINFO_EXTENSION));
            if (in_array($extension, $imageExtensions)):
            ?>
                <div class="mb-6">
                    <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden bg-gray-100">
                        <img src="dl.php?id=<?php echo urlencode($fileId); ?>" 
                             alt="<?php echo htmlspecialchars($fileInfo['file_name']); ?>"
                             class="object-contain w-full h-full"
                             loading="lazy">
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Add Copy Link Section -->
            <div class="mb-6">
                <div class="flex rounded-md shadow-sm">
                    <input type="text" 
                           value="<?php echo BASE_URL . '/download.php?id=' . $fileId; ?>" 
                           class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 text-sm" 
                           readonly 
                           id="shareLink">
                    <button onclick="copyToClipboard('shareLink')" 
                            class="inline-flex items-center px-4 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 transition-colors">
                        <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                            <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                        </svg>
                        Copy Link
                    </button>
                </div>
            </div>

            <!-- Add Share Buttons -->
            <div class="border-t border-gray-200 pt-4 mb-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">Share this file</h3>
                <div class="grid grid-cols-2 gap-2">
                    <a href="https://wa.me/?text=<?php echo urlencode($fileInfo['file_name'] . ' - ' . BASE_URL . '/download.php?id=' . $fileId); ?>" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 text-sm font-medium text-gray-700 transition-colors">
                        <svg class="h-5 w-5 mr-1.5 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        </svg>
                        WhatsApp
                    </a>
                    
                    <a href="https://t.me/share/url?url=<?php echo urlencode(BASE_URL . '/download.php?id=' . $fileId); ?>&text=<?php echo urlencode($fileInfo['file_name']); ?>" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 text-sm font-medium text-gray-700 transition-colors">
                        <svg class="h-5 w-5 mr-1.5 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18 1.897-.962 6.502-1.359 8.627-.168.9-.5 1.201-.82 1.23-.697.064-1.226-.461-1.901-.903-1.056-.692-1.653-1.123-2.678-1.799-1.185-.781-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.479.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.756-.244-1.359-.374-1.307-.789.027-.216.324-.437.892-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635.099-.002.321.023.465.178.119.13.154.306.165.433.032.337-.116.672-.114.672z"/>
                        </svg>
                        Telegram
                    </a>

                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(BASE_URL . '/download.php?id=' . $fileId); ?>" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 text-sm font-medium text-gray-700 transition-colors">
                        <svg class="h-5 w-5 mr-1.5 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                        Facebook
                    </a>

                    <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($fileInfo['file_name']); ?>&url=<?php echo urlencode(BASE_URL . '/download.php?id=' . $fileId); ?>" 
                       target="_blank"
                       class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md bg-white hover:bg-gray-50 text-sm font-medium text-gray-700 transition-colors">
                        <svg class="h-5 w-5 mr-1.5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                        Twitter
                    </a>
                </div>
            </div>

            <div class="space-y-4 mb-8">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Size:</span>
                    <span class="font-medium <?php echo $fileInfo['size'] > MAX_FILE_SIZE ? 'text-red-600' : ''; ?>">
                        <?php echo number_format($fileInfo['size'] / 1024 / 1024, 2); ?> MB
                        <?php if ($fileInfo['size'] > MAX_FILE_SIZE): ?>
                            <span class="text-sm">(Exceeds 100MB limit)</span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Upload Date:</span>
                    <span class="font-medium"><?php echo $fileInfo['created_at']; ?></span>
                </div>
            </div>
            
            <div class="flex justify-center">
                <a href="https://onenetly.com/wait.php?link=<?php echo BASE_URL . '/dl.php?id=' . $fileId; ?>" 
                   class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Download Now
                </a>
            </div>
        </article>
    <?php else: ?>
        <article class="bg-white rounded-lg shadow-sm p-8 max-w-md w-full">
            <div class="text-center text-red-600">
                <h1 class="text-2xl font-bold">File Not Found</h1>
                <p class="mt-2">The requested file could not be found or has been removed.</p>
            </div>
        </article>
    <?php endif; ?>
</main>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    document.execCommand('copy');
    
    // Show feedback tooltip
    const tooltip = document.createElement('div');
    tooltip.className = 'fixed top-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-lg text-sm transform transition-all duration-300 opacity-0 translate-y-2';
    tooltip.textContent = 'Link copied to clipboard!';
    document.body.appendChild(tooltip);
    
    // Animate in
    requestAnimationFrame(() => {
        tooltip.classList.remove('opacity-0', 'translate-y-2');
    });
    
    // Remove after delay
    setTimeout(() => {
        tooltip.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => tooltip.remove(), 300);
    }, 2000);
}
</script>

<?php include 'includes/footer.php'; ?>
</html>