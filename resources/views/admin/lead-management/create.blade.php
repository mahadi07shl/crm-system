@extends('admin.layouts.app')

@section('title', 'Add Lead - Skils Hut CRM')

@section('content')

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.leads.index') }}" class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-display-lg text-on-surface" style="font-size: 32px;">Add New Lead</h1>
            <p class="font-body-md text-body-md text-secondary mt-1">Enter the lead's details below. It will be added with "New" status.</p>
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

    <form action="{{ route('admin.leads.store') }}" method="POST" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col gap-lg max-w-3xl">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-md">

            <!-- Company Name -->
            <div class="flex flex-col gap-1.5">
                <label for="company_name" class="font-label-sm text-label-sm text-on-surface font-semibold">
                    Company Name <span class="text-error">*</span>
                </label>
                <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}"
                       required
                       placeholder="e.g. Acme Corp"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('company_name') border-error @else border-outline-variant @enderror">
                @error('company_name')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Contact Name -->
            <div class="flex flex-col gap-1.5">
                <label for="contact_name" class="font-label-sm text-label-sm text-on-surface font-semibold">
                    Contact Name <span class="text-error">*</span>
                </label>
                <input type="text" id="contact_name" name="contact_name" value="{{ old('contact_name') }}"
                       required
                       placeholder="e.g. Jane Doe"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('contact_name') border-error @else border-outline-variant @enderror">
                @error('contact_name')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Email -->
            <div class="flex flex-col gap-1.5">
                <label for="email" class="font-label-sm text-label-sm text-on-surface font-semibold">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                       placeholder="e.g. jane@acmecorp.com"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('email') border-error @else border-outline-variant @enderror">
                @error('email')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Phone -->
            <div class="flex flex-col gap-1.5">
                <label for="phone" class="font-label-sm text-label-sm text-on-surface font-semibold">Phone</label>
                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                       placeholder="e.g. +880 1XXXXXXXXX"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('phone') border-error @else border-outline-variant @enderror">
                @error('phone')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Designation -->
            <div class="flex flex-col gap-1.5">
                <label for="designation" class="font-label-sm text-label-sm text-on-surface font-semibold">Designation</label>
                <input type="text" id="designation" name="designation" value="{{ old('designation') }}"
                       placeholder="e.g. Marketing Manager"
                       class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                              focus:border-primary-container focus:ring-1 focus:ring-primary-container
                              @error('designation') border-error @else border-outline-variant @enderror">
                @error('designation')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Category -->
            <div class="flex flex-col gap-1.5">
                <label for="category_id" class="font-label-sm text-label-sm text-on-surface font-semibold">
                    Category <span class="text-error">*</span>
                </label>
                <select id="category_id" name="category_id" required
                        class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                               focus:border-primary-container focus:ring-1 focus:ring-primary-container
                               @error('category_id') border-error @else border-outline-variant @enderror">
                    <option value="" disabled {{ old('category_id') ? '' : 'selected' }}>Select a category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror
                @if ($categories->isEmpty())
                    <span class="text-xs text-secondary">No categories yet — ask an Admin to add one first.</span>
                @endif
            </div>
        </div>

        <!-- Remark -->
        <div class="flex flex-col gap-1.5">
            <label for="remark" class="font-label-sm text-label-sm text-on-surface font-semibold">Remark</label>
            <textarea id="remark" name="remark" rows="4"
                      placeholder="Any free-text notes about this lead..."
                      class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors resize-none
                             focus:border-primary-container focus:ring-1 focus:ring-primary-container
                             @error('remark') border-error @else border-outline-variant @enderror">{{ old('remark') }}</textarea>
            @error('remark')
                <span class="text-xs text-error">{{ $message }}</span>
            @enderror
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-md pt-md border-t border-outline-variant">
            <a href="{{ route('admin.leads.index') }}"
               class="px-6 py-3 rounded-lg font-label-sm text-label-sm text-secondary hover:bg-surface-variant transition-colors">
                Cancel
            </a>
            <button type="submit"
                    class="bg-primary-container text-on-primary-container px-8 py-3 rounded-lg font-label-sm text-label-sm font-semibold hover:bg-[#e0650c] active:scale-95 transition-all shadow-md shadow-primary-container/20 flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">check</span>
                Save Lead
            </button>
        </div>
    </form>

@endsection