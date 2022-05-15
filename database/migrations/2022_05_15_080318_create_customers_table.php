<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_jenis');
            $table->bigInteger('id_wilayah');
            $table->bigInteger('id_staff');
            $table->string('nama',255);
            $table->string('email',255)->nullable();
            $table->string('password',255)->nullable();
            $table->string('alamat_utama',255);
            $table->string('alamat_nomor',255)->nullable();
            $table->text('keterangan_alamat')->nullable();
            $table->string('koordinat',255)->nullable();
            $table->string('telepon',15)->nullable();
            $table->integer('durasi_kunjungan');
            $table->integer('counter_to_effective_call');
            $table->bigInteger('tipe_retur')->nullable();
            $table->double('limit_pembelian')->nullable();
            $table->double('pengajuan_limit_pembelian')->nullable();
            $table->bigInteger('status_limit_pembelian')->nullable();
            $table->string('foto',255)->nullable();
            $table->bigInteger('status');
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
        Schema::dropIfExists('customers');
    }
}
