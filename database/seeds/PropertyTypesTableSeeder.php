<?php

use Illuminate\Database\Seeder;

class PropertyTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('property_types')->insert([            
            'value' => 'Single Family Home'
        ]);
        DB::table('property_types')->insert([            
            'value' => 'Room'
        ]);
        DB::table('property_types')->insert([            
            'value' => 'Townhouse'
        ]);
        DB::table('property_types')->insert([            
            'value' => 'Apartment'
        ]);
        DB::table('property_types')->insert([            
            'value' => 'Multifamily'
        ]);
        DB::table('property_types')->insert([            
            'value' => 'Room in shared apartment'
        ]);
        DB::table('property_types')->insert([            
            'value' => 'Individual studio'
        ]);
        DB::table('property_types')->insert([            
            'value' => 'Room in residence'
        ]);
    }
}
