<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAdsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //mihintsy
    public function up()
    {
        Schema::table('ads', function (Blueprint $table) {
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->string('kilometrage')->nullable();
            $table->string('carburant')->nullable();
            $table->integer('couleur')->nullable();
            $table->integer('nombre_portes')->nullable();
            $table->integer('nombre_place')->nullable();
            $table->string('puissance')->nullable();
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
