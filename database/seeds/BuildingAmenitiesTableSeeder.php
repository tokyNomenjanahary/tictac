<?php

use Illuminate\Database\Seeder;

class BuildingAmenitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('building_amenities')->insert([            
            'value' => 'Business Center'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Concierge Service'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Door Person'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Dry Cleaning'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Elevator'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Fitness Center'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Garage Parking'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'On Site Laundry'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Onsite Management'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Outdoor Space'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Package Service'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => "Residents' Lounge"
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Roof Deck'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'In Unit Laundry'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Storage'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Swimming Pool'
        ]);
        DB::table('building_amenities')->insert([            
            'value' => 'Wheelchair Access'
        ]);
    }
}
