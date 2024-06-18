<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Property\PropertyTypes;
use DateTime;
use PDF;
use Illuminate\Support\Facades\DB;
use App\UserProfiles;
use App\Finance;
use PhpParser\Node\Stmt\Foreach_;
use Illuminate\Console\Command;

class generationLoyer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Loyer:genere';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generation loyer mensuelle';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
  {
// $month = date('m');
$locations = DB::table("locations_proprietaire")->get();
try{
    foreach ($locations as $location) {
        $date_finance = Carbon::parse($location->date_finance);
        $date_fin = Carbon::parse($location->fin);
        if($date_finance->month < $date_fin->month){

            // $date_finance->addMonth();
            $date_finance_Actuel = now()->format('Y-m-d');
            DB::table('locations_proprietaire')->update(['date_finance' => $date_finance_Actuel]);

            $data = [
                'logement_id' => $location->logement_id,
                'locataire_id' => $location->locataire_id,
                'montant' => $location->montant,
                'location_id' => $location->id,
                'user_id' => $location->user_id,
                'Description' =>'Loyer',
                'loyer_HC' => $location->charge,
                'charge' => $location->charge,
                'debut' => now()->format('Y-m-d'),
                'fin' => $location->fin,
                'type' => 'loyer',
                'Etat' => '1',
            ];
            DB::table('finances')->insert($data);
        }
    }
}
catch (\Exception $e){
    throw new \Exception();
}
  }
}
