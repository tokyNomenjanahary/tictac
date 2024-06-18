<?php

use App\User;
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
        Schema::create('locataires_general_informations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('locataireType');
            $table->string('TenantPhoto')->nullable();
            $table->string('civilite')->nullable();
            $table->string('TenantFirstName');
            $table->string('TenantLastName');
            $table->string('TenantBirthDate')->nullable();
            $table->string('TenantBirthPlace')->nullable();
            $table->string('TenantProfession')->nullable();
            $table->string('TenantRevenus')->nullable();
            $table->string('LandlordIDType')->nullable();
            $table->string('TenantIDNumber')->nullable();
            $table->string('TenantIDExpiry')->nullable();
            $table->string('TenantIDCard')->nullable();
            $table->string('TenantEmail')->nullable();
            $table->boolean('send_invite')->nullable();
            $table->string('TenantMobilePhone')->nullable();
            $table->string('TenantAddress')->nullable();
            $table->string('TenantCity')->nullable();
            $table->string('TenantZip')->nullable();
            $table->string('TenantState')->nullable();
            $table->string('country_selector')->nullable();
            $table->boolean('archiver')->default(0)->nullable();
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
        Schema::dropIfExists('locataires_general_information');
    }
};
