<?php

use Illuminate\Database\Seeder;

class PropertyRulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('property_rules')->insert([            
            'value' => 'Couples accepted'
        ]);
        DB::table('property_rules')->insert([            
            'value' => 'Pets accepted'
        ]);
    }
}
