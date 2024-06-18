<?php

namespace App\Console\Commands;

use App\AdsArchive;
use App\Http\Models\Ads\Ads;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AdsArchiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ads:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive les annonces plus de 3 mois';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $paris3MonthsAgo = Carbon::now('Europe/Paris')->addMonths(-3)->toDateString(); // Take the date 3 month ago
        $adsLessThan3Months = Ads::where('updated_at', '<', $paris3MonthsAgo . ' 00:00:00')->get()->toArray();  // Take ads less than 3 months

        foreach ($adsLessThan3Months as $adsLessThan3Month) {
            // Archiver les anciennes annonces
            AdsArchive::insert($adsLessThan3Month);
            //verifie que les donnees ont bien ete entrer
            $verification = AdsArchive::where('id', $adsLessThan3Month['id'])->first();
            //supprime les ancienne annonces dans ads
            if ($verification) {
                Ads::where('id', $adsLessThan3Month['id'])->delete();
            }
        }
        verifCron("ArchiveAds","23:59");
    }
}
