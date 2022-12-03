<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemPriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_price_lists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_item');
            $table->double('price');
            $table->enum('type',['1','2','3','hpp'])->comment('1:harga1, 2:harga2, 3:harga3, hpp:hargahpp');
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
        Schema::dropIfExists('item_price_lists');
    }
}
