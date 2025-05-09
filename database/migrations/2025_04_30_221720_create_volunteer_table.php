<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVolunteerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('volunteer', function (Blueprint $table) {
            $table->bigIncrements('vol_id')->unsigned()->autoIncrement();
            $table->unsignedBigInteger('divisi_id');
            $table->foreign('divisi_id')->references('divisi_id')->on('divisi')->onDelete('cascade'); // Foreign key mengacu ke tabel divisi
            $table->unsignedBigInteger('sub_divisi_id')->nullable();
            $table->foreign('sub_divisi_id')->references('sub_divisi_id')->on('sub_divisi')->onDelete('cascade');
            $table->string('nama', 100);
            $table->string('nim', 100)->unique();
            $table->string('fakultas', 100);
            $table->string('jurusan', 100);
            $table->date('mulai_aktif');
            $table->date('akhir_aktif');
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->enum('status_etik', ['normal', 'dalam_peninjauan', 'dihentikan'])->default('normal');
            $table->string('email', 255)->unique();
            $table->string('no_rek_vlt', 100);
            $table->string('password', 255)->nullable();
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_token_expires_at')->nullable();
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
        Schema::dropIfExists('volunteer');
    }
}
