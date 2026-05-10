@extends('layouts.guest')
@section('title', 'Login — Campus Connect Pro')

@section('content')
<div class="glass rounded-2xl p-8">
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4 shadow-lg shadow-brand-500/25">C</div>
        <h1 class="text-2xl font-bold gradient-text">Welcome Back</h1>
        <p class="text-gray-400 text-sm mt-1">Sign in to your account</p>
    </div>

    @if(session('status'))
    <div class="mb-4 p-3 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 text-sm">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm"
                placeholder="you@university.edu">
            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm"
                placeholder="••••••••">
            @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2 text-sm text-gray-400">
                <input type="checkbox" name="remember" class="rounded bg-white/5 border-white/10 text-brand-500 focus:ring-brand-500">
                Remember me
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-brand-400 hover:text-brand-300 transition">Forgot password?</a>
        </div>
        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition shadow-lg shadow-brand-600/25">Sign In</button>
    </form>
    <p class="text-center text-gray-500 text-sm mt-6">Don't have an account? <a href="{{ route('register') }}" class="text-brand-400 hover:text-brand-300 font-medium transition">Sign up</a></p>
</div>
@endsection
