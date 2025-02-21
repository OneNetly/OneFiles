<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $meta['title'] ?? SITE_NAME; ?></title>
    
    <!-- SEO Meta Tags -->
    <?php if (isset($meta)): ?>
        <meta name="description" content="<?php echo $meta['description']; ?>">
        <meta name="keywords" content="<?php echo $meta['keywords']; ?>">
        <link rel="canonical" href="<?php echo $meta['canonical']; ?>">
        
        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo $meta['canonical']; ?>">
        <meta property="og:title" content="<?php echo $meta['title']; ?>">
        <meta property="og:description" content="<?php echo $meta['description']; ?>">
        <meta property="og:image" content="<?php echo OG_IMAGE; ?>">
        
        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="<?php echo $meta['canonical']; ?>">
        <meta name="twitter:title" content="<?php echo $meta['title']; ?>">
        <meta name="twitter:description" content="<?php echo $meta['description']; ?>">
        <meta name="twitter:image" content="<?php echo TWITTER_IMAGE; ?>">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo BASE_URL; ?>/assets/images/icon.png">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#0EA5E9'
                    }
                }
            }
        }
    </script>
    <style>
        .aspect-w-16 {
            position: relative;
            padding-bottom: 56.25%;
        }
        .aspect-w-16 > * {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white border-b">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="/" class="flex items-center space-x-2">
                    <svg class="h-8 w-8 text-primary" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M9 3v15h3V3H9zm3 0h3v15h-3V3z"/>
                    </svg>
                    <span class="text-xl font-bold text-gray-900"><?php echo SITE_NAME; ?></span>
                </a>
                <div class="flex items-center space-x-6">
                    <a href="/" class="text-gray-600 hover:text-primary transition-colors">Home</a>
                    <a href="/dmca.php" class="text-gray-600 hover:text-primary transition-colors">DMCA</a>
                    <a href="/privacy.php" class="text-gray-600 hover:text-primary transition-colors">Privacy</a>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-grow">
