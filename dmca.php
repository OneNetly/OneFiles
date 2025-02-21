<?php 
require_once 'config.php';
$pageTitle = 'DMCA Policy - ' . SITE_NAME;
include 'includes/header.php';
?>

<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">DMCA Policy</h1>
    <div class="prose max-w-none">
        <p class="mb-4">We respect the intellectual property rights of others and expect our users to do the same. In accordance with the Digital Millennium Copyright Act of 1998 ("DMCA"), we will respond expeditiously to claims of copyright infringement that are reported to our designated copyright agent.</p>
        
        <h2 class="text-xl font-semibold mt-6 mb-4">How to File a DMCA Notice</h2>
        <p class="mb-4">If you believe that your copyrighted work has been copied in a way that constitutes copyright infringement, please provide our copyright agent with the following information:</p>
        
        <ol class="list-decimal pl-6 mb-6">
            <li class="mb-2">A description of the copyrighted work that you claim has been infringed;</li>
            <li class="mb-2">A description of where the material that you claim is infringing is located;</li>
            <li class="mb-2">Your address, telephone number, and email address;</li>
            <li class="mb-2">A statement by you that you have a good faith belief that the disputed use is not authorized;</li>
            <li class="mb-2">A statement by you, made under penalty of perjury, that the information in your notice is accurate;</li>
            <li>A physical or electronic signature of the copyright owner or authorized person.</li>
        </ol>

        <h2 class="text-xl font-semibold mt-6 mb-4">Contact Our DMCA Agent</h2>
        <p>Email: <?php echo DMCA_EMAIL; ?></p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
