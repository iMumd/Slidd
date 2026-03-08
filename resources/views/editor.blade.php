<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} — Slidd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased" style="font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;" x-data="editor()" @keydown.escape.window="shareOpen = false">

    {{-- Navbar --}}
    <header class="h-14 bg-white border-b border-gray-100 flex items-center justify-between px-4 shrink-0">

        {{-- Left: back + title --}}
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-slate-900 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <span class="text-sm font-semibold text-slate-900 truncate max-w-xs">{{ $project->title }}</span>
        </div>

        {{-- Center: view toggles --}}
        <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1">
            <button @click="view = 'editor'" :class="view === 'editor' ? 'bg-white text-slate-900 shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/>
                </svg>
                Editor
            </button>
            <button @click="view = 'preview'" :class="view === 'preview' ? 'bg-white text-slate-900 shadow-sm' : 'text-gray-400 hover:text-gray-600'" class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.574-3.007-9.964-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Preview
            </button>
        </div>

        {{-- Right: share + export --}}
        <div class="flex items-center gap-2">

            {{-- Share button + dropdown --}}
            <div class="relative">
                <button @click="shareOpen = !shareOpen" class="flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-slate-900 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                    </svg>
                    Share
                </button>

                <div x-show="shareOpen"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     @click.outside="shareOpen = false"
                     class="absolute right-0 top-full mt-1.5 w-72 bg-white border border-gray-100 rounded-xl shadow-lg p-4 z-50"
                     style="display:none;">
                    <p class="text-xs font-semibold text-slate-900 mb-1">Share this presentation</p>
                    <p class="text-xs text-gray-400 mb-3">Anyone with the link can view it.</p>
                    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 mb-3">
                        <span class="text-xs text-gray-500 flex-1 truncate">{{ url('/s/' . $project->slug) }}</span>
                    </div>
                    <button @click="copyLink()" class="w-full text-xs font-medium bg-slate-900 hover:bg-slate-800 text-white py-2 rounded-lg transition-colors" x-text="linkCopied ? 'Copied!' : 'Copy link'">Copy link</button>
                </div>
            </div>

            <button class="flex items-center gap-1.5 text-xs font-medium text-white bg-slate-900 hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Export .slidd
            </button>
        </div>
    </header>

    {{-- Workspace --}}
    <div class="flex" style="height:calc(100vh - 3.5rem);">

        {{-- Slide panel --}}
        <aside class="w-60 bg-white border-r border-gray-100 flex flex-col shrink-0 overflow-hidden">
            <div class="px-4 pt-4 pb-2">
                <span class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase">Slides</span>
            </div>

            <div class="flex-1 overflow-y-auto px-3 pb-3 space-y-2">
                {{-- Slide 1 thumbnail (active) --}}
                <div class="relative cursor-pointer rounded-lg overflow-hidden border-2 border-slate-900 ring-2 ring-slate-900/10">
                    <div class="aspect-video bg-white flex items-start p-3">
                        <div class="space-y-1.5 w-full">
                            <div class="h-2 bg-gray-800 rounded-sm w-3/4"></div>
                            <div class="h-1.5 bg-gray-200 rounded-sm w-full"></div>
                            <div class="h-1.5 bg-gray-200 rounded-sm w-5/6"></div>
                            <div class="h-4 bg-gray-900 rounded-sm w-full mt-2"></div>
                        </div>
                    </div>
                    <div class="absolute bottom-1 right-1.5 text-[9px] text-gray-400 font-medium">1</div>
                </div>
            </div>

            <div class="px-3 pb-4 pt-2 border-t border-gray-100">
                <button class="w-full flex items-center justify-center gap-1.5 py-2.5 border border-dashed border-gray-200 hover:border-gray-400 rounded-lg text-xs text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Slide
                </button>
            </div>
        </aside>

        {{-- Canvas --}}
        <main class="flex-1 overflow-y-auto" style="background:#f4f4f5;">
            <div class="flex justify-center py-10 px-6">
                <div class="w-full max-w-3xl bg-white rounded-xl border border-gray-200 shadow-sm" style="min-height:520px; padding:3.5rem 4rem;">

                    {{-- H1 --}}
                    <h1 class="text-4xl font-bold text-slate-900 leading-tight mb-5" style="letter-spacing:-0.02em;">{{ $project->title }}</h1>

                    {{-- Body text --}}
                    <p class="text-base text-gray-500 leading-relaxed mb-7">Start building your presentation. Add text, images, code blocks, and more. Use the sidebar to manage your slides.</p>

                    {{-- Code block --}}
                    <div class="rounded-xl overflow-hidden mb-7">
                        <div class="flex items-center justify-between px-4 py-2.5 bg-zinc-900">
                            <div class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-full bg-red-500/70 block"></span>
                                <span class="w-3 h-3 rounded-full bg-yellow-500/70 block"></span>
                                <span class="w-3 h-3 rounded-full bg-green-500/70 block"></span>
                            </div>
                            <span class="text-xs text-zinc-500 font-mono">index.ts</span>
                            <div class="w-14"></div>
                        </div>
                        <div class="bg-zinc-950 px-5 py-4">
                            <pre class="text-sm leading-loose font-mono"><code><span class="text-blue-400">const</span> <span class="text-emerald-400">slidd</span> <span class="text-gray-300">=</span> <span class="text-purple-400">new</span> <span class="text-yellow-400">Presentation</span><span class="text-gray-300">(</span><span class="text-orange-400">'{{ addslashes($project->title) }}'</span><span class="text-gray-300">);</span>
<span class="text-zinc-500">// Add your first slide</span>
<span class="text-emerald-400">slidd</span><span class="text-gray-300">.</span><span class="text-blue-300">addSlide</span><span class="text-gray-300">({</span>
  <span class="text-red-400">type</span><span class="text-gray-300">:</span> <span class="text-orange-400">'{{ $project->type }}'</span><span class="text-gray-300">,</span>
<span class="text-gray-300">});</span></code></pre>
                        </div>
                    </div>

                    {{-- Add block hint --}}
                    <button class="flex items-center gap-2 text-sm text-gray-300 hover:text-gray-400 transition-colors select-none group">
                        <span class="flex items-center justify-center w-5 h-5 rounded-md border border-dashed border-gray-200 group-hover:border-gray-300 transition-colors">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </span>
                        Click to add a block
                    </button>

                </div>
            </div>
        </main>

    </div>

    {{-- Toast notification --}}
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-1"
         class="fixed bottom-5 right-5 flex items-center gap-3 bg-white border border-gray-100 px-4 py-3 rounded-xl shadow-lg z-50"
         style="display:none;">
        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
        <span class="text-sm font-medium text-slate-900" x-text="toastMsg"></span>
    </div>

<script>
function editor() {
    return {
        view: 'editor',
        shareOpen: false,
        linkCopied: false,
        showToast: false,
        toastMsg: '',
        _timer: null,

        async copyLink() {
            const url = @js(url('/s/' . $project->slug));
            try {
                await navigator.clipboard.writeText(url);
                this.linkCopied = true;
                this.shareOpen = false;
                this.toast('Preview link copied!');
                setTimeout(() => { this.linkCopied = false; }, 2500);
            } catch {
                this.toast('Copy failed — grab the URL manually.');
            }
        },

        toast(msg) {
            clearTimeout(this._timer);
            this.toastMsg = msg;
            this.showToast = true;
            this._timer = setTimeout(() => { this.showToast = false; }, 3000);
        },
    };
}
</script>

</body>
</html>
