<?php
$pageTitle = 'Tools - OneNetly';

$tools = [
    
    'Encoding & Decoding' => [
        [
            'name' => 'Base64 Encoder/Decoder',
            'path' => '/tools/base64',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>'
        ],
        [
            'name' => 'URL Encoder/Decoder',
            'path' => '/tools/url',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>'
        ],
        [
            'name' => 'HTML Encoder',
            'path' => '/tools/html',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>'
        ],
        [
            'name' => 'JWT Decoder',
            'path' => '/tools/jwt',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
            </svg>'
        ],
        [
            'name' => 'ROT13 Encoder',
            'path' => '/tools/rot13',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>'
        ],
        [
            'name' => 'Morse Code Encoder/Decoder',
            'path' => '/tools/morse',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M19 3v4M3 5h4M17 5h4M5 19v-4M19 19v-4M3 19h4M17 19h4" /></svg>'
        ],
        [
            'name' => 'Binary Text Converter',
            'path' => '/tools/binary',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
        ],
        [
            'name' => 'ASCII/Unicode Converter',
            'path' => '/tools/unicode',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" /></svg>'
        ]

    ],
    'Code Tools' => [
        [
            'name' => 'Code Beautifier',
            'path' => '/tools/beautifier',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>'
        ],
        [
            'name' => 'JSON Formatter',
            'path' => '/tools/json',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" /></svg>'
        ],
        [
            'name' => 'PHP Obfuscator',
            'path' => '/tools/obfuscator',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>'
        ],
        [
            'name' => 'JavaScript Obfuscator', 
            'path' => '/tools/js-obfuscator',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>'
        ],
        [
            'name' => 'SQL Formatter',
            'path' => '/tools/sql',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4" /></svg>'
        ],
        [
            'name' => 'XML Formatter',
            'path' => '/tools/xml',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>'
        ],
        [
            'name' => 'YAML Formatter',
            'path' => '/tools/yaml',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>'
        ],
        [
            'name' => 'JavaScript Minifier',
            'path' => '/tools/js-minifier',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>'
        ]
    ],
    'Security Tools' => [
        [
            'name' => 'Hash Generator',
            'path' => '/tools/hash',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>'
        ],
        [
            'name' => 'Password Generator',
            'path' => '/tools/password',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" /></svg>'
        ],
        [
            'name' => 'SSL Checker',
            'path' => '/tools/ssl',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /></svg>'
        ],
        [
            'name' => 'Whois Lookup',
            'path' => '/tools/whois',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5z" /></svg>'
        ],
        [
            'name' => 'IP Address Lookup',
            'path' => '/tools/ip',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5z" /></svg>'
        ],
        [
            'name' => 'DNS Lookup',
            'path' => '/tools/dns',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5z" /></svg>'
        ],
        [
            'name' => 'Port Scanner',
            'path' => '/tools/port',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5z" /></svg>'
        ],
        [
            'name' => 'Subnet Calculator',
            'path' => '/tools/subnet',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5z" /></svg>'
        ]
    ],
    'Media Tools' => [
        [
            'name' => 'Image Optimizer',
            'path' => '/tools/image',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
        ],
        [
            'name' => 'Color Converter',
            'path' => '/tools/color',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" /></svg>'
        ],
        [
            'name' => 'QR Code Generator',
            'path' => '/tools/qr-code',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v-4m6 0h-2m2 0v4m-6 0h-2m2 0v4m-6-4h2m-2 0v4m0-11v-4m0 0h2m-2 0h4m6 0h-4m4 0v4" /></svg>'
        ],
        [
            'name' => 'Barcode Generator',
            'path' => '/tools/barcode',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M12 4v1m0 4v1m0 4v1m0 4v1M4 12h1m4 0h1m4 0h1m4 0h1M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>'
        ]
    ],
    'Development Tools' => [
        [
            'name' => 'API Tester',
            'path' => '/tools/api-test',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
        ],
        [
            'name' => 'Ad Blocker Detector',
            'path' => '/tools/adblock-detector-generator',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>'
        ]
    ]
    ,
    'File Tools' => [
        [
            'name' => 'File Metadata',
            'path' => '/tools/file-meta',
            'icon' => '<svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>'
        ]
    ]
];
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <script src="../js/cdn.min.js"></script>
    <script src="../js/vue.global.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once 'nav.php'; ?>
    
    <div id="app" class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search Bar -->
    <div class="max-w-2xl mx-auto mb-8">
        <input 
            type="text"
            v-model="searchQuery"
            placeholder="Search tools..."
            class="w-full p-3 border border-gray-300 rounded-lg text-base bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm"
        >
    </div>

    <!-- Tools Grid -->
    <div v-if="Object.keys(filteredTools).length" class="space-y-12">
        <div v-for="(tools, category) in filteredTools" :key="category" class="space-y-6">
            <h2 class="text-xl font-bold text-gray-900 border-b pb-2">{{ category }}</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-8 gap-4">
                <a v-for="tool in tools" 
                   :key="tool.path"
                   :href="tool.path"
                   class="group flex flex-col items-center p-3 bg-white rounded-lg hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 border border-gray-100">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-500 text-white mb-2 group-hover:bg-blue-600 transition-colors" 
                         v-html="tool.icon">
                    </div>
                    <h3 class="text-sm font-medium text-gray-700 text-center leading-tight group-hover:text-blue-600 transition-colors">
                        {{ tool.name }}
                    </h3>
                </a>
            </div>
        </div>
    </div>

    <!-- No Results Message -->
    <div v-else class="text-center py-8">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
            <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </div>
        <p class="text-gray-500">No tools found matching your search.</p>
    </div>
</div>

    <script>
    const { createApp } = Vue

    createApp({
        data() {
            return {
                tools: <?php echo json_encode($tools); ?>,
                searchQuery: ''
            }
        },
        computed: {
            filteredTools() {
                if (!this.searchQuery) return this.tools;
                
                const query = this.searchQuery.toLowerCase();
                const filtered = {};
                
                Object.entries(this.tools).forEach(([category, tools]) => {
                    const matchedTools = tools.filter(tool => 
                        tool.name.toLowerCase().includes(query)
                    );
                    
                    if (matchedTools.length > 0) {
                        filtered[category] = matchedTools;
                    }
                });
                
                return filtered;
            }
        }
    }).mount('#app')
    </script>

    <?php require_once 'footer.php'; ?>
</body>
</html>