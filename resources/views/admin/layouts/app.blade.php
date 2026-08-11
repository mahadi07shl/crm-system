<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>@yield('title', 'Skils Hut CRM')</title>

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<!-- Tailwind Config -->
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "outline-variant": "#e0c0b1",
                    "error": "#ba1a1a",
                    "tertiary": "#735c00",
                    "on-primary": "#ffffff",
                    "primary-fixed-dim": "#ffb690",
                    "secondary-container": "#d5e0f8",
                    "on-tertiary-container": "#4e3e00",
                    "tertiary-fixed": "#ffe083",
                    "surface-container-high": "#dce9ff",
                    "inverse-primary": "#ffb690",
                    "primary-fixed": "#ffdbca",
                    "primary-container": "#f97316",
                    "surface": "#f8f9ff",
                    "surface-tint": "#9d4300",
                    "on-secondary": "#ffffff",
                    "tertiary-fixed-dim": "#eec200",
                    "secondary-fixed": "#d8e3fb",
                    "on-primary-fixed-variant": "#783200",
                    "secondary-fixed-dim": "#bcc7de",
                    "on-secondary-container": "#586377",
                    "on-error-container": "#93000a",
                    "surface-variant": "#d3e4fe",
                    "error-container": "#ffdad6",
                    "surface-container": "#e5eeff",
                    "secondary": "#545f73",
                    "on-tertiary-fixed": "#231b00",
                    "on-secondary-fixed": "#111c2d",
                    "surface-bright": "#f8f9ff",
                    "on-error": "#ffffff",
                    "tertiary-container": "#cea700",
                    "on-surface-variant": "#584237",
                    "on-tertiary-fixed-variant": "#574500",
                    "on-secondary-fixed-variant": "#3c475a",
                    "inverse-surface": "#213145",
                    "inverse-on-surface": "#eaf1ff",
                    "on-primary-fixed": "#341100",
                    "background": "#f8f9ff",
                    "on-background": "#0b1c30",
                    "surface-container-low": "#eff4ff",
                    "surface-dim": "#cbdbf5",
                    "primary": "#9d4300",
                    "surface-container-highest": "#d3e4fe",
                    "outline": "#8c7164",
                    "surface-container-lowest": "#ffffff",
                    "on-primary-container": "#582200",
                    "on-surface": "#0b1c30",
                    "on-tertiary": "#ffffff"
                },
                "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
                },
                "spacing": {
                    "lg": "24px",
                    "margin-desktop": "48px",
                    "gutter": "24px",
                    "xl": "40px",
                    "md": "16px",
                    "margin-mobile": "16px",
                    "sm": "8px",
                    "base": "4px",
                    "xs": "4px"
                },
                "fontFamily": {
                    "label-sm": ["Hanken Grotesk"],
                    "headline-lg": ["Hanken Grotesk"],
                    "body-md": ["Hanken Grotesk"],
                    "headline-lg-mobile": ["Hanken Grotesk"],
                    "display-lg": ["Hanken Grotesk"]
                },
                "fontSize": {
                    "label-sm": ["13px", { "lineHeight": "16px", "letterSpacing": "0.02em", "fontWeight": "500" }],
                    "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                    "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                    "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                    "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }]
                }
            }
        }
    }
</script>

<style>
    body { font-family: 'Hanken Grotesk', sans-serif; }

    /* Required utility class for Material Symbols icons to render as glyphs
       instead of raw ligature text (e.g. "space_dashboard" showing as plain words).
       The Google Fonts <link> only loads the font file — it does NOT define this class. */
    .material-symbols-outlined {
        font-family: 'Material Symbols Outlined' !important;
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        line-height: 1;
        letter-spacing: normal;
        text-transform: none;
        display: inline-block;
        white-space: nowrap;
        word-wrap: normal;
        direction: ltr;
        -webkit-font-feature-settings: 'liga';
        -webkit-font-smoothing: antialiased;
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }

    /* Checkbox styling override for primary brand color */
    input[type="checkbox"]:checked {
        background-color: #f97316 !important;
        border-color: #f97316 !important;
    }
    input[type="radio"]:checked {
        color: #f97316 !important;
        border-color: #f97316 !important;
    }
    /* Custom scrollbar for agent list to keep it minimal */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #e0c0b1;
        border-radius: 20px;
    }
</style>

@stack('styles')
</head>
<body class="bg-background text-on-background antialiased min-h-screen selection:bg-primary-container/30 selection:text-primary">

@include('admin.layouts.partials.sidebar')

@include('admin.layouts.partials.header')

<!-- Main Content Canvas -->
<main class="pt-16 md:pl-64 min-h-screen flex flex-col">
    <div class="flex-1 p-margin-mobile md:p-margin-desktop max-w-[1600px] w-full mx-auto">
        @yield('content')
    </div>

    @include('admin.layouts.partials.footer')
</main>

@stack('scripts')
</body>
</html>