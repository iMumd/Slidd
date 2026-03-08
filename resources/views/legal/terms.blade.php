@extends('layouts.guest')

@section('title', 'Terms of Service — Slidd')

@section('content')

<div class="max-w-2xl mx-auto px-6 pt-32 pb-24">
    <div class="mb-10">
        <a href="/" class="flex w-max items-center gap-1.5 text-xs text-zinc-400 hover:text-zinc-700 transition-colors duration-150 mb-8 group">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Back
        </a>
        <p class="text-xs text-zinc-400 font-medium uppercase tracking-widest mb-3">Legal</p>
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900 mb-3">Terms of Service</h1>
        <p class="text-sm text-zinc-400">Last updated: {{ date('F j, Y') }}</p>
    </div>

    <div class="prose prose-sm prose-zinc max-w-none space-y-8 text-zinc-600 leading-relaxed">

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">1. Acceptance</h2>
            <p>By accessing or using Slidd, you agree to be bound by these Terms of Service. If you do not agree to these terms, do not use the service. We may update these terms from time to time and will notify you of significant changes via email.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">2. Your Account</h2>
            <p>You are responsible for maintaining the confidentiality of your account credentials and for all activity that occurs under your account. You must notify us immediately of any unauthorized use of your account.</p>
            <p class="mt-3">You must be at least 16 years old to use Slidd. By creating an account, you confirm that you meet this requirement.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">3. Your Content</h2>
            <p>You retain full ownership of the content you create in Slidd. By using the service, you grant us a limited license to store and display your content solely for the purpose of providing the service to you. We do not claim ownership of your presentations, slides, or any other content.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">4. Acceptable Use</h2>
            <p>You agree not to use Slidd to create, share, or store content that is unlawful, harmful, threatening, abusive, or otherwise objectionable. You may not attempt to reverse-engineer, scrape, or disrupt the platform or its infrastructure.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">5. Service Availability</h2>
            <p>We strive for high availability but do not guarantee uninterrupted access to Slidd. We may modify, suspend, or discontinue any part of the service at any time. We will provide reasonable notice for planned downtime or significant changes.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">6. Termination</h2>
            <p>You may close your account at any time from your profile settings. We reserve the right to suspend or terminate accounts that violate these terms, with or without notice depending on the severity of the violation.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">7. Limitation of Liability</h2>
            <p>Slidd is provided "as is" without warranties of any kind. To the fullest extent permitted by law, we are not liable for any indirect, incidental, or consequential damages arising from your use of the service.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">8. Contact</h2>
            <p>Questions about these terms? Reach out at <span class="text-zinc-900 font-medium">legal@slidd.app</span>.</p>
        </section>

    </div>
</div>

@endsection
