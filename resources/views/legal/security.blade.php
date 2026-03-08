@extends('layouts.guest')

@section('title', 'Security — Slidd')

@section('content')

<div class="max-w-2xl mx-auto px-6 pt-32 pb-24">
    <div class="mb-10">
        <a href="/" class="flex w-max items-center gap-1.5 text-xs text-zinc-400 hover:text-zinc-700 transition-colors duration-150 mb-8 group">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
            Back
        </a>
        <p class="text-xs text-zinc-400 font-medium uppercase tracking-widest mb-3">Legal</p>
        <h1 class="text-4xl font-bold tracking-tight text-zinc-900 mb-3">Security</h1>
        <p class="text-sm text-zinc-400">Last updated: {{ date('F j, Y') }}</p>
    </div>

    <div class="space-y-8 text-zinc-600 leading-relaxed">

        <p class="text-base text-zinc-500">Security is not an afterthought at Slidd. Here is how we protect your data and the platform.</p>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">Infrastructure</h2>
            <p class="text-sm">Slidd runs on hardened cloud infrastructure. Servers operate in isolated environments with strict firewall rules. All services run behind a reverse proxy with automated TLS certificate renewal. We apply OS-level and dependency security patches on a regular basis.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">Data Encryption</h2>
            <p class="text-sm">All data in transit is encrypted with TLS 1.2 or higher. Data at rest is encrypted using AES-256. Database backups are encrypted before storage and stored in geographically separate locations.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">Authentication</h2>
            <p class="text-sm">Passwords are hashed using bcrypt with a sufficient cost factor. We enforce minimum password complexity and rate-limit login attempts to prevent brute-force attacks. Session tokens are rotated on authentication and invalidated on logout.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">Application Security</h2>
            <p class="text-sm">Slidd is built with security-first practices. CSRF tokens are required on all state-changing requests. All user input is validated and sanitized. SQL queries use parameterized statements. Security headers are set on all responses.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">Access Control</h2>
            <p class="text-sm">Internal access to production systems is restricted to authorized personnel only, protected by multi-factor authentication and audited. No team member has standing access to user data beyond what is required to operate the service.</p>
        </section>

        <div class="border-t border-zinc-100"></div>

        <section>
            <h2 class="text-base font-semibold text-zinc-900 mb-3">Responsible Disclosure</h2>
            <p class="text-sm">If you discover a security vulnerability in Slidd, please report it responsibly. Do not exploit or publicly disclose the issue before we have had a chance to address it.</p>
            <div class="mt-4 bg-zinc-50 border border-zinc-200 rounded-xl px-5 py-4 flex items-center gap-3">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#52525b" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span class="text-sm text-zinc-700 font-medium">security@slidd.app</span>
            </div>
            <p class="text-xs text-zinc-400 mt-3">We aim to acknowledge reports within 48 hours and resolve confirmed issues within 14 days.</p>
        </section>

    </div>
</div>

@endsection
