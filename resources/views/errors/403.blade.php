@extends('layouts.guest')

@section('title', '403 — Access denied')
@section('description', 'You do not have permission to access this page.')

@section('content')
<div class="min-h-[calc(100vh-7rem)] flex items-center justify-center px-5 pt-14">
    <div class="text-center max-w-md mx-auto">

        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-zinc-100 mb-6">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-400">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>

        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">403</p>
        <h1 class="text-2xl font-bold text-zinc-900 mb-3">Access denied</h1>
        <p class="text-sm text-zinc-500 leading-relaxed mb-8">
            You don't have permission to view this page.<br>Sign in with the right account or go back home.
        </p>

        <div class="flex items-center justify-center gap-3">
            <a href="/" class="inline-flex items-center gap-2 text-sm font-medium text-white bg-zinc-900 px-4 py-2 rounded-lg hover:bg-zinc-700 transition-colors duration-150">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Go home
            </a>
            @guest
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-medium text-zinc-600 bg-zinc-100 px-4 py-2 rounded-lg hover:bg-zinc-200 transition-colors duration-150">
                Sign in
            </a>
            @endguest
        </div>

    </div>
</div>
@endsection
