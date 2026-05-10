@extends('layouts.app')
@section('title', 'Edit Profile')
@section('page_title', 'Edit Profile')

@section('content')
<div class="max-w-lg mx-auto fade-in space-y-6">
    <div class="glass rounded-xl p-6">
        <h3 class="font-semibold text-lg mb-4">Profile Information</h3>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div class="flex items-center gap-4">
                <img src="{{ $user->avatar_url }}" class="w-16 h-16 rounded-xl ring-2 ring-brand-500/20" alt="">
                <div>
                    <input type="file" name="avatar" accept="image/*" class="text-sm text-gray-400 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-brand-600 file:text-white file:text-xs hover:file:bg-brand-500">
                    <p class="text-[10px] text-gray-500 mt-1">Max 2MB</p>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm">
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Bio</label>
                <textarea name="bio" rows="3" maxlength="500"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm resize-none"
                    placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm" placeholder="Optional">
            </div>
            <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition">Save Changes</button>
        </form>
    </div>

    <div class="glass rounded-xl p-6">
        <h3 class="font-semibold text-lg mb-4">Change Password</h3>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Current Password</label>
                <input type="password" name="current_password" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm">
                @error('current_password')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">New Password</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Confirm New Password</label>
                <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm">
            </div>
            <button type="submit" class="w-full py-3 bg-white/5 hover:bg-white/10 border border-white/10 text-white font-medium rounded-xl transition">Update Password</button>
        </form>
    </div>
</div>
@endsection
