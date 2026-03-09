<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $project->title }} — Slidd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">

    <style>
        .code-view pre  {
            margin          : 0;
            border-radius   : 0;
            background      : transparent !important;
            padding         : 0;
        }
        .code-view .hljs {
            background      : transparent !important;
            padding         : 1.1rem 1.4rem;
            font-size       : 13.5px;
            line-height     : 1.7;
            white-space     : pre-wrap;
            word-break      : break-word;
        }

        .code-ta {
            display         : block;
            width           : 100%;
            min-height      : 80px;
            padding         : 1.1rem 1.4rem;
            background      : transparent;
            color           : #abb2bf;
            caret-color     : #e879f9;
            font-size       : 13.5px;
            line-height     : 1.7;
            outline         : none;
            resize          : none;
            border          : none;
            white-space     : pre-wrap;
            word-break      : break-word;
            box-sizing      : border-box;
            overflow        : hidden;
        }
        .code-ta::selection { background: rgba(232,121,249,.25); }

        .txt-block:empty:before {
            content         : attr(data-placeholder);
            color           : #d1d5db;
            pointer-events  : none;
        }

        .txt-block > div,
        .txt-block > p {
            margin     : 0;
            min-height : 1em;
        }

        .txt-block ul { list-style-type: disc;    list-style-position: inside; margin-bottom: 0.5rem; }
        .txt-block ol { list-style-type: decimal; list-style-position: inside; margin-bottom: 0.5rem; }
        .txt-block li { margin-bottom: 0.2rem; }
        .txt-block li::marker { color: inherit; font-family: inherit; }

    </style>
</head>
<body class="h-full antialiased"
      style="font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;"
      x-data="editor()"
      @keydown.escape.window="shareOpen = false; isShortcutsModalOpen = false"
      @mouseup.window="checkSelection()"
      @keyup.window="checkSelection()">

    <header class="h-14 bg-white border-b border-gray-100 flex items-center justify-between px-4 shrink-0 z-40 relative">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-slate-900 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                </svg>
            </a>
            <span class="text-sm font-semibold text-slate-900 truncate max-w-xs">{{ $project->title }}</span>
        </div>

        <div class="flex items-center gap-2">

            {{-- Shortcuts guide --}}
            <button @click="isShortcutsModalOpen = true"
                    title="Keyboard shortcuts"
                    class="flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-gray-400 hover:text-slate-700 hover:bg-gray-100 transition-colors group">
                <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <rect x="2" y="6" width="20" height="13" rx="2.5" stroke="currentColor" stroke-width="1.75" fill="none"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M6 14h.01M10 14h.01M14 14h4"/>
                </svg>
                <span class="text-xs font-medium">Shortcuts</span>
            </button>

            {{-- Save --}}
            <button @click="saveProject()"
                    :disabled="isSaving"
                    x-text="isSaving ? 'Saving...' : 'Save'"
                    class="flex items-center gap-1.5 text-xs font-medium text-white bg-slate-900 hover:bg-slate-800 disabled:opacity-50 disabled:cursor-not-allowed px-3 py-1.5 rounded-lg transition-colors">
                Save
            </button>

            {{-- Share --}}
            <div class="relative">
                <button @click="shareOpen = !shareOpen"
                        class="flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-slate-900 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
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
                    <button @click="copyLink()"
                            class="w-full text-xs font-medium bg-slate-900 hover:bg-slate-800 text-white py-2 rounded-lg transition-colors"
                            x-text="linkCopied ? 'Copied!' : 'Copy link'">Copy link</button>
                </div>
            </div>

            {{-- Export dropdown --}}
            <div class="relative" x-data="{ exportOpen: false }" @click.away="exportOpen = false">
                <button @click="exportOpen = !exportOpen"
                        class="flex items-center gap-1.5 text-xs font-medium text-gray-500 hover:text-slate-900 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                    Export
                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="exportOpen"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 top-full mt-1.5 w-44 bg-white border border-gray-100 rounded-xl shadow-lg py-1 z-50"
                     style="display:none;">
                    <button @click="exportOpen = false"
                            class="w-full text-left flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                        </svg>
                        Export as .slidd
                    </button>
                    <button @click="exportOpen = false"
                            class="w-full text-left flex items-center gap-2.5 px-3 py-2 text-xs text-gray-700 hover:bg-gray-50 transition-colors">
                        <svg class="w-3.5 h-3.5 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                        </svg>
                        Export as PDF
                    </button>
                </div>
            </div>

        </div>
    </header>

    <div class="flex" style="height:calc(100vh - 3.5rem);">

        {{-- Slide panel (static) --}}
        <aside class="w-60 bg-white border-r border-gray-100 flex flex-col shrink-0 overflow-hidden">
            <div class="px-4 pt-4 pb-2">
                <span class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase">Slides</span>
            </div>
            <div class="flex-1 overflow-y-auto px-3 pb-3 space-y-2">
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
            <div class="px-6">
                <div class="bg-white border border-gray-200 shadow-lg rounded-md max-w-4xl mx-auto w-full min-h-[1056px] p-12 sm:p-16 mb-24 mt-8 relative cursor-text"
                     @click.self="focusLastBlock()">

                    <template x-if="blocks.length === 0">
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none select-none">
                            <svg class="w-8 h-8 text-gray-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p class="text-sm text-gray-300 font-medium">Start typing by adding a block below</p>
                        </div>
                    </template>

                    <template x-for="block in blocks" :key="block.id">
                        <div class="relative mb-5 group/block">

                            {{-- Delete handle --}}
                            <button @click="deleteBlock(block.id)"
                                    class="absolute -right-9 top-2 opacity-0 group-hover/block:opacity-100 transition-opacity z-10
                                           flex items-center justify-center w-6 h-6 rounded-md hover:bg-red-50 text-gray-300 hover:text-red-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>

                            <template x-if="block.type === 'text'">
                                <div contenteditable="true"
                                     dir="auto"
                                     data-placeholder="Type something..."
                                     class="txt-block outline-none min-h-[1.5em] w-full break-words text-start cursor-text block leading-relaxed py-1"
                                     x-init="
                                         $el.innerHTML = block.content;
                                         document.execCommand('defaultParagraphSeparator', false, 'div');
                                         applyParaDirs($el);
                                     "
                                     @keydown.enter="$nextTick(() => applyParaDirs($event.target))"
                                     @input="
                                         if ($event.target.innerText.trim() === '') $event.target.innerHTML = '';
                                         block.content = $event.target.innerHTML;
                                         applyParaDirs($event.target);
                                     "
                                     @keydown.backspace="if ($event.target.innerText.trim() === '') { $event.preventDefault(); deleteBlock(block.id); focusLastBlock(); }">
                                </div>
                            </template>

                            <template x-if="block.type === 'code'">
                                <div class="rounded-2xl overflow-hidden ring-1 ring-white/5"
                                     style="background:#272822; box-shadow:0 4px 28px rgba(0,0,0,.32);">

                                    {{-- Header bar --}}
                                    <div class="flex items-center gap-2.5 px-4 py-2.5"
                                         style="background:rgba(0,0,0,.28); border-bottom:1px solid rgba(255,255,255,.055);">
                                        <span class="w-3 h-3 rounded-full shrink-0" style="background:#ff5f57;"></span>
                                        <span class="w-3 h-3 rounded-full shrink-0" style="background:#febc2e;"></span>
                                        <span class="w-3 h-3 rounded-full shrink-0" style="background:#28c840;"></span>

                                        {{-- Auto-detected language badge --}}
                                        <span x-show="block.detectedLang"
                                              x-text="block.detectedLang"
                                              class="ml-1.5 text-[10px] font-bold text-zinc-500 uppercase tracking-widest select-none">
                                        </span>

                                        {{-- Edit mode indicator --}}
                                        <span x-show="block.isEditing"
                                              class="ml-auto text-[10px] text-zinc-600 font-medium tracking-wide select-none">
                                            editing — click away to preview
                                        </span>
                                    </div>

                                    <div x-show="!block.isEditing"
                                         class="code-view cursor-text min-h-[52px]"
                                         @click="startEditing(block, $event.currentTarget)">

                                        <template x-if="block.content.trim().length > 0">
                                            <pre class="font-jetbrains"><code class="hljs" x-html="block.highlightedContent"></code></pre>
                                        </template>

                                        <template x-if="block.content.trim().length === 0">
                                            <div class="px-5 py-4 font-jetbrains text-sm"
                                                 style="color:rgba(255,255,255,.18);">
                                                // click to write code...
                                            </div>
                                        </template>

                                    </div>

                                    <textarea x-show="block.isEditing"
                                              x-model="block.content"
                                              @input="resizeTextarea($event.target)"
                                              @blur="stopEditing(block)"
                                              @keydown.tab.prevent="insertTab($event, block)"
                                              @keydown.backspace="if (block.content.length === 0) { $event.preventDefault(); deleteBlock(block.id); }"
                                              class="code-ta font-jetbrains"
                                              spellcheck="false"
                                              autocomplete="off"
                                              autocorrect="off"
                                              autocapitalize="off"
                                              placeholder="// start typing..."
                                              style="display:none;">
                                    </textarea>

                                </div>
                            </template>

                        </div>
                    </template>

                    <div class="mt-1 flex items-center gap-3">
                        <button @click="addTextBlock()"
                                class="flex items-center gap-1.5 text-xs text-gray-300 hover:text-gray-600 transition-colors select-none group">
                            <span class="flex items-center justify-center w-5 h-5 rounded-md border border-dashed border-gray-200 group-hover:border-gray-400 transition-colors">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                            </span>
                            Text
                        </button>
                        <span class="text-gray-200 select-none">·</span>
                        <button @click="addCodeBlock()"
                                class="flex items-center gap-1.5 text-xs text-gray-300 hover:text-gray-600 transition-colors select-none group">
                            <span class="flex items-center justify-center w-5 h-5 rounded-md border border-dashed border-gray-200 group-hover:border-gray-400 transition-colors font-mono text-[10px] leading-none">&lt;/&gt;</span>
                            Code
                        </button>
                    </div>

                </div>
            </div>
        </main>

    </div>

    <div x-show="showToolbar"
         :style="`top:${toolbarY}px; left:${toolbarX}px`"
         class="fixed z-50 -translate-x-1/2 -translate-y-full pb-2"
         style="display:none;">
        <div class="backdrop-blur-md bg-white/95 shadow-xl border border-gray-200 rounded-lg p-1.5 flex gap-0.5 items-center flex-wrap max-w-2xl">

            <div class="relative" @click.away="isFontMenuOpen = false">
                <button @mousedown.prevent="isFontMenuOpen = !isFontMenuOpen"
                        class="flex items-center gap-1 px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded transition-colors select-none">
                    <span x-text="currentFontName"></span>
                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="isFontMenuOpen"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"   x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full left-0 mt-1 w-36 bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-lg py-1 z-50 flex flex-col"
                     style="display:none;">
                    <template x-for="font in ['Arial', 'Cairo', 'Tajawal', 'Lotus', 'JetBrains Mono']" :key="font">
                        <button @mouseenter="_previewConfirmed = false; previewFont(font)"
                                @mouseleave="if (!_previewConfirmed) document.execCommand('undo')"
                                @mousedown.prevent="_previewConfirmed = true; selectFont(font)"
                                :style="`font-family: ${font}`"
                                class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 transition-colors text-gray-700"
                                x-text="font">
                        </button>
                    </template>
                </div>
            </div>

            <div class="relative" @click.away="isFontSizeMenuOpen = false">
                <button @mousedown.prevent="isFontSizeMenuOpen = !isFontSizeMenuOpen"
                        class="flex items-center gap-1 px-2 py-1 text-sm text-gray-700 hover:bg-gray-100 rounded transition-colors select-none min-w-[3rem]">
                    <span x-text="currentFontSize"></span>
                    <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="isFontSizeMenuOpen"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"   x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full left-0 mt-1 w-24 bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-lg py-1 z-50 flex flex-col max-h-56 overflow-y-auto"
                     style="display:none;">
                    <template x-for="size in ['8px','10px','12px','14px','16px','18px','20px','24px','30px','36px','48px','64px']" :key="size">
                        <button @mouseenter="_previewConfirmed = false; previewFontSize(size)"
                                @mouseleave="if (!_previewConfirmed) document.execCommand('undo')"
                                @mousedown.prevent="_previewConfirmed = true; selectFontSize(size)"
                                :class="currentFontSize === size ? 'bg-gray-100 font-semibold' : ''"
                                class="w-full text-left px-3 py-1.5 text-sm hover:bg-gray-100 transition-colors text-gray-700"
                                x-text="size">
                        </button>
                    </template>
                </div>
            </div>

            <div class="w-px h-4 bg-gray-200 mx-0.5"></div>

            <button @mousedown.prevent="formatText('bold')"      class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-800 font-bold text-sm transition-colors">B</button>
            <button @mousedown.prevent="formatText('italic')"    class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-800 italic text-sm transition-colors">I</button>
            <button @mousedown.prevent="formatText('underline')" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-800 underline text-sm transition-colors">U</button>

            <div class="w-px h-4 bg-gray-200 mx-0.5"></div>

            <button @mousedown.prevent="formatText('justifyLeft')"   title="Align left"   class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5"/></svg>
            </button>
            <button @mousedown.prevent="formatText('justifyCenter')" title="Align center" class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M6 12h12M3.75 17.25h16.5"/></svg>
            </button>
            <button @mousedown.prevent="formatText('justifyRight')"  title="Align right"  class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M12 12h8.25M3.75 17.25h16.5"/></svg>
            </button>

            <div class="w-px h-4 bg-gray-200 mx-0.5"></div>

            <button @mousedown.prevent="setBlockDirection('ltr')" title="Left to Right (LTR)"
                    class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-600 transition-colors text-[10px] font-bold tracking-tight select-none">
                LTR
            </button>
            <button @mousedown.prevent="setBlockDirection('rtl')" title="Right to Left (RTL)"
                    class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-600 transition-colors text-[10px] font-bold tracking-tight select-none">
                RTL
            </button>

            <div class="w-px h-4 bg-gray-200 mx-0.5"></div>

            {{-- Text Color --}}
            <div class="relative" @click.away="isTextColorMenuOpen = false">
                <button @mousedown.prevent="isTextColorMenuOpen = !isTextColorMenuOpen"
                        title="Text color"
                        class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 transition-colors select-none">
                    <span class="flex flex-col items-center gap-0">
                        <span class="text-xs font-bold text-slate-800 leading-none">A</span>
                        <span class="w-4 h-1 rounded-sm mt-0.5" :style="`background:${customTextColor}`"></span>
                    </span>
                </button>
                <div x-show="isTextColorMenuOpen"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"   x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full left-0 mt-1 bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-lg p-2 z-50 w-48 flex flex-col gap-2"
                     style="display:none;">
                    {{-- Preset swatches 5-col grid --}}
                    <div class="grid grid-cols-5 gap-1.5">
                        <template x-for="color in presetTextColors" :key="color">
                            <button @mousedown.prevent="customTextColor = color; applyTextColor(color)"
                                    :style="`background:${color}`"
                                    :title="color"
                                    class="w-6 h-6 rounded border border-white/20 hover:scale-110 transition-transform shadow-sm"
                                    :class="customTextColor === color ? 'ring-2 ring-offset-1 ring-slate-400' : ''">
                            </button>
                        </template>
                    </div>
                    <div class="h-px bg-gray-100"></div>
                    {{-- Custom color row --}}
                    <div class="flex items-center gap-2">
                        <div class="relative shrink-0">
                            <div class="w-7 h-7 rounded border border-gray-300 overflow-hidden cursor-pointer"
                                 :style="`background:${customTextColor}`">
                                <input type="color"
                                       x-model="customTextColor"
                                       @input="document.execCommand('foreColor', false, customTextColor)"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       title="Pick custom color">
                            </div>
                        </div>
                        <input type="text"
                               x-model="customTextColor"
                               @keydown.enter.prevent="applyTextColor(customTextColor)"
                               maxlength="7"
                               placeholder="#0f172a"
                               class="flex-1 min-w-0 text-xs border border-gray-200 rounded-md px-2 py-1 text-gray-700 font-mono outline-none focus:border-gray-400 bg-white">
                    </div>
                </div>
            </div>

            <div class="relative" @click.away="isHighlightMenuOpen = false">
                <button @mousedown.prevent="isHighlightMenuOpen = !isHighlightMenuOpen"
                        title="Highlight color"
                        class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 transition-colors select-none">
                    {{-- Marker icon --}}
                    <svg class="w-3.5 h-3.5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-9.5 9.5H5v-3.5L9 11z"/>
                        <path stroke-linecap="round" d="M3 21h7" stroke-width="2.5" style="stroke:#fef08a"/>
                    </svg>
                </button>
                <div x-show="isHighlightMenuOpen"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"   x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full left-0 mt-1 bg-white/95 backdrop-blur-md border border-gray-200 shadow-xl rounded-lg p-2 z-50 flex flex-col gap-1 w-32"
                     style="display:none;">
                    {{-- Swatch row --}}
                    <div class="flex items-center gap-1.5 flex-wrap">
                        {{-- Clear --}}
                        <button @mousedown.prevent="hiliteColor('transparent')"
                                title="Clear highlight"
                                class="w-6 h-6 rounded border border-gray-300 flex items-center justify-center hover:border-gray-500 transition-colors bg-white">
                            <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <button @mousedown.prevent="hiliteColor('#fef08a')" title="Yellow"  class="w-6 h-6 rounded border border-yellow-200 hover:border-yellow-400 transition-colors" style="background:#fef08a;"></button>
                        <button @mousedown.prevent="hiliteColor('#bbf7d0')" title="Green"   class="w-6 h-6 rounded border border-green-200  hover:border-green-400  transition-colors" style="background:#bbf7d0;"></button>
                        <button @mousedown.prevent="hiliteColor('#bfdbfe')" title="Blue"    class="w-6 h-6 rounded border border-blue-200   hover:border-blue-400   transition-colors" style="background:#bfdbfe;"></button>
                        <button @mousedown.prevent="hiliteColor('#fecaca')" title="Red"     class="w-6 h-6 rounded border border-red-200    hover:border-red-400    transition-colors" style="background:#fecaca;"></button>
                        <button @mousedown.prevent="hiliteColor('#e9d5ff')" title="Purple"  class="w-6 h-6 rounded border border-purple-200 hover:border-purple-400 transition-colors" style="background:#e9d5ff;"></button>
                    </div>
                </div>
            </div>

            <div class="w-px h-4 bg-gray-200 mx-0.5"></div>

            <button @mousedown.prevent="formatText('insertUnorderedList')" title="Bullet list"
                    class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                </svg>
            </button>
            <button @mousedown.prevent="formatText('insertOrderedList')" title="Numbered list"
                    class="w-7 h-7 flex items-center justify-center rounded-md hover:bg-gray-100 text-slate-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.242 5.992h12m-12 6.003H20.24m-12 5.999h12M4.117 7.495v-3.75H2.99m1.125 3.75H2.99m1.125 0H5.24m-1.92 2.577a1.125 1.125 0 113.356 1.076 1.125 1.125 0 01-1.256 1.176h-.054m0 0H3.99m-.356 1.234.034-.072a.518.518 0 01.018-.042m0 0H5.34"/>
                </svg>
            </button>

        </div>
    </div>

    {{-- Keyboard Shortcuts Modal --}}
    <div x-show="isShortcutsModalOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4"
         style="display:none;">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"
             @click="isShortcutsModalOpen = false"></div>

        {{-- Panel --}}
        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="relative w-full max-w-md bg-white/95 backdrop-blur-xl border border-gray-200/80 rounded-2xl shadow-2xl overflow-hidden">

            {{-- Header --}}
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
                        <p class="text-xs text-gray-400">Speed up your workflow</p>
                    </div>
                </div>
                <button @click="isShortcutsModalOpen = false"
                        class="flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-slate-900 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Shortcut rows --}}
            <div class="px-6 py-4 space-y-1">

                {{-- Section: Formatting --}}
                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase mb-3">Formatting</p>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Bold</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">B</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Italic</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">I</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Underline</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">U</kbd>
                    </div>
                </div>

                {{-- Section: Alignment --}}
                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase pt-4 mb-3">Alignment</p>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Left Align</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⇧</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">L</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Center Align</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⇧</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">E</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Right Align</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⇧</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">J</kbd>
                    </div>
                </div>

                {{-- Section: Insert & Size --}}
                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase pt-4 mb-3">Insert & Size</p>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Insert Divider</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⇧</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">H</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Increase Font Size</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⇧</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">&gt;</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm text-slate-700">Decrease Font Size</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⇧</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">&lt;</kbd>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-3 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-400">⌘ = Ctrl on Windows</span>
                <button @click="isShortcutsModalOpen = false"
                        class="text-xs font-medium text-slate-600 hover:text-slate-900 transition-colors">
                    Close
                </button>
            </div>

        </div>
    </div>

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

    {{-- Highlight.js: synchronous, executes before Alpine's deferred bundle --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

<script>
function editor() {
    return {
        view        : 'editor',
        shareOpen   : false,
        linkCopied  : false,
        showToast   : false,
        toastMsg    : '',
        _timer      : null,

        blocks  : {!! count($savedBlocks) ? \Illuminate\Support\Js::from($savedBlocks) : '[{"id":1,"type":"text","content":""}]' !!},
        _nextId : {{ count($savedBlocks) ? collect($savedBlocks)->max('id') + 1 : 2 }},

        showToolbar         : false,
        toolbarX            : 0,
        toolbarY            : 0,
        isFontMenuOpen      : false,
        currentFontName     : 'Arial',
        isFontSizeMenuOpen  : false,
        currentFontSize     : '16px',
        isSaving            : false,
        isHighlightMenuOpen : false,
        isTextColorMenuOpen   : false,
        isShortcutsModalOpen  : false,
        _previewConfirmed     : false,
        customTextColor     : '#0f172a',
        presetTextColors    : ['#0f172a','#64748b','#ef4444','#f97316','#eab308','#22c55e','#3b82f6','#a855f7','#ec4899'],


        init() {
            this.blocks.forEach(block => {
                if (block.type === 'code' && block.content) {
                    block.highlightedContent = hljs.highlightAuto(block.content).value;
                    block.detectedLang       = hljs.highlightAuto(block.content).language || '';
                }
            });
            // Register shortcut listener in capture phase so we intercept
            // before the browser handles conflicting built-in shortcuts.
            document.addEventListener('keydown', (e) => this.handleShortcuts(e), true);
        },

        async saveProject() {
            if (this.isSaving) return;
            this.isSaving = true;
            try {
                const res = await fetch('/editor/{{ $project->slug }}/save', {
                    method  : 'POST',
                    headers : {
                        'Content-Type' : 'application/json',
                        'Accept'       : 'application/json',
                        'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({ blocks: this.blocks }),
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message ?? data.error ?? `HTTP ${res.status}`);
                this.toast('Project saved!');
            } catch (err) {
                this.toast('Save failed: ' + err.message);
                console.error('[saveProject]', err);
            } finally {
                this.isSaving = false;
            }
        },

        addTextBlock() {
            this.blocks.push({ id: this._nextId++, type: 'text', content: '' });
        },

        addCodeBlock() {
            this.blocks.push({
                id                : this._nextId++,
                type              : 'code',
                content           : '',
                highlightedContent: '',
                detectedLang      : '',
                isEditing         : false,
            });
        },

        deleteBlock(id) {
            this.blocks = this.blocks.filter(b => b.id !== id);
        },

        focusLastBlock() {
            const blocks = document.querySelectorAll('[contenteditable="true"]');
            if (blocks.length > 0) { blocks[blocks.length - 1].focus(); }
        },

        applyParaDirs(el) {
            Array.from(el.childNodes).forEach(node => {
                if (node.nodeType !== Node.ELEMENT_NODE) return;
                if (node.tagName !== 'DIV' && node.tagName !== 'P') return;
                const text = node.textContent || '';
                const dir  = this._detectDir(text);
                node.setAttribute('dir', dir ?? 'auto');
            });
        },

        _detectDir(text) {
            for (const ch of text) {
                const cp = ch.codePointAt(0);
                // Strong RTL: Hebrew, Arabic, Syriac, Thaana, NKo, Samaritan,
                //             Mandaic, Hebrew/Arabic Presentation Forms
                if (
                    (cp >= 0x0590 && cp <= 0x05FF) ||
                    (cp >= 0x0600 && cp <= 0x06FF) ||
                    (cp >= 0x0700 && cp <= 0x074F) ||
                    (cp >= 0x0750 && cp <= 0x077F) ||
                    (cp >= 0x0780 && cp <= 0x07FF) ||
                    (cp >= 0x0800 && cp <= 0x083F) ||
                    (cp >= 0x0840 && cp <= 0x085F) ||
                    (cp >= 0xFB1D && cp <= 0xFB4F) ||
                    (cp >= 0xFB50 && cp <= 0xFDFF) ||
                    (cp >= 0xFE70 && cp <= 0xFEFF)
                ) return 'rtl';
                // Strong LTR: Latin, Greek, Cyrillic, Armenian
                if (
                    (cp >= 0x0041 && cp <= 0x005A) ||
                    (cp >= 0x0061 && cp <= 0x007A) ||
                    (cp >= 0x00C0 && cp <= 0x02B8) ||
                    (cp >= 0x0370 && cp <= 0x03FF) ||
                    (cp >= 0x0400 && cp <= 0x04FF) ||
                    (cp >= 0x0500 && cp <= 0x052F) ||
                    (cp >= 0x0530 && cp <= 0x058F)
                ) return 'ltr';
            }
            return null;
        },

        setBlockDirection(dir) {
            const sel = window.getSelection();
            if (!sel || !sel.rangeCount) return;
            let node = sel.getRangeAt(0).commonAncestorContainer;
            if (node.nodeType === Node.TEXT_NODE) node = node.parentElement;
            const ce = node.closest('[contenteditable]');
            if (!ce) return;
            let para = node;
            while (para && para.parentElement !== ce) para = para.parentElement;
            const target = (para && para !== ce) ? para : ce;
            target.setAttribute('dir', dir);
        },

        handleShortcuts(e) {
            const mod = e.ctrlKey || e.metaKey;
            if (!mod || !e.shiftKey) return;

            // Only act when focus is inside a text block
            const ab = document.activeElement?.closest('[contenteditable]')
                    ?? window.getSelection()?.anchorNode?.parentElement?.closest('[contenteditable]');
            if (!ab) return;

            const sizes = [8,10,12,14,16,18,20,24,30,36,48,64];

            const stepSize = (delta) => {
                const sel  = window.getSelection();
                if (!sel || !sel.rangeCount) return;
                let node = sel.anchorNode;
                if (node.nodeType === Node.TEXT_NODE) node = node.parentElement;
                const cur = parseInt(getComputedStyle(node).fontSize) || 16;
                // find nearest size index
                const idx  = sizes.reduce((best, s, i) =>
                    Math.abs(s - cur) < Math.abs(sizes[best] - cur) ? i : best, 0);
                const size = sizes[Math.min(Math.max(idx + delta, 0), sizes.length - 1)] + 'px';
                document.execCommand('fontSize', false, '7');
                ab.querySelectorAll('font[size="7"]').forEach(f => {
                    f.style.fontSize = size;
                    f.removeAttribute('size');
                });
                this.currentFontSize = size;
            };

            // e.code is keyboard-layout-independent ('KeyH', 'Period', etc.)
            switch (e.code) {
                case 'KeyH':
                    e.preventDefault();
                    document.execCommand('insertHTML', false,
                        '<hr class="border-t-2 border-slate-200 my-6 mx-auto w-full" /><div><br></div>');
                    break;
                case 'KeyE':
                    e.preventDefault();
                    document.execCommand('justifyCenter');
                    break;
                case 'KeyL':
                    e.preventDefault();
                    document.execCommand('justifyLeft');
                    break;
                case 'KeyJ':
                    // Ctrl+Shift+R = hard-reload in Chrome (cannot be prevented).
                    // Using J (justify right) instead — safe across all browsers.
                    e.preventDefault();
                    document.execCommand('justifyRight');
                    break;
                case 'Period':   // Shift+Period = '>'
                    e.preventDefault();
                    stepSize(1);
                    break;
                case 'Comma':    // Shift+Comma  = '<'
                    e.preventDefault();
                    stepSize(-1);
                    break;
            }
        },

        insertDivider() {
            document.execCommand('insertHTML', false,
                '<hr class="border-t-2 border-slate-200 my-6 mx-auto w-full" /><div><br></div>');
        },

        applyTextColor(color) {
            document.execCommand('foreColor', false, color);
            this.isTextColorMenuOpen = false;
        },

        hiliteColor(color) {
            if (!document.execCommand('hiliteColor', false, color)) {
                document.execCommand('backColor', false, color);
            }
            this.isHighlightMenuOpen = false;
        },

        startEditing(block, viewEl) {
            block.isEditing = true;
            this.$nextTick(() => {
                const ta = viewEl.parentElement.querySelector('textarea');
                if (!ta) return;
                this.resizeTextarea(ta);
                ta.focus();
                ta.selectionStart = ta.selectionEnd = ta.value.length;
            });
        },

        stopEditing(block) {
            block.isEditing = false;
            this.highlightCode(block);
        },

        highlightCode(block) {
            if (!block.content) {
                block.highlightedContent = '';
                block.detectedLang       = '';
                return;
            }
            if (typeof hljs === 'undefined') {
                block.highlightedContent = block.content
                    .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return;
            }
            const result             = hljs.highlightAuto(block.content);
            block.highlightedContent = result.value;
            block.detectedLang       = result.language || '';
        },

        resizeTextarea(ta) {
            ta.style.height = 'auto';
            ta.style.height = ta.scrollHeight + 'px';
        },

        insertTab(e, block) {
            const ta    = e.target;
            const start = ta.selectionStart;
            const end   = ta.selectionEnd;
            block.content = block.content.substring(0, start) + '    ' + block.content.substring(end);
            this.$nextTick(() => {
                ta.selectionStart = ta.selectionEnd = start + 4;
                this.resizeTextarea(ta);
            });
        },

        checkSelection() {
            const sel = window.getSelection();
            if (!sel || sel.toString().trim().length === 0) {
                this.showToolbar = false;
                return;
            }
            const anchor = sel.anchorNode;
            const node   = anchor?.nodeType === Node.TEXT_NODE ? anchor.parentElement : anchor;
            if (!node || !node.closest('.txt-block')) {
                this.showToolbar = false;
                return;
            }
            const rect = sel.getRangeAt(0).getBoundingClientRect();
            if (!rect || rect.width === 0) { this.showToolbar = false; return; }
            this.toolbarX    = rect.left + rect.width / 2;
            this.toolbarY    = rect.top;
            this.showToolbar = true;
        },

        formatText(command, value = null) {
            document.execCommand(command, false, value);
        },

        previewFont(fontName) {
            document.execCommand('fontName', false, fontName);
            // execCommand wraps inner text in <font face>, but the ::marker
            // pseudo-element inherits font-family from the <li> itself, not
            // from a descendant <font> node. Stamp the family directly on the
            // <li> (and its parent list) so the bullet/number glyph updates too.
            const sel = window.getSelection();
            if (sel && sel.rangeCount) {
                let node = sel.getRangeAt(0).commonAncestorContainer;
                if (node.nodeType === Node.TEXT_NODE) node = node.parentElement;
                const li = node.closest('li');
                if (li) {
                    li.style.fontFamily = fontName;
                    const list = li.closest('ul, ol');
                    if (list) list.style.fontFamily = fontName;
                }
            }
        },

        selectFont(fontName) {
            this.currentFontName = fontName;
            this.previewFont(fontName);
            this.isFontMenuOpen  = false;
        },

        previewFontSize(size) {
            document.execCommand('fontSize', false, '7');
            const activeBlock = window.getSelection()?.anchorNode?.parentElement?.closest('[contenteditable]');
            if (activeBlock) {
                activeBlock.querySelectorAll('font[size="7"]').forEach(font => {
                    font.style.fontSize = size;
                    font.removeAttribute('size');
                });
            }
        },

        selectFontSize(size) {
            this.currentFontSize    = size;
            this.previewFontSize(size);
            this.isFontSizeMenuOpen = false;
        },

        async copyLink() {
            const url = @js(url('/s/' . $project->slug));
            try {
                await navigator.clipboard.writeText(url);
                this.linkCopied = true;
                this.shareOpen  = false;
                this.toast('Preview link copied!');
                setTimeout(() => { this.linkCopied = false; }, 2500);
            } catch {
                this.toast('Copy failed — grab the URL manually.');
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
