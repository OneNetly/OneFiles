<?php
$pageTitle = 'DNS Lookup - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">DNS Lookup</h1>

            <!-- Input Section -->
            <div class="mb-6">
                <label for="domain" class="block mb-2 font-bold text-lg">Domain Name:</label>
                <div class="flex space-x-4">
                    <input
                        type="text"
                        id="domain"
                        v-model="domain"
                        class="flex-1 p-3 border border-gray-300 rounded text-lg"
                        placeholder="Enter domain (e.g., example.com, https://example.com, www.example.com)"
                        @keyup.enter="lookupDNS"
                    >
                    <button
                        @click="lookupDNS"
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
            <div v-if="dnsData" class="space-y-6">
                <!-- A Records -->
                <div class="border rounded">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold">A Records (IPv4)</h3>
                    </div>
                    <div class="p-4">
                        <div v-if="dnsData.a && dnsData.a.length" class="space-y-2">
                            <div v-for="record in dnsData.a" :key="record" class="text-gray-600">
                                {{ record }}
                            </div>
                        </div>
                        <div v-else class="text-gray-500">No A records found</div>
                    </div>
                </div>

                <!-- AAAA Records -->
                <div class="border rounded">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold">AAAA Records (IPv6)</h3>
                    </div>
                    <div class="p-4">
                        <div v-if="dnsData.aaaa && dnsData.aaaa.length" class="space-y-2">
                            <div v-for="record in dnsData.aaaa" :key="record" class="text-gray-600">
                                {{ record }}
                            </div>
                        </div>
                        <div v-else class="text-gray-500">No AAAA records found</div>
                    </div>
                </div>

                <!-- MX Records -->
                <div class="border rounded">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold">MX Records</h3>
                    </div>
                    <div class="p-4">
                        <div v-if="dnsData.mx && dnsData.mx.length" class="space-y-2">
                            <div v-for="record in dnsData.mx" :key="record.host" class="text-gray-600">
                                Priority: {{ record.priority }} - {{ record.host }}
                            </div>
                        </div>
                        <div v-else class="text-gray-500">No MX records found</div>
                    </div>
                </div>

                <!-- NS Records -->
                <div class="border rounded">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold">NS Records</h3>
                    </div>
                    <div class="p-4">
                        <div v-if="dnsData.ns && dnsData.ns.length" class="space-y-2">
                            <div v-for="record in dnsData.ns" :key="record" class="text-gray-600">
                                {{ record }}
                            </div>
                        </div>
                        <div v-else class="text-gray-500">No NS records found</div>
                    </div>
                </div>

                <!-- TXT Records -->
                <div class="border rounded">
                    <div class="p-4 border-b bg-gray-50">
                        <h3 class="font-bold">TXT Records</h3>
                    </div>
                    <div class="p-4">
                        <div v-if="dnsData.txt && dnsData.txt.length" class="space-y-2">
                            <div v-for="record in dnsData.txt" :key="record" class="text-gray-600 break-words">
                                {{ record }}
                            </div>
                        </div>
                        <div v-else class="text-gray-500">No TXT records found</div>
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
                dnsData: null
            }
        },
        methods: {
            cleanDomainInput(input) {
                // Remove protocol (http:// or https://)
                let cleaned = input.replace(/^https?:\/\//, '');
                
                // Remove www. prefix
                cleaned = cleaned.replace(/^www\./, '');
                
                // Remove any path, query parameters, or hash
                cleaned = cleaned.split('/')[0];
                
                // Remove any port numbers
                cleaned = cleaned.split(':')[0];
                
                return cleaned.trim();
            },
            async lookupDNS() {
                if (!this.domain || this.isLoading) return;
    
                // Clean the domain input before sending
                const cleanedDomain = this.cleanDomainInput(this.domain);
                
                // Update the input field with cleaned domain
                this.domain = cleanedDomain;
    
                this.isLoading = true;
                this.error = '';
                this.dnsData = null;
    
                try {
                    const response = await fetch('/api/dns.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ domain: cleanedDomain })
                    });
    
                    const data = await response.json();
    
                    if (!response.ok) {
                        throw new Error(data.error || 'Failed to lookup DNS records');
                    }
    
                    this.dnsData = data;
                } catch (err) {
                    console.error('DNS lookup failed:', err);
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