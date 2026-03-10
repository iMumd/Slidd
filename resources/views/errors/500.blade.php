@extends('layouts.guest')

@section('title', '500 — Server error')
@section('description', 'Something went wrong on our end.')

@section('content')
<div class="min-h-[calc(100vh-7rem)] flex items-center justify-center px-5 pt-14">
    <div class="text-center max-w-md mx-auto">

        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-zinc-100 mb-6">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-400">
                <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
        </div>

        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">500</p>
        <h1 class="text-2xl font-bold text-zinc-900 mb-3">Something went wrong</h1>
        <p class="text-sm text-zinc-500 leading-relaxed mb-8">
            An unexpected error occurred on our end.<br>Try refreshing — if the issue persists, it'll be fixed soon.
        </p>

        <div class="flex items-center justify-center gap-3">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-medium text-white bg-zinc-900 px-4 py-2 rounded-lg hover:bg-zinc-700 transition-colors duration-150">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Go home
            </a>
            <button onclick="location.reload()" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 bg-zinc-100 px-4 py-2 rounded-lg hover:bg-zinc-200 transition-colors duration-150">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6"/><path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"/></svg>
                Retry
            </button>
            @auth
            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 bg-zinc-100 px-4 py-2 rounded-lg hover:bg-zinc-200 transition-colors duration-150">
                Dashboard
            </a>
            @endauth
        </div>

    </div>
</div>
@endsection
