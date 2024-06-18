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
        Schema::create('depense_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->bigInteger('ticket_id');
            $table->foreign("ticket_id")->references('id')->on('tickets');
            $table->bigInteger('logement_id');
            $table->foreign("logement_id")->references('id')->on('logements');
            $table->bigInteger('location_id');
            $table->foreign('location_id')->references('id')->on('locations_proprietaire');
            $table->bigInteger('locataire_id');
            $table->foreign('locataire_id')->references('id')->on('locataires_general_informations');
            $table->string('ticket_type');
            $table->string('payer_a');
            $table->date('date_depense');
            $table->integer('montant');
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
        Schema::dropIfExists('depense_tickets');
    }
};
