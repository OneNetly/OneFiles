<?php
$pageTitle = 'SSL Checker - OneNetly';
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
            <h1 class="text-2xl font-bold mb-6">SSL Certificate Checker</h1>

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
                        @keyup.enter="checkSSL"
                    >
                    <button
                        @click="checkSSL"
                        :disabled="!domain || isLoading"
                        :class="[
                            'px-6 py-3 rounded text-lg font-bold transition duration-300',
                            domain && !isLoading
                                ? 'bg-blue-600 hover:bg-blue-700 text-white'
                                : 'bg-gray-300 cursor-not-allowed text-gray-500'
                        ]"
                    >
                        <span v-if="!isLoading">Check SSL</span>
                        <span v-else>Checking...</span>
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
            <div v-if="certInfo" class="space-y-6">
                <!-- Status -->
                <div class="p-4 rounded" :class="certInfo.valid ? 'bg-green-50' : 'bg-red-50'">
                    <div class="flex items-center">
                        <div v-if="certInfo.valid" class="text-green-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div v-else class="text-red-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <span class="ml-2 font-bold" :class="certInfo.valid ? 'text-green-600' : 'text-red-600'">
                            {{ certInfo.valid ? 'Valid SSL Certificate' : 'Invalid SSL Certificate' }}
                        </span>
                    </div>
                </div>

                <!-- Certificate Details -->
                <div class="border rounded divide-y">
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Common Name</h3>
                        <p>{{ certInfo.commonName }}</p>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Issuer</h3>
                        <p>{{ certInfo.issuer }}</p>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Valid Period</h3>
                        <p>From: {{ certInfo.validFrom }}</p>
                        <p>To: {{ certInfo.validTo }}</p>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold mb-2">Alternative Names</h3>
                        <ul class="list-disc list-inside">
                            <li v-for="name in certInfo.altNames" :key="name">{{ name }}</li>
                        </ul>
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
                certInfo: null
            }
        },
        methods: {
            async checkSSL() {
                if (!this.domain || this.isLoading) return;

                this.isLoading = true;
                this.error = '';
                this.certInfo = null;

                try {
                    const response = await fetch('/api/check-ssl.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ domain: this.domain })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(data.error || 'Failed to check SSL certificate');
                    }

                    this.certInfo = {
                        valid: data.valid,
                        commonName: data.common_name,
                        issuer: data.issuer,
                        validFrom: new Date(data.valid_from).toLocaleDateString(),
                        validTo: new Date(data.valid_to).toLocaleDateString(),
                        altNames: data.alt_names || []
                    };
                } catch (err) {
                    console.error('SSL check failed:', err);
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