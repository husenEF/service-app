<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text("dataname");
            $table->string("status")->nullable();
            $table->integer('id_tires')->nullable();
            $table->integer("id_vehicle")->nullable();
            $table->integer("created_by")->nullable();
            $table->integer("posistion")->default("0");
            $table->string("merek")->nullable();
            $table->date("buy_date")->nullable();
            $table->string("images")->nullable();
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
        Schema::dropIfExists('histories');
    }
}
