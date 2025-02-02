<?php
$pageTitle = 'Terms of Service - OneNetly';
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $pageTitle; ?></title>
    <link href="css/output.css" rel="stylesheet">
    <script src="js/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gray-100">
    <?php require_once 'nav.php'; ?>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-lg shadow p-6 prose">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Terms of Service</h1>
            
            <p class="text-gray-600 mb-8">Last updated: <?php echo date('F d, Y'); ?></p>

            <div class="space-y-6">
                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">1. Terms of Use</h2>
                    <p>By accessing and using OneNetly's developer tools and services, you agree to be bound by these Terms of Service. If you do not agree to these terms, please do not use our services.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">2. Services</h2>
                    <p>OneNetly provides various developer tools and utilities including but not limited to:</p>
                    <ul class="list-disc pl-6 text-gray-600">
                        <li>Code formatting and beautification</li>
                        <li>Base64 encoding/decoding</li>
                        <li>JavaScript obfuscation</li>
                        <li>HTML/XML tools</li>
                        <li>File sharing capabilities</li>
                        <li>API testing tools</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">3. User Accounts</h2>
                    <p>When creating an account, you must provide accurate and complete information. You are responsible for maintaining the security of your account credentials and for all activities that occur under your account.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">4. Usage Guidelines</h2>
                    <p>You agree not to:</p>
                    <ul class="list-disc pl-6 text-gray-600">
                        <li>Use the services for any illegal purposes</li>
                        <li>Upload malicious code or content</li>
                        <li>Attempt to breach our security measures</li>
                        <li>Share your account credentials</li>
                        <li>Abuse or overload our systems</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">5. Privacy</h2>
                    <p>Your use of our services is also governed by our <a href="/privacy.php" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>. By using our services, you consent to our collection and use of information as outlined in the Privacy Policy.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">6. Intellectual Property</h2>
                    <p>The services, including all content, features, and functionality, are owned by OneNetly and are protected by copyright, trademark, and other intellectual property laws.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">7. Limitation of Liability</h2>
                    <p>OneNetly shall not be liable for any indirect, incidental, special, consequential, or punitive damages resulting from your use or inability to use the services.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">8. Changes to Terms</h2>
                    <p>We reserve the right to modify these terms at any time. We will notify users of any material changes via email or through our website.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">9. Contact Us</h2>
                    <p>If you have any questions about these Terms of Service, please contact us at:</p>
                    <div class="mt-2 text-gray-600">
                        <p>Email: support@onetly.com</p>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>
</body>
</html>