<!-- Shared Component: TopNavBar -->
<header class="fixed top-0 right-0 w-full md:w-[calc(100%-16rem)] h-16 bg-surface border-b border-outline-variant flex justify-between items-center px-margin-desktop z-30">
    <div class="flex items-center">
        <!-- Mobile Menu Toggle (Visible only on small screens) -->
        <button class="md:hidden mr-4 text-secondary" id="mobileMenuToggle">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <!-- Search -->
        <div class="relative hidden sm:block w-64">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-sm">search</span>
            <input class="w-full pl-9 pr-4 py-2 bg-surface-container-low border border-transparent focus:border-primary-container focus:bg-surface-container-lowest rounded-full font-body-md text-sm text-on-surface transition-all outline-none placeholder:text-secondary-fixed-dim" placeholder="Search across CRM..." type="text"/>
        </div>
    </div>
    <div class="flex items-center gap-6">
        <div class="flex items-center gap-4">
            <button class="text-secondary hover:text-primary transition-all cursor-pointer active:opacity-80">
                <span class="material-symbols-outlined">notifications</span>
            </button>
            <button class="text-secondary hover:text-primary transition-all cursor-pointer active:opacity-80">
                <span class="material-symbols-outlined">apps</span>
            </button>
        </div>
        <div class="flex items-center gap-3 pl-6 border-l border-outline-variant cursor-pointer group">
            <div class="text-right hidden sm:block">
                <p class="font-label-sm text-label-sm text-on-surface group-hover:text-primary-container transition-colors">
                    {{ auth()->user()->name ?? 'Admin Profile' }}
                </p>
            </div>
            <img alt="Admin user avatar"
                 class="w-9 h-9 rounded-full object-cover border border-outline-variant group-hover:border-primary-container transition-colors"
                 src="{{ auth()->user()->avatar_url ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuDXZqJeVr9BOZezWzQd_zmzo86mE78MqhJ2gG5f3r-OaD-VCBTlDMdV4GD8LmHbBBk-XTPOUqLzlLSv2yjh7CT-RDwKXWNvZ5Zf1XsYrhkQjm4SqmIZLfgO47z_kcP0XHEGhPO7PPO9yP3qV_Zn8L8UMtlEg336OeYIjWP_Tv2ZTCIBVMv9ubZ5VCzk2DPHgQK02SHAVkITJNhJtkPecLKLRL1ySQvLJ1_zW1voK25eLp9sbZTeAD2n' }}"/>
        </div>
    </div>
</header>