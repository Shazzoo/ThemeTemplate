<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', config('app.name'))</title>
        @php
            $activePluginSlugs = function_exists('active_plugin_slugs') ? active_plugin_slugs() : [];
            $isAdminRequest = request()->is('admin*');
        @endphp

        {{-- @vite(['storage/app/themes/<theme-name>/resources/css/app.css','storage/app/themes/<theme-name>/resources/js/app.js',], 'theme-build/<theme-name>') --}}

        @stack('styles')

    </head>
    @php
        $page = $page ?? null;
        $image_ids = [setting('logo', null)];
        $images = FinnWiel\ShazzooMedia\Models\ShazzooMedia::whereIn('id', $image_ids)->get();
    @endphp
    <body class="antialiased m-0 font-sans overflow-x-hidden">
        <div class="overflow-x-clip lg:overflow-x-visible flex flex-col min-h-screen">
            <x-template-vendor-theme-name::page.header :page="$page" :images="$images" />
            <button id="back-to-top-button"
                class="fixed border border-white/30 bottom-6 right-6 z-[999] w-10 h-10 rounded-full bg-[#1b223690] text-white transition-all duration-300 ease-out opacity-0 translate-y-2 pointer-events-none hover:bg-[#1b2236]">
                ↑
            </button>

            <main class="flex-1">
                @yield('content')
            </main>

            <x-template-vendor-theme-name::page.footer :images="$images" :page="$page" />
        </div>

        @stack('scripts')
    </body>
</html>
