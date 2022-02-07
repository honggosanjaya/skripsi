<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMainColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nomor_telepon',15);
            $table->string('foto_profil',255);
            $table->enum('status',['0','1'])->comment('0:Sudah Dipecat, 1:Masih Bekerja')->default(1);
            $table->enum('role',['0','1','2'])->comment('0:Admin, 1:Supervisor, 2:Sales')->default(0);
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
