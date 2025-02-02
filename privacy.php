<?php
$pageTitle = 'Privacy Policy - OneNetly';
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
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Privacy Policy</h1>
            
            <p class="text-gray-600 mb-8">Last updated: <?php echo date('F d, Y'); ?></p>

            <div class="space-y-6">
                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">1. Information We Collect</h2>
                    <p>We collect information you provide directly to us when using OneNetly's services:</p>
                    <ul class="list-disc pl-6 text-gray-600">
                        <li>Account information (name, email, password)</li>
                        <li>Profile information (company, address, phone)</li>
                        <li>Usage data and preferences</li>
                        <li>Payment information (processed securely by our payment providers)</li>
                        <li>Files and content you upload</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">2. How We Use Your Information</h2>
                    <p>We use the collected information for:</p>
                    <ul class="list-disc pl-6 text-gray-600">
                        <li>Providing and improving our services</li>
                        <li>Processing your transactions</li>
                        <li>Sending service updates and notifications</li>
                        <li>Analyzing usage patterns and trends</li>
                        <li>Protecting against unauthorized access</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">3. Data Storage</h2>
                    <p>Your data is stored securely on our servers with industry-standard encryption. We retain your information for as long as your account is active or as needed to provide services, comply with legal obligations, or enforce agreements.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">4. Data Sharing</h2>
                    <p>We do not sell your personal information. We may share your information with:</p>
                    <ul class="list-disc pl-6 text-gray-600">
                        <li>Service providers who assist in our operations</li>
                        <li>Legal authorities when required by law</li>
                        <li>Third parties with your explicit consent</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">5. Cookies and Tracking</h2>
                    <p>We use cookies and similar technologies to:</p>
                    <ul class="list-disc pl-6 text-gray-600">
                        <li>Keep you logged in</li>
                        <li>Remember your preferences</li>
                        <li>Analyze usage patterns</li>
                        <li>Improve our services</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">6. Your Rights</h2>
                    <p>You have the right to:</p>
                    <ul class="list-disc pl-6 text-gray-600">
                        <li>Access your personal data</li>
                        <li>Correct inaccurate data</li>
                        <li>Request data deletion</li>
                        <li>Export your data</li>
                        <li>Opt-out of marketing communications</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">7. Security</h2>
                    <p>We implement appropriate security measures to protect your personal information. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">8. Children's Privacy</h2>
                    <p>Our services are not intended for children under 13. We do not knowingly collect personal information from children under 13.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">9. Changes to Policy</h2>
                    <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the date at the top.</p>
                </section>

                <section>
                    <h2 class="text-2xl font-semibold text-gray-800">10. Contact Us</h2>
                    <p>If you have questions about this Privacy Policy, please contact us at:</p>
                    <div class="mt-2 text-gray-600">
                        <p>Email: privacy@onetly.com</p>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>
</body>
</html>