<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Simpanan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simpanan', function (Blueprint $table) {
            $table->increments('id_simpanan');
            $table->foreignId('id_simpanan_induks')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_member')->onDelete('cascade')->nullable();
            $table->integer('bayar_pokok');
            $table->integer('bayar_wajib');
            $table->integer('bayar_manasuka');
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
        Schema::dropIfExists('simpanan');
    }
}
