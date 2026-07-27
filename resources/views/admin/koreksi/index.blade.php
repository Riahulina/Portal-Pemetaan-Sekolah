@extends('layouts.admin')

@section('title', 'Antrean Koreksi Data')

@section('content')
    <!-- FLASH MESSAGE -->
    @if (session('success'))
        <div class="mb-6 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- SUMMARY CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Menunggu</p>
                <p class="text-xl font-bold text-gray-900">{{ $laporans->total() ? $laporans->getCollection()->filter(fn($l) => $l->status === 'pending')->count() : 0 }}</p>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-4">
            <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wide font-medium">Total Laporan</p>
                <p class="text-xl font-bold text-gray-900">{{ $laporans->total() }}</p>
            </div>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-center px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs w-16">No</th>
                        <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Tanggal</th>
                        <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Pelapor</th>
                        <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Sekolah</th>
                        <th class="text-left px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Pesan Koreksi</th>
                        <th class="text-center px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Status</th>
                        <th class="text-center px-6 py-3.5 font-bold text-gray-500 uppercase tracking-wide text-xs">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($laporans as $laporan)
                        <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 text-center text-gray-500">
                                {{ $laporans->firstItem() + $loop->index }}
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-xs whitespace-nowrap">
                                {{ $laporan->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-medium text-gray-900">{{ $laporan->nama_pelapor }}</p>
                                <p class="text-xs text-gray-400">{{ $laporan->email_pelapor }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if ($laporan->sekolah)
                                    <p class="font-medium text-gray-900">{{ $laporan->sekolah->nama_sekolah }}</p>
                                    <code class="text-xs bg-gray-100 px-2 py-0.5 rounded font-mono text-gray-600">{{ $laporan->sekolah_npsn }}</code>
                                @else
                                    <span class="text-xs text-red-400 italic">Sekolah tidak ditemukan</span>
                                    <code class="text-xs bg-gray-100 px-2 py-0.5 rounded font-mono text-gray-600 block mt-1">{{ $laporan->sekolah_npsn }}</code>
                                @endif
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <p class="text-sm text-gray-700 line-clamp-2" title="{{ $laporan->pesan_koreksi }}">
                                    {{ $laporan->pesan_koreksi }}
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($laporan->status === 'pending')
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-yellow-50 text-yellow-700 border border-yellow-200">Menunggu</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-xs font-medium rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.sekolah.index', ['search' => $laporan->sekolah_npsn]) }}"
                                        class="text-xs font-medium text-blue-600 hover:underline whitespace-nowrap">
                                        Lihat Sekolah
                                    </a>
                                    @if ($laporan->status === 'pending')
                                        <form action="{{ route('admin.koreksi.resolve', $laporan->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                onclick="return confirm('Tandai laporan ini sebagai selesai?')"
                                                class="text-xs font-medium text-emerald-600 hover:underline whitespace-nowrap">
                                                Tandai Selesai
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 text-sm">Belum ada laporan koreksi data.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if ($laporans->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $laporans->firstItem() }}-{{ $laporans->lastItem() }} dari {{ $laporans->total() }} laporan
                </p>
                <div class="flex items-center gap-1">
                    @if ($laporans->onFirstPage())
                        <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed">&laquo;</span>
                    @else
                        <a href="{{ $laporans->previousPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&laquo;</a>
                    @endif

                    @foreach ($laporans->getUrlRange(max(1, $laporans->currentPage() - 2), min($laporans->lastPage(), $laporans->currentPage() + 2)) as $page => $url)
                        @if ($page == $laporans->currentPage())
                            <span class="px-3 py-1.5 text-sm text-white bg-[#0d9296] rounded-lg font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($laporans->hasMorePages())
                        <a href="{{ $laporans->nextPageUrl() }}" class="px-3 py-1.5 text-sm text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">&raquo;</a>
                    @else
                        <span class="px-3 py-1.5 text-sm text-gray-300 bg-gray-50 rounded-lg cursor-not-allowed">&raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
