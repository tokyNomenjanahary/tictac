<?php

namespace App\Console;

use App\ToctocSend;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\CronAlert::class,
        Commands\ToctocSend::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->call('\App\CronUserVisit@run')->everyMinute();
        $schedule->call('\App\CronUserVisit@run')->dailyAt('23:59');
        $schedule->call('\App\MailRestartMysql@run')->hourly();
        /*$schedule->call('\App\CronEscroc@run')->everyMinute();*/

        /*$schedule->call('\App\Cron@run')->hourly();*/
        /*$schedule->call('\App\CronUserVilles@run')->everyMinute();*/

        # Le vrai Algo à decommenter pour le prod
        // $schedule->call('\App\finance_transfert@run')->everyMinute();
        $schedule->call('\App\AdExpiredCron@run')->dailyAt('23:59');
        // $schedule->call('\App\Sendecheance@run')->everyMinute();
        // $schedule->call('\App\Sendquittance@run')->everyMinute();
        # Ad Desactivation
        $schedule->call('\App\AdDesactiveCron@run')->dailyAt('23:59');
        $schedule->call('\App\viderCorbeille@run')->everyMinute();
        // $schedule->call('\App\user_transfert@run')->dailyAt('01:00');
        $schedule->call('\App\SendStatCron@runEmailStat')->dailyAt('22:30');
        $schedule->call('\App\SendStatCron@runEmailStat')->dailyAt('12:00');
        $schedule->call('\App\AdsArchive@ArchiveAds')->dailyAt('23:59');
        $schedule->call('\App\PurgeMail@vider')->monthlyOn(4,'00:00');

        //$schedule->call('\App\SendHeurSupCron@sendMailHeurSup')->dailyAt('21:00');
        //envoi du toctoc auto chaque heure
        // if(getConfig("active_toctoc") == 1)
        // {
        //     $schedule->call('\App\ToctocSend@envoiToctoc')->hourlyAt(rand(0,20))->timezone('Europe/Paris')->between('06:00', '23:00');
        //     //$schedule->call('\App\ToctocSend@testmail')->everyMinute()->timezone('Europe/Paris')->between('6:00', '23:00');
        // }
        //$schedule->call('\App\ToctocSend@testmail')->everyMinute()->between('8:26', '23:00');

        # Tester en LOCAL et en DEV && Pour Essayer l'Envoi hourlyAt(rand(0,20))
        # Ad Expired
        # $schedule->call('\App\AdExpiredCron@run')->hourly();
        # Ad Desactivation
        # $schedule->call('\App\AdDesactiveCron@run')->hourly();
        # $schedule->call('\App\ChechServerCron@run')->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
