<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminSekolahController extends Controller
{
    /**
     * Tampilkan daftar sekolah dengan pencarian dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $filterKurang = $request->query('filter_kurang', '');

        $sekolah = Sekolah::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_sekolah', 'ilike', '%'.$search.'%')
                        ->orWhere('npsn', 'ilike', '%'.$search.'%');
                });
            })
            ->when($filterKurang !== '', function ($query) use ($filterKurang) {
                $query->where(function ($q) use ($filterKurang) {
                    switch ($filterKurang) {
                        case 'koordinat':
                            $q->whereNull('latitude')->orWhereNull('longitude');
                            break;
                        case 'siswa':
                            $q->where('total_siswa', 0);
                            break;
                        case 'sosmed':
                            $q->whereNull('social_media')->orWhere('social_media', '');
                            break;
                        case 'email':
                            $q->whereNull('email')->orWhere('email', '');
                            break;
                        case 'telepon':
                            $q->whereNull('no_telepon')->orWhere('no_telepon', '');
                            break;
                    }
                });
            })
            ->orderByRaw('updated_at DESC NULLS LAST')
            ->paginate(10)
            ->appends([
                'search' => $search,
                'filter_kurang' => $filterKurang,
            ]);

        return view('admin.manajemen-sekolah', compact('sekolah', 'search', 'filterKurang'));
    }

    /**
     * Perbarui data sekolah berdasarkan NPSN.
     */
    public function update(Request $request, string $npsn)
    {
        $sekolah = Sekolah::findOrFail($npsn);

        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:150',
            'jenjang' => 'nullable|string|max:20',
            'status' => 'nullable|string|max:20|in:NEGERI,SWASTA',
            'akreditasi' => 'nullable|string|max:5',
            'provinsi' => 'nullable|string|max:100',
            'kabupaten_kota' => 'nullable|string|max:100',
            'kecamatan' => 'nullable|string|max:100',
            'kelurahan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'no_telepon' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:100',
            'social_media' => 'nullable|string',
            'jumlah_siswa_laki_laki' => 'nullable|integer|min:0',
            'jumlah_siswa_perempuan' => 'nullable|integer|min:0',
            'yayasan' => 'nullable|string',
        ]);

        $validated['total_siswa'] = ($validated['jumlah_siswa_laki_laki'] ?? 0) + ($validated['jumlah_siswa_perempuan'] ?? 0);

        $sekolah->update($validated);

        return redirect()->route('admin.sekolah.index')->with('success', 'Data sekolah berhasil diperbarui.');
    }

    /**
     * Hapus sekolah berdasarkan NPSN.
     */
    public function destroy(string $npsn)
    {
        $sekolah = Sekolah::findOrFail($npsn);
        $sekolah->delete();

        // Bust admin dashboard cache
        Cache::forget('admin_dashboard_data');

        // Bust static wilayah + summary caches
        Cache::forget('sekolah_wilayah_v2');
        Cache::forget('sekolah_provinsi_summary_v1');

        // Bust dynamic map caches affected by this school's location
        $filters = [$sekolah->provinsi, '', '', '', ''];
        Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));

        $filters[1] = $sekolah->kabupaten_kota;
        Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));

        $filters[2] = $sekolah->kecamatan;
        Cache::forget('sekolah_map_v5_'.md5(implode('_', $filters)));

        return redirect()->route('admin.sekolah.index')->with('success', 'Data sekolah berhasil dihapus.');
    }
}
