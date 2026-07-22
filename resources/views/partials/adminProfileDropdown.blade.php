{{-- resources/views/partials/adminProfileDropdown.blade.php --}}
<style>
    .admin-dropdown-link {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        color: #334155;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
    }
    .admin-dropdown-link:hover {
        background-color: #f8fafc;
        color: #0d9488;
    }
    .admin-dropdown-logout {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        color: #ef4444;
        font-size: 14px;
        font-weight: 500;
        font-family: 'Inter', sans-serif;
        text-decoration: none;
        width: 100%;
        text-align: left;
        background: none;
        border: none;
        cursor: pointer;
    }
    .admin-dropdown-logout:hover {
        background-color: #fef2f2;
        color: #dc2626;
    }
</style>

<div x-data="{ open: false }" @click.away="open = false"
    style="position: relative; display: inline-block;">

    <button @click="open = !open"
        style="font-family: 'Inter', sans-serif; display: flex; align-items: center; gap: 10px; background: #ffffff; padding: 6px 14px; border-radius: 99px; border: 1px solid #e2e8f0; cursor: pointer;">
        <div style="width: 32px; height: 32px; background-color: #0d9488; color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; flex-shrink: 0;">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <span style="font-weight: 600; font-size: 14px; color: #1e293b;">
            {{ Auth::user()->name }}
        </span>
        <svg style="width: 12px; height: 12px; color: #64748b; margin-left: 2px;"
            fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
            <path d="m6 9 6 6 6-6" />
        </svg>
    </button>

    <div x-show="open" x-cloak
        style="font-family: 'Inter', sans-serif; position: absolute; right: 0; top: calc(100% + 10px); background: #ffffff; min-width: 200px; border-radius: 12px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); border: 1px solid #e2e8f0; padding: 8px 0; z-index: 9999;">

        <div style="padding: 8px 16px; font-size: 13px; font-weight: 400; color: #64748b; word-break: break-all;">
            {{ Auth::user()->email }}
        </div>

        <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 0;">

        <a href="{{ route('admin.profile.index') }}" class="admin-dropdown-link">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile
        </a>

        <a href="{{ route('dashboard') }}" class="admin-dropdown-link">
            <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
            </svg>
            Peta Interaktif
        </a>

        <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 0;">

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="admin-dropdown-logout">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Keluar / Logout
            </button>
        </form>
    </div>
</div>