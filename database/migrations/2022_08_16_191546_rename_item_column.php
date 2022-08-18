<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameItemColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('items', function(Blueprint $table) {
        $table->renameColumn('harga_satuan', 'harga1_satuan');
     });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::table('items', function(Blueprint $table) {
        $table->renameColumn('harga1_satuan', 'harga_satuan');
    });
    }
}
