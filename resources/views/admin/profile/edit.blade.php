<x-admin-layout>
    <x-slot name="title">Profile Settings</x-slot>

    @php
        /** @var \App\Models\User $user */
        $user ??= Auth::user();
    @endphp

    @include('admin.profile.partials.script')

    <div class="max-w-4xl mx-auto space-y-6 sm:space-y-8">

        {{-- Page Heading & Breadcrumb --}}
        @include('admin.profile.partials.header')

        {{-- 1. PROFILE OVERVIEW (Avatar & Display Name) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6 lg:p-8">
            <div class="border-b border-gray-100 pb-4 mb-6">
                <h2 class="text-lg font-bold text-gray-900 tracking-tight">Profile Overview</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Your public administrator identity, avatar photo, and display name.
                </p>
            </div>

            @include('admin.profile.partials.avatar')
            @include('admin.profile.partials.name')
        </div>

        {{-- 2. PROFILE PREFERENCES & SECURITY (Email & Password) --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 sm:p-6 lg:p-8">
            <div class="border-b border-gray-100 pb-4 mb-6">
                <h2 class="text-lg font-bold text-gray-900 tracking-tight">Profile Preferences</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-0.5">
                    Manage your administrator contact email address and account security password.
                </p>
            </div>

            @include('admin.profile.partials.email')
            @include('admin.profile.partials.password')
        </div>

    </div>
</x-admin-layout>
