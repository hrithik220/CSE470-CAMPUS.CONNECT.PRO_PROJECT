@extends('layouts.app')
@section('title', 'Fraud Reports — Admin')
@section('page_title', 'Fraud Reports')

@section('content')
<div class="fade-in space-y-4">
    <div class="flex flex-wrap gap-3 items-center">
        <form method="GET" class="flex gap-3 flex-1">
            <select name="status" class="px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm glass">
                <option value="">All Status</option>
                @foreach(['pending','investigating','resolved','dismissed'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="type" class="px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm glass">
                <option value="">All Types</option>
                @foreach($types as $k => $v)
                <option value="{{ $k }}" {{ request('type') === $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
            <button class="px-4 py-2 bg-brand-600 text-white rounded-lg text-sm">Filter</button>
        </form>
        <form method="POST" action="{{ route('admin.fraud-scan') }}">@csrf
            <button class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 rounded-lg text-sm transition flex items-center gap-2">
                <i data-lucide="search" class="w-4 h-4"></i> Run Fraud Scan
            </button>
        </form>
    </div>

    @forelse($reports as $report)
    <div class="glass rounded-xl p-5">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl {{ $report->status === 'pending' ? 'bg-yellow-500/10 text-yellow-400' : ($report->status === 'resolved' ? 'bg-green-500/10 text-green-400' : 'bg-red-500/10 text-red-400') }} flex items-center justify-center flex-shrink-0">
                <i data-lucide="{{ $report->status === 'pending' ? 'alert-triangle' : ($report->status === 'resolved' ? 'check-circle' : 'search') }}" class="w-6 h-6"></i>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1 flex-wrap">
                    <span class="px-2 py-0.5 text-[10px] rounded-full bg-brand-500/20 text-brand-400">{{ $report->type_label }}</span>
                    <span class="px-2 py-0.5 text-[10px] rounded-full {{ $report->status === 'pending' ? 'bg-yellow-500/20 text-yellow-400' : ($report->status === 'resolved' ? 'bg-green-500/20 text-green-400' : 'bg-gray-500/20 text-gray-400') }}">{{ ucfirst($report->status) }}</span>
                    <span class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</span>
                </div>
                <p class="text-sm mb-2">{{ $report->reason }}</p>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <span>Reported: <strong class="text-gray-300">{{ $report->reportedUser->name ?? '—' }}</strong></span>
                    @if($report->reporter)<span>By: {{ $report->reporter->name }}</span>@else<span>By: System</span>@endif
                </div>
                @if($report->admin_notes)
                <div class="mt-2 p-2 rounded bg-white/5 text-xs text-gray-400"><strong>Admin Notes:</strong> {{ $report->admin_notes }}</div>
                @endif
            </div>
        </div>

        @if($report->status !== 'resolved' && $report->status !== 'dismissed')
        <form method="POST" action="{{ route('admin.fraud-reports.resolve', $report) }}" class="mt-4 p-4 rounded-lg bg-white/5 space-y-3">
            @csrf
            <textarea name="admin_notes" required placeholder="Admin notes..." rows="2"
                class="w-full px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-white text-sm focus:border-brand-500 outline-none resize-none"></textarea>
            <div class="flex flex-wrap gap-3 items-center">
                <label class="flex items-center gap-2 text-xs text-gray-400"><input type="checkbox" name="suspend_user" value="1" class="rounded bg-white/5 border-white/10 text-red-500"> Suspend User</label>
                <div class="flex items-center gap-2">
                    <label class="text-xs text-gray-400">Karma Penalty:</label>
                    <input type="number" name="karma_penalty" value="0" min="0" max="1000" class="w-20 px-2 py-1 rounded bg-white/5 border border-white/10 text-white text-sm">
                </div>
                <div class="flex gap-2 ml-auto">
                    <button type="submit" name="status" value="resolved" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg text-xs transition">Resolve</button>
                    <button type="submit" name="status" value="dismissed" class="px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white rounded-lg text-xs transition">Dismiss</button>
                </div>
            </div>
        </form>
        @endif
    </div>
    @empty
    <div class="text-center py-16">
        <div class="w-16 h-16 bg-green-500/10 rounded-2xl flex items-center justify-center mx-auto mb-4 text-green-400">
            <i data-lucide="party-popper" class="w-8 h-8"></i>
        </div>
        <p class="text-gray-400">No fraud reports found.</p>
    </div>
    @endforelse
    {{ $reports->links() }}
</div>
@endsection
