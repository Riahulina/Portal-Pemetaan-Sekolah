<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Dataset statis hierarki wilayah Indonesia (pulau → provinsi →
     * kabupaten/kota → kecamatan). Sumber data: KPU (Pemilu 2024),
     * di-generate satu kali dan disimpan sebagai array statis sehingga
     * seeder tidak pernah memanggil API eksternal (zero egress).
     *
     * @var array<string, array<string, array<string, array<int, string>>>>
     */
    private array $wilayah;

    public function __construct()
    {
        $this->wilayah = require __DIR__.'/data/wilayah.php';
    }

    /**
     * Seed reference tables dengan data hierarki wilayah.
     * Idempotent — memakai insertOrIgnore agar aman dijalankan ulang.
     */
    public function run(): void
    {
        $now = now();

        foreach ($this->wilayah as $pulau => $provinsi) {
            DB::table('ref_pulau')->insertOrIgnore([
                'nama' => $pulau,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($provinsi as $provName => $kabupaten) {
                DB::table('ref_provinsi')->insertOrIgnore([
                    'nama' => $provName,
                    'pulau' => $pulau,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ($kabupaten as $kabName => $kecamatan) {
                    DB::table('ref_kabupaten')->insertOrIgnore([
                        'nama' => $kabName,
                        'provinsi' => $provName,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $rows = collect($kecamatan)->map(fn (string $kecName) => [
                        'nama' => $kecName,
                        'kabupaten' => $kabName,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all();

                    foreach (array_chunk($rows, 500) as $chunk) {
                        DB::table('ref_kecamatan')->insertOrIgnore($chunk);
                    }
                }
            }
        }
    }
}
