<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->bigIncrements('tugas_id')->unsigned()->autoIncrement();
            $table->unsignedBigInteger('divisi_id');
            $table->foreign('divisi_id')->references('divisi_id')->on('divisi')->onDelete('cascade'); 
            $table->string('desk_tgs', 100);
            $table->date('deadline');
            $table->text('link_gdrive');
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
        Schema::dropIfExists('tugas');
    }
}
