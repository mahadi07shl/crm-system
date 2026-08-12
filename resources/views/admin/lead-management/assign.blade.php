@extends('admin.layouts.app')

@section('title', 'Assign Lead - Skils Hut CRM')

@section('content')

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.leads.index') }}" class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-display-lg text-on-surface" style="font-size: 32px;">Assign Lead</h1>
            <p class="font-body-md text-body-md text-secondary mt-1">Choose who should own and follow up on this lead.</p>
        </div>
    </div>

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">

        <!-- Lead Summary Card -->
        <div class="lg:col-span-1">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col gap-md">
                <h2 class="font-headline-lg text-headline-lg text-on-surface" style="font-size: 18px;">Lead Details</h2>

                <div class="flex flex-col gap-3">
                    <div>
                        <span class="block font-label-sm text-label-sm text-secondary">Company</span>
                        <span class="font-body-md text-body-md text-on-surface font-semibold">{{ $lead->company_name }}</span>
                    </div>
                    <div>
                        <span class="block font-label-sm text-label-sm text-secondary">Contact</span>
                        <span class="font-body-md text-body-md text-on-surface">{{ $lead->contact_name }}</span>
                    </div>
                    @if ($lead->email)
                        <div>
                            <span class="block font-label-sm text-label-sm text-secondary">Email</span>
                            <span class="font-body-md text-body-md text-on-surface">{{ $lead->email }}</span>
                        </div>
                    @endif
                    @if ($lead->phone)
                        <div>
                            <span class="block font-label-sm text-label-sm text-secondary">Phone</span>
                            <span class="font-body-md text-body-md text-on-surface">{{ $lead->phone }}</span>
                        </div>
                    @endif
                    <div>
                        <span class="block font-label-sm text-label-sm text-secondary">Status</span>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-primary-container/10 text-[#9d4300] font-label-sm text-label-sm font-semibold mt-1">
                            {{ $lead->status instanceof \BackedEnum ? $lead->status->value : $lead->status }}
                        </span>
                    </div>
                    <div>
                        <span class="block font-label-sm text-label-sm text-secondary">Currently Assigned To</span>
                        <span class="font-body-md text-body-md text-on-surface">
                            {{ $lead->assignee->name ?? 'Unassigned' }}
                        </span>
                    </div>
                </div>

                @auth
                    @if (auth()->user()->isAdminOrSupervisor())
                        <form action="{{ route('admin.admin.leads.assign-self', $lead) }}" method="POST" class="mt-2">
                            @csrf
                            <button type="submit"
                                    class="w-full px-4 py-2.5 border-2 border-primary-container text-primary-container rounded-lg font-label-sm text-label-sm font-semibold hover:bg-primary-container/5 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined" style="font-size: 18px;">person</span>
                                Assign to Myself
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </div>

        <!-- Assignment Form -->
        <div class="lg:col-span-2">
            <form action="{{ route('admin.admin.leads.assign.store', $lead) }}" method="POST"
                  class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col gap-lg">
                @csrf

                <div class="flex flex-col gap-1.5">
                    <label class="font-label-sm text-label-sm text-on-surface font-semibold">
                        Select Assignee <span class="text-error">*</span>
                    </label>
                    <p class="text-xs text-secondary mb-2">This lead currently has a single owner — assigning a new person replaces the previous one.</p>

                    <div class="flex flex-col gap-2 max-h-96 overflow-y-auto pr-1">
                        @forelse ($assignees as $assignee)
                            <label class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition-colors
                                          {{ old('assigned_to', $lead->assigned_to) == $assignee->id ? 'border-primary-container bg-primary-container/5' : 'border-outline-variant hover:border-primary-container/40' }}">
                                <input type="radio" name="assigned_to" value="{{ $assignee->id }}"
                                       {{ old('assigned_to', $lead->assigned_to) == $assignee->id ? 'checked' : '' }}
                                       class="w-4 h-4 accent-[#ff5c00]" required>
                                <div class="w-9 h-9 rounded-full bg-surface-dim flex items-center justify-center text-primary font-semibold text-sm flex-shrink-0">
                                    {{ \Illuminate\Support\Str::of($assignee->name)->explode(' ')->map(fn($w) => \Illuminate\Support\Str::substr($w, 0, 1))->take(2)->implode('') }}
                                </div>
                                <div class="flex-1">
                                    <div class="font-label-sm text-label-sm text-on-surface font-semibold">{{ $assignee->name }}</div>
                                    <div class="text-xs text-secondary">{{ $assignee->email }}</div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                             {{ strtolower($assignee->role) === 'admin' ? 'bg-[#ffdbca] text-[#783200]' : (strtolower($assignee->role) === 'supervisor' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $assignee->role }}
                                </span>
                            </label>
                        @empty
                            <p class="text-secondary font-body-md text-body-md">No active staff available to assign.</p>
                        @endforelse
                    </div>
                    @error('assigned_to')
                        <span class="text-xs text-error mt-1">{{ $message }}</span>
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
                        Confirm Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection