@extends('layouts.guest')

@section('title', '404 — Page not found')
@section('description', 'The page you are looking for does not exist.')

@section('content')
<div class="min-h-[calc(100vh-7rem)] flex items-center justify-center px-5 pt-14">
    <div class="text-center max-w-md mx-auto">

        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-zinc-100 mb-6">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-400">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35M11 8v3M11 14h.01"/>
            </svg>
        </div>

        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">404</p>
        <h1 class="text-2xl font-bold text-zinc-900 mb-3">Page not found</h1>
        <p class="text-sm text-zinc-500 leading-relaxed mb-8">
            This page doesn't exist or may have been moved.<br>Double-check the URL and try again.
        </p>

        <div class="flex items-center justify-center gap-3">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-medium text-white bg-zinc-900 px-4 py-2 rounded-lg hover:bg-zinc-700 transition-colors duration-150">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Go home
            </a>
            @auth
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 bg-zinc-100 px-4 py-2 rounded-lg hover:bg-zinc-200 transition-colors duration-150">
                Dashboard
            </a>
            @endauth
        </div>

    </div>
</div>
@endsection
