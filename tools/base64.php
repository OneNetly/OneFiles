<?php
$encoded = '';
$decoded = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['encode'])) {
        $text = $_POST['text'] ?? '';
        $encoded = base64_encode($text);
    } elseif (isset($_POST['decode'])) {
        $text = $_POST['text'] ?? '';
        try {
            $decoded = base64_decode($text, true);
            if ($decoded === false) {
                throw new Exception('Invalid base64 string');
            }
        } catch (Exception $e) {
            $error = 'Invalid base64 string';
        }
    }
}
$pageTitle = 'Base64 Encoder/Decoder - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">Base64 Encoder/Decoder</h1>

            <!-- Mode Selector -->
            <div class="mb-6">
                <div class="flex space-x-4">
                    <button 
                        @click="mode = 'text'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'text' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Text</button>
                    <button 
                        @click="mode = 'image'"
                        :class="[
                            'px-4 py-2 rounded-md',
                            mode === 'image' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >Image</button>
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

            <!-- Input Sections -->
            <div class="mb-6" v-if="mode === 'text'">
                <label for="textInput" class="block mb-2 font-bold text-lg">Text Input:</label>
                <textarea
                    id="textInput"
                    v-model="textInput"
                    rows="5"
                    class="w-full p-3 border border-gray-300 rounded text-lg"
                    placeholder="Enter text to encode..."
                ></textarea>
            </div>

            <div class="mb-6" v-if="mode === 'image'">
                <label for="imageInput" class="block mb-2 font-bold text-lg">Image Input:</label>
                <div class="flex items-center">
                    <label class="w-64 flex flex-col items-center px-4 py-6 bg-white text-blue rounded-lg shadow-lg tracking-wide uppercase border border-blue cursor-pointer">
                        <svg class="w-8 h-8" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M16.88 9.1A4 4 0 0 1 16 17H5a5 5 0 0 1-1-9.9V7a3 3 0 0 1 4.52-2.59A4.98 4.98 0 0 1 17 8c0 .38-.04.74-.12 1.1zM11 11h3l-4-4-4 4h3v3h2v-3z" />
                        </svg>
                        <span class="mt-2 text-base leading-normal">Select an image</span>
                        <input id="imageInput" type="file" accept="image/*" @change="encodeImage" class="hidden">
                    </label>
                </div>
                <div v-if="imageUrl" class="mt-4">
                    <img :src="imageUrl" alt="Selected Image" class="max-w-full h-auto rounded">
                </div>
            </div>

            <div class="mb-6" v-if="mode === 'decode'">
                <label for="decodeInput" class="block mb-2 font-bold text-lg">Base64 Input:</label>
                <textarea
                    id="decodeInput"
                    v-model="decodeInput"
                    rows="5"
                    class="w-full p-3 border border-gray-300 rounded text-lg"
                    placeholder="Enter Base64 string to decode..."
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
            mode: 'text',
            textInput: '',
            decodeInput: '',
            output: '',
            imageUrl: null,
            copyButtonText: 'Copy to Clipboard',
            isCopying: false
        }
    },
    watch: {
        textInput(val) {
            if (this.mode === 'text') {
                this.output = btoa(val)
            }
        },
        decodeInput(val) {
            if (this.mode === 'decode') {
                try {
                    this.output = atob(val)
                } catch (e) {
                    this.output = 'Invalid Base64 string'
                }
            }
        },
        mode() {
            this.textInput = ''
            this.decodeInput = ''
            this.output = ''
            this.copyButtonText = 'Copy to Clipboard'
        }
    },
    methods: {
        async encodeImage(event) {
            const file = event.target.files[0]
            if (file) {
                const reader = new FileReader()
                reader.onload = (e) => {
                    this.imageUrl = e.target.result
                    this.output = e.target.result.split(',')[1]
                }
                reader.readAsDataURL(file)
            }
        },
        async copyOutput() {
            if (this.isCopying || !this.output) return;
            
            this.isCopying = true;
            
            try {
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(this.output);
                } else {
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