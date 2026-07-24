@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div x-data="userManager()" x-init="init()">

        <!-- FLASH MESSAGES -->
        @if (session('success'))
            <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-2">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('error') }}
            </div>
        @endif

        <!-- SEARCH BAR -->
        <form method="GET" action="{{ route('admin.pengguna.index') }}" class="mb-6">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                <div class="relative flex-1 min-w-0 max-w-md w-full">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari berdasarkan nama atau email..."
                        class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-[#0d9296]/30 focus:border-[#0d9296] transition-all">
                </div>
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-[#0d9296] text-white text-sm font-medium rounded-lg hover:bg-[#0b7e82] transition-colors">
                    Cari
                </button>
                @if ($search)
                    <a href="{{ route('admin.pengguna.index') }}" class="w-full sm:w-auto px-4 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors text-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- DATA TABLE -->
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50">
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Nama</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Email</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Role</th>
                            <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Tanggal Daftar</th>
                            <th class="text-center px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $row)
                            <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-medium text-gray-900">{{ $row->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $row->email }}</td>
                                <td class="px-6 py-4">
                                    @if ($row->is_admin)
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-orange-50 text-orange-700 border border-orange-200">Admin</span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-50 text-blue-700 border border-blue-200">User</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button @click="openDeleteModal({{ $row->toJson() }})"
                                        class="px-3 py-1.5 text-xs font-medium text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                    </svg>
                                    <p class="text-gray-500 text-sm">Tidak ada data pengguna ditemukan.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if ($users->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $users->firstItem() }}-{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                    </p>
                    <div class="flex items-center gap-1">
                        @if ($users->onFirstPage())
                            <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed">&laquo;</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}&search={{ $search }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&laquo;</a>
                        @endif

                        @foreach ($users->getUrlRange(max(1, $users->currentPage() - 2), min($users->lastPage(), $users->currentPage() + 2)) as $page => $url)
                            @if ($page == $users->currentPage())
                                <span class="px-3 py-1.5 text-sm text-white bg-[#0d9296] rounded-lg font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}&search={{ $search }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}&search={{ $search }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&raquo;</a>
                        @else
                            <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed">&raquo;</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- MODAL: HAPUS PENGGUNA -->
        <div x-show="deleteModal" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="deleteModal = false">
            <div class="absolute inset-0 bg-black/40"></div>
            <div x-show="deleteModal" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                class="relative bg-white rounded-2xl shadow-xl w-full max-w-md">
                <div class="px-6 py-5 text-center">
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Hapus Pengguna?</h3>
                    <p class="text-sm text-gray-500 mb-1">Anda yakin ingin menghapus pengguna ini?</p>
                    <p class="text-sm font-semibold text-gray-900" x-text="deleteData?.name || ''"></p>
                    <p class="text-xs text-gray-400 mt-1" x-text="deleteData?.email || ''"></p>
                    <p class="text-xs text-red-500 mt-3">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-center gap-3">
                    <button @click="deleteModal = false" class="px-5 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">Batal</button>
                    <form :action="'{{ url('admin/pengguna') }}/' + (deleteData?.id || '')" method="POST" x-show="deleteData">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-5 py-2 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-600 transition-colors">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        function userManager() {
            return {
                deleteModal: false,
                deleteData: null,

                init() {},

                openDeleteModal(data) {
                    this.deleteData = data;
                    this.deleteModal = true;
                },
            };
        }
    </script>
@endpush
