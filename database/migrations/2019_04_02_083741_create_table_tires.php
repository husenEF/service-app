<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTires extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("id_vehicle")->default("0");
            $table->integer("created_by");
            $table->integer("posistion")->default("0");
            $table->string("merek", 50);
            $table->integer("ukuran")->nullable();
            $table->string("nomor", 50)->nullable();
            $table->string("stempel", 50)->nullable();
            $table->date("buy_date");
            $table->string("images", 50)->nullable();
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
        Schema::dropIfExists('tires');
    }
}
