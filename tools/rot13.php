<?php
$pageTitle = 'ROT13 Encoder - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">ROT13 Encoder/Decoder</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">Input Text:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="5"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Enter text to encode/decode..."
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
                input: '',
                output: '',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false
            }
        },
        watch: {
            input(val) {
                this.output = this.rot13(val)
            }
        },
        methods: {
            rot13(str) {
                return str.replace(/[a-zA-Z]/g, c => {
                    const start = c <= 'Z' ? 65 : 97
                    return String.fromCharCode(start + (c.charCodeAt(0) - start + 13) % 26)
                })
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