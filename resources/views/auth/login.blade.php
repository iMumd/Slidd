<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in — Slidd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased flex items-center justify-center px-4 py-12" style="font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;background:#f4f4f5;">

    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <a href="/" class="text-sm font-bold tracking-tight text-gray-900">Slidd</a>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-8 py-8">
            <h1 class="text-lg font-semibold text-gray-900 mb-1">Sign in</h1>
            <p class="text-sm text-gray-400 mb-6">Welcome back. Enter your credentials to continue.</p>

            @if (session('status'))
                <div class="mb-5 text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-medium text-gray-700 mb-1.5">Email</label>
                    <input
                        id="email" type="email" name="email" value="{{ old('email') }}"
                        required autofocus autocomplete="username" placeholder="you@example.com"
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->has('email') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="text-xs font-medium text-gray-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-gray-400 hover:text-gray-700 transition-colors duration-150">Forgot?</a>
                        @endif
                    </div>
                    <input
                        id="password" type="password" name="password"
                        required autocomplete="current-password" placeholder="••••••••"
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->has('password') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input id="remember_me" type="checkbox" name="remember" class="w-3.5 h-3.5 rounded border-gray-300 text-gray-900 focus:ring-gray-900/20">
                    <label for="remember_me" class="text-xs text-gray-500">Remember me</label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-150 mt-1">
                    Sign in
                </button>
            </form>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-400 mt-6">
                No account?
                <a href="{{ route('register') }}" class="text-gray-900 font-medium hover:underline ml-1">Create one</a>
            </p>
        @endif

    </div>

</body>
</html>
