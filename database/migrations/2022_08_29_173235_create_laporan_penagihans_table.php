<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaporanPenagihansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laporan_penagihans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_invoice');
            $table->bigInteger('id_staff_penagih');
            $table->date('tanggal');
            $table->enum('status_enum',['-1','1'])->comment('-1:belum ditagih, 1:sudah ditagih');
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
        Schema::dropIfExists('laporan_penagihans');
    }
}
