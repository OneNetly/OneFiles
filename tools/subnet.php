<?php
$pageTitle = 'Subnet Calculator - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">Subnet Calculator</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label class="block mb-2 font-bold text-lg">IP Address and Subnet Mask:</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input
                        type="text"
                        v-model="ipAddress"
                        class="p-3 border border-gray-300 rounded text-lg"
                        placeholder="IP Address (e.g., 192.168.1.0)"
                        @input="calculateSubnet"
                    >
                    <select 
                        v-model="subnetMask" 
                        class="p-3 border border-gray-300 rounded text-lg"
                        @change="calculateSubnet"
                    >
                        <option value="">Select CIDR</option>
                        <option v-for="i in 32" :key="i" :value="i">/{{ i }}</option>
                    </select>
                </div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded">
                {{ error }}
            </div>

            <!-- Results Section -->
            <div v-if="results" class="space-y-6">
                <!-- Network Information -->
                <div class="border rounded">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold">Network Information</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">Network Address:</div>
                            <div>{{ results.networkAddress }}</div>

                            <div class="text-gray-600">Broadcast Address:</div>
                            <div>{{ results.broadcastAddress }}</div>

                            <div class="text-gray-600">Network Mask:</div>
                            <div>{{ results.subnetMask }}</div>

                            <div class="text-gray-600">Wildcard Mask:</div>
                            <div>{{ results.wildcardMask }}</div>
                        </div>
                    </div>
                </div>

                <!-- Host Range -->
                <div class="border rounded">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold">Host Range</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">First Host:</div>
                            <div>{{ results.firstHost }}</div>

                            <div class="text-gray-600">Last Host:</div>
                            <div>{{ results.lastHost }}</div>

                            <div class="text-gray-600">Total Hosts:</div>
                            <div>{{ results.totalHosts }}</div>

                            <div class="text-gray-600">Usable Hosts:</div>
                            <div>{{ results.usableHosts }}</div>
                        </div>
                    </div>
                </div>

                <!-- Binary Information -->
                <div class="border rounded">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold">Binary Information</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">IP Address:</div>
                            <div class="font-mono">{{ results.ipBinary }}</div>

                            <div class="text-gray-600">Network Mask:</div>
                            <div class="font-mono">{{ results.maskBinary }}</div>

                            <div class="text-gray-600">Network Address:</div>
                            <div class="font-mono">{{ results.networkBinary }}</div>

                            <div class="text-gray-600">Broadcast Address:</div>
                            <div class="font-mono">{{ results.broadcastBinary }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                ipAddress: '',
                subnetMask: '',
                error: '',
                results: null
            }
        },
        methods: {
            calculateSubnet() {
                this.error = '';
                this.results = null;

                if (!this.ipAddress || !this.subnetMask) return;

                try {
                    // Validate IP address
                    if (!this.isValidIP(this.ipAddress)) {
                        throw new Error('Invalid IP address format');
                    }

                    const ip = this.ipAddress.split('.').map(Number);
                    const cidr = parseInt(this.subnetMask);
                    
                    // Calculate subnet mask
                    const mask = this.calculateMask(cidr);
                    const maskArr = this.maskToArray(mask);
                    
                    // Calculate network address
                    const network = ip.map((octet, i) => octet & maskArr[i]);
                    
                    // Calculate broadcast address
                    const wildcard = maskArr.map(octet => 255 - octet);
                    const broadcast = network.map((octet, i) => octet | wildcard[i]);
                    
                    // Calculate first and last host
                    const firstHost = [...network];
                    firstHost[3] = network[3] + (network[3] === broadcast[3] ? 0 : 1);
                    
                    const lastHost = [...broadcast];
                    lastHost[3] = broadcast[3] - (network[3] === broadcast[3] ? 0 : 1);
                    
                    // Calculate total hosts
                    const totalHosts = Math.pow(2, 32 - cidr);
                    const usableHosts = totalHosts > 2 ? totalHosts - 2 : totalHosts;

                    this.results = {
                        networkAddress: network.join('.'),
                        broadcastAddress: broadcast.join('.'),
                        subnetMask: maskArr.join('.'),
                        wildcardMask: wildcard.join('.'),
                        firstHost: firstHost.join('.'),
                        lastHost: lastHost.join('.'),
                        totalHosts: totalHosts.toLocaleString(),
                        usableHosts: usableHosts.toLocaleString(),
                        ipBinary: ip.map(n => n.toString(2).padStart(8, '0')).join('.'),
                        maskBinary: maskArr.map(n => n.toString(2).padStart(8, '0')).join('.'),
                        networkBinary: network.map(n => n.toString(2).padStart(8, '0')).join('.'),
                        broadcastBinary: broadcast.map(n => n.toString(2).padStart(8, '0')).join('.')
                    };
                } catch (err) {
                    this.error = err.message;
                }
            },
            isValidIP(ip) {
                const octets = ip.split('.');
                if (octets.length !== 4) return false;
                
                return octets.every(octet => {
                    const num = parseInt(octet);
                    return num >= 0 && num <= 255 && !isNaN(num);
                });
            },
            calculateMask(cidr) {
                return ((1n << 32n) - (1n << (32n - BigInt(cidr))));
            },
            maskToArray(mask) {
                return [
                    (mask >> 24n) & 255n,
                    (mask >> 16n) & 255n,
                    (mask >> 8n) & 255n,
                    mask & 255n
                ].map(Number);
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>