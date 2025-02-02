<?php
$pageTitle = 'YAML Formatter - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-yaml/4.1.0/js-yaml.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">YAML Formatter</h1>

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

            <!-- Options -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.indent" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Use 2 spaces indentation</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.sortKeys" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Sort Keys</span>
                </label>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">YAML Input:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="10"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Enter YAML to format..."
                    @input="formatYAML"
                ></textarea>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded">
                {{ error }}
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label for="output" class="block mb-2 font-bold text-lg">Formatted YAML:</label>
                <pre><code
                    id="output"
                    class="block w-full p-3 border border-gray-300 rounded text-lg font-mono bg-gray-50 whitespace-pre-wrap"
                >{{ output }}</code></pre>
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
                mode: 'format',
                input: '',
                output: '',
                error: '',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false,
                options: {
                    indent: true,
                    sortKeys: false
                }
            }
        },
        methods: {
            formatYAML() {
                if (!this.input.trim()) {
                    this.output = '';
                    this.error = '';
                    return;
                }

                try {
                    // Parse YAML to check validity and convert to object
                    const parsedYAML = jsyaml.load(this.input);

                    if (this.options.sortKeys) {
                        this.sortObjectKeys(parsedYAML);
                    }

                    // Convert back to YAML with formatting
                    if (this.mode === 'format') {
                        this.output = jsyaml.dump(parsedYAML, {
                            indent: this.options.indent ? 2 : 0,
                            lineWidth: -1,
                            noRefs: true
                        });
                    } else {
                        // Minify mode
                        this.output = jsyaml.dump(parsedYAML, {
                            indent: 0,
                            lineWidth: -1,
                            noRefs: true,
                            flowLevel: 0
                        });
                    }

                    this.error = '';
                } catch (err) {
                    console.error('YAML formatting error:', err);
                    this.error = 'Invalid YAML: ' + err.message;
                    this.output = '';
                }
            },
            sortObjectKeys(obj) {
                if (Array.isArray(obj)) {
                    obj.forEach(item => {
                        if (typeof item === 'object' && item !== null) {
                            this.sortObjectKeys(item);
                        }
                    });
                } else if (typeof obj === 'object' && obj !== null) {
                    const sorted = {};
                    Object.keys(obj).sort().forEach(key => {
                        sorted[key] = obj[key];
                        if (typeof obj[key] === 'object' && obj[key] !== null) {
                            this.sortObjectKeys(obj[key]);
                        }
                    });
                    Object.assign(obj, sorted);
                }
            },
            async copyOutput() {
                if (!this.output || this.isCopying) return;
                
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
                
                const blob = new Blob([this.output], {type: 'text/yaml'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'formatted.yaml';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }
        },
        watch: {
            'options.indent'() {
                this.formatYAML();
            },
            'options.sortKeys'() {
                this.formatYAML();
            },
            mode() {
                this.formatYAML();
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>