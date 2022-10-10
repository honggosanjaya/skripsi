<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKanvasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kanvas', function (Blueprint $table) {
            $table->id();
            $table->string('nama',255);
            $table->integer('stok_awal');
            $table->integer('sisa_stok');
            $table->bigInteger('id_item');
            $table->bigInteger('id_staff_pengonfirmasi_pembawaan');
            $table->bigInteger('id_staff_pengonfirmasi_pengembalian')->nullable();
            $table->bigInteger('id_staff_yang_membawa');
            $table->timestamp('waktu_dibawa')->nullable();
            $table->timestamp('waktu_dikembalikan')->nullable();
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
        Schema::dropIfExists('kanvas');
    }
}
