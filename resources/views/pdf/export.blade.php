<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
/* ═══════════════════════════════════════════════════════
   Page setup — A4, generous margins
   ═══════════════════════════════════════════════════════ */
@page {
    size: A4 portrait;
    margin-top:    55pt;
    margin-bottom: 50pt;
    margin-left:   58pt;
    margin-right:  58pt;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html, body {
    background-color: #ffffff;
    color: #0f172a;
    font-family: "DejaVu Sans", Helvetica, Arial, sans-serif;
    font-size: 13pt;
    line-height: 1.65;
}

/* ═══════════════════════════════════════════════════════
   Slide wrapper — one slide = one PDF page
   ═══════════════════════════════════════════════════════ */
.slide {
    width: 100%;
    page-break-after: always;
    padding-bottom: 24pt;
}
.slide:last-child {
    page-break-after: auto;
}

/* ── Slide meta bar (number + title) ── */
.slide-meta {
    width: 100%;
    margin-bottom: 28pt;
    border-bottom: 1pt solid #e2e8f0;
    padding-bottom: 8pt;
}
.slide-meta-inner {
    display: table;
    width: 100%;
}
.slide-project {
    display: table-cell;
    font-size: 8pt;
    font-weight: bold;
    color: #64748b;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    vertical-align: middle;
}
.slide-num {
    display: table-cell;
    font-size: 8pt;
    color: #94a3b8;
    text-align: right;
    vertical-align: middle;
    letter-spacing: 0.05em;
}

/* ═══════════════════════════════════════════════════════
   Text blocks
   ═══════════════════════════════════════════════════════ */
.txt-block {
    font-family: "DejaVu Sans", Helvetica, Arial, sans-serif;
    font-size: 13pt;
    line-height: 1.7;
    color: #0f172a;
    margin-bottom: 14pt;
    word-break: break-word;
}

/* Rich-text formatting produced by the editor */
.txt-block b,
.txt-block strong { font-weight: bold; }

.txt-block i,
.txt-block em     { font-style: italic; }

.txt-block u      { text-decoration: underline; }
.txt-block s      { text-decoration: line-through; }

/* Headings written inline by contenteditable */
.txt-block h1 {
    font-size: 22pt;
    font-weight: bold;
    color: #0f172a;
    line-height: 1.25;
    margin-bottom: 10pt;
}
.txt-block h2 {
    font-size: 17pt;
    font-weight: bold;
    color: #1e293b;
    line-height: 1.3;
    margin-bottom: 8pt;
}
.txt-block h3 {
    font-size: 14pt;
    font-weight: bold;
    color: #1e293b;
    margin-bottom: 6pt;
}

/* Lists */
.txt-block ul,
.txt-block ol {
    padding-left: 22pt;
    margin-bottom: 8pt;
}
.txt-block ul { list-style-type: disc; }
.txt-block ol { list-style-type: decimal; }
.txt-block li  { margin-bottom: 3pt; }

/* Divider */
.txt-block hr {
    border: none;
    border-top: 1pt solid #e2e8f0;
    margin: 18pt 0;
    width: 100%;
}

/* Inline code in text */
.txt-block code {
    font-family: "DejaVu Sans Mono", "Courier New", Courier, monospace;
    font-size: 11pt;
    background-color: #f1f5f9;
    color: #7c3aed;
    padding: 1pt 4pt;
    border-radius: 3pt;
}

/* ═══════════════════════════════════════════════════════
   Code blocks — dark terminal card
   ═══════════════════════════════════════════════════════ */
.code-card {
    margin-bottom: 16pt;
    background-color: #1e1e2e;
    border-radius: 10pt;
    overflow: hidden;
    border: 1pt solid #2d2d3f;
}

/* Header row — table layout (flexbox not reliable in DomPDF) */
.code-header {
    display: table;
    width: 100%;
    background-color: #16161e;
    border-bottom: 1pt solid #2d2d3f;
    padding: 7pt 14pt;
}
.code-header-dots {
    display: table-cell;
    vertical-align: middle;
    width: 1%;
    white-space: nowrap;
}
.code-dot {
    display: inline-block;
    width: 9pt;
    height: 9pt;
    border-radius: 50%;
    margin-right: 5pt;
}
.code-header-lang {
    display: table-cell;
    vertical-align: middle;
    padding-left: 10pt;
    font-family: "DejaVu Sans", Helvetica, Arial, sans-serif;
    font-size: 7.5pt;
    font-weight: bold;
    color: #6b7280;
    letter-spacing: 0.12em;
    text-transform: uppercase;
}

/* Code body */
.code-body {
    font-family: "DejaVu Sans Mono", "Courier New", Courier, monospace;
    font-size: 10pt;
    line-height: 1.65;
    color: #cdd6f4;
    background-color: #1e1e2e;
    padding: 14pt 16pt;
    white-space: pre-wrap;
    word-break: break-all;
    overflow: hidden;
}
</style>
</head>
<body>

@foreach ($project->slides as $slide)
<div class="slide">

    {{-- Slide meta bar --}}
    <div class="slide-meta">
        <div class="slide-meta-inner">
            <span class="slide-project">{{ $project->title }}</span>
            <span class="slide-num">{{ $loop->iteration }} / {{ $project->slides->count() }}</span>
        </div>
    </div>

    {{-- Blocks --}}
    @foreach ($slide->blocks as $block)

        @if ($block->type === 'text')
            <div class="txt-block" dir="auto">{!! $block->content['html'] ?? '' !!}</div>

        @elseif ($block->type === 'code')
            @php
                $code = $block->content['code'] ?? '';
                $lang = $block->content['lang'] ?? '';
            @endphp
            <div class="code-card">
                <div class="code-header">
                    <span class="code-header-dots">
                        <span class="code-dot" style="background-color:#ff5f57;"></span><span class="code-dot" style="background-color:#febc2e;"></span><span class="code-dot" style="background-color:#28c840;"></span>
                    </span>
                    @if ($lang)
                        <span class="code-header-lang">{{ $lang }}</span>
                    @endif
                </div>
                <pre class="code-body">{{ $code }}</pre>
            </div>
        @endif

    @endforeach

</div>
@endforeach

</body>
</html>
