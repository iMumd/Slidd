<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} — Slidd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">

    <style>
        /* ── shared with editor ─────────────────────────────── */
        .code-view pre  { margin:0; border-radius:0; background:transparent!important; padding:0; }
        .code-view .hljs { background:transparent!important; padding:1.1rem 1.4rem; font-size:13.5px; line-height:1.7; white-space:pre-wrap; word-break:break-word; }

        .txt-block > div,
        .txt-block > p  { margin:0; min-height:1em; }
        .txt-block ul   { list-style-type:disc;    list-style-position:inside; margin-bottom:.5rem; }
        .txt-block ol   { list-style-type:decimal; list-style-position:inside; margin-bottom:.5rem; }
        .txt-block li   { margin-bottom:.2rem; }
        .txt-block li::marker { color:inherit; font-family:inherit; }

        /* ── read-only badge ────────────────────────────────── */
        .ro-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 9px; border-radius: 99px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            font-size: 11px; font-weight: 500; color: #64748b;
            user-select: none;
        }
    </style>
</head>
<body class="h-full antialiased"
      style="font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;"
      x-data="viewer()"
      @keydown.escape.window="isShortcutsOpen = false"
      @keydown.arrow-right.window="nextSlide()"
      @keydown.arrow-left.window="prevSlide()"
      x-init="init()">

    {{-- ══ HEADER ═══════════════════════════════════════════════ --}}
    <header class="h-14 bg-white border-b border-gray-100 flex items-center justify-between px-4 shrink-0 z-40 relative">

        {{-- Left: logo + title --}}
        <div class="flex items-center gap-3 min-w-0">
            <a href="/" class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 transition-colors shrink-0">
                <span class="text-sm font-bold text-slate-900 tracking-tight">S</span>
            </a>
            <span class="text-gray-300 select-none">/</span>
            <span class="text-sm font-semibold text-slate-900 truncate max-w-xs">{{ $project->title }}</span>
            <span class="ro-badge hidden sm:inline-flex">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                View only
            </span>
        </div>

        {{-- Right: controls --}}
        <div class="flex items-center gap-2">

            {{-- Author --}}
            <div class="hidden md:flex items-center gap-2 mr-1">
                <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0"
                     style="background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    {{ strtoupper(substr($project->user->name, 0, 1)) }}
                </div>
                <span class="text-xs text-gray-400">by <span class="font-medium text-slate-600">{{ $project->user->name }}</span></span>
            </div>

            <div class="w-px h-5 bg-gray-100 hidden md:block"></div>

            {{-- Shortcuts --}}
            <button @click="isShortcutsOpen = true"
                    title="Keyboard shortcuts"
                    class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-gray-400 hover:text-slate-700 hover:bg-gray-100 transition-colors">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <rect x="2" y="6" width="20" height="13" rx="2.5" stroke="currentColor" stroke-width="1.75" fill="none"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M6 14h.01M10 14h.01M14 14h4"/>
                </svg>
                <span class="text-xs font-medium hidden sm:inline">Shortcuts</span>
            </button>

            {{-- Copy link --}}
            <button @click="copyLink()"
                    class="flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-slate-900 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                </svg>
                <span x-text="linkCopied ? 'Copied!' : 'Share'" class="hidden sm:inline"></span>
            </button>

@auth
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-1.5 text-xs font-medium text-white bg-slate-900 hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors">
                    Dashboard
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="flex items-center gap-1.5 text-xs font-medium text-white bg-slate-900 hover:bg-slate-800 px-3 py-1.5 rounded-lg transition-colors">
                    Get started free
                </a>
            @endauth

        </div>
    </header>

    {{-- ══ BODY ════════════════════════════════════════════════ --}}
    <div class="flex" style="height:calc(100vh - 3.5rem);">

        {{-- ── Sidebar ─────────────────────────────────────────── --}}
        <aside class="w-56 flex flex-col shrink-0 overflow-hidden" style="background:#f0f0f0; border-right:1px solid #e2e2e2;">

            <div class="flex items-center justify-between px-4 pt-4 pb-3">
                <span class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase">Slides</span>
                <span class="text-[10px] text-gray-400" x-text="slides.length"></span>
            </div>

            <div class="flex-1 overflow-y-auto px-3 pb-3 space-y-2.5">
                <template x-for="(slide, index) in slides" :key="slide.id">
                    <div @click="goTo(index)"
                         class="group/slide relative cursor-pointer select-none"
                         style="filter:drop-shadow(0 2px 6px rgba(0,0,0,.10));">

                        <div :class="current === index
                                 ? 'ring-2 ring-blue-500'
                                 : 'ring-1 ring-black/[.07] group-hover/slide:ring-black/20'"
                             class="rounded-md overflow-hidden transition-all duration-150">

                            {{-- Mini canvas --}}
                            <div class="aspect-video bg-white relative overflow-hidden" style="padding:8% 9%;">

                                <template x-if="slide.blocks.length === 0">
                                    <div class="absolute inset-0 flex flex-col justify-center items-center gap-1.5 px-5">
                                        <div class="h-[5px] rounded-full bg-gray-200 w-2/3"></div>
                                        <div class="h-[3px] rounded-full bg-gray-100 w-full"></div>
                                        <div class="h-[3px] rounded-full bg-gray-100 w-5/6"></div>
                                    </div>
                                </template>

                                <template x-for="(b, bi) in slide.blocks.slice(0, 6)" :key="bi">
                                    <div class="mb-[4px] w-full">

                                        <template x-if="b.type === 'text'">
                                            <div>
                                                <template x-if="bi === 0">
                                                    <div class="h-[5px] rounded-full mb-[3px]" style="background:#1e293b; width:68%;"></div>
                                                </template>
                                                <template x-if="bi !== 0">
                                                    <div class="space-y-[2.5px]">
                                                        <div class="h-[3px] rounded-full" style="background:#cbd5e1; width:100%;"></div>
                                                        <div class="h-[3px] rounded-full" style="background:#e2e8f0; width:82%;"></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        <template x-if="b.type === 'code'">
                                            <div class="rounded-[3px] overflow-hidden" style="background:#1e1e2e; padding:4px 5px;">
                                                <div class="flex gap-[3px] mb-[3px]">
                                                    <div class="w-[5px] h-[5px] rounded-full" style="background:#ff5f57;"></div>
                                                    <div class="w-[5px] h-[5px] rounded-full" style="background:#febc2e;"></div>
                                                    <div class="w-[5px] h-[5px] rounded-full" style="background:#28c840;"></div>
                                                </div>
                                                <div class="space-y-[2px]">
                                                    <div class="h-[2.5px] rounded-full" style="background:#7c6af5; width:55%;"></div>
                                                    <div class="h-[2.5px] rounded-full" style="background:#4a4a6a; width:80%;"></div>
                                                    <div class="h-[2.5px] rounded-full" style="background:#4a4a6a; width:65%;"></div>
                                                </div>
                                            </div>
                                        </template>

                                    </div>
                                </template>

                            </div>

                            {{-- Footer bar --}}
                            <div class="flex items-center px-2 py-1"
                                 :class="current === index ? 'bg-blue-500' : 'bg-gray-100'"
                                 style="border-top:1px solid rgba(0,0,0,.06);">
                                <span class="text-[9px] font-semibold leading-none"
                                      :class="current === index ? 'text-white/80' : 'text-gray-400'"
                                      x-text="'Slide ' + (index + 1)"></span>
                            </div>

                        </div>
                    </div>
                </template>
            </div>

        </aside>

        {{-- ── Canvas ──────────────────────────────────────────── --}}
        <main class="flex-1 overflow-y-auto" style="background:#f4f4f5;">
            <div class="px-6">
                <div class="bg-white border border-gray-200 shadow-lg rounded-md max-w-4xl mx-auto w-full min-h-[1056px] p-12 sm:p-16 mb-24 mt-8 relative">

                    {{-- Empty slide --}}
                    <template x-if="activeBlocks.length === 0">
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none select-none">
                            <svg class="w-8 h-8 text-gray-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p class="text-sm text-gray-300 font-medium">Empty slide</p>
                        </div>
                    </template>

                    {{-- Blocks — read-only --}}
                    <template x-for="(block, index) in activeBlocks" :key="index">
                        <div class="mb-5">

                            {{-- Text block --}}
                            <template x-if="block.type === 'text'">
                                <div class="txt-block outline-none w-full break-words leading-relaxed py-1"
                                     x-html="block.content || ''"></div>
                            </template>

                            {{-- Code block --}}
                            <template x-if="block.type === 'code'">
                                <div class="rounded-2xl overflow-hidden ring-1 ring-white/5"
                                     style="background:#272822; box-shadow:0 4px 28px rgba(0,0,0,.32);">

                                    <div class="flex items-center gap-2.5 px-4 py-2.5"
                                         style="background:rgba(0,0,0,.28); border-bottom:1px solid rgba(255,255,255,.055);">
                                        <span class="w-3 h-3 rounded-full shrink-0" style="background:#ff5f57;"></span>
                                        <span class="w-3 h-3 rounded-full shrink-0" style="background:#febc2e;"></span>
                                        <span class="w-3 h-3 rounded-full shrink-0" style="background:#28c840;"></span>
                                        <span x-show="block.detectedLang"
                                              x-text="block.detectedLang"
                                              class="ml-1.5 text-[10px] font-bold text-zinc-500 uppercase tracking-widest select-none">
                                        </span>
                                    </div>

                                    <div class="code-view min-h-[52px]">
                                        <template x-if="block.content && block.content.trim().length > 0">
                                            <pre><code class="hljs" x-html="block.highlighted"></code></pre>
                                        </template>
                                        <template x-if="!block.content || block.content.trim().length === 0">
                                            <div class="px-5 py-4 font-mono text-sm" style="color:rgba(255,255,255,.18);">
                                                // empty code block
                                            </div>
                                        </template>
                                    </div>

                                </div>
                            </template>

                        </div>
                    </template>

                </div>
            </div>
        </main>

    </div>

    {{-- ══ SHORTCUTS MODAL ═════════════════════════════════════ --}}
    <div x-show="isShortcutsOpen"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"  x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         style="display:none;">

        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="isShortcutsOpen = false"></div>

        <div x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="relative w-full max-w-sm bg-white/95 backdrop-blur-xl border border-gray-200/80 rounded-2xl shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100">
                        <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <rect x="2" y="6" width="20" height="13" rx="2.5" stroke="currentColor" stroke-width="1.75" fill="none"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M6 14h.01M10 14h.01M14 14h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Keyboard Shortcuts</p>
                        <p class="text-xs text-gray-400">Viewer shortcuts</p>
                    </div>
                </div>
                <button @click="isShortcutsOpen = false"
                        class="flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-slate-900 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="px-6 py-4 space-y-1">

                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase mb-3">Navigation</p>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Next slide</span>
                    <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">→</kbd>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Previous slide</span>
                    <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">←</kbd>
                </div>

                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase pt-4 mb-3">Actions</p>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Copy share link</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⇧</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">C</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm text-slate-700">Close modal</span>
                    <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">Esc</kbd>
                </div>

            </div>

            <div class="px-6 py-3 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-400">⌘ = Ctrl on Windows</span>
                <button @click="isShortcutsOpen = false" class="text-xs font-medium text-slate-600 hover:text-slate-900 transition-colors">Close</button>
            </div>

        </div>
    </div>

    {{-- ══ TOAST ════════════════════════════════════════════════ --}}
    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"  x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
         class="fixed bottom-5 right-5 flex items-center gap-3 bg-white border border-gray-100 px-4 py-3 rounded-xl shadow-lg z-50"
         style="display:none;">
        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
        <span class="text-sm font-medium text-slate-900" x-text="toastMsg"></span>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
    function viewer() {
        return {
            slides          : {!! \Illuminate\Support\Js::from($slides) !!},
            current         : 0,
            isShortcutsOpen : false,
            linkCopied      : false,
            showToast       : false,
            toastMsg        : '',
            _timer          : null,

            get activeBlocks() {
                return this.slides[this.current]?.blocks ?? [];
            },

            init() {
                // highlight all code blocks up front
                this.slides.forEach(slide => {
                    slide.blocks.forEach(block => {
                        if (block.type === 'code' && block.content?.trim()) {
                            const r       = hljs.highlightAuto(block.content);
                            block.highlighted  = r.value;
                            if (!block.detectedLang) block.detectedLang = r.language ?? '';
                        } else {
                            block.highlighted = '';
                        }
                    });
                });

                // global hotkeys
                document.addEventListener('keydown', e => {
                    const mod = e.ctrlKey || e.metaKey;
                    if (mod && e.shiftKey && e.key.toLowerCase() === 'c') {
                        e.preventDefault();
                        this.copyLink();
                    }
                }, true);
            },

            goTo(index) {
                if (index < 0 || index >= this.slides.length) return;
                this.current = index;
            },

            nextSlide() { this.goTo(this.current + 1); },
            prevSlide()  { this.goTo(this.current - 1); },

async copyLink() {
                try {
                    await navigator.clipboard.writeText(window.location.href);
                    this.linkCopied = true;
                    this.toast('Share link copied!');
                    setTimeout(() => { this.linkCopied = false; }, 2500);
                } catch {
                    this.toast('Could not copy link.');
                }
            },

            toast(msg) {
                clearTimeout(this._timer);
                this.toastMsg  = msg;
                this.showToast = true;
                this._timer    = setTimeout(() => { this.showToast = false; }, 3000);
            },
        };
    }
    </script>
</body>
</html>
