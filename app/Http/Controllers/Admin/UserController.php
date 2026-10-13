<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
// TODO(SUPABASE): inject SupabaseService for user sync when role/status changes
// use App\Services\SupabaseService;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')
            ->latest()
            ->paginate(20);

        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update user role and active status.
     * Cannot demote or deactivate yourself.
     */
    public function update(Request $request, User $user)
    {
        // Prevent self-demotion or self-deactivation
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot modify your own account here.');
        }

        $data = $request->validate([
            'role'      => 'required|string|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        // Sync role
        $user->syncRoles([$data['role']]);

        // Update active status
        $isActive = $request->boolean('is_active', true);
        $user->update(['is_active' => $isActive]);

        // TODO(SUPABASE): sync role/active-status change to Supabase Auth user:
        //   $this->supabase->syncUserStatus($user->id, $isActive, $data['role']);

        // If deactivating, invalidate all their sessions immediately
        if (!$isActive) {
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        }

        return redirect()->route('admins.users.index')
            ->with('success', "User \"{$user->name}\" updated successfully.");
    }
}
