<?php
$pageTitle = 'Hash Generator - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <script src="../js/crypto-js.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">Hash Generator</h1>

            <!-- Algorithm Selector -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2">
                    <button 
                        v-for="algo in algorithms"
                        :key="algo"
                        @click="selectedAlgorithm = algo"
                        :class="[
                            'px-4 py-2 rounded-md',
                            selectedAlgorithm === algo ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >{{ algo }}</button>
                </div>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">Input Text:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="4"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Enter text to hash..."
                    @input="generateHash"
                ></textarea>
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Hash Output:</label>
                <div class="relative">
                    <input
                        type="text"
                        v-model="output"
                        class="w-full p-3 border border-gray-300 rounded text-lg font-mono bg-gray-50"
                        readonly
                    >
                </div>
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
                algorithms: ['MD5', 'SHA1', 'SHA256', 'SHA512', 'RIPEMD160'],
                selectedAlgorithm: 'MD5',
                input: '',
                output: '',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false
            }
        },
        methods: {
            generateHash() {
                if (!this.input) {
                    this.output = '';
                    return;
                }

                try {
                    switch(this.selectedAlgorithm) {
                        case 'MD5':
                            this.output = CryptoJS.MD5(this.input).toString();
                            break;
                        case 'SHA1':
                            this.output = CryptoJS.SHA1(this.input).toString();
                            break;
                        case 'SHA256':
                            this.output = CryptoJS.SHA256(this.input).toString();
                            break;
                        case 'SHA512':
                            this.output = CryptoJS.SHA512(this.input).toString();
                            break;
                        case 'RIPEMD160':
                            this.output = CryptoJS.RIPEMD160(this.input).toString();
                            break;
                    }
                } catch (error) {
                    console.error('Hash generation failed:', error);
                    this.output = 'Error generating hash';
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
        },
        watch: {
            selectedAlgorithm() {
                this.generateHash();
            }
        },
        mounted() {
            this.generateHash();
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>