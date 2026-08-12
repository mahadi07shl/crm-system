@extends('admin.layouts.app')

@section('title', 'User Management - Skils Hut CRM')

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-display-lg text-display-lg text-on-surface" style="font-size: 32px;">User Management</h1>
            <p class="font-body-md text-body-md text-secondary mt-1">Manage team access, roles, and monitor active lead assignments.</p>
        </div>
        <a href="{{ route('admin.staffs.create') }}"
           class="bg-primary-container hover:bg-[#e0650c] text-on-primary-container px-6 py-3 rounded-lg font-label-sm text-label-sm font-semibold flex items-center gap-2 transition-colors active:scale-95">
            <span class="material-symbols-outlined" style="font-size: 18px;">person_add</span>
            Add New User
        </a>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="bg-[#dcfce7] text-[#166534] px-4 py-3 rounded-lg font-label-sm text-label-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant flex flex-col sm:flex-row gap-4 items-center justify-between">
        <form action="{{ route('admin.staffs.index') }}" method="GET" class="relative w-full sm:w-96">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary" style="font-size: 20px;">search</span>
            <input class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded-lg font-label-sm text-label-sm focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none bg-surface-bright"
                   placeholder="Search staffs by name or email..." type="text" name="search" value="{{ request('search') }}">
        </form>

        <div class="flex gap-2 w-full sm:w-auto overflow-x-auto pb-2 sm:pb-0">
            @php $currentRole = request('role', ''); @endphp
            <a href="{{ route('admin.staffs.index', array_filter(array_merge(request()->query(), ['role' => null]))) }}"
               class="px-4 py-2 rounded-full font-label-sm text-label-sm whitespace-nowrap border transition-colors
                      {{ $currentRole === '' ? 'bg-primary-container/10 text-[#9d4300] border-primary-container/20' : 'bg-surface-container-low text-secondary border-transparent hover:border-outline-variant' }}">
                All Roles
            </a>
            @foreach ($roles as $role)
                <a href="{{ route('admin.staffs.index', array_merge(request()->query(), ['role' => $role])) }}"
                   class="px-4 py-2 rounded-full font-label-sm text-label-sm whitespace-nowrap border transition-colors
                          {{ $currentRole === $role ? 'bg-primary-container/10 text-[#9d4300] border-primary-container/20' : 'bg-surface-container-low text-secondary border-transparent hover:border-outline-variant' }}">
                    {{ $role }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-lg">

        <!-- Summary Card -->
        <div class="lg:col-span-1 bg-surface-container-lowest p-lg rounded-xl border border-outline-variant flex flex-col justify-between">
            <div>
                <h3 class="font-headline-lg text-headline-lg text-on-surface mb-1" style="font-size: 20px; line-height: 28px;">Team Overview</h3>
                <p class="font-body-md text-body-md text-secondary" style="font-size: 14px;">A snapshot of your current team capacity and distribution.</p>
            </div>

            <div class="mt-lg grid grid-cols-2 gap-4">
                <div class="bg-surface-container p-4 rounded-lg">
                    <span class="block font-label-sm text-label-sm text-secondary mb-1">Total staffs</span>
                    <span class="font-display-lg text-display-lg text-primary-container leading-none" style="font-size: 32px;">
                        {{ $staffs->total() }}
                    </span>
                </div>
                <div class="bg-surface-container p-4 rounded-lg">
                    <span class="block font-label-sm text-label-sm text-secondary mb-1">Active</span>
                    <span class="font-display-lg text-display-lg text-on-surface leading-none" style="font-size: 32px;">
                        {{ $staffs->where('status', 'active')->count() }}
                    </span>
                </div>
            </div>

            <div class="mt-lg space-y-3">
                @foreach ($roles as $role)
                    @php
                        $countForRole = $staffs->where('role', $role)->count();
                        $percent = $staffs->total() > 0 ? round(($countForRole / $staffs->total()) * 100) : 0;
                    @endphp
                    <div class="flex justify-between items-center font-label-sm text-label-sm {{ !$loop->first ? 'mt-4' : '' }}">
                        <span class="text-secondary">{{ $role }}</span>
                        <span class="font-semibold text-on-surface">{{ $countForRole }}</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5">
                        <div class="bg-primary-container h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Main Data List -->
        <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-bright">
                            <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">User</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">Role</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">Active Leads</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">Status</th>
                            <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/50">
                        @forelse ($staffs as $user)
                            <tr class="hover:bg-surface-container-low transition-colors group relative">
                                <td class="py-4 px-6 relative">
                                    @if (strtolower($user->role) === 'admin')
                                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary-container"></div>
                                    @endif
                                    <div class="flex items-center gap-3">
                                        @if ($user->avatarUrl())
                                            <img alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover" src="{{ $user->avatarUrl() }}">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-surface-dim flex items-center justify-center text-primary font-semibold">
                                                {{ \Illuminate\Support\Str::of($user->name)->explode(' ')->map(fn($w) => \Illuminate\Support\Str::substr($w, 0, 1))->take(2)->implode('') }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-label-sm text-label-sm text-on-surface font-semibold">{{ $user->name }}</div>
                                            <div class="text-xs text-secondary mt-0.5">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $roleClasses = strtolower($user->role) === 'admin'
                                            ? 'bg-[#ffdbca] text-[#783200]'
                                            : (strtolower($user->role) === 'supervisor' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700');
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $roleClasses }}">
                                        {{ $user->role }}
                                    </span>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-label-sm text-label-sm text-on-surface">{{ $user->assigned_leads_count ?? 0 }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if ($user->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 text-sm text-[#006874]">
                                            <span class="w-2 h-2 rounded-full bg-[#006874]"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-sm text-secondary">
                                            <span class="w-2 h-2 rounded-full bg-slate-300"></span> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.staffs.edit', $user) }}"
                                           class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                        <form action="{{ route('admin.users.toggleStatus', $user) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="p-2 text-secondary hover:text-error transition-colors rounded-full hover:bg-error/10">
                                                <span class="material-symbols-outlined text-[20px]">
                                                    {{ $user->status === 'active' ? 'block' : 'play_arrow' }}
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-14 px-6 text-center">
                                    <div class="flex flex-col items-center gap-2 text-secondary">
                                        <span class="material-symbols-outlined" style="font-size: 32px;">group</span>
                                        <span class="font-label-sm text-label-sm">No staffs found.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($staffs, 'links'))
                <div class="mt-auto p-4 border-t border-outline-variant bg-surface-bright flex items-center justify-between">
                    <span class="font-label-sm text-label-sm text-secondary">
                        Showing {{ $staffs->firstItem() ?? 0 }} to {{ $staffs->lastItem() ?? 0 }} of {{ $staffs->total() }} staffs
                    </span>
                    <div class="flex gap-1">
                        {{ $staffs->onEachSide(1)->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>

@endsection