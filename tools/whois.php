<?php
$pageTitle = 'WHOIS Lookup - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">WHOIS Lookup</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="domain" class="block mb-2 font-bold text-lg">Domain Name:</label>
                <div class="flex space-x-4">
                    <input
                        type="text"
                        id="domain"
                        v-model="domain"
                        class="flex-1 p-3 border border-gray-300 rounded text-lg"
                        placeholder="Enter domain name (e.g., example.com)"
                        @keyup.enter="lookupWhois"
                    >
                    <button
                        @click="lookupWhois"
                        :disabled="!domain || isLoading"
                        :class="[
                            'px-6 py-3 rounded text-lg font-bold transition duration-300',
                            domain && !isLoading
                                ? 'bg-blue-600 hover:bg-blue-700 text-white'
                                : 'bg-gray-300 cursor-not-allowed text-gray-500'
                        ]"
                    >
                        <span v-if="!isLoading">Lookup</span>
                        <span v-else>Looking up...</span>
                    </button>
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
            <div v-if="whoisData" class="space-y-6">
                <!-- Domain Information -->
                <div class="border rounded divide-y">
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Domain Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">Domain Name:</div>
                            <div>{{ whoisData.domain_name }}</div>
                            
                            <div class="text-gray-600">Registrar:</div>
                            <div>{{ whoisData.registrar }}</div>
                            
                            <div class="text-gray-600">Creation Date:</div>
                            <div>{{ formatDate(whoisData.created_date) }}</div>
                            
                            <div class="text-gray-600">Expiration Date:</div>
                            <div>{{ formatDate(whoisData.expiry_date) }}</div>
                            
                            <div class="text-gray-600">Updated Date:</div>
                            <div>{{ formatDate(whoisData.updated_date) }}</div>
                        </div>
                    </div>

                    <!-- Domain Status -->
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Domain Status</h3>
                        <ul v-if="whoisData.status && whoisData.status.length" class="space-y-1">
                            <li v-for="status in whoisData.status" :key="status" class="text-gray-600">
                                {{ status }}
                            </li>
                        </ul>
                        <div v-else class="text-gray-500">
                            No status information available
                        </div>
                    </div>

                    <!-- Registrant Information -->
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Registrant Information</h3>
                        <div v-if="hasRegistrantInfo" class="grid grid-cols-2 gap-4">
                            <div class="text-gray-600">Name:</div>
                            <div>{{ whoisData.registrant.name || 'Not available' }}</div>
                            
                            <div class="text-gray-600">Organization:</div>
                            <div>{{ whoisData.registrant.organization || 'Not available' }}</div>
                            
                            <div class="text-gray-600">Country:</div>
                            <div>{{ whoisData.registrant.country || 'Not available' }}</div>
                        </div>
                        <div v-else class="text-gray-500">
                            Registrant information is private or not available
                        </div>
                    </div>

                    <!-- Name Servers -->
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Name Servers</h3>
                        <ul v-if="whoisData.nameservers && whoisData.nameservers.length" class="space-y-1">
                            <li v-for="ns in whoisData.nameservers" :key="ns" class="text-gray-600">
                                {{ ns }}
                            </li>
                        </ul>
                        <div v-else class="text-gray-500">
                            No name servers found
                        </div>
                    </div>

                    <!-- Raw WHOIS Data -->
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Raw WHOIS Data</h3>
                        <pre class="bg-gray-50 p-4 rounded text-sm overflow-x-auto whitespace-pre-wrap">{{ whoisData.raw }}</pre>
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
                domain: '',
                isLoading: false,
                error: '',
                whoisData: null
            }
        },
        computed: {
            hasRegistrantInfo() {
                const r = this.whoisData?.registrant;
                return r && (r.name || r.organization || r.country);
            }
        },
        methods: {
            async lookupWhois() {
                if (!this.domain || this.isLoading) return;

                this.isLoading = true;
                this.error = '';
                this.whoisData = null;

                try {
                    const response = await fetch('/api/whois.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ domain: this.domain })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.error || 'Failed to lookup WHOIS information');
                    }

                    this.whoisData = data;
                } catch (err) {
                    console.error('WHOIS lookup failed:', err);
                    this.error = err.message;
                } finally {
                    this.isLoading = false;
                }
            },
            formatDate(dateString) {
                if (!dateString) return 'Not available';
                try {
                    return new Date(dateString).toLocaleDateString(undefined, {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (err) {
                    return dateString; // Return original string if parsing fails
                }
            }
        }
    }).mount('#app')
    </script>

    <?php require_once '../footer.php'; ?>
</body>
</html>