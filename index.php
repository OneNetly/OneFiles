<?php
require_once 'config.php';

// SEO Meta Data
$meta = [
    'title' => 'Free File Sharing & Storage - ' . SITE_NAME,
    'description' => 'Upload and share files securely with ' . SITE_NAME . '. Fast, reliable, and free file hosting service. Share large files easily with anyone.',
    'keywords' => 'file sharing, file upload, file hosting, share files, file storage, free file sharing',
    'canonical' => BASE_URL
];

// Handle file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    if ($_FILES['file']['size'] > MAX_FILE_SIZE) {
        $uploadResponse = [
            'success' => false,
            'error' => 'File size exceeds the limit of 100MB'
        ];
    } else {
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
        curl_close($ch);
        $uploadResponse = json_decode($response, true);
    }
}

$pageTitle = $meta['title'];
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-4 sm:text-5xl">
            Share Files Securely & Easily
        </h1>
        <p class="text-xl text-gray-600 max-w-2xl mx-auto">
            Upload your files and get shareable links instantly. No registration required.
            Free, secure, and reliable file sharing solution.
        </p>
    </div>

    <!-- Upload Section -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-12">
        <div class="max-w-2xl mx-auto">
            <!-- Features List -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="text-gray-600">Up to 100MB file size</span>
                </div>
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span class="text-gray-600">Secure encryption</span>
                </div>
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-gray-600">180 days storage from last download</span>
                </div>
                <div class="flex items-center space-x-3">
                    <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                    </svg>
                    <span class="text-gray-600">No registration</span>
                </div>
            </div>

            <!-- Upload Form -->
            <form id="uploadForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div id="dropZone" 
                     class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center transition-all"
                     ondrop="handleDrop(event)" 
                     ondragover="handleDragOver(event)"
                     ondragleave="handleDragLeave(event)">
                    <input type="file" name="file" id="fileInput" class="hidden" onchange="handleFileSelect(this)">
                    <div class="flex flex-col items-center cursor-pointer" onclick="document.getElementById('fileInput').click()">
                        <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">Drag and drop your file here or click to browse</p>
                        <p class="text-xs text-gray-500 mt-1">Max file size: 100MB</p>
                    </div>
                </div>

                <!-- Progress Bar (Hidden by default) -->
                <div id="uploadProgress" class="hidden transition-opacity duration-300 ease-out opacity-100">
                    <div class="flex items-center justify-between mb-1">
                        <span id="fileName" class="text-sm font-medium text-gray-900"></span>
                        <span id="progressText" class="text-sm font-medium text-gray-500">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div id="progressBar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <button type="button" 
                            id="cancelUpload" 
                            class="mt-2 text-sm text-red-600 hover:text-red-800 hidden">
                        Cancel Upload
                    </button>
                </div>

                <button type="submit" 
                        id="uploadButton"
                        class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Upload File
                </button>
            </form>

            <!-- Upload Response -->
            <div id="uploadResponse" class="mt-8 hidden"></div>

            <?php if (isset($uploadResponse)): ?>
                <div class="mt-8">
                    <?php if ($uploadResponse['success']): ?>
                        <div class="bg-white border rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900">Upload Complete!</h3>
                                </div>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Download Page</label>
                                    <div class="flex mt-1 rounded-md shadow-sm">
                                        <input type="text" 
                                               value="<?php echo BASE_URL . '/download.php?id=' . $uploadResponse['data']['file_id']; ?>" 
                                               class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 text-sm" 
                                               readonly 
                                               id="downloadLink">
                                        <button onclick="copyToClipboard('downloadLink')" 
                                                class="inline-flex items-center px-4 py-2 border border-l-0 border-r-0 border-gray-300 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 transition-colors">
                                            <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                                <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                                            </svg>
                                            Copy
                                        </button>
                                        <a href="<?php echo BASE_URL . '/download.php?id=' . $uploadResponse['data']['file_id']; ?>" 
                                           target="_blank"
                                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 transition-colors">
                                            <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Upload Failed</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p><?php echo $uploadResponse['error']; ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Information Sections -->
    <div class="grid md:grid-cols-2 gap-8 mb-12">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">How It Works</h2>
            <ol class="list-decimal list-inside space-y-3 text-gray-600">
                <li>Select or drag & drop your file</li>
                <li>Wait for upload to complete</li>
                <li>Get your shareable download link</li>
                <li>Share with anyone, anywhere</li>
            </ol>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Why Choose <?php echo SITE_NAME; ?>?</h2>
            <ul class="list-disc list-inside space-y-3 text-gray-600">
                <li>Fast and reliable uploads</li>
                <li>Secure file storage</li>
                <li>No registration required</li>
                <li>Simple and user-friendly</li>
            </ul>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-2xl font-bold text-center mb-8">Frequently Asked Questions</h2>
        <div class="grid gap-6 max-w-3xl mx-auto">
            <div>
                <h3 class="font-semibold text-lg mb-2">Is it really free?</h3>
                <p class="text-gray-600">Yes, our service is completely free for files up to 100MB.</p>
            </div>
            <div>
                <h3 class="font-semibold text-lg mb-2">How long are files stored?</h3>
                <p class="text-gray-600">Files are stored for 180 days from the last download date.</p>
            </div>
            <div>
                <h3 class="font-semibold text-lg mb-2">Is my data secure?</h3>
                <p class="text-gray-600">Yes, we use industry-standard encryption to protect your files.</p>
            </div>
        </div>
    </div>
</div>

<!-- Schema.org structured data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebApplication",
    "name": "<?php echo SITE_NAME; ?>",
    "url": "<?php echo BASE_URL; ?>",
    "description": "<?php echo $meta['description']; ?>",
    "applicationCategory": "File Sharing",
    "operatingSystem": "All",
    "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
    }
}
</script>

<script>
let xhr = null;
const MAX_FILE_SIZE = <?php echo MAX_FILE_SIZE; ?>; // Add PHP max file size constant
const dropZone = document.getElementById('dropZone');
const uploadForm = document.getElementById('uploadForm');
const progressBar = document.getElementById('progressBar');
const progressText = document.getElementById('progressText');
const uploadProgress = document.getElementById('uploadProgress');
const fileName = document.getElementById('fileName');
const cancelUpload = document.getElementById('cancelUpload');
const uploadButton = document.getElementById('uploadButton');

function handleDrop(e) {
    e.preventDefault();
    dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        validateAndProcessFile(files[0]);
    }
}

function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        validateAndProcessFile(input.files[0]);
    }
}

function validateAndProcessFile(file) {
    // Clear previous error state and messages
    dropZone.classList.remove('border-red-500', 'bg-red-50', 'border-blue-500', 'bg-blue-50');
    const existingError = document.getElementById('uploadError');
    if (existingError) {
        existingError.remove();
    }
    
    // Reset progress system first
    uploadProgress.style.opacity = '1';
    uploadProgress.classList.remove('opacity-0');
    
    // Hide previous upload response
    const uploadResponse = document.getElementById('uploadResponse');
    uploadResponse.classList.add('hidden');
    uploadResponse.innerHTML = '';
    
    // Show and reset progress elements
    uploadProgress.classList.remove('hidden');
    progressBar.style.width = '0%';
    progressText.textContent = '0%';
    fileName.textContent = file.name;
    
    // Show cancel button
    cancelUpload.classList.remove('hidden');
    
    // Check file size and continue with validation
    if (file.size > MAX_FILE_SIZE) {
        // Show error state
        dropZone.classList.add('border-red-500');
        
        // Create error message with animation
        const errorDiv = document.createElement('div');
        errorDiv.id = 'uploadError';
        errorDiv.className = 'mt-4 transform transition-all duration-300 ease-in-out opacity-0 scale-95';
        errorDiv.innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">File cannot be uploaded</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc space-y-1 pl-5">
                                <li>Selected file: <span class="font-medium">${file.name}</span></li>
                                <li>File size: <span class="font-medium">${(file.size / (1024 * 1024)).toFixed(2)} MB</span></li>
                                <li>Maximum allowed: <span class="font-medium">100 MB</span></li>
                            </ul>
                            <p class="mt-2">Please select a smaller file and try again.</p>
                        </div>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button onclick="this.closest('#uploadError').remove()" 
                                    class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 011.414 1.414L11.414 10l4.293 4.293a1 1 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 01-1.414-1.414L8.586 10 4.293 5.707a1 1 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Insert error message after dropZone
        dropZone.parentNode.insertBefore(errorDiv, dropZone.nextSibling);
        
        // Trigger animation
        requestAnimationFrame(() => {
            errorDiv.classList.remove('opacity-0', 'scale-95');
            errorDiv.classList.add('opacity-100', 'scale-100');
        });
        
        // Disable upload button
        uploadButton.disabled = true;
        uploadButton.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Clear file input
        document.getElementById('fileInput').value = '';
        uploadProgress.classList.add('hidden');
        return;
    }
    
    // Enable upload button
    uploadButton.disabled = false;
    uploadButton.classList.remove('opacity-50', 'cursor-not-allowed');
    
    // Show progress bar
    uploadProgress.classList.remove('hidden');
    cancelUpload.classList.remove('hidden');
    
    // Add success state to dropZone
    dropZone.classList.add('border-green-500', 'bg-green-50');
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function updateProgress(e) {
    if (e.lengthComputable) {
        const percentComplete = Math.round((e.loaded / e.total) * 100);
        const uploadedSize = formatFileSize(e.loaded);
        const totalSize = formatFileSize(e.total);
        
        // Update progress bar
        progressBar.style.width = percentComplete + '%';
        progressText.innerHTML = `
            <span class="font-medium">${percentComplete}%</span>
            <span class="text-gray-500 text-xs ml-2">${uploadedSize} / ${totalSize}</span>
        `;
        
        // Add color transitions based on progress
        if (percentComplete < 30) {
            progressBar.classList.add('bg-blue-400');
            progressBar.classList.remove('bg-blue-500', 'bg-blue-600');
        } else if (percentComplete < 70) {
            progressBar.classList.add('bg-blue-500');
            progressBar.classList.remove('bg-blue-400', 'bg-blue-600');
        } else {
            progressBar.classList.add('bg-blue-600');
            progressBar.classList.remove('bg-blue-400', 'bg-blue-500');
        }
        
        // Add upload speed and time remaining
        if (window.uploadStartTime) {
            const elapsedTime = (Date.now() - window.uploadStartTime) / 1000; // in seconds
            const uploadSpeed = e.loaded / elapsedTime; // bytes per second
            const remainingBytes = e.total - e.loaded;
            const remainingTime = remainingBytes / uploadSpeed; // seconds
            
            const speedText = formatFileSize(uploadSpeed) + '/s';
            const timeText = remainingTime > 0 ? 
                           `${Math.ceil(remainingTime)}s remaining` : 
                           'Completing...';
            
            document.getElementById('uploadStats').innerHTML = `
                <div class="text-xs text-gray-500 mt-1 flex justify-between">
                    <span>${speedText}</span>
                    <span>${timeText}</span>
                </div>
            `;
        }
    }
}

uploadForm.onsubmit = function(e) {
    e.preventDefault();
    const formData = new FormData(uploadForm);
    
    if (!formData.get('file').name) {
        alert('Please select a file to upload');
        return;
    }

    // Reset and show progress elements
    progressBar.style.width = '0%';
    progressText.textContent = '0%';
    uploadProgress.classList.remove('hidden');
    window.uploadStartTime = Date.now();
    
    // Add stats container if not exists
    if (!document.getElementById('uploadStats')) {
        const statsDiv = document.createElement('div');
        statsDiv.id = 'uploadStats';
        uploadProgress.appendChild(statsDiv);
    }

    // Disable upload button
    uploadButton.disabled = true;
    uploadButton.classList.add('opacity-50');

    // Create and send request
    xhr = new XMLHttpRequest();
    xhr.open('POST', '/upload.php', true); // Changed to local proxy URL
    // No need for API key header as it's handled by the proxy

    xhr.upload.onprogress = updateProgress;
    
    xhr.onload = function() {
        try {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                // First fade out progress section
                uploadProgress.classList.add('opacity-0');
                
                setTimeout(() => {
                    // Hide progress section completely
                    uploadProgress.classList.add('hidden');
                    cancelUpload.classList.add('hidden');
                    
                    // Clean up progress elements
                    progressBar.style.width = '0%';
                    progressText.textContent = '0%';
                    fileName.textContent = '';
                    const statsDiv = document.getElementById('uploadStats');
                    if (statsDiv) statsDiv.remove();
                    
                    // Show success response with animation
                    const uploadResponse = document.getElementById('uploadResponse');
                    uploadResponse.innerHTML = `
                        <div class="transform transition-all duration-300 ease-out opacity-0 translate-y-4">
                            <div class="bg-white border rounded-xl shadow-sm p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                            </svg>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900">Upload Complete!</h3>
                                    </div>
                                    <span class="text-sm text-gray-500">File ID: ${response.data.file_id}</span>
                                </div>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Download Page</label>
                                        <div class="flex mt-1 rounded-md shadow-sm">
                                            <input type="text" 
                                                   value="${window.location.origin}/download.php?id=${response.data.file_id}" 
                                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 text-sm" 
                                                   readonly 
                                                   id="downloadLink">
                                            <button onclick="copyToClipboard('downloadLink')" 
                                                    class="inline-flex items-center px-4 py-2 border border-l-0 border-r-0 border-gray-300 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 transition-colors">
                                                <svg class="h-4 w-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                                    <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                                                </svg>
                                                Copy
                                            </button>
                                            <a href="${window.location.origin}/download.php?id=${response.data.file_id}" 
                                               target="_blank"
                                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-r-md bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 transition-colors">
                                                <svg class="h-4 w-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                View
                                            </a>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-500">
                                            Share this link with others to let them download your file
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    uploadResponse.classList.remove('hidden');
                    
                    // Trigger fade in animation for success message
                    requestAnimationFrame(() => {
                        uploadResponse.firstElementChild.classList.remove('opacity-0', 'translate-y-4');
                    });
                    
                    // Reset form and upload zone but keep the success message
                    uploadForm.reset();
                    dropZone.classList.remove('border-green-500', 'bg-green-50');
                    
                }, 300); // Wait for fade out to complete
                
            } else {
                throw new Error(response.error || 'Upload failed');
            }
        } catch (error) {
            alert('Upload failed: ' + error.message);
        } finally {
            uploadButton.disabled = false;
            uploadButton.classList.remove('opacity-50');
        }
    };

    xhr.onerror = function() {
        alert('Network error occurred. Please check your connection and try again.');
        uploadButton.disabled = false;
        uploadButton.classList.remove('opacity-50');
        uploadProgress.classList.add('hidden');
    };

    xhr.send(formData);
};

function copyLink() {
    const shareLink = document.getElementById('shareLink');
    shareLink.select();
    document.execCommand('copy');
    alert('Link copied to clipboard!');
}

function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    document.execCommand('copy');
    
    // Show feedback
    const button = element.nextElementSibling;
    const originalText = button.innerHTML;
    button.innerHTML = `
        <svg class="h-4 w-4 mr-1.5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
        </svg>
        Copied!
    `;
    button.classList.add('text-green-600');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('text-green-600');
    }, 2000);
}
</script>

<?php include 'includes/footer.php'; ?>