@extends('layouts.guest')

@section('title', 'Privacy Policy — Slidd')

@section('content')

<div class="max-w-2xl mx-auto px-6 pt-32 pb-24">
    <div class="mb-10">
        <a href="/" class="flex w-max items-center gap-1.5 text-xs text-zinc-400 hover:text-zinc-700 transition-colors duration-150 mb-8 group">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Back
        </a>
        <p class="text-xs text-zinc-400 font-medium uppercase tracking-widest mb-3">Legal</p>
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900 mb-3">Privacy Policy</h1>
        <p class="text-sm text-zinc-400">Last updated: {{ date('F j, Y') }}</p>
    </div>

    <div class="prose prose-sm prose-zinc max-w-none space-y-8 text-zinc-600 leading-relaxed">

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">1. Information We Collect</h2>
            <p>When you create an account or use Slidd, we collect information you provide directly to us. This includes your name, email address, and any content you create within the application such as projects, slides, and blocks.</p>
            <p class="mt-3">We also collect usage data automatically — including browser type, IP address, pages visited, and time spent — to improve the product.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">2. How We Use Your Information</h2>
            <p>We use the information we collect to operate and improve Slidd, communicate with you about your account, respond to your requests, and ensure the security of the platform. We do not sell your personal data to third parties.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">3. Data Storage</h2>
            <p>Your data is stored on servers located within the European Union. We retain your account data for as long as your account is active. You may request deletion of your account and associated data at any time by contacting us.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">4. Cookies</h2>
            <p>Slidd uses essential cookies to maintain your session and remember your preferences. We do not use tracking cookies or third-party advertising cookies.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">5. Third-Party Services</h2>
            <p>We may use third-party services for infrastructure, error monitoring, and analytics. These services have their own privacy policies and we ensure they meet adequate data protection standards.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">6. Your Rights</h2>
            <p>You have the right to access, correct, or delete your personal data at any time. If you are located in the EU or EEA, you also have rights under the GDPR including data portability and the right to object to processing.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">7. Contact</h2>
            <p>If you have questions about this policy or your data, reach out at <span class="text-zinc-900 font-medium">privacy@slidd.app</span>.</p>
        </section>

    </div>
</div>

@endsection
