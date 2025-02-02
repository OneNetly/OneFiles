<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="text-center p-8 bg-white rounded-lg shadow-xl max-w-2xl mx-4">
        <div class="mb-8">
            <svg class="w-16 h-16 mx-auto text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Under Maintenance</h1>
        
        <p class="text-gray-600 mb-8">
            We're currently performing some scheduled maintenance. We'll be back online shortly!
        </p>
        
        <div class="flex items-center justify-center space-x-4">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"></div>
            <span class="text-gray-500">Expected downtime: 30 minutes</span>
        </div>
        
        <div class="mt-8 text-sm text-gray-500">
            If you need immediate assistance, please contact: 
            <a href="mailto:support@example.com" class="text-blue-500 hover:text-blue-600">support@example.com</a>
        </div>
    </div>
</body>
</html>