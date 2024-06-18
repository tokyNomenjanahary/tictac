<?php

use App\LocatairesGeneralInformations;
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
        Schema::create('locataires_complementaire_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('info_gen_id');
            $table->foreign('info_gen_id')->references('id')->on('locataires_general_informations')->onDelete('cascade');
            $table->string('NameSociete')->nullable();
            $table->string('nTva')->nullable();
            $table->string('rcs')->nullable();
            $table->string('capital')->nullable();
            $table->string('domaineActiviter')->nullable();
            $table->string('employeur')->nullable();
            $table->string('adresseProfesionnel')->nullable();
            $table->string('banque')->nullable();
            $table->string('adresseBanque')->nullable();
            $table->string('codePostalBanque')->nullable();
            $table->string('ibanBanque')->nullable();
            $table->string('swiftBicBanque')->nullable();
            $table->string('nouvelleAdresse')->nullable();
            $table->string('NotePrive')->nullable();
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
        Schema::dropIfExists('locataires_complementaire_informations');
    }
};
