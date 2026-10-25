@php
    $user ??= Auth::user();
@endphp

<section x-data="{
    photoName: null,
    photoPreview: null,
    updatePreview(event) {
        const file = event.target.files[0];
        if (file) {
            this.photoName = file.name;
            this.photoPreview = URL.createObjectURL(file);
        }
    },
    clearPreview() {
        this.photoPreview = null;
        this.photoName = null;
        if (this.$refs.photoInput) {
            this.$refs.photoInput.value = '';
        }
    }
}">
    <header>
        <h2 class="text-lg font-bold text-gray-900">
            {{ __('Profile Picture') }}
        </h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ __('Upload a custom avatar for your account. Image is stored securely on Supabase Storage.') }}
        </p>
    </header>

    <div class="mt-6 flex flex-col sm:flex-row items-start sm:items-center gap-6">
        {{-- Avatar Preview Container --}}
        <div class="relative group">
            {{-- Current Avatar or Fallback --}}
            <div x-show="!photoPreview" class="relative">
                {{-- avatar_url is an accessor defined in App\Models\User via getAvatarUrlAttribute() --}}
                @if ($user->avatar_url)
                    <img src="{{ $user->avatar_url }}"
                         alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-full object-cover border-2 border-gray-100 shadow-md">
                @else
                    <div class="w-24 h-24 rounded-full bg-blue-100 text-white-600 font-bold text-3xl flex items-center justify-center border-2 border-red-200/60 shadow-md">
                        {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Live Selected Preview --}}
            <div x-show="photoPreview" style="display: none;" class="relative">
                <img :src="photoPreview"
                     alt="Avatar Preview"
                     class="w-24 h-24 rounded-full object-cover border-2 border-red-500 shadow-md ring-4 ring-red-50">
                <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full uppercase tracking-wider">New</span>
            </div>
        </div>

        {{-- Upload & Delete Actions --}}
        <div class="flex-1 space-y-3">
            <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <input type="file"
                       id="avatar"
                       name="avatar"
                       x-ref="photoInput"
                       @change="updatePreview($event)"
                       accept="image/png,image/jpeg,image/webp,image/jpg"
                       class="hidden"
                       required>

                <div class="flex flex-wrap items-center gap-3">
                    {{-- Select File Button --}}
                    <button type="button"
                            @click="$refs.photoInput.click()"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-900 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-blue-600 shadow-sm transition-all duration-200 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        <span x-text="photoPreview ? 'Change Selection' : 'Choose New Photo'">Choose New Photo</span>
                    </button>

                    {{-- Save Button (shown when new image is selected) --}}
                    <button type="submit"
                            x-show="photoPreview"
                            style="display: none;"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-blue-700 shadow-sm transition-all duration-200 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Upload & Save</span>
                    </button>

                    {{-- Cancel Preview Button --}}
                    <button type="button"
                            x-show="photoPreview"
                            style="display: none;"
                            @click="clearPreview()"
                            class="px-3 py-2 text-xs font-semibold text-gray-500 hover:text-gray-700 rounded-xl transition cursor-pointer">
                        {{ __('Cancel') }}
                    </button>

                    {{-- Remove Existing Avatar Button --}}
                    @if ($user->avatar_path)
                        <button type="button"
                                x-show="!photoPreview"
                                onclick="if(confirm('Are you sure you want to remove your profile photo?')) document.getElementById('delete-avatar-form').submit();"
                                class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs sm:text-sm font-semibold text-gray-600 hover:text-white-600 bg-gray-50 hover:bg-blue-50 rounded-xl border border-gray-200 hover:border-red-200 transition duration-150 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>{{ __('Remove Photo') }}</span>
                        </button>
                    @endif
                </div>

                <p class="text-xs text-gray-400">
                    {{ __('Allowed formats: JPG, PNG, WEBP. Maximum file size: 5MB.') }}
                </p>

                <x-input-error class="mt-2" :messages="$errors?->get('avatar') ?? []" />

                @if (session('status') === 'avatar-updated')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm font-semibold text-emerald-600 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ __('Profile picture updated successfully.') }}
                    </p>
                @endif

                @if (session('status') === 'avatar-deleted')
                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm font-semibold text-gray-600">
                        {{ __('Profile picture removed.') }}
                    </p>
                @endif
            </form>

            {{-- Hidden Form for Delete --}}
            @if ($user->avatar_path)
                <form id="delete-avatar-form" method="post" action="{{ route('profile.avatar.destroy') }}" class="hidden">
                    @csrf
                    @method('delete')
                </form>
            @endif
        </div>
    </div>
</section>
