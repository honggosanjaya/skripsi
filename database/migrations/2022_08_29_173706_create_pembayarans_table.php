<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePembayaransTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_invoice');
            $table->bigInteger('id_staff_penagih');
            $table->date('tanggal');
            $table->double('total_pembayaran');
            $table->enum('metode_pembayaran',['1','2','3'])->comment('1:tunai, 2:giro, 3:dicicil');
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
        Schema::dropIfExists('pembayarans');
    }
}
