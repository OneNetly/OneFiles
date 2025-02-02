<?php
$pageTitle = 'QR Code Generator - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <!-- Add QR Code library -->
    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">QR Code Generator</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">Text or URL:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="4"
                    class="w-full p-3 border border-gray-300 rounded text-lg"
                    placeholder="Enter text or URL to generate QR code..."
                    @input="generateQR"
                ></textarea>
            </div>

            <!-- Options Section -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Size -->
                <div>
                    <label class="block mb-2">Size (px):</label>
                    <input 
                        type="number" 
                        v-model="size" 
                        min="100" 
                        max="1000"
                        step="50"
                        class="w-full p-2 border border-gray-300 rounded"
                        @input="generateQR"
                    >
                </div>

                <!-- Background Color -->
                <div>
                    <label class="block mb-2">Background Color:</label>
                    <input 
                        type="color" 
                        v-model="background"
                        class="w-full p-1 border border-gray-300 rounded h-10"
                        @input="generateQR"
                    >
                </div>

                <!-- Foreground Color -->
                <div>
                    <label class="block mb-2">Foreground Color:</label>
                    <input 
                        type="color" 
                        v-model="foreground"
                        class="w-full p-1 border border-gray-300 rounded h-10"
                        @input="generateQR"
                    >
                </div>
            </div>

            <!-- QR Code Output -->
            <div class="mb-6" v-if="input">
                <label class="block mb-2 font-bold text-lg">Generated QR Code:</label>
                <div class="flex justify-center bg-gray-50 p-4 rounded-lg">
                    <canvas id="qr"></canvas>
                </div>
            </div>

            <!-- Download Button -->
            <button
                @click="downloadQR"
                v-if="input"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded text-lg transition duration-300"
            >
                Download QR Code
            </button>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                input: '',
                size: 300,
                background: '#FFFFFF',
                foreground: '#000000',
                qr: null
            }
        },
        methods: {
            generateQR() {
                if (!this.input) return;
                
                if (!this.qr) {
                    this.qr = new QRious({
                        element: document.getElementById('qr'),
                        size: this.size,
                        value: this.input,
                        background: this.background,
                        foreground: this.foreground,
                        level: 'H' // High error correction
                    });
                } else {
                    this.qr.set({
                        value: this.input,
                        size: this.size,
                        background: this.background,
                        foreground: this.foreground
                    });
                }
            },
            downloadQR() {
                if (!this.qr) return;
                
                const link = document.createElement('a');
                link.download = 'qr-code.png';
                link.href = this.qr.toDataURL('image/png');
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        },
        mounted() {
            this.generateQR();
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>