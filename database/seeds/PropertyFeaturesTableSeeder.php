<?php

use Illuminate\Database\Seeder;

class PropertyFeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('property_features')->insert([            
            'value' => 'Air Conditioning'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Assigned Parking'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Balcony'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Carpet'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Ceiling Fan'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Central Heat'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Deck'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Dishwasher'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Fireplace'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Furnished'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Garden'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Hardwood Floor'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'High Ceilings'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'In Unit Laundry'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'On Site Laundry'
        ]);
        DB::table('property_features')->insert([            
            'value' => 'Walk In Closet'
        ]);
    }
}
