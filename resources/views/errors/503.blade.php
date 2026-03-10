@extends('layouts.guest')

@section('title', 'Back soon — Slidd')
@section('description', 'Slidd is under maintenance. We will be back shortly.')

@section('content')
<div class="min-h-[calc(100vh-7rem)] flex items-center justify-center px-5 pt-14">
    <div class="text-center max-w-md mx-auto">

        <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-zinc-100 mb-6">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="text-zinc-400">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
            </svg>
        </div>

        <p class="text-xs font-semibold text-zinc-400 uppercase tracking-widest mb-3">Maintenance</p>
        <h1 class="text-2xl font-bold text-zinc-900 mb-3">Back soon.</h1>
        <p class="text-sm text-zinc-500 leading-relaxed mb-8">
            Slidd is undergoing scheduled maintenance.<br>We'll be back up shortly — thanks for your patience.
        </p>

        <a href="/" class="inline-flex items-center gap-2 text-sm font-medium text-white bg-zinc-900 px-4 py-2 rounded-lg hover:bg-zinc-700 transition-colors duration-150">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6"/><path d="M22 11.5A10 10 0 0 0 3.2 7.2M2 12.5a10 10 0 0 0 18.8 4.2"/></svg>
            Try again
        </a>

    </div>
</div>
@endsection
