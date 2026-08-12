@extends('admin.layouts.app')

@section('title', 'Add User - Skils Hut CRM')

@section('content')

<div class="max-w-5xl mx-auto w-full flex flex-col gap-lg">

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.staffs.index') }}" class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-display-lg text-on-surface" style="font-size: 32px;">Add New User</h1>
            <p class="font-body-md text-body-md text-secondary mt-1">Create a team member account and assign their role.</p>
        </div>
    </div>

    {{-- Validation errors summary --}}
    @if ($errors->any())
        <div class="bg-error-container text-on-error-container px-4 py-3 rounded-lg font-label-sm text-label-sm">
            <p class="font-semibold mb-1">Please fix the following:</p>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.staffs.store') }}" method="POST" enctype="multipart/form-data" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col gap-lg w-full">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">

            <!-- Name -->
            <div class="flex flex-col gap-1.5">
                <label for="name" class="font-label-sm text-label-sm text-on-surface font-semibold">
                    Full Name <span class="text-error">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       required placeholder="e.g. Sarah Jenkins"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('name') border-error @else border-outline-variant @enderror">
                @error('name')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-1.5">
                <label for="email" class="font-label-sm text-label-sm text-on-surface font-semibold">
                    Email <span class="text-error">*</span>
                </label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       required placeholder="e.g. sarah.j@skilshut.com"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('email') border-error @else border-outline-variant @enderror">
                @error('email')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Position -->
            <div class="flex flex-col gap-1.5">
                <label for="position" class="font-label-sm text-label-sm text-on-surface font-semibold">Position</label>
                <input type="text" id="position" name="position" value="{{ old('position') }}"
                       placeholder="e.g. Sales Executive"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('position') border-error @else border-outline-variant @enderror">
                @error('position')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Role -->
            <div class="flex flex-col gap-1.5">
                <label for="role" class="font-label-sm text-label-sm text-on-surface font-semibold">
                    Role <span class="text-error">*</span>
                </label>
                <select id="role" name="role" required
                        class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                               focus:border-primary-container focus:ring-1 focus:ring-primary-container
                               @error('role') border-error @else border-outline-variant @enderror">
                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select a role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>{{ $role }}</option>
                    @endforeach
                </select>
                @error('role')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
                <p class="text-xs text-secondary mt-1">Admin: full access. Supervisor: manage & assign leads. User: follow-up only.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-md pt-md border-t border-outline-variant">

            <!-- Password -->
            <div class="flex flex-col gap-1.5">
                <label for="password" class="font-label-sm text-label-sm text-on-surface font-semibold">
                    Password <span class="text-error">*</span>
                </label>
                <input type="password" id="password" name="password"
                       required minlength="8" placeholder="Minimum 8 characters"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('password') border-error @else border-outline-variant @enderror">
                @error('password')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="flex flex-col gap-1.5">
                <label for="password_confirmation" class="font-label-sm text-label-sm text-on-surface font-semibold">
                    Confirm Password <span class="text-error">*</span>
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       required minlength="8" placeholder="Re-enter password"
                       class="w-full px-4 py-2.5 border border-outline-variant rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container">
            </div>
        </div>

        <!-- Extended Profile Info -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-md pt-md border-t border-outline-variant">

            <!-- Profile Picture -->
            <div class="flex flex-col gap-1.5 sm:col-span-2">
                <label for="profile_picture" class="font-label-sm text-label-sm text-on-surface font-semibold">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors file:mr-4 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-primary-container/10 file:text-primary-container file:font-semibold
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('profile_picture') border-error @else border-outline-variant @enderror">
                @error('profile_picture')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
                <p class="text-xs text-secondary">JPG, PNG or GIF. Max 2MB.</p>
            </div>

            <!-- Phone -->
            <div class="flex flex-col gap-1.5">
                <label for="phone" class="font-label-sm text-label-sm text-on-surface font-semibold">Phone Number</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                       placeholder="e.g. +880 1XXXXXXXXX"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('phone') border-error @else border-outline-variant @enderror">
                @error('phone')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Gender -->
            <div class="flex flex-col gap-1.5">
                <label for="gender" class="font-label-sm text-label-sm text-on-surface font-semibold">Gender</label>
                <select id="gender" name="gender"
                        class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                               focus:border-primary-container focus:ring-1 focus:ring-primary-container
                               @error('gender') border-error @else border-outline-variant @enderror">
                    <option value="" {{ old('gender') ? '' : 'selected' }}>Prefer not to say</option>
                    <option value="male" @selected(old('gender') === 'male')>Male</option>
                    <option value="female" @selected(old('gender') === 'female')>Female</option>
                    <option value="other" @selected(old('gender') === 'other')>Other</option>
                </select>
                @error('gender')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Address -->
            <div class="flex flex-col gap-1.5 sm:col-span-2">
                <label for="address" class="font-label-sm text-label-sm text-on-surface font-semibold">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}"
                       placeholder="e.g. House 12, Road 5, Dhanmondi, Dhaka"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('address') border-error @else border-outline-variant @enderror">
                @error('address')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Emergency Contact Name -->
            <div class="flex flex-col gap-1.5">
                <label for="emergency_contact_name" class="font-label-sm text-label-sm text-on-surface font-semibold">Emergency Contact Name</label>
                <input type="text" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                       placeholder="e.g. Fatima Rahman"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('emergency_contact_name') border-error @else border-outline-variant @enderror">
                @error('emergency_contact_name')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Emergency Contact Phone -->
            <div class="flex flex-col gap-1.5">
                <label for="emergency_contact_phone" class="font-label-sm text-label-sm text-on-surface font-semibold">Emergency Contact Phone</label>
                <input type="text" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                       placeholder="e.g. +880 1XXXXXXXXX"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('emergency_contact_phone') border-error @else border-outline-variant @enderror">
                @error('emergency_contact_phone')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-md pt-md border-t border-outline-variant">
            <a href="{{ route('admin.staffs.index') }}"
               class="px-6 py-3 rounded-lg font-label-sm text-label-sm text-secondary hover:bg-surface-variant transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="bg-primary-container text-on-primary-container px-8 py-3 rounded-lg font-label-sm text-label-sm font-semibold hover:bg-[#e0650c] active:scale-95 transition-all shadow-md shadow-primary-container/20 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">check</span>
                Create User
            </button>
        </div>
    </form>

</div>

@endsection