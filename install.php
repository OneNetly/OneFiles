<?php
// Check if already installed
if (file_exists('config.php')) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>OneFiles - Already Installed</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    </head>
    <body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8 animate__animated animate__fadeIn">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-blue-500 animate__animated animate__rubberBand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="mt-4 text-xl font-semibold text-gray-900">OneFiles is Ready!</h2>
                <p class="mt-2 text-gray-600">Your OneFiles installation is complete and running.</p>
                <a href="/" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transform transition hover:scale-105">
                    Go to Homepage
                </a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Handle installation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validate inputs
    if (empty($_POST['api_key'])) {
        $errors[] = 'API Key is required';
    }
    if (empty($_POST['site_name'])) {
        $errors[] = 'Site Name is required';
    }
    if (empty($_POST['site_email']) || !filter_var($_POST['site_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid Site Email is required';
    }
    if (empty($_POST['dmca_email']) || !filter_var($_POST['dmca_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid DMCA Email is required';
    }
    if (empty($_POST['privacy_email']) || !filter_var($_POST['privacy_email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid Privacy Email is required';
    }

    // If no errors, create config file
    if (empty($errors)) {
        $config = <<<PHP
<?php
// API Configuration
define('API_URL', 'https://onenetly.com/api.php');
define('API_KEY', '{$_POST['api_key']}');

// Site Configuration
define('SITE_NAME', '{$_POST['site_name']}');
define('SITE_EMAIL', '{$_POST['site_email']}');
define('DMCA_EMAIL', '{$_POST['dmca_email']}');
define('PRIVACY_EMAIL', '{$_POST['privacy_email']}');
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // 100MB in bytes

// Base URL (without trailing slash)
define('BASE_URL', 'http://' . \$_SERVER['HTTP_HOST']);

// Image Configuration
define('OG_IMAGE', BASE_URL . '/assets/images/og.png');
define('TWITTER_IMAGE', BASE_URL . '/assets/images/og.png');
PHP;

        if (file_put_contents('config.php', $config)) {
            // Modified redirect to show success message first
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Installing FreeNetly...</title>
                <script src="https://cdn.tailwindcss.com"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
                <script>
                    // Redirect after 5 seconds
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 5000);
                </script>
            </head>
            <body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
                <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8 text-center animate__animated animate__fadeInUp">
                    <svg class="mx-auto h-16 w-16 text-green-500 animate__animated animate__bounceIn" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h2 class="mt-4 text-2xl font-semibold text-gray-900">Installation Complete!</h2>
                    <p class="mt-2 text-gray-600">FreeNetly has been successfully installed.</p>
                    <div class="mt-4">
                        <div class="animate-pulse text-blue-600">Redirecting to homepage...</div>
                        <div class="mt-2 text-sm text-gray-500">
                            Click <a href="/" class="text-blue-600 hover:underline">here</a> if you're not redirected automatically
                        </div>
                    </div>
                </div>
            </body>
            </html>
            <?php
            exit;
        } else {
            $errors[] = 'Failed to create config file. Please check directory permissions.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install FreeNetly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <?php if (isset($_GET['success'])): ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center animate__animated animate__fadeInUp">
                <svg class="mx-auto h-16 w-16 text-green-500 animate__animated animate__bounceIn animate__delay-1s" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="mt-4 text-2xl font-semibold text-gray-900 animate__animated animate__fadeInUp animate__delay-1s">
                    Installation Complete!
                </h2>
                <p class="mt-2 text-gray-600 animate__animated animate__fadeInUp animate__delay-1s">
                    FreeNetly has been successfully installed and is ready to use.
                </p>
                <a href="/" class="mt-6 inline-block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transform transition hover:scale-105 animate__animated animate__fadeInUp animate__delay-2s">
                    Launch FreeNetly
                </a>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-8 animate__animated animate__fadeIn">
                <h2 class="text-2xl font-bold text-center mb-6">Install OneFiles</h2>
                
                <?php if (!empty($errors)): ?>
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                        <ul class="list-disc list-inside">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">API Key</label>
                        <input type="text" name="api_key" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['api_key'] ?? ''); ?>"
                               placeholder="Enter your OneNetly API Key">
                        <p class="mt-1 text-sm text-gray-500">Get your API key from <a href="https://onenetly.com" class="text-blue-600 hover:underline" target="_blank">OneNetly</a></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Site Name</label>
                        <input type="text" name="site_name" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['site_name'] ?? 'FreeNetly'); ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Site Email</label>
                        <input type="email" name="site_email" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['site_email'] ?? ''); ?>"
                               placeholder="info@yourdomain.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">DMCA Email</label>
                        <input type="email" name="dmca_email" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['dmca_email'] ?? ''); ?>"
                               placeholder="dmca@yourdomain.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Privacy Email</label>
                        <input type="email" name="privacy_email" required 
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                               value="<?php echo htmlspecialchars($_POST['privacy_email'] ?? ''); ?>"
                               placeholder="privacy@yourdomain.com">
                    </div>

                    <button type="submit" 
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Install FreeNetly
                    </button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
