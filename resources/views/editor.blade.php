<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slidd — Editor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [contenteditable]:focus { outline: none; }
        [contenteditable]:empty::before {
            content: attr(data-placeholder);
            color: #d1d5db;
            pointer-events: none;
        }
    </style>
</head>
<body class="h-full flex flex-col bg-white antialiased" style="font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif;">
    <header class="h-11 shrink-0 flex items-center gap-3 px-4 border-b border-gray-100 bg-white z-50">
        <a href="/dashboard"
           class="flex items-center justify-center w-7 h-7 rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-all"
           title="Back to Dashboard">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
        </a>
        <span class="w-px h-4 bg-gray-200 shrink-0"></span>
        <input
            type="text"
            value="My Presentation"
            spellcheck="false"
            class="flex-1 min-w-0 max-w-xs text-sm font-medium text-gray-800 bg-transparent rounded-md px-2 py-1 outline-none hover:bg-gray-50 focus:bg-gray-100 transition-colors"
        >
        <div class="ml-auto flex items-center gap-2">
            <div class="flex items-center bg-gray-100 rounded-lg p-0.5 gap-0.5">
                <button class="text-xs font-medium px-3 py-1 rounded-md bg-white text-gray-900 shadow-sm transition-all">
                    Solid Text
                </button>
                <button class="text-xs font-medium px-3 py-1 rounded-md text-gray-500 hover:text-gray-700 transition-colors">
                    Galaxy Space
                </button>
            </div>
            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-all">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                </svg>
                Share
            </button>
            <button class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-white bg-gray-900 hover:bg-gray-700 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Export .slidd
            </button>
        </div>
    </header>
    <div class="flex flex-1 overflow-hidden">
        <aside class="w-52 shrink-0 flex flex-col border-r border-gray-100" style="background:#fafafa;">
            <div class="px-3 pt-4 pb-2 shrink-0">
                <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Slides</span>
            </div>

            <nav class="flex-1 overflow-y-auto px-2 pb-2 space-y-0.5">
                <div class="flex items-center gap-2 px-2 py-2 rounded-lg bg-white border border-gray-200 shadow-sm cursor-pointer">
                    <div class="w-10 h-7 shrink-0 rounded border border-gray-200 bg-white overflow-hidden flex items-stretch">
                        <div class="w-1 h-full bg-gray-900 rounded-l-sm"></div>
                        <div class="flex-1 p-1 space-y-1">
                            <div class="h-1 bg-gray-800 rounded-full" style="width:80%"></div>
                            <div class="h-0.5 bg-gray-300 rounded-full"></div>
                            <div class="h-0.5 bg-gray-300 rounded-full" style="width:65%"></div>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-gray-900 truncate">Introduction</p>
                        <p class="text-[10px] text-gray-400">Solid Text</p>
                    </div>
                    <span class="text-[10px] text-gray-300 font-medium shrink-0">1</span>
                </div>
                <div class="flex items-center gap-2 px-2 py-2 rounded-lg border border-transparent hover:bg-white hover:border-gray-200 hover:shadow-sm cursor-pointer transition-all">
                    <div class="w-10 h-7 shrink-0 rounded border border-gray-200 overflow-hidden flex items-stretch" style="background:#f9fafb">
                        <div class="w-1 h-full bg-gray-300 rounded-l-sm"></div>
                        <div class="flex-1 p-1 space-y-1">
                            <div class="h-1 bg-gray-400 rounded-full" style="width:75%"></div>
                            <div class="h-0.5 bg-gray-300 rounded-full"></div>
                            <div class="h-0.5 bg-gray-300 rounded-full" style="width:50%"></div>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 truncate">The Problem</p>
                        <p class="text-[10px] text-gray-400">Solid Text</p>
                    </div>
                    <span class="text-[10px] text-gray-300 font-medium shrink-0">2</span>
                </div>
                <div class="flex items-center gap-2 px-2 py-2 rounded-lg border border-transparent hover:bg-white hover:border-gray-200 hover:shadow-sm cursor-pointer transition-all">
                    <div class="w-10 h-7 shrink-0 rounded border border-gray-700 overflow-hidden" style="background:#0d1117;">
                        <div class="w-full h-full p-1 space-y-1">
                            <div class="h-1 rounded-full" style="width:80%;background:#ff7b72;opacity:.75;"></div>
                            <div class="h-0.5 rounded-full" style="background:#a5d6ff;opacity:.6;"></div>
                            <div class="h-0.5 rounded-full" style="width:60%;background:#d2a8ff;opacity:.6;"></div>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 truncate">Galaxy View</p>
                        <p class="text-[10px] text-gray-400">Galaxy Space</p>
                    </div>
                    <span class="text-[10px] text-gray-300 font-medium shrink-0">3</span>
                </div>
                <div class="flex items-center gap-2 px-2 py-2 rounded-lg border border-transparent hover:bg-white hover:border-gray-200 hover:shadow-sm cursor-pointer transition-all">
                    <div class="w-10 h-7 shrink-0 rounded border border-gray-200 overflow-hidden flex items-stretch" style="background:#f9fafb;">
                        <div class="w-1 h-full bg-gray-300 rounded-l-sm"></div>
                        <div class="flex-1 p-1 space-y-1">
                            <div class="h-1 bg-gray-400 rounded-full" style="width:60%"></div>
                            <div class="h-0.5 bg-gray-300 rounded-full" style="width:85%"></div>
                            <div class="h-0.5 bg-gray-300 rounded-full" style="width:45%"></div>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 truncate">Solution</p>
                        <p class="text-[10px] text-gray-400">Solid Text</p>
                    </div>
                    <span class="text-[10px] text-gray-300 font-medium shrink-0">4</span>
                </div>
                <div class="flex items-center gap-2 px-2 py-2 rounded-lg border border-transparent hover:bg-white hover:border-gray-200 hover:shadow-sm cursor-pointer transition-all">
                    <div class="w-10 h-7 shrink-0 rounded border border-gray-200 overflow-hidden flex items-stretch" style="background:#f9fafb;">
                        <div class="w-1 h-full bg-gray-300 rounded-l-sm"></div>
                        <div class="flex-1 p-1 space-y-1">
                            <div class="h-1 bg-gray-400 rounded-full" style="width:70%"></div>
                            <div class="h-0.5 bg-gray-300 rounded-full" style="width:40%"></div>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-medium text-gray-600 truncate">Thank You</p>
                        <p class="text-[10px] text-gray-400">Solid Text</p>
                    </div>
                    <span class="text-[10px] text-gray-300 font-medium shrink-0">5</span>
                </div>

            </nav>
            <div class="px-2 pb-3 pt-2 border-t border-gray-100 shrink-0">
                <button class="w-full flex items-center justify-center gap-1.5 py-2 text-xs font-medium text-gray-400 hover:text-gray-700 border border-dashed border-gray-200 hover:border-gray-400 hover:bg-white rounded-lg transition-all">
                    <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Add Slide
                </button>
            </div>
        </aside>
        <main class="flex-1 overflow-auto" style="background:#f4f4f5;">
            <div class="flex justify-center px-8 py-10 min-h-full">
                <div class="w-full max-w-2xl flex flex-col gap-5">
                    <article class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="flex items-center gap-2 px-6 py-2.5 border-b border-gray-50">
                            <span class="text-[10px] text-gray-300 font-medium uppercase tracking-widest">Slide 1</span>
                            <span class="text-[10px] text-gray-200">·</span>
                            <span class="text-[10px] text-gray-400 font-medium">Introduction</span>
                        </div>
                        <div class="px-12 py-10 space-y-0.5">
                            <div class="group relative -mx-2">
                                <div class="absolute -left-5 top-2.5 flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-1 rounded text-gray-300 hover:text-gray-500 hover:bg-gray-100 transition-all" title="Add block">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                    </button>
                                    <button class="p-1 rounded text-gray-300 hover:text-gray-500 hover:bg-gray-100 transition-all cursor-grab" title="Drag to reorder">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                                        </svg>
                                    </button>
                                </div>
                                <h1
                                    contenteditable="true"
                                    spellcheck="false"
                                    data-placeholder="Untitled"
                                    class="w-full text-[2rem] font-bold text-gray-900 tracking-tight leading-snug rounded-lg px-2 py-1 hover:bg-gray-50 focus:bg-gray-50 transition-colors"
                                >Building the Future of Presentations</h1>
                            </div>
                            <div class="h-2"></div>
                            <div class="group relative -mx-2">
                                <div class="absolute -left-5 top-1.5 flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-1 rounded text-gray-300 hover:text-gray-500 hover:bg-gray-100 transition-all">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                    </button>
                                    <button class="p-1 rounded text-gray-300 hover:text-gray-500 hover:bg-gray-100 transition-all cursor-grab">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                                        </svg>
                                    </button>
                                </div>
                                <p
                                    contenteditable="true"
                                    data-placeholder="Write something, or press / for commands…"
                                    class="w-full text-base text-gray-500 leading-7 rounded-lg px-2 py-1 hover:bg-gray-50 focus:bg-gray-50 transition-colors"
                                >Slidd is a developer-first presentation tool that blends the clarity of a document editor with the power of an infinite canvas. Start with a thought — ship a story.</p>
                            </div>
                            <div class="group relative -mx-2">
                                <div class="absolute -left-5 top-1.5 flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-1 rounded text-gray-300 hover:text-gray-500 hover:bg-gray-100 transition-all">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                    </button>
                                    <button class="p-1 rounded text-gray-300 hover:text-gray-500 hover:bg-gray-100 transition-all cursor-grab">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                                        </svg>
                                    </button>
                                </div>
                                <p
                                    contenteditable="true"
                                    data-placeholder="Write something, or press / for commands…"
                                    class="w-full text-base text-gray-500 leading-7 rounded-lg px-2 py-1 hover:bg-gray-50 focus:bg-gray-50 transition-colors"
                                >Connect your ideas visually in Galaxy Space — an infinite node canvas where blocks become nodes and relationships become edges.</p>
                            </div>
                            <div class="h-4"></div>
                            <div class="group relative -mx-2">
                                <div class="absolute -left-5 top-3 flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-1 rounded text-gray-300 hover:text-gray-500 hover:bg-gray-100 transition-all">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                    </button>
                                    <button class="p-1 rounded text-gray-300 hover:text-gray-500 hover:bg-gray-100 transition-all cursor-grab">
                                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="mx-2 rounded-xl overflow-hidden" style="background:#0d1117;">
                                    <div class="flex items-center justify-between px-4 py-2.5" style="background:#161b22;border-bottom:1px solid rgba(255,255,255,0.05);">
                                        <div class="flex items-center gap-3">
                                            <div class="flex gap-1.5">
                                                <span class="block w-2.5 h-2.5 rounded-full" style="background:#ff5f57;"></span>
                                                <span class="block w-2.5 h-2.5 rounded-full" style="background:#febc2e;"></span>
                                                <span class="block w-2.5 h-2.5 rounded-full" style="background:#28c840;"></span>
                                            </div>
                                            <span class="text-xs font-medium" style="color:#6e7681;">php</span>
                                        </div>
                                        <button
                                            class="flex items-center gap-1 text-xs font-medium transition-colors"
                                            style="color:#6e7681;"
                                            onmouseover="this.style.color='#c9d1d9'"
                                            onmouseout="this.style.color='#6e7681'"
                                        >
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/>
                                            </svg>
                                            Copy
                                        </button>
                                    </div>
                                    <div class="px-5 py-5 overflow-x-auto">
                                        <pre class="text-sm leading-6" style="font-family:'JetBrains Mono','Fira Code','Cascadia Code',monospace;color:#e6edf3;"><code><span style="color:#ff7b72;">class</span> <span style="color:#ffa657;">Block</span> <span style="color:#ff7b72;">extends</span> <span style="color:#ffa657;">Model</span>
<span style="color:#e6edf3;">{</span>
    <span style="color:#ff7b72;">protected</span> <span style="color:#e6edf3;">$casts</span> <span style="color:#ff7b72;">=</span> <span style="color:#e6edf3;">[</span>
        <span style="color:#a5d6ff;">'content'</span>  <span style="color:#e6edf3;">=></span> <span style="color:#a5d6ff;">'array'</span><span style="color:#e6edf3;">,</span>
        <span style="color:#a5d6ff;">'style'</span>    <span style="color:#e6edf3;">=></span> <span style="color:#a5d6ff;">'array'</span><span style="color:#e6edf3;">,</span>
        <span style="color:#a5d6ff;">'position'</span> <span style="color:#e6edf3;">=></span> <span style="color:#a5d6ff;">'array'</span><span style="color:#e6edf3;">,</span>
        <span style="color:#a5d6ff;">'edges'</span>    <span style="color:#e6edf3;">=></span> <span style="color:#a5d6ff;">'array'</span><span style="color:#e6edf3;">,</span>
    <span style="color:#e6edf3;">];</span>

    <span style="color:#ff7b72;">public function</span> <span style="color:#d2a8ff;">slide</span><span style="color:#e6edf3;">(): </span><span style="color:#ffa657;">BelongsTo</span>
    <span style="color:#e6edf3;">{</span>
        <span style="color:#ff7b72;">return</span> <span style="color:#e6edf3;">$this-></span><span style="color:#d2a8ff;">belongsTo</span><span style="color:#e6edf3;">(</span><span style="color:#ffa657;">Slide</span><span style="color:#e6edf3;">::</span><span style="color:#ff7b72;">class</span><span style="color:#e6edf3;">);</span>
    <span style="color:#e6edf3;">}</span>
<span style="color:#e6edf3;">}</span></code></pre>
                                    </div>
                                </div>
                            </div>
                            <div class="h-4"></div>
                            <div class="group relative -mx-2">
                                <p
                                    contenteditable="true"
                                    data-placeholder="Type '/' for commands…"
                                    class="w-full text-base leading-7 rounded-lg px-2 py-1 hover:bg-gray-50 focus:bg-gray-50 transition-colors"
                                    style="color:#d1d5db;"
                                ></p>
                            </div>
                        </div>
                    </article>
                    <div class="flex items-center justify-center gap-3 pb-8">
                        <button class="p-1.5 rounded-lg text-gray-300 hover:text-gray-600 hover:bg-gray-200 transition-all">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                            </svg>
                        </button>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-900"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                        </div>
                        <span class="text-xs font-medium text-gray-400 tabular-nums w-8 text-center">1 / 5</span>
                        <button class="p-1.5 rounded-lg text-gray-300 hover:text-gray-600 hover:bg-gray-200 transition-all">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </main>

    </div>

</body>
</html>
