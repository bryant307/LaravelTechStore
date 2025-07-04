<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- FontAwesome --}}
        <script src="https://kit.fontawesome.com/3e7eb58d7a.js" crossorigin="anonymous"></script>

        <!-- Styles -->
        @livewireStyles
        @stack('css')
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="flex flex-col min-h-screen">
            <div class="flex-grow bg-gray-100">
                @livewire('navigation')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    @yield('content', $slot ?? '')
                </main>
            </div>

            @include('layouts.partials.app.footer')
        </div>

        @stack('modals')

        @livewireScripts
        @stack('js')
    </body>
</html>
