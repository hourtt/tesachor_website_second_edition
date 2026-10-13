<x-admin-layout>
    <x-slot name="title">Users Management</x-slot>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Users Management</h1>
            <p class="text-gray-500 text-sm mt-1">Manage user roles, permissions, and account activation status.</p>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/75 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">User</th>
                        <th class="px-6 py-4">Role</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Joined</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-100 shrink-0 flex items-center justify-center font-bold text-sm text-gray-600">
                                        @if ($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 flex items-center gap-2">
                                            {{ $user->name }}
                                            @if ($user->id === auth()->id())
                                                <span class="text-[10px] bg-red-50 text-red-600 px-2 py-0.5 rounded-full font-bold">You</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @forelse ($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                        {{ $role->name === 'admin' ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-gray-100 text-gray-700' }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @empty
                                    <span class="text-xs text-gray-400">No role</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-4">
                                @if ($user->is_active ?? true)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Deactivated
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $user->created_at?->format('M d, Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if ($user->id !== auth()->id())
                                    <a href="{{ route('admins.users.edit', $user->id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i class="fa-solid fa-user-pen"></i> Edit Role & Status
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">No users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>
