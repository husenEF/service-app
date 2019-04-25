<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tire_id');
            $table->integer('user');
            $table->integer('kendaraan');
            $table->integer('tekanan_angin')->nullable();
            $table->integer('tebal_tapak')->nullable();
            $table->integer('posisi');
            $table->integer('jarakkm')->nullable();
            $table->text('catatan')->nullable();
            $table->text('kelainan')->nullable();
            $table->boolean("lepasban")->nullable();
            $table->text('alasanlepas')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }
}
