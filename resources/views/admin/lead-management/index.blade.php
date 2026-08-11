@extends('admin.layouts.app')

@section('title', 'All Leads - Skils Hut CRM')

@section('content')

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="font-display-lg text-display-lg text-on-surface">All Leads</h1>
            <p class="font-body-md text-body-md text-secondary mt-2">Track, assign, and follow up on every lead in the pipeline.</p>
        </div>
        <div class="flex items-center gap-3">
            @can('bulkUpload', App\Models\Lead::class)
                <a href="{{ Route::has('leads.bulk-upload') ? route('leads.bulk-upload') : '#' }}"
                   class="flex items-center gap-2 px-4 py-2.5 border-2 border-primary-container text-primary-container rounded-lg hover:bg-primary-container/5 transition-colors font-label-sm text-label-sm font-semibold active:scale-95 duration-150">
                    <span class="material-symbols-outlined" style="font-size: 18px;">upload_file</span>
                    Bulk Upload
                </a>
            @endcan
            <a href="{{ Route::has('leads.create') ? route('leads.create') : '#' }}"
               class="bg-primary-container hover:bg-[#e0650c] text-on-primary-container px-6 py-3 rounded-lg font-label-sm text-label-sm font-semibold flex items-center gap-2 transition-colors active:scale-95">
                <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                Add Lead
            </a>
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="bg-[#dcfce7] text-[#166534] px-4 py-3 rounded-lg font-label-sm text-label-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-surface-container-lowest p-md rounded-xl border border-outline-variant flex flex-col lg:flex-row gap-4 items-stretch lg:items-center justify-between">
        <form action="{{ route('admin.leads.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full">

            <div class="relative w-full sm:w-72">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary" style="font-size: 20px;">search</span>
                <input class="w-full pl-10 pr-4 py-2 border border-outline-variant rounded-lg font-label-sm text-label-sm focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none bg-surface-bright"
                       placeholder="Search company or contact..." type="text" name="search" value="{{ request('search') }}">
            </div>

            <select name="status" onchange="this.form.submit()"
                    class="w-full sm:w-44 border border-outline-variant rounded-lg font-label-sm text-label-sm py-2 px-3 bg-surface-bright focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none">
                <option value="">All Status</option>
                @foreach (\App\Enums\LeadStatus::cases() as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="category_id" onchange="this.form.submit()"
                    class="w-full sm:w-44 border border-outline-variant rounded-lg font-label-sm text-label-sm py-2 px-3 bg-surface-bright focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none">
                <option value="">All Categories</option>
                @foreach ($categories ?? [] as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <select name="assigned_to" onchange="this.form.submit()"
                    class="w-full sm:w-48 border border-outline-variant rounded-lg font-label-sm text-label-sm py-2 px-3 bg-surface-bright focus:border-primary-container focus:ring-1 focus:ring-primary-container outline-none">
                <option value="">All Assignees</option>
                <option value="unassigned" @selected(request('assigned_to') === 'unassigned')>Unassigned</option>
                @foreach ($assignableUsers ?? [] as $u)
                    <option value="{{ $u->id }}" @selected((string) request('assigned_to') === (string) $u->id)>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>

            @if (request()->hasAny(['search', 'status', 'category_id', 'assigned_to']))
                <a href="{{ route('leads.index') }}" class="flex items-center gap-1 text-secondary hover:text-error font-label-sm text-label-sm px-3 py-2 whitespace-nowrap">
                    <span class="material-symbols-outlined" style="font-size: 18px;">close</span> Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Leads Table -->
    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-outline-variant bg-surface-bright">
                        <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">Company / Contact</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">Category</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">Status</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">Assigned To</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold">Follow-up Date</th>
                        <th class="py-4 px-6 font-label-sm text-label-sm text-secondary font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/50">
                    @forelse ($leads as $lead)
                        <tr class="hover:bg-surface-container-low transition-colors group">
                            <td class="py-4 px-6">
                                <div class="font-label-sm text-label-sm text-on-surface font-semibold">{{ $lead->company_name }}</div>
                                <div class="text-xs text-secondary mt-0.5">{{ $lead->contact_name }}</div>
                                @if ($lead->email)
                                    <div class="text-xs text-outline mt-0.5">{{ $lead->email }}</div>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-surface-container text-on-surface-variant">
                                    {{ $lead->category->name ?? '—' }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $lead->status->badgeClasses() }}">
                                    {{ $lead->status->label() }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @if ($lead->assignedUser)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-surface-dim flex items-center justify-center text-primary text-xs font-semibold">
                                            {{ Str::of($lead->assignedUser->name)->explode(' ')->map(fn($w) => Str::substr($w, 0, 1))->take(2)->implode('') }}
                                        </div>
                                        <span class="font-label-sm text-label-sm text-on-surface">{{ $lead->assignedUser->name }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-outline italic">Unassigned</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-label-sm text-label-sm text-on-surface">
                                    {{ $lead->follow_up_date?->format('d M, Y') ?? '—' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if ($lead->editableBy(auth()->user()))
                                        <a href="{{ Route::has('leads.edit') ? route('leads.edit', $lead) : '#' }}"
                                           class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endif

                                    @if ($lead->assignableBy(auth()->user()))
                                        <a href="{{ Route::has('leads.assign') ? route('leads.assign', $lead) : '#' }}"
                                           class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
                                            <span class="material-symbols-outlined text-[20px]">person_add</span>
                                        </a>
                                    @endif

                                    @if ($lead->deletableBy(auth()->user()))
                                        <form action="{{ Route::has('leads.destroy') ? route('leads.destroy', $lead) : '#' }}"
                                              method="POST" onsubmit="return confirm('Delete this lead? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-secondary hover:text-error transition-colors rounded-full hover:bg-error/10">
                                                <span class="material-symbols-outlined text-[20px]">delete</span>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-14 px-6 text-center">
                                <div class="flex flex-col items-center gap-2 text-secondary">
                                    <span class="material-symbols-outlined" style="font-size: 32px;">person_search</span>
                                    <span class="font-label-sm text-label-sm">No leads found.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (method_exists($leads, 'links'))
            <div class="mt-auto p-4 border-t border-outline-variant bg-surface-bright flex items-center justify-between">
                <span class="font-label-sm text-label-sm text-secondary">
                    Showing {{ $leads->firstItem() ?? 0 }} to {{ $leads->lastItem() ?? 0 }} of {{ $leads->total() }} leads
                </span>
                <div class="flex gap-1">
                    {{ $leads->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </div>

@endsection