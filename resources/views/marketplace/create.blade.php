@extends('layouts.app')
@section('title', 'List an Item')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">List an Item for Sale</h1>

    <form method="POST" action="{{ route('marketplace.store') }}"
          enctype="multipart/form-data"
          class="bg-white rounded-lg shadow p-6 space-y-5">
        @csrf

        {{-- Title --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Item Title *</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                   placeholder="e.g. Data Structures Textbook">
            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="4"
                      class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                      placeholder="Describe the item...">{{ old('description') }}</textarea>
        </div>

        {{-- Price --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Price (৳) *</label>
            <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0"
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                   placeholder="e.g. 350">
            @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Condition & Category --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Condition *</label>
                <select name="condition_rating"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Select condition</option>
                    <option value="new"      {{ old('condition_rating')=='new' ? 'selected' : '' }}>New</option>
                    <option value="like_new" {{ old('condition_rating')=='like_new' ? 'selected' : '' }}>Like New</option>
                    <option value="good"     {{ old('condition_rating')=='good' ? 'selected' : '' }}>Good</option>
                    <option value="fair"     {{ old('condition_rating')=='fair' ? 'selected' : '' }}>Fair</option>
                    <option value="poor"     {{ old('condition_rating')=='poor' ? 'selected' : '' }}>Poor</option>
                </select>
                @error('condition_rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                <select name="category"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    <option value="">Select category</option>
                    <option value="books"       {{ old('category')=='books' ? 'selected' : '' }}>Books</option>
                    <option value="electronics" {{ old('category')=='electronics' ? 'selected' : '' }}>Electronics</option>
                    <option value="clothing"    {{ old('category')=='clothing' ? 'selected' : '' }}>Clothing</option>
                    <option value="furniture"   {{ old('category')=='furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="other"       {{ old('category')=='other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Photos --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Photos (max 5)</label>
            <input type="file" name="photos[]" multiple accept="image/*"
                   class="w-full border rounded px-3 py-2 text-sm text-gray-500">
            @error('photos.*') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded font-semibold hover:bg-blue-700 transition">
            List Item
        </button>
    </form>
</div>
@endsection
