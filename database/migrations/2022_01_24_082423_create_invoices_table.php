<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->bigInteger('metodepembayaran_id');
            $table->varchar('nomor_invoice', 255);
            $table->double('harga_total');
            $table->timestamp('waktu_pelunasan');
            $table->enum('status_pelunasan', ['0', '1', '2'])->default('0')->index('status_pelunasan')->comment('0:belum lunas, 1:setengah lunas, 2:lunas');
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
        Schema::dropIfExists('invoices');
    }
}
