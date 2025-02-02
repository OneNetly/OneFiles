<?php
$pageTitle = 'Binary Text Converter - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">Binary Text Converter</h1>

            <!-- Mode Selector -->
            <div class="mb-6">
                <div class="flex space-x-4">
                    <button 
                        @click="mode = 'encode'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'encode' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Text to Binary</button>
                    <button 
                        @click="mode = 'decode'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'decode' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Binary to Text</button>
                </div>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">
                    {{ mode === 'encode' ? 'Text Input:' : 'Binary Input:' }}
                </label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="5"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    :placeholder="mode === 'encode' ? 'Enter text to convert to binary...' : 'Enter binary code (space-separated)...'"
                ></textarea>
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label for="output" class="block mb-2 font-bold text-lg">Output:</label>
                <textarea
                    id="output"
                    v-model="output"
                    rows="5"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono bg-gray-50"
                    readonly
                ></textarea>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded">
                {{ error }}
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
                mode: 'encode',
                input: '',
                output: '',
                error: '',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false
            }
        },
        watch: {
            input() {
                this.convertText()
            },
            mode() {
                this.input = ''
                this.output = ''
                this.error = ''
                this.copyButtonText = 'Copy to Clipboard'
            }
        },
        methods: {
            convertText() {
                this.error = ''
                if (!this.input.trim()) {
                    this.output = ''
                    return
                }

                try {
                    if (this.mode === 'encode') {
                        this.output = this.input
                            .split('')
                            .map(char => char.charCodeAt(0).toString(2).padStart(8, '0'))
                            .join(' ')
                    } else {
                        this.output = this.input
                            .trim()
                            .split(/\s+/)
                            .map(bin => {
                                if (!/^[01]{8}$/.test(bin)) {
                                    throw new Error('Invalid binary format')
                                }
                                return String.fromCharCode(parseInt(bin, 2))
                            })
                            .join('')
                    }
                } catch (err) {
                    this.error = 'Invalid input: Please ensure binary code is 8-bit space-separated'
                    this.output = ''
                }
            },
            async copyOutput() {
                if (this.isCopying || !this.output) return
                
                this.isCopying = true
                
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(this.output)
                    } else {
                        const textArea = document.createElement('textarea')
                        textArea.value = this.output
                        textArea.style.position = 'fixed'
                        textArea.style.left = '-999999px'
                        document.body.appendChild(textArea)
                        textArea.focus()
                        textArea.select()
                        
                        try {
                            document.execCommand('copy')
                            textArea.remove()
                        } catch (err) {
                            textArea.remove()
                            throw new Error('Failed to copy')
                        }
                    }
                    
                    this.copyButtonText = 'Copied!'
                } catch (err) {
                    console.error('Failed to copy:', err)
                    this.copyButtonText = 'Failed to copy'
                } finally {
                    setTimeout(() => {
                        this.copyButtonText = 'Copy to Clipboard'
                        this.isCopying = false
                    }, 2000)
                }
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>