@extends('layouts.guest')
@section('title', 'Register — Campus Connect Pro')

@section('content')
<div class="glass rounded-2xl p-8">
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4 shadow-lg shadow-brand-500/25">C</div>
        <h1 class="text-2xl font-bold gradient-text">Join Campus Connect</h1>
        <p class="text-gray-400 text-sm mt-1">Create your student account</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm"
                placeholder="John Doe">
            @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">University Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm"
                placeholder="you@university.edu">
            @error('email')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="university_id" class="block text-sm font-medium text-gray-300 mb-1.5">University ID <span class="text-gray-500">(optional)</span></label>
            <input id="university_id" type="text" name="university_id" value="{{ old('university_id') }}"
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm"
                placeholder="STU12345">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm"
                placeholder="Min 8 characters">
            @error('password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 outline-none transition text-sm"
                placeholder="••••••••">
        </div>
        <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition shadow-lg shadow-brand-600/25 mt-2">Create Account</button>
    </form>
    <p class="text-center text-gray-500 text-sm mt-6">Already have an account? <a href="{{ route('login') }}" class="text-brand-400 hover:text-brand-300 font-medium transition">Sign in</a></p>
</div>
@endsection
