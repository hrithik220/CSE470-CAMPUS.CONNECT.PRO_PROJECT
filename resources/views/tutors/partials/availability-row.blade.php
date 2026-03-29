@php
    $slot = $slot ?? ['day_of_week' => 1, 'start_time' => '', 'end_time' => ''];
@endphp

<div class="availability-row grid grid-cols-1 gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 md:grid-cols-4">
    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Day</label>
        <select
            name="availability[{{ $index }}][day_of_week]"
            class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
            @foreach (\App\Models\TutorAvailabilitySlot::DAYS as $dayValue => $dayLabel)
                <option value="{{ $dayValue }}" @selected((int) ($slot['day_of_week'] ?? 1) === $dayValue)>
                    {{ $dayLabel }}
                </option>
            @endforeach
        </select>
        @error("availability.$index.day_of_week")
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">Start time</label>
        <input
            type="time"
            name="availability[{{ $index }}][start_time]"
            value="{{ $slot['start_time'] ?? '' }}"
            class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error("availability.$index.start_time")
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="mb-1 block text-sm font-medium text-slate-700">End time</label>
        <input
            type="time"
            name="availability[{{ $index }}][end_time]"
            value="{{ $slot['end_time'] ?? '' }}"
            class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
        >
        @error("availability.$index.end_time")
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-end">
        <button
            type="button"
            class="remove-availability inline-flex w-full items-center justify-center rounded-lg border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50"
        >
            Remove slot
        </button>
    </div>
</div>
