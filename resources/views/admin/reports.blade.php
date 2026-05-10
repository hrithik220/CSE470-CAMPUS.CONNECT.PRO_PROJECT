@extends('layouts.app')
@section('title', 'Reports')
@section('header', '🛡️ Content Reports')
@section('content')
<div class="space-y-4">
    @forelse($reports as $report)
    <div class="glass rounded-xl p-5">
        <div class="flex items-start gap-4">
            <span class="text-2xl">⚠️</span>
            <div class="flex-1">
                <p class="font-semibold">{{ $report->reason }}</p>
                <p class="text-sm text-gray-400 mt-1">Reported by {{ $report->reporter->name }} · {{ $report->created_at->diffForHumans() }}</p>
                @if($report->details)<p class="text-sm text-gray-300 mt-2 p-3 rounded-lg bg-white/5">{{ $report->details }}</p>@endif
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2 py-1 rounded-full text-xs {{ $report->status==='pending'?'bg-amber-500/20 text-amber-300':($report->status==='resolved'?'bg-emerald-500/20 text-emerald-300':'bg-gray-500/20 text-gray-300') }}">{{ ucfirst($report->status) }}</span>
                @if($report->status === 'pending')
                <form method="POST" action="{{ route('admin.reports.review', $report) }}" class="flex gap-1">@csrf @method('PUT')
                    <button name="status" value="resolved" class="px-2 py-1 rounded bg-emerald-500/20 text-emerald-300 text-xs">Resolve</button>
                    <button name="status" value="dismissed" class="px-2 py-1 rounded bg-gray-500/20 text-gray-300 text-xs">Dismiss</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty<p class="text-center text-gray-500 py-12">No reports 🎉</p>@endforelse
</div>
<div class="mt-6">{{ $reports->links() }}</div>
@endsection
