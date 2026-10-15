{{-- Avatar & Identity Container --}}
<div x-data="{
    uploading: false,
    onAvatarSelected(event) {
        const file = event.target.files[0];
        if (file) {
            this.uploading = true;
            this.$refs.avatarUploadForm.submit();
        }
    }
}"
    class="flex flex-col sm:flex-row items-start sm:items-center gap-4 sm:gap-6 pb-6 border-b border-gray-100">

    {{-- Hidden Avatar Upload Form (Supabase Storage) --}}
    <form x-ref="avatarUploadForm" method="post" action="{{ route('profile.avatar.update') }}"
        enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="file" x-ref="avatarFileInput" id="admin_avatar_file" name="avatar"
            accept="image/png,image/jpeg,image/webp,image/jpg" @change="onAvatarSelected($event)"
            class="hidden">
    </form>

    {{-- Interactive Circular Avatar Trigger --}}
    <div class="relative group cursor-pointer flex-shrink-0" @click="$refs.avatarFileInput.click()"
        role="button" tabindex="0" @keydown.enter.prevent="$refs.avatarFileInput.click()"
        @keydown.space.prevent="$refs.avatarFileInput.click()" title="Click to change profile picture"
        aria-label="Change profile photo">

        {{-- Circular Avatar Image / Fallback Initials --}}
        <div
            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-gray-200 group-hover:border-blue-500 group-hover:ring-4 group-hover:ring-blue-100 shadow-xs transition-all duration-200 bg-gray-50">
            @if ($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}"
                    class="w-full h-full object-cover">
            @else
                <div
                    class="w-full h-full bg-blue-100 text-blue-700 font-bold text-2xl sm:text-3xl flex items-center justify-center">
                    {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                </div>
            @endif
        </div>

        {{-- Desktop Hover Semi-Transparent Dark Overlay with Camera Icon --}}
        <div
            class="hidden sm:flex absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-full flex-col items-center justify-center text-white pointer-events-none">
            <i class="fa-solid fa-camera text-base"></i>
            <span class="text-[10px] font-medium tracking-wide mt-1">Change</span>
        </div>

        {{-- Mobile Touch Camera Badge (Anchored to Bottom-Right Corner) --}}
        <div
            class="sm:hidden absolute -bottom-1 -right-1 w-7 h-7 bg-blue-600 text-white rounded-full flex items-center justify-center border-2 border-white shadow-xs pointer-events-none">
            <i class="fa-solid fa-camera text-[11px]"></i>
        </div>

        {{-- Uploading Spinner Overlay --}}
        <div x-cloak x-show="uploading"
            class="absolute inset-0 bg-black/60 rounded-full flex items-center justify-center text-white">
            <i class="fa-solid fa-circle-notch fa-spin text-xl"></i>
        </div>
    </div>

    {{-- Identity Info Beside Avatar --}}
    <div class="flex-1 min-w-0">
        <div class="flex flex-wrap items-center gap-2">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight truncate">
                {{ $user->name }}
            </h3>
            <span
                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200/60 shadow-2xs">
                <i class="fa-solid fa-shield-halved text-[10px]"></i>
                Administrator
            </span>
        </div>
        <p class="text-xs sm:text-sm text-gray-500 mt-1 break-all truncate">
            {{ $user->email }}
        </p>
        <p class="text-xs text-gray-400 mt-1.5 hidden sm:flex items-center gap-1.5">
            <i class="fa-solid fa-circle-info text-[10px] text-gray-400"></i>
            Click the avatar to upload a custom JPG, PNG, or WEBP photo.
        </p>
        <p class="text-xs text-gray-400 mt-1 sm:hidden flex items-center gap-1">
            <i class="fa-solid fa-hand-pointer text-[10px] text-blue-500"></i>
            Tap avatar to upload photo
        </p>
    </div>
</div>

{{-- Avatar Validation Error --}}
@if ($errors->get('avatar'))
    <div
        class="mt-4 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-700 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation text-red-500 flex-shrink-0"></i>
        <span>{{ $errors->first('avatar') }}</span>
    </div>
@endif
