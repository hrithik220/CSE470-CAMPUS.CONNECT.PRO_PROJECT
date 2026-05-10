@extends('layouts.app')
@section('title', 'Leave a Review')
@section('page_title', 'Leave a Review')

@section('content')
<div class="max-w-lg mx-auto fade-in">
    <div class="glass rounded-xl p-6">
        <div class="text-center mb-6">
            <p class="text-4xl mb-2">⭐</p>
            <h2 class="text-xl font-bold">Rate your experience</h2>
            <p class="text-sm text-gray-400 mt-1">Review for: {{ $transaction->item->title }}</p>
            <p class="text-xs text-gray-500">Seller: {{ $transaction->seller->name }}</p>
        </div>
        <form method="POST" action="{{ route('review.store', $transaction) }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-3 text-center">Rating</label>
                <div class="flex justify-center gap-2" id="starRating">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" onclick="setRating({{ $i }})" class="star text-4xl text-gray-600 hover:text-yellow-400 transition cursor-pointer">☆</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating', 5) }}">
                @error('rating')<p class="text-red-400 text-xs mt-1 text-center">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">Comment (optional)</label>
                <textarea name="comment" rows="3" maxlength="1000"
                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-gray-500 focus:border-brand-500 outline-none transition text-sm resize-none"
                    placeholder="Share your experience...">{{ old('comment') }}</textarea>
            </div>
            <button type="submit" class="w-full py-3 bg-brand-600 hover:bg-brand-500 text-white font-semibold rounded-xl transition">Submit Review</button>
        </form>
    </div>
</div>
@push('scripts')
<script>
function setRating(r) {
    document.getElementById('ratingInput').value = r;
    document.querySelectorAll('.star').forEach((s, i) => {
        s.textContent = i < r ? '★' : '☆';
        s.classList.toggle('text-yellow-400', i < r);
        s.classList.toggle('text-gray-600', i >= r);
    });
}
setRating(5);
</script>
@endpush
@endsection
