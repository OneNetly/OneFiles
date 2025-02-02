<?php
$pageTitle = 'Code Beautifier - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <!-- Add Prism.js for syntax highlighting -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <!-- Add js-beautify library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify-html.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.0/beautify-css.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">Code Beautifier</h1>

            <!-- Language Selection -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Language:</label>
                <div class="flex space-x-4">
                    <button 
                        v-for="lang in languages"
                        :key="lang"
                        @click="selectedLanguage = lang"
                        :class="[
                            'px-4 py-2 rounded-md',
                            selectedLanguage === lang ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >{{ lang }}</button>
                </div>
            </div>

            <!-- Options -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.indent_size" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Use 4 spaces indentation</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.preserve_newlines" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Preserve newlines</span>
                </label>
                <label class="flex items-center">
                    <input type="checkbox" v-model="options.wrap_line_length" class="h-4 w-4 text-blue-600">
                    <span class="ml-2">Wrap long lines</span>
                </label>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">Input Code:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="10"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Paste your code here..."
                    @input="beautifyCode"
                ></textarea>
            </div>

            <!-- Output Section -->
            <div class="mb-6">
                <label for="output" class="block mb-2 font-bold text-lg">Beautified Code:</label>
                <pre><code
                    id="output"
                    :class="'language-' + selectedLanguage.toLowerCase()"
                    class="block w-full p-3 border border-gray-300 rounded text-lg font-mono bg-gray-50"
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
                selectedLanguage: 'HTML',
                languages: ['HTML', 'CSS', 'JavaScript', 'JSON'],
                copyButtonText: 'Copy to Clipboard',
                isCopying: false,
                options: {
                    indent_size: true,
                    preserve_newlines: true,
                    wrap_line_length: false,
                    indent_char: ' ',
                    max_preserve_newlines: 5,
                    preserve_newlines: true,
                    wrap_line_length: 0
                }
            }
        },
        methods: {
            beautifyCode() {
                if (!this.input) {
                    this.output = '';
                    return;
                }

                try {
                    const opts = {
                        indent_size: this.options.indent_size ? 4 : 2,
                        indent_char: ' ',
                        max_preserve_newlines: this.options.preserve_newlines ? 5 : 0,
                        preserve_newlines: this.options.preserve_newlines,
                        wrap_line_length: this.options.wrap_line_length ? 80 : 0
                    };

                    switch (this.selectedLanguage) {
                        case 'HTML':
                            this.output = html_beautify(this.input, opts);
                            break;
                        case 'CSS':
                            this.output = css_beautify(this.input, opts);
                            break;
                        case 'JavaScript':
                            this.output = js_beautify(this.input, opts);
                            break;
                        case 'JSON':
                            this.output = js_beautify(this.input, {
                                ...opts,
                                preserve_newlines: false
                            });
                            break;
                    }

                    // Highlight code using Prism.js
                    this.$nextTick(() => {
                        Prism.highlightElement(document.querySelector('#output'));
                    });
                } catch (error) {
                    console.error('Beautification error:', error);
                    this.output = 'Error: Invalid code format';
                }
            },
            async copyOutput() {
                if (!this.output || this.isCopying) return;

                this.isCopying = true;
                this.copyButtonText = 'Copied!';

                try {
                    await navigator.clipboard.writeText(this.output);
                    setTimeout(() => {
                        this.copyButtonText = 'Copy to Clipboard';
                        this.isCopying = false;
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy:', err);
                    this.copyButtonText = 'Failed to copy';
                    setTimeout(() => {
                        this.copyButtonText = 'Copy to Clipboard';
                        this.isCopying = false;
                    }, 2000);
                }
            },
            downloadOutput() {
                if (!this.output) return;

                const extensions = {
                    'HTML': 'html',
                    'CSS': 'css',
                    'JavaScript': 'js',
                    'JSON': 'json'
                };

                const blob = new Blob([this.output], { type: 'text/plain' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `beautified.${extensions[this.selectedLanguage]}`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>