<?php

use App\Logement;
use App\TypeContratDiagnostic;
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
        Schema::create('contrat_diagnostic_logements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(TypeContratDiagnostic::class)->nullable();
            $table->string('document')->nullable();
            $table->string('document_original_name')->nullable();
            $table->string('description');
            $table->date('date_establishment')->nullable();
            $table->date('due_date')->nullable();
            $table->string('unique_contrat_diagnostic');
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
        Schema::dropIfExists('contrat_diagnostic_logements');
    }
};
