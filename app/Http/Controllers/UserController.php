<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of system users.
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $search = $request->input('search');
        $status = $request->input('status');

        // 1. Filter Users List with Search, Status, and Security Scope
        $users = User::with(['office', 'roles'])
            ->when(!$currentUser->hasRole('SuperAdmin'), function ($query) {
                // Exclude users who have the SuperAdmin role if current user is not a SuperAdmin
                $query->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'SuperAdmin');
                });
            })
            ->when($search, function ($query, $search) {
                // Search across user name or email address
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                // Filter by user account status (pending, approved, deactivated)
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Preserves ?search= and ?status= parameters across pagination pages

        // 2. Filter Roles Dropdown Options
        $roles = Role::when(!$currentUser->hasRole('SuperAdmin'), function ($query) {
            // Hide the 'SuperAdmin' role from dropdown menu for non-SuperAdmins
            $query->where('name', '!=', 'SuperAdmin');
        })->orderBy('name')
            ->get();

        // 3. Active Offices for Modals/Filters
        $offices = Office::where('is_active', true)->orderBy('name')->get();

        return view('users.index', compact('users', 'offices', 'roles', 'search', 'status'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'office_id' => 'required|exists:offices,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'office_id' => $validated['office_id'],
            'status' => 'approved',
        ]);

        $user->assignRole($validated['role']);

        return redirect()->route('users.index')->with('success', 'User account created successfully!');
    }
    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email,' . $user->id,
            'office_id' => 'required|exists:offices,id',
            'role'      => 'required|exists:roles,name',
            'password'  => 'nullable|string|min:8',
        ]);

        $userData = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'office_id' => $validated['office_id'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $userData['password'] = Hash::make($validated['password']);
        }

        $user->update($userData);
        $user->syncRoles([$validated['role']]);

        return redirect()->route('users.index')->with('success', 'User account updated successfully!');
    }
    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    public function updateStatus(Request $request, User $user)
    {
        // Ensure only authorized users can change user status
        if (!auth()->user()->hasAnyRole(['SuperAdmin', 'Admin'])) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,pending,deactivated',
        ]);

        $user->update([
            'status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', "User account status updated to {$user->status}.");
    }
}
