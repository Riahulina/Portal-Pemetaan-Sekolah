<!-- resources/views/partials/adminSidebar.blade.php -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"></div>

<aside x-cloak :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 h-screen">
    <div class="flex flex-col h-full">
        <!-- Brand -->
        <a href="{{ route('admin.dashboard') }}"
            style="display: flex; align-items: center; gap: 16px; padding: 0 24px; height: 100px; border-bottom: 1px solid #e5e7eb; text-decoration: none; flex-shrink: 0; width: 100%; box-sizing: border-box;">
            <img src="{{ asset('assets/logo.png') }}" alt="Logo"
                style="width: 60px; height: 60px; object-fit: contain; flex-shrink: 0;">
            <div style="display: flex; flex-direction: column; line-height: 1.15; gap: 1px;">
                <div style="display: flex; align-items: baseline; line-height: 1; margin-bottom: 2px;">
                    <span style="font-family: 'Inter', sans-serif; font-size: 2rem; font-weight: 700; color: #f2a53a;">Satu</span>
                    <span style="font-family: 'Lora', serif; font-size: 2rem; font-weight: 700; color: #146b68;">Peta</span>
                </div>
                <div style="font-family: 'Inter', sans-serif; font-size: 0.75rem; font-weight: 500; color: #4b5d5c; letter-spacing: 0.3px;">Admin Panel</div>
            </div>
        </a>

        <!-- Menu -->
        <nav class="flex flex-col gap-2 px-6 py-4 flex-1" style="font-family: 'Inter', sans-serif;">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-[10px] px-4 py-3 rounded-lg text-[0.9rem] font-medium transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-[#e0f2f1] text-[#0d9488] font-semibold' : 'text-[#475569] hover:bg-[#e0f2f1] hover:text-[#0d9488]' }}">
                <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.sekolah.index') }}"
                class="flex items-center gap-[10px] px-4 py-3 rounded-lg text-[0.9rem] font-medium transition-all duration-200 {{ request()->routeIs('admin.sekolah.*') ? 'bg-[#e0f2f1] text-[#0d9488] font-semibold' : 'text-[#475569] hover:bg-[#e0f2f1] hover:text-[#0d9488]' }}">
                <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Manajemen Sekolah
            </a>

            <a href="{{ route('admin.pendaftaran.index') }}"
                class="flex items-center gap-[10px] px-4 py-3 rounded-lg text-[0.9rem] font-medium transition-all duration-200 {{ request()->routeIs('admin.pendaftaran.*') ? 'bg-[#e0f2f1] text-[#0d9488] font-semibold' : 'text-[#475569] hover:bg-[#e0f2f1] hover:text-[#0d9488]' }}">
                <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                Pendaftaran
            </a>

            <a href="{{ route('admin.pengguna.index') }}"
                class="flex items-center gap-[10px] px-4 py-3 rounded-lg text-[0.9rem] font-medium transition-all duration-200 {{ request()->routeIs('admin.pengguna.*') ? 'bg-[#e0f2f1] text-[#0d9488] font-semibold' : 'text-[#475569] hover:bg-[#e0f2f1] hover:text-[#0d9488]' }}">
                <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Manajemen Pengguna
            </a>

            <a href="{{ route('admin.laporan.index') }}"
                class="flex items-center gap-[10px] px-4 py-3 rounded-lg text-[0.9rem] font-medium transition-all duration-200 {{ request()->routeIs('admin.laporan.index') ? 'bg-[#e0f2f1] text-[#0d9488] font-semibold' : 'text-[#475569] hover:bg-[#e0f2f1] hover:text-[#0d9488]' }}">
                <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path
                        d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Laporan
            </a>
        </nav>
    </div>
</aside>
