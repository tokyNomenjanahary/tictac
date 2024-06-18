<?php

namespace App\Console\Commands;

use App\AdsArchive;
use App\Http\Models\Ads\Ads;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UserArchiveCommande extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:archive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive les users plus de 6 mois';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date_recent = date('Y-m-d', strtotime('-6 month'));
    $users_anciens = DB::table('users')
    ->where('last_conection', '<', $date_recent)
    ->get();
    foreach ($users_anciens as $key => $as) {
        DB::beginTransaction();
        try {
            archive_profiles($as->id);
            $data = json_decode(json_encode($as), true);
            DB::table('archive_users')->insert($data);
            DB::table('users')->where('last_conection', '<', $date_recent)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            // Gérer l'erreur d'insertion ici
            $subject="erreur lors d'archivage des users";
            sendMailAdmin("emails.users.archiveusers", ["subject" => $subject,"erreur" => $e->getMessage()]);
            break;
        }

    }
    verifCron("ArchiveUsers","23:59");
    }
}
