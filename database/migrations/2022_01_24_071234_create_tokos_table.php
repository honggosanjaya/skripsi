<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTokosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokos', function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('jenis_toko_id');
            $table->string('nama',255);
            $table->string('alamat_utama',255);
            $table->string('alamat_nomor',255)->nullable();
            $table->string('keterangan_alamat',255)->nullable();
            $table->string('koordinat',255)->nullable();
            $table->string('nomor_telepon',15)->nullable();
            $table->enum('status', ['-1','0','1'])->comment('-1:Tutup, 0:Belum Effective Call, 1:Effective Call')->default(0);
            $table->integer('durasi kunjungan (satuan hari)');
            $table->timestamp('terakhir_order');
            $table->integer('counter_to_effective_call');
            $table->integer('total_counter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tokos');
    }
}
