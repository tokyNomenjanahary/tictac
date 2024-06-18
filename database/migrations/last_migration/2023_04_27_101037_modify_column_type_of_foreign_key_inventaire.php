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
            $table->unsignedBigInteger('logement_id')->change();
            $table->unsignedBigInteger('location_id')->change();
            $table->foreign('logement_id')->references('id')->on('logements');
            $table->foreign('location_id')->references('id')->on('locations_proprietaire');
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
            $table->dropForeign(['logement_id']);
            $table->dropForeign(['location_id']);
            $table->string('logement_id')->change();
            $table->string('location_id')->change();
        });
    }
};
