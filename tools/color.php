<?php
$pageTitle = 'Color Converter - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">Color Converter</h1>

            <!-- Input Type Selector -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Input Format:</label>
                <div class="flex flex-wrap gap-2">
                    <button 
                        v-for="format in formats"
                        :key="format"
                        @click="inputFormat = format"
                        :class="[
                            'px-4 py-2 rounded-md',
                            inputFormat === format ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700'
                        ]"
                    >{{ format }}</button>
                </div>
            </div>

            <!-- Color Input -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Color Input:</label>
                <div class="flex items-center space-x-4">
                    <input 
                        type="text" 
                        v-model="colorInput"
                        class="flex-1 p-3 border border-gray-300 rounded text-lg font-mono"
                        :placeholder="getPlaceholder()"
                    >
                    <input 
                        type="color" 
                        v-model="colorPicker"
                        class="h-12 w-16 cursor-pointer"
                        v-if="inputFormat === 'HEX'"
                    >
                </div>
                <div v-if="error" class="mt-2 text-red-600">{{ error }}</div>
            </div>

            <!-- Color Preview -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Color Preview:</label>
                <div 
                    class="h-16 w-full rounded border border-gray-300"
                    :style="{ backgroundColor: previewColor }"
                ></div>
            </div>

            <!-- Color Outputs -->
            <div class="mb-6 space-y-4">
                <label class="block mb-2 font-bold text-lg">Color Values:</label>
                <div v-for="format in formats" :key="format" class="flex items-center space-x-4">
                    <span class="w-20 font-medium">{{ format }}:</span>
                    <input 
                        type="text" 
                        :value="getOutputValue(format)"
                        class="flex-1 p-2 border border-gray-300 rounded font-mono bg-gray-50"
                        readonly
                    >
                    <button
                        @click="copyValue(format)"
                        :disabled="!getOutputValue(format)"
                        :class="[
                            'px-4 py-2 rounded transition duration-300',
                            getOutputValue(format) 
                                ? 'bg-blue-600 text-white hover:bg-blue-700' 
                                : 'bg-gray-300 cursor-not-allowed text-gray-500'
                        ]"
                    >Copy</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                formats: ['HEX', 'RGB', 'HSL'],
                inputFormat: 'HEX',
                colorInput: '',
                colorPicker: '#000000',
                error: '',
                previewColor: 'transparent',
                copyStatus: {}
            }
        },
        methods: {
            getPlaceholder() {
                switch(this.inputFormat) {
                    case 'HEX': return '#FF0000 or #F00'
                    case 'RGB': return 'rgb(255, 0, 0) or 255, 0, 0'
                    case 'HSL': return 'hsl(0, 100%, 50%) or 0, 100%, 50%'
                }
            },
            updateColor() {
                try {
                    let color = this.colorInput.trim()
                    if (!color) {
                        this.error = ''
                        this.previewColor = 'transparent'
                        return
                    }

                    switch(this.inputFormat) {
                        case 'HEX':
                            if (color.startsWith('#')) {
                                color = color.substring(1)
                            }
                            if (!/^([0-9A-F]{3}|[0-9A-F]{6})$/i.test(color)) {
                                throw new Error('Invalid HEX color')
                            }
                            if (color.length === 3) {
                                color = color.split('').map(c => c + c).join('')
                            }
                            this.previewColor = '#' + color
                            break

                        case 'RGB':
                            let rgbValues = color.replace(/[rgb()]/g, '').split(',').map(x => parseInt(x.trim()))
                            if (rgbValues.length !== 3 || rgbValues.some(x => isNaN(x) || x < 0 || x > 255)) {
                                throw new Error('Invalid RGB color')
                            }
                            this.previewColor = `rgb(${rgbValues.join(', ')})`
                            break

                        case 'HSL':
                            let hslValues = color.replace(/[hsl()%]/g, '').split(',').map(x => parseFloat(x.trim()))
                            if (hslValues.length !== 3 || 
                                isNaN(hslValues[0]) || hslValues[0] < 0 || hslValues[0] > 360 ||
                                isNaN(hslValues[1]) || hslValues[1] < 0 || hslValues[1] > 100 ||
                                isNaN(hslValues[2]) || hslValues[2] < 0 || hslValues[2] > 100) {
                                throw new Error('Invalid HSL color')
                            }
                            this.previewColor = `hsl(${hslValues[0]}, ${hslValues[1]}%, ${hslValues[2]}%)`
                            break
                    }
                    this.error = ''
                } catch (err) {
                    this.error = err.message
                    this.previewColor = 'transparent'
                }
            },
            hexToRgb(hex) {
                const r = parseInt(hex.slice(1, 3), 16)
                const g = parseInt(hex.slice(3, 5), 16)
                const b = parseInt(hex.slice(5, 7), 16)
                return [r, g, b]
            },
            rgbToHex(r, g, b) {
                return '#' + [r, g, b].map(x => {
                    const hex = x.toString(16)
                    return hex.length === 1 ? '0' + hex : hex
                }).join('').toUpperCase()
            },
            rgbToHsl(r, g, b) {
                r /= 255
                g /= 255
                b /= 255
                const max = Math.max(r, g, b)
                const min = Math.min(r, g, b)
                let h, s, l = (max + min) / 2

                if (max === min) {
                    h = s = 0
                } else {
                    const d = max - min
                    s = l > 0.5 ? d / (2 - max - min) : d / (max + min)
                    switch(max) {
                        case r: h = (g - b) / d + (g < b ? 6 : 0); break
                        case g: h = (b - r) / d + 2; break
                        case b: h = (r - g) / d + 4; break
                    }
                    h /= 6
                }

                return [
                    Math.round(h * 360),
                    Math.round(s * 100),
                    Math.round(l * 100)
                ]
            },
            getOutputValue(format) {
                if (!this.previewColor || this.previewColor === 'transparent') {
                    return ''
                }

                try {
                    let rgb
                    if (this.previewColor.startsWith('#')) {
                        rgb = this.hexToRgb(this.previewColor)
                    } else if (this.previewColor.startsWith('rgb')) {
                        rgb = this.previewColor.replace(/[rgb()]/g, '').split(',').map(x => parseInt(x.trim()))
                    } else if (this.previewColor.startsWith('hsl')) {
                        return this.previewColor
                    }

                    switch(format) {
                        case 'HEX':
                            return this.rgbToHex(...rgb)
                        case 'RGB':
                            return `rgb(${rgb.join(', ')})`
                        case 'HSL':
                            const hsl = this.rgbToHsl(...rgb)
                            return `hsl(${hsl[0]}, ${hsl[1]}%, ${hsl[2]}%)`
                    }
                } catch (err) {
                    return ''
                }
            },
            async copyValue(format) {
                const value = this.getOutputValue(format)
                if (!value) return

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(value)
                    } else {
                        const textArea = document.createElement('textarea')
                        textArea.value = value
                        textArea.style.position = 'fixed'
                        textArea.style.left = '-999999px'
                        document.body.appendChild(textArea)
                        textArea.focus()
                        textArea.select()
                        
                        try {
                            document.execCommand('copy')
                            textArea.remove()
                        } catch (err) {
                            textArea.remove()
                            throw new Error('Failed to copy')
                        }
                    }

                    // Visual feedback
                    this.copyStatus[format] = true
                    const button = event.target
                    const originalText = button.textContent
                    button.textContent = 'Copied!'
                    
                    setTimeout(() => {
                        button.textContent = originalText
                        this.copyStatus[format] = false
                    }, 2000)

                } catch (err) {
                    console.error('Failed to copy:', err)
                    const button = event.target
                    const originalText = button.textContent
                    button.textContent = 'Failed!'
                    
                    setTimeout(() => {
                        button.textContent = originalText
                    }, 2000)
                }
            }
        },
        watch: {
            colorInput() {
                this.updateColor()
            },
            colorPicker() {
                if (this.inputFormat === 'HEX') {
                    this.colorInput = this.colorPicker.toUpperCase()
                }
            },
            inputFormat() {
                this.colorInput = ''
                this.error = ''
                this.previewColor = 'transparent'
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>