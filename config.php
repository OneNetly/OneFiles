<?php
// API Configuration
define('API_URL', 'https://onenetly.com/api.php');
define('API_KEY', '59d144a41422d3f7881fefdae6ffd53b020264f57a5c7da10bd1345bb54e6fb1');

// Site Configuration
define('SITE_NAME', 'FreeNetly');
define('SITE_EMAIL', 'motiurop@outlook.com');
define('DMCA_EMAIL', 'motiurop@outlook.com');
define('PRIVACY_EMAIL', 'motiurop@outlook.com');
define('MAX_FILE_SIZE', 100 * 1024 * 1024); // 100MB in bytes

// Base URL (without trailing slash)
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST']);

// Image Configuration
define('OG_IMAGE', BASE_URL . '/assets/images/og.png');
define('TWITTER_IMAGE', BASE_URL . '/assets/images/og.png');