<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Confirm Password — Slidd</title>
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                </svg>
            </div>

            <h1 class="text-lg font-semibold text-gray-900 mb-1">Confirm your password</h1>
            <p class="text-sm text-gray-400 mb-6">This is a secure area. Please re-enter your password to continue.</p>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="password" class="block text-xs font-medium text-gray-700 mb-1.5">Password</label>
                    <input
                        id="password" type="password" name="password"
                        required autocomplete="current-password" placeholder="••••••••"
                        class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->has('password') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-2.5 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors duration-150">
                    Confirm
                </button>
            </form>
        </div>

    </div>

</body>
</html>
