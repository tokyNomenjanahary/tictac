<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CronAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alert:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
    public function handle()
    {
        /*DB::table('cron')->insert(['field' => "command"]);
        $email = "";
        \Mail::raw("Test Hourly Update", function($message) use ($email)
        {
               $message->from('info@bailti.fr');
               $message->to($email)->subject('Hourly Update');
        });*/
    }
}
