@extends('layouts.app')
@section('title', 'Leave a Review')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Leave a Review</h1>

    <div class="bg-white rounded-lg shadow p-6">

        {{-- Item Info --}}
        <div class="flex items-center gap-4 mb-6 pb-6 border-b">
            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 text-sm">
                @if($item->photos && count($item->photos) > 0)
                    <img src="{{ Storage::url($item->photos[0]) }}" class="w-full h-full object-cover rounded-lg">
                @else
                    No Photo
                @endif
            </div>
            <div>
                <p class="font-bold text-gray-800">{{ $item->title }}</p>
                <p class="text-sm text-gray-500">Seller: {{ $seller->name }}</p>
                <p class="text-blue-600 font-semibold">৳{{ number_format($item->price, 2) }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('marketplace.review.store', $item) }}">
            @csrf

            {{-- Star Rating --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">Rating *</label>
                <div class="flex gap-2" id="star-container">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                                onclick="setRating({{ $i }})"
                                class="star-btn text-4xl text-gray-300 hover:text-yellow-400 transition"
                                data-value="{{ $i }}">★</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" value="">
                @error('rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Comment --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Comment (optional)</label>
                <textarea name="comment" rows="4"
                          class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
                          placeholder="Share your experience with this seller...">{{ old('comment') }}</textarea>
                @error('comment') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                Submit Review
            </button>
        </form>
    </div>
</div>

<script>
function setRating(value) {
    document.getElementById('rating-input').value = value;
    document.querySelectorAll('.star-btn').forEach(btn => {
        if (parseInt(btn.dataset.value) <= value) {
            btn.classList.remove('text-gray-300');
            btn.classList.add('text-yellow-400');
        } else {
            btn.classList.remove('text-yellow-400');
            btn.classList.add('text-gray-300');
        }
    });
}
</script>
@endsection
