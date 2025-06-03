<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeskDivTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desk_div', function (Blueprint $table) {
            $table->bigIncrements('deskripsi_id')->unsigned()->autoIncrement();;
            $table->unsignedBigInteger('divisi_id');
            $table->text('deskripsi');
            
            $table->foreign('divisi_id')->references('divisi_id')->on('divisi')->onDelete('cascade');
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
        Schema::dropIfExists('desk_div');
    }
}
