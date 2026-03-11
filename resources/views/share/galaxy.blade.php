<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $project->title }} — Galaxy Slidd</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='22' fill='%230f172a'/><text y='74' x='50' text-anchor='middle' font-size='62' font-family='system-ui,sans-serif' font-weight='700' fill='white'>S</text></svg>">
    <link rel="icon" href="/favicon.ico" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">

    <style>
        html, body { height: 100%; overflow: hidden; display: flex; flex-direction: column; font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', sans-serif; }

        .gv-header {
            height: 3.5rem;
            background: rgba(8, 9, 13, 0.88);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-bottom: 1px solid rgba(255, 255, 255, .07);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 0.75rem;
            position: relative; z-index: 60;
            flex-shrink: 0;
        }
        .gv-hdr-left  { display: flex; align-items: center; gap: 0.5rem; min-width: 0; overflow: hidden; }
        .gv-hdr-right { display: flex; align-items: center; gap: 0.375rem; flex-shrink: 0; }
        .gv-hdr-title {
            font-size: .875rem; font-weight: 600; color: rgba(255,255,255,.8);
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            max-width: 120px; flex-shrink: 1;
        }
        .gv-hdr-badge-galaxy { display: none; }
        .gv-hdr-badge-view   { display: none; }
        .gv-hdr-sep-sm       { display: none; }
        .gv-hdr-author-name  { display: none; }
        .gv-hdr-sep-md       { display: none; }
        .gv-hdr-made-with    { display: none; }
        @media (min-width: 640px) {
            .gv-header          { padding: 0 1rem; }
            .gv-hdr-left        { gap: 0.75rem; }
            .gv-hdr-right       { gap: 0.625rem; }
            .gv-hdr-title       { max-width: 20rem; }
            .gv-hdr-badge-galaxy { display: inline-flex; }
            .gv-hdr-badge-view   { display: inline-flex; }
            .gv-hdr-sep-sm       { display: block; }
        }
        @media (min-width: 768px) {
            .gv-hdr-author-name { display: flex; }
            .gv-hdr-sep-md      { display: block; }
            .gv-hdr-made-with   { display: inline-flex; }
        }

        #gv-viewport {
            position: relative;
            overflow: hidden;
            width: 100%;
            flex: 1;
            min-height: 0;
            background: #08090d;
            cursor: grab;
            user-select: none;
            touch-action: none;
        }
        #gv-viewport.panning { cursor: grabbing; }

        .gv-grid {
            position: absolute; inset: 0;
            background-image: radial-gradient(rgba(255,255,255,.055) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        .gv-nebula {
            position: absolute; inset: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 60% 45% at 20% 55%, rgba(99,102,241,.045) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 80% 25%, rgba(139,92,246,.04) 0%, transparent 70%),
                radial-gradient(ellipse 40% 35% at 60% 80%, rgba(59,130,246,.03) 0%, transparent 70%);
        }

        .gv-vignette {
            position: absolute; inset: 0; pointer-events: none; z-index: 2;
            background: radial-gradient(ellipse 85% 85% at 50% 50%, transparent 50%, rgba(0,0,0,.55) 100%);
        }

        #gv-canvas {
            position: absolute; top: 0; left: 0;
            transform-origin: 0 0;
            width: 0; height: 0;
        }

        .gv-node {
            position: absolute;
            border-radius: 14px;
            display: flex; flex-direction: column;
            transition: box-shadow .25s, transform .25s;
            cursor: default;
            animation: gvNodeIn .5s cubic-bezier(.16,1,.3,1) both;
        }
        .gv-node:hover { transform: translateY(-3px); }

        @keyframes gvNodeIn {
            from { opacity: 0; transform: translateY(14px) scale(.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .gv-text-node {
            background: #ffffff;
            box-shadow:
                0 0 0 1px rgba(255,255,255,.9),
                0 6px 24px rgba(0,0,0,.45),
                0 18px 56px rgba(0,0,0,.30),
                0 40px 80px rgba(0,0,0,.18);
        }
        .gv-text-node:hover {
            box-shadow:
                0 0 0 2px rgba(99,102,241,.35),
                0 0 40px rgba(99,102,241,.1),
                0 8px 32px rgba(0,0,0,.55),
                0 22px 60px rgba(0,0,0,.35);
        }
        .gv-text-header {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 12px 6px;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 14px 14px 0 0;
            flex-shrink: 0;
        }
        .gv-text-title {
            font-size: 10px; font-weight: 600; color: #94a3b8;
            flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .gv-text-body { padding: 12px 14px; overflow: hidden; }
        .gv-text-content { font-size: .875rem; line-height: 1.7; color: #0f172a; word-break: break-word; }

        .gv-code-node {
            background: #11121f;
            border: 1px solid rgba(99,102,241,.18);
            box-shadow:
                0 0 0 1px rgba(99,102,241,.07),
                0 6px 28px rgba(0,0,0,.6),
                0 0 60px rgba(99,102,241,.06),
                inset 0 1px 0 rgba(99,102,241,.12);
        }
        .gv-code-node:hover {
            box-shadow:
                0 0 0 1.5px rgba(99,102,241,.3),
                0 10px 40px rgba(0,0,0,.7),
                0 0 80px rgba(99,102,241,.1),
                inset 0 1px 0 rgba(99,102,241,.18);
        }
        .gv-code-header {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 12px 6px;
            background: rgba(0,0,0,.35);
            border-bottom: 1px solid rgba(255,255,255,.05);
            border-radius: 14px 14px 0 0;
            flex-shrink: 0;
        }
        .gv-mac-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
        .gv-code-title {
            font-size: 10px; font-weight: 600;
            color: rgba(255,255,255,.28); flex: 1;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .gv-code-lang {
            font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .1em;
            color: rgba(255,255,255,.18); flex-shrink: 0;
        }
        .gv-code-body { overflow: visible; flex: 1; }
        .gv-code-body pre { margin: 0; padding: 14px 16px; overflow: visible; }
        .gv-code-body code { font-family: 'JetBrains Mono','Fira Code',ui-monospace,monospace; font-size: .78rem; line-height: 1.7; background: transparent !important; white-space: pre-wrap; word-break: break-word; }

        .gv-note-header {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 12px 6px;
            background: rgba(0,0,0,.08);
            border-bottom: 1px solid rgba(0,0,0,.07);
            border-radius: 14px 14px 0 0;
            flex-shrink: 0;
        }
        .gv-note-title { font-size: 10px; font-weight: 600; color: rgba(0,0,0,.38); flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .gv-note-body { padding: 12px 14px; overflow: hidden; }
        .gv-note-content { font-size: .875rem; line-height: 1.7; color: rgba(0,0,0,.72); word-break: break-word; }

        .gv-image-node {
            background: #0d0e14;
            border: 1px solid rgba(255,255,255,.1);
            overflow: hidden;
            box-shadow: 0 6px 28px rgba(0,0,0,.6), 0 18px 56px rgba(0,0,0,.35);
        }
        .gv-image-node:hover {
            box-shadow: 0 0 0 1.5px rgba(99,102,241,.3), 0 10px 40px rgba(0,0,0,.7), 0 0 60px rgba(99,102,241,.08);
        }
        .gv-image-header {
            display: flex; align-items: center; gap: 6px;
            padding: 8px 12px 6px;
            background: rgba(0,0,0,.45);
            border-bottom: 1px solid rgba(255,255,255,.06);
            border-radius: 14px 14px 0 0;
            flex-shrink: 0;
        }
        .gv-image-title {
            font-size: 10px; font-weight: 600; color: rgba(255,255,255,.3);
            flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .gv-image-body { flex: 1; overflow: hidden; position: relative; display: flex; }
        .gv-image-body img { width: 100%; height: 100%; object-fit: cover; display: block; pointer-events: none; user-select: none; border-radius: 0 0 14px 14px; }

        /* ── Rich content (ul/ol inside node body) ──────────────── */
        .gv-rich ul { list-style-type: disc;    list-style-position: outside; padding-left: 1.25rem; margin-bottom: .4rem; }
        .gv-rich ol { list-style-type: decimal; list-style-position: outside; padding-left: 1.25rem; margin-bottom: .4rem; }
        .gv-rich li { margin-bottom: .15rem; min-height: 1em; }

        /* ── SVG edges ──────────────────────────────────────────── */
        #gv-svg {
            position: absolute; top: 0; left: 0;
            width: 100%; height: 100%;
            pointer-events: none; z-index: 5;
        }

        .gv-ctrl-panel {
            position: absolute; left: 12px; bottom: 16px; z-index: 50;
            background: rgba(15, 17, 23, .92);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 12px;
            padding: 6px;
            display: flex; align-items: center; gap: 2px;
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
        }
        @media (min-width: 640px) { .gv-ctrl-panel { left: 16px; bottom: 24px; } }
        .gv-ctrl-btn {
            width: 40px; height: 40px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.45); cursor: pointer;
            transition: background .15s, color .15s;
        }
        @media (min-width: 640px) { .gv-ctrl-btn { width: 32px; height: 32px; } }
        .gv-ctrl-btn:hover { background: rgba(255,255,255,.1); color: #fff; }
        .gv-zoom-val {
            font-size: 10px; font-weight: 700; color: rgba(255,255,255,.3);
            font-variant-numeric: tabular-nums; padding: 0 6px;
        }

        .gv-empty {
            position: absolute; inset: 0; z-index: 10;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            pointer-events: none;
        }

        .gv-badge-galaxy {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 10px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
            color: #818cf8; background: rgba(99,102,241,.12); border: 1px solid rgba(99,102,241,.2);
            padding: 3px 8px; border-radius: 99px; user-select: none;
        }
        .gv-badge-view {
            display: inline-flex; align-items: center; gap: 4px;
            font-size: 11px; font-weight: 500; color: rgba(255,255,255,.3);
            background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08);
            padding: 3px 10px; border-radius: 99px; user-select: none;
        }

        .gv-made-with {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 11px; font-weight: 600; color: rgba(255,255,255,.28);
            padding: 5px 12px; border-radius: 99px;
            background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.07);
            text-decoration: none; transition: background .15s, color .15s, border-color .15s;
            user-select: none;
        }
        .gv-made-with:hover { background: rgba(255,255,255,.08); color: rgba(255,255,255,.55); border-color: rgba(255,255,255,.13); }
        .gv-made-with-dot { width: 6px; height: 6px; border-radius: 50%; background: #6366f1; flex-shrink: 0; }

        .gv-avatar {
            width: 26px; height: 26px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 10px; font-weight: 700; color: #fff; flex-shrink: 0;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            border: 1.5px solid rgba(99,102,241,.35);
            box-shadow: 0 0 12px rgba(99,102,241,.3);
        }
    </style>
</head>
<body class="h-full antialiased"
      x-data="galaxyView()"
      x-init="init()"
      @mousedown.window="onMousedown($event)"
      @mousemove.window="onMousemove($event)"
      @mouseup.window="onMouseup($event)"
      @wheel.window.prevent="onWheel($event)">

    <header class="gv-header">

        <div class="gv-hdr-left">
            <a href="/" style="display:flex;align-items:center;justify-content:center;width:2rem;height:2rem;border-radius:8px;flex-shrink:0;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.08);text-decoration:none;"
               onmouseenter="this.style.background='rgba(255,255,255,.1)'" onmouseleave="this.style.background='rgba(255,255,255,.05)'">
                <span style="font-size:.875rem;font-weight:700;color:rgba(255,255,255,.8);letter-spacing:-.025em;">S</span>
            </a>
            <span style="color:rgba(255,255,255,.2);user-select:none;flex-shrink:0;">/</span>
            <span class="gv-hdr-title">{{ $project->title }}</span>
            <span class="gv-badge-galaxy gv-hdr-badge-galaxy">
                <svg style="width:10px;height:10px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                </svg>
                Galaxy
            </span>
        </div>

        <div class="gv-hdr-right">

            <span class="gv-badge-view gv-hdr-badge-view">
                <svg style="width:12px;height:12px;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                View only
            </span>

            <div class="gv-hdr-sep-sm" style="width:1px;height:16px;background:rgba(255,255,255,.08);"></div>

            <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
                <div class="gv-avatar">{{ strtoupper(substr($project->user->name, 0, 1)) }}</div>
                <div class="gv-hdr-author-name" style="flex-direction:column;line-height:1;gap:2px;">
                    <span style="font-size:10px;font-weight:500;color:rgba(255,255,255,.25);">Created by</span>
                    <span style="font-size:.75rem;font-weight:600;color:rgba(255,255,255,.65);">{{ $project->user->name }}</span>
                </div>
            </div>

            <div class="gv-hdr-sep-md" style="width:1px;height:16px;background:rgba(255,255,255,.08);"></div>

            <a href="/" class="gv-made-with gv-hdr-made-with">
                <span class="gv-made-with-dot"></span>
                Made with Slidd
            </a>

        </div>
    </header>

    <div id="gv-viewport"
         x-ref="viewport"
         :class="{ 'panning': _isPanning }"
         :style="`background-position: ${panX % 28}px ${panY % 28}px`">

        <div class="gv-nebula"></div>
        <div class="gv-grid" :style="`background-position: ${panX % 28}px ${panY % 28}px`"></div>
        <div class="gv-vignette"></div>

        <svg id="gv-svg">
            <defs>
                <filter id="edge-glow" x="-20%" y="-20%" width="140%" height="140%">
                    <feGaussianBlur stdDeviation="2.5" result="blur"/>
                    <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
                </filter>
                <marker id="gv-arr" viewBox="0 0 10 10" refX="9" refY="5"
                        markerWidth="7" markerHeight="7" orient="auto-start-reverse">
                    <path d="M1,1 L9,5 L1,9 Z" fill="rgba(99,102,241,.55)"/>
                </marker>
            </defs>
            <g id="gv-edges"></g>
        </svg>

        <div id="gv-canvas"
             x-ref="canvas"
             :style="`transform: translate(${panX}px,${panY}px) scale(${zoom})`">

            <template x-for="(node, idx) in nodes" :key="node.id">
                <div class="gv-node"
                     :class="'gv-' + node.type + '-node'"
                     :style="`
                        left:${node.x}px; top:${node.y}px;
                        width:${node.w}px;
                        ${node.type === 'image' ? 'height' : 'min-height'}:${node.h}px;
                        z-index:${node.z ?? 1};
                        animation-delay:${idx * 40}ms;
                        ${node.type === 'note' ? 'background:' + node.color + ';' : ''}
                        ${node.type === 'note' ? 'box-shadow: 0 6px 24px rgba(0,0,0,.35), 0 18px 56px rgba(0,0,0,.2);' : ''}
                     `">

                    <template x-if="node.type === 'text'">
                        <div style="display:contents;">
                            <div class="gv-text-header">
                                <svg class="w-3 h-3 shrink-0 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12"/>
                                </svg>
                                <span class="gv-text-title" x-text="node.title || 'Text'"></span>
                            </div>
                            <div class="gv-text-body" style="flex:1;">
                                <div class="gv-text-content gv-rich" x-html="node.content || '<span style=\'color:#d1d5db\'>Empty block</span>'"></div>
                            </div>
                        </div>
                    </template>

                    <template x-if="node.type === 'code'">
                        <div style="display:contents;">
                            <div class="gv-code-header">
                                <div class="gv-mac-dot" style="background:#ff5f57;"></div>
                                <div class="gv-mac-dot" style="background:#febc2e;"></div>
                                <div class="gv-mac-dot" style="background:#28c840;"></div>
                                <span class="gv-code-title" x-text="node.title || ''"></span>
                                <span x-show="node.detectedLang" x-text="node.detectedLang" class="gv-code-lang"></span>
                            </div>
                            <div class="gv-code-body" style="flex:1;">
                                <template x-if="node.highlighted">
                                    <pre><code class="hljs" x-html="node.highlighted"></code></pre>
                                </template>
                                <template x-if="!node.highlighted && node.content">
                                    <pre><code x-text="node.content" style="color:#abb2bf;"></code></pre>
                                </template>
                                <template x-if="!node.content">
                                    <div style="padding:14px 16px;color:rgba(255,255,255,.18);font-family:monospace;font-size:.78rem;">// empty</div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="node.type === 'note'">
                        <div style="display:contents;">
                            <div class="gv-note-header" :style="`background:${darken(node.color)}`">
                                <svg class="w-3 h-3 shrink-0" style="color:rgba(0,0,0,.35)" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                </svg>
                                <span class="gv-note-title" x-text="node.title || 'Note'"></span>
                            </div>
                            <div class="gv-note-body" style="flex:1;">
                                <div class="gv-note-content gv-rich" x-html="node.content || '<span style=\'opacity:.4\'>Empty note</span>'"></div>
                            </div>
                        </div>
                    </template>

                    <template x-if="node.type === 'image'">
                        <div style="display:contents;">
                            <div class="gv-image-header">
                                <svg class="w-3 h-3 shrink-0" style="color:rgba(255,255,255,.3)" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                </svg>
                                <span class="gv-image-title" x-text="node.title || 'Image'"></span>
                            </div>
                            <div class="gv-image-body">
                                <template x-if="node.src">
                                    <img :src="node.src" draggable="false">
                                </template>
                                <template x-if="!node.src">
                                    <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:6px;color:rgba(255,255,255,.18);">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                                        <span style="font-size:10px;">No image</span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                </div>
            </template>

        </div>

        <div class="gv-empty" x-show="nodes.length === 0">
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4"
                     style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);">
                    <svg class="w-7 h-7" style="color:rgba(255,255,255,.2)" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold" style="color:rgba(255,255,255,.25)">This Galaxy Space is empty</p>
                <p class="text-xs mt-1" style="color:rgba(255,255,255,.12)">Nothing has been added yet</p>
            </div>
        </div>

        <div class="gv-ctrl-panel" @mousedown.stop>
            <button class="gv-ctrl-btn" title="Zoom in" @click="zoomStep(1.25)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6"/>
                </svg>
            </button>
            <span class="gv-zoom-val" x-text="Math.round(zoom * 100) + '%'"></span>
            <button class="gv-ctrl-btn" title="Zoom out" @click="zoomStep(0.8)">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM13.5 10.5h-6"/>
                </svg>
            </button>
            <div style="width:1px;height:20px;background:rgba(255,255,255,.08);margin:0 2px;"></div>
            <button class="gv-ctrl-btn" title="Fit to content" @click="fitToContent()">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/>
                </svg>
            </button>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script>
    function galaxyView() {
        return {
            nodes : {!! \Illuminate\Support\Js::from($savedNodes) !!},
            edges : {!! \Illuminate\Support\Js::from($savedEdges) !!},

            zoom  : 1,
            panX  : 0,
            panY  : 0,

            _isPanning : false,
            _pan       : null,   // { sx, sy, spx, spy }

            init() {
                this.$nextTick(() => {
                    this.nodes.forEach(n => {
                        if (n.type === 'code' && n.content?.trim()) {
                            const r = hljs.highlightAuto(n.content);
                            n.highlighted  = r.value;
                            n.detectedLang = n.detectedLang || r.language || '';
                        }
                    });

                    setTimeout(() => this.fitToContent(), 80);
                    this._drawEdges();
                    this._initTouch();
                });
            },

            _initTouch() {
                let _t1 = null, _t2 = null;
                let _panStart    = null;
                let _pinchBase   = null, _touchZoomBase = 1, _touchMidBase = null;
                let _velX = 0, _velY = 0, _lastTime = 0, _inertiaRaf = null;
                const vp = this.$refs.viewport;

                window.addEventListener('touchstart', e => {
                    cancelAnimationFrame(_inertiaRaf);
                    _t1 = e.touches[0];
                    _t2 = e.touches.length >= 2 ? e.touches[1] : null;
                    if (_t2) {
                        _pinchBase     = Math.hypot(_t2.clientX - _t1.clientX, _t2.clientY - _t1.clientY);
                        _touchZoomBase = this.zoom;
                        _touchMidBase  = { mx: (_t1.clientX + _t2.clientX) / 2, my: (_t1.clientY + _t2.clientY) / 2, panX: this.panX, panY: this.panY };
                        _panStart = null;
                    } else {
                        _panStart  = { sx: _t1.clientX, sy: _t1.clientY, spx: this.panX, spy: this.panY };
                        _pinchBase = null;
                        _velX = 0; _velY = 0; _lastTime = performance.now();
                    }
                    e.preventDefault();
                }, { passive: false });

                window.addEventListener('touchmove', e => {
                    _t1 = e.touches[0];
                    _t2 = e.touches.length >= 2 ? e.touches[1] : null;
                    const rect = vp.getBoundingClientRect();
                    if (_t2 && _pinchBase) {
                        const dist    = Math.hypot(_t2.clientX - _t1.clientX, _t2.clientY - _t1.clientY);
                        const newZoom = Math.min(4, Math.max(0.08, _touchZoomBase * (dist / _pinchBase)));
                        const curMidX = (_t1.clientX + _t2.clientX) / 2 - rect.left;
                        const curMidY = (_t1.clientY + _t2.clientY) / 2 - rect.top;
                        const baseMidX = _touchMidBase.mx - rect.left;
                        const baseMidY = _touchMidBase.my - rect.top;
                        this.panX = baseMidX - (baseMidX - _touchMidBase.panX) * (newZoom / _touchZoomBase) + (curMidX - baseMidX);
                        this.panY = baseMidY - (baseMidY - _touchMidBase.panY) * (newZoom / _touchZoomBase) + (curMidY - baseMidY);
                        this.zoom = newZoom;
                        this._drawEdges();
                    } else if (_panStart) {
                        const now = performance.now();
                        const dt  = Math.max(now - _lastTime, 1);
                        const nx  = _panStart.spx + (_t1.clientX - _panStart.sx);
                        const ny  = _panStart.spy + (_t1.clientY - _panStart.sy);
                        _velX = (nx - this.panX) / dt * 16;
                        _velY = (ny - this.panY) / dt * 16;
                        this.panX = nx; this.panY = ny; _lastTime = now;
                        this._drawEdges();
                    }
                    e.preventDefault();
                }, { passive: false });

                const endTouch = e => {
                    if (e.touches.length === 0) {
                        if (_panStart && (Math.abs(_velX) > 0.4 || Math.abs(_velY) > 0.4)) {
                            const run = () => {
                                _velX *= 0.92; _velY *= 0.92;
                                this.panX += _velX; this.panY += _velY;
                                this._drawEdges();
                                if (Math.abs(_velX) > 0.3 || Math.abs(_velY) > 0.3) _inertiaRaf = requestAnimationFrame(run);
                            };
                            _inertiaRaf = requestAnimationFrame(run);
                        }
                        _panStart = null; _pinchBase = null; _t1 = null; _t2 = null;
                    } else if (e.touches.length === 1) {
                        _t1 = e.touches[0]; _t2 = null; _pinchBase = null;
                        _panStart = { sx: _t1.clientX, sy: _t1.clientY, spx: this.panX, spy: this.panY };
                        _velX = 0; _velY = 0; _lastTime = performance.now();
                    }
                };
                window.addEventListener('touchend',    endTouch, { passive: false });
                window.addEventListener('touchcancel', () => {
                    cancelAnimationFrame(_inertiaRaf);
                    _panStart = null; _pinchBase = null; _t1 = null; _t2 = null; _velX = 0; _velY = 0;
                }, { passive: false });
            },

            _drawEdges() {
                const layer = document.getElementById('gv-edges');
                if (!layer) return;
                layer.innerHTML = this.edges.map(edge => {
                    const d = this._edgePath(edge);
                    return `
                        <path d="${d}" stroke="rgba(99,102,241,.18)" stroke-width="4" fill="none"/>
                        <path d="${d}" stroke="rgba(99,102,241,.55)" stroke-width="1.5" fill="none"
                              filter="url(#edge-glow)" marker-end="url(#gv-arr)"/>
                    `;
                }).join('');
            },

            _edgePath(edge) {
                const p1 = this._anchor(edge.from, edge.fromAnchor);
                const p2 = this._anchor(edge.to,   edge.toAnchor);
                return this._bezier(p1, edge.fromAnchor, p2, edge.toAnchor);
            },

            _anchor(nodeId, side) {
                const n = this.nodes.find(n => n.id === nodeId);
                if (!n) return { x: 0, y: 0 };
                const vx = cx => cx * this.zoom + this.panX;
                const vy = cy => cy * this.zoom + this.panY;
                return ({
                    top:    { x: vx(n.x + n.w/2), y: vy(n.y) },
                    right:  { x: vx(n.x + n.w),   y: vy(n.y + n.h/2) },
                    bottom: { x: vx(n.x + n.w/2), y: vy(n.y + n.h) },
                    left:   { x: vx(n.x),          y: vy(n.y + n.h/2) },
                })[side] ?? { x: vx(n.x + n.w/2), y: vy(n.y + n.h/2) };
            },

            _bezier(p1, a1, p2, a2) {
                const d  = Math.max(Math.abs(p2.x-p1.x), Math.abs(p2.y-p1.y), 60) * 0.55;
                const c1 = { x:p1.x, y:p1.y }, c2 = { x:p2.x, y:p2.y };
                ({ top:()=>c1.y-=d, right:()=>c1.x+=d, bottom:()=>c1.y+=d, left:()=>c1.x-=d }[a1] ?? (()=>{}))();
                if (a2) ({ top:()=>c2.y-=d, right:()=>c2.x+=d, bottom:()=>c2.y+=d, left:()=>c2.x-=d }[a2] ?? (()=>{}))();
                else c2.x -= d;
                return `M ${p1.x} ${p1.y} C ${c1.x} ${c1.y} ${c2.x} ${c2.y} ${p2.x} ${p2.y}`;
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

            zoomStep(factor) {
                const vp = this.$refs.viewport;
                const mx = vp ? vp.offsetWidth  / 2 : 0;
                const my = vp ? vp.offsetHeight / 2 : 0;
                const nz = Math.min(4, Math.max(0.08, this.zoom * factor));
                this.panX = mx - (mx - this.panX) * (nz / this.zoom);
                this.panY = my - (my - this.panY) * (nz / this.zoom);
                this.zoom = nz;
                this._drawEdges();
            },

            onWheel(e) {
                e.ctrlKey ? this._wheelZoom(e) : this._wheelPan(e);
            },

            _wheelZoom(e) {
                const rect = this.$refs.viewport.getBoundingClientRect();
                const mx   = e.clientX - rect.left;
                const my   = e.clientY - rect.top;
                if (e.deltaMode === 0) {
                    const factor = Math.exp(-e.deltaY * 0.005);
                    const nz     = Math.min(4, Math.max(0.08, this.zoom * factor));
                    this.panX = mx - (mx - this.panX) * (nz / this.zoom);
                    this.panY = my - (my - this.panY) * (nz / this.zoom);
                    this.zoom = nz;
                    this._drawEdges();
                } else {
                    if (!this._zoomAnim) {
                        this._zoomAnim = { target: this.zoom, mx, my };
                    } else {
                        this._zoomAnim.mx = mx;
                        this._zoomAnim.my = my;
                    }
                    const f = e.deltaY < 0 ? 1.12 : 1 / 1.12;
                    this._zoomAnim.target = Math.min(4, Math.max(0.08, this._zoomAnim.target * f));
                    if (!this._zoomRaf) this._tickZoomAnim();
                }
            },

            _tickZoomAnim() {
                const a = this._zoomAnim;
                if (!a) { this._zoomRaf = null; return; }
                const dz = a.target - this.zoom;
                if (Math.abs(dz) < 0.0005) {
                    const nz  = a.target;
                    this.panX = a.mx - (a.mx - this.panX) * (nz / this.zoom);
                    this.panY = a.my - (a.my - this.panY) * (nz / this.zoom);
                    this.zoom = nz;
                    this._zoomAnim = null;
                    this._zoomRaf  = null;
                    this._drawEdges();
                    return;
                }
                const nz  = this.zoom + dz * 0.14;
                this.panX = a.mx - (a.mx - this.panX) * (nz / this.zoom);
                this.panY = a.my - (a.my - this.panY) * (nz / this.zoom);
                this.zoom = nz;
                this._drawEdges();
                this._zoomRaf = requestAnimationFrame(() => this._tickZoomAnim());
            },

            _wheelPan(e) {
                cancelAnimationFrame(this._wheelPanRaf);
                clearTimeout(this._wheelPanTimer);
                const dx  = e.deltaMode === 1 ? e.deltaX * 16 : e.deltaX;
                const dy  = e.deltaMode === 1 ? e.deltaY * 16 : e.deltaY;
                const now = Date.now();
                const dt  = now - (this._wheelPanLastT || now);
                if (dt > 0 && dt < 80) {
                    this._wheelPanVx = (-dx / dt) * 14;
                    this._wheelPanVy = (-dy / dt) * 14;
                }
                this._wheelPanLastT = now;
                this.panX -= dx;
                this.panY -= dy;
                this._drawEdges();
                this._wheelPanTimer = setTimeout(() => {
                    if (Math.hypot(this._wheelPanVx || 0, this._wheelPanVy || 0) < 1.5) return;
                    const step = () => {
                        this._wheelPanVx *= 0.88;
                        this._wheelPanVy *= 0.88;
                        if (Math.abs(this._wheelPanVx) < 0.2 && Math.abs(this._wheelPanVy) < 0.2) return;
                        this.panX += this._wheelPanVx;
                        this.panY += this._wheelPanVy;
                        if (this.edges.length) this._drawEdges();
                        this._wheelPanRaf = requestAnimationFrame(step);
                    };
                    this._wheelPanRaf = requestAnimationFrame(step);
                }, 60);
            },

            onMousedown(e) {
                if (e.button !== 0) return;
                const vp = this.$refs.viewport;
                if (!vp) return;
                const rect = vp.getBoundingClientRect();
                if (e.clientX < rect.left || e.clientX > rect.right ||
                    e.clientY < rect.top  || e.clientY > rect.bottom) return;
                this._isPanning = true;
                this._pan = { sx: e.clientX, sy: e.clientY, spx: this.panX, spy: this.panY };
            },

            onMousemove(e) {
                if (!this._pan) return;
                this.panX = this._pan.spx + (e.clientX - this._pan.sx);
                this.panY = this._pan.spy + (e.clientY - this._pan.sy);
                this._drawEdges();
            },

            onMouseup() {
                this._pan = null;
                this._isPanning = false;
            },

            darken(hex) {
                const c = parseInt((hex ?? '#fef9c3').replace('#',''), 16);
                const r = Math.max(0, (c>>16&255) - 15);
                const g = Math.max(0, (c>>8 &255) - 15);
                const b = Math.max(0, (c     &255) - 15);
                return `rgb(${r},${g},${b})`;
            },
        };
    }
    </script>

</body>
</html>
