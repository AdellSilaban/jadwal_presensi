<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugasVolunteerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tugas_volunteer', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tugas_id');
            $table->foreign('tugas_id')->references('tugas_id')->on('tugas')->onDelete('cascade');
            $table->unsignedBigInteger('vol_id');
            $table->foreign('vol_id')->references('vol_id')->on('volunteer')->onDelete('cascade');
            $table->enum('status', ['Belum Dikerjakan','Sedang Dikerjakan', 'Tugas Selesai'])->default('Belum Dikerjakan');
            $table->string('peran')->nullable();
            $table->enum('status_validasi', ['Pending','Selesai', 'Revisi'])->default('Pending');
            $table->text('revisi_catatan')->nullable();
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
        Schema::dropIfExists('tugas_volunteer');
    }
}
