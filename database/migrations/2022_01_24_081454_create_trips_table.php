<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id()->primary();
            $table->bigInteger('toko_id');
            $table->bigInteger('sales_id');
            $table->text('alasan_menolak')->nullable();
            $table->string('koordinat', 255)->nullable();
            $table->timestamp('waktu_masuk')->nullable();
            $table->timestamp('waktu_keluar')->nullable();
            $table->enum('status', ['0','1','2'])->comment('0:Belum Effective Call, 1:Effective Call, 2:Effective Call dan sudah beli');
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
        Schema::dropIfExists('trips');
    }
}
