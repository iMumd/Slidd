<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Slidd — Minimal presentation workspace')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-[#fafafa] antialiased text-zinc-900">

    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 transition-all duration-200">
        <div class="max-w-6xl mx-auto px-5 sm:px-6">

            <div class="h-14 flex items-center justify-between">

                <a href="/" class="font-bold text-sm tracking-tight text-zinc-900 shrink-0">Slidd</a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('introduction') }}" class="text-sm text-zinc-500 hover:text-zinc-900 px-3 py-1.5 rounded-lg hover:bg-black/5 transition-all duration-150">Introduction</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm text-zinc-500 hover:text-zinc-900 px-3 py-1.5 rounded-lg hover:bg-black/5 transition-all duration-150 ml-2">Dashboard</a>
                        @else
                            <div class="w-px h-4 bg-zinc-200 mx-2"></div>
                            <a href="{{ route('login') }}" class="text-sm text-zinc-500 hover:text-zinc-900 px-3 py-1.5 rounded-lg hover:bg-black/5 transition-all duration-150">Sign in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-sm font-medium text-white bg-zinc-900 px-4 py-1.5 rounded-lg hover:bg-zinc-700 transition-colors duration-150 ml-1">Get started</a>
                            @endif
                        @endauth
                    @endif
                </div>

                <button id="menu-btn" class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg text-zinc-500 hover:text-zinc-900 hover:bg-black/5 transition-all duration-150" aria-label="Toggle menu">
                    <svg id="icon-open" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    <svg id="icon-close" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" class="hidden"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>

            </div>

        </div>

        <div id="mobile-menu" class="hidden md:hidden border-t border-zinc-200/60 bg-white/80 backdrop-blur-md">
            <div class="max-w-6xl mx-auto px-5 sm:px-6 py-3 space-y-0.5">
                <a href="{{ route('introduction') }}" class="flex items-center text-sm text-zinc-600 hover:text-zinc-900 px-3 py-2.5 rounded-xl hover:bg-black/5 transition-all duration-150">Introduction</a>
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="flex items-center text-sm text-zinc-600 hover:text-zinc-900 px-3 py-2.5 rounded-xl hover:bg-black/5 transition-all duration-150">Dashboard</a>
                    @else
                        <div class="pt-2 mt-2 border-t border-zinc-100 space-y-0.5">
                            <a href="{{ route('login') }}" class="flex items-center text-sm text-zinc-600 hover:text-zinc-900 px-3 py-2.5 rounded-xl hover:bg-black/5 transition-all duration-150">Sign in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="flex items-center text-sm font-medium text-zinc-900 px-3 py-2.5 rounded-xl bg-zinc-100 hover:bg-zinc-200 transition-all duration-150">Get started</a>
                            @endif
                        </div>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-zinc-100">
        <div class="max-w-6xl mx-auto px-5 sm:px-6 h-14 flex items-center justify-between">
            <p class="text-xs text-zinc-400">&copy; {{ date('Y') }} Slidd</p>
            <div class="flex items-center gap-5">
                <a href="{{ route('privacy') }}" class="text-xs text-zinc-400 hover:text-zinc-700 transition-colors duration-150">Privacy</a>
                <a href="{{ route('terms') }}" class="text-xs text-zinc-400 hover:text-zinc-700 transition-colors duration-150">Terms</a>
                <a href="{{ route('security') }}" class="text-xs text-zinc-400 hover:text-zinc-700 transition-colors duration-150">Security</a>
            </div>
        </div>
    </footer>

    <script>
        const navbar = document.getElementById('navbar');
        const mobileMenu = document.getElementById('mobile-menu');
        const menuBtn = document.getElementById('menu-btn');
        const iconOpen = document.getElementById('icon-open');
        const iconClose = document.getElementById('icon-close');
        const glassClasses = ['bg-white/80', 'backdrop-blur-md', 'border-b', 'border-zinc-200/60', 'shadow-sm'];
        let menuOpen = false;

        function applyGlass() {
            navbar.classList.add(...glassClasses);
        }

        function removeGlass() {
            if (!menuOpen) navbar.classList.remove(...glassClasses);
        }

        function onScroll() {
            window.scrollY > 8 ? applyGlass() : removeGlass();
        }

        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();

        menuBtn.addEventListener('click', () => {
            menuOpen = !menuOpen;
            mobileMenu.classList.toggle('hidden', !menuOpen);
            iconOpen.classList.toggle('hidden', menuOpen);
            iconClose.classList.toggle('hidden', !menuOpen);
            menuOpen ? applyGlass() : onScroll();
        });
    </script>

</body>
</html>
