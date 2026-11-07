@props([
    'title' => null,
    'htmlClass' => '',
    'bodyClass' => 'font-sans antialiased',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $htmlClass }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?: config('app.name', 'Tesachor') }}</title>

    {{-- Google Fonts loaded globally in this shared document root --}}
    <x-google-fonts />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ $head ?? '' }}
</head>

<body {{ $attributes->merge(['class' => $bodyClass]) }}>
    {{ $slot }}
</body>

</html>

