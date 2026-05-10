<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'price' => 'required|numeric|min:0|max:99999.99',
            'category' => 'required|in:textbooks,electronics,furniture,clothing,sports,supplies,tickets,other',
            'condition' => 'required|in:new,used,fair',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'Please upload at least one image.',
            'images.*.max' => 'Each image must be less than 2MB.',
            'price.max' => 'Price cannot exceed $99,999.99.',
        ];
    }
}
