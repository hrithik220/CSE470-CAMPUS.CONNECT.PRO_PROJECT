@php
    $isEdit = isset($tutorProfile);
    $selectedSubjects = old('subjects', $isEdit ? $tutorProfile->subjects->pluck('id')->all() : []);
    $availabilityRows = old(
        'availability',
        $isEdit
            ? $tutorProfile->availabilitySlots->map(fn ($slot) => [
                'day_of_week' => $slot->day_of_week,
                'start_time' => substr((string) $slot->start_time, 0, 5),
                'end_time' => substr((string) $slot->end_time, 0, 5),
            ])->values()->all()
            : [
                ['day_of_week' => 1, 'start_time' => '10:00', 'end_time' => '12:00'],
            ]
    );
@endphp

@if ($errors->any())
    <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
        <p class="font-semibold">Please fix the following problems:</p>
        <ul class="mt-2 list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-8">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-900">Basic profile</h2>
        <p class="mt-1 text-sm text-slate-500">Show your expertise, rate, and academic background clearly.</p>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="headline" class="mb-1 block text-sm font-medium text-slate-700">Headline</label>
                <input
                    type="text"
                    id="headline"
                    name="headline"
                    maxlength="120"
                    value="{{ old('headline', $tutorProfile->headline ?? '') }}"
                    placeholder="Example: CSE tutor focused on algorithms and databases"
                    class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                @error('headline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subjects" class="mb-1 block text-sm font-medium text-slate-700">Subjects you teach</label>
                <select
                    id="subjects"
                    name="subjects[]"
                    multiple
                    class="min-h-[160px] w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}" @selected(in_array($subject->id, $selectedSubjects))>
                            {{ $subject->display_name }}
                        </option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-slate-500">Hold Ctrl / Command to select multiple subjects.</p>
                @error('subjects')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('subjects.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="bio" class="mb-1 block text-sm font-medium text-slate-700">Bio</label>
                <textarea
                    id="bio"
                    name="bio"
                    rows="6"
                    maxlength="1200"
                    placeholder="Describe your teaching style, experience, strengths, and who should contact you."
                    class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >{{ old('bio', $tutorProfile->bio ?? '') }}</textarea>
                @error('bio')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700">Tutoring rate</label>

                <input type="hidden" name="is_free" value="0">
                <label class="mb-3 inline-flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700">
                    <input
                        type="checkbox"
                        id="is_free"
                        name="is_free"
                        value="1"
                        @checked((bool) old('is_free', $tutorProfile->is_free ?? false))
                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                    >
                    This tutoring is free
                </label>

                <div>
                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        id="hourly_rate"
                        name="hourly_rate"
                        value="{{ old('hourly_rate', $tutorProfile->hourly_rate ?? '') }}"
                        placeholder="Hourly rate in BDT"
                        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                    <p class="mt-1 text-xs text-slate-500">Leave this empty only if the tutoring is free.</p>
                    @error('hourly_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">Availability calendar</h2>
                <p class="mt-1 text-sm text-slate-500">Add the weekly time slots when students can request you.</p>
            </div>

            <button
                type="button"
                id="addAvailabilityRow"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700"
            >
                Add availability slot
            </button>
        </div>

        @error('availability')
            <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <div id="availabilityRows" class="mt-6 space-y-4">
            @foreach ($availabilityRows as $index => $slot)
                @include('tutors.partials.availability-row', ['index' => $index, 'slot' => $slot])
            @endforeach
        </div>

        <template id="availabilityRowTemplate">
            @include('tutors.partials.availability-row', ['index' => '__INDEX__', 'slot' => ['day_of_week' => 1, 'start_time' => '', 'end_time' => '']])
        </template>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('tutors.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
            Cancel
        </a>
        <button type="submit" class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
            {{ $isEdit ? 'Update profile' : 'Create profile' }}
        </button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isFreeCheckbox = document.getElementById('is_free');
        const hourlyRateInput = document.getElementById('hourly_rate');
        const availabilityRows = document.getElementById('availabilityRows');
        const addAvailabilityButton = document.getElementById('addAvailabilityRow');
        const template = document.getElementById('availabilityRowTemplate').innerHTML;

        function toggleRateInput() {
            if (!hourlyRateInput || !isFreeCheckbox) {
                return;
            }

            hourlyRateInput.disabled = isFreeCheckbox.checked;
            if (isFreeCheckbox.checked) {
                hourlyRateInput.value = '';
                hourlyRateInput.classList.add('bg-slate-100');
            } else {
                hourlyRateInput.classList.remove('bg-slate-100');
            }
        }

        function bindRemoveButtons() {
            availabilityRows.querySelectorAll('.remove-availability').forEach(function (button) {
                button.onclick = function () {
                    const rows = availabilityRows.querySelectorAll('.availability-row');
                    if (rows.length === 1) {
                        alert('At least one availability slot is required.');
                        return;
                    }
                    button.closest('.availability-row').remove();
                };
            });
        }

        addAvailabilityButton.addEventListener('click', function () {
            const nextIndex = availabilityRows.querySelectorAll('.availability-row').length;
            availabilityRows.insertAdjacentHTML('beforeend', template.split('__INDEX__').join(nextIndex));
            bindRemoveButtons();
        });

        isFreeCheckbox?.addEventListener('change', toggleRateInput);
        toggleRateInput();
        bindRemoveButtons();
    });
</script>
