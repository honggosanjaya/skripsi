<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultParentToCashAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cash_accounts', function (Blueprint $table) {
          $table->enum('default',['1','2','3'])->comment('1:pengadaan, 2:penjualan, 3:parent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cash_accounts', function (Blueprint $table) {
            //
        });
    }
}
