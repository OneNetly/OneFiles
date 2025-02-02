<?php
$pageTitle = 'JSON Formatter - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">JSON Formatter</h1>

            <!-- Mode Selector -->
            <div class="mb-6">
                <div class="flex space-x-4">
                    <button 
                        @click="mode = 'format'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'format' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Format</button>
                    <button 
                        @click="mode = 'minify'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'minify' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Minify</button>
                </div>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">JSON Input:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="8"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Enter JSON to format..."
                    @input="processInput"
                ></textarea>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6">
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ error }}
                </div>
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label for="output" class="block mb-2 font-bold text-lg">Output:</label>
                <textarea
                    id="output"
                    v-model="output"
                    rows="8"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    readonly
                ></textarea>
            </div>

            <!-- Copy Button -->
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
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                mode: 'format',
                input: '',
                output: '',
                error: '',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false
            }
        },
        methods: {
            processInput() {
                if (!this.input.trim()) {
                    this.output = '';
                    this.error = '';
                    return;
                }

                try {
                    // Parse JSON to validate it
                    const parsed = JSON.parse(this.input);
                    this.error = '';

                    // Format based on mode
                    if (this.mode === 'format') {
                        this.output = JSON.stringify(parsed, null, 2);
                    } else {
                        this.output = JSON.stringify(parsed);
                    }
                } catch (e) {
                    this.error = 'Invalid JSON: ' + e.message;
                    this.output = '';
                }
            },
                        async copyOutput() {
                            if (this.isCopying || !this.output) return;
                            
                            this.isCopying = true;
                            
                            try {
                                if (navigator.clipboard && window.isSecureContext) {
                                    await navigator.clipboard.writeText(this.output);
                                } else {
                                    // Fallback for older browsers
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
                        }
                    },
                    watch: {
                        mode() {
                            this.processInput();
                        }
                    }
                }).mount('#app')
                </script>
            
                <?php require_once '../footer.php'; ?>
            </body>
            </html>