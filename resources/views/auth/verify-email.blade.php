<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email — Slidd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased flex items-center justify-center px-4 py-12" style="font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;background:#f4f4f5;">

    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <a href="/" class="text-sm font-bold tracking-tight text-gray-900">Slidd</a>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-8 py-8">

            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center mb-5">
                <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
            </div>

            <h1 class="text-lg font-semibold text-gray-900 mb-1">Check your inbox</h1>
            <p class="text-sm text-gray-400 mb-6">We sent a verification link to your email address. Click it to activate your account.</p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-5 text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                    A new verification link has been sent to your email.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-150">
                    Resend Verification Email
                </button>
            </form>
        </div>

        <div class="text-center mt-6">
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm text-gray-400 hover:text-gray-700 transition-colors duration-150">
                    Sign out
                </button>
            </form>
        </div>

    </div>

</body>
</html>
