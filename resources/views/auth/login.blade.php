<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in — Slidd</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-zinc-50 antialiased text-zinc-900 min-h-screen flex items-center justify-center px-4 py-12">

    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <a href="/" class="font-semibold text-sm tracking-tight text-zinc-900">Slidd</a>
        </div>

        <div class="bg-white border border-zinc-200 rounded-xl px-8 py-8">
            <h1 class="text-xl font-semibold text-zinc-900 mb-1">Sign in</h1>
            <p class="text-sm text-zinc-500 mb-6">Welcome back. Enter your credentials to continue.</p>

            @if (session('status'))
                <div class="mb-4 text-sm text-zinc-600 bg-zinc-50 border border-zinc-200 rounded-lg px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-700 mb-1.5">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                        placeholder="you@example.com"
                        class="w-full px-3 py-2 text-sm border border-zinc-200 rounded-lg bg-white placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 transition-colors duration-150 @error('email') border-red-400 focus:ring-red-400 focus:border-red-400 @enderror"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-zinc-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-zinc-400 hover:text-zinc-700 transition-colors duration-150">Forgot password?</a>
                        @endif
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-3 py-2 text-sm border border-zinc-200 rounded-lg bg-white placeholder-zinc-400 text-zinc-900 focus:outline-none focus:ring-1 focus:ring-zinc-900 focus:border-zinc-900 transition-colors duration-150 @error('password') border-red-400 focus:ring-red-400 focus:border-red-400 @enderror"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full py-2.5 px-4 bg-zinc-900 text-white text-sm font-medium rounded-lg hover:bg-zinc-700 transition-colors duration-150 mt-2"
                >
                    Sign in
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-zinc-500 mt-6">
            Don't have an account?
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="text-zinc-900 font-medium hover:underline ml-1">Register</a>
            @endif
        </p>

    </div>

</body>
</html>
