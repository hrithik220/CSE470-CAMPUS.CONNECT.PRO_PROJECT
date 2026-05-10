@extends('layouts.guest')
@section('title', 'Reset Password — Campus Connect Pro')
@section('content')
<div class="glass rounded-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold gradient-text">Set New Password</h1>
    </div>
    <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $email) }}" required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm">
            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">New Password</label>
            <input id="password" type="password" name="password" required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm">
            @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm">
        </div>
        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition">Reset Password</button>
    </form>
</div>
@endsection
