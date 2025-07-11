<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="flex h-screen overflow-hidden">
            <!-- Sidebar -->
            @include('admin.partials.sidebar')

            <!-- Main Content -->
            <div class="flex-1 overflow-auto">
                <!-- Top Navigation -->
                @include('admin.partials.topnav')

                <!-- Page Content -->
                <main class="p-6">
                    <!-- Header -->
                    @isset($header)
                        <div class="mb-6">
                            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $header }}</h1>
                        </div>
                    @endisset

                    <!-- Alert Messages -->
                    @if (session('success'))
                        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-4 mb-6 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Main Content -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Modal Container for dynamic modals -->
    <div id="modal-container"></div>

    <!-- Alpine.js Components -->
    <script>
        document.addEventListener('alpine:init', () => {
            // Định nghĩa các components Alpine.js ở đây
        });
    </script>

    <!-- Extra Scripts -->
    @stack('scripts')
</body>
</html>
