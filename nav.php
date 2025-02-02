<?php
$isLoggedIn = isset($_SESSION['user_id']);
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<header class="bg-white shadow" x-data="{
    isOpen: false,
    isSidebarOpen: false,
    isCategoryOpen: false,
    isAuthenticated: <?php echo isset($_SESSION['user_id']) ? 'true' : 'false' ?>,
    isAdmin: <?php echo isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 'true' : 'false' ?>,
    user: {
        name: '<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) : 'User' ?>'
    }
}">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-30 w-64 transition duration-300 transform bg-white border-r shadow-lg"
         :class="{'translate-x-0 ease-out': isSidebarOpen, '-translate-x-full ease-in': !isSidebarOpen}">
        
        <!-- Logo Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-600 to-indigo-700">
            <div class="flex items-center space-x-3">
                <svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                    <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
                        <path d="M2400 5114 c-516 -36 -1036 -236 -1430 -552 -517 -414 -842 -982 -941 -1642 -25 -168 -30 -502 -10 -655 48 -367 161 -704 336 -1000 344 -582 922 -1019 1564 -1180 907 -228 1840 35 2477 698 432 449 671 991 715 1618 36 531 -111 1097 -403 1545 -471 721 -1234 1146 -2096 1168 -86 2 -182 2 -212 0z m-414 -1061 c-4 -10 -194 -350 -422 -755 l-415 -738 242 -430 c133 -236 245 -430 248 -430 3 1 339 534 745 1186 l740 1184 380 -2 379 -3 424 -752 423 -753 -423 -752 -424 -753 -382 -3 -382 -2 15 22 c7 13 199 353 425 755 l412 733 -242 430 c-133 236 -245 430 -248 430 -3 -1 -339 -534 -745 -1186 l-740 -1184 -380 2 -379 3 -424 752 -423 753 406 722 c223 398 415 738 426 756 l20 32 375 0 c353 0 375 -1 369 -17z"/>
                    </g>
                </svg>
                <div>
                    <h1 class="text-xl font-bold text-white">OneNetly</h1>
                </div>
            </div>
            <button @click="isSidebarOpen = false" 
                    class="p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Content -->
        <div class="flex flex-col h-full">
            <!-- Auth Section - Show if not authenticated -->
            <template x-if="!isAuthenticated">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="space-y-3">
                        <a href="/login.php" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Sign In
                        </a>
                        <a href="/register.php" class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Sign Up
                        </a>
                    </div>
                </div>
            </template>

            <!-- User Profile Section - Show if authenticated -->
            <template x-if="isAuthenticated">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex h-10 w-10 rounded-full bg-indigo-100 items-center justify-center">
                            <span class="text-sm font-medium leading-none text-indigo-700">
                                <?php echo isset($_SESSION['first_name']) ? strtoupper(substr($_SESSION['first_name'], 0, 1)) : 'G'; ?>
                            </span>
                        </span>
                        <div class="flex flex-col">
                            <span class="text-sm font-medium text-gray-900" x-text="user.name">User Name</span>
                            <span class="text-xs text-gray-500" x-text="isAdmin ? 'Administrator' : 'User'"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Main Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <!-- Main Links -->
                <div class="px-4 space-y-4">
                    <div>
                        <div class="px-3 mb-2">
                            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Navigation</h3>
                        </div>
                        <div class="space-y-1">
                            <a href="/" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'index' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-100'; ?>">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Home
                            </a>
                            <a href="/tools" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'tools' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-100'; ?>">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065z"/>
                                </svg>
                                Tools
                            </a>
                        </div>
                    </div>

                    <!-- Show Dashboard Section only if authenticated -->
                    <template x-if="isAuthenticated">
                        <div>
                            <div class="px-3 mb-2">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Dashboard</h3>
                            </div>
                            <div class="space-y-1">
                                <a href="/dashboard.php" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'dashboard' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-100'; ?>">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Dashboard
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- Show Admin Section only if authenticated and is admin -->
                    <template x-if="isAuthenticated && isAdmin">
                        <div>
                            <div class="px-3 mb-2">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Administration</h3>
                            </div>
                            <div class="space-y-1">
                                <a href="/admin" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-lg transition-colors <?php echo $currentPage === 'admin' ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-100'; ?>">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Admin Panel
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </nav>

            <!-- Persistent Logout Button -->
            <template x-if="isAuthenticated">
                <div class="sticky bottom-0 left-0 right-0 p-4 bg-white border-t border-gray-200 shadow-lg">
                    <form action="/logout.php" method="POST">
                        <button type="submit" 
                                class="group relative w-full flex items-center justify-center px-4 py-2.5 text-sm font-medium text-red-600 bg-white border border-red-300 rounded-lg hover:bg-red-50 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-150">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-red-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </div>
                            Sign out of OneNetly
                        </button>
                    </form>
                </div>
            </template>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left Section -->
                <div class="flex items-center">
                    <!-- Sidebar Toggle -->
                    <button @click="isSidebarOpen = !isSidebarOpen" 
                            class="p-2 rounded-md text-gray-500 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                <!-- Logo -->
                <a href="/" class="flex items-center ml-4">
                    <svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                        <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
                            <path d="M2400 5114 c-516 -36 -1036 -236 -1430 -552 -517 -414 -842 -982 -941 -1642 -25 -168 -30 -502 -10 -655 48 -367 161 -704 336 -1000 344 -582 922 -1019 1564 -1180 907 -228 1840 35 2477 698 432 449 671 991 715 1618 36 531 -111 1097 -403 1545 -471 721 -1234 1146 -2096 1168 -86 2 -182 2 -212 0z m-414 -1061 c-4 -10 -194 -350 -422 -755 l-415 -738 242 -430 c133 -236 245 -430 248 -430 3 1 339 534 745 1186 l740 1184 380 -2 379 -3 424 -752 423 -753 -423 -752 -424 -753 -382 -3 -382 -2 15 22 c7 13 199 353 425 755 l412 733 -242 430 c-133 236 -245 430 -248 430 -3 -1 -339 -534 -745 -1186 l-740 -1184 -380 2 -379 3 -424 752 -423 753 406 722 c223 398 415 738 426 756 l20 32 375 0 c353 0 375 -1 369 -17z"/>
                        </g>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900">OneNetly</span>
                </a>
                </div>

                <!-- Center Navigation -->
                <nav class="hidden lg:flex lg:items-center lg:justify-center flex-1 px-4">
                    <div class="flex space-x-1">
                        <a href="/" class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Home
                        </a>
                        <a href="/tools" class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065z"/>
                            </svg>
                            Tools
                        </a>
                        <?php if ($isLoggedIn): ?>
                            <a href="/biolink" class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Biolink
                            </a>
                            <a href="/donation" class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Donations
                            </a>
                        <?php else: ?>
                            <a href="/biolinks.php" class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                Biolink
                            </a>
                            <a href="/fundraising.php" class="flex items-center px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                Donations
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>
                <!-- Wallet Balance (if authenticated) -->
                <a href="/wallet"><template x-if="isAuthenticated">
                    <div class="ml-auto flex items-center pr-4">
                        <div class="flex items-center px-4 py-2 bg-gray-50 rounded-lg">
                            <svg class="w-5 h-5 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">Balance</span>
                                <span class="font-medium text-gray-900">
                                <?php
                                    if (isset($_SESSION['user_id'])) {
                                        try {
                                            $walletStmt = $db->prepare("SELECT balance FROM user_wallets WHERE user_id = ?");
                                            $walletStmt->execute([$_SESSION['user_id']]);
                                            $balance = $walletStmt->fetchColumn() ?: 0;
                                            echo '$' . number_format($balance, 2);
                                        } catch (PDOException $e) {
                                            error_log("Error fetching wallet balance: " . $e->getMessage());
                                            echo '$0.00';
                                        }
                                    } else {
                                        echo '$0.00';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </template></a>
                <!-- Right Section - Auth Buttons -->
                <div class="flex items-center space-x-4">
                    <?php if ($isLoggedIn): ?>
                        <!-- Dashboard Button -->
                        <a href="/dashboard.php" class="flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Dashboard
                        </a>
                        <!-- Profile/Logout Dropdown can be added here -->
                    <?php else: ?>
                        <!-- Login Button -->
                        <a href="/login.php" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-gray-900">
                            Sign In
                        </a>
                        <!-- Register Button -->
                        <a href="/register.php" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            Get Started
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Overlay -->
    <div x-show="isSidebarOpen" 
         class="fixed inset-0 z-20 bg-black bg-opacity-50"
         @click="isSidebarOpen = false">
    </div>
</header>
<script src="https://onenetly.com/js/adblock-detector.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detector = new AdBlockDetector({
            warningMessage: 'Please disable your ad blocker to continue',
            warningTitle: '🛡️ Ad Blocker Detected',
            opacity: 0.95,
            blur: true,
            blurAmount: 5,
        });
        detector.init();
    });

    // Add event listener for keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Toggle sidebar with Ctrl + B
        if (e.ctrlKey && e.key === 'b') {
            e.preventDefault();
            Alpine.store('isSidebarOpen', !Alpine.store('isSidebarOpen'));
        }
        // Close sidebar with Escape key
        if (e.key === 'Escape') {
            Alpine.store('isSidebarOpen', false);
            Alpine.store('isOpen', false);
        }
    });
</script>