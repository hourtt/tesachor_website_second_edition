{{-- Editable Display Name Form --}}
<form method="post" action="{{ route('admins.profile.update') }}" class="pt-6">
    @csrf
    @method('patch')

    <div class="space-y-4">
        <div>
            <label for="admin_name" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Display Name
            </label>
            <div x-data="autoResizeInput(@js(old('name', $user->name)), 36)" class="relative inline-block max-w-full">
                <span x-ref="sizer"
                    class="absolute -left-[9999px] -top-[9999px] invisible whitespace-pre text-base sm:text-sm font-sans select-none pointer-events-none"
                    x-text="displayText"></span>
                <input id="admin_name" name="name" type="text" x-model="val"
                    @input="resize()" @focus="resize()" required autocomplete="name"
                    placeholder="Display Name"
                    :style="'width: ' + width + 'px; min-width: 150px; max-width: 100%;'"
                    class="block pl-4 pr-4 py-2.5 text-base sm:text-sm bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 shadow-2xs focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition-[width] duration-200 ease-out">
            </div>
            @if ($errors->get('name'))
                <p class="mt-1.5 text-xs font-medium text-red-600 flex items-center gap-1">
                    <i class="fa-solid fa-circle-exclamation text-[11px]"></i>
                    {{ $errors->first('name') }}
                </p>
            @endif
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 pt-2">
            <button type="submit"
                class="w-full sm:w-auto justify-center inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-semibold rounded-xl shadow-xs hover:shadow-sm transition-all duration-200 cursor-pointer focus:outline-hidden focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fa-solid fa-check text-xs"></i>
                Save Display Name
            </button>

            @if (session('status') === 'profile-updated' && !session('email_updated'))
                <span x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-semibold text-emerald-600 flex items-center gap-1.5 justify-center sm:justify-start">
                    <i class="fa-solid fa-circle-check text-emerald-500"></i>
                    Saved successfully.
                </span>
            @endif
        </div>
    </div>
</form>
