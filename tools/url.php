<?php
$encoded = '';
$decoded = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['encode'])) {
        $text = $_POST['text'] ?? '';
        $encoded = urlencode($text);
    } elseif (isset($_POST['decode'])) {
        $text = $_POST['text'] ?? '';
        try {
            $decoded = urldecode($text);
        } catch (Exception $e) {
            $error = 'Invalid URL encoded string';
        }
    }
}
$pageTitle = 'URL Encoder/Decoder - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">URL Encoder/Decoder</h1>

            <!-- Mode Selector -->
            <div class="mb-6">
                <div class="flex space-x-4">
                    <button 
                        @click="mode = 'encode'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'encode' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Encode</button>
                    <button 
                        @click="mode = 'decode'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'decode' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Decode</button>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">
                    {{ mode === 'encode' ? 'Text to Encode:' : 'URL to Decode:' }}
                </label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="5"
                    class="w-full p-3 border border-gray-300 rounded text-lg"
                    :placeholder="mode === 'encode' ? 'Enter text to encode...' : 'Enter URL encoded string to decode...'"
                ></textarea>
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label for="output" class="block mb-2 font-bold text-lg">Output:</label>
                <textarea
                    id="output"
                    v-model="output"
                    rows="5"
                    class="w-full p-3 border border-gray-300 rounded text-lg"
                    readonly
                ></textarea>
            </div>

            <button
    @click="copyOutput"
    :disabled="!output || isCopying"
    :class="[
        'font-bold py-3 px-6 rounded text-lg transition duration-300',
        output && !isCopying 
            ? 'bg-blue-500 hover:bg-blue-600 text-white' 
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
                copyButtonText: 'Copy to Clipboard',
                isCopying: false
            }
        },
        watch: {
            input(val) {
                if (this.mode === 'encode') {
                    try {
                        this.output = encodeURIComponent(val)
                    } catch (e) {
                        this.output = 'Error: Invalid input for encoding'
                    }
                } else {
                    try {
                        this.output = decodeURIComponent(val)
                    } catch (e) {
                        this.output = 'Error: Invalid URL encoded string'
                    }
                }
            },
            mode() {
                this.input = ''
                this.output = ''
                this.copyButtonText = 'Copy to Clipboard'
            }
        },
        methods: {
            async copyOutput() {
                if (this.isCopying || !this.output) return;
                
                this.isCopying = true;
                
                try {
                    // Try using newer Clipboard API first
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
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>