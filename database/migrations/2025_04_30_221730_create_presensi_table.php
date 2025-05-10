<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->bigIncrements('presensi_id')->unsigned()->autoIncrement();
            $table->unsignedBigInteger('jadwal_id');
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwal')->onDelete('cascade');
            $table->unsignedBigInteger('vol_id'); // ✅ cocok dengan bigIncrements di volunteer
            $table->foreign('vol_id')->references('vol_id')->on('volunteer')->onDelete('cascade');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->string('desk_tgs')->nullable();
            $table->time('total_jam')->nullable();
            $table->enum('status', ['Diproses', 'Diterima', 'Ditolak']);
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
        Schema::dropIfExists('presensi');
    }
}
