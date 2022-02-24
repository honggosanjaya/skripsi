<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use \App\Models\Toko;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Toko::create([
          "jenis_toko_id" => '1',
          "nama" => 'Toko Admin',
          "alamat_utama" => "Perumahan Araya",
          "keterangan_alamat" => "Tokonya admin",
          "status" => "-1",
          "durasi kunjungan (satuan hari)" => 0,
          "terakhir_order" => "2022-02-11 17:00:00",
          "counter_to_effective_call" => "0",
          "total_counter" => "0",
        ]);
    }
}
