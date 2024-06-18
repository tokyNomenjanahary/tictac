<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fichier_location', function (Blueprint $table) {
            $table->id();
            $table->string('folder');
            $table->string('image');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->timestamps();
            $table->foreign('location_id')->references('id')->on('locations_proprietaire')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fichier_location');
    }
};
