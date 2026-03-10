<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} — Slidd</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='22' fill='%230f172a'/><text y='74' x='50' text-anchor='middle' font-size='62' font-family='system-ui,sans-serif' font-weight='700' fill='white'>S</text></svg>">
    <link rel="icon" href="/favicon.ico" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <style>
        /* ── shared blocks ──────────────────────────────────── */
        .code-view pre  { margin:0; border-radius:0; background:transparent!important; padding:0; }
        .code-view .hljs { background:transparent!important; padding:1.1rem 1.4rem; font-size:13.5px; line-height:1.7; white-space:pre-wrap; word-break:break-word; }
        .txt-block > div, .txt-block > p { margin:0; min-height:1em; }
        .txt-block ul { list-style-type:disc;    list-style-position:inside; margin-bottom:.5rem; }
        .txt-block ol { list-style-type:decimal; list-style-position:inside; margin-bottom:.5rem; }
        .txt-block li { margin-bottom:.2rem; }
        .txt-block li::marker { color:inherit; font-family:inherit; }

        /* ── header ─────────────────────────────────────────── */
        .hdr {
            height: 3.5rem;
            background: #fff;
            border-bottom: 1px solid #f3f4f6;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 0.75rem;
            flex-shrink: 0; position: relative; z-index: 40;
        }
        .hdr-left {
            display: flex; align-items: center; gap: 0.5rem;
            min-width: 0; overflow: hidden;
        }
        .hdr-right {
            display: flex; align-items: center; gap: 0.25rem;
            flex-shrink: 0;
        }
        .hdr-sep { color: #d1d5db; user-select: none; flex-shrink: 0; }
        .hdr-title {
            font-size: .875rem; font-weight: 600; color: #0f172a;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            max-width: 120px; flex-shrink: 1;
        }
        .hdr-badge {
            display: none;
            align-items: center; gap: 5px;
            padding: 3px 9px; border-radius: 99px;
            background: #f1f5f9; border: 1px solid #e2e8f0;
            font-size: 11px; font-weight: 500; color: #64748b;
            user-select: none; flex-shrink: 0;
        }
        .hdr-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 8px; border-radius: 8px;
            color: #9ca3af; cursor: pointer; background: transparent; border: none;
            transition: background .15s, color .15s; font-size: .75rem; font-weight: 500;
        }
        .hdr-btn:hover { background: #f3f4f6; color: #1e293b; }
        .hdr-btn-text { display: none; }
        .hdr-author  { display: none; }
        .hdr-divider { display: none; width: 1px; height: 20px; background: #f3f4f6; }
        .hdr-cta {
            display: flex; align-items: center; gap: 6px;
            font-size: .75rem; font-weight: 500; color: #fff;
            background: #0f172a; padding: 6px 12px; border-radius: 8px;
            text-decoration: none; transition: background .15s; white-space: nowrap;
        }
        .hdr-cta:hover { background: #1e293b; }
        .hdr-cta-long  { display: none; }
        .hdr-cta-short { display: inline; }

        /* ── sm breakpoint (640px+) ─────────────────────────── */
        @media (min-width: 640px) {
            .hdr            { padding: 0 1rem; }
            .hdr-left       { gap: 0.75rem; }
            .hdr-right      { gap: 0.5rem; }
            .hdr-title      { max-width: 20rem; }
            .hdr-badge      { display: inline-flex; }
            .hdr-btn-text   { display: inline; }
            .hdr-btn        { padding: 6px 10px; }
            .hdr-cta-long   { display: inline; }
            .hdr-cta-short  { display: none; }
        }
        /* ── md breakpoint (768px+) ─────────────────────────── */
        @media (min-width: 768px) {
            .hdr-author  { display: flex; align-items: center; gap: 8px; margin-right: 4px; }
            .hdr-divider { display: block; }
        }

        /* ── sidebar ─────────────────────────────────────────── */
        .sv-sidebar { display: none; }
        @media (min-width: 640px) {
            .sv-sidebar { display: flex; flex-direction: column; flex-shrink: 0; width: 14rem; overflow: hidden; }
        }

        /* ── mobile nav ──────────────────────────────────────── */
        .mobile-nav {
            position: fixed; bottom: 0; left: 0; right: 0; z-index: 40;
            display: flex; align-items: center; justify-content: space-between;
            padding: 8px 16px; background: #fff; border-top: 1px solid #f3f4f6;
        }
        @media (min-width: 640px) { .mobile-nav { display: none; } }
        .mobile-nav-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 12px; border-radius: 8px; border: none; background: transparent;
            font-size: .875rem; font-weight: 500; cursor: pointer; transition: background .15s;
        }
        .mobile-nav-btn:disabled { color: #d1d5db; cursor: default; }
        .mobile-nav-btn:not(:disabled) { color: #1e293b; }
        .mobile-nav-btn:not(:disabled):active { background: #f3f4f6; }

        /* ── main canvas ─────────────────────────────────────── */
        .sv-main { flex: 1; overflow-y: auto; background: #f4f4f5; }
        .sv-canvas-wrap { padding: 12px; }
        .sv-canvas {
            background: #fff; border: 1px solid #e5e7eb;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
            border-radius: 6px; max-width: 56rem;
            margin: 0 auto; width: 100%;
            min-height: 600px; padding: 1.5rem;
            position: relative; margin-bottom: 24px; margin-top: 12px;
        }
        @media (min-width: 640px) {
            .sv-canvas-wrap { padding: 24px; }
            .sv-canvas { padding: 3rem; min-height: 1056px; margin-bottom: 6rem; margin-top: 2rem; }
        }
        @media (min-width: 1024px) {
            .sv-canvas { padding: 4rem; }
        }

        /* ── toast ───────────────────────────────────────────── */
        .toast-pos { bottom: 5rem; right: 1.25rem; }
        @media (min-width: 640px) { .toast-pos { bottom: 1.25rem; } }
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
    <header class="hdr">

        <div class="hdr-left">
            <a href="/" style="display:flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:8px;flex-shrink:0;text-decoration:none;" onmouseenter="this.style.background='#f3f4f6'" onmouseleave="this.style.background='transparent'">
                <span style="font-size:.875rem;font-weight:700;color:#0f172a;letter-spacing:-.025em;">S</span>
            </a>
            <span class="hdr-sep">/</span>
            <span class="hdr-title">{{ $project->title }}</span>
            <span class="hdr-badge">
                <svg style="width:12px;height:12px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                View only
            </span>
        </div>

        <div class="hdr-right">
            <div class="hdr-author">
                <div style="width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:700;flex-shrink:0;background:linear-gradient(135deg,#6366f1,#8b5cf6);">
                    {{ strtoupper(substr($project->user->name, 0, 1)) }}
                </div>
                <span style="font-size:.75rem;color:#9ca3af;">by <span style="font-weight:500;color:#475569;">{{ $project->user->name }}</span></span>
            </div>
            <div class="hdr-divider"></div>

            <button @click="isShortcutsOpen = true" class="hdr-btn" title="Keyboard shortcuts">
                <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <rect x="2" y="6" width="20" height="13" rx="2.5" stroke="currentColor" stroke-width="1.75" fill="none"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M6 14h.01M10 14h.01M14 14h4"/>
                </svg>
                <span class="hdr-btn-text">Shortcuts</span>
            </button>

            <button @click="copyLink()" class="hdr-btn">
                <svg style="width:14px;height:14px;flex-shrink:0;" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                </svg>
                <span class="hdr-btn-text" x-text="linkCopied ? 'Copied!' : 'Share'">Share</span>
            </button>

@auth
            <a href="{{ route('dashboard') }}" class="hdr-cta">Dashboard</a>
            @else
            <a href="{{ route('register') }}" class="hdr-cta">
                <span class="hdr-cta-short">Sign up</span>
                <span class="hdr-cta-long">Get started free</span>
            </a>
@endauth
        </div>
    </header>

    {{-- ══ BODY ════════════════════════════════════════════════ --}}
    <div style="display:flex;height:calc(100vh - 3.5rem);">

        {{-- ── Sidebar ─────────────────────────────────────────── --}}
        <aside class="sv-sidebar" style="background:#f0f0f0; border-right:1px solid #e2e2e2;">

            <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 16px 12px;">
                <span style="font-size:10px;font-weight:600;color:#9ca3af;letter-spacing:.1em;text-transform:uppercase;">Slides</span>
                <span style="font-size:10px;color:#9ca3af;" x-text="slides.length"></span>
            </div>

            <div style="flex:1;overflow-y:auto;padding:0 12px 12px;display:flex;flex-direction:column;gap:10px;">
                <template x-for="(slide, index) in slides" :key="slide.id">
                    <div @click="goTo(index)"
                         style="cursor:pointer;user-select:none;filter:drop-shadow(0 2px 6px rgba(0,0,0,.10));">
                        <div :style="current === index ? 'outline:2px solid #3b82f6;outline-offset:-2px;' : 'outline:1px solid rgba(0,0,0,.07);'"
                             style="border-radius:6px;overflow:hidden;transition:outline .15s;">
                            <div style="aspect-ratio:16/9;background:#fff;position:relative;overflow:hidden;padding:8% 9%;">
                                <template x-if="slide.blocks.length === 0">
                                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;justify-content:center;align-items:center;gap:6px;padding:0 20px;">
                                        <div style="height:5px;border-radius:99px;background:#e5e7eb;width:66%;"></div>
                                        <div style="height:3px;border-radius:99px;background:#f3f4f6;width:100%;"></div>
                                        <div style="height:3px;border-radius:99px;background:#f3f4f6;width:83%;"></div>
                                    </div>
                                </template>
                                <template x-for="(b, bi) in slide.blocks.slice(0, 6)" :key="bi">
                                    <div style="margin-bottom:4px;width:100%;">
                                        <template x-if="b.type === 'text'">
                                            <div>
                                                <template x-if="bi === 0">
                                                    <div style="height:5px;border-radius:99px;margin-bottom:3px;background:#1e293b;width:68%;"></div>
                                                </template>
                                                <template x-if="bi !== 0">
                                                    <div style="display:flex;flex-direction:column;gap:2.5px;">
                                                        <div style="height:3px;border-radius:99px;background:#cbd5e1;width:100%;"></div>
                                                        <div style="height:3px;border-radius:99px;background:#e2e8f0;width:82%;"></div>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                        <template x-if="b.type === 'code'">
                                            <div style="border-radius:3px;overflow:hidden;background:#1e1e2e;padding:4px 5px;">
                                                <div style="display:flex;gap:3px;margin-bottom:3px;">
                                                    <div style="width:5px;height:5px;border-radius:50%;background:#ff5f57;"></div>
                                                    <div style="width:5px;height:5px;border-radius:50%;background:#febc2e;"></div>
                                                    <div style="width:5px;height:5px;border-radius:50%;background:#28c840;"></div>
                                                </div>
                                                <div style="display:flex;flex-direction:column;gap:2px;">
                                                    <div style="height:2.5px;border-radius:99px;background:#7c6af5;width:55%;"></div>
                                                    <div style="height:2.5px;border-radius:99px;background:#4a4a6a;width:80%;"></div>
                                                    <div style="height:2.5px;border-radius:99px;background:#4a4a6a;width:65%;"></div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                            <div style="display:flex;align-items:center;padding:4px 8px;border-top:1px solid rgba(0,0,0,.06);"
                                 :style="current === index ? 'background:#3b82f6;' : 'background:#f3f4f6;'">
                                <span style="font-size:9px;font-weight:600;line-height:1;"
                                      :style="current === index ? 'color:rgba(255,255,255,.8)' : 'color:#9ca3af'"
                                      x-text="'Slide ' + (index + 1)"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </aside>

        {{-- ── Canvas ──────────────────────────────────────────── --}}
        <main class="sv-main">
            <div class="sv-canvas-wrap">
                <div class="sv-canvas">

                    <template x-if="activeBlocks.length === 0">
                        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;user-select:none;">
                            <svg style="width:32px;height:32px;color:#e5e7eb;margin-bottom:12px;" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p style="font-size:.875rem;color:#d1d5db;font-weight:500;">Empty slide</p>
                        </div>
                    </template>

                    <template x-for="(block, index) in activeBlocks" :key="index">
                        <div style="margin-bottom:20px;">
                            <template x-if="block.type === 'text'">
                                <div class="txt-block" style="outline:none;width:100%;word-break:break-word;line-height:1.625;padding:4px 0;"
                                     x-html="block.content || ''"></div>
                            </template>
                            <template x-if="block.type === 'code'">
                                <div style="border-radius:16px;overflow:hidden;outline:1px solid rgba(255,255,255,.05);background:#272822;box-shadow:0 4px 28px rgba(0,0,0,.32);">
                                    <div style="display:flex;align-items:center;gap:10px;padding:10px 16px;background:rgba(0,0,0,.28);border-bottom:1px solid rgba(255,255,255,.055);">
                                        <span style="width:12px;height:12px;border-radius:50%;flex-shrink:0;background:#ff5f57;"></span>
                                        <span style="width:12px;height:12px;border-radius:50%;flex-shrink:0;background:#febc2e;"></span>
                                        <span style="width:12px;height:12px;border-radius:50%;flex-shrink:0;background:#28c840;"></span>
                                        <span x-show="block.detectedLang" x-text="block.detectedLang"
                                              style="margin-left:6px;font-size:10px;font-weight:700;color:#71717a;text-transform:uppercase;letter-spacing:.1em;user-select:none;"></span>
                                    </div>
                                    <div class="code-view" style="min-height:52px;">
                                        <template x-if="block.content && block.content.trim().length > 0">
                                            <pre><code class="hljs" x-html="block.highlighted"></code></pre>
                                        </template>
                                        <template x-if="!block.content || block.content.trim().length === 0">
                                            <div style="padding:16px 20px;font-family:monospace;font-size:.875rem;color:rgba(255,255,255,.18);">// empty code block</div>
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

    {{-- ══ MOBILE SLIDE NAV ══════════════════════════════════════ --}}
    <div class="mobile-nav">
        <button @click="prevSlide()" :disabled="current === 0" class="mobile-nav-btn">
            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
            </svg>
            Prev
        </button>
        <div style="display:flex;flex-direction:column;align-items:center;gap:2px;">
            <span style="font-size:.75rem;font-weight:600;color:#1e293b;" x-text="(current + 1) + ' / ' + slides.length"></span>
            <span style="font-size:10px;color:#9ca3af;">Swipe to navigate</span>
        </div>
        <button @click="nextSlide()" :disabled="current === slides.length - 1" class="mobile-nav-btn">
            Next
            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </button>
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
                <button @click="isShortcutsOpen = false" class="flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-slate-900 hover:bg-gray-100 transition-colors">
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
         class="toast-pos fixed flex items-center gap-3 bg-white border border-gray-100 px-4 py-3 rounded-xl shadow-lg z-50"
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
                this.slides.forEach(slide => {
                    slide.blocks.forEach(block => {
                        if (block.type === 'code' && block.content?.trim()) {
                            const r = hljs.highlightAuto(block.content);
                            block.highlighted  = r.value;
                            if (!block.detectedLang) block.detectedLang = r.language ?? '';
                        } else {
                            block.highlighted = '';
                        }
                    });
                });

                document.addEventListener('keydown', e => {
                    const mod = e.ctrlKey || e.metaKey;
                    if (mod && e.shiftKey && e.key.toLowerCase() === 'c') {
                        e.preventDefault(); this.copyLink();
                    }
                }, true);

                // touch swipe
                let _tx = 0, _ty = 0;
                document.addEventListener('touchstart', e => { _tx = e.touches[0].clientX; _ty = e.touches[0].clientY; }, { passive: true });
                document.addEventListener('touchend', e => {
                    const dx = e.changedTouches[0].clientX - _tx;
                    const dy = e.changedTouches[0].clientY - _ty;
                    if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 48) dx < 0 ? this.nextSlide() : this.prevSlide();
                }, { passive: true });
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
                } catch { this.toast('Could not copy link.'); }
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
