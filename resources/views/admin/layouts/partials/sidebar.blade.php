<!-- Shared Component: SideNavBar -->
<nav class="h-screen w-64 fixed left-0 top-0 bg-surface-container-lowest border-r border-outline-variant flex flex-col py-lg z-40 hidden md:flex">
    <!-- Brand Header -->
    <div class="px-lg mb-xl flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary flex items-center justify-center font-bold text-xl">
            SH
        </div>
        <div>
            <h1 class="font-headline-lg text-[20px] leading-[24px] text-primary tracking-tight">Skils Hut</h1>
            <p class="font-label-sm text-label-sm text-secondary">CRM Admin</p>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex-1 flex flex-col gap-1 px-sm overflow-y-auto custom-scrollbar">

        <!-- Dashboard -->
        <a href="{{ \Route::has('dashboard') ? route('dashboard') : '#' }}"
           class="flex items-center gap-3 px-4 py-3 rounded-r-full active:scale-95 duration-150 transition-colors
                  {{ request()->routeIs('dashboard')
                        ? 'text-primary border-l-4 border-primary bg-primary-container/10'
                        : 'text-secondary hover:bg-surface-container-low' }}">
            <span class="material-symbols-outlined font-label-sm text-label-sm">space_dashboard</span>
            <span class="font-label-sm text-label-sm">Dashboard</span>
        </a>

        <!-- Leads (collapsible) -->
        @php $leadsActive = request()->routeIs('leads.*'); @endphp
        <div class="sidebar-collapse-group">
            <button type="button" data-sidebar-toggle="leadsMenu"
               class="sidebar-collapse-btn w-full flex items-center justify-between gap-3 px-4 py-3 rounded-r-full active:scale-95 duration-150 transition-colors
                      {{ $leadsActive
                            ? 'text-primary border-l-4 border-primary bg-primary-container/10'
                            : 'text-secondary hover:bg-surface-container-low' }}">
                <span class="flex items-center gap-3">
                    <span class="material-symbols-outlined font-label-sm text-label-sm">person_search</span>
                    <span class="font-label-sm text-label-sm">Leads</span>
                </span>
                <span class="material-symbols-outlined text-[16px] sidebar-collapse-chevron transition-transform {{ $leadsActive ? 'rotate-180' : '' }}">expand_more</span>
            </button>
            <div id="leadsMenu" class="sidebar-collapse-panel flex flex-col gap-1 pl-12 pr-2 mt-1 {{ $leadsActive ? '' : 'hidden' }}">
               <a href="{{ route('admin.leads.index') }}"
   class="flex items-center gap-2 px-3 py-2 rounded-lg font-label-sm text-xs transition-colors
          {{ request()->routeIs('admin.leads.index')
                ? 'text-primary bg-primary-container/10'
                : 'text-secondary hover:bg-surface-container-low' }}">
    <span class="material-symbols-outlined text-[16px]">format_list_bulleted</span>
    All Leads
</a>
                <a href="{{ \Route::has('') ? route('') : '#' }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-label-sm text-xs transition-colors
                          {{ request()->routeIs('admin.leads.create')
                                ? 'text-primary bg-primary-container/10'
                                : 'text-secondary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined text-[16px]">assignment_ind</span>
                    Lead Assignment
                </a>
                <a href="{{ \Route::has('admin.categories.index') ? route('admin.categories.index') : '#' }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-label-sm text-xs transition-colors
                          {{ request()->routeIs('admin.categories.index')
                                ? 'text-primary bg-primary-container/10'
                                : 'text-secondary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined text-[16px]">route</span>
                    Lead Sources
                </a>
            </div>
        </div>

        <!-- Deals / Pipeline (collapsible) -->
        @php $dealsActive = request()->routeIs('deals.*'); @endphp
        <div class="sidebar-collapse-group">
            <button type="button" data-sidebar-toggle="dealsMenu"
               class="sidebar-collapse-btn w-full flex items-center justify-between gap-3 px-4 py-3 rounded-r-full active:scale-95 duration-150 transition-colors
                      {{ $dealsActive
                            ? 'text-primary border-l-4 border-primary bg-primary-container/10'
                            : 'text-secondary hover:bg-surface-container-low' }}">
                <span class="flex items-center gap-3">
                    <span class="material-symbols-outlined font-label-sm text-label-sm">handshake</span>
                    <span class="font-label-sm text-label-sm">Deals</span>
                </span>
                <span class="material-symbols-outlined text-[16px] sidebar-collapse-chevron transition-transform {{ $dealsActive ? 'rotate-180' : '' }}">expand_more</span>
            </button>
            <div id="dealsMenu" class="sidebar-collapse-panel flex flex-col gap-1 pl-12 pr-2 mt-1 {{ $dealsActive ? '' : 'hidden' }}">
                <a href="{{ \Route::has('deals.index') ? route('deals.index') : '#' }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-label-sm text-xs transition-colors
                          {{ request()->routeIs('deals.index')
                                ? 'text-primary bg-primary-container/10'
                                : 'text-secondary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined text-[16px]">view_kanban</span>
                    Pipeline Board
                </a>
                <a href="{{ \Route::has('deals.won') ? route('deals.won') : '#' }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-label-sm text-xs transition-colors
                          {{ request()->routeIs('deals.won')
                                ? 'text-primary bg-primary-container/10'
                                : 'text-secondary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined text-[16px]">emoji_events</span>
                    Won Deals
                </a>
                <a href="{{ \Route::has('deals.lost') ? route('deals.lost') : '#' }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-label-sm text-xs transition-colors
                          {{ request()->routeIs('deals.lost')
                                ? 'text-primary bg-primary-container/10'
                                : 'text-secondary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined text-[16px]">cancel</span>
                    Lost Deals
                </a>
            </div>
        </div>

        <!-- Contacts (collapsible) -->
        @php $contactsActive = request()->routeIs('contacts.*'); @endphp
        <div class="sidebar-collapse-group">
            <button type="button" data-sidebar-toggle="contactsMenu"
               class="sidebar-collapse-btn w-full flex items-center justify-between gap-3 px-4 py-3 rounded-r-full active:scale-95 duration-150 transition-colors
                      {{ $contactsActive
                            ? 'text-primary border-l-4 border-primary bg-primary-container/10'
                            : 'text-secondary hover:bg-surface-container-low' }}">
                <span class="flex items-center gap-3">
                    <span class="material-symbols-outlined font-label-sm text-label-sm">contacts</span>
                    <span class="font-label-sm text-label-sm">Contacts</span>
                </span>
                <span class="material-symbols-outlined text-[16px] sidebar-collapse-chevron transition-transform {{ $contactsActive ? 'rotate-180' : '' }}">expand_more</span>
            </button>
            <div id="contactsMenu" class="sidebar-collapse-panel flex flex-col gap-1 pl-12 pr-2 mt-1 {{ $contactsActive ? '' : 'hidden' }}">
                <a href="{{ \Route::has('contacts.companies') ? route('contacts.companies') : '#' }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-label-sm text-xs transition-colors
                          {{ request()->routeIs('contacts.companies')
                                ? 'text-primary bg-primary-container/10'
                                : 'text-secondary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined text-[16px]">apartment</span>
                    Companies
                </a>
                <a href="{{ \Route::has('contacts.people') ? route('contacts.people') : '#' }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-lg font-label-sm text-xs transition-colors
                          {{ request()->routeIs('contacts.people')
                                ? 'text-primary bg-primary-container/10'
                                : 'text-secondary hover:bg-surface-container-low' }}">
                    <span class="material-symbols-outlined text-[16px]">person</span>
                    People
                </a>
            </div>
        </div>

        <!-- Categories -->
        <a href="{{ \Route::has('categories.index') ? route('categories.index') : '#' }}"
           class="flex items-center gap-3 px-4 py-3 rounded-r-full active:scale-95 duration-150 transition-colors
                  {{ request()->routeIs('categories.*')
                        ? 'text-primary border-l-4 border-primary bg-primary-container/10'
                        : 'text-secondary hover:bg-surface-container-low' }}">
            <span class="material-symbols-outlined font-label-sm text-label-sm">category</span>
            <span class="font-label-sm text-label-sm">Categories</span>
        </a>

        <!-- Users -->
        <a href="{{ \Route::has('admin.staffs.index') ? route('admin.staffs.index') : '#' }}"
           class="flex items-center gap-3 px-4 py-3 rounded-r-full active:scale-95 duration-150 transition-colors
                  {{ request()->routeIs('staffs.*')
                        ? 'text-primary border-l-4 border-primary bg-primary-container/10'
                        : 'text-secondary hover:bg-surface-container-low' }}">
            <span class="material-symbols-outlined font-label-sm text-label-sm">group</span>
            <span class="font-label-sm text-label-sm">Users</span>
        </a>

        <!-- Reports -->
        <a href="{{ \Route::has('reports.index') ? route('reports.index') : '#' }}"
           class="flex items-center gap-3 px-4 py-3 rounded-r-full active:scale-95 duration-150 transition-colors
                  {{ request()->routeIs('reports.*')
                        ? 'text-primary border-l-4 border-primary bg-primary-container/10'
                        : 'text-secondary hover:bg-surface-container-low' }}">
            <span class="material-symbols-outlined font-label-sm text-label-sm">bar_chart</span>
            <span class="font-label-sm text-label-sm">Reports</span>
        </a>

    </div>

    <!-- New Lead CTA -->
    <div class="px-sm mb-4">
        <a href="{{ \Route::has('leads.create') ? route('leads.create') : '#' }}"
           class="w-full flex items-center justify-center gap-2 bg-primary-container hover:bg-[#c25a11] text-on-primary font-label-sm text-sm py-2.5 px-4 rounded-lg transition-colors active:scale-[0.98]">
            <span class="material-symbols-outlined text-[18px]">add</span>
            New Lead
        </a>
    </div>

    <!-- Footer Tabs -->
    <div class="flex flex-col gap-1 px-sm border-t border-outline-variant pt-4">
        <a href="{{ \Route::has('settings.index') ? route('settings.index') : '#' }}"
           class="flex items-center gap-3 px-4 py-3 rounded-r-full active:scale-95 duration-150 transition-colors
                  {{ request()->routeIs('settings.*')
                        ? 'text-primary border-l-4 border-primary bg-primary-container/10'
                        : 'text-secondary hover:bg-surface-container-low' }}">
            <span class="material-symbols-outlined font-label-sm text-label-sm">settings</span>
            <span class="font-label-sm text-label-sm">Settings</span>
        </a>
        <form method="POST" action="{{ \Route::has('logout') ? route('logout') : '#' }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-secondary hover:bg-surface-container-low transition-colors rounded-r-full active:scale-95 duration-150">
                <span class="material-symbols-outlined font-label-sm text-label-sm">logout</span>
                <span class="font-label-sm text-label-sm">Logout</span>
            </button>
        </form>
    </div>
</nav>

<script>
    // Vanilla-JS collapse for sidebar sub-menus (no external JS library needed)
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-sidebar-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const panel = document.getElementById(btn.getAttribute('data-sidebar-toggle'));
                const chevron = btn.querySelector('.sidebar-collapse-chevron');
                if (!panel) return;
                panel.classList.toggle('hidden');
                if (chevron) chevron.classList.toggle('rotate-180');
            });
        });
    });
</script>