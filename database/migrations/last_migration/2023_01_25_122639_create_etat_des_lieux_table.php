<?php

use App\User;
use App\Location;
use App\TypeEtat;
use App\CleLocation;
use App\Fonctionnement;
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
        Schema::create('etat_des_lieux', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->tinyInteger('is_active')->default(1);
            $table->text('observation')->nullable();
            $table->foreignIdFor(Location::class)->nullable();
            $table->foreignIdFor(TypeEtat::class)->default(1);
            $table->foreignIdFor(CleLocation::class)->nullable();
            $table->foreignIdFor(User::class);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('etat_des_lieux');
    }
};
