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
        Schema::create('location_revision_loyer', function (Blueprint $table) {
            $table->id();
            $table->decimal('ancien_loyer', 8, 2);
            $table->decimal('loyer_hors_charge', 8, 2);
            $table->decimal('charge', 8, 2);
            $table->decimal('augmentation', 8, 2);
            $table->integer('indice_depart');
            $table->integer('nouvel_indice');
            $table->date('date_revision');
            $table->unsignedBigInteger('location_id')->nullable();
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
        Schema::dropIfExists('location_revision_loyer');
    }
};
