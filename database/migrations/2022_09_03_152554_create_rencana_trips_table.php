<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRencanaTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rencana_trips', function (Blueprint $table) {
          $table->id();
          $table->bigInteger('id_customer');
          $table->bigInteger('id_staff');
          $table->date('tanggal');
          $table->enum('status_enum',['-1','1'])->comment('-1:belum dikunjungi, 1:sudah dikunjungi');
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
        Schema::dropIfExists('rencana_trips');
    }
}
