@extends('layouts.guest')
@section('title', 'Verify Email - Campus Connect Pro')

@section('content')
<div class="glass rounded-2xl p-8 shadow-2xl text-center">
    <div class="w-20 h-20 rounded-full bg-indigo-500/20 flex items-center justify-center text-4xl mx-auto mb-6">📧</div>
    <h1 class="text-2xl font-bold mb-2">Verify Your Email</h1>
    <p class="text-gray-400 mb-6">We've sent a verification link to your email address. Please check your inbox and click the link to activate your account.</p>

    @if(session('message'))
    <div class="mb-4 p-3 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-sm">
        {{ session('message') }}
    </div>
    @endif

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <button type="submit" class="btn-primary text-white px-6 py-3 rounded-xl font-medium transition">
            Resend Verification Email
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button type="submit" class="text-gray-400 hover:text-white text-sm">← Back to Login</button>
    </form>
</div>
@endsection
