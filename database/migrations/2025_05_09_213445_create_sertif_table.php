<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSertifTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sertif', function (Blueprint $table) {
            $table->bigIncrements('sertif_id')->unsigned()->autoIncrement();
            $table->unsignedBigInteger('vol_id');
            $table->foreign('vol_id')->references('vol_id')->on('volunteer')->onDelete('cascade'); // Foreign key mengacu ke tabel divisi
            $table->integer('periode_ke'); // periode keberapa (misal: 1, 2, 3, 4)
            $table->string('file_sertifikat'); // nama file PDF sertifikat yang diupload
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
        Schema::dropIfExists('sertif');
    }
}
