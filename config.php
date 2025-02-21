<?php
// API Configuration
define('API_URL', 'https://onenetly.com/api.php');
define('API_KEY', 'your_api_key'); //Register https://onenetly.com and get api key

// Site Configuration
define('SITE_NAME', 'FreeNetly');
define('SITE_EMAIL', 'info@freenetly.com');
define('DMCA_EMAIL', 'dmca@freenetly.com');
define('PRIVACY_EMAIL', 'privacy@freenetly.com');
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // 100MB in bytes

// Base URL (without trailing slash)
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);

// Image Configuration
define('OG_IMAGE', BASE_URL . '/assets/images/og.png');
define('TWITTER_IMAGE', BASE_URL . '/assets/images/og.png');
