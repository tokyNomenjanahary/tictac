<?php

use App\EtatUsure;
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
        Schema::create('etat_properties', function (Blueprint $table) {
            $table->id();
            $table->string('element')->nullable();
            $table->string('revetement')->nullable();
            $table->text('commentaire')->nullable();
            $table->foreignIdFor(EtatUsure::class)->nullable();
            $table->unsignedBigInteger('etat_piece_id');
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
        Schema::dropIfExists('etat_properties');
    }
};
