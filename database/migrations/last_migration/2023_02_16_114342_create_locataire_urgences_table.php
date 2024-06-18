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
        Schema::create('locataire_urgences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('info_gen_id');
            $table->foreign('info_gen_id')->references('id')->on('locataires_general_informations')->onDelete('cascade');
            $table->string('urgencePrenoms')->nullable();
            $table->string('urgenceNom')->nullable();
            $table->string('urgenceDateNaissance')->nullable();
            $table->string('urgenceLieuNaissance')->nullable();
            $table->string('urgenceEmail')->nullable();
            $table->string('urgenceMobile')->nullable();
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
        Schema::dropIfExists('locataire_urgences');
    }
};
