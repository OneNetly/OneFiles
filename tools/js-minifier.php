<?php
$pageTitle = 'JavaScript Minifier - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/terser@5.19.2/dist/bundle.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">JavaScript Minifier</h1>

            <!-- Options -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.mangle" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Mangle Variable Names</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.compress" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Compress Code</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.comments" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Remove Comments</span>
                </label>
            </div>

            <!-- Input -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">JavaScript Code:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="10"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Paste your JavaScript code here..."
                    @input="minifyCode"
                ></textarea>
            </div>

            <!-- Stats -->
            <div v-if="stats.show" class="mb-6 p-4 bg-gray-50 rounded border">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="font-bold">Original Size:</span> 
                        {{ stats.originalSize }} KB
                    </div>
                    <div>
                        <span class="font-bold">Minified Size:</span> 
                        {{ stats.minifiedSize }} KB
                    </div>
                    <div>
                        <span class="font-bold">Compression Ratio:</span> 
                        {{ stats.compressionRatio }}%
                    </div>
                    <div>
                        <span class="font-bold">Saved:</span> 
                        {{ stats.savedSize }} KB
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded">
                {{ error }}
            </div>

            <!-- Output -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Minified Code:</label>
                <textarea
                    v-model="output"
                    rows="10"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono bg-gray-50"
                    readonly
                ></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-4">
                <button
                    @click="copyOutput"
                    :disabled="!output || isCopying"
                    :class="[
                        'font-bold py-3 px-6 rounded text-lg transition duration-300',
                        output && !isCopying 
                            ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                            : 'bg-gray-300 cursor-not-allowed text-gray-500'
                    ]"
                >
                    {{ copyButtonText }}
                </button>

                <button
                    @click="downloadOutput"
                    :disabled="!output"
                    :class="[
                        'font-bold py-3 px-6 rounded text-lg transition duration-300',
                        output 
                            ? 'bg-green-600 hover:bg-green-700 text-white' 
                            : 'bg-gray-300 cursor-not-allowed text-gray-500'
                    ]"
                >
                    Download
                </button>
            </div>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                input: '',
                output: '',
                error: '',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false,
                options: {
                    mangle: true,
                    compress: true,
                    comments: true
                },
                stats: {
                    show: false,
                    originalSize: 0,
                    minifiedSize: 0,
                    compressionRatio: 0,
                    savedSize: 0
                }
            }
        },
        methods: {
            async minifyCode() {
                if (!this.input.trim()) {
                    this.output = '';
                    this.error = '';
                    this.stats.show = false;
                    return;
                }
        
                try {
                    // Wait for Terser to load
                    if (typeof Terser === 'undefined') {
                        await new Promise(resolve => {
                            const checkTerser = setInterval(() => {
                                if (typeof Terser !== 'undefined') {
                                    clearInterval(checkTerser);
                                    resolve();
                                }
                            }, 100);
                        });
                    }
        
                    const result = await Terser.minify(this.input, {
                        mangle: this.options.mangle,
                        compress: this.options.compress,
                        output: {
                            comments: !this.options.comments
                        }
                    });
        
                    this.output = result.code;
                    this.error = '';
                    this.calculateStats();
                } catch (err) {
                    console.error('Minification error:', err);
                    this.error = 'JavaScript syntax error: ' + err.message;
                    this.output = '';
                    this.stats.show = false;
                }
            },
            calculateStats() {
                const originalBytes = new Blob([this.input]).size;
                const minifiedBytes = new Blob([this.output]).size;
                
                this.stats.show = true;
                this.stats.originalSize = (originalBytes / 1024).toFixed(2);
                this.stats.minifiedSize = (minifiedBytes / 1024).toFixed(2);
                this.stats.compressionRatio = ((1 - minifiedBytes / originalBytes) * 100).toFixed(1);
                this.stats.savedSize = ((originalBytes - minifiedBytes) / 1024).toFixed(2);
            },
            async copyOutput() {
    if (!this.output || this.isCopying) return;
    
    this.isCopying = true;
    
    try {
        // Try modern clipboard API first
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(this.output);
        } else {
            // Fallback for older browsers or non-HTTPS
            const textArea = document.createElement('textarea');
            textArea.value = this.output;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                textArea.remove();
            } catch (err) {
                textArea.remove();
                throw new Error('Failed to copy');
            }
        }
        
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
},
            downloadOutput() {
                if (!this.output) return;
                
                const blob = new Blob([this.output], { type: 'text/javascript' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'minified.js';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }
        },
        watch: {
            'options': {
                handler() {
                    this.minifyCode();
                },
                deep: true
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>