<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    public function rules(): array
    {
        return [
            'province_id'       => 'required|exists:provinces,id',
            'place_name'        => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'thumbnail'         => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'rating'            => 'nullable|numeric|min:0|max:5',
            'is_recommended'    => 'boolean',
            'place_description' => 'required|string',
            'place_history'     => 'nullable|string',
            'place_information' => 'nullable|string',
            'highlights'        => 'nullable|array',
            'highlights.*'      => 'string|max:255',
            'gallery_images'    => 'nullable|array|max:10',
            'gallery_images.*'  => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ];
    }

    /**
     * Prepare the data for validation — ensure is_recommended is a boolean.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_recommended' => $this->has('is_recommended'),
        ]);
    }

    public function messages(): array
    {
        return [
            'thumbnail.required'       => 'A cover image is required.',
            'thumbnail.max'            => 'The cover image must not exceed 10 MB.',
            'gallery_images.max'       => 'You may upload up to 10 gallery images.',
            'gallery_images.*.max'     => 'Each gallery image must not exceed 10 MB.',
            'place_description.required' => 'A full description is required.',
        ];
    }
}
