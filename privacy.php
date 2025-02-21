<?php 
require_once 'config.php';
$pageTitle = 'Privacy Policy - ' . SITE_NAME;
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Privacy Policy</h1>
    <div class="prose max-w-none">
        <p class="mb-4">Your privacy is important to us. This Privacy Policy explains how we collect, use, and protect your personal information.</p>
        
        <h2 class="text-xl font-semibold mt-6 mb-4">Information We Collect</h2>
        <p class="mb-4">We collect information that you provide directly to us when you:</p>
        <ul class="list-disc pl-6 mb-6">
            <li>Upload files</li>
            <li>Share download links</li>
            <li>Contact our support</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-4">How We Use Your Information</h2>
        <p class="mb-4">We use the information we collect to:</p>
        <ul class="list-disc pl-6 mb-6">
            <li>Provide file sharing services</li>
            <li>Maintain and improve our service</li>
            <li>Respond to your requests</li>
            <li>Prevent abuse</li>
        </ul>

        <h2 class="text-xl font-semibold mt-6 mb-4">Contact Us</h2>
        <p>If you have any questions about our Privacy Policy, please contact us at <?php echo PRIVACY_EMAIL; ?></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
