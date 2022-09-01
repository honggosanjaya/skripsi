<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusEnumToReimbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reimbursements', function (Blueprint $table) {
          $table->enum('status_enum',['-1','0','1','2'])->comment('-1:ditolak, 0:diajukan, 1:diproses, 2:dibayar');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reimbursements', function (Blueprint $table) {
            //
        });
    }
}
