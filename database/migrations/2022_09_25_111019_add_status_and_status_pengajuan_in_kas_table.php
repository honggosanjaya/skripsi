<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndStatusPengajuanInKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kas', function (Blueprint $table) {
          $table->enum('status_pengajuan',['-1','0','1'])->comment('-1:ditolak, 0:diajukan, 1:disetujui')->nullable();
          // $table->enum('status',['-1','1'])->comment('-1:inactive, 1:active')->default('1');
          $table->enum('status',['-1','1'])->comment('-1:inactive, 1:active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kas', function (Blueprint $table) {
            //
        });
    }
}
