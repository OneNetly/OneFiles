<?php
$pageTitle = 'JWT Decoder - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">JWT Decoder</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="jwtInput" class="block mb-2 font-bold text-lg">JWT Token:</label>
                <textarea 
                    id="jwtInput"
                    v-model="input"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    rows="4"
                    placeholder="Enter JWT token..."
                ></textarea>
            </div>

            <!-- Output Sections -->
            <div class="space-y-6" v-if="decodedData">
                <!-- Header -->
                <div>
                    <h2 class="text-lg font-bold mb-2">Header</h2>
                    <div class="bg-gray-50 p-4 rounded border">
                        <pre class="text-sm font-mono whitespace-pre-wrap">{{ formatJSON(decodedData.header) }}</pre>
                    </div>
                </div>

                <!-- Payload -->
                <div>
                    <h2 class="text-lg font-bold mb-2">Payload</h2>
                    <div class="bg-gray-50 p-4 rounded border">
                        <pre class="text-sm font-mono whitespace-pre-wrap">{{ formatJSON(decodedData.payload) }}</pre>
                    </div>
                </div>

                <!-- Signature -->
                <div>
                    <h2 class="text-lg font-bold mb-2">Signature</h2>
                    <div class="bg-gray-50 p-4 rounded border">
                        <code class="text-sm font-mono break-all">{{ decodedData.signature }}</code>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mt-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded">
                {{ error }}
            </div>

            <!-- Copy Buttons -->
            <div class="mt-6" v-if="decodedData">
                <div class="flex space-x-4">
                    <button 
                        @click="copyOutput('header')"
                        :disabled="!decodedData || isCopying"
                        :class="[
                            'font-bold py-3 px-6 rounded text-lg transition duration-300',
                            decodedData && !isCopying 
                                ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                                : 'bg-gray-300 cursor-not-allowed text-gray-500'
                        ]"
                    >
                        {{ copyButtonText.header }}
                    </button>
                    <button 
                        @click="copyOutput('payload')"
                        :disabled="!decodedData || isCopying"
                        :class="[
                            'font-bold py-3 px-6 rounded text-lg transition duration-300',
                            decodedData && !isCopying 
                                ? 'bg-blue-600 hover:bg-blue-700 text-white' 
                                : 'bg-gray-300 cursor-not-allowed text-gray-500'
                        ]"
                    >
                        {{ copyButtonText.payload }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                input: '',
                decodedData: null,
                error: null,
                isCopying: false,
                copyButtonText: {
                    header: 'Copy Header',
                    payload: 'Copy Payload'
                }
            }
        },
        watch: {
            input() {
                this.decodeJWT()
            }
        },
        methods: {
            decodeJWT() {
                this.error = null
                this.decodedData = null

                if (!this.input.trim()) {
                    return
                }

                try {
                    const parts = this.input.split('.')
                    if (parts.length !== 3) {
                        throw new Error('Invalid JWT format')
                    }

                    this.decodedData = {
                        header: JSON.parse(this.base64UrlDecode(parts[0])),
                        payload: JSON.parse(this.base64UrlDecode(parts[1])),
                        signature: parts[2]
                    }
                } catch (err) {
                    this.error = 'Invalid JWT token: ' + err.message
                }
            },
            base64UrlDecode(input) {
                let base64 = input.replace(/-/g, '+').replace(/_/g, '/')
                const pad = base64.length % 4
                if (pad) {
                    base64 += '='.repeat(4 - pad)
                }
                return atob(base64)
            },
            formatJSON(obj) {
                return JSON.stringify(obj, null, 2)
            },
            async copyOutput(type) {
                if (this.isCopying || !this.decodedData) return;
                
                this.isCopying = true;
                
                try {
                    const text = this.formatJSON(this.decodedData[type]);
                    
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(text);
                    } else {
                        const textArea = document.createElement('textarea');
                        textArea.value = text;
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
                    
                    this.copyButtonText[type] = 'Copied!';
                } catch (err) {
                    console.error('Failed to copy:', err);
                    this.copyButtonText[type] = 'Failed to copy';
                } finally {
                    setTimeout(() => {
                        this.copyButtonText[type] = type === 'header' ? 'Copy Header' : 'Copy Payload';
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