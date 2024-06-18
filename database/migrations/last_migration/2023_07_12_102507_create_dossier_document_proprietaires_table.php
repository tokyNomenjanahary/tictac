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
        Schema::create('dossier_document_proprietaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_proprietaires_id')->constrained();
            $table->unsignedBigInteger('document_id');
            $table->foreign("document_id")->references('id')->on('document');
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
        Schema::dropIfExists('dossier_document_proprietaires');
    }
};
