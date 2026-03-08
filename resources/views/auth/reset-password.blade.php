<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password — Slidd</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full antialiased flex items-center justify-center px-4 py-12" style="font-family:-apple-system,BlinkMacSystemFont,'Inter','Segoe UI',sans-serif;background:#f4f4f5;">

    <div class="w-full max-w-sm">

        <div class="text-center mb-8">
            <a href="/" class="text-sm font-bold tracking-tight text-gray-900">Slidd</a>
        </div>

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-8 py-8">
            <h1 class="text-lg font-semibold text-gray-900 mb-1">Set new password</h1>
            <p class="text-sm text-gray-400 mb-6">Choose a strong password for your account.</p>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="block text-xs font-medium text-gray-700 mb-1.5">Email</label>
                    <input
                        id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                        required autofocus autocomplete="username" placeholder="you@example.com"
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->has('email') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-xs font-medium text-gray-700 mb-1.5">New Password</label>
                    <input
                        id="password" type="password" name="password"
                        required autocomplete="new-password" placeholder="••••••••"
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->has('password') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1.5">Confirm Password</label>
                    <input
                        id="password_confirmation" type="password" name="password_confirmation"
                        required autocomplete="new-password" placeholder="••••••••"
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->has('password_confirmation') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                    >
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-150">
                    Reset Password
                </button>
            </form>
        </div>

    </div>

</body>
</html>
