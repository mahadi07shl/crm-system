@extends('admin.layouts.app')

@section('title', 'Category Management - Skils Hut CRM')

@section('content')

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.leads.index') }}" class="p-2 text-secondary hover:text-primary-container transition-colors rounded-full hover:bg-primary-container/10">
            <span class="material-symbols-outlined">arrow_back</span>
        </a>
        <div>
            <h1 class="font-display-lg text-display-lg text-on-surface" style="font-size: 32px;">Category Management</h1>
            <p class="font-body-md text-body-md text-secondary mt-1">Manage the categories/niches leads are grouped under.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-[#dcfce7] text-[#166534] px-4 py-3 rounded-lg font-label-sm text-label-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-error-container text-on-error-container px-4 py-3 rounded-lg font-label-sm text-label-sm">
            {{ session('error') }}
        </div>
    @endif

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

        <!-- ============================= -->
        <!-- Add Category Card             -->
        <!-- ============================= -->
        <div class="lg:col-span-1">
            <form action="{{ route('admin.categories.store') }}" method="POST"
                  class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg flex flex-col gap-md">
                @csrf
                <h2 class="font-headline-lg text-headline-lg text-on-surface" style="font-size: 18px;">Add Category</h2>

                <div class="flex flex-col gap-1.5">
                    <label for="name" class="font-label-sm text-label-sm text-on-surface font-semibold">
                        Category Name <span class="text-error">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           required
                           placeholder="e.g. IT, Real Estate, Education"
                           class="w-full px-4 py-2.5 border rounded-lg font-body-md text-body-md bg-surface-bright outline-none transition-colors
                                  focus:border-primary-container focus:ring-1 focus:ring-primary-container
                                  @error('name') border-error @else border-outline-variant @enderror">
                    @error('name')
                        <span class="text-xs text-error">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                        class="bg-primary-container text-on-primary-container px-6 py-3 rounded-lg font-label-sm text-label-sm font-semibold hover:bg-[#e0650c] active:scale-95 transition-all shadow-md shadow-primary-container/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add</span>
                    Add Category
                </button>
            </form>
        </div>

        <!-- ============================= -->
        <!-- Category List                 -->
        <!-- ============================= -->
        <div class="lg:col-span-2">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-surface-container-low border-b border-outline-variant">
                        <tr>
                            <th class="px-5 py-3 font-label-sm text-label-sm text-secondary font-semibold">Name</th>
                            <th class="px-5 py-3 font-label-sm text-label-sm text-secondary font-semibold">Status</th>
                            <th class="px-5 py-3 font-label-sm text-label-sm text-secondary font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse ($categories as $category)
                            <tr data-row-for="{{ $category->id }}">
                                <!-- Name (view mode) -->
                                <td class="px-5 py-3 font-body-md text-body-md text-on-surface" data-view="{{ $category->id }}">
                                    {{ $category->name }}
                                </td>

                                <!-- Name (edit mode, hidden by default) -->
                                <td class="px-5 py-3 hidden" colspan="3" data-edit="{{ $category->id }}">
                                    <form action="{{ route('admin.categories.update', $category) }}" method="POST"
                                          class="flex items-center gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="name" value="{{ $category->name }}" required
                                               class="flex-1 px-3 py-2 border border-outline-variant rounded-lg font-body-md text-body-md bg-surface-bright outline-none
                                                      focus:border-primary-container focus:ring-1 focus:ring-primary-container">
                                        <button type="submit"
                                                class="px-4 py-2 bg-primary-container text-on-primary-container rounded-lg font-label-sm text-label-sm font-semibold hover:bg-[#e0650c] transition-colors">
                                            Save
                                        </button>
                                        <button type="button" data-cancel-edit="{{ $category->id }}"
                                                class="px-4 py-2 text-secondary hover:bg-surface-variant rounded-lg font-label-sm text-label-sm transition-colors">
                                            Cancel
                                        </button>
                                    </form>
                                </td>

                                <td class="px-5 py-3" data-view="{{ $category->id }}">
                                    @if ($category->status)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-[#dcfce7] text-[#166534] font-label-sm text-label-sm font-semibold">
                                            Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-surface-variant text-secondary font-label-sm text-label-sm font-semibold">
                                            Inactive
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-3 text-right" data-view="{{ $category->id }}">
                                    <div class="flex items-center justify-end gap-1">
                                        <button type="button" data-edit-btn="{{ $category->id }}"
                                                class="p-2 text-secondary hover:text-primary-container hover:bg-primary-container/10 rounded-full transition-colors"
                                                title="Edit">
                                            <span class="material-symbols-outlined" style="font-size: 18px;">edit</span>
                                        </button>

                                        <form action="{{ route('admin.categories.toggle-status', $category) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="p-2 text-secondary hover:text-primary-container hover:bg-primary-container/10 rounded-full transition-colors"
                                                    title="{{ $category->status ? 'Deactivate' : 'Activate' }}">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">
                                                    {{ $category->status ? 'toggle_on' : 'toggle_off' }}
                                                </span>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                              onsubmit="return confirm('Delete category &quot;{{ $category->name }}&quot;? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-secondary hover:text-error hover:bg-error-container/20 rounded-full transition-colors"
                                                    title="Delete">
                                                <span class="material-symbols-outlined" style="font-size: 18px;">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-10 text-center font-body-md text-body-md text-secondary">
                                    No categories yet — add one using the form on the left.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($categories instanceof \Illuminate\Contracts\Pagination\Paginator || $categories instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-md">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection

@push('scripts')
<script>
    (function () {
        document.querySelectorAll('[data-edit-btn]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.editBtn;
                document.querySelectorAll(`[data-view="${id}"]`).forEach(el => el.classList.add('hidden'));
                document.querySelector(`[data-edit="${id}"]`).classList.remove('hidden');
            });
        });

        document.querySelectorAll('[data-cancel-edit]').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.cancelEdit;
                document.querySelectorAll(`[data-view="${id}"]`).forEach(el => el.classList.remove('hidden'));
                document.querySelector(`[data-edit="${id}"]`).classList.add('hidden');
            });
        });
    })();
</script>
@endpush