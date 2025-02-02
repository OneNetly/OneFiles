<?php
$pageTitle = 'ASCII/Unicode Converter - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">ASCII/Unicode Converter</h1>

            <!-- Mode Selector -->
            <div class="mb-6">
                <div class="flex space-x-4">
                    <button 
                        @click="mode = 'toCode'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'toCode' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Text to Code</button>
                    <button 
                        @click="mode = 'toText'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'toText' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Code to Text</button>
                </div>
            </div>

            <!-- Format Selector -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Format:</label>
                <div class="flex space-x-4">
                    <button 
                        @click="format = 'decimal'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            format === 'decimal' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Decimal</button>
                    <button 
                        @click="format = 'hex'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            format === 'hex' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Hexadecimal</button>
                </div>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">
                    {{ mode === 'toCode' ? 'Text Input:' : 'Code Input:' }}
                </label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="5"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    :placeholder="getPlaceholder()"
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
                mode: 'toCode',
                format: 'decimal',
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
            },
            format() {
                this.convertText()
            }
        },
        methods: {
            getPlaceholder() {
                if (this.mode === 'toCode') {
                    return 'Enter text to convert to codes...'
                } else {
                    return this.format === 'decimal' 
                        ? 'Enter decimal codes (space-separated)...' 
                        : 'Enter hex codes (space-separated)...'
                }
            },
            convertText() {
                this.error = ''
                if (!this.input.trim()) {
                    this.output = ''
                    return
                }

                try {
                    if (this.mode === 'toCode') {
                        // Convert text to codes
                        this.output = this.input
                            .split('')
                            .map(char => {
                                const code = char.charCodeAt(0)
                                return this.format === 'decimal' 
                                    ? code 
                                    : '0x' + code.toString(16).toUpperCase()
                            })
                            .join(' ')
                    } else {
                        // Convert codes to text
                        const codes = this.input.trim().split(/\s+/)
                        this.output = codes
                            .map(code => {
                                let num
                                if (this.format === 'decimal') {
                                    num = parseInt(code, 10)
                                } else {
                                    num = parseInt(code.replace(/^0x/i, ''), 16)
                                }
                                if (isNaN(num)) {
                                    throw new Error('Invalid code format')
                                }
                                return String.fromCharCode(num)
                            })
                            .join('')
                    }
                } catch (err) {
                    this.error = `Invalid input: Please ensure ${this.format} codes are properly formatted`
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