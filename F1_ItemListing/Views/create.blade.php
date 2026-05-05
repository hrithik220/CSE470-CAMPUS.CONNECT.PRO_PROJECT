@extends('layouts.app')
@section('title', 'Sell an Item')
@section('header', 'Sell an Item')
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass rounded-2xl p-8">
        <form method="POST" action="{{ route('marketplace.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div><label class="block text-sm font-medium text-gray-300 mb-2">Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-indigo-500 outline-none" placeholder="What are you selling?"></div>
            <div><label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                <textarea name="description" rows="4" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-indigo-500 outline-none resize-none" placeholder="Describe your item...">{{ old('description') }}</textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Price (৳)</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-indigo-500 outline-none"></div>
                <div><label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                    <select name="category" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-indigo-500 outline-none">
                        @foreach($categories as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                    </select></div>
            </div>
            <div><label class="block text-sm font-medium text-gray-300 mb-2">Condition</label>
                <select name="condition" required class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:border-indigo-500 outline-none">
                    @foreach($conditions as $k=>$v)<option value="{{ $k }}">{{ $v }}</option>@endforeach
                </select></div>
            <div><label class="block text-sm font-medium text-gray-300 mb-2">Images (up to 5)</label>
                <input type="file" name="images[]" multiple accept="image/*" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-indigo-500/20 file:text-indigo-300 hover:file:bg-indigo-500/30"></div>
            <button type="submit" class="w-full bg-brand-600 hover:bg-brand-500 text-white py-3 rounded-xl font-semibold transition flex items-center justify-center gap-2">
                <i data-lucide="shopping-cart" class="w-5 h-5"></i> Post Listing
            </button>
        </form>
    </div>
</div>
@endsection
