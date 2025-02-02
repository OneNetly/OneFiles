<?php
$isLoggedIn = isset($_SESSION['user_id']);
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<header class="bg-white shadow" x-data="{
    isOpen: false,
    isCategoryOpen: false,
    isAuthenticated: <?php echo isset($_SESSION['user_id']) ? 'true' : 'false' ?>,
    isAdmin: <?php echo isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 'true' : 'false' ?>,
    user: {
        name: '<?php echo isset($_SESSION['first_name']) ? htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']) : 'User' ?>'
    }
}">
    <!-- Main Navigation Bar -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Left Section -->
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="/" class="flex items-center ml-4">
                        <svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                            <g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)" fill="currentColor" stroke="none">
                                <path d="M2400 5114 c-516 -36 -1036 -236 -1430 -552 -517 -414 -842 -982 -941 -1642 -25 -168 -30 -502 -10 -655 48 -367 161 -704 336 -1000 344 -582 922 -1019 1564 -1180 907 -228 1840 35 2477 698 432 449 671 991 715 1618 36 531 -111 1097 -403 1545 -471 721 -1234 1146 -2096 1168 -86 2 -182 2 -212 0z m-414 -1061 c-4 -10 -194 -350 -422 -755 l-415 -738 242 -430 c133 -236 245 -430 248 -430 3 1 339 534 745 1186 l740 1184 380 -2 379 -3 424 -752 423 -753 -423 -752 -424 -753 -382 -3 -382 -2 15 22 c7 13 199 353 425 755 l412 733 -242 430 c-133 236 -245 430 -248 430 -3 -1 -339 -534 -745 -1186 l-740 -1184 -380 2 -379 3 -424 752 -423 753 406 722 c223 398 415 738 426 756 l20 32 375 0 c353 0 375 -1 369 -17z"/>
                            </g>
                        </svg>
                        <span class="ml-2 text-xl font-bold text-gray-900">FreeNetly</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>
<script>
    // Add event listener for keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Close sidebar with Escape key
        if (e.key === 'Escape') {
            Alpine.store('isOpen', false);
        }
    });
</script>