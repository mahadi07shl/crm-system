@extends('admin.layouts.app')

@section('title', 'Add Lead - Skils Hut CRM')

@section('content')

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.leads.index') }}" class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-display-lg text-on-surface" style="font-size: 32px;">Add Lead</h1>
            <p class="font-body-md text-body-md text-secondary mt-1">Add a single lead manually, or import many at once via CSV/XLSX.</p>
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

    @if (session('success'))
        <div class="bg-[#dcfce7] text-[#166534] px-4 py-3 rounded-lg font-label-sm text-label-sm">
            {{ session('success') }}
        </div>
    @endif

    @php
        // SRS section 3: only Admin/Supervisor can bulk upload.
        // Roles are stored capitalized in the DB (Admin, Supervisor, User), so compare case-insensitively.
        $canBulkUpload = in_array(strtolower(auth()->user()->role ?? ''), ['admin', 'supervisor'], true);
        // If a previous submit failed on the bulk-upload form, land back on that tab.
        $activeTab = old('_active_tab', request('tab', 'manual'));
    @endphp

    <!-- Tabs -->
    <div class="flex gap-2 border-b border-outline-variant max-w-3xl">
        <button type="button" data-tab-btn="manual"
                class="tab-btn px-5 py-3 font-label-sm text-label-sm font-semibold border-b-2 transition-colors">
            <span class="inline-flex items-center gap-2">
                <span class="material-symbols-outlined" style="font-size: 18px;">person_add</span>
                Manual Entry
            </span>
        </button>

        @if ($canBulkUpload)
            <button type="button" data-tab-btn="bulk"
                    class="tab-btn px-5 py-3 font-label-sm text-label-sm font-semibold border-b-2 transition-colors">
                <span class="inline-flex items-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">upload_file</span>
                    Bulk Upload
                </span>
            </button>
        @endif
    </div>

    <!-- ============================= -->
    <!-- Tab 1: Manual Entry           -->
    <!-- ============================= -->
    <div data-tab-panel="manual" class="max-w-3xl">
        <form action="{{ route('admin.leads.store') }}" method="POST" class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col gap-lg">
            @csrf
            <input type="hidden" name="_active_tab" value="manual">

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
                      
                    </select>
                    @error('category_id')
                        <span class="text-xs text-error">{{ $message }}</span>
                    @enderror
                  
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
    </div>

    <!-- ============================= -->
    <!-- Tab 2: Bulk Upload            -->
    <!-- ============================= -->
    @if ($canBulkUpload)
        <div data-tab-panel="bulk" class="max-w-5xl hidden">

            <div class="flex items-end justify-between mb-4 flex-wrap gap-3">
                <p class="font-body-md text-body-md text-secondary">Import new leads efficiently via CSV or XLSX files.</p>
                <a href="{{ route('admin.leads.bulk-upload.template') ?? '#' }}"
                   class="flex items-center gap-2 px-4 py-2.5 border-2 border-primary-container text-primary-container rounded-lg hover:bg-primary-container/5 transition-colors font-label-sm text-label-sm font-semibold active:scale-95 duration-150">
                    <span class="material-symbols-outlined" style="font-size: 18px;">download</span>
                    Download Template
                </a>
            </div>

            <form action="{{ route('admin.leads.bulk-upload.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-lg">
                @csrf
                <input type="hidden" name="_active_tab" value="bulk">

                <!-- Dropzone -->
                <div id="dropzone"
                     class="bg-surface-container-lowest border-2 border-dashed border-outline-variant hover:border-primary-container transition-colors rounded-xl p-xl flex flex-col items-center justify-center text-center group cursor-pointer relative overflow-hidden min-h-[280px]">

                    <div id="dropzone-empty-state" class="flex flex-col items-center">
                        <div class="w-16 h-16 bg-surface-container-low rounded-full flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                            <span class="material-symbols-outlined text-primary-container" style="font-size: 32px;">cloud_upload</span>
                        </div>
                        <h3 class="font-headline-lg text-headline-lg text-on-surface mb-1" style="font-size: 20px; line-height: 28px;">
                            Drag &amp; drop your file here
                        </h3>
                        <p class="font-body-md text-body-md text-secondary mb-4">or click to browse from your computer</p>
                        <button type="button" id="browse-btn"
                                class="bg-primary-container text-on-primary-container px-6 py-2.5 rounded-lg font-label-sm text-label-sm font-semibold hover:bg-[#e0650c] active:scale-95 transition-all shadow-sm">
                            Browse Files
                        </button>
                        <p class="font-label-sm text-label-sm text-outline mt-4">Supported formats: .csv, .xlsx. Maximum file size: 50MB.</p>
                    </div>

                    <div id="dropzone-file-state" class="hidden w-full max-w-md">
                        <div class="w-14 h-14 mx-auto bg-[#107c41]/10 text-[#107c41] rounded-lg flex items-center justify-center mb-3">
                            <span class="material-symbols-outlined" style="font-size: 28px;">description</span>
                        </div>
                        <h4 id="selected-file-name" class="font-body-md text-body-md font-semibold text-on-surface mb-1"></h4>
                        <p id="selected-file-size" class="font-label-sm text-label-sm text-secondary mb-3"></p>
                        <button type="button" id="remove-file-btn"
                                class="inline-flex items-center gap-1 text-secondary hover:text-error font-label-sm text-label-sm transition-colors">
                            <span class="material-symbols-outlined" style="font-size: 16px;">close</span>
                            Remove file
                        </button>
                    </div>

                    <input type="file" id="file-input" name="file" accept=".csv,.xlsx" class="hidden">
                </div>
                @error('file')
                    <span class="text-xs text-error">{{ $message }}</span>
                @enderror

                <!-- Default category for the batch -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col gap-1.5 max-w-md">
                    <label for="bulk_category_id" class="font-label-sm text-label-sm text-on-surface font-semibold">
                        Default Category <span class="text-error">*</span>
                    </label>
                    <select id="bulk_category_id" name="category_id"
                            class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                                   focus:border-primary-container focus:ring-1 focus:ring-primary-container
                                   @error('category_id') border-error @else border-outline-variant @enderror">
                        <option value="" disabled selected>Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-secondary mt-1">Applied to every lead in this file unless your sheet has its own category column mapped.</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-md pt-md border-t border-outline-variant">
                    <a href="{{ route('admin.leads.index') }}"
                       class="px-6 py-3 rounded-lg font-label-sm text-label-sm text-secondary hover:bg-surface-variant transition-colors">
                        Cancel
                    </a>
                    <button type="submit" id="submit-bulk-btn" disabled
                            class="bg-primary-container text-on-primary-container px-8 py-3 rounded-lg font-label-sm text-label-sm font-semibold
                                   hover:bg-[#e0650c] active:scale-95 transition-all shadow-md shadow-primary-container/20 flex items-center gap-2
                                   disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-primary-container">
                        <span class="material-symbols-outlined" style="font-size: 18px;">play_arrow</span>
                        Start Import
                    </button>
                </div>
            </form>
        </div>
    @endif

@endsection

@push('styles')
<style>
    .tab-btn { color: var(--tw-text-opacity, 1); }
    .tab-btn.tab-active {
        border-color: #9d4300;
        color: #9d4300;
    }
    .tab-btn:not(.tab-active) {
        border-color: transparent;
        color: #545f73;
    }
    .tab-btn:not(.tab-active):hover {
        color: #9d4300;
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        // ---------- Tabs ----------
        const tabButtons = document.querySelectorAll('[data-tab-btn]');
        const tabPanels  = document.querySelectorAll('[data-tab-panel]');
        const initialTab = @json($activeTab);

        function activateTab(name) {
            tabButtons.forEach(btn => {
                btn.classList.toggle('tab-active', btn.dataset.tabBtn === name);
            });
            tabPanels.forEach(panel => {
                panel.classList.toggle('hidden', panel.dataset.tabPanel !== name);
            });
        }

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => activateTab(btn.dataset.tabBtn));
        });

        activateTab(document.querySelector(`[data-tab-panel="${initialTab}"]`) ? initialTab : 'manual');

        // ---------- Dropzone (only present if Bulk Upload tab exists) ----------
        const dropzone = document.getElementById('dropzone');
        if (dropzone) {
            const fileInput     = document.getElementById('file-input');
            const browseBtn     = document.getElementById('browse-btn');
            const removeBtn     = document.getElementById('remove-file-btn');
            const emptyState    = document.getElementById('dropzone-empty-state');
            const fileState     = document.getElementById('dropzone-file-state');
            const fileNameLabel = document.getElementById('selected-file-name');
            const fileSizeLabel = document.getElementById('selected-file-size');
            const submitBtn     = document.getElementById('submit-bulk-btn');

            function formatBytes(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
            }

            function showFile(file) {
                fileNameLabel.textContent = file.name;
                fileSizeLabel.textContent = formatBytes(file.size);
                emptyState.classList.add('hidden');
                fileState.classList.remove('hidden');
                submitBtn.disabled = false;
            }

            function clearFile() {
                fileInput.value = '';
                emptyState.classList.remove('hidden');
                fileState.classList.add('hidden');
                submitBtn.disabled = true;
            }

            dropzone.addEventListener('click', (e) => {
                if (e.target === removeBtn || removeBtn.contains(e.target)) return;
                fileInput.click();
            });

            fileInput.addEventListener('change', () => {
                if (fileInput.files.length) showFile(fileInput.files[0]);
            });

            removeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                clearFile();
            });

            ['dragenter', 'dragover'].forEach(evt => {
                dropzone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('border-primary-container');
                });
            });

            ['dragleave', 'drop'].forEach(evt => {
                dropzone.addEventListener(evt, (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('border-primary-container');
                });
            });

            dropzone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files.length) {
                    fileInput.files = files;
                    showFile(files[0]);
                }
            });
        }
    })();
</script>
@endpush