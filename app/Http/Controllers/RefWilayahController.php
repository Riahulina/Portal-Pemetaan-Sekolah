<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RefWilayahController extends Controller
{
    /**
     * Cache respons referensi wilayah agar cepat dan tidak membebani database.
     * Data referensi bersifat statis, hanya berubah saat seeder dijalankan ulang
     * (sinkronkan dengan `php artisan cache:clear` bila dataset diperbarui).
     */
    private const CACHE_TTL_SECONDS = 86400 * 30;

    /**
     * GET /api/ref/pulau
     */
    public function pulau(): JsonResponse
    {
        $data = Cache::remember('ref_wilayah:pulau', self::CACHE_TTL_SECONDS, function () {
            return DB::table('ref_pulau')
                ->orderBy('nama')
                ->get(['nama'])
                ->toArray();
        });

        return response()->json($data);
    }

    /**
     * GET /api/ref/provinsi?pulau=Sumatera
     */
    public function provinsi(Request $request): JsonResponse
    {
        $pulau = $request->query('pulau');

        $key = 'ref_wilayah:provinsi:'.md5(mb_strtolower((string) $pulau));

        $data = Cache::remember($key, self::CACHE_TTL_SECONDS, function () use ($pulau) {
            return DB::table('ref_provinsi')
                ->when($pulau, fn ($q) => $q->where('pulau', $pulau))
                ->orderBy('nama')
                ->get(['nama', 'pulau'])
                ->toArray();
        });

        return response()->json($data);
    }

    /**
     * GET /api/ref/kabupaten?provinsi=Sumatera Utara
     */
    public function kabupaten(Request $request): JsonResponse
    {
        $provinsi = $request->query('provinsi');

        if (! $provinsi) {
            return response()->json([
                'message' => 'Parameter "provinsi" wajib diisi.',
            ], 422);
        }

        $key = 'ref_wilayah:kabupaten:'.md5(mb_strtolower((string) $provinsi));

        $data = Cache::remember($key, self::CACHE_TTL_SECONDS, function () use ($provinsi) {
            return DB::table('ref_kabupaten')
                ->where('provinsi', $provinsi)
                ->orderBy('nama')
                ->get(['nama', 'provinsi'])
                ->toArray();
        });

        return response()->json($data);
    }

    /**
     * GET /api/ref/kecamatan?kabupaten=Kota Medan
     */
    public function kecamatan(Request $request): JsonResponse
    {
        $kabupaten = $request->query('kabupaten');

        if (! $kabupaten) {
            return response()->json([
                'message' => 'Parameter "kabupaten" wajib diisi.',
            ], 422);
        }

        $key = 'ref_wilayah:kecamatan:'.md5(mb_strtolower((string) $kabupaten));

        $data = Cache::remember($key, self::CACHE_TTL_SECONDS, function () use ($kabupaten) {
            return DB::table('ref_kecamatan')
                ->where('kabupaten', $kabupaten)
                ->orderBy('nama')
                ->get(['nama', 'kabupaten'])
                ->toArray();
        });

        return response()->json($data);
    }
}
