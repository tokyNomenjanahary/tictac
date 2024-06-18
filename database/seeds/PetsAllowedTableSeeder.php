<?php

use Illuminate\Database\Seeder;

class PetsAllowedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pets_allowed')->insert([            
            'value' => 'Cats'
        ]);
        DB::table('pets_allowed')->insert([            
            'value' => 'Dogs'
        ]);
    }
}
