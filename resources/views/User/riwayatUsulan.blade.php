@extends('layouts.app')

@section('title', 'Riwayat Usulan Perbaikan')

@section('styles')
<style>
    .ru-container { background: #fff; border-radius: 10px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin-top: 20px; }
    .ru-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .ru-table th, .ru-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eee; font-size: 0.9rem; }
    .ru-table th { background: #f8fafc; color: #475569; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
    .badge-pending { background: #fef08a; color: #854d0e; }
    .badge-selesai { background: #dcfce3; color: #166534; }
    .ru-empty { text-align: center; padding: 40px; color: #64748b; font-style: italic; }
</style>
@endsection

@section('content')
<div class="dashboard-layout">
    @include('partials.sidebar')

    <main class="main-content">
        <h2 style="color: #1e293b; font-size: 1.5rem; font-weight: 600;">Riwayat Usulan Perbaikan</h2>
        <p style="color: #64748b; margin-top: 5px; font-size: 0.95rem;">Pantau status perbaikan data sekolah yang telah Anda laporkan.</p>

        <div class="ru-container">
            @if($laporans->isEmpty())
                <div class="ru-empty">Belum ada riwayat usulan perbaikan data.</div>
            @else
                <div style="overflow-x: auto;">
                    <table class="ru-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Sekolah</th>
                                <th>Pesan Koreksi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporans as $laporan)
                            <tr>
                                <td>{{ $laporan->created_at->format('d M Y') }}</td>
                                <td>
                                    <strong style="color: #0f172a;">{{ $laporan->sekolah->nama_sekolah ?? 'Sekolah Dihapus' }}</strong><br>
                                    <span style="color: #64748b; font-size: 0.8rem;">{{ $laporan->sekolah_npsn }}</span>
                                </td>
                                <td style="max-width: 300px; line-height: 1.5;">{{ $laporan->pesan_koreksi }}</td>
                                <td>
                                    <span class="badge {{ $laporan->status === 'pending' ? 'badge-pending' : 'badge-selesai' }}">
                                        {{ ucfirst($laporan->status) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 20px;">
                    {{ $laporans->links() }}
                </div>
            @endif
        </div>
    </main>
</div>
@endsection
