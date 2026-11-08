<?php

namespace App\Http\Requests\Admin;

use App\Models\Destination;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole('admin');
    }

    protected function prepareForValidation(): void
    {
        $khmerName = $this->input('place_name_in_khmer');
        $this->merge([
            'is_recommended' => $this->has('is_recommended'),
            'place_name_in_khmer' => \is_string($khmerName)
                ? (trim(str_replace("\u{200B}", '', $khmerName)) ?: null)
                : null,
        ]);
    }

    public function rules(): array
    {
        $param = (string) $this->route('destination');
        $destination = Destination::where(function ($query) use ($param) {
            if (is_numeric($param)) {
                $query->where('id', (int) $param)->orWhere('slug', $param);
            } else {
                $query->where('slug', $param);
            }
        })->first();
        $destinationId = $destination?->id;

        return [
            'province_id' => 'required|exists:provinces,id',
            'category_id' => 'required|exists:categories,id',
            'place_name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($destinationId) {
                    $exists = Destination::whereRaw('LOWER(place_name) = LOWER(?)', [$value])
                        ->when($destinationId, fn($q) => $q->where('id', '!=', $destinationId))
                        ->exists();
                    if ($exists) {
                        $fail('This destination already exists.');
                    }
                },
            ],
            'short_description' => 'required|string|max:500',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'rating' => 'nullable|numeric|min:0|max:5',
            'is_recommended' => 'boolean',
            'place_description' => 'required|string',
            'place_history' => 'nullable|string',
            'place_information' => 'nullable|string',
            'highlights' => 'nullable|array',
            'highlights.*' => 'string|max:255',
            'gallery_images' => 'nullable|array|max:10',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'place_name_in_khmer' => [
                'nullable',
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($destinationId) {
                    $exists = Destination::where('place_name_in_khmer', $value)
                        ->when($destinationId, fn($q) => $q->where('id', '!=', $destinationId))
                        ->exists();
                    if ($exists) {
                        $fail('This destination already exists.');

                    }
                },
            ],
            'short_description_in_khmer' => ['nullable', 'string', 'max:500'],
            'place_description_in_khmer' => ['nullable', 'string'],
            'place_history_in_khmer' => ['nullable', 'string'],
            'place_information_in_khmer' => ['nullable', 'string'],
            'highlights_in_khmer' => ['nullable', 'array'],
            'highlights_in_khmer.*' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'place_name.required' => 'The destination name is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'province_id.required' => 'Please select a province.',
            'province_id.exists' => 'The selected province is invalid.',
            'thumbnail.max' => 'The cover image must not exceed 10 MB.',
            'gallery_images.max' => 'You may upload up to 10 gallery images.',
            'gallery_images.*.max' => 'Each gallery image must not exceed 10 MB.',
            'place_description.required' => 'A full description is required.',
        ];
    }
}
