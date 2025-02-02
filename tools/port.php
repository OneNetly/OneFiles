<?php
$pageTitle = 'Port Scanner - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">Port Scanner</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="host" class="block mb-2 font-bold text-lg">Host:</label>
                <div class="flex space-x-4">
                    <input
                        type="text"
                        id="host"
                        v-model="host"
                        class="flex-1 p-3 border border-gray-300 rounded text-lg"
                        placeholder="Enter hostname or IP (e.g., example.com or 8.8.8.8)"
                        @keyup.enter="scanPorts"
                    >
                    <button
                        @click="scanPorts"
                        :disabled="!host || isScanning"
                        :class="[
                            'px-6 py-3 rounded text-lg font-bold transition duration-300',
                            host && !isScanning
                                ? 'bg-blue-600 hover:bg-blue-700 text-white'
                                : 'bg-gray-300 cursor-not-allowed text-gray-500'
                        ]"
                    >
                        <span v-if="!isScanning">Scan</span>
                        <span v-else>Scanning...</span>
                    </button>
                </div>
            </div>

            <!-- Port Range Selection -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">Port Range:</label>
                <div class="flex items-center space-x-4">
                    <select v-model="portRange" class="p-2 border rounded">
                        <option value="common">Common Ports</option>
                        <option value="well-known">Well-known Ports (0-1023)</option>
                        <option value="custom">Custom Range</option>
                    </select>
                    <div v-if="portRange === 'custom'" class="flex items-center space-x-2">
                        <input
                            type="number"
                            v-model="customRange.start"
                            class="w-24 p-2 border rounded"
                            min="1"
                            max="65535"
                            placeholder="Start"
                        >
                        <span>-</span>
                        <input
                            type="number"
                            v-model="customRange.end"
                            class="w-24 p-2 border rounded"
                            min="1"
                            max="65535"
                            placeholder="End"
                        >
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isScanning" class="mb-6">
                <div class="flex items-center justify-center space-x-3">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent"></div>
                    <span class="text-gray-600">Scanning port {{ currentPort }}...</span>
                </div>
                <div class="mt-2 bg-gray-100 rounded-full overflow-hidden">
                    <div
                        class="bg-blue-600 h-2 transition-all duration-300"
                        :style="{ width: progress + '%' }"
                    ></div>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded">
                {{ error }}
            </div>

            <!-- Results Section -->
            <div v-if="results.length" class="space-y-6">
                <div class="border rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Port</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Service</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="result in results" :key="result.port">
                                <td class="px-6 py-4 whitespace-nowrap">{{ result.port }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        :class="[
                                            'px-2 py-1 text-sm rounded-full',
                                            result.status === 'open' 
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-red-100 text-red-800'
                                        ]"
                                    >
                                        {{ result.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ result.service }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                host: '',
                isScanning: false,
                error: '',
                results: [],
                portRange: 'common',
                customRange: {
                    start: 1,
                    end: 1024
                },
                currentPort: 0,
                progress: 0,
                commonPorts: [
                    20, 21, 22, 23, 25, 53, 80, 110, 123, 143, 161, 194, 443, 465, 
                    587, 993, 995, 1433, 1521, 3306, 3389, 5432, 5900, 8080
                ]
            }
        },
        methods: {
            async scanPorts() {
                if (!this.host || this.isScanning) return;

                this.isScanning = true;
                this.error = '';
                this.results = [];
                let ports = [];

                // Determine ports to scan
                switch (this.portRange) {
                    case 'common':
                        ports = this.commonPorts;
                        break;
                    case 'well-known':
                        ports = Array.from({length: 1024}, (_, i) => i);
                        break;
                    case 'custom':
                        if (!this.isValidCustomRange) {
                            this.error = 'Invalid port range';
                            this.isScanning = false;
                            return;
                        }
                        ports = Array.from(
                            {length: this.customRange.end - this.customRange.start + 1},
                            (_, i) => i + parseInt(this.customRange.start)
                        );
                        break;
                }

                const totalPorts = ports.length;

                for (let i = 0; i < ports.length; i++) {
                    const port = ports[i];
                    this.currentPort = port;
                    this.progress = Math.round((i / totalPorts) * 100);

                    try {
                        const response = await fetch('/api/port-scan.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ host: this.host, port: port })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.error || 'Scan failed');
                        }

                        this.results.push({
                            port: port,
                            status: data.status,
                            service: data.service || 'Unknown'
                        });
                    } catch (err) {
                        console.error(`Scan failed for port ${port}:`, err);
                    }
                }

                this.progress = 100;
                this.isScanning = false;
            }
        },
        computed: {
            isValidCustomRange() {
                const start = parseInt(this.customRange.start);
                const end = parseInt(this.customRange.end);
                return start > 0 && end <= 65535 && start <= end;
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>