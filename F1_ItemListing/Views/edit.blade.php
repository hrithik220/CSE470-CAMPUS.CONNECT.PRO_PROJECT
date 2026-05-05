@extends('layouts.app')
@section('title', 'Edit Item — Campus Connect Pro')
@section('page_title', 'Edit Item')

@section('content')
<div class="max-w-2xl mx-auto fade-in">
    <div class="glass rounded-xl p-6">
        <form method="POST" action="{{ route('marketplace.update', $item) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Title</label>
                <input type="text" name="title" value="{{ old('title', $item->title) }}" required
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm">
                @error('title')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Description</label>
                <textarea name="description" rows="4" required
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm resize-none">{{ old('description', $item->description) }}</textarea>
                @error('description')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Price (৳)</label>
                    <input type="number" name="price" value="{{ old('price', $item->price) }}" step="0.01" min="0" required
                        class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-brand-500 outline-none transition text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Category</label>
                    <select name="category" required class="w-full px-3 py-3 rounded-xl bg-white/5 border border-white/10 text-gray-300 text-sm">
                        @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $item->category) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Condition</label>
                    <select name="condition" required class="w-full px-3 py-3 rounded-xl bg-white/5 border border-white/10 text-gray-300 text-sm">
                        @foreach($conditions as $key => $label)
                        <option value="{{ $key }}" {{ old('condition', $item->condition) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @if($item->images->count())
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Current Images</label>
                <div class="flex flex-wrap gap-3">
                    @foreach($item->images as $img)
                    <div class="relative group">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-20 h-20 object-cover rounded-lg border border-white/10">
                        <label class="absolute inset-0 bg-red-500/50 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center cursor-pointer transition">
                            <input type="checkbox" name="remove_images[]" value="{{ $img->id }}" class="hidden">
                            <span class="text-white text-xs font-bold">Remove</span>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Add More Images</label>
                <input type="file" name="images[]" multiple accept="image/*"
                    class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-brand-600 file:text-white file:font-medium hover:file:bg-brand-500">
            </div>
            <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition flex items-center justify-center gap-2">
                <i data-lucide="save" class="w-5 h-5"></i> Update Listing
            </button>
        </form>
    </div>
</div>
@endsection
