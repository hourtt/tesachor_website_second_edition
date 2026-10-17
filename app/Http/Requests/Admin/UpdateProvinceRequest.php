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
        // We need the province ID to exclude it from unique check.
        // The route parameter could be slug, so we resolve via the service layer —
        // or we accept it via a hidden input. Simplest: find by slug from route.
        $provinceId = optional(Province::where('slug', $this->route('province'))->first())->id;

        return [
            'name' => 'required|string|max:255|unique:provinces,name,'.$provinceId,
            'name_in_khmer' => 'required|string|max:255',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'thumbnail.max' => 'The cover image must not exceed 4 MB.',
            'name.unique' => 'A province with this name already exists.',
        ];
    }
}
