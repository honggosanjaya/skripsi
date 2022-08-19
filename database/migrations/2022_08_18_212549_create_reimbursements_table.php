<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReimbursementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reimbursements', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_staff_pengaju');
            $table->bigInteger('id_staff_pengonfirmasi')->nullable();
            $table->bigInteger('id_cash_account');
            $table->string('foto',255)->nullable();
            $table->double('jumlah_uang');
            $table->text('keterangan_pengajuan')->nullable();
            $table->text('keterangan_konfirmasi')->nullable();
            $table->bigInteger('status');
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
        Schema::dropIfExists('reimbursements');
    }
}
