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
        Schema::create('revenus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('logement_id');
            $table->unsignedBigInteger('location_id');
            $table->string('type');
            $table->string('frequence');
            $table->date('date');
            $table->unsignedBigInteger('locataire_id');
            $table->unsignedBigInteger('montant');
            $table->unsignedBigInteger('tva');
            $table->string('description');
            $table->string('information');
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
        Schema::dropIfExists('revenus');
    }
};
