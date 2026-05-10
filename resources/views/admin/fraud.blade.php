@extends('layouts.app')
@section('title', 'Fraud Detection')
@section('header', '🛡️ Fraud Detection')
@section('content')
<div class="space-y-4">
    @forelse($flags as $flag)
    <div class="glass rounded-xl p-5 {{ $flag->severity==='critical'?'border border-red-500/30':'' }}">
        <div class="flex items-start gap-4">
            <span class="text-2xl">🚨</span>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <p class="font-semibold">{{ $flag->user->name }}</p>
                    <span class="px-2 py-0.5 rounded-full text-xs {{ $flag->severity==='critical'?'bg-red-500/20 text-red-300':($flag->severity==='high'?'bg-amber-500/20 text-amber-300':'bg-gray-500/20 text-gray-300') }}">{{ ucfirst($flag->severity) }}</span>
                </div>
                <p class="text-sm text-gray-300">{{ $flag->reason }}</p>
                @if($flag->details)<p class="text-sm text-gray-400 mt-1">{{ $flag->details }}</p>@endif
            </div>
            @if(in_array($flag->status, ['pending','investigating']))
            <form method="POST" action="{{ route('admin.fraud.review', $flag) }}" class="flex gap-1">@csrf @method('PUT')
                <button name="status" value="confirmed" class="px-2 py-1 rounded bg-red-500/20 text-red-300 text-xs">Confirm</button>
                <button name="status" value="dismissed" class="px-2 py-1 rounded bg-gray-500/20 text-gray-300 text-xs">Dismiss</button>
            </form>
            @else
            <span class="px-2 py-1 rounded-full text-xs {{ $flag->status==='confirmed'?'bg-red-500/20 text-red-300':'bg-gray-500/20 text-gray-300' }}">{{ ucfirst($flag->status) }}</span>
            @endif
        </div>
    </div>
    @empty<p class="text-center text-gray-500 py-12">No fraud flags 🎉</p>@endforelse
</div>
<div class="mt-6">{{ $flags->links() }}</div>
@endsection
