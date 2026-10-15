{{-- Security & Password Section --}}
<div class="pt-8">
    <div class="mb-5">
        <h3 class="text-base font-bold text-gray-900 tracking-tight flex items-center gap-2">
            <i class="fa-solid fa-key text-blue-600 text-sm"></i>
            Change Password
        </h3>
        <p class="text-xs sm:text-sm text-gray-500 mt-1">
            Ensure your account is using a long, random password to maintain administrative security.
        </p>
    </div>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password"
                class="block text-sm font-semibold text-gray-700 mb-1.5">
                Current Password
            </label>
            <div x-data="autoResizeInput('', 70, true, 360)" class="relative inline-block max-w-full">
                <span x-ref="sizer"
                    class="absolute -left-[9999px] -top-[9999px] invisible whitespace-pre text-base sm:text-sm font-sans select-none pointer-events-none"
                    x-text="displayText"></span>
                <input :type="showPass ? 'text' : 'password'" id="update_password_current_password"
                    name="current_password" x-model="val" @input="resize()" @focus="resize()" required autocomplete="current-password"
                    placeholder="Current"
                    :style="'width: ' + width + 'px; min-width: 150px; max-width: 100%;'"
                    class="block pl-4 pr-12 py-2.5 text-base sm:text-sm bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-[width] duration-200 ease-out">
                <button type="button" @click="showPass = !showPass; resize()"
                    class="absolute inset-y-0 right-0 min-w-[44px] min-h-[44px] px-3 flex items-center justify-center text-gray-400 hover:text-gray-700 transition-colors focus:outline-hidden cursor-pointer"
                    :title="showPass ? 'Hide password' : 'Show password'"
                    :aria-label="showPass ? 'Hide password' : 'Show password'">
                    <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"
                        class="text-sm"></i>
                </button>
            </div>
            @if ($errors->updatePassword->get('current_password'))
                <p class="mt-1.5 text-xs font-medium text-red-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                    {{ $errors->updatePassword->first('current_password') }}
                </p>
            @endif
        </div>

        {{-- Paired Password Fields: Tight Flex Row with push/pull animation --}}
        <div class="flex flex-col sm:flex-row sm:flex-wrap items-start gap-4">
            {{-- New Password --}}
            <div class="w-auto">
                <label for="update_password_password"
                    class="block text-sm font-semibold text-gray-700 mb-1.5">
                    New Password
                </label>
                <div x-data="autoResizeInput('', 70, true, 360)" class="relative inline-block max-w-full">
                    <span x-ref="sizer"
                        class="absolute -left-[9999px] -top-[9999px] invisible whitespace-pre text-base sm:text-sm font-sans select-none pointer-events-none"
                        x-text="displayText"></span>
                    <input :type="showPass ? 'text' : 'password'" id="update_password_password"
                        name="password" x-model="val" @input="resize()" @focus="resize()" required autocomplete="new-password"
                        placeholder="New"
                        :style="'width: ' + width + 'px; min-width: 150px; max-width: 100%;'"
                        class="block pl-4 pr-12 py-2.5 text-base sm:text-sm bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-[width] duration-200 ease-out">
                    <button type="button" @click="showPass = !showPass; resize()"
                        class="absolute inset-y-0 right-0 min-w-[44px] min-h-[44px] px-3 flex items-center justify-center text-gray-400 hover:text-gray-700 transition-colors focus:outline-hidden cursor-pointer"
                        :title="showPass ? 'Hide password' : 'Show password'"
                        :aria-label="showPass ? 'Hide password' : 'Show password'">
                        <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"
                            class="text-sm"></i>
                    </button>
                </div>
                @if ($errors->updatePassword->get('password'))
                    <p class="mt-1.5 text-xs font-medium text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                        {{ $errors->updatePassword->first('password') }}
                    </p>
                @endif
            </div>

            {{-- Confirm Password --}}
            <div class="w-auto">
                <label for="update_password_password_confirmation"
                    class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Confirm Password
                </label>
                <div x-data="autoResizeInput('', 70, true, 360)" class="relative inline-block max-w-full">
                    <span x-ref="sizer"
                        class="absolute -left-[9999px] -top-[9999px] invisible whitespace-pre text-base sm:text-sm font-sans select-none pointer-events-none"
                        x-text="displayText"></span>
                    <input :type="showPass ? 'text' : 'password'"
                        id="update_password_password_confirmation" name="password_confirmation"
                        x-model="val" @input="resize()" @focus="resize()" required
                        autocomplete="new-password" placeholder="Confirm"
                        :style="'width: ' + width + 'px; min-width: 150px; max-width: 100%;'"
                        class="block pl-4 pr-12 py-2.5 text-base sm:text-sm bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-[width] duration-200 ease-out">
                    <button type="button" @click="showPass = !showPass; resize()"
                        class="absolute inset-y-0 right-0 min-w-[44px] min-h-[44px] px-3 flex items-center justify-center text-gray-400 hover:text-gray-700 transition-colors focus:outline-hidden cursor-pointer"
                        :title="showPass ? 'Hide password' : 'Show password'"
                        :aria-label="showPass ? 'Hide password' : 'Show password'">
                        <i :class="showPass ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye'"
                            class="text-sm"></i>
                    </button>
                </div>
                @if ($errors->updatePassword->get('password_confirmation'))
                    <p class="mt-1.5 text-xs font-medium text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                        {{ $errors->updatePassword->first('password_confirmation') }}
                    </p>
                @endif
            </div>
        </div>

        {{-- Action & Feedback --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
            <button type="submit"
                class="w-full sm:w-auto justify-center inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-xs hover:shadow-sm transition-all duration-200 cursor-pointer focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fa-solid fa-lock text-xs"></i>
                Update Password
            </button>

            @if (session('status') === 'password-updated')
                <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-semibold text-emerald-600 flex items-center gap-1.5 justify-center sm:justify-start">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    Password updated successfully.
                </span>
            @endif
        </div>
    </form>
</div>
