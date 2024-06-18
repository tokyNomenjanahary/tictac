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
        Schema::create('autres_paiements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_id')->nullable();
            $table->integer('montant')->nullable();
            $table->string('locataire')->nullable();
            $table->string('mode_paiement')->nullable();
            $table->date('date');
            $table->timestamps();
            $table->foreign('finance_id')->references('id')->on('finances')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autres_paiements');
    }
};
