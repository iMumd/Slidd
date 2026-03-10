@extends('layouts.guest')

@section('title', '419 — Session expired')
@section('description', 'Your session has expired.')

@section('content')
<div class="min-h-[calc(100vh-7rem)] flex items-center justify-center px-5 pt-14">
    <div class="text-center max-w-md mx-auto">

        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-zinc-100 mb-6">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-400">
                <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"/><polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>

        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">419</p>
        <h1 class="text-2xl font-bold text-zinc-900 mb-3">Session expired</h1>
        <p class="text-sm text-zinc-500 leading-relaxed mb-8">
            Your session timed out for security reasons.<br>Refresh the page and try again.
        </p>

        <div class="flex items-center justify-center gap-3">
            <button onclick="location.reload()" class="inline-flex items-center gap-2 text-sm font-medium text-white bg-zinc-900 px-4 py-2 rounded-lg hover:bg-zinc-700 transition-colors duration-150">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6"/><path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"/></svg>
                Refresh
            </button>
            <a href="/" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 bg-zinc-100 px-4 py-2 rounded-lg hover:bg-zinc-200 transition-colors duration-150">
                Go home
            </a>
        </div>

    </div>
</div>
@endsection
