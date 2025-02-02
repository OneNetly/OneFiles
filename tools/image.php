<?php
$pageTitle = 'Image Optimizer - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <!-- Add image compression library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/browser-image-compression/2.0.0/browser-image-compression.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">Image Optimizer</h1>

            <!-- Upload Section -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Upload Image:</label>
                <div class="flex items-center justify-center w-full">
                    <label class="w-full flex flex-col items-center px-4 py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 cursor-pointer hover:bg-gray-100">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-10 h-10 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                            <p class="mb-2 text-sm text-gray-500">
                                <span class="font-semibold">Click to upload</span> or drag and drop
                            </p>
                            <p class="text-xs text-gray-500">PNG, JPG or WEBP (MAX. 10MB)</p>
                        </div>
                        <input 
                            type="file" 
                            class="hidden" 
                            accept="image/*"
                            @change="handleImageUpload"
                        >
                    </label>
                </div>
            </div>

            <!-- Options Section -->
            <div v-if="originalImage" class="mb-6">
                <h2 class="text-lg font-bold mb-4">Optimization Options</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Quality Slider -->
                    <div>
                        <label class="block mb-2">Quality: {{ quality }}%</label>
                        <input 
                            type="range" 
                            v-model="quality" 
                            min="1" 
                            max="100" 
                            class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-gray-200"
                            @input="handleQualityChange"
                        >
                    </div>
                    
                    <!-- Max Width -->
                    <div>
                        <label class="block mb-2">Max Width (px):</label>
                        <input 
                            type="number" 
                            v-model="maxWidth" 
                            min="100" 
                            max="4000"
                            class="w-full p-2 border border-gray-300 rounded"
                            @input="handleMaxWidthChange"
                        >
                    </div>

                    <!-- Format Selection -->
                    <div>
                        <label class="block mb-2">Output Format:</label>
                        <select 
                            v-model="outputFormat"
                            class="w-full p-2 border border-gray-300 rounded"
                            @change="handleFormatChange"
                        >
                            <option value="image/jpeg">JPEG</option>
                            <option value="image/png">PNG</option>
                            <option value="image/webp">WebP</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div v-if="originalImage" class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Original Image -->
                <div>
                    <h3 class="font-bold mb-2">Original Image</h3>
                    <div class="relative border rounded-lg overflow-hidden">
                        <img :src="originalImage" class="w-full h-auto" alt="Original image">
                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white px-3 py-1 text-sm">
                            {{ originalSize }}
                        </div>
                    </div>
                </div>

                <!-- Optimized Image -->
                <div>
                    <h3 class="font-bold mb-2">Optimized Image</h3>
                    <div v-if="optimizedImage" class="relative border rounded-lg overflow-hidden">
                        <img :src="optimizedImage" class="w-full h-auto" alt="Optimized image">
                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white px-3 py-1 text-sm">
                            {{ optimizedSize }}
                            <span class="float-right">{{ compressionRatio }}% smaller</span>
                        </div>
                    </div>
                    <div v-else class="flex items-center justify-center h-full border rounded-lg p-4">
                        <p class="text-gray-500">Processing...</p>
                    </div>
                </div>
            </div>

            <!-- Download Button -->
            <div v-if="optimizedImage" class="flex justify-end">
                <button
                    @click="downloadImage"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Download Optimized Image
                </button>
            </div>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                originalImage: null,
                optimizedImage: null,
                originalFile: null,
                originalSize: '0 KB',
                optimizedSize: '0 KB',
                compressionRatio: 0,
                quality: 80,
                maxWidth: 1920,
                outputFormat: 'image/jpeg',
                processing: false
            }
        },
        methods: {
            formatFileSize(bytes) {
                if (bytes === 0) return '0 KB';
                const k = 1024;
                const sizes = ['B', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            },
            
            async handleImageUpload(event) {
                const file = event.target.files[0];
                if (!file) return;
                
                // Check file size
                if (file.size > 10 * 1024 * 1024) {
                    alert('File size must be less than 10MB');
                    return;
                }

                this.originalFile = file;
                this.originalImage = URL.createObjectURL(file);
                this.originalSize = this.formatFileSize(file.size);
                
                await this.optimizeImage();
            },

            async handleQualityChange() {
                if (this.originalFile) {
                    await this.optimizeImage();
                }
            },

            async handleMaxWidthChange() {
                if (this.originalFile) {
                    await this.optimizeImage();
                }
            },

            async handleFormatChange() {
                if (this.originalFile) {
                    await this.optimizeImage();
                }
            },

            async optimizeImage() {
                if (!this.originalFile || this.processing) return;
                
                this.processing = true;
                this.optimizedImage = null;

                try {
                    const options = {
                        maxSizeMB: 10,
                        maxWidthOrHeight: this.maxWidth,
                        useWebWorker: true,
                        fileType: this.outputFormat,
                        quality: this.quality / 100
                    };

                    const compressedFile = await imageCompression(this.originalFile, options);
                    this.optimizedImage = URL.createObjectURL(compressedFile);
                    this.optimizedSize = this.formatFileSize(compressedFile.size);
                    
                    // Calculate compression ratio
                    const ratio = ((this.originalFile.size - compressedFile.size) / this.originalFile.size * 100);
                    this.compressionRatio = Math.round(ratio);
                } catch (error) {
                    console.error('Error during image optimization:', error);
                    alert('Error optimizing image. Please try again.');
                } finally {
                    this.processing = false;
                }
            },

            downloadImage() {
                if (!this.optimizedImage) return;

                const extension = this.outputFormat.split('/')[1];
                const link = document.createElement('a');
                link.href = this.optimizedImage;
                link.download = `optimized_image.${extension}`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>