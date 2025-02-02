<?php
$pageTitle = 'Password Generator - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">Password Generator</h1>

            <!-- Options Section -->
            <div class="mb-6 space-y-4">
                <div class="flex items-center justify-between">
                    <label class="text-gray-700">Password Length: {{ length }}</label>
                    <input 
                        type="range" 
                        v-model="length" 
                        min="8" 
                        max="64" 
                        class="w-1/2"
                    >
                </div>

                <div class="space-y-2">
                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="uppercase" 
                            v-model="useUppercase"
                            class="h-4 w-4 text-blue-600"
                        >
                        <label for="uppercase" class="ml-2 text-gray-700">Include Uppercase Letters</label>
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="lowercase" 
                            v-model="useLowercase"
                            class="h-4 w-4 text-blue-600"
                        >
                        <label for="lowercase" class="ml-2 text-gray-700">Include Lowercase Letters</label>
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="numbers" 
                            v-model="useNumbers"
                            class="h-4 w-4 text-blue-600"
                        >
                        <label for="numbers" class="ml-2 text-gray-700">Include Numbers</label>
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="symbols" 
                            v-model="useSymbols"
                            class="h-4 w-4 text-blue-600"
                        >
                        <label for="symbols" class="ml-2 text-gray-700">Include Symbols</label>
                    </div>
                </div>
            </div>

            <!-- Generated Password Section -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Generated Password:</label>
                <div class="relative">
                    <input
                        type="text"
                        v-model="generatedPassword"
                        class="w-full p-3 border border-gray-300 rounded text-lg font-mono bg-gray-50"
                        readonly
                    >
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-4">
                <button
                    @click="generatePassword"
                    class="bg-blue-600 text-white px-6 py-3 rounded font-bold hover:bg-blue-700 transition duration-300"
                >
                    Generate New Password
                </button>

                <button
                    @click="copyPassword"
                    :disabled="!generatedPassword || isCopying"
                    :class="[
                        'font-bold py-3 px-6 rounded transition duration-300',
                        generatedPassword && !isCopying 
                            ? 'bg-green-600 hover:bg-green-700 text-white' 
                            : 'bg-gray-300 cursor-not-allowed text-gray-500'
                    ]"
                >
                    {{ copyButtonText }}
                </button>
            </div>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                length: 16,
                useUppercase: true,
                useLowercase: true,
                useNumbers: true,
                useSymbols: true,
                generatedPassword: '',
                copyButtonText: 'Copy to Clipboard',
                isCopying: false
            }
        },
        methods: {
            generatePassword() {
                const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                const lowercase = 'abcdefghijklmnopqrstuvwxyz';
                const numbers = '0123456789';
                const symbols = '!@#$%^&*()_+-=[]{}|;:,.<>?';

                let chars = '';
                if (this.useUppercase) chars += uppercase;
                if (this.useLowercase) chars += lowercase;
                if (this.useNumbers) chars += numbers;
                if (this.useSymbols) chars += symbols;

                if (!chars) {
                    this.generatedPassword = '';
                    return;
                }

                let password = '';
                for (let i = 0; i < this.length; i++) {
                    const randomIndex = Math.floor(Math.random() * chars.length);
                    password += chars[randomIndex];
                }

                this.generatedPassword = password;
            },
            async copyPassword() {
                if (this.isCopying || !this.generatedPassword) return;
                
                this.isCopying = true;
                
                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(this.generatedPassword);
                    } else {
                        const textArea = document.createElement('textarea');
                        textArea.value = this.generatedPassword;
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
        mounted() {
            this.generatePassword();
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>