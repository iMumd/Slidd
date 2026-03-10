@extends('layouts.app')

@section('title', 'Settings — Slidd')

@section('content')

<div class="p-4 sm:p-6 md:p-10">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Settings</h1>
        <p class="text-sm text-gray-400 mt-1">Manage your account and preferences.</p>
    </div>

    <div class="max-w-3xl">

        <div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-6">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-slate-900">Profile Information</h2>
                <p class="text-xs text-gray-400 mt-0.5">Update your name and email address.</p>
            </div>
            <div class="px-6 py-5">

                @if (session('status') === 'profile-updated')
                    <div class="mb-5 flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg px-4 py-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Profile updated successfully.
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('patch')

                    <div>
                        <label for="name" class="block text-xs font-medium text-gray-700 mb-1.5">Full name</label>
                        <input
                            id="name" type="text" name="name" value="{{ old('name', $user->name) }}"
                            required autocomplete="name"
                            class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->has('name') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-medium text-gray-700 mb-1.5">Email address</label>
                        <input
                            id="email" type="email" name="email" value="{{ old('email', $user->email) }}"
                            required autocomplete="username"
                            class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->has('email') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <p class="mt-2 text-xs text-gray-400">
                                Your email is unverified.
                                <button form="send-verification" class="text-slate-900 font-medium hover:underline">Resend verification</button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-1 text-xs text-emerald-600">A new verification link has been sent.</p>
                            @endif
                        @endif
                    </div>

                    <div class="pt-1 flex items-center justify-between">
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors">
                            Save changes
                        </button>
                    </div>
                </form>

                <form id="send-verification" method="POST" action="{{ route('verification.send') }}" class="hidden">
                    @csrf
                </form>
            </div>
        </div>

        <div class="bg-white border border-gray-100 rounded-xl shadow-sm mb-6">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-sm font-semibold text-slate-900">Update Password</h2>
                <p class="text-xs text-gray-400 mt-0.5">Use a long, random password to keep your account secure.</p>
            </div>
            <div class="px-6 py-5">

                @if (session('status') === 'password-updated')
                    <div class="mb-5 flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg px-4 py-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Password updated successfully.
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-xs font-medium text-gray-700 mb-1.5">Current password</label>
                        <input
                            id="current_password" type="password" name="current_password"
                            autocomplete="current-password" placeholder="••••••••"
                            class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->updatePassword->has('current_password') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                        >
                        @if ($errors->updatePassword->has('current_password'))
                            <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-medium text-gray-700 mb-1.5">New password</label>
                        <input
                            id="password" type="password" name="password"
                            autocomplete="new-password" placeholder="••••••••"
                            class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->updatePassword->has('password') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                        >
                        @if ($errors->updatePassword->has('password'))
                            <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('password') }}</p>
                        @endif
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-medium text-gray-700 mb-1.5">Confirm new password</label>
                        <input
                            id="password_confirmation" type="password" name="password_confirmation"
                            autocomplete="new-password" placeholder="••••••••"
                            class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                        >
                        @if ($errors->updatePassword->has('password_confirmation'))
                            <p class="mt-1.5 text-xs text-red-500">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                        @endif
                    </div>

                    <div class="pt-1">
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors">
                            Update password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white border border-red-100 rounded-xl shadow-sm">
            <div class="px-6 py-5 border-b border-red-100">
                <h2 class="text-sm font-semibold text-red-600">Delete Account</h2>
                <p class="text-xs text-gray-400 mt-0.5">Permanently delete your account and all associated data. This cannot be undone.</p>
            </div>
            <div class="px-6 py-5">
                <div id="delete-section-toggle">
                    <button
                        type="button"
                        onclick="document.getElementById('delete-section-toggle').classList.add('hidden');document.getElementById('delete-section-form').classList.remove('hidden');"
                        class="px-4 py-2 text-sm font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors"
                    >
                        Delete my account
                    </button>
                </div>

                <div id="delete-section-form" class="hidden">
                    <p class="text-sm text-gray-500 mb-4">Please enter your password to confirm you want to permanently delete your account.</p>

                    <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                        @csrf
                        @method('delete')

                        <div>
                            <label for="delete_password" class="block text-xs font-medium text-gray-700 mb-1.5">Password</label>
                            <input
                                id="delete_password" type="password" name="password"
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full px-3 py-2 text-sm border rounded-lg bg-white placeholder-gray-300 text-gray-900 outline-none transition-colors duration-150 {{ $errors->userDeletion->has('password') ? 'border-red-300 focus:border-red-400 focus:ring-1 focus:ring-red-300' : 'border-gray-200 focus:border-gray-400 focus:ring-1 focus:ring-gray-900/10' }}"
                            >
                            @if ($errors->userDeletion->has('password'))
                                <p class="mt-1.5 text-xs text-red-500">{{ $errors->userDeletion->first('password') }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-3 pt-1">
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                Confirm deletion
                            </button>
                            <button
                                type="button"
                                onclick="document.getElementById('delete-section-toggle').classList.remove('hidden');document.getElementById('delete-section-form').classList.add('hidden');"
                                class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-slate-900 transition-colors"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@if ($errors->userDeletion->isNotEmpty())
<script>
    document.getElementById('delete-section-toggle').classList.add('hidden');
    document.getElementById('delete-section-form').classList.remove('hidden');
</script>
@endif

@endsection
