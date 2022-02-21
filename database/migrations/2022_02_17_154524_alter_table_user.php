<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('alamat',255)->nullable();
            $table->string('tanggal_lahir',255)->nullable();
            $table->string('agama',255)->nullable();
            $table->string('nama_wali',255)->nullable();
            $table->string('status_wali',255)->nullable();
            $table->string('nomer_telepon_wali',255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            
        });
    }
}
