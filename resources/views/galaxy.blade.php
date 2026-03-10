<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $project->title }} — Galaxy Slidd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">

    <style>
        html, body { height: 100%; overflow: hidden; font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif; }

        #galaxy-viewport {
            position: relative;
            overflow: hidden;
            width: 100%;
            height: calc(100vh - 3.5rem);
            background-color: #0f1117;
            background-image: radial-gradient(rgba(255,255,255,.07) 1px, transparent 1px);
            background-size: 28px 28px;
            cursor: default;
            user-select: none;
        }
        #galaxy-canvas {
            position: absolute;
            top: 0; left: 0;
            transform-origin: 0 0;
            width: 0; height: 0;
        }

        #conn-svg {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none;
            z-index: 5;
        }
        .edge-hit {
            stroke: transparent;
            stroke-width: 16;
            fill: none;
            pointer-events: stroke;
            cursor: pointer;
        }
        .edge-path {
            fill: none;
            stroke-width: 2;
            pointer-events: none;
            transition: stroke .15s;
        }
        .edge-path.selected { stroke: #818cf8 !important; }
        .edge-pending { fill: none; stroke: #818cf8; stroke-width: 2.5; stroke-dasharray: 8 5; pointer-events: none; }

        .g-node {
            position: absolute;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,.35), 0 1px 4px rgba(0,0,0,.2);
            display: flex;
            flex-direction: column;
            min-width: 160px;
            min-height: 80px;
            transition: box-shadow .15s;
        }
        .g-node.selected {
            box-shadow: 0 0 0 2px #6366f1, 0 4px 24px rgba(0,0,0,.4);
        }
        .g-node.link-source {
            box-shadow: 0 0 0 2.5px #22c55e, 0 0 16px rgba(34,197,94,.25), 0 4px 24px rgba(0,0,0,.4);
        }
        .g-node-header {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 10px 6px;
            border-radius: 12px 12px 0 0;
            cursor: grab;
            flex-shrink: 0;
        }
        .g-node-header:active { cursor: grabbing; }
        .g-node-body {
            flex: 1;
            overflow: visible;          /* default: content grows the node */
            border-radius: 0 0 12px 12px;
            scrollbar-width: thin;
        }
        .code-node .g-node-body { overflow: visible; }

        .text-node { background: #ffffff; }
        .text-node .g-node-header { background: #f8fafc; border-bottom: 1px solid #f1f5f9; }
        .text-node .g-node-body { padding: 12px 14px; }
        .text-editable {
            outline: none;
            min-height: 60px;
            width: 100%;
            font-size: .875rem;
            line-height: 1.7;
            color: #0f172a;
            word-break: break-word;
            cursor: text;
        }
        .text-editable:empty::before { content: 'Start writing…'; color: #d1d5db; pointer-events: none; }
        .text-editable > div, .text-editable > p { margin: 0; min-height: 1em; }
        .text-editable { user-select: text; }
        .text-editable ul, .note-editable ul { list-style-type: disc;    list-style-position: outside; padding-left: 1.25rem; margin-bottom: 0.4rem; }
        .text-editable ol, .note-editable ol { list-style-type: decimal; list-style-position: outside; padding-left: 1.25rem; margin-bottom: 0.4rem; }
        .text-editable li, .note-editable li { margin-bottom: 0.15rem; min-height: 1em; }

        /* ── Code node ─────────────────────────────────────────── */
        .code-node { background: #1e1e2e; border: 1px solid rgba(255,255,255,.08); }
        .code-node .g-node-header { background: rgba(0,0,0,.3); border-bottom: 1px solid rgba(255,255,255,.06); }
        .code-node .g-node-body { background: transparent; }
        .code-view-pre { margin: 0; padding: 14px 16px; overflow: visible; }
        .code-view-pre code { font-family: 'JetBrains Mono', 'Fira Code', ui-monospace, monospace; font-size: .78rem; line-height: 1.7; background: transparent !important; white-space: pre-wrap; word-break: break-word; }
        .code-ta {
            display: block; width: 100%; padding: 14px 16px;
            background: transparent; color: #abb2bf; caret-color: #e879f9;
            font-family: 'JetBrains Mono', monospace; font-size: .78rem; line-height: 1.7;
            outline: none; resize: none; border: none; min-height: 60px;
            box-sizing: border-box; white-space: pre-wrap; word-break: break-word;
            overflow: hidden; /* never scroll the textarea itself */
        }
        .code-empty-ph { padding: 14px 16px; color: rgba(255,255,255,.18); font-family: monospace; font-size: .78rem; }

        .note-node .g-node-header { background: rgba(0,0,0,.08); border-bottom: 1px solid rgba(0,0,0,.08); }
        .note-node .g-node-body { padding: 12px 14px; }
        .note-editable {
            outline: none; min-height: 60px; width: 100%;
            font-size: .875rem; line-height: 1.7; word-break: break-word; cursor: text;
        }
        .note-editable { user-select: text; }
        .note-editable:empty::before { content: 'Add a note…'; color: rgba(0,0,0,.3); pointer-events: none; }

        .g-anchor {
            position: absolute;
            width: 12px; height: 12px; border-radius: 50%;
            background: #6366f1; border: 2px solid #fff;
            opacity: 0; cursor: crosshair; z-index: 40;
            transition: opacity .15s, transform .15s;
        }
        .g-anchor:hover { transform: scale(1.4); }
        .g-node:hover .g-anchor,
        .g-node.selected .g-anchor { opacity: 1; }
        .g-anchor.top    { top: -6px;    left: 50%;  transform: translateX(-50%); }
        .g-anchor.right  { right: -6px;  top: 50%;   transform: translateY(-50%); }
        .g-anchor.bottom { bottom: -6px; left: 50%;  transform: translateX(-50%); }
        .g-anchor.left   { left: -6px;   top: 50%;   transform: translateY(-50%); }
        .g-anchor.top:hover    { transform: translateX(-50%) scale(1.4); }
        .g-anchor.right:hover  { transform: translateY(-50%) scale(1.4); }
        .g-anchor.bottom:hover { transform: translateX(-50%) scale(1.4); }
        .g-anchor.left:hover   { transform: translateY(-50%) scale(1.4); }

        .g-resize {
            position: absolute; right: 0; bottom: 0;
            width: 28px; height: 28px; cursor: nwse-resize; z-index: 50;
            opacity: 0.4; transition: opacity .15s;
            display: flex; align-items: flex-end; justify-content: flex-end; padding: 5px;
        }
        .g-node:hover .g-resize { opacity: 0.7; }
        .g-node.selected .g-resize { opacity: 1; }
        .g-resize:hover { opacity: 1 !important; }

        .tool-panel {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            z-index: 50;
            background: rgba(17,24,39,.92);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            padding: 10px 8px;
            display: flex; flex-direction: column; align-items: center; gap: 2px;
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
            min-width: 52px;
        }
        .tool-sep { width: 28px; height: 1px; background: rgba(255,255,255,.1); margin: 6px 0; }
        .tool-btn {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.5); font-size: 13px; font-weight: 600;
            cursor: pointer; transition: background .15s, color .15s;
            position: relative;
        }
        .tool-btn:hover { background: rgba(255,255,255,.1); color: #fff; }
        .tool-btn.active { background: #6366f1; color: #fff; }
        .tool-btn[title]:hover::after {
            content: attr(title);
            position: absolute;
            left: calc(100% + 10px);
            top: 50%; transform: translateY(-50%);
            background: #1e293b; color: #fff;
            font-size: 11px; font-weight: 500;
            padding: 4px 8px; border-radius: 6px;
            white-space: nowrap; pointer-events: none;
            border: 1px solid rgba(255,255,255,.1);
        }
        .zoom-val {
            font-size: 10px; font-weight: 700; color: rgba(255,255,255,.4);
            font-variant-numeric: tabular-nums; letter-spacing: .03em;
        }

        .galaxy-empty {
            position: absolute; inset: 0;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            pointer-events: none; user-select: none;
        }

        .link-mode { cursor: crosshair !important; }
        .mid-pan { cursor: grabbing !important; }

        .node-title {
            font-size: 10px; font-weight: 600;
            flex: 1; min-width: 0;
            outline: none; background: transparent; border: none; padding: 0;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
            cursor: text;
        }
        .node-title:focus { text-overflow: clip; }
        .node-title::placeholder { color: rgba(0,0,0,.22); font-style: italic; }
        .code-node .node-title::placeholder { color: rgba(255,255,255,.18); }

        .mac-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }

        .node-close-btn {
            width: 18px; height: 18px; border-radius: 5px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(0,0,0,.25); transition: background .12s, color .12s;
            flex-shrink: 0;
        }
        .node-close-btn:hover { background: rgba(239,68,68,.12); color: #ef4444; }
        .node-close-dark { color: rgba(255,255,255,.25); }
        .node-close-dark:hover { background: rgba(239,68,68,.2); color: #fca5a5; }
        .node-close-note { color: rgba(0,0,0,.3); }
        .node-close-note:hover { background: rgba(239,68,68,.15); color: #dc2626; }

        .shortcuts-overlay {
            position: fixed; inset: 0; z-index: 200;
            display: flex; align-items: center; justify-content: center;
            background: rgba(15,23,42,.4); backdrop-filter: blur(6px);
        }

        .sc-scroll::-webkit-scrollbar { width: 4px; }
        .sc-scroll::-webkit-scrollbar-track { background: transparent; }
        .sc-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 99px; }
        .sc-scroll::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        .gsize-scroll { scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.18) transparent; }
        .gsize-scroll::-webkit-scrollbar { width: 4px; }
        .gsize-scroll::-webkit-scrollbar-track { background: transparent; border-radius: 4px; }
        .gsize-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,.18); border-radius: 4px; }
        .gsize-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,.35); }

        .exit-card {
            background: #0f1117;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px;
            padding: 28px 28px 24px;
            width: 400px;
            box-shadow: 0 40px 100px rgba(0,0,0,.85), inset 0 1px 0 rgba(255,255,255,.07);
        }
        .exit-icon {
            width: 44px; height: 44px; border-radius: 14px;
            background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.2);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 16px;
        }
        .exit-title { font-size: 1rem; font-weight: 700; color: #fff; margin-bottom: 6px; }
        .exit-body  { font-size: .82rem; color: rgba(255,255,255,.45); line-height: 1.6; margin-bottom: 22px; }
        .exit-actions { display: flex; gap: 8px; }
        .exit-btn-discard {
            flex: 1; padding: 9px 0; border-radius: 10px; font-size: .82rem; font-weight: 600;
            background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.2); color: #f87171;
            cursor: pointer; transition: background .15s, border-color .15s;
        }
        .exit-btn-discard:hover { background: rgba(239,68,68,.2); border-color: rgba(239,68,68,.35); }
        .exit-btn-save {
            flex: 1; padding: 9px 0; border-radius: 10px; font-size: .82rem; font-weight: 600;
            background: #6366f1; border: none; color: #fff;
            cursor: pointer; transition: background .15s; display: flex; align-items: center; justify-content: center; gap: 6px;
        }
        .exit-btn-save:hover { background: #4f46e5; }
        .exit-btn-save:disabled { opacity: .5; cursor: default; }
        .exit-btn-stay {
            padding: 9px 16px; border-radius: 10px; font-size: .82rem; font-weight: 600;
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1); color: rgba(255,255,255,.6);
            cursor: pointer; transition: background .15s, color .15s;
        }
        .exit-btn-stay:hover { background: rgba(255,255,255,.1); color: #fff; }
    </style>
</head>
<body class="h-full antialiased"
      x-data="galaxy()"
      x-init="init()"
      @keydown.window="onKeydown($event)"
      @mousedown.window="onGlobalMousedown($event)"
      @mousemove.window="onMousemove($event)"
      @mouseup.window="onMouseup($event)">

    <header class="h-14 bg-white border-b border-gray-100 flex items-center justify-between px-4 shrink-0 z-50 relative">
        <div class="flex items-center gap-3 min-w-0">
            <button @click="tryExit('{{ route('dashboard') }}')" class="flex items-center justify-center w-8 h-8 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-slate-900 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
            </button>
            <span class="text-sm font-semibold text-slate-900 truncate max-w-xs">{{ $project->title }}</span>
            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-full uppercase tracking-widest select-none">
                <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
                Galaxy
            </span>
        </div>

        <div class="flex items-center gap-2">
            <button x-show="selectedNodeId !== null"
                    @click="deleteNode(selectedNodeId)"
                    class="flex items-center gap-1.5 text-xs font-medium text-red-500 hover:text-red-700 hover:bg-red-50 px-2.5 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                Delete
            </button>

            {{-- Shortcuts --}}
            <button @click="showGToolbar = false; isShortcutsOpen = true"
                    class="flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-slate-700 hover:bg-gray-100 px-2.5 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg>
                Shortcuts
            </button>

            {{-- Save --}}
            <button @click="saveGalaxy()"
                    :disabled="isSaving"
                    class="flex items-center gap-1.5 text-xs font-medium text-white bg-slate-900 hover:bg-slate-800 disabled:opacity-50 px-3 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/></svg>
                <span x-text="isSaving ? 'Saving…' : 'Save'"></span>
            </button>

            {{-- Share --}}
            <div class="relative" x-data="{ gShareOpen: false, gLinkCopied: false }" @click.away="gShareOpen = false">
                <button @click="gShareOpen = !gShareOpen"
                        :class="gShareOpen ? 'bg-indigo-50 text-indigo-600' : 'text-gray-500 hover:text-slate-900 hover:bg-gray-100'"
                        class="flex items-center gap-1.5 text-xs font-medium px-2.5 py-1.5 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/>
                    </svg>
                    Share
                </button>

                <div x-show="gShareOpen"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-1"
                     class="absolute right-0 top-full mt-2 w-80 rounded-2xl z-[999] overflow-hidden"
                     style="display:none; background:#0f1117; border:1px solid rgba(255,255,255,.12); box-shadow:0 20px 60px rgba(0,0,0,.8), 0 0 0 1px rgba(255,255,255,.04);">

                    <div class="flex items-center gap-3 px-4 py-4" style="border-bottom:1px solid rgba(255,255,255,.08);">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                             style="background:rgba(99,102,241,.15); border:1px solid rgba(99,102,241,.3);">
                            <svg class="w-4 h-4" style="color:#818cf8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-white">Share this Galaxy</p>
                            <p class="text-xs mt-0.5" style="color:rgba(255,255,255,.38);">Anyone with the link can explore it</p>
                        </div>
                    </div>

                    <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.08);">
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-2" style="color:rgba(255,255,255,.22);">Public link</p>
                        <div class="flex items-center gap-2 px-3 py-2.5 rounded-xl" style="background:rgba(255,255,255,.06); border:1px solid rgba(255,255,255,.09);">
                            <svg class="w-3 h-3 shrink-0" style="color:rgba(255,255,255,.3)" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/>
                            </svg>
                            <span class="text-xs font-mono truncate flex-1" style="color:rgba(255,255,255,.45);">{{ url('/s/' . $project->slug) }}</span>
                        </div>
                    </div>

                    <div class="flex gap-2 px-4 py-3">
                        <button @click="navigator.clipboard.writeText('{{ url('/s/' . $project->slug) }}').then(() => { gLinkCopied = true; setTimeout(() => gLinkCopied = false, 2500) })"
                                class="flex-1 flex items-center justify-center gap-1.5 py-2 rounded-xl text-xs font-semibold text-white transition-colors"
                                :style="gLinkCopied ? 'background:#16a34a' : 'background:#6366f1'"
                                onmouseenter="if(!this.__copied) this.style.background='#4f46e5'" onmouseleave="if(!this.__copied) this.style.background='#6366f1'">
                            <svg x-show="!gLinkCopied" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 006.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0015 2.25h-1.5a2.251 2.251 0 00-2.15 1.586"/>
                            </svg>
                            <svg x-show="gLinkCopied" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            <span x-text="gLinkCopied ? 'Copied!' : 'Copy link'"></span>
                        </button>

                        <a href="{{ url('/s/' . $project->slug) }}" target="_blank" rel="noopener"
                           class="flex items-center justify-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
                           style="background:rgba(255,255,255,.07); color:rgba(255,255,255,.6); border:1px solid rgba(255,255,255,.1);"
                           onmouseenter="this.style.background='rgba(255,255,255,.12)';this.style.color='#fff'"
                           onmouseleave="this.style.background='rgba(255,255,255,.07)';this.style.color='rgba(255,255,255,.6)'">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                            </svg>
                            Open
                        </a>
                    </div>

                </div>
            </div>

            {{-- Export .slidd --}}
            <button @click="exportSlidd()"
                    class="flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-slate-700 hover:bg-gray-100 px-2.5 py-1.5 rounded-lg transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                </svg>
                Export .slidd
            </button>
        </div>
    </header>

    <div id="galaxy-viewport"
         x-ref="viewport"
         :class="{ 'link-mode': tool === 'link', 'mid-pan': _isMidPan }"
         :style="`background-position: ${panX % 28}px ${panY % 28}px`"
         @mousedown="onViewportMousedown($event)"
         @click="onViewportClick($event)"
         @wheel.prevent="onWheel($event)">

        <div class="tool-panel" @mousedown.stop>

            <p class="text-[9px] font-bold text-white/60 uppercase tracking-widest mb-1">Add</p>

            <button class="tool-btn" title="Text block" @click="addNode('text')">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
            </button>
            <button class="tool-btn" title="Code block" @click="addNode('code')">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg>
            </button>
            <button class="tool-btn" title="Sticky note" @click="addNode('note')">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
            </button>

            <div class="tool-sep"></div>

            <button class="tool-btn" :class="{ active: tool === 'link' }" title="Draw connection"
                    @click="tool = (tool === 'link') ? 'select' : 'link'; _linkSource = null">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
            </button>

            <div class="tool-sep"></div>

            <button class="tool-btn" title="Zoom in"  @click="zoomStep(1.25)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/></svg>
            </button>
            <span class="zoom-val" x-text="Math.round(zoom * 100) + '%'"></span>
            <button class="tool-btn" title="Zoom out" @click="zoomStep(0.8)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM13.5 10.5h-6"/></svg>
            </button>
            <button class="tool-btn" title="Fit to content" @click="fitToContent()">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
            </button>

        </div>

        <svg id="conn-svg">
            <defs>
                <marker id="arr" viewBox="0 0 10 10" refX="9" refY="5"
                        markerWidth="8" markerHeight="8" orient="auto-start-reverse">
                    <path d="M1,1 L9,5 L1,9 Z" fill="#94a3b8"/>
                </marker>
                <marker id="arr-sel" viewBox="0 0 10 10" refX="9" refY="5"
                        markerWidth="8" markerHeight="8" orient="auto-start-reverse">
                    <path d="M1,1 L9,5 L1,9 Z" fill="#818cf8"/>
                </marker>
                <marker id="arr-pend" viewBox="0 0 10 10" refX="9" refY="5"
                        markerWidth="8" markerHeight="8" orient="auto-start-reverse">
                    <path d="M1,1 L9,5 L1,9 Z" fill="#818cf8"/>
                </marker>
            </defs>
            <g id="edges-layer" @mousedown.stop="onSvgMousedown($event)" @click.stop="onEdgeClick($event)" style="pointer-events:all;"></g>
            <path x-show="_isDrawing" class="edge-pending" :d="pendingPath()" marker-end="url(#arr-pend)" style="display:none;"></path>
        </svg>

        <div id="galaxy-canvas"
             x-ref="canvas"
             :style="`transform: translate(${panX}px,${panY}px) scale(${zoom})`">

            {{-- Nodes --}}
            <template x-for="node in nodes" :key="node.id">
                <div class="g-node"
                     :class="[node.type + '-node', { selected: selectedNodeId === node.id, 'link-source': _linkSource === node.id }]"
                     :style="`left:${node.x}px; top:${node.y}px; width:${node.w}px; min-height:${node.h}px; z-index:${node.z ?? 1};`"
                     :data-node-id="node.id"
                     @mousedown.stop="selectNode(node.id)"
                     @click.capture="linkClickCapture(node.id, $event)">

                    <div class="g-anchor top"    @mousedown.stop="startEdge(node.id,'top',$event)"></div>
                    <div class="g-anchor right"  @mousedown.stop="startEdge(node.id,'right',$event)"></div>
                    <div class="g-anchor bottom" @mousedown.stop="startEdge(node.id,'bottom',$event)"></div>
                    <div class="g-anchor left"   @mousedown.stop="startEdge(node.id,'left',$event)"></div>

                    <template x-if="node.type === 'text'">
                        <div style="display:contents;">
                            <div class="g-node-header" @mousedown.stop="startDrag(node.id,$event)">
                                <svg class="w-3 h-3 text-gray-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/></svg>
                                <input type="text" class="node-title text-gray-500" x-model="node.title" placeholder="Text block…"
                                       @mousedown.stop @click.stop @keydown.enter.prevent="$el.blur()" @keydown.escape.prevent="$el.blur()">
                                <button @click.stop="deleteNode(node.id)" class="node-close-btn" title="Delete">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="g-node-body">
                                <div class="text-editable" contenteditable="true"
                                     x-init="$el.innerHTML = node.content"
                                     @input="node.content = $event.target.innerHTML; $nextTick(() => _drawEdges())"
                                     @mousedown.stop @click.stop>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="node.type === 'code'">
                        <div style="display:contents;">
                            <div class="g-node-header" @mousedown.stop="startDrag(node.id,$event)">
                                <div class="mac-dot" style="background:#ff5f57;"></div>
                                <div class="mac-dot" style="background:#febc2e;"></div>
                                <div class="mac-dot" style="background:#28c840;"></div>
                                <input type="text"
                                       class="node-title"
                                       style="color:rgba(255,255,255,.5);"
                                       x-model="node.title"
                                       placeholder="Code block…"
                                       @mousedown.stop @click.stop
                                       @keydown.enter.prevent="$el.blur()"
                                       @keydown.escape.prevent="$el.blur()">
                                <span x-show="node.detectedLang && !node.title"
                                      x-text="node.detectedLang"
                                      class="text-[9px] font-bold uppercase tracking-widest shrink-0"
                                      style="color:rgba(255,255,255,.25)">
                                </span>
                                <span x-show="node.isEditing" class="text-[9px] text-zinc-600 font-medium shrink-0">editing</span>
                                <button @click.stop="deleteNode(node.id)" class="node-close-btn node-close-dark" title="Delete">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="g-node-body">
                                <div x-show="!node.isEditing"
                                     @click.stop="node.isEditing = true; $nextTick(() => { const ta = $el.nextElementSibling; autoExpandCode(node, ta); ta.focus(); })">
                                    <template x-if="node.content && node.content.trim()">
                                        <pre class="code-view-pre"><code class="hljs" x-html="node.highlighted || node.content"></code></pre>
                                    </template>
                                    <template x-if="!node.content || !node.content.trim()">
                                        <div class="code-empty-ph">// click to write code…</div>
                                    </template>
                                </div>
                                <textarea x-show="node.isEditing"
                                          class="code-ta"
                                          x-model="node.content"
                                          @mousedown.stop @click.stop
                                          @input="autoExpandCode(node, $el)"
                                          @blur="node.isEditing = false; highlightNode(node)"
                                          @keydown.tab.prevent="insertTab($event)"
                                          spellcheck="false"
                                          placeholder="// start typing…"
                                          style="display:none;">
                                </textarea>
                            </div>
                        </div>
                    </template>

                    <template x-if="node.type === 'note'">
                        <div style="display:contents;">
                            <div class="g-node-header" @mousedown.stop="startDrag(node.id,$event)" :style="`background:${darken(node.color)}`">
                                <svg class="w-3 h-3 shrink-0" style="color:rgba(0,0,0,.4)" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                <input type="text" class="node-title" style="color:rgba(0,0,0,.5);" x-model="node.title" placeholder="Note…"
                                       @mousedown.stop @click.stop @keydown.enter.prevent="$el.blur()" @keydown.escape.prevent="$el.blur()">
                                <button @click.stop="cycleColor(node)" class="w-4 h-4 rounded-full border border-black/10 shrink-0" :style="`background:${node.color}`" title="Change color"></button>
                                <button @click.stop="deleteNode(node.id)" class="node-close-btn node-close-note" title="Delete">
                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <div class="g-node-body" :style="`background:${node.color}`">
                                <div class="note-editable" contenteditable="true"
                                     x-init="$el.innerHTML = node.content"
                                     @input="node.content = $event.target.innerHTML; $nextTick(() => _drawEdges())"
                                     @mousedown.stop @click.stop>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="g-resize" @mousedown.stop="startResize(node.id,$event)">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none">
                            <path d="M9 1L1 9M9 5L5 9" stroke="rgba(0,0,0,.25)" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </div>

                </div>
            </template>

        </div>

        <div class="galaxy-empty" x-show="nodes.length === 0">
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7" style="color:rgba(255,255,255,.25)" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold" style="color:rgba(255,255,255,.3)">Your Galaxy Space is empty</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,.15)">Use the panel on the left to add text, code, or notes</p>
            </div>
        </div>

    </div>

    <pre id="_code-ruler" style="position:fixed;top:-9999px;left:-9999px;visibility:hidden;white-space:pre;font-family:'JetBrains Mono','Fira Code',ui-monospace,monospace;font-size:.78rem;line-height:1.7;padding:0;margin:0;border:none;pointer-events:none;"></pre>

    <div x-show="tool === 'link'"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="position:fixed; bottom:24px; left:50%; transform:translateX(-50%); z-index:90; pointer-events:none; display:none;"
         class="flex items-center gap-2.5 bg-indigo-600 text-white text-xs font-medium px-4 py-2.5 rounded-full shadow-xl">
        <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
        <span x-text="_linkSource !== null ? 'Now click the target node →' : '← Click the source node'"></span>
        <span style="opacity:.55">Esc to cancel</span>
    </div>

    <div x-show="showGToolbar"
         :style="`top:${gToolbarY}px; left:${gToolbarX}px`"
         class="fixed z-[500] -translate-x-1/2 -translate-y-full pb-2 pointer-events-auto"
         style="display:none;"
         @mousedown.stop>
        <div class="flex items-center gap-0.5 p-1.5 rounded-xl border border-white/10 shadow-2xl flex-wrap"
             style="background:rgba(15,17,23,.96);backdrop-filter:blur(20px);">

            <div class="relative" @click.away="gFontMenuOpen = false">
                <button @mousedown.prevent="gFontMenuOpen = !gFontMenuOpen"
                        class="flex items-center gap-1 px-2 py-1 rounded-lg transition-colors select-none"
                        style="font-size:12px;color:rgba(255,255,255,.65);hover:background:rgba(255,255,255,.08);"
                        onmouseenter="this.style.background='rgba(255,255,255,.08)'" onmouseleave="this.style.background='transparent'">
                    <span x-text="gCurrentFont" style="max-width:56px;overflow:hidden;white-space:nowrap;"></span>
                    <svg class="w-2.5 h-2.5 shrink-0" style="color:rgba(255,255,255,.3)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="gFontMenuOpen"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"   x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full left-0 mt-1 w-36 rounded-xl border border-white/10 py-1 z-50 flex flex-col shadow-2xl"
                     style="background:rgba(15,17,23,.98);backdrop-filter:blur(20px);display:none;">
                    <template x-for="font in ['Arial','Georgia','Tajawal','JetBrains Mono','Comic Sans MS']" :key="font">
                        <button @mouseenter="_gPreviewOk = false; gPreviewFont(font)"
                                @mouseleave="if (!_gPreviewOk) document.execCommand('undo')"
                                @mousedown.prevent="_gPreviewOk = true; gSelectFont(font)"
                                :style="`font-family:${font};font-size:13px;color:rgba(255,255,255,.7)`"
                                class="w-full text-left px-3 py-1.5 transition-colors"
                                onmouseenter="this.style.background='rgba(255,255,255,.06)'" onmouseleave="this.style.background='transparent'"
                                x-text="font">
                        </button>
                    </template>
                </div>
            </div>

            <div class="relative" @click.away="gSizeMenuOpen = false">
                <button @mousedown.prevent="gSizeMenuOpen = !gSizeMenuOpen"
                        class="flex items-center gap-1 px-2 py-1 rounded-lg transition-colors select-none"
                        style="font-size:12px;color:rgba(255,255,255,.65);min-width:3rem;"
                        onmouseenter="this.style.background='rgba(255,255,255,.08)'" onmouseleave="this.style.background='transparent'">
                    <span x-text="gCurrentSize"></span>
                    <svg class="w-2.5 h-2.5 shrink-0" style="color:rgba(255,255,255,.3)" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="gSizeMenuOpen"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"   x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full left-0 mt-1 w-24 rounded-xl border border-white/10 py-1 z-50 flex flex-col shadow-2xl overflow-y-auto gsize-scroll"
                     style="background:rgba(15,17,23,.98);backdrop-filter:blur(20px);max-height:200px;display:none;">
                    <template x-for="size in ['10px','12px','14px','16px','18px','20px','24px','30px','36px','48px']" :key="size">
                        <button @mouseenter="_gPreviewOk = false; gPreviewSize(size)"
                                @mouseleave="if (!_gPreviewOk) document.execCommand('undo')"
                                @mousedown.prevent="_gPreviewOk = true; gSelectSize(size)"
                                :class="gCurrentSize === size ? 'font-semibold' : ''"
                                class="w-full text-left px-3 py-1.5 transition-colors"
                                style="font-size:13px;color:rgba(255,255,255,.7);"
                                onmouseenter="this.style.background='rgba(255,255,255,.06)'" onmouseleave="this.style.background='transparent'"
                                x-text="size">
                        </button>
                    </template>
                </div>
            </div>

            <div style="width:1px;height:16px;background:rgba(255,255,255,.1);margin:0 2px;flex-shrink:0;"></div>

            <button @mousedown.prevent="gFmt('bold')"
                    class="w-7 h-7 flex items-center justify-center rounded-md transition-colors font-bold text-sm select-none"
                    style="color:rgba(255,255,255,.65);"
                    onmouseenter="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'" onmouseleave="this.style.background='transparent';this.style.color='rgba(255,255,255,.65)'">B</button>
            <button @mousedown.prevent="gFmt('italic')"
                    class="w-7 h-7 flex items-center justify-center rounded-md transition-colors italic text-sm select-none"
                    style="color:rgba(255,255,255,.65);"
                    onmouseenter="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'" onmouseleave="this.style.background='transparent';this.style.color='rgba(255,255,255,.65)'">I</button>
            <button @mousedown.prevent="gFmt('underline')"
                    class="w-7 h-7 flex items-center justify-center rounded-md transition-colors underline text-sm select-none"
                    style="color:rgba(255,255,255,.65);"
                    onmouseenter="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'" onmouseleave="this.style.background='transparent';this.style.color='rgba(255,255,255,.65)'">U</button>

            <div style="width:1px;height:16px;background:rgba(255,255,255,.1);margin:0 2px;flex-shrink:0;"></div>

            <button @mousedown.prevent="gFmt('justifyLeft')" title="Align left"
                    class="w-7 h-7 flex items-center justify-center rounded-md transition-colors"
                    style="color:rgba(255,255,255,.55);"
                    onmouseenter="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'" onmouseleave="this.style.background='transparent';this.style.color='rgba(255,255,255,.55)'">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5"/></svg>
            </button>
            <button @mousedown.prevent="gFmt('justifyCenter')" title="Align center"
                    class="w-7 h-7 flex items-center justify-center rounded-md transition-colors"
                    style="color:rgba(255,255,255,.55);"
                    onmouseenter="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'" onmouseleave="this.style.background='transparent';this.style.color='rgba(255,255,255,.55)'">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M6 12h12M3.75 17.25h16.5"/></svg>
            </button>
            <button @mousedown.prevent="gFmt('justifyRight')" title="Align right"
                    class="w-7 h-7 flex items-center justify-center rounded-md transition-colors"
                    style="color:rgba(255,255,255,.55);"
                    onmouseenter="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'" onmouseleave="this.style.background='transparent';this.style.color='rgba(255,255,255,.55)'">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M12 12h8.25M3.75 17.25h16.5"/></svg>
            </button>

            <div style="width:1px;height:16px;background:rgba(255,255,255,.1);margin:0 2px;flex-shrink:0;"></div>

            <div class="relative" @click.away="gColorMenuOpen = false">
                <button @mousedown.prevent="gColorMenuOpen = !gColorMenuOpen" title="Text color"
                        class="w-7 h-7 flex items-center justify-center rounded-md transition-colors select-none"
                        style="color:rgba(255,255,255,.65);"
                        onmouseenter="this.style.background='rgba(255,255,255,.08)'" onmouseleave="this.style.background='transparent'">
                    <span class="flex flex-col items-center gap-0.5">
                        <span class="text-xs font-bold leading-none" style="font-family:serif;">A</span>
                        <span class="w-4 h-1 rounded-sm" :style="`background:${gCurrentColor}`"></span>
                    </span>
                </button>
                <div x-show="gColorMenuOpen"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"   x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full left-0 mt-1 rounded-xl border border-white/10 p-2.5 z-50 shadow-2xl flex flex-col gap-2"
                     style="background:rgba(15,17,23,.98);backdrop-filter:blur(20px);width:160px;display:none;">
                    <div class="grid grid-cols-5 gap-1.5">
                        <template x-for="c in ['#ffffff','#94a3b8','#f87171','#fb923c','#facc15','#4ade80','#60a5fa','#a78bfa','#f472b6','#0f172a']" :key="c">
                            <button @mousedown.prevent="gApplyColor(c)"
                                    :style="`background:${c}`"
                                    :class="gCurrentColor === c ? 'ring-2 ring-offset-1 ring-white/40' : ''"
                                    class="w-6 h-6 rounded border border-white/10 hover:scale-110 transition-transform shadow-sm">
                            </button>
                        </template>
                    </div>
                    <div style="height:1px;background:rgba(255,255,255,.07);"></div>
                    <div class="flex items-center gap-2">
                        <div class="relative shrink-0">
                            <div class="w-7 h-7 rounded border border-white/20 overflow-hidden cursor-pointer" :style="`background:${gCurrentColor}`">
                                <input type="color" x-model="gCurrentColor"
                                       @input="document.execCommand('styleWithCSS',false,true);document.execCommand('foreColor',false,gCurrentColor)"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </div>
                        </div>
                        <input type="text" x-model="gCurrentColor"
                               @keydown.enter.prevent="gApplyColor(gCurrentColor)"
                               maxlength="7" placeholder="#ffffff"
                               class="flex-1 min-w-0 text-xs rounded-md px-2 py-1 font-mono outline-none"
                               style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:rgba(255,255,255,.7);">
                    </div>
                </div>
            </div>

            <div class="relative" @click.away="gHlMenuOpen = false">
                <button @mousedown.prevent="gHlMenuOpen = !gHlMenuOpen" title="Highlight"
                        class="w-7 h-7 flex items-center justify-center rounded-md transition-colors"
                        style="color:rgba(255,255,255,.55);"
                        onmouseenter="this.style.background='rgba(255,255,255,.08)'" onmouseleave="this.style.background='transparent'">
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-9.5 9.5H5v-3.5L9 11z"/>
                        <path stroke-linecap="round" d="M3 21h7" stroke-width="2.5" style="stroke:#fef08a"/>
                    </svg>
                </button>
                <div x-show="gHlMenuOpen"
                     x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"   x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="absolute top-full left-0 mt-1 rounded-xl border border-white/10 p-2 z-50 shadow-2xl"
                     style="background:rgba(15,17,23,.98);backdrop-filter:blur(20px);display:none;">
                    <div class="flex items-center gap-1.5 flex-wrap" style="width:120px;">
                        <button @mousedown.prevent="gHilite('transparent')" title="Clear" class="w-6 h-6 rounded border border-white/20 flex items-center justify-center" style="background:rgba(255,255,255,.05);">
                            <svg class="w-3 h-3" style="color:rgba(255,255,255,.4)" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                        <button @mousedown.prevent="gHilite('#fef08a')" class="w-6 h-6 rounded border border-white/10 hover:scale-110 transition-transform" style="background:#fef08a;"></button>
                        <button @mousedown.prevent="gHilite('#bbf7d0')" class="w-6 h-6 rounded border border-white/10 hover:scale-110 transition-transform" style="background:#bbf7d0;"></button>
                        <button @mousedown.prevent="gHilite('#bfdbfe')" class="w-6 h-6 rounded border border-white/10 hover:scale-110 transition-transform" style="background:#bfdbfe;"></button>
                        <button @mousedown.prevent="gHilite('#fecaca')" class="w-6 h-6 rounded border border-white/10 hover:scale-110 transition-transform" style="background:#fecaca;"></button>
                        <button @mousedown.prevent="gHilite('#e9d5ff')" class="w-6 h-6 rounded border border-white/10 hover:scale-110 transition-transform" style="background:#e9d5ff;"></button>
                    </div>
                </div>
            </div>

            <div style="width:1px;height:16px;background:rgba(255,255,255,.1);margin:0 2px;flex-shrink:0;"></div>

            <button @mousedown.prevent="gFmt('insertUnorderedList')" title="Bullet list"
                    class="w-7 h-7 flex items-center justify-center rounded-md transition-colors"
                    style="color:rgba(255,255,255,.55);"
                    onmouseenter="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'" onmouseleave="this.style.background='transparent';this.style.color='rgba(255,255,255,.55)'">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
            </button>
            <button @mousedown.prevent="gFmt('insertOrderedList')" title="Numbered list"
                    class="w-7 h-7 flex items-center justify-center rounded-md transition-colors"
                    style="color:rgba(255,255,255,.55);"
                    onmouseenter="this.style.background='rgba(255,255,255,.08)';this.style.color='#fff'" onmouseleave="this.style.background='transparent';this.style.color='rgba(255,255,255,.55)'">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.242 5.992h12m-12 6.003H20.24m-12 5.999h12M4.117 7.495v-3.75H2.99m1.125 3.75H2.99m1.125 0H5.24m-1.92 2.577a1.125 1.125 0 113.356 1.076 1.125 1.125 0 01-1.256 1.176h-.054m0 0H3.99m-.356 1.234.034-.072a.518.518 0 01.018-.042m0 0H5.34"/></svg>
            </button>

        </div>
    </div>

    <div class="shortcuts-overlay"
         x-show="showExitModal"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display:none;">
        <div class="exit-card"
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95">
            <div class="exit-icon">
                <svg class="w-5 h-5" style="color:#f87171" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <div class="exit-title">You have unsaved changes</div>
            <div class="exit-body">Your work on this Galaxy canvas hasn't been saved yet. If you leave now, all unsaved changes will be lost.</div>
            <div class="exit-actions">
                <button class="exit-btn-discard" @click="exitDiscard()">Leave anyway</button>
                <button class="exit-btn-save" :disabled="isSaving" @click="exitSave()">
                    <svg x-show="isSaving" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    <span x-text="isSaving ? 'Saving…' : 'Save & Leave'"></span>
                </button>
                <button class="exit-btn-stay" @click="showExitModal = false">Stay</button>
            </div>
        </div>
    </div>

    <div class="shortcuts-overlay"
         x-show="isShortcutsOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="isShortcutsOpen = false"
         style="display:none;">

        <div x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             class="relative w-full max-w-md bg-white/95 backdrop-blur-xl border border-gray-200/80 rounded-2xl shadow-2xl overflow-hidden">

            <div class="flex items-center justify-between px-6 pt-5 pb-4 border-b border-gray-100">
                <div class="flex items-center gap-2.5">
                    <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100">
                        <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <rect x="2" y="6" width="20" height="13" rx="2.5" stroke="currentColor" stroke-width="1.75" fill="none"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 10h.01M10 10h.01M14 10h.01M18 10h.01M6 14h.01M10 14h.01M14 14h4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Galaxy Shortcuts</p>
                        <p class="text-xs text-gray-400">Keyboard shortcuts for the canvas</p>
                    </div>
                </div>
                <button @click="isShortcutsOpen = false"
                        class="flex items-center justify-center w-7 h-7 rounded-lg text-gray-400 hover:text-slate-900 hover:bg-gray-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="sc-scroll px-6 py-4 space-y-1 overflow-y-auto" style="max-height:60vh;scrollbar-width:thin;scrollbar-color:#e2e8f0 transparent;">

                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase mb-3">General</p>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Save</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⇧</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">S</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Shortcuts</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">/</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Cancel / Deselect</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">Esc</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Delete selected</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">Del</kbd>
                    </div>
                </div>

                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase pt-4 mb-3">Add Nodes</p>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Add text block</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">T</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Add code block</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">C</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Add sticky note</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">N</kbd>
                    </div>
                </div>

                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase pt-4 mb-3">Canvas</p>

                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Toggle link tool</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">L</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Fit to content</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">F</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Zoom in / out</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">+</kbd>
                        <span class="text-xs text-gray-300">/</span>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">−</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Reset zoom & pan</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">0</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Zoom in / out</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">Scroll</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Pan canvas</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">Middle drag</kbd>
                    </div>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-50">
                    <span class="text-sm text-slate-700">Draw connection</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">Drag anchor</kbd>
                    </div>
                </div>

                <p class="text-[10px] font-semibold text-gray-400 tracking-widest uppercase pt-4 mb-3">Text Editing</p>

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
                <div class="flex items-center justify-between py-2.5">
                    <span class="text-sm text-slate-700">Underline</span>
                    <div class="flex items-center gap-1">
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">⌘</kbd>
                        <kbd class="px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-mono font-bold text-slate-600 shadow-sm">U</kbd>
                    </div>
                </div>

            </div>

            <div class="px-6 py-3 bg-gray-50/80 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-400">⌘ = Ctrl on Windows &nbsp;·&nbsp; ⇧ = Shift</span>
                <button @click="isShortcutsOpen = false"
                        class="text-xs font-medium text-slate-600 hover:text-slate-900 transition-colors">
                    Close
                </button>
            </div>

        </div>
    </div>

    <div x-show="showToast"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"  x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1"
         class="fixed bottom-5 right-5 flex items-center gap-3 bg-white border border-gray-100 px-4 py-3 rounded-xl shadow-lg z-[100]"
         style="display:none;">
        <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
        <span class="text-sm font-medium text-slate-900" x-text="toastMsg"></span>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
    function galaxy() {
        return {
            nodes      : {!! \Illuminate\Support\Js::from($savedNodes) !!},
            edges      : {!! \Illuminate\Support\Js::from($savedEdges) !!},
            _nextId    : {!! $nextId !!},

            zoom : 1,
            panX : 0,
            panY : 0,

            tool          : 'select',   // 'select' | 'link'
            selectedNodeId: null,
            selectedEdgeId: null,
            _drag         : null,       // { nodeId, ox, oy }
            _pan          : null,       // { sx, sy, spx, spy }
            _resize       : null,       // { nodeId, sx, sy, sw, sh }
            _isDrawing    : false,
            _drawFromId   : null,
            _drawFromAnchor: null,
            _drawCurX     : 0,
            _drawCurY     : 0,
            _linkSource   : null,
            _isMidPan     : false,
            _edgeDrag     : null,  // { edgeId, end:'from'|'to', curX, curY, snapNodeId, snapAnchor }

            isSaving         : false,
            showToast        : false,
            toastMsg         : '',
            isShortcutsOpen  : false,
            showExitModal    : false,
            _isDirty         : false,
            _pendingExit     : null,
            _timer           : null,

            showGToolbar    : false,
            gToolbarX       : 0,
            gToolbarY       : 0,
            gFontMenuOpen   : false,
            gSizeMenuOpen   : false,
            gColorMenuOpen  : false,
            gHlMenuOpen     : false,
            gCurrentFont    : 'Arial',
            gCurrentSize    : '14px',
            gCurrentColor   : '#ffffff',
            _gPreviewOk     : false,

            _noteColors: ['#fef9c3','#fce7f3','#dcfce7','#dbeafe','#ede9fe','#ffedd5'],

            // ─────────────────────────────────────────────────────
            init() {
                this.$nextTick(() => {
                    const vp = this.$refs.viewport;
                    if (vp) {
                        this.panX = vp.offsetWidth  / 2;
                        this.panY = vp.offsetHeight / 2;
                    }
                    this.nodes.forEach(n => {
                        if (n.title === undefined) n.title = '';
                        this.highlightNode(n);
                    });
                    if (this.nodes.length > 0) this.fitToContent();
                    this._drawEdges();

                    this.$watch('nodes', () => { this._isDirty = true; });
                    this.$watch('edges', () => { this._isDirty = true; });
                });

                document.addEventListener('mouseup', () => setTimeout(() => this.checkGSelection(), 20));
                document.addEventListener('keyup',   () => this.checkGSelection());

                window.addEventListener('beforeunload', (e) => {
                    if (!this._isDirty) return;
                    e.preventDefault();
                    e.returnValue = '';
                });
            },

            _drawEdges() {
                const layer = document.getElementById('edges-layer');
                if (!layer) return;
                layer.innerHTML = this._buildEdgesSVG();
            },

            _buildEdgesSVG() {
                const self = this;
                let html = self.edges.map(function(edge) {
                    if (self._edgeDrag && self._edgeDrag.edgeId === edge.id) return '';

                    const d   = self.edgePath(edge);
                    const mid = self.edgeMid(edge);
                    const sel = self.selectedEdgeId === edge.id;
                    const stroke = sel ? '#818cf8' : '#94a3b8';
                    const marker = sel ? 'url(#arr-sel)' : 'url(#arr)';
                    const del = sel
                        ? `<g data-del-edge="${edge.id}" style="cursor:pointer">
                               <circle cx="${mid.x}" cy="${mid.y}" r="10" fill="#1e293b" stroke="#818cf8" stroke-width="1.5"/>
                               <text x="${mid.x}" y="${mid.y + 4.5}" text-anchor="middle" font-size="13" fill="#f87171" style="pointer-events:none;font-family:sans-serif">×</text>
                           </g>`
                        : '';

                    let handles = '';
                    if (sel) {
                        const pF = self._anchor(edge.from, edge.fromAnchor);
                        const pT = self._anchor(edge.to,   edge.toAnchor);
                        handles = `<circle cx="${pF.x}" cy="${pF.y}" r="7" fill="#6366f1" stroke="#fff" stroke-width="2"
                                           data-edge-handle="${edge.id}" data-handle-end="from" style="cursor:grab;pointer-events:all;"/>
                                   <circle cx="${pT.x}" cy="${pT.y}" r="7" fill="#6366f1" stroke="#fff" stroke-width="2"
                                           data-edge-handle="${edge.id}" data-handle-end="to"   style="cursor:grab;pointer-events:all;"/>`;
                    }

                    return `<path class="edge-hit" data-edge-id="${edge.id}" d="${d}"/>
                            <path d="${d}" stroke="${stroke}" stroke-width="2" fill="none" marker-end="${marker}" style="pointer-events:none"/>
                            ${del}${handles}`;
                }).join('');

                if (self._edgeDrag) {
                    const drag = self._edgeDrag;
                    const edge = self.edges.find(e => e.id === drag.edgeId);
                    if (edge) {
                        html += `<path d="${self.edgePath(edge)}" stroke="#6366f1" stroke-width="1.5" fill="none"
                                      stroke-opacity="0.3" stroke-dasharray="6 4" style="pointer-events:none"/>`;

                        let p1, a1, p2, a2;
                        if (drag.snapNodeId && drag.snapAnchor) {
                            const sp = self._anchor(drag.snapNodeId, drag.snapAnchor);
                            if (drag.end === 'from') {
                                p1 = sp; a1 = drag.snapAnchor;
                                p2 = self._anchor(edge.to, edge.toAnchor); a2 = edge.toAnchor;
                            } else {
                                p1 = self._anchor(edge.from, edge.fromAnchor); a1 = edge.fromAnchor;
                                p2 = sp; a2 = drag.snapAnchor;
                            }
                            html += `<circle cx="${sp.x}" cy="${sp.y}" r="9" fill="none" stroke="#22c55e" stroke-width="2.5" style="pointer-events:none"/>`;
                        } else {
                            const cur = { x: drag.curX, y: drag.curY };
                            if (drag.end === 'from') {
                                p1 = cur; a1 = null;
                                p2 = self._anchor(edge.to, edge.toAnchor); a2 = edge.toAnchor;
                            } else {
                                p1 = self._anchor(edge.from, edge.fromAnchor); a1 = edge.fromAnchor;
                                p2 = cur; a2 = null;
                            }
                            html += `<circle cx="${drag.curX}" cy="${drag.curY}" r="7" fill="#6366f1" stroke="#fff" stroke-width="2" style="pointer-events:none"/>`;
                        }

                        html += `<path d="${self._bezier(p1, a1, p2, a2)}" stroke="#818cf8" stroke-width="2.5" fill="none"
                                      stroke-dasharray="8 5" marker-end="url(#arr-pend)" style="pointer-events:none"/>`;
                    }
                }

                return html;
            },

            _uid() { return this._nextId++; },
            _topZ() { return this.nodes.reduce((m, n) => Math.max(m, n.z ?? 1), 0); },

            addNode(type) {
                const vp = this.$refs.viewport;
                const cx = vp ? (vp.offsetWidth  / 2 - this.panX) / this.zoom : 0;
                const cy = vp ? (vp.offsetHeight / 2 - this.panY) / this.zoom : 0;

                const sizes = { text: [300,180], code: [440,240], note: [240,200] };
                const [w, h] = sizes[type] ?? [300, 180];

                const defaultTitles = { text: 'Text block', code: 'Code block', note: 'Note' };
                const node = {
                    id: this._uid(), type,
                    title: defaultTitles[type] ?? '',
                    x: cx - w/2, y: cy - h/2, w, h,
                    content: '', color: type === 'note' ? '#fef9c3' : '#ffffff',
                    detectedLang: '', highlighted: '', isEditing: false,
                    z: this._topZ() + 1,
                };
                this.nodes.push(node);
                this.selectedNodeId = node.id;
            },

            deleteNode(id) {
                this.nodes = this.nodes.filter(n => n.id !== id);
                this.edges = this.edges.filter(e => e.from !== id && e.to !== id);
                if (this.selectedNodeId === id) this.selectedNodeId = null;
                this._drawEdges();
            },

            selectNode(id) {
                if (this.selectedNodeId !== id) this._blurActive();
                this.selectedNodeId = id;
                this.selectedEdgeId = null;
                const n = this.nodes.find(n => n.id === id);
                if (n) n.z = this._topZ() + 1;
            },

            // ── Code highlighting ──────────────────────────────────
            highlightNode(node) {
                if (node.type === 'code' && node.content?.trim()) {
                    const r = hljs.highlightAuto(node.content);
                    node.highlighted  = r.value;
                    node.detectedLang = node.detectedLang || r.language || '';
                }
            },

            insertTab(e) {
                document.execCommand('insertText', false, '    ');
            },

            autoExpandCode(node, ta) {
                ta.style.height = 'auto';
                ta.style.height = ta.scrollHeight + 'px';
                const HEADER_H = 36;
                node.h = Math.max(80, ta.scrollHeight + HEADER_H);

                // Width: measure the longest line with a hidden ruler element
                const ruler = document.getElementById('_code-ruler');
                if (ruler && ta.value) {
                    const lines = ta.value.split('\n');
                    const longest = lines.reduce((a, b) => b.length > a.length ? b : a, '');
                    ruler.textContent = longest;
                    const measuredW = ruler.scrollWidth + 48; // 32px padding + 16px buffer
                    node.w = Math.max(node.w, measuredW, 220);
                }
                this.$nextTick(() => this._drawEdges());
            },

            checkGSelection() {
                // Always hide the toolbar while any modal is open
                if (this.isShortcutsOpen || this.showExitModal) { this.showGToolbar = false; return; }
                const sel = window.getSelection();
                if (!sel || sel.toString().trim().length === 0) { this.showGToolbar = false; return; }
                const anchor = sel.anchorNode;
                const el = anchor?.nodeType === Node.TEXT_NODE ? anchor.parentElement : anchor;
                if (!el || !el.closest('.text-editable, .note-editable')) { this.showGToolbar = false; return; }
                const rect = sel.getRangeAt(0).getBoundingClientRect();
                if (!rect || rect.width === 0) { this.showGToolbar = false; return; }
                this.gToolbarX    = rect.left + rect.width / 2;
                this.gToolbarY    = rect.top;
                this.showGToolbar = true;
            },

            gFmt(cmd, val) { document.execCommand(cmd, false, val ?? null); },

            gSelectFont(font) {
                this.gCurrentFont  = font;
                document.execCommand('fontName', false, font);
                this.gFontMenuOpen = false;
            },

            gPreviewFont(font) { document.execCommand('fontName', false, font); },

            gSelectSize(size) {
                this.gCurrentSize  = size;
                document.execCommand('fontSize', false, '7');
                const ce = window.getSelection()?.anchorNode?.parentElement?.closest('[contenteditable]');
                if (ce) ce.querySelectorAll('font[size="7"]').forEach(f => { f.style.fontSize = size; f.removeAttribute('size'); });
                this.gSizeMenuOpen = false;
            },

            gPreviewSize(size) {
                document.execCommand('fontSize', false, '7');
                const ce = window.getSelection()?.anchorNode?.parentElement?.closest('[contenteditable]');
                if (ce) ce.querySelectorAll('font[size="7"]').forEach(f => { f.style.fontSize = size; f.removeAttribute('size'); });
            },

            gApplyColor(color) {
                this.gCurrentColor  = color;
                document.execCommand('styleWithCSS', false, true);
                document.execCommand('foreColor', false, color);
                this.gColorMenuOpen = false;
            },

            gHilite(color) {
                document.execCommand('styleWithCSS', false, true);
                document.execCommand('backColor', false, color);
                this.gHlMenuOpen = false;
            },

            cycleColor(node) {
                const i = this._noteColors.indexOf(node.color);
                node.color = this._noteColors[(i + 1) % this._noteColors.length];
            },

            darken(hex) {
                // Return a slightly darker version for the header
                const c = parseInt(hex.replace('#',''), 16);
                const r = Math.max(0,(c>>16&255)-15);
                const g = Math.max(0,(c>>8&255)-15);
                const b = Math.max(0,(c&255)-15);
                return `rgb(${r},${g},${b})`;
            },

            // ── Drag ──────────────────────────────────────────────
            startDrag(id, e) {
                e.stopPropagation();
                this.selectNode(id);
                const vp   = this.$refs.viewport;
                const node = this.nodes.find(n => n.id === id);
                const mx   = (e.clientX - vp.getBoundingClientRect().left - this.panX) / this.zoom;
                const my   = (e.clientY - vp.getBoundingClientRect().top  - this.panY) / this.zoom;
                this._drag = { nodeId: id, ox: mx - node.x, oy: my - node.y };
            },

            // ── Resize ────────────────────────────────────────────
            startResize(id, e) {
                e.stopPropagation();
                const node = this.nodes.find(n => n.id === id);
                this._resize = { nodeId: id, sx: e.clientX, sy: e.clientY, sw: node.w, sh: node.h };
            },

            onGlobalMousedown(e) {
                if (this.isShortcutsOpen || this.showExitModal) return;
                if (e.button !== 1) return;
                e.preventDefault(); // prevent browser auto-scroll cursor
                const vp = this.$refs.viewport;
                const rect = vp?.getBoundingClientRect();
                if (!rect) return;
                if (e.clientX < rect.left || e.clientX > rect.right ||
                    e.clientY < rect.top  || e.clientY > rect.bottom) return;
                this._isMidPan = true;
                this._pan = { sx: e.clientX, sy: e.clientY, spx: this.panX, spy: this.panY };
            },

            onViewportMousedown(e) {
                if (this.isShortcutsOpen || this.showExitModal) return;
                if (e.button !== 0) return;
                if (e.target.closest('.g-node, .tool-panel')) return;
                this._blurActive();
                this.selectedNodeId = null;
                this.selectedEdgeId = null;
            },

            onViewportClick(e) {
                if (this.tool === 'link' && e.target === this.$refs.viewport) {
                    this._linkSource = null;
                    this.tool = 'select';
                }
            },

            onWheel(e) {
                if (this.isShortcutsOpen || this.showExitModal) return;
                const factor   = e.deltaY < 0 ? 1.1 : 0.9;
                const newZoom  = Math.min(4, Math.max(0.08, this.zoom * factor));
                const rect     = this.$refs.viewport.getBoundingClientRect();
                const mx = e.clientX - rect.left;
                const my = e.clientY - rect.top;
                this.panX = mx - (mx - this.panX) * (newZoom / this.zoom);
                this.panY = my - (my - this.panY) * (newZoom / this.zoom);
                this.zoom = newZoom;
                this._drawEdges();
            },

            zoomStep(factor) {
                const vp = this.$refs.viewport;
                const mx = vp ? vp.offsetWidth  / 2 : 0;
                const my = vp ? vp.offsetHeight / 2 : 0;
                const newZoom = Math.min(4, Math.max(0.08, this.zoom * factor));
                this.panX = mx - (mx - this.panX) * (newZoom / this.zoom);
                this.panY = my - (my - this.panY) * (newZoom / this.zoom);
                this.zoom = newZoom;
                this._drawEdges();
            },

            fitToContent() {
                if (!this.nodes.length) return;
                const vp = this.$refs.viewport;
                if (!vp) return;
                const pad  = 80;
                const minX = Math.min(...this.nodes.map(n => n.x));
                const minY = Math.min(...this.nodes.map(n => n.y));
                const maxX = Math.max(...this.nodes.map(n => n.x + n.w));
                const maxY = Math.max(...this.nodes.map(n => n.y + n.h));
                const cw   = maxX - minX + pad * 2;
                const ch   = maxY - minY + pad * 2;
                const z    = Math.min(1, Math.min(vp.offsetWidth / cw, vp.offsetHeight / ch));
                this.zoom  = z;
                this.panX  = (vp.offsetWidth  - cw * z) / 2 - (minX - pad) * z;
                this.panY  = (vp.offsetHeight - ch * z) / 2 - (minY - pad) * z;
                this._drawEdges();
            },

            // ── Global mouse events ───────────────────────────────
            onMousemove(e) {
                if (this.isShortcutsOpen || this.showExitModal) return;
                const vp   = this.$refs.viewport;
                const rect = vp?.getBoundingClientRect();

                if (this._pan) {
                    this.panX = this._pan.spx + (e.clientX - this._pan.sx);
                    this.panY = this._pan.spy + (e.clientY - this._pan.sy);
                }

                if (this._drag && rect) {
                    const node = this.nodes.find(n => n.id === this._drag.nodeId);
                    if (node) {
                        node.x = (e.clientX - rect.left - this.panX) / this.zoom - this._drag.ox;
                        node.y = (e.clientY - rect.top  - this.panY) / this.zoom - this._drag.oy;
                        if (this.edges.length) this._drawEdges();
                    }
                }

                if (this._resize) {
                    const node = this.nodes.find(n => n.id === this._resize.nodeId);
                    if (node) {
                        node.w = Math.max(160, this._resize.sw + (e.clientX - this._resize.sx) / this.zoom);
                        node.h = Math.max(80,  this._resize.sh + (e.clientY - this._resize.sy) / this.zoom);
                    }
                }

                if (this._pan) this._drawEdges();

                if (this._edgeDrag && rect) {
                    this._edgeDrag.curX = e.clientX - rect.left;
                    this._edgeDrag.curY = e.clientY - rect.top;
                    const snap = this._findSnapAnchor(this._edgeDrag.curX, this._edgeDrag.curY, this._edgeDrag.edgeId, this._edgeDrag.end);
                    this._edgeDrag.snapNodeId = snap ? snap.nodeId : null;
                    this._edgeDrag.snapAnchor = snap ? snap.anchor : null;
                    this._drawEdges();
                }

                if (this._isDrawing && rect) {
                    this._drawCurX = e.clientX - rect.left;
                    this._drawCurY = e.clientY - rect.top;
                }
            },

            onMouseup(e) {
                if (this._isDrawing) {
                    const els    = document.elementsFromPoint(e.clientX, e.clientY);
                    const nodeEl = els.find(el => el.dataset && el.dataset.nodeId);
                    const target = nodeEl ? parseInt(nodeEl.dataset.nodeId) : null;
                    if (target && target !== this._drawFromId) {
                        const { toAnchor } = this._opposite(this._drawFromAnchor);
                        this.edges = [...this.edges, {
                            id         : this._uid(),
                            from       : this._drawFromId,
                            fromAnchor : this._drawFromAnchor,
                            to         : target,
                            toAnchor,
                            label      : '',
                        }];
                        this._drawEdges();
                    }
                    this._isDrawing = false;
                    this._drawFromId = null;
                    this._drawFromAnchor = null;
                }
                if (this._edgeDrag) {
                    const drag = this._edgeDrag;
                    if (drag.snapNodeId && drag.snapAnchor) {
                        this.edges = this.edges.map(e => {
                            if (e.id !== drag.edgeId) return e;
                            return drag.end === 'from'
                                ? { ...e, from: drag.snapNodeId, fromAnchor: drag.snapAnchor }
                                : { ...e, to:   drag.snapNodeId, toAnchor:   drag.snapAnchor };
                        });
                    }
                    this._edgeDrag = null;
                    this._drawEdges();
                }

                this._pan = this._drag = this._resize = null;
                this._isMidPan = false;
            },

            // ── Anchor-drag edge drawing ──────────────────────────
            startEdge(fromId, fromAnchor, e) {
                e.stopPropagation();
                const rect = this.$refs.viewport?.getBoundingClientRect();
                this._isDrawing      = true;
                this._drawFromId     = fromId;
                this._drawFromAnchor = fromAnchor;
                this._drawCurX       = rect ? e.clientX - rect.left : 0;
                this._drawCurY       = rect ? e.clientY - rect.top  : 0;
            },

            _opposite(anchor) {
                return { toAnchor: { top:'bottom', bottom:'top', left:'right', right:'left' }[anchor] ?? 'left' };
            },

            // ── Link tool ─────────────────────────────────────────
            // capture-phase handler: fires before any child @click.stop can swallow the event
            linkClickCapture(id, e) {
                if (this.tool !== 'link') return;
                e.stopPropagation(); // prevent further bubbling (normal @click.stop won't run)
                this.clickNodeLink(id);
            },

            clickNodeLink(id) {
                if (this.tool !== 'link') return;
                if (this._linkSource === null) {
                    this._linkSource = id;
                    return;
                }
                if (this._linkSource === id) return; // same node
                const from = this.nodes.find(n => n.id === this._linkSource);
                const to   = this.nodes.find(n => n.id === id);
                if (from && to) {
                    const { fromAnchor, toAnchor } = this._autoAnchors(from, to);
                    // use spread instead of push — guarantees Alpine detects the change
                    this.edges = [...this.edges, {
                        id: this._uid(),
                        from: this._linkSource, fromAnchor,
                        to: id, toAnchor,
                        label: '',
                    }];
                }
                this._linkSource = null;
                this.tool = 'select';
                this._drawEdges();
            },

            _autoAnchors(from, to) {
                const dx = (to.x + to.w/2) - (from.x + from.w/2);
                const dy = (to.y + to.h/2) - (from.y + from.h/2);
                if (Math.abs(dx) >= Math.abs(dy))
                    return dx > 0 ? { fromAnchor:'right', toAnchor:'left' } : { fromAnchor:'left', toAnchor:'right' };
                return dy > 0 ? { fromAnchor:'bottom', toAnchor:'top' } : { fromAnchor:'top', toAnchor:'bottom' };
            },

            deleteEdge(id) {
                this.edges = this.edges.filter(e => e.id !== id);
                if (this.selectedEdgeId === id) this.selectedEdgeId = null;
                this._drawEdges();
            },


            onEdgeClick(e) {
                // walk up to find data-edge-id or data-del-edge
                let el = e.target;
                while (el && el.id !== 'edges-layer') {
                    if (el.dataset.delEdge) { this.deleteEdge(parseInt(el.dataset.delEdge)); return; }
                    if (el.dataset.edgeId)  {
                        const id = parseInt(el.dataset.edgeId);
                        this.selectedEdgeId = (this.selectedEdgeId === id) ? null : id;
                        this.selectedNodeId = null;
                        this._drawEdges();
                        return;
                    }
                    el = el.parentElement;
                }
            },

            onSvgMousedown(e) {
                // Only handle drags on endpoint handle circles
                const el = e.target;
                if (!el.dataset || !el.dataset.edgeHandle) return;
                e.stopPropagation();
                const edgeId = parseInt(el.dataset.edgeHandle);
                const end    = el.dataset.handleEnd; // 'from' | 'to'
                const rect   = this.$refs.viewport?.getBoundingClientRect();
                this._edgeDrag = {
                    edgeId,
                    end,
                    curX      : rect ? e.clientX - rect.left : e.clientX,
                    curY      : rect ? e.clientY - rect.top  : e.clientY,
                    snapNodeId: null,
                    snapAnchor: null,
                };
                this._drawEdges();
            },

            _findSnapAnchor(vx, vy, edgeId, end) {
                const SNAP = 40; // viewport pixels
                const edge = this.edges.find(e => e.id === edgeId);
                if (!edge) return null;
                let best = null, bestDist = SNAP;
                for (const node of this.nodes) {
                    for (const side of ['top','right','bottom','left']) {
                        const pt   = this._anchor(node.id, side);
                        const dist = Math.hypot(pt.x - vx, pt.y - vy);
                        if (dist < bestDist) { bestDist = dist; best = { nodeId: node.id, anchor: side }; }
                    }
                }
                return best;
            },

            _anchor(nodeId, side) {
                const n = this.nodes.find(n => n.id === nodeId);
                if (!n) return { x: 0, y: 0 };
                // For text/note nodes use actual DOM height (they grow with content via min-height)
                const el      = document.querySelector(`[data-node-id="${nodeId}"]`);
                const actualH = (el && n.type !== 'code') ? el.offsetHeight : n.h;
                const actualW = el ? el.offsetWidth : n.w;
                const vx = cx => cx * this.zoom + this.panX;
                const vy = cy => cy * this.zoom + this.panY;
                return {
                    top:    { x: vx(n.x + actualW/2), y: vy(n.y) },
                    right:  { x: vx(n.x + actualW),   y: vy(n.y + actualH/2) },
                    bottom: { x: vx(n.x + actualW/2), y: vy(n.y + actualH) },
                    left:   { x: vx(n.x),             y: vy(n.y + actualH/2) },
                }[side] ?? { x: vx(n.x + actualW/2), y: vy(n.y + actualH/2) };
            },

            _bezier(p1, a1, p2, a2) {
                const d  = Math.max(Math.abs(p2.x-p1.x), Math.abs(p2.y-p1.y), 60) * 0.55;
                const c1 = { x:p1.x, y:p1.y }, c2 = { x:p2.x, y:p2.y };
                ({ top:()=>c1.y-=d, right:()=>c1.x+=d, bottom:()=>c1.y+=d, left:()=>c1.x-=d }[a1] ?? (()=>{}))();
                if (a2) ({ top:()=>c2.y-=d, right:()=>c2.x+=d, bottom:()=>c2.y+=d, left:()=>c2.x-=d }[a2] ?? (()=>{}))();
                else c2.x -= d;
                return `M ${p1.x} ${p1.y} C ${c1.x} ${c1.y} ${c2.x} ${c2.y} ${p2.x} ${p2.y}`;
            },

            edgePath(edge) {
                return this._bezier(
                    this._anchor(edge.from, edge.fromAnchor), edge.fromAnchor,
                    this._anchor(edge.to,   edge.toAnchor),   edge.toAnchor
                );
            },

            pendingPath() {
                if (!this._isDrawing) return '';
                return this._bezier(
                    this._anchor(this._drawFromId, this._drawFromAnchor),
                    this._drawFromAnchor,
                    { x: this._drawCurX, y: this._drawCurY },
                    null
                );
            },

            edgeMid(edge) {
                const p1 = this._anchor(edge.from, edge.fromAnchor);
                const p2 = this._anchor(edge.to,   edge.toAnchor);
                return { x: (p1.x + p2.x) / 2, y: (p1.y + p2.y) / 2 };
            },

            // ── Save ──────────────────────────────────────────────
            async saveGalaxy() {
                if (this.isSaving) return false;
                this.isSaving = true;
                try {
                    const res = await fetch('/editor/{{ $project->slug }}/save-galaxy', {
                        method : 'POST',
                        headers: {
                            'Content-Type' : 'application/json',
                            'Accept'       : 'application/json',
                            'X-CSRF-TOKEN' : document.querySelector('meta[name=csrf-token]').content,
                        },
                        body: JSON.stringify({
                            nodes: this.nodes.map(n => ({
                                id: n.id, type: n.type,
                                title: n.title ?? '',
                                x: n.x,  y: n.y,
                                w: n.w,  h: n.h,
                                content: n.content, color: n.color,
                                detectedLang: n.detectedLang ?? '',
                            })),
                            edges: this.edges,
                        }),
                    });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.error ?? `HTTP ${res.status}`);
                    this._isDirty = false;
                    this.toast('Saved!');
                    return true;
                } catch (err) {
                    this.toast('Save failed: ' + err.message);
                    return false;
                } finally {
                    this.isSaving = false;
                }
            },

            exportSlidd() {
                const payload = {
                    version : 1,
                    project : { title: @js($project->title), type: 'galaxy_space' },
                    nodes   : this.nodes.map(n => ({
                        id: n.id, type: n.type,
                        title: n.title ?? '',
                        x: n.x, y: n.y, w: n.w, h: n.h,
                        content: n.content, color: n.color ?? null,
                        detectedLang: n.detectedLang ?? '',
                    })),
                    edges   : this.edges,
                };
                const blob = new Blob([JSON.stringify(payload, null, 2)], { type: 'application/json' });
                const a    = document.createElement('a');
                a.href     = URL.createObjectURL(blob);
                a.download = @js(\Illuminate\Support\Str::slug($project->title)) + '.slidd';
                a.click();
                URL.revokeObjectURL(a.href);
                this.toast('Exported as .slidd!');
            },

            tryExit(url) {
                if (!this._isDirty) { window.location.href = url; return; }
                this._pendingExit = url;
                this.showExitModal = true;
            },

            exitDiscard() {
                this._isDirty = false;
                window.location.href = this._pendingExit;
            },

            async exitSave() {
                const ok = await this.saveGalaxy();
                if (ok) window.location.href = this._pendingExit;
                else this.showExitModal = false;
            },

            onKeydown(e) {
                const mod  = e.ctrlKey || e.metaKey;
                const inInput = e.target.isContentEditable || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'INPUT';

                if (mod && e.shiftKey && e.key.toLowerCase() === 's') { e.preventDefault(); this.saveGalaxy(); return; }
                if (mod && e.key.toLowerCase() === '/') { e.preventDefault(); this.showGToolbar = false; this.isShortcutsOpen = !this.isShortcutsOpen; return; }

                // ── Rich-text hotkeys inside canvas text / note nodes ──────────
                if (mod && e.target.isContentEditable && e.target.closest('.text-editable, .note-editable')) {
                    const k = e.key.toLowerCase();
                    if (k === 'b') { e.preventDefault(); document.execCommand('bold');      return; }
                    if (k === 'i') { e.preventDefault(); document.execCommand('italic');    return; }
                    if (k === 'u') { e.preventDefault(); document.execCommand('underline'); return; }
                }

                if (e.key === 'Escape') {
                    if (this.isShortcutsOpen) { this.isShortcutsOpen = false; return; }
                    this._blurActive();
                    this.tool = 'select';
                    this._linkSource = null;
                    this._isDrawing  = false;
                    this.selectedNodeId = null;
                    this.selectedEdgeId = null;
                    return;
                }

                if (!inInput) {
                    if ((e.key === 'Delete' || e.key === 'Backspace')) {
                        if (this.selectedNodeId) this.deleteNode(this.selectedNodeId);
                        else if (this.selectedEdgeId) this.deleteEdge(this.selectedEdgeId);
                        return;
                    }
                    if (e.key === 'l' || e.key === 'L') { this.tool = (this.tool === 'link') ? 'select' : 'link'; this._linkSource = null; return; }
                    if (e.key === 't' || e.key === 'T') { this.addNode('text'); return; }
                    if (e.key === 'c' || e.key === 'C') { this.addNode('code'); return; }
                    if (e.key === 'n' || e.key === 'N') { this.addNode('note'); return; }
                    if (e.key === 'f' || e.key === 'F') { this.fitToContent(); return; }
                    if (e.key === '+' || e.key === '=') { this.zoomStep(1.25); return; }
                    if (e.key === '-') { this.zoomStep(0.8); return; }
                    if (e.key === '0') { this.zoom = 1; const vp = this.$refs.viewport; if (vp) { this.panX = vp.offsetWidth/2; this.panY = vp.offsetHeight/2; } return; }
                    if (e.key === '?') { this.showGToolbar = false; this.isShortcutsOpen = !this.isShortcutsOpen; return; }
                }
            },

            // ── Blur active editable ───────────────────────────────
            _blurActive() {
                const el = document.activeElement;
                if (!el) return;
                if (el.isContentEditable || el.tagName === 'TEXTAREA' || el.tagName === 'INPUT') {
                    el.blur();
                    window.getSelection()?.removeAllRanges();
                }
            },

            // ── Toast ─────────────────────────────────────────────
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
