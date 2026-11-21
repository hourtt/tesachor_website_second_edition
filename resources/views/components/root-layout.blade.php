@props([
    'title' => null,
    'description' => null,
    'image' => null,
    'type' => 'website',
    'htmlClass' => '',
    'bodyClass' => 'font-sans antialiased',
])

@php
    $defaultTitle = config('app.name', 'Tesachor') . ' — Cambodia Tourism & Travel Guide';
    $pageTitle = $title ? $title : $defaultTitle;
    $metaDescription = $description ?: 'Explore Cambodia\'s most breathtaking destinations, historical provinces, ancient temples, and natural wonders with Tesachor.';
    $ogImage = $image ?: asset('images/tesachor-logo.png');
    $canonicalUrl = url()->current();
    $locale = app()->getLocale() === 'km' ? 'km_KH' : 'en_US';
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $htmlClass }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- Favicons --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}">

    {{-- Open Graph / Social Sharing --}}
    <meta property="og:site_name" content="Tesachor">
    <meta property="og:type" content="{{ $type }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:locale" content="{{ $locale }}">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    <meta name="twitter:image" content="{{ $ogImage }}">

    {{-- Google Fonts loaded globally in this shared document root --}}
    <x-google-fonts />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{ $head ?? '' }}
</head>

<body {{ $attributes->merge(['class' => $bodyClass]) }}>
    {{ $slot }}
</body>

</html>
