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
            <a href="{{ Route::has('admin.leads.create') ? route('admin.leads.create') : '#' }}"
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
               
            </table>
        </div>

       
    </div>

@endsection