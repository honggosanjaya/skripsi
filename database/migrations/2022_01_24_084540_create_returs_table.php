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
            $table->bigInteger('item_id');
            $table->bigInteger('sales_id');
            $table->bigInteger('toko_id');
            $table->integer('quantity');
            $table->text('alasan');
            $table->enum('status', ['-1','0', '1'])->default('0')->index('status')->comment('-1: ditolak, 0:belum melakukan tindakan, 1:disetujui');
            $table->enum('tindakan_selanjutnya', ['0', '1'])->default('0')->index('tindakan_selanjutnya')->comment('0:tukar barang, 1:lainnya');
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
