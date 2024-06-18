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
        Schema::create('inventaire_element_piece', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventaire_piece');
            $table->string('identifiant');
            $table->integer('nombre');
            $table->decimal('prix',10,2);
            $table->unsignedBigInteger('etat_usure_id');
            $table->text('commentaire');

            $table->foreign('inventaire_piece')->references('id')->on('inventaire_piece');
            $table->foreign('etat_usure_id')->references('id')->on('etat_usures');

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
        Schema::dropIfExists('inventaire_element_piece');
    }
};
