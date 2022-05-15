<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_staff');
            $table->string('nama',255);
            $table->text('keterangan')->nullable();
            $table->tinyInteger('diskon')->nullable();
            $table->double('potongan')->nullable();
            $table->double('min_pembelian')->nullable();
            $table->string('kode',50)->nullable();
            $table->timestamp('date_start');
            $table->timestamp('date_end')->nullable();
            $table->bigInteger('status');
            $table->string('gambar',255)->nullable();
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
        Schema::dropIfExists('events');
    }
}
