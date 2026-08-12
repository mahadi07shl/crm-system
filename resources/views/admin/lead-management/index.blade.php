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
            @can('bulkUpload', App\Models\LeadManagement::class)
                <a href="{{ Route::has('admin.leads.bulk-upload') ? route('admin.leads.bulk-upload') : '#' }}"
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
    <div id="flash-message-container">
        @if (session('success'))
            <div class="bg-[#dcfce7] text-[#166534] px-4 py-3 rounded-lg font-label-sm text-label-sm">
                {{ session('success') }}
            </div>
        @endif
    </div>

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
                <a href="{{ route('admin.leads.index') }}" class="flex items-center gap-1 text-secondary hover:text-error font-label-sm text-label-sm px-3 py-2 whitespace-nowrap">
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
                        <tr class="hover:bg-surface-container-low transition-colors group" data-lead-row="{{ $lead->id }}">
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

                            <!-- Assigned To cell — updated live by JS after modal submit -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-2" data-assigned-cell>
                                    <button type="button"
                                            onclick="openAssignModal({{ $lead->id }}, {{ Illuminate\Support\Js::from($lead->company_name) }}, {{ $lead->assigned_to ?? 'null' }})"
                                            class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10"
                                            title="{{ $lead->assignedUser ? 'Reassign' : 'Assign' }}">
                                        <span class="material-symbols-outlined text-[20px]" data-assign-icon>
                                            {{ $lead->assignedUser ? 'sync_alt' : 'person_add' }}
                                        </span>
                                    </button>

                                    <span class="font-label-sm text-label-sm text-on-surface" data-assigned-name style="{{ $lead->assignedUser ? '' : 'display:none' }}">
                                        {{ $lead->assignedUser->name ?? '' }}
                                    </span>
                                    <span class="text-xs text-outline italic" data-unassigned-label style="{{ $lead->assignedUser ? 'display:none' : '' }}">
                                        Unassigned
                                    </span>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <span class="font-label-sm text-label-sm text-on-surface">
                                    {{ $lead->follow_up_date?->format('d M, Y') ?? '—' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if ($lead->editableBy(auth()->user()))
                                        <a href="{{ Route::has('admin.leads.edit') ? route('admin.leads.edit', $lead) : '#' }}"
                                           class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
                                            <span class="material-symbols-outlined text-[20px]">edit</span>
                                        </a>
                                    @endif

                                    @if ($lead->deletableBy(auth()->user()))
                                        <form action="{{ Route::has('admin.leads.destroy') ? route('admin.leads.destroy', $lead) : '#' }}"
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

    <!-- ============================= -->
    <!-- Assign User Modal             -->
    <!-- ============================= -->
    <div id="assign-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 p-4">
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant w-full max-w-sm p-lg flex flex-col gap-md">
            <div class="flex items-center justify-between">
                <h3 class="font-headline-lg text-headline-lg text-on-surface" style="font-size: 18px;">Assign Lead</h3>
                <button type="button" onclick="closeAssignModal()" class="p-1 text-secondary hover:text-error rounded-full hover:bg-error/10">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <p class="font-body-md text-body-md text-secondary" style="font-size: 14px;">
                Assign <span id="assign-modal-lead-name" class="font-semibold text-on-surface"></span> to:
            </p>

           <form id="assign-form" class="flex flex-col gap-md" data-url-template="{{ route('admin.admin.leads.assign.store', ['lead' => '__LEAD_ID__']) }}">
                @csrf
                <select id="assign-user-select" name="assigned_to" required
                        class="w-full px-4 py-2.5 border border-outline-variant rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                               focus:border-primary-container focus:ring-1 focus:ring-primary-container">
                    <option value="" disabled selected>Select a user</option>
                    @foreach ($assignableUsers ?? [] as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                    @endforeach
                </select>

                <span id="assign-error" class="text-xs text-error hidden"></span>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeAssignModal()"
                            class="px-4 py-2 rounded-lg font-label-sm text-label-sm text-secondary hover:bg-surface-variant transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="assign-submit-btn"
                            class="bg-primary-container text-on-primary-container px-5 py-2 rounded-lg font-label-sm text-label-sm font-semibold hover:bg-[#e0650c] transition-colors">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let currentAssignLeadId = null;

    function openAssignModal(leadId, leadName, currentAssignedTo) {
        currentAssignLeadId = leadId;
        document.getElementById('assign-modal-lead-name').textContent = leadName;
        document.getElementById('assign-user-select').value = currentAssignedTo ?? '';
        document.getElementById('assign-error').classList.add('hidden');
        document.getElementById('assign-modal').classList.remove('hidden');
        document.getElementById('assign-modal').classList.add('flex');
    }

    function closeAssignModal() {
        document.getElementById('assign-modal').classList.add('hidden');
        document.getElementById('assign-modal').classList.remove('flex');
        currentAssignLeadId = null;
    }

    document.getElementById('assign-form').addEventListener('submit', function (e) {
        e.preventDefault();

        const select = document.getElementById('assign-user-select');
        const errorEl = document.getElementById('assign-error');
        const submitBtn = document.getElementById('assign-submit-btn');

        if (!select.value) {
            errorEl.textContent = 'Please select a user.';
            errorEl.classList.remove('hidden');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Assigning...';

        // Build the assign URL for this specific lead.
       const url = document.getElementById('assign-form').dataset.urlTemplate.replace('__LEAD_ID__', currentAssignLeadId);

       fetch(url, {
    method: 'POST',   // ছিল 'PATCH' — route POST হিসেবে রেজিস্টার করা, তাই মিলিয়ে দিলাম
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            ?? document.querySelector('input[name="_token"]').value,
    },
    body: JSON.stringify({ assigned_to: select.value }),
})
            .then(async (response) => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Something went wrong.');
                }
                return data;
            })
            .then((data) => {
                // Update the row in place — no page reload.
                const row = document.querySelector(`tr[data-lead-row="${data.lead_id}"]`);
                if (row) {
                    row.querySelector('[data-assigned-name]').textContent = data.assignee_name;
                    row.querySelector('[data-assigned-name]').style.display = '';
                    row.querySelector('[data-unassigned-label]').style.display = 'none';
                    row.querySelector('[data-assign-icon]').textContent = 'sync_alt';
                }

                showFlashMessage(data.message);
                closeAssignModal();
            })
            .catch((err) => {
                errorEl.textContent = err.message;
                errorEl.classList.remove('hidden');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Assign';
            });
    });

    function showFlashMessage(message) {
        const container = document.getElementById('flash-message-container');
        container.innerHTML = `
            <div class="bg-[#dcfce7] text-[#166534] px-4 py-3 rounded-lg font-label-sm text-label-sm">
                ${message}
            </div>`;
        setTimeout(() => { container.innerHTML = ''; }, 4000);
    }

    // Close modal when clicking the dark backdrop
    document.getElementById('assign-modal').addEventListener('click', function (e) {
        if (e.target === this) closeAssignModal();
    });
</script>
@endpush