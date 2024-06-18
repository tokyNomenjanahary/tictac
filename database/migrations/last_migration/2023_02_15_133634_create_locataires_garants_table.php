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
        Schema::create('locataires_garants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('info_gen_id');
            $table->foreign('info_gen_id')->references('id')->on('locataires_general_informations')->onDelete('cascade');
            $table->string('garantPrenoms')->nullable();
            $table->string('garantNom')->nullable();
            $table->string('garantDateNaissance')->nullable();
            $table->string('garantLieuNaissance')->nullable();
            $table->string('garantEmail')->nullable();
            $table->string('garantMobile')->nullable();
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
        Schema::dropIfExists('locataires_garants');
    }
};
