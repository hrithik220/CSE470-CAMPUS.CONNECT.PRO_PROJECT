@extends('layouts.app')
@section('title', 'Manage Users — Admin')
@section('page_title', 'Manage Users')

@section('content')
<div class="fade-in space-y-4">
    <form method="GET" class="glass rounded-xl p-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
            class="flex-1 min-w-[200px] px-4 py-2 rounded-lg bg-white/5 border border-white/10 text-white placeholder-gray-500 text-sm focus:border-brand-500 outline-none">
        <select name="role" class="px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm">
            <option value="">All Roles</option>
            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
        </select>
        <select name="status" class="px-3 py-2 rounded-lg bg-white/5 border border-white/10 text-gray-300 text-sm">
            <option value="">All Status</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        <button type="submit" class="px-5 py-2 bg-brand-600 text-white rounded-lg text-sm">Filter</button>
    </form>

    <div class="glass rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b border-white/5 text-left text-xs text-gray-500 uppercase">
                    <th class="p-4">User</th><th class="p-4">Role</th><th class="p-4">Karma</th><th class="p-4">Items</th><th class="p-4">Sales</th><th class="p-4">Status</th><th class="p-4">Actions</th>
                </tr></thead>
                <tbody>
                @foreach($users as $user)
                <tr class="border-b border-white/5 hover:bg-white/5 transition">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
                            <div><p class="font-medium">{{ $user->name }}</p><p class="text-xs text-gray-500">{{ $user->email }}</p></div>
                        </div>
                    </td>
                    <td class="p-4"><span class="px-2 py-0.5 text-[10px] rounded-full {{ $user->role === 'admin' ? 'bg-purple-500/20 text-purple-400' : 'bg-blue-500/20 text-blue-400' }}">{{ ucfirst($user->role) }}</span></td>
                    <td class="p-4 text-brand-400 font-semibold">{{ $user->karma_points }}</td>
                    <td class="p-4">{{ $user->items_count }}</td>
                    <td class="p-4">{{ $user->sales_count }}</td>
                    <td class="p-4">
                        @if($user->is_suspended)
                        <span class="px-2 py-0.5 text-[10px] rounded-full bg-red-500/20 text-red-400">Suspended</span>
                        @else
                        <span class="px-2 py-0.5 text-[10px] rounded-full bg-green-500/20 text-green-400">Active</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($user->is_suspended)
                        <form method="POST" action="{{ route('admin.users.unsuspend', $user) }}" class="inline">@csrf
                            <button class="text-xs text-green-400 hover:text-green-300">Unsuspend</button>
                        </form>
                        @else
                        <form method="POST" action="{{ route('admin.users.suspend', $user) }}" class="inline" onsubmit="return confirm('Suspend this user?')">@csrf
                            <button class="text-xs text-red-400 hover:text-red-300">Suspend</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $users->links() }}
</div>
@endsection
