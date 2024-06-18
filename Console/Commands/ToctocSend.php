<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Mail;

class ToctocSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ToctocSend:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoi des toctoc';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    // public function handle(Schedule $schedule)
    // {
    //     Mail::to("larry1ratianarivo@gmail.com")->send(new testCron());
    //     info('mail bien envoyé');
    // }
}
