<?php
$pageTitle = 'SQL Formatter - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sql-formatter/4.0.2/sql-formatter.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">SQL Formatter</h1>

            <!-- Language Selection -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">SQL Dialect:</label>
                <div class="flex space-x-4">
                    <button 
                        v-for="dialect in dialects"
                        :key="dialect"
                        @click="selectedDialect = dialect"
                        :class="[
                            'px-4 py-2 rounded-md',
                            selectedDialect === dialect ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >{{ dialect }}</button>
                </div>
            </div>

            <!-- Options -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.uppercase" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Uppercase Keywords</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.indent" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Use Indentation</span>
                </label>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">SQL Input:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="10"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Enter SQL query to format..."
                    @input="formatSQL"
                ></textarea>
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label for="output" class="block mb-2 font-bold text-lg">Formatted SQL:</label>
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
                selectedDialect: 'Standard SQL',
                dialects: ['Standard SQL', 'MySQL', 'PostgreSQL', 'Microsoft SQL'],
                copyButtonText: 'Copy to Clipboard',
                isCopying: false,
                options: {
                    uppercase: true,
                    indent: true
                }
            }
        },
        methods: {
            formatSQL() {
                if (!this.input.trim()) {
                    this.output = '';
                    return;
                }

                try {
                    const config = {
                        language: this.selectedDialect.toLowerCase().replace(/\s/g, ''),
                        uppercase: this.options.uppercase,
                        indent: this.options.indent ? '    ' : '',
                        linesBetweenQueries: 2
                    };

                    this.output = sqlFormatter.format(this.input, config);
                } catch (error) {
                    console.error('SQL formatting error:', error);
                    this.output = 'Error: Invalid SQL query';
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
                
                const blob = new Blob([this.output], {type: 'text/plain'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'formatted.sql';
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }
        },
        watch: {
            selectedDialect() {
                this.formatSQL();
            },
            'options.uppercase'() {
                this.formatSQL(); 
            },
            'options.indent'() {
                this.formatSQL();
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>