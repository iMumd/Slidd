<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Slidd'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white antialiased min-h-screen" style="font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;">

<div class="flex min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Mobile overlay --}}
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/30 z-40 md:hidden"
        style="display:none;"
    ></div>

    {{-- Sidebar --}}
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-100 flex flex-col shrink-0 transition-transform duration-200 ease-in-out md:relative md:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
    >
        <div class="p-6 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="font-bold text-xl text-slate-900 tracking-tight">Slidd</a>
            <button @click="sidebarOpen = false" class="md:hidden p-1 rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <nav class="flex-1 px-0 space-y-0.5 mt-1">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 p-2 mx-4 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-gray-50 text-slate-900' : 'text-gray-500 hover:bg-gray-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 p-2 mx-4 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('profile.edit') ? 'bg-gray-50 text-slate-900' : 'text-gray-500 hover:bg-gray-50 hover:text-slate-900' }}">
                <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </nav>

        <div class="mt-auto border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 p-4 text-sm text-gray-500 hover:text-slate-900 transition-colors">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                    Log out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Mobile top bar --}}
        <div class="md:hidden flex items-center h-14 px-4 border-b border-gray-100 bg-white shrink-0">
            <button @click="sidebarOpen = true" class="p-1.5 rounded-lg text-gray-400 hover:text-slate-900 hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
            <a href="{{ route('dashboard') }}" class="ml-3 font-bold text-xl text-slate-900 tracking-tight">Slidd</a>
        </div>

        <main class="flex-1">
            @yield('content')
        </main>
    </div>

</div>

</body>
</html>
