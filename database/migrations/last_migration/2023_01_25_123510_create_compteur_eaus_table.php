<?php

use App\EtatDesLieu;
use App\Fonctionnement;
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
        Schema::create('compteur_eaux', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('numero')->nullable();
            $table->integer('volume')->nullable();
            $table->text('observation')->nullable();
            $table->unsignedBigInteger('etat_des_lieu_id')->nullable();
            $table->timestamps();
            $table->foreignIdFor(Fonctionnement::class)->nullable();
            $table->foreign('etat_des_lieu_id')->references('id')->on('etat_des_lieux')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compteur_eaux');
    }
};
