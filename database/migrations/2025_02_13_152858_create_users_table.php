<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('user_id')->unsigned()->autoIncrement();
            $table->unsignedBigInteger('divisi_id')->nullable();
            $table->foreign('divisi_id')->references('divisi_id')->on('divisi')->onDelete('cascade'); 
            $table->string('nama', 100);
            $table->string('jabatan', 100);
            $table->string('email', 100);
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
