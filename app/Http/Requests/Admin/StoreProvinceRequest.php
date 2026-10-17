<?php

namespace App\Http\Requests\Admin;

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
            'name' => 'required|string|max:255|unique:provinces,name',
            'name_in_khmer' => 'required|string|max:255',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'thumbnail.required' => 'A cover image is required for the province.',
            'thumbnail.max' => 'The cover image must not exceed 4 MB.',
            'name.unique' => 'A province with this name already exists.',
        ];
    }
}
