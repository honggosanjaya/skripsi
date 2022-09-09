<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_staff');
            $table->bigInteger('id_cash_account');
            $table->date('tanggal');
            $table->string('no_bukti',255)->nullable();
            $table->enum('debit_kredit',['-1','1'])->comment('-1:kredit, 1:debit')->nullable();
            $table->text('keterangan_1')->nullable();
            $table->text('keterangan_2')->nullable();
            $table->enum('status',['0','1'])->comment('0:perubahan diajukan, 1:perubahan disetujui')->nullable();
            $table->double('saldo');
            $table->string('kontak',255)->nullable();
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
        Schema::dropIfExists('kas');
    }
}
