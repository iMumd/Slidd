<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Presentation not found — Slidd</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='22' fill='%230f172a'/><text y='74' x='50' text-anchor='middle' font-size='62' font-family='system-ui,sans-serif' font-weight='700' fill='white'>S</text></svg>">
    <link rel="icon" href="/favicon.ico" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        body {
            background: #08090d;
            color: rgba(255,255,255,.85);
        }

        /* subtle nebula background */
        .nebula {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 60% 50% at 50% 30%, rgba(99,102,241,.07) 0%, transparent 70%),
                radial-gradient(ellipse 40% 30% at 70% 70%, rgba(139,92,246,.05) 0%, transparent 60%);
        }

        /* star dots */
        .stars {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                radial-gradient(1px 1px at 20% 30%, rgba(255,255,255,.18) 0%, transparent 100%),
                radial-gradient(1px 1px at 60% 15%, rgba(255,255,255,.12) 0%, transparent 100%),
                radial-gradient(1px 1px at 80% 55%, rgba(255,255,255,.15) 0%, transparent 100%),
                radial-gradient(1px 1px at 35% 75%, rgba(255,255,255,.10) 0%, transparent 100%),
                radial-gradient(1px 1px at 90% 25%, rgba(255,255,255,.13) 0%, transparent 100%),
                radial-gradient(1px 1px at 10% 60%, rgba(255,255,255,.08) 0%, transparent 100%),
                radial-gradient(1px 1px at 50% 90%, rgba(255,255,255,.11) 0%, transparent 100%),
                radial-gradient(1px 1px at 75% 40%, rgba(255,255,255,.09) 0%, transparent 100%);
        }

        .hdr {
            height: 3.5rem;
            background: rgba(8, 9, 13, 0.88);
            border-bottom: 1px solid rgba(255,255,255,.07);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1rem;
            position: fixed; top: 0; left: 0; right: 0; z-index: 40;
        }

        .brand {
            font-size: .875rem; font-weight: 700; color: rgba(255,255,255,.9);
            letter-spacing: -.01em; text-decoration: none;
        }

        .hdr-link {
            font-size: .75rem; font-weight: 500;
            color: rgba(255,255,255,.4);
            text-decoration: none;
            padding: 5px 10px; border-radius: 7px;
            border: 1px solid rgba(255,255,255,.08);
            transition: color .15s, background .15s, border-color .15s;
        }
        .hdr-link:hover {
            color: rgba(255,255,255,.8);
            background: rgba(255,255,255,.06);
            border-color: rgba(255,255,255,.14);
        }

        .content {
            position: relative; z-index: 1;
            min-height: 100dvh;
            display: flex; align-items: center; justify-content: center;
            padding: 5rem 1.5rem 2rem;
        }

        .card {
            text-align: center;
            max-width: 420px;
            width: 100%;
        }

        .icon-wrap {
            display: inline-flex;
            align-items: center; justify-content: center;
            width: 64px; height: 64px;
            border-radius: 18px;
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            margin-bottom: 1.5rem;
        }

        .error-code {
            font-size: .6875rem; font-weight: 600;
            color: rgba(255,255,255,.25);
            text-transform: uppercase; letter-spacing: .12em;
            margin-bottom: .75rem;
        }

        .title {
            font-size: 1.5rem; font-weight: 700;
            color: rgba(255,255,255,.9);
            margin-bottom: .75rem; line-height: 1.3;
        }

        .desc {
            font-size: .875rem; line-height: 1.7;
            color: rgba(255,255,255,.4);
            margin-bottom: 2rem;
        }

        .actions {
            display: flex; align-items: center; justify-content: center; gap: .625rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex; align-items: center; gap: .4rem;
            font-size: .8125rem; font-weight: 500;
            color: #fff;
            background: rgba(99,102,241,.8);
            border: 1px solid rgba(99,102,241,.5);
            padding: .5rem 1rem; border-radius: .5rem;
            text-decoration: none;
            transition: background .15s, border-color .15s;
        }
        .btn-primary:hover {
            background: rgba(99,102,241,1);
            border-color: rgba(99,102,241,.8);
        }

        .btn-ghost {
            display: inline-flex; align-items: center; gap: .4rem;
            font-size: .8125rem; font-weight: 500;
            color: rgba(255,255,255,.5);
            background: rgba(255,255,255,.05);
            border: 1px solid rgba(255,255,255,.08);
            padding: .5rem 1rem; border-radius: .5rem;
            text-decoration: none;
            transition: color .15s, background .15s, border-color .15s;
        }
        .btn-ghost:hover {
            color: rgba(255,255,255,.85);
            background: rgba(255,255,255,.08);
            border-color: rgba(255,255,255,.14);
        }
    </style>
</head>
<body class="h-full antialiased">

    <div class="nebula"></div>
    <div class="stars"></div>

    <header class="hdr">
        <a href="/" class="brand">Slidd</a>
        <a href="/" class="hdr-link">Back to home</a>
    </header>

    <div class="content">
        <div class="card">

            <div class="icon-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.35)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                    <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                    <line x1="2" y1="2" x2="22" y2="22"/>
                </svg>
            </div>

            <p class="error-code">Presentation not found</p>
            <h1 class="title">This presentation doesn't exist</h1>
            <p class="desc">
                It may have been deleted by its owner,<br>or this link is no longer valid.
            </p>

            <div class="actions">
                <a href="/" class="btn-primary">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Go home
                </a>
                @auth
                <a href="{{ url('/dashboard') }}" class="btn-ghost">
                    My dashboard
                </a>
                @else
                <a href="{{ route('register') }}" class="btn-ghost">
                    Create your own
                </a>
                @endauth
            </div>

        </div>
    </div>

</body>
</html>
