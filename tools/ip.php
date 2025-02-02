<?php
$pageTitle = 'IP Address Lookup - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">IP Address Lookup</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="ip" class="block mb-2 font-bold text-lg">IP Address:</label>
                <div class="flex space-x-4">
                    <input
                        type="text"
                        id="ip"
                        v-model="ip"
                        class="flex-1 p-3 border border-gray-300 rounded text-lg"
                        :placeholder="userIp ? 'Your IP: ' + userIp : 'Enter IP address (e.g., 8.8.8.8)'"
                        @keyup.enter="lookupIP()"
                    >
                    <button
                        @click="lookupIP()"
                        :disabled="!ip || isLoading"
                        :class="[
                            'px-6 py-3 rounded text-lg font-bold transition duration-300',
                            ip && !isLoading
                                ? 'bg-blue-600 hover:bg-blue-700 text-white'
                                : 'bg-gray-300 cursor-not-allowed text-gray-500'
                        ]"
                    >
                        <span v-if="!isLoading">Lookup</span>
                        <span v-else>Looking up...</span>
                    </button>
                </div>
                <div class="mt-2 text-sm text-gray-500" v-if="userIp && ip !== userIp">
                    <a href="#" class="text-blue-600 hover:text-blue-800" @click.prevent="ip = userIp; lookupIP()">
                        Show my IP details again
                    </a>
                </div>
            </div>

            <!-- Loading State -->
            <div v-if="isLoading" class="mb-6 text-center">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent"></div>
            </div>

            <!-- Error Message -->
            <div v-if="error" class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded">
                {{ error }}
            </div>

            <!-- Results Section -->
            <div v-if="ipData" class="space-y-6">
                <!-- IP Information -->
                <div class="border rounded divide-y">
                    <div class="p-4">
                        <h3 class="font-bold mb-2">IP Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">IP Address:</div>
                            <div>{{ ipData.ip }}</div>
                            
                            <div class="text-gray-600">Type:</div>
                            <div>{{ ipData.type }}</div>
                            
                            <div class="text-gray-600">Continent:</div>
                            <div>{{ ipData.continent_name }} ({{ ipData.continent_code }})</div>
                            
                            <div class="text-gray-600">Country:</div>
                            <div>{{ ipData.country_name }} ({{ ipData.country_code }})</div>
                            
                            <div class="text-gray-600">Region:</div>
                            <div>{{ ipData.region_name }}</div>
                            
                            <div class="text-gray-600">City:</div>
                            <div>{{ ipData.city }}</div>
                            
                            <div class="text-gray-600">Zip Code:</div>
                            <div>{{ ipData.zip }}</div>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Location</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">Latitude:</div>
                            <div>{{ ipData.latitude }}</div>
                            
                            <div class="text-gray-600">Longitude:</div>
                            <div>{{ ipData.longitude }}</div>
                            
                            <div class="text-gray-600">Time Zone:</div>
                            <div>{{ ipData.timezone_id }}</div>
                        </div>
                    </div>

                    <!-- Connection Information -->
                    <div v-if="ipData.connection" class="p-4">
                        <h3 class="font-bold mb-2">Connection Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">ASN:</div>
                            <div>{{ ipData.connection.asn }}</div>
                            
                            <div class="text-gray-600">ISP:</div>
                            <div>{{ ipData.connection.isp }}</div>
                            
                            <div class="text-gray-600">Organization:</div>
                            <div>{{ ipData.connection.organization }}</div>
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
                ip: '',
                isLoading: false,
                error: '',
                ipData: null,
                userIp: ''
            }
        },
        async mounted() {
            // Get user's IP address on component mount
            try {
                const response = await fetch('https://api.ipify.org?format=json');
                const data = await response.json();
                this.userIp = data.ip;
                // Lookup the user's IP details automatically
                this.ip = this.userIp;
                await this.lookupIP();
            } catch (err) {
                console.error('Failed to get user IP:', err);
                this.error = 'Failed to detect your IP address';
            }
        },
        methods: {
            async lookupIP(customIp) {
                if (this.isLoading) return;
                
                const ipToLookup = customIp || this.ip;
                if (!ipToLookup) return;
    
                this.isLoading = true;
                this.error = '';
                this.ipData = null;
    
                try {
                    const response = await fetch('/api/ip.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ ip: ipToLookup })
                    });
    
                    const data = await response.json();
    
                    if (!response.ok) {
                        throw new Error(data.error || 'Failed to lookup IP information');
                    }
    
                    this.ipData = data;
                } catch (err) {
                    console.error('IP lookup failed:', err);
                    this.error = err.message;
                } finally {
                    this.isLoading = false;
                }
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>