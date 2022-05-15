<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nama',255);
            $table->string('kode_barang',20);
            $table->integer('stok')->default(0);
            $table->integer('min_stok')->default(0);
            $table->integer('max_stok')->default(0);
            $table->integer('max_pengadaan')->default(0);
            $table->string('satuan',30);
            $table->double('harga_satuan');
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
        Schema::dropIfExists('items');
    }
}
