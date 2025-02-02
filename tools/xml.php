<?php
$pageTitle = 'XML Formatter - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">XML Formatter</h1>

            <!-- Options -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.indent" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Use Indentation</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.newlines" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Add New Lines</span>
                </label>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">XML Input:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="10"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Enter XML to format..."
                    @input="formatXML"
                ></textarea>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded">
                {{ error }}
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label for="output" class="block mb-2 font-bold text-lg">Formatted XML:</label>
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
                input: '',
                output: '',
                error: '',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false,
                options: {
                    indent: true,
                    newlines: true
                }
            }
        },
        methods: {
            formatXML() {
                if (!this.input.trim()) {
                    this.output = '';
                    this.error = '';
                    return;
                }

                try {
                    // Parse XML string to check if it's valid
                    const parser = new DOMParser();
                    const xmlDoc = parser.parseFromString(this.input, 'text/xml');
                    
                    if (xmlDoc.getElementsByTagName('parsererror').length > 0) {
                        throw new Error('Invalid XML');
                    }

                    // Format XML using pretty print function
                    const formatted = this.prettyPrintXML(xmlDoc);
                    this.output = formatted;
                    this.error = '';
                } catch (err) {
                    console.error('XML formatting error:', err);
                    this.error = 'Invalid XML: Please check your input';
                    this.output = '';
                }
            },
            prettyPrintXML(xml) {
                const serializer = new XMLSerializer();
                let output = serializer.serializeToString(xml);
                
                if (this.options.indent || this.options.newlines) {
                    let formatted = '';
                    let indent = 0;
                    
                    const lines = output
                        .replace(/>\s*</g, '>\n<')
                        .trim()
                        .split('\n');

                    for (let line of lines) {
                        let spaces = this.options.indent 
                            ? '    '.repeat(indent) 
                            : '';
                            
                        if (line.match(/.+<\/\w[^>]*>$/)) {
                            formatted += spaces + line + '\n';
                        } else if (line.match(/^<\/\w/)) {
                            indent--;
                            spaces = this.options.indent 
                                ? '    '.repeat(indent) 
                                : '';
                            formatted += spaces + line + '\n';
                        } else if (line.match(/^<\w[^>]*[^\/]>.*$/)) {
                            formatted += spaces + line + '\n';
                            indent++;
                        } else {
                            formatted += spaces + line + '\n';
                        }
                    }
                    
                    output = formatted.trim();
                }
                
                return output;
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
                
                const blob = new Blob([this.output], {type: 'text/xml'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'formatted.xml';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }
        },
        watch: {
            'options.indent'() {
                this.formatXML();
            },
            'options.newlines'() {
                this.formatXML();
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>