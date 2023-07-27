<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBahanPanganTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bahan_pangan', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kategori_id')->unsigned()->nullable();
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_bahan')->nullable();
            $table->integer('bulan')->nullable();
            $table->integer('tahun')->nullable();
            $table->decimal('harga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bahan_pangan');
    }
}
