<?php

$pageTitle = 'API Tester - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">API Tester</h1>

            <!-- Request Form -->
            <div class="mb-6">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block mb-2 font-bold">Method</label>
                        <select v-model="method" class="w-full p-2 border rounded">
                            <option>GET</option>
                            <option>POST</option>
                            <option>PUT</option>
                            <option>DELETE</option>
                            <option>PATCH</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 font-bold">URL</label>
                        <input type="text" v-model="url" class="w-full p-2 border rounded" 
                               placeholder="https://api.example.com/endpoint">
                    </div>
                </div>

                <!-- Headers -->
                <div class="mb-4">
                    <label class="block mb-2 font-bold">Headers</label>
                    <div v-for="(header, index) in headers" :key="index" class="flex gap-2 mb-2">
                        <input type="text" v-model="header.key" placeholder="Key" 
                               class="w-1/2 p-2 border rounded">
                        <input type="text" v-model="header.value" placeholder="Value" 
                               class="w-1/2 p-2 border rounded">
                        <button @click="removeHeader(index)" class="text-red-500">✕</button>
                    </div>
                    <button @click="addHeader" class="text-blue-600">+ Add Header</button>
                </div>

                <!-- Request Body -->
                <div class="mb-4">
                    <label class="block mb-2 font-bold">Request Body</label>
                    <textarea v-model="body" rows="5" class="w-full p-2 border rounded font-mono"
                              placeholder="Enter request body (JSON)"></textarea>
                </div>

                <button @click="sendRequest" 
                        class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                    Send Request
                </button>
            </div>

            <!-- Response Section -->
            <div v-if="response" class="mt-8">
                <h2 class="text-xl font-bold mb-4">Response</h2>
                <div class="mb-2">Status: <span :class="responseStatusClass">{{ response.status }}</span></div>
                <pre class="bg-gray-50 p-4 rounded font-mono overflow-x-auto">{{ formattedResponse }}</pre>
            </div>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                method: 'GET',
                url: '',
                headers: [{ key: '', value: '' }],
                body: '',
                response: null,
                loading: false
            }
        },
        computed: {
            formattedResponse() {
                if (!this.response?.data) return ''
                try {
                    return JSON.stringify(this.response.data, null, 2)
                } catch {
                    return this.response.data
                }
            },
            responseStatusClass() {
                if (!this.response) return ''
                return {
                    'text-green-600': this.response.status >= 200 && this.response.status < 300,
                    'text-yellow-600': this.response.status >= 300 && this.response.status < 400,
                    'text-red-600': this.response.status >= 400
                }
            }
        },
        methods: {
            addHeader() {
                this.headers.push({ key: '', value: '' })
            },
            removeHeader(index) {
                this.headers.splice(index, 1)
            },
            async sendRequest() {
                this.loading = true
                this.response = null
                
                try {
                    const headers = {}
                    this.headers.forEach(h => {
                        if (h.key && h.value) headers[h.key] = h.value
                    })

                    const response = await fetch(this.url, {
                        method: this.method,
                        headers: {
                            'Content-Type': 'application/json',
                            ...headers
                        },
                        body: this.method !== 'GET' ? this.body : undefined
                    })

                    const data = await response.text()
                    this.response = {
                        status: response.status,
                        data: this.tryParseJSON(data)
                    }
                } catch (error) {
                    this.response = {
                        status: 'Error',
                        data: error.message
                    }
                } finally {
                    this.loading = false
                }
            },
            tryParseJSON(text) {
                try {
                    return JSON.parse(text)
                } catch {
                    return text
                }
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>