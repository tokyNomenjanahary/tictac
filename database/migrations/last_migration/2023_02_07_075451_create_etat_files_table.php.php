<?php

use App\EtatDesLieu;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('etat_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name');
            $table->string('alt');
            $table->integer('ordre');
            $table->integer('size');
            $table->tinyInteger('media_type');
            $table->unsignedBigInteger('etat_des_lieu_id')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('etat_files');
    }
};
