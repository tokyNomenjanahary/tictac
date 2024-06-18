<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class JobMaster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'job:master';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'JobMaster qui verifie tous les taches crons executés';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $schedule = ["CronUserVisit", "AdExpiredCron", "AdDesactiveCron", "SendStatCron", "TotocController", "MailRestartMysql","user_transfert","ArchiveAds"];
        $list_schedule = [];

        foreach ($schedule as $s) {
            $verif_fichier = DB::table("verif_crons")->where("fichier", $s)->first();
            if ($verif_fichier) {
                $corn_exec = [];
                $corn_exec = array("fichier" => $verif_fichier->fichier, "heur_exec" => $verif_fichier->heur_exec, "dernier_exec" => $verif_fichier->updated_at);
                array_push($list_schedule, $corn_exec);
            }
        }

        sendMailAdmin("emails.admin.jobmaster", ["subject" => "Job Master", "list_schedule" => $list_schedule]);

    }
}
