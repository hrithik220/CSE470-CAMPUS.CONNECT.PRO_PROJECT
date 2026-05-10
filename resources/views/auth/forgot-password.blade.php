@extends('layouts.guest')
@section('title', 'Forgot Password — Campus Connect Pro')
@section('content')
<div class="glass rounded-2xl p-8">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold gradient-text">Reset Password</h1>
        <p class="text-gray-400 text-sm mt-1">Enter your email to receive a reset link</p>
    </div>
    @if(session('status'))
    <div class="mb-4 p-3 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 text-sm">{{ session('status') }}</div>
    @endif
    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm"
                placeholder="you@university.edu">
            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition shadow-lg shadow-brand-600/25">Send Reset Link</button>
    </form>
    <p class="text-center text-gray-500 text-sm mt-6"><a href="{{ route('login') }}" class="text-brand-400 hover:text-brand-300 transition">← Back to login</a></p>
</div>
@endsection
