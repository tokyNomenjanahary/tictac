<?php

use App\CategorieContact;
use App\ContactLogement;
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
        Schema::create('contact_logements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(CategorieContact::class);
            $table->integer('type');
            $table->string('name');
            $table->string('first_name')->nullable();
            $table->string('email')->nullable();
            $table->string('mobile');
            $table->string('adress');
            $table->string('ville');
            $table->string('code_postal')->nullable();
            $table->string('comment')->nullable();
            $table->string('pays')->nullable();
            $table->string('unique_id_contact');
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
        Schema::dropIfExists('contact_logements');
    }
};
