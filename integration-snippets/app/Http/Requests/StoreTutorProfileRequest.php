<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTutorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'headline' => ['nullable', 'string', 'max:120'],
            'bio' => ['required', 'string', 'max:1200'],
            'is_free' => ['required', 'boolean'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['required', 'integer', 'exists:subjects,id'],
            'availability' => ['required', 'array', 'min:1'],
            'availability.*.day_of_week' => ['required', 'integer', 'between:0,6'],
            'availability.*.start_time' => ['required', 'date_format:H:i'],
            'availability.*.end_time' => ['required', 'date_format:H:i'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_free' => filter_var($this->input('is_free', false), FILTER_VALIDATE_BOOLEAN),
        ]);
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (! $this->boolean('is_free') && ! $this->filled('hourly_rate')) {
                $validator->errors()->add('hourly_rate', 'Hourly rate is required unless the tutor is marked as free.');
            }

            $seen = [];

            foreach ($this->input('availability', []) as $index => $slot) {
                $start = $slot['start_time'] ?? null;
                $end = $slot['end_time'] ?? null;
                $day = $slot['day_of_week'] ?? null;

                if ($start && $end && strtotime($end) <= strtotime($start)) {
                    $validator->errors()->add("availability.$index.end_time", 'End time must be later than start time.');
                }

                $signature = $day . '|' . $start . '|' . $end;
                if (isset($seen[$signature])) {
                    $validator->errors()->add("availability.$index.day_of_week", 'Duplicate availability slot found.');
                }
                $seen[$signature] = true;
            }
        });
    }

    public function messages(): array
    {
        return [
            'subjects.required' => 'Please select at least one subject expertise.',
            'availability.required' => 'Please add at least one availability slot.',
        ];
    }
}
