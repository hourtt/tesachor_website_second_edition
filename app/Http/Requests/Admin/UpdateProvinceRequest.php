<?php

namespace App\Http\Requests\Admin;

use App\Models\Province;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProvinceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        $param = (string) $this->route('province');
        $province = Province::where(function ($query) use ($param) {
            if (is_numeric($param)) {
                $query->where('id', (int) $param)->orWhere('slug', $param);
            } else {
                $query->where('slug', $param);
            }
        })->first();
        $provinceId = $province?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($provinceId) {
                    $trimmed = trim((string) $value);
                    if ($trimmed === '') {
                        return;
                    }
                    $query = Province::whereRaw('LOWER(name) = LOWER(?)', [$trimmed]);
                    if ($provinceId) {
                        $query->where('id', '!=', $provinceId);
                    }
                    if ($query->exists()) {
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
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The province name is required.',
            'name_in_khmer.required' => 'The Khmer name is required.',
            'name_in_khmer.regex' => 'The Khmer name must contain only Khmer letters, marks, and spaces.',
            'thumbnail.max' => 'The cover image must not exceed 4 MB.',
        ];
    }
}
