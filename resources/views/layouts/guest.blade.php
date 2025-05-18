<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
        use Illuminate\Support\Facades\Cache;
        use Illuminate\Support\Facades\Storage;

        $settings = Cache::remember('app_settings', now()->addDay(), function() {
            if (Storage::exists('settings.json')) {
                return json_decode(Storage::get('settings.json'), true);
            }
            return [];
        });
        
        $siteName = $settings['site_name'] ?? config('app.name', 'LaraMob');
        $siteDescription = $settings['site_description'] ?? 'Sistema imobiliário';
        @endphp

        <title>{{ $siteName }}</title>

        <!-- Meta tags -->
        <meta name="description" content="{{ $settings['meta_description'] ?? $siteDescription }}">
        @if(isset($settings['meta_keywords']) && $settings['meta_keywords'])
        <meta name="keywords" content="{{ $settings['meta_keywords'] }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Dynamic Styles based on settings -->
        <link rel="stylesheet" href="{{ route('dynamic.styles') }}">
        
        @if(isset($settings['google_analytics_id']) && $settings['google_analytics_id'])
        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $settings['google_analytics_id'] }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $settings['google_analytics_id'] }}');
        </script>
        @endif
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
