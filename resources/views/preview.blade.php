<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} — Slidd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-white antialiased flex flex-col" style="font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;">

    <header class="h-11 shrink-0 flex items-center justify-between px-5 border-b border-gray-100">
        <a href="/" class="text-sm font-bold text-slate-900 tracking-tight">Slidd</a>
        <div class="flex items-center gap-3">
            <span class="text-xs text-gray-400 capitalize">{{ $project->type === 'galaxy' ? 'Galaxy Workspace' : 'Standard Slides' }}</span>
            @auth
                <a href="{{ route('dashboard') }}" class="text-xs font-medium text-gray-500 hover:text-slate-900 transition-colors">Dashboard</a>
            @else
                <a href="{{ route('register') }}" class="text-xs font-medium text-white bg-slate-900 px-3 py-1.5 rounded-lg hover:bg-slate-800 transition-colors">Get started</a>
            @endauth
        </div>
    </header>

    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center" style="background:#f4f4f5;">
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-10 py-12 max-w-md w-full">
            <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 flex items-center justify-center mb-5 mx-auto">
                @if ($project->type === 'galaxy')
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456z"/></svg>
                @else
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>
                @endif
            </div>
            <h1 class="text-xl font-bold text-slate-900 mb-2">{{ $project->title }}</h1>
            <p class="text-sm text-gray-400 mb-1">Shared via Slidd</p>
            <p class="text-xs text-gray-300">Viewer coming soon.</p>
        </div>
    </div>

</body>
</html>
