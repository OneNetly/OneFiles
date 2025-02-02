<?php
$pageTitle = 'JavaScript Obfuscator - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <script src="../js/index.browser.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">JavaScript Obfuscator</h1>

            <!-- Obfuscation Level -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Obfuscation Level:</label>
                <div class="flex flex-wrap gap-2">
                    <button 
                        v-for="level in ['Low', 'Medium', 'High']"
                        :key="level"
                        @click="setObfuscationLevel(level)"
                        :class="[
                            'px-4 py-2 rounded-md',
                            obfuscationLevel === level ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >{{ level }}</button>
                </div>
            </div>

            <!-- Options -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.compact" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Compact Code</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.controlFlowFlattening" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Control Flow Flattening</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.deadCodeInjection" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Dead Code Injection</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.stringArray" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">String Array Encoding</span>
                </label>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">JavaScript Code:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="10"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Enter JavaScript code to obfuscate..."
                    @input="obfuscateCode"
                ></textarea>
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label for="output" class="block mb-2 font-bold text-lg">Obfuscated Code:</label>
                <textarea
                    id="output"
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
                obfuscationLevel: 'Low',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false,
                options: {
                    compact: true,
                    controlFlowFlattening: false,
                    deadCodeInjection: false,
                    stringArray: true
                }
            }
        },
        methods: {
            setObfuscationLevel(level) {
                this.obfuscationLevel = level;
                switch(level) {
                    case 'Low':
                        this.options = {
                            compact: true,
                            controlFlowFlattening: false,
                            deadCodeInjection: false,
                            stringArray: true
                        };
                        break;
                    case 'Medium':
                        this.options = {
                            compact: true,
                            controlFlowFlattening: true,
                            deadCodeInjection: false,
                            stringArray: true
                        };
                        break;
                    case 'High':
                        this.options = {
                            compact: true,
                            controlFlowFlattening: true,
                            deadCodeInjection: true,
                            stringArray: true
                        };
                        break;
                }
                this.obfuscateCode();
            },
            
            obfuscateCode() {
                if (!this.input.trim()) {
                    this.output = '';
                    return;
                }
            
                try {
                    // Use JavaScriptObfuscator from the loaded CDN instead of require
                    const result = JavaScriptObfuscator.obfuscate(this.input, {
                        compact: this.options.compact,
                        controlFlowFlattening: this.options.controlFlowFlattening,
                        controlFlowFlatteningThreshold: this.obfuscationLevel === 'High' ? 1 : 0.75,
                        deadCodeInjection: this.options.deadCodeInjection,
                        deadCodeInjectionThreshold: 0.4,
                        stringArray: this.options.stringArray,
                        stringArrayEncoding: ['base64'],
                        stringArrayThreshold: 0.75,
                        transformObjectKeys: true,
                        unicodeEscapeSequence: true
                    });
            
                    this.output = result.getObfuscatedCode();
                } catch (error) {
                    console.error('Obfuscation error:', error);
                    this.output = 'Error: ' + error.message;
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
            },
            downloadOutput() {
                if (!this.output) return;
                
                const blob = new Blob([this.output], {type: 'text/javascript'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'obfuscated.js';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }
        },
        mounted() {
            this.setObfuscationLevel('Low');
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>