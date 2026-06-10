<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left branding panel -->
        <div class="hidden lg:flex lg:w-1/2 bg-blue-600 flex-col items-center justify-center p-12 text-white">
            <img src="{{ asset('images/Icon-White.png') }}" alt="Logo" class="h-24 w-24 mb-6" />
            <h1 class="text-4xl font-bold mb-3">Short URLs</h1>
            <p class="text-blue-100 text-lg text-center max-w-xs">Simple, fast link shortening for everyone.</p>
        </div>

        <!-- Right form panel -->
        <div class="flex-1 flex flex-col items-center justify-center px-6 py-12 bg-gray-50 dark:bg-gray-900">
            <!-- Mobile logo -->
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('images/Icon-Blue.png') }}" alt="Logo" class="dark:hidden h-16 w-16 mx-auto" />
                    <img src="{{ asset('images/Icon-White.png') }}" alt="Logo" class="hidden dark:block h-16 w-16 mx-auto" />
                </a>
            </div>

            <div class="w-full max-w-md bg-white dark:bg-gray-800 shadow-md rounded-xl px-8 py-10">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>
