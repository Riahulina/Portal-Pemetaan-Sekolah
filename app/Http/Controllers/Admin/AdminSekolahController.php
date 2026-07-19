<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class AdminSekolahController extends Controller
{
    /**
     * Tampilkan daftar sekolah dengan pencarian dan pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        $sekolah = Sekolah::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_sekolah', 'ilike', '%'.$search.'%')
                        ->orWhere('npsn', 'ilike', '%'.$search.'%');
                });
            })
            ->orderBy('nama_sekolah')
            ->paginate(10);

        return view('admin.manajemen-sekolah', compact('sekolah', 'search'));
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
            'status' => 'nullable|string|max:20',
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
            'total_siswa' => 'nullable|integer|min:0',
            'yayasan' => 'nullable|string',
        ]);

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

        return redirect()->route('admin.sekolah.index')->with('success', 'Data sekolah berhasil dihapus.');
    }
}
