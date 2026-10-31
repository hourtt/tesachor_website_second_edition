<?php

namespace App\Http\Requests\Admin;

use App\Models\Province;
use Illuminate\Foundation\Http\FormRequest;

class StoreProvinceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $trimmed = trim((string) $value);
                    if ($trimmed === '') {
                        return;
                    }
                    if (Province::whereRaw('LOWER(name) = LOWER(?)', [$trimmed])->exists()) {
                        $fail('This province already exists.');
                    }
                },
            ],
            'name_in_khmer' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\x{1780}-\x{17B3}\x{17B6}-\x{17D3}\s]+$/u',
            ],
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The province name is required.',
            'name_in_khmer.required' => 'The Khmer name is required.',
            'name_in_khmer.regex' => 'The Khmer name must contain only Khmer letters, marks, and spaces.',
            'thumbnail.required' => 'A cover image is required for the province.',
            'thumbnail.max' => 'The cover image must not exceed 4 MB.',
        ];
    }
}
