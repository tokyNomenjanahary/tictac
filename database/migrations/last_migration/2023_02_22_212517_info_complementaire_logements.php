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
        Schema::create('info_complementaire_logements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('logement_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->integer('type_habitat')->nullable();
            $table->integer('coproprietaire')->nullable();
            $table->string('autre_dependance')->nullable();
            $table->integer('taxe_habitation')->nullable();
            $table->integer('taxe_fonciere')->nullable();
            $table->date('date_acquisition')->nullable();
            $table->integer('prix_acquisition')->nullable();
            $table->integer('frais_acquisition')->nullable();
            $table->integer('valeur_actuel')->nullable();
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
        Schema::dropIfExists('info_complementaire_logements');
    }
};
