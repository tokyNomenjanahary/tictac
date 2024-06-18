<?php

use Illuminate\Database\Seeder;

class GuaranteesAskedTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('guarantees_asked')->insert([            
            'value' => 'Pay slips'
        ]);
        DB::table('guarantees_asked')->insert([            
            'value' => 'Guarantor'
        ]);
        DB::table('guarantees_asked')->insert([            
            'value' => 'Deposit'
        ]);
        DB::table('guarantees_asked')->insert([            
            'value' => 'Declaration of tax'
        ]);
        DB::table('guarantees_asked')->insert([            
            'value' => 'Recommendation of last landlord'
        ]);
        DB::table('guarantees_asked')->insert([            
            'value' => 'Section 8'
        ]);
        DB::table('guarantees_asked')->insert([            
            'value' => 'Income Restricted'
       ]);
    }
}
