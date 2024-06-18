<?php

use App\EtatUsure;
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
        Schema::create('etat_equipements', function (Blueprint $table) {
            $table->id();
            $table->string('element')->nullable();
            $table->string('materiaux')->nullable();
            $table->text('commentaire')->nullable();
            $table->foreignIdFor(Fonctionnement::class)->nullable();
            $table->foreignIdFor(EtatUsure::class)->nullable();
            $table->unsignedBigInteger('etat_piece_id')->nullable();        
            $table->timestamps();
            $table->foreign('etat_piece_id')->references('id')->on('etat_pieces')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etat_equipements');
    }
};
