@extends('layouts.guest')

@section('title', 'Introduction — Slidd')
@section('description', 'Learn how Slidd works — a developer-first presentation builder with a Notion-like slide editor and an infinite Galaxy canvas for visual thinking.')
@section('canonical', url('/introduction'))

@section('content')

<div class="max-w-2xl mx-auto px-6 pt-32 pb-24">

    <div class="mb-14">
        <a href="/" class="flex w-max items-center gap-1.5 text-xs text-zinc-400 hover:text-zinc-700 transition-colors duration-150 mb-8">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Back
        </a>
        <p class="text-xs text-zinc-400 font-medium uppercase tracking-widest mb-3">Introduction</p>
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900 mb-4">What is Slidd?</h1>
        <p class="text-zinc-500 text-lg leading-relaxed">A minimal presentation builder for developers who are tired of fighting their tools instead of focusing on their content.</p>
    </div>

    <div class="space-y-14 text-zinc-600 leading-relaxed">

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-4">The problem</h2>
            <blockquote class="border-l-2 border-zinc-200 pl-4 text-sm text-zinc-500 leading-relaxed italic mb-4">
                "Why does making a slide deck feel harder than writing the actual code?"
            </blockquote>
            <p class="text-sm leading-relaxed">Most presentation tools are built for non-technical users — packed with drag-and-drop widgets, theme galleries, and animation timelines. Developers end up spending more time fighting the UI than building the talk. Code is always an afterthought: awkward screenshots or broken syntax in a floating text box.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-4">Why Slidd?</h2>
            <p class="text-sm leading-relaxed mb-5">Slidd takes its cues from tools developers already love — Notion for its fluid writing, Linear for its restraint, and code editors for their keyboard-first philosophy.</p>
            <div class="space-y-2.5">
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded-md bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <p class="text-sm">Block-based editor — headings, paragraphs, code, images, all keyboard-first.</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded-md bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <p class="text-sm">Code blocks are first-class — syntax highlighted, theme-aware, beautiful on every slide.</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded-md bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <p class="text-sm">Galaxy Space — infinite canvas for when ideas outgrow a linear slide format.</p>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-5 h-5 rounded-md bg-indigo-50 border border-indigo-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <p class="text-sm">Export as a portable <code class="text-xs bg-zinc-100 text-zinc-700 px-1.5 py-0.5 rounded font-mono">.slidd</code> file — a JSON tree of your entire project.</p>
                </div>
            </div>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-4">Core concepts</h2>
            <div class="space-y-3">
                <div class="rounded-xl border border-zinc-200 px-5 py-4 flex gap-4 items-start">
                    <div class="w-8 h-8 rounded-lg bg-violet-50 border border-violet-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 mb-0.5">Project</p>
                        <p class="text-sm text-zinc-500">A deck. Has a title, visibility (public or private), and an auto-generated URL slug.</p>
                    </div>
                </div>
                <div class="rounded-xl border border-zinc-200 px-5 py-4 flex gap-4 items-start">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 mb-0.5">Slide</p>
                        <p class="text-sm text-zinc-500">A canvas inside a project. Two types: <span class="text-zinc-700 font-medium">Solid Text</span> (linear doc) or <span class="text-zinc-700 font-medium">Galaxy Space</span> (infinite canvas).</p>
                    </div>
                </div>
                <div class="rounded-xl border border-zinc-200 px-5 py-4 flex gap-4 items-start">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-zinc-900 mb-0.5">Block</p>
                        <p class="text-sm text-zinc-500">A content node on a slide — text, code, image, shape, edge. New block types never require a schema migration.</p>
                    </div>
                </div>
            </div>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-8">Roadmap</h2>

            <div class="space-y-8">

                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700">Shipped</span>
                        <div class="flex-1 h-px bg-emerald-100"></div>
                    </div>
                    <div class="relative pl-5 border-l border-emerald-200 space-y-0">
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Database schema</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Projects, slides, blocks with JSON edge support</p>
                            </div>
                        </div>
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Landing page &amp; authentication</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Register, login, password reset, email verification</p>
                            </div>
                        </div>
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Dashboard</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Project grid, create, rename, delete — with time-aware greeting</p>
                            </div>
                        </div>
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Solid Text editor</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Block-based authoring with drag-reorder, RTL/LTR, rich formatting, and pro hotkeys</p>
                            </div>
                        </div>
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Native code blocks</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Auto language detection and syntax highlighting powered by Highlight.js</p>
                            </div>
                        </div>
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Public sharing &amp; read-only preview</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Shareable link at <code class="font-mono text-xs">/s/{slug}</code> for both slides and Galaxy — no account needed</p>
                            </div>
                        </div>
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800"><code class="text-xs bg-zinc-100 text-zinc-600 px-1.5 py-0.5 rounded font-mono">.slidd</code> export &amp; import</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Portable JSON project bundle for both editor types — export and re-import anytime</p>
                            </div>
                        </div>
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Galaxy Space — infinite canvas editor</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Pan, zoom, drag nodes, draw bezier edge connections — text, code, and sticky note blocks</p>
                            </div>
                        </div>
                        <div class="relative pb-5">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Image blocks in Galaxy</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Upload images directly onto the canvas — resizable, connectable, persisted as base64</p>
                            </div>
                        </div>
                        <div class="relative">
                            <div class="absolute -left-[5px] top-1.5 w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-white ring-1 ring-emerald-300"></div>
                            <div class="pl-4">
                                <p class="text-sm font-medium text-zinc-800">Galaxy public preview</p>
                                <p class="text-xs text-zinc-400 mt-0.5">Full interactive canvas view for shared Galaxy projects — pan, zoom, read-only</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-4">Built with</h2>
            <div class="grid grid-cols-2 gap-3">

                <div class="rounded-xl border border-zinc-200 px-4 py-3 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-red-50 border border-red-100 flex items-center justify-center shrink-0">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="#ef4444"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-800">Laravel 12</p>
                        <p class="text-[11px] text-zinc-400">PHP 8.2+ backend</p>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 px-4 py-3 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-sky-50 border border-sky-100 flex items-center justify-center shrink-0">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="#0ea5e9"><path d="M12 6.5a1 1 0 110-2 1 1 0 010 2zM6 12a6 6 0 1112 0A6 6 0 016 12z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-800">Tailwind CSS</p>
                        <p class="text-[11px] text-zinc-400">Utility-first styling</p>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 px-4 py-3 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-cyan-50 border border-cyan-100 flex items-center justify-center shrink-0">
                        {{-- Alpine.js mountain icon --}}
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#0891b2" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 17 13 8 9 12 2 17"/><polyline points="16 17 13 8 9 14"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-800">Alpine.js</p>
                        <p class="text-[11px] text-zinc-400">Reactive UI, zero build step</p>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 px-4 py-3 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-yellow-50 border border-yellow-100 flex items-center justify-center shrink-0">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="#eab308"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-800">Vite</p>
                        <p class="text-[11px] text-zinc-400">Frontend build tooling</p>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 px-4 py-3 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-orange-50 border border-orange-100 flex items-center justify-center shrink-0">
                        {{-- Highlight.js flame-ish icon --}}
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#ea580c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"/><path d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-800">Highlight.js</p>
                        <p class="text-[11px] text-zinc-400">Auto syntax highlighting</p>
                    </div>
                </div>

                <div class="rounded-xl border border-zinc-200 px-4 py-3 flex items-center gap-3">
                    <div class="w-7 h-7 rounded-lg bg-zinc-50 border border-zinc-200 flex items-center justify-center shrink-0">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#52525b" stroke-width="2"><ellipse cx="12" cy="12" rx="10" ry="4"/><path d="M2 12c0 2.21 4.48 4 10 4s10-1.79 10-4"/><path d="M2 12v6c0 2.21 4.48 4 10 4s10-1.79 10-4v-6"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-zinc-800">SQLite / MySQL</p>
                        <p class="text-[11px] text-zinc-400">Relational database</p>
                    </div>
                </div>

            </div>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <div class="flex items-center gap-3 mb-5">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" class="text-zinc-700 shrink-0"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                <h2 class="text-base font-semibold text-zinc-900">Open source</h2>
            </div>
            <p class="text-sm text-zinc-500 leading-relaxed mb-4">Slidd is fully open source under the MIT license. You're free to self-host it, fork it, build on top of it, or contribute back. No black boxes.</p>
            <div class="rounded-xl border border-zinc-200 bg-zinc-50 px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-zinc-700 mb-0.5">Repository</p>
                    <a href="https://github.com/iMumd/Slidd" target="_blank" rel="noopener noreferrer" class="text-sm text-indigo-600 hover:text-indigo-800 font-mono break-all transition-colors duration-150">github.com/iMumd/Slidd</a>
                </div>
                <a href="https://github.com/iMumd/Slidd" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-900 text-white text-xs font-semibold rounded-lg hover:bg-zinc-700 transition-colors duration-150 shrink-0">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>
                    Star on GitHub
                </a>
            </div>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section class="text-center">
            <p class="text-sm text-zinc-500 mb-5">Ready to try it?</p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-zinc-900 text-white text-sm font-semibold rounded-xl hover:bg-zinc-700 transition-colors duration-150">
                    Get started for free
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg>
                </a>
            @endif
        </section>

    </div>
</div>

@endsection
