<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
 
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::table('retur_types')->insert([
          [
            'nama' => 'potongan',
            'detail' => 'potongan terhadap invoice yang telah selesai dan belum lunas',
            'created_at' => now(),
            'updated_at' => now()
          ],
          [
            'nama' => 'tukar guling',
            'detail' => 'pertukaran barang saja',
            'created_at' => now(),
            'updated_at' => now()
          ]
        ]);

        DB::table('staff_roles')->insert([
          [
            'nama' => 'owner',
            'detail' => 'pemilik (hanya boleh 1)',
            'created_at' => now(),
            'updated_at' => now()
          ],
          [
            'nama' => 'supervisor',
            'detail' => 'penanggung jawab',
            'created_at' => now(),
            'updated_at' => now()
          ],
          [
            'nama' => 'salesman',
            'detail' => 'tenaga penjual',
            'created_at' => now(),
            'updated_at' => now()
          ],
          [
          'nama' => 'shipper',
          'detail' => 'tenaga pengirim',
          'created_at' => now(),
          'updated_at' => now()
          ],
          [
            'nama' => 'administrasi',
            'detail' => 'admin',
            'created_at' => now(),
            'updated_at' => now()
          ]
        ]);
    }
}