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
        Schema::create('modele_document', function (Blueprint $table) {
            $table->id();
            $table->string('intitule');
            $table->string('nom_fichier');
            $table->string('path');
            $table->unsignedInteger('type_modele');
            $table->foreign('type_modele')->references('id')->on('type_modele_document')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modele_document');
    }
};
