<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_tracks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_order');
            $table->bigInteger('id_staff_pengonfirmasi')->nullable();
            $table->bigInteger('id_staff_pengirim')->nullable();
            $table->bigInteger('id_vehicle')->nullable();
            $table->bigInteger('status');
            $table->timestamp('waktu_order')->nullable();
            $table->timestamp('waktu_diteruskan')->nullable();
            $table->timestamp('waktu_dikonfirmasi')->nullable();
            $table->timestamp('waktu_berangkat')->nullable();
            $table->timestamp('waktu_sampai')->nullable();
            $table->tinyInteger('estimasi_waktu_pengiriman');
            $table->string('foto_pengiriman',255)->nullable();
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
        Schema::dropIfExists('order_tracks');
    }
}
