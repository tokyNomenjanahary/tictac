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
        Schema::table('inventaires', function (Blueprint $table) {
            $table->dropForeign(['logement_id']);
            $table->dropForeign(['location_id']);
            $table->foreign('logement_id')->references('id')->on('logements')->onDelete('cascade');
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
        Schema::table('inventaires', function (Blueprint $table) {
            //
        });
    }
};
