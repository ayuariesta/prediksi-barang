<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChartData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('data_chart', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_bahan');
            $table->integer('nilai_aktual');
            $table->integer('nilai_prediksi');
            $table->float('nilai_mape');
            $table->string('bulan');
            $table->integer('tahun');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
