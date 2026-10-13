<x-guest-layout>
    <div class="flex justify-center mb-4">
        <div class="p-3 bg-indigo-100 rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-600" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z" />
                <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0" />
                <path d="M8 11v-4a4 4 0 1 1 8 0v4" />
            </svg>
        </div>
    </div>

    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-900">Forgot Password?</h2>
        <div class="mt-2 text-sm text-gray-600">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link.') }}
        </div>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email Address')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                required autofocus placeholder="Enter your registered email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
                Back to Login
            </a>
        </div>
    </form>
</x-guest-layout>
