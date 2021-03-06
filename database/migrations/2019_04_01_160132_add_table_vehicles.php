<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableVehicles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("user_id");
            $table->string("merek",50)->nullable();
            $table->integer("size")->nullable();
            $table->string("platnumber", 15)->nullable();
            $table->integer("update_by")->nullable()->comment("user updater");
            $table->integer("created_by")->nullable()->comment("User creator");
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
        Schema::dropIfExists('vehicles');
    }
}
