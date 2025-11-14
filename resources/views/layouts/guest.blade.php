<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Real Estate CRM') }}</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-blue-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-xl mb-4">
                <span class="text-white text-2xl font-bold">RE</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Real Estate CRM</h1>
        </div>

        <!-- Card -->
        <div class="bg-white shadow-lg rounded-2xl p-8">
            @yield('content')
        </div>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-600 mt-6">
            © {{ date('Y') }} Real Estate CRM. All rights reserved.
        </p>
    </div>
</body>
</html>