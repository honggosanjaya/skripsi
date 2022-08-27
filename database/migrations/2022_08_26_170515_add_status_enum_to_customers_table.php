<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusEnumToCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
          $table->enum('status_enum',['1','-1'])->comment('-1:inactive, 1:active');
          $table->enum('status_limit_pembelian_enum',['1','0','-1'])->comment('-1:tidak disetujui, 0:diajukan, 1:disetujui')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
}
