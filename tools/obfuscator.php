<?php
$pageTitle = 'PHP Obfuscator - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">PHP Obfuscator</h1>

            <!-- Obfuscation Level -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Obfuscation Level:</label>
                <div class="flex flex-wrap gap-2">
                    <button 
                        v-for="level in ['Basic', 'Medium', 'High']"
                        :key="level"
                        @click="setObfuscationLevel(level)"
                        :class="[
                            'px-4 py-2 rounded-md',
                            obfuscationLevel === level ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >{{ level }}</button>
                </div>
            </div>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">PHP Code:</label>
                <textarea
                    id="input"
                    v-model="input"
                    rows="10"
                    class="w-full p-3 border border-gray-300 rounded text-lg font-mono"
                    placeholder="Enter PHP code to obfuscate..."
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
                obfuscationLevel: 'Basic',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false
            }
        },
        methods: {
            setObfuscationLevel(level) {
                this.obfuscationLevel = level;
                this.obfuscateCode();
            },
            obfuscateCode() {
                if (!this.input.trim()) {
                    this.output = '';
                    return;
                }

                let code = this.input;

                // Basic obfuscation
                code = this.basicObfuscation(code);

                // Additional obfuscation based on level
                if (this.obfuscationLevel === 'Medium' || this.obfuscationLevel === 'High') {
                    code = this.mediumObfuscation(code);
                }

                if (this.obfuscationLevel === 'High') {
                    code = this.highObfuscation(code);
                }

                this.output = code;
            },
            basicObfuscation(code) {
                // Remove comments and whitespace
                code = code.replace(/\/\*[\s\S]*?\*\/|\/\/.*/g, '');
                code = code.replace(/\s+/g, ' ');
                
                // Basic variable name obfuscation
                let varCount = 0;
                const varMap = new Map();
                
                code = code.replace(/\$[a-zA-Z_]\w*/g, match => {
                    if (!varMap.has(match)) {
                        varMap.set(match, `$v${varCount++}`);
                    }
                    return varMap.get(match);
                });

                return code;
            },
            mediumObfuscation(code) {
                // Encode strings
                code = code.replace(/'([^']*)'/g, (match, p1) => {
                    return "base64_decode('" + btoa(p1) + "')";
                });
                
                // Add random spaces
                code = code.replace(/([{}()[\],;])/g, '$1 ');
                
                return code;
            },
            highObfuscation(code) {
                // Convert to eval version
                code = `eval(base64_decode('${btoa(code)}'));`;
                
                // Add fake variables and code
                const fakeCode = [
                    '$_x = "' + Math.random().toString(36).substring(7) + '";',
                    '$_y = array(' + Array.from({length: 5}, () => Math.random()).join(',') + ');',
                    '$_z = function(){};'
                ].join('\n');
                
                return fakeCode + '\n' + code;
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
                
                const blob = new Blob([this.output], {type: 'text/plain'});
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'obfuscated.php';
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