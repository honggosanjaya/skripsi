<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_customer');
            $table->bigInteger('id_staff_pengaju');
            $table->bigInteger('id_staff_pengonfirmasi')->nullable();
            $table->bigInteger('id_item');
            $table->bigInteger('id_invoice')->nullable();
            $table->string('no_retur',20);
            $table->integer('kuantitas');
            $table->double('harga_satuan');
            $table->bigInteger('tipe_retur');
            $table->text('alasan');
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
        Schema::dropIfExists('returs');
    }
}
