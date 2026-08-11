@extends('admin.layouts.app')

@section('title', 'Dashboard - Skils Hut CRM')

@section('content')

    @php
        // Fallback sample data so the view renders standalone.
        // In production, pass these from the DashboardController.
        $adminName = $adminName ?? auth()->user()->name ?? 'Admin';

        $stats = $stats ?? [
            [
                'label' => 'New Leads Today',
                'value' => '18',
                'icon' => 'person_add',
                'delta' => '+12%',
                'trend' => 'up',
                'context' => 'vs. yesterday',
            ],
            [
                'label' => 'Conversion Rate',
                'value' => '24.6%',
                'icon' => 'trending_up',
                'delta' => '+3.1%',
                'trend' => 'up',
                'context' => 'last 30 days',
            ],
            [
                'label' => 'Active Deals',
                'value' => '146',
                'icon' => 'handshake',
                'delta' => '-4',
                'trend' => 'down',
                'context' => 'vs. last week',
            ],
            [
                'label' => 'Revenue This Month',
                'value' => '$84.2k',
                'icon' => 'payments',
                'delta' => '+9.8%',
                'trend' => 'up',
                'context' => 'vs. last month',
            ],
        ];

        // Simple 7-day lead volume, used to draw a lightweight CSS bar chart.
        $chartData = $chartData ?? [
            ['label' => 'Mon', 'value' => 22],
            ['label' => 'Tue', 'value' => 35],
            ['label' => 'Wed', 'value' => 28],
            ['label' => 'Thu', 'value' => 41],
            ['label' => 'Fri', 'value' => 38],
            ['label' => 'Sat', 'value' => 14],
            ['label' => 'Sun', 'value' => 18],
        ];
        $chartMax = collect($chartData)->max('value');

        $activities = $activities ?? [
            [
                'icon' => 'assignment_ind',
                'style' => 'bg-secondary-container text-on-secondary-container',
                'text' => 'Elena Rostova was assigned 2 new leads from Acme Corp Solutions.',
                'time' => '12 min ago',
            ],
            [
                'icon' => 'task_alt',
                'style' => 'bg-tertiary-fixed text-on-tertiary-fixed',
                'text' => 'Deal with NovaTech Industries moved to Negotiation stage.',
                'time' => '48 min ago',
            ],
            [
                'icon' => 'person_add',
                'style' => 'bg-secondary-container text-on-secondary-container',
                'text' => 'New inbound lead captured: Global Logistics Inc.',
                'time' => '1 hour ago',
            ],
            [
                'icon' => 'mail',
                'style' => 'bg-tertiary-fixed text-on-tertiary-fixed',
                'text' => 'Marcus Thorne sent a follow-up proposal to Bright Path Retail.',
                'time' => '3 hours ago',
            ],
        ];

        $topAgents = $topAgents ?? [
            ['name' => 'Marcus Thorne', 'role' => 'Senior AE', 'closed' => 14, 'pct' => 90],
            ['name' => 'Elena Rostova', 'role' => 'Inbound Specialist', 'closed' => 11, 'pct' => 70],
            ['name' => 'James Teller', 'role' => 'Enterprise Sales', 'closed' => 9, 'pct' => 55],
        ];
    @endphp

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-xl gap-4">
        <div>
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-on-background mb-2">
                Good to see you, {{ $adminName }}
            </h2>
            <p class="font-body-md text-body-md text-secondary">Here's what's happening across your pipeline today.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ \Route::has('leads.index') ? route('leads.index') : '#' }}"
               class="flex items-center gap-2 bg-primary-container hover:bg-[#c25a11] text-on-primary font-label-sm text-sm py-2.5 px-4 rounded-lg transition-colors active:scale-[0.98]">
                <span class="material-symbols-outlined text-[18px]">person_search</span>
                Assign Leads
            </a>
        </div>
    </div>

    <!-- KPI Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-gutter mb-gutter">
        @foreach ($stats as $stat)
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-5 flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-lg bg-primary-container/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">{{ $stat['icon'] }}</span>
                    </div>
                    <span class="flex items-center gap-1 font-label-sm text-xs {{ $stat['trend'] === 'up' ? 'text-primary' : 'text-error' }}">
                        <span class="material-symbols-outlined text-[14px]">
                            {{ $stat['trend'] === 'up' ? 'arrow_upward' : 'arrow_downward' }}
                        </span>
                        {{ $stat['delta'] }}
                    </span>
                </div>
                <div>
                    <p class="font-headline-lg text-[26px] leading-[32px] text-on-background">{{ $stat['value'] }}</p>
                    <p class="font-body-md text-sm text-secondary mt-1">{{ $stat['label'] }}</p>
                </div>
                <p class="font-body-md text-xs text-secondary-fixed-dim">{{ $stat['context'] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">

        <!-- Left Column (Span 8) -->
        <div class="lg:col-span-8 flex flex-col gap-gutter">

            <!-- Leads Over Time Chart -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-headline-lg text-[20px] leading-[26px] text-on-background">Leads This Week</h3>
                        <p class="font-body-md text-sm text-secondary mt-1">New inbound &amp; sourced leads by day</p>
                    </div>
                    <span class="px-3 py-1.5 rounded-full bg-surface-container font-label-sm text-xs text-primary">
                        {{ collect($chartData)->sum('value') }} total
                    </span>
                </div>
                <div class="flex items-end justify-between gap-3 h-40">
                    @foreach ($chartData as $day)
                        @php $heightPct = $chartMax > 0 ? round(($day['value'] / $chartMax) * 100) : 0; @endphp
                        <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                            <span class="font-label-sm text-xs text-secondary opacity-0 group-hover:opacity-100 transition-opacity">
                                {{ $day['value'] }}
                            </span>
                            <div class="w-full bg-surface-container-high rounded-t-md overflow-hidden flex items-end" style="height: 100%;">
                                <div class="w-full bg-primary-container rounded-t-md transition-all" style="height: {{ $heightPct }}%;"></div>
                            </div>
                            <span class="font-label-sm text-xs text-secondary">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                <h3 class="font-headline-lg text-[20px] leading-[26px] text-on-background mb-4">Recent Activity</h3>
                <div class="flex flex-col">
                    @foreach ($activities as $activity)
                        <div class="flex items-start gap-4 py-3 {{ !$loop->last ? 'border-b border-outline-variant' : '' }}">
                            <div class="w-9 h-9 rounded-full {{ $activity['style'] }} flex items-center justify-center shrink-0">
                                <span class="material-symbols-outlined text-[18px]">{{ $activity['icon'] }}</span>
                            </div>
                            <div class="flex-1">
                                <p class="font-body-md text-sm text-on-background">{{ $activity['text'] }}</p>
                                <p class="font-label-sm text-xs text-secondary mt-1">{{ $activity['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right Column (Span 4) -->
        <div class="lg:col-span-4 flex flex-col gap-gutter">

            <!-- Top Performers -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                <h3 class="font-headline-lg text-[20px] leading-[26px] text-on-background mb-4">Top Performers</h3>
                <div class="flex flex-col gap-4">
                    @foreach ($topAgents as $agent)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <div>
                                    <p class="font-label-sm text-sm text-on-background">{{ $agent['name'] }}</p>
                                    <p class="font-body-md text-xs text-secondary">{{ $agent['role'] }}</p>
                                </div>
                                <span class="font-label-sm text-xs text-primary">{{ $agent['closed'] }} closed</span>
                            </div>
                            <div class="w-full bg-surface-container-high rounded-full h-1.5 overflow-hidden">
                                <div class="bg-primary-container h-1.5 rounded-full" style="width: {{ $agent['pct'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6">
                <h3 class="font-headline-lg text-[20px] leading-[26px] text-on-background mb-4">Quick Actions</h3>
                <div class="flex flex-col gap-2">
                    <a href="{{ \Route::has('leads.index') ? route('leads.index') : '#' }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg border border-outline-variant text-on-background hover:bg-surface transition-colors font-label-sm text-sm">
                        <span class="material-symbols-outlined text-[18px] text-primary">person_search</span>
                        Assign unassigned leads
                    </a>
                    <a href="{{ \Route::has('users.create') ? route('users.create') : '#' }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg border border-outline-variant text-on-background hover:bg-surface transition-colors font-label-sm text-sm">
                        <span class="material-symbols-outlined text-[18px] text-primary">person_add</span>
                        Invite a team member
                    </a>
                    <a href="{{ \Route::has('categories.index') ? route('categories.index') : '#' }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg border border-outline-variant text-on-background hover:bg-surface transition-colors font-label-sm text-sm">
                        <span class="material-symbols-outlined text-[18px] text-primary">category</span>
                        Manage lead categories
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection