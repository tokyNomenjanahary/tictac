<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ad_id');
            $table->unsignedInteger('property_type_id');
            $table->float('min_surface_area', 15,4);
            $table->float('max_surface_area', 15,4)->nullable();
            $table->enum('preferred_gender', ['Man', 'Woman', 'does not matter'])->nullable();
            $table->enum('preferred_occupation', ['Student', 'Salaried', 'does not matter'])->nullable();
            $table->unsignedInteger('age_range_from')->nullable();
            $table->unsignedInteger('age_range_to')->nullable();
            $table->unsignedInteger('bedrooms');
            $table->unsignedInteger('bathrooms');
            $table->unsignedInteger('partial_bathrooms')->nullable();
            $table->string('pets_allowed_ids', 100)->nullable();
            $table->enum('leasing_fees', ['Yes', 'No'])->nullable();
            $table->string('property_features_ids', 100)->nullable();
            $table->string('building_amenities_ids', 100)->nullable();
            $table->string('guarantee_asked_ids', 100)->nullable();
            $table->enum('kitchen_separated', ['Yes'])->nullable();
            $table->unsignedInteger('minimum_stay')->nullable();
            $table->float('deposit_price', 15,4)->nullable();
            $table->enum('add_utility_costs', ['0', '1'])->default('0');
            $table->float('utility_cost', 15,4)->nullable();
            $table->string('property_rules_ids', 100)->nullable();
            $table->unsignedInteger('no_of_roommates')->nullable();
            $table->enum('furnished_unfurnished', ['Furnished', 'Unfurnished']);
            $table->enum('apl_rights', ['Yes', 'No'])->nullable();
            $table->enum('advertising_as', ['Roommate', 'Landlord', 'Agent'])->nullable();
            $table->enum('accept_as', ['Roommate', 'Landlord', 'Agent'])->nullable();
            $table->float('broker_fees', 15,4)->nullable();
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
        Schema::dropIfExists('ad_infos');
    }
}
