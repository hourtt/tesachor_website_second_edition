<x-admin-layout>
    <x-slot name="title">Edit User: {{ $user->name }}</x-slot>

    {{-- Breadcrumb / Header --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="{{ route('admins.users.index') }}" class="hover:text-gray-900 transition">Users</a>
                <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                <span class="text-gray-900 font-medium">Edit: {{ $user->name }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Edit User Account</h1>
        </div>
        <a href="{{ route('admins.users.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">
            <i class="fa-solid fa-arrow-left mr-1"></i> Back to Users
        </a>
    </div>

    {{-- Edit Card --}}
    <div class="max-w-2xl bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
        {{-- User Summary Header --}}
        <div class="flex items-center gap-4 pb-6 mb-6 border-b border-gray-100">
            <div class="w-14 h-14 rounded-full bg-gray-100 flex items-center justify-center font-bold text-lg text-gray-600 shrink-0">
                {{-- avatar_url is an accessor defined in App\Models\User via getAvatarUrlAttribute() --}}
                @if ($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover rounded-full">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <p class="text-xs text-gray-400 mt-0.5">Member since {{ $user->created_at?->format('M d, Y') ?? 'N/A' }}</p>
            </div>
        </div>

        <form action="{{ route('admins.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Role Selection --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role Assignment</label>
                    <select name="role" required
                        class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-blue-500 border-gray-200 outline-none transition bg-white">
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @selected($user->hasRole($role->name))>
                                {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Active Status --}}
                <div class="pt-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Account Status</label>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3.5 border rounded-xl cursor-pointer hover:bg-gray-50 transition
                            {{ ($user->is_active ?? true) ? 'border-emerald-300 bg-emerald-50/30' : 'border-gray-200' }}">
                            <input type="radio" name="is_active" value="1"
                                {{ ($user->is_active ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                            <div>
                                <span class="block text-sm font-semibold text-gray-900">Active</span>
                                <span class="block text-xs text-gray-500">User can log in and browse their authorized pages.</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3.5 border rounded-xl cursor-pointer hover:bg-gray-50 transition
                            {{ !($user->is_active ?? true) ? 'border-rose-300 bg-rose-50/30' : 'border-gray-200' }}">
                            <input type="radio" name="is_active" value="0"
                                {{ !($user->is_active ?? true) ? 'checked' : '' }}
                                class="w-4 h-4 text-rose-600 focus:ring-rose-500">
                            <div>
                                <span class="block text-sm font-semibold text-gray-900">Deactivated</span>
                                <span class="block text-xs text-gray-500">User will be immediately logged out and forbidden from logging in.</span>
                            </div>
                        </label>
                    </div>
                    @error('is_active')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Submit --}}
            <div class="flex justify-end pt-6 mt-6 border-t border-gray-100 gap-3">
                <a href="{{ route('admins.users.index') }}"
                    class="px-5 py-2.5 rounded-xl border border-gray-200 text-gray-600 hover:bg-gray-50 transition font-medium text-sm">
                    Cancel
                </a>
                <button type="submit"
                    class="text-white bg-blue-600 hover:bg-blue-700 font-semibold rounded-xl px-6 py-2.5 shadow-sm transition text-sm">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</x-admin-layout>
