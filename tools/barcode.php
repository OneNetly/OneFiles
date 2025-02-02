<?php

$pageTitle = 'Barcode Generator - OneNetly';
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="../css/output.css" rel="stylesheet">
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
    <!-- Add JsBarcode library -->
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once '../nav.php'; ?>
    
    <div id="app" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white p-6 rounded-lg shadow">
            <h1 class="text-2xl font-bold mb-6">Barcode Generator</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="input" class="block mb-2 font-bold text-lg">Text/Number:</label>
                <input
                    type="text"
                    id="input"
                    v-model="input"
                    class="w-full p-3 border border-gray-300 rounded text-lg"
                    placeholder="Enter text or numbers..."
                    @input="generateBarcode"
                >
            </div>

            <!-- Options Section -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Format -->
                <div>
                    <label class="block mb-2">Format:</label>
                    <select 
                        v-model="format"
                        class="w-full p-2 border border-gray-300 rounded"
                        @change="generateBarcode"
                    >
                        <option v-for="fmt in formats" :key="fmt" :value="fmt">{{ fmt }}</option>
                    </select>
                </div>

                <!-- Width -->
                <div>
                    <label class="block mb-2">Width:</label>
                    <input 
                        type="number" 
                        v-model="width"
                        min="1" 
                        max="4"
                        step="0.5"
                        class="w-full p-2 border border-gray-300 rounded"
                        @input="generateBarcode"
                    >
                </div>

                <!-- Height -->
                <div>
                    <label class="block mb-2">Height:</label>
                    <input 
                        type="number" 
                        v-model="height"
                        min="10" 
                        max="150"
                        step="10"
                        class="w-full p-2 border border-gray-300 rounded"
                        @input="generateBarcode"
                    >
                </div>
            </div>

            <!-- Color Options -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Line Color -->
                <div>
                    <label class="block mb-2">Line Color:</label>
                    <input 
                        type="color" 
                        v-model="lineColor"
                        class="w-full p-1 border border-gray-300 rounded h-10"
                        @input="generateBarcode"
                    >
                </div>

                <!-- Background -->
                <div>
                    <label class="block mb-2">Background:</label>
                    <input 
                        type="color" 
                        v-model="background"
                        class="w-full p-1 border border-gray-300 rounded h-10"
                        @input="generateBarcode"
                    >
                </div>
            </div>

            <!-- Display Options -->
            <div class="mb-6 flex flex-wrap gap-4">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        v-model="displayValue"
                        class="h-4 w-4 text-blue-600"
                        @change="generateBarcode"
                    >
                    <span class="ml-2">Show Text</span>
                </label>
            </div>

            <!-- Barcode Output -->
            <div class="mb-6" v-if="input">
                <label class="block mb-2 font-bold text-lg">Generated Barcode:</label>
                <div class="flex justify-center bg-gray-50 p-4 rounded-lg">
                    <svg id="barcode"></svg>
                </div>
            </div>

            <!-- Download Button -->
            <button
                @click="downloadBarcode"
                v-if="input"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded text-lg transition duration-300"
            >
                Download Barcode
            </button>
        </div>
    </div>

    <script>
    const { createApp } = Vue
    
    createApp({
        data() {
            return {
                input: '',
                format: 'CODE128',
                width: 2,
                height: 100,
                lineColor: '#000000',
                background: '#ffffff',
                displayValue: true,
                formats: [
                    'CODE128',
                    'EAN13',
                    'EAN8',
                    'EAN5',
                    'EAN2',
                    'UPC',
                    'CODE39',
                    'ITF14',
                    'MSI',
                    'pharmacode',
                    'codabar'
                ]
            }
        },
        watch: {
            input(newVal) {
                if (newVal) {
                    this.$nextTick(() => {
                        this.generateBarcode();
                    });
                }
            },
            format() { this.generateBarcode(); },
            width() { this.generateBarcode(); },
            height() { this.generateBarcode(); },
            lineColor() { this.generateBarcode(); },
            background() { this.generateBarcode(); },
            displayValue() { this.generateBarcode(); }
        },
        methods: {
            generateBarcode() {
                try {
                    JsBarcode("#barcode", this.input, {
                        format: this.format,
                        width: this.width,
                        height: this.height,
                        displayValue: this.displayValue,
                        lineColor: this.lineColor,
                        background: this.background,
                        margin: 10,
                        font: 'Arial',
                        fontSize: 20,
                        textAlign: 'center',
                        textPosition: 'bottom'
                    });
                } catch (error) {
                    console.error('Barcode generation error:', error);
                }
            },
            downloadBarcode() {
                const svg = document.getElementById('barcode');
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const svgData = new XMLSerializer().serializeToString(svg);
                const img = new Image();
                
                img.onload = () => {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.fillStyle = this.background;
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    ctx.drawImage(img, 0, 0);
                    
                    const a = document.createElement('a');
                    a.download = `barcode-${this.input}.png`;
                    a.href = canvas.toDataURL('image/png');
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                };
                
                img.src = 'data:image/svg+xml;base64,' + btoa(svgData);
            },
            validateInput() {
                switch(this.format) {
                    case 'EAN13':
                        return this.input.length === 13 && !isNaN(this.input);
                    case 'EAN8':
                        return this.input.length === 8 && !isNaN(this.input);
                    case 'EAN5':
                        return this.input.length === 5 && !isNaN(this.input);
                    case 'EAN2':
                        return this.input.length === 2 && !isNaN(this.input);
                    case 'UPC':
                        return this.input.length === 12 && !isNaN(this.input);
                    case 'CODE39':
                        return /^[A-Z0-9\-\.\ \$\/\+\%]+$/i.test(this.input);
                    case 'CODE128':
                        return this.input.length > 0;
                    case 'ITF14':
                        return this.input.length === 14 && !isNaN(this.input);
                    case 'MSI':
                        return !isNaN(this.input);
                    case 'pharmacode':
                        return !isNaN(this.input) && parseInt(this.input) > 0;
                    case 'codabar':
                        return /^[A-D][0-9\-\$\:\/\.\+]+[A-D]$/i.test(this.input);
                    default:
                        return true;
                }
            }
        },
        computed: {
            isValid() {
                return this.validateInput();
            },
            errorMessage() {
                if (!this.input) return '';
                if (!this.isValid) {
                    switch(this.format) {
                        case 'EAN13':
                            return 'Must be 13 digits';
                        case 'EAN8':
                            return 'Must be 8 digits';
                        case 'EAN5':
                            return 'Must be 5 digits';
                        case 'EAN2':
                            return 'Must be 2 digits';
                        case 'UPC':
                            return 'Must be 12 digits';
                        case 'CODE39':
                            return 'Invalid characters';
                        case 'ITF14':
                            return 'Must be 14 digits';
                        case 'MSI':
                            return 'Must be numbers only';
                        case 'pharmacode':
                            return 'Must be positive number';
                        case 'codabar':
                            return 'Invalid format';
                        default:
                            return 'Invalid input';
                    }
                }
                return '';
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>