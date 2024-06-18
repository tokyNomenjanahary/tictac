<?php

use App\Logement;
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
        Schema::create('info_complementaire_logements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Logement::class);
            $table->integer('type_habitat');
            $table->integer('coproprietaire');
            $table->string('autre_dependance');
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
        Schema::dropIfExists('info_complementaire_logements');
    }
};
