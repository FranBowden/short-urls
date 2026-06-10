<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Short URLs') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="text-center">
            <div class="flex justify-center mb-6">
                <img src="{{ asset('images/Icon-Blue.png') }}" alt="Logo" class="dark:hidden h-24 w-24" />
                <img src="{{ asset('images/Icon-White.png') }}" alt="Logo" class="hidden dark:block h-24 w-24" />
            </div>

            <h1 class="text-5xl font-bold tracking-tight mb-3">Short URLs</h1>
            <p class="text-lg text-gray-500 dark:text-gray-400 mb-10">Simple, fast link shortening.</p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}"
                    class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                    Sign Up
                </a>
                <a href="{{ route('login') }}"
                    class="px-8 py-3 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-800 dark:text-white font-semibold rounded-lg border border-gray-200 dark:border-gray-700 transition">
                    Log In
                </a>
            </div>
        </div>
    </div>
</body>

</html>
