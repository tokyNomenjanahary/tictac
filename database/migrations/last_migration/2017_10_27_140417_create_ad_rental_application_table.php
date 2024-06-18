<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdRentalApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_rental_application', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_id');
            $table->float('actual_renting_price', 15,4);
            $table->enum('occupation', ['Student', 'Salaried'])->nullable();
            $table->string('job_title');
            $table->unsignedInteger('monthly_salary')->nullable();
            $table->date('start_date')->nullable();
            $table->string('company_name')->nullable();
            $table->timestamps();
            
            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ad_rental_application');
    }
}
