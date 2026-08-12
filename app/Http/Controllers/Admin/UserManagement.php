<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagement extends Controller
{
    protected array $roles = ['Admin', 'Supervisor', 'User'];

    /**
     * List all users — Admin-only screen (role management is not part of the
     * regular lead permission matrix, so gate this in route middleware).
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->ofRole($request->get('role'))
            ->when($request->get('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->withCount(['assignedLeads' => function ($q) {
                $q->whereNotIn('status', ['Converted', 'Lost']);
            }])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'roles' => $this->roles,
        ]);
    }

    public function create()
    {
        return view('admin.users.create', ['roles' => $this->roles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'position'     => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'     => ['required', 'string', 'min:8', 'confirmed'],
            'role'         => ['required', Rule::in($this->roles)],
        ]);

        User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'status'   => 'active',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$validated['name']}\" was created successfully.");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user'  => $user,
            'roles' => $this->roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'position'     => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'         => ['required', Rule::in($this->roles)],
            // Password optional on edit — only update if provided
            'password'     => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "User \"{$user->name}\" was updated.");
    }

    /**
     * Block/Activate toggle — matches the action button seen in the Users list UI.
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', "User \"{$user->name}\" is now {$user->status}.");
    }
}