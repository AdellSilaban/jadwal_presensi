<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKoordinatorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('koordinator', function (Blueprint $table) {
            $table->bigIncrements('vol_id')->unsigned()->autoIncrement();
            $table->unsignedBigInteger('divisi_id');
            $table->foreign('divisi_id')->references('divisi_id')->on('divisi')->onDelete('cascade');// Foreign key mengacu ke tabel divisi
            $table->string('nama_koor', 100);
            $table->string('email', 100);
            $table->string('password', 255);
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
        Schema::dropIfExists('koordinator');
    }
}
