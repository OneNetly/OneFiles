<?php
$pageTitle = 'Ad Blocker Detector Generator - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">Ad Blocker Detector Generator</h1>

            <!-- Options -->
            <div class="mb-6 space-y-4">
                <!-- Message -->
                <div>
                    <label class="block mb-2 font-bold">Warning Message</label>
                    <input 
                        type="text" 
                        v-model="options.warningMessage"
                        class="w-full p-3 border border-gray-300 rounded"
                        placeholder="Please disable your ad blocker to continue"
                    >
                </div>

                <!-- Title -->
                <div>
                    <label class="block mb-2 font-bold">Warning Title</label>
                    <input 
                        type="text" 
                        v-model="options.warningTitle"
                        class="w-full p-3 border border-gray-300 rounded"
                        placeholder="🛡️ Ad Blocker Detected"
                    >
                </div>

                <!-- Enhanced Appearance Settings -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Opacity Control -->
                    <div>
                        <label class="block mb-2 font-bold">Background Opacity</label>
                        <div class="space-y-2">
                            <input 
                                type="range" 
                                v-model.number="options.opacity" 
                                min="0" 
                                max="1" 
                                step="0.05"
                                class="w-full"
                            >
                            <div class="text-sm text-gray-600">
                                {{ Math.round(options.opacity * 100) }}% opacity
                            </div>
                        </div>
                        <!-- Live Preview -->
                        <div 
                            class="mt-2 h-16 rounded border"
                            :style="{
                                backgroundColor: `rgba(255, 255, 255, ${options.opacity})`
                            }"
                        ></div>
                    </div>

                    <!-- Blur Control -->
                    <div>
                        <label class="block mb-2 font-bold">Blur Effect</label>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        v-model="options.blur"
                                        class="h-4 w-4 text-blue-600"
                                    >
                                    <span class="ml-2">Enable blur effect</span>
                                </label>
                                <input 
                                    type="number" 
                                    v-model.number="options.blurAmount" 
                                    :disabled="!options.blur"
                                    min="1"
                                    max="20"
                                    class="w-20 p-1 border rounded"
                                >
                            </div>
                            <!-- Live Preview with dynamic content -->
                            <div class="relative h-24 overflow-hidden rounded border">
                                <!-- Background content -->
                                <div class="absolute inset-0 p-3 text-sm bg-gray-50">
                                    <div class="grid grid-cols-3 gap-2">
                                        <div v-for="i in 6" :key="i" 
                                            class="h-6 bg-gray-200 rounded animate-pulse"
                                        ></div>
                                    </div>
                                </div>
                                <!-- Blur overlay -->
                                <div 
                                    class="absolute inset-0"
                                    :style="{
                                        backgroundColor: `rgba(255, 255, 255, ${options.opacity})`,
                                        backdropFilter: options.blur ? `blur(${options.blurAmount}px)` : 'none'
                                    }"
                                ></div>
                                <!-- Preview text -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-sm font-medium">
                                        Preview with {{ options.blur ? `${options.blurAmount}px blur` : 'no blur' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Generated Code -->
            <div class="mb-6">
                <label class="block mb-2 font-bold">Generated Code</label>
                <pre class="bg-gray-50 p-4 rounded border overflow-x-auto"><code>{{ generatedCode }}</code></pre>
            </div>

            <!-- Copy Button -->
            <button
                @click="copyCode"
                :disabled="isCopying"
                :class="[
                    'font-bold py-3 px-6 rounded text-lg transition duration-300',
                    !isCopying ? 'bg-blue-600 hover:bg-blue-700 text-white' : 'bg-gray-300 cursor-not-allowed text-gray-500'
                ]"
            >
                {{ copyButtonText }}
            </button>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                options: {
                    warningMessage: 'Please disable your ad blocker to continue',
                    warningTitle: '🛡️ Ad Blocker Detected',
                    opacity: 0.95,
                    blur: true,
                    blurAmount: 5
                },
                copyButtonText: 'Copy to Clipboard',
                isCopying: false
            }
        },
        computed: {
            generatedCode() {
                return `<script src="https://onenetly.com/js/adblock-detector.js"><\/script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detector = new AdBlockDetector({
            warningMessage: '${this.options.warningMessage}',
            warningTitle: '${this.options.warningTitle}',
            opacity: ${this.options.opacity},
            blur: ${this.options.blur},
            blurAmount: ${this.options.blurAmount},
        });
        detector.init();
    });
<\/script>`;
            }
        },
        methods: {
            async copyCode() {
                if (this.isCopying) return;
                
                this.isCopying = true;
                
                try {
                    await navigator.clipboard.writeText(this.generatedCode);
                    this.copyButtonText = 'Copied!';
                } catch (err) {
                    console.error('Failed to copy:', err);
                    this.copyButtonText = 'Failed to copy';
                } finally {
                    setTimeout(() => {
                        this.copyButtonText = 'Copy to Clipboard';
                        this.isCopying = false;
                    }, 2000);
                }
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>