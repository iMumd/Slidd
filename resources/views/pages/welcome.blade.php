@extends('layouts.guest')

@section('title', 'Slidd — Build slides like you write code')
@section('description', 'A minimal, developer-first presentation builder. Write slides like code with a Notion-like editor and an infinite Galaxy canvas for visual thinking.')
@section('canonical', url('/'))

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebApplication",
    "name": "Slidd",
    "url": "{{ url('/') }}",
    "description": "A minimal, developer-first presentation builder with an infinite Galaxy canvas.",
    "applicationCategory": "ProductivityApplication",
    "operatingSystem": "Web",
    "offers": {
        "@@type": "Offer",
        "price": "0",
        "priceCurrency": "USD"
    },
    "author": {
        "@@type": "Organization",
        "name": "Slidd"
    }
}
</script>
@endpush

@section('content')

<section class="relative min-h-screen flex items-center justify-center px-6 pt-14 overflow-hidden">
    <div class="absolute inset-0 flex items-center justify-center pointer-events-none" aria-hidden="true">
        <div class="w-[700px] h-[700px] rounded-full" style="background: radial-gradient(circle at center, rgba(99,102,241,0.10) 0%, rgba(139,92,246,0.06) 35%, transparent 70%);"></div>
    </div>
    <div class="relative text-center max-w-3xl mx-auto py-28">
        <div class="inline-flex items-center gap-2 text-xs text-zinc-500 border border-zinc-200 bg-white/80 rounded-full px-3.5 py-1.5 mb-8 tracking-wide font-medium shadow-sm">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 inline-block"></span>
            Now in early access
        </div>
        <h1 class="text-6xl md:text-7xl font-extrabold tracking-tight text-zinc-900 leading-[1.04] mb-6">
            Build slides<br>like you<br>write code.
        </h1>
        <p class="text-zinc-500 text-xl mb-10 leading-relaxed max-w-lg mx-auto">
            A distraction-free presentation workspace for developers. Notion-like editing, native code blocks, and an infinite canvas.
        </p>
        <div class="flex items-center justify-center gap-3 flex-wrap">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-zinc-900 text-white text-sm font-semibold rounded-xl hover:bg-zinc-700 transition-colors duration-150 shadow-sm">
                    Start for free
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                </a>
            @endif
            <a href="https://github.com/iMumd/Slidd" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-zinc-600 text-sm font-medium rounded-xl border border-zinc-200 hover:border-zinc-300 hover:text-zinc-900 transition-colors duration-150">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                Star on GitHub
            </a>
        </div>
    </div>
</section>

<section class="px-6 pb-28">
    <div class="max-w-5xl mx-auto">
        <div class="rounded-2xl overflow-hidden shadow-2xl border border-zinc-200/70" style="background:#fff;">
            <div class="flex items-center gap-2 px-4 py-3 border-b border-zinc-200" style="background:#f4f4f5;">
                <div class="w-3 h-3 rounded-full bg-red-400/90"></div>
                <div class="w-3 h-3 rounded-full bg-yellow-400/90"></div>
                <div class="w-3 h-3 rounded-full bg-green-400/90"></div>
                <div class="flex-1 flex justify-center">
                    <div class="bg-white rounded-md h-6 w-52 border border-zinc-200/80 flex items-center justify-center gap-1.5 px-3">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#a1a1aa" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <span class="text-[10px] text-zinc-400 font-medium">slidd.app/editor/my-talk</span>
                    </div>
                </div>
            </div>
            <div class="flex" style="height:480px;">
                <div class="w-52 border-r border-zinc-100 flex flex-col shrink-0" style="background:#f9f9f9;">
                    <div class="px-3 pt-4 pb-3 border-b border-zinc-100">
                        <div class="text-[10px] font-semibold text-zinc-400 uppercase tracking-wider mb-3">My Deck</div>
                        <div class="space-y-2">
                            <div class="bg-white border border-indigo-200 rounded-lg p-2.5 shadow-sm cursor-pointer">
                                <div class="h-1.5 w-3/4 bg-zinc-800 rounded-sm mb-1.5"></div>
                                <div class="h-1 w-1/2 bg-zinc-200 rounded-sm"></div>
                            </div>
                            <div class="bg-white border border-zinc-100 rounded-lg p-2.5">
                                <div class="h-1.5 w-2/3 bg-zinc-200 rounded-sm mb-1.5"></div>
                                <div class="h-1 w-2/5 bg-zinc-100 rounded-sm"></div>
                            </div>
                            <div class="bg-white border border-zinc-100 rounded-lg p-2.5">
                                <div class="h-1.5 w-3/4 bg-zinc-200 rounded-sm mb-1.5"></div>
                                <div class="h-1 w-1/2 bg-zinc-100 rounded-sm"></div>
                            </div>
                            <div class="bg-white border border-zinc-100 rounded-lg p-2.5 border-dashed flex items-center justify-center" style="height:40px;">
                                <div class="flex items-center gap-1">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#d4d4d8" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                                    <span class="text-[9px] text-zinc-300">Add slide</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-auto px-3 py-3 border-t border-zinc-100">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-indigo-100 flex items-center justify-center shrink-0">
                                <span class="text-[10px] text-indigo-600 font-bold">U</span>
                            </div>
                            <div>
                                <div class="h-1.5 w-14 bg-zinc-300 rounded-sm mb-1"></div>
                                <div class="h-1 w-10 bg-zinc-200 rounded-sm"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 flex flex-col">
                    <div class="border-b border-zinc-100 px-4 py-2.5 flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="h-6 w-6 rounded-md bg-zinc-100 flex items-center justify-center">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#71717a" stroke-width="2.5"><path d="M4 6h16M4 12h10"/></svg>
                            </div>
                            <div class="h-6 w-6 rounded-md bg-zinc-100 flex items-center justify-center">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#71717a" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                            </div>
                            <div class="h-6 w-6 rounded-md bg-zinc-100 flex items-center justify-center">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="#71717a" stroke-width="2.5"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                            </div>
                        </div>
                        <div class="flex-1"></div>
                        <div class="h-7 px-3 rounded-lg bg-zinc-900 flex items-center justify-center">
                            <span class="text-[10px] text-white font-semibold">Present</span>
                        </div>
                    </div>
                    <div class="flex-1 flex items-center justify-center p-8" style="background: radial-gradient(circle at 50% 50%, #f8f8f8 0%, #f4f4f4 100%);">
                        <div class="w-full max-w-md bg-white rounded-2xl border border-zinc-100/80 shadow-md p-8">
                            <div class="h-1.5 w-12 bg-indigo-300 rounded-full mb-5"></div>
                            <div class="h-5 w-4/5 bg-zinc-900 rounded-md mb-3"></div>
                            <div class="h-3 w-full bg-zinc-100 rounded mb-1.5"></div>
                            <div class="h-3 w-5/6 bg-zinc-100 rounded mb-1.5"></div>
                            <div class="h-3 w-3/5 bg-zinc-100 rounded mb-6"></div>
                            <div class="rounded-xl overflow-hidden border border-zinc-800" style="background:#18181b;">
                                <div class="flex items-center gap-2 px-4 py-2.5 border-b border-zinc-800">
                                    <div class="h-1.5 w-6 bg-indigo-400/70 rounded"></div>
                                    <div class="h-1.5 w-10 bg-emerald-400/70 rounded"></div>
                                    <div class="h-1.5 w-5 bg-zinc-600 rounded"></div>
                                </div>
                                <div class="p-4 space-y-2">
                                    <div class="h-2 w-3/4 bg-zinc-700 rounded"></div>
                                    <div class="h-2 w-1/2 bg-zinc-700 rounded"></div>
                                    <div class="h-2 w-2/3 bg-zinc-700 rounded"></div>
                                    <div class="h-2 w-2/5 bg-zinc-700 rounded"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-28 px-6" style="background-image: linear-gradient(rgba(0,0,0,0.035) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.035) 1px, transparent 1px); background-size: 32px 32px; background-color: #fafafa;">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-bold tracking-tight text-zinc-900 mb-4">Everything you need.<br>Nothing you don't.</h2>
            <p class="text-zinc-500 text-lg max-w-md mx-auto">Designed from the ground up for developers who care about their tools.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-7 hover:shadow-lg hover:border-zinc-300 transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center mb-5">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#52525b" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
                <h3 class="text-base font-semibold text-zinc-900 mb-2">Fluid Block Editor</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Write slides like you write code. A keyboard-first, block-based canvas that keeps your flow uninterrupted.</p>
            </div>
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-7 hover:shadow-lg hover:border-zinc-300 transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center mb-5">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#52525b" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
                </div>
                <h3 class="text-base font-semibold text-zinc-900 mb-2">Native Code Blocks</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Syntax-highlighted, gorgeous code blocks. Built specifically for technical talks and demos.</p>
            </div>
            <div class="bg-white rounded-2xl border border-zinc-200/80 p-7 hover:shadow-lg hover:border-zinc-300 transition-all duration-200">
                <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center mb-5">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="#52525b" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M5 3l14 9-14 9V3z"/><circle cx="19" cy="12" r="1"/></svg>
                </div>
                <h3 class="text-base font-semibold text-zinc-900 mb-2">Infinite Canvas</h3>
                <p class="text-sm text-zinc-500 leading-relaxed">Switch to Galaxy Space — an infinite, pannable canvas for complex diagrams, flows, and ideas.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-32 px-6 bg-white border-t border-zinc-100">
    <div class="max-w-xl mx-auto text-center">
        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-5">For developers, by developers</p>
        <h2 class="text-5xl md:text-6xl font-extrabold tracking-tight text-zinc-900 leading-[1.05] mb-6">Drop the<br>bloated tools.</h2>
        <p class="text-zinc-500 text-lg mb-10 leading-relaxed">
            You don't need 40 themes or a plugin marketplace. You need slides that work and get out of your way.
        </p>
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="inline-flex items-center gap-2.5 px-8 py-4 bg-zinc-900 text-white text-sm font-semibold rounded-xl hover:bg-zinc-700 transition-colors duration-150 shadow-sm">
                Build your first deck
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
            </a>
        @endif
    </div>
</section>

@endsection
