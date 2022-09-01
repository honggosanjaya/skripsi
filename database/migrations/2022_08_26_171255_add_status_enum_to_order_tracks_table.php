<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusEnumToOrderTracksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_tracks', function (Blueprint $table) {
          $table->enum('status_enum',['-1','0','1','2','3','4','5'])->comment('-1:order ditolak, 0:diajukan customer, 1:diajukan salesman, 2: dikonfirmasi admin, 3: dalam perjalanan, 4: order telah sampai, 5: order selesai');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_tracks', function (Blueprint $table) {
            //
        });
    }
}
