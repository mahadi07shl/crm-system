<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaffManagement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffManagementController extends Controller
{
    protected array $roles = ['Admin', 'Supervisor', 'User'];

    /**
     * List all staffs — Admin-only screen (role management is not part of the
     * regular lead permission matrix, so gate this in route middleware).
     */
    public function index(Request $request)
    {
        $staffs = User::query()
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

        return view('admin.staff-management.index', [
            'staffs' => $staffs,
            'roles' => $this->roles,
        ]);
    }

    public function create()
    {
        return view('admin.staff-management.create', ['roles' => $this->roles]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                           => ['required', 'string', 'max:255'],
            'position'                       => ['nullable', 'string', 'max:255'],
          
         'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'                       => ['required', 'string', 'min:8', 'confirmed'],
            'role'                           => ['required', Rule::in($this->roles)],
            'phone'                          => ['nullable', 'string', 'max:30'],
            'profile_picture'                => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'gender'                         => ['nullable', 'in:male,female,other'],
            'address'                        => ['nullable', 'string', 'max:255'],
            'emergency_contact_name'         => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone'        => ['nullable', 'string', 'max:30'],
        ]);
  
        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('staffs', 'public');
        }

        User::create([
            ...$validated,
            'password' => Hash::make($validated['password']),
            'status'   => 'active',
        ]);

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', "User \"{$validated['name']}\" was created successfully.");
    }

    public function edit(User $user)
    {
        return view('admin.staffs.edit', [
            'user'  => $user,
            'roles' => $this->roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'                    => ['required', 'string', 'max:255'],
            'position'                => ['nullable', 'string', 'max:255'],
           
          'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role'                    => ['required', Rule::in($this->roles)],
            'phone'                   => ['nullable', 'string', 'max:30'],
            'profile_picture'         => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'gender'                  => ['nullable', 'in:male,female,other'],
            'address'                 => ['nullable', 'string', 'max:255'],
            'emergency_contact_name'  => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            // Password optional on edit — only update if provided
            'password'                => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('staffs', 'public');
        }

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.staffs.index')
            ->with('success', "User \"{$user->name}\" was updated.");
    }

    /**
     * Block/Activate toggle — matches the action button seen in the staffs list UI.
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'active' ? 'inactive' : 'active',
        ]);

        return back()->with('success', "User \"{$user->name}\" is now {$user->status}.");
    }
}