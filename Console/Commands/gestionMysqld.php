<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\MailErrorAdmin;


class gestionMysqld extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gestionMysqld';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command pour restarter mysql automatiquement si le cpu est plus de 400% avec mysqld';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        /**
         * execution du commade top
         *  cette commade n'est pas accessible via exec fonction php
         *  car c'est une commande installé
         *  donc il faut parcourir directement avec son path bin
         */

       ob_start();
       passthru('/usr/bin/top -b -n 1 | grep mysqld');
       
       $a = ob_get_clean();
    
       $result=[];

       /**
        * traitement du resulat
        * echange du resulat en tableu
        */

        $tab=explode(' ',$a);
        $i=0;

        foreach($tab as $k=>$val)
        {
            if(!in_array($val,['',' ','\t','   ',chr(27)]))
            {
            $result[$i]=$val;
            ++$i;
            }
        }

        /**
         *  le colone du cpu est 8
         */

        if (!empty($result[8])) {
            $mysqldCPU=explode(',', $result[8]);
            $mysqldCPU=(int)$mysqldCPU[0];
        }
        else
            $mysqldCPU=0;

        
        $this->traitement($mysqldCPU);

        return 0;
    }

    private function traitement($cpu)
    {
        /**
         *  selectioner tous les requete en cours sur mysql
         */
        $exec=DB::select("SHOW FULL processlist");

        echo("cpu: ".$cpu."\n");

        if ($cpu>=400 && $this->hasTimeUp($exec) ) {


            //restart automatique du mysql

            $is_restart = $this->restartMysql();

            //envoi mail si le restart est fait

            if($is_restart)
            {
                echo("restart ok\n");
                $admin=MailErrorAdmin::all();
                foreach ($admin as $l) {
                    sendMail($l->email,'emails.admin.restartMysql',
                    [
                        'cpu'=>$cpu,
                        'subject'=>__('mail.cpu_usage_alert')
                    ]);
                }
            }
            else{
                echo("restart error\n");
            }

        }

        else
        {
            echo("le mysql est encore stable\n");
        }


        return 0;
    
    }

    /**
     *  verifier s'il y a des requete qui prends 1 sur le colone time
     */

    private function hasTimeUp($processlist)
    {
        foreach($processlist as $key=>$value)
        {
            if($value->Info!=null && $value->Time >=1 )
                return true;

        }

        return false;
    }


    /**
     *  fonction restart automatique du mysql
     */

    private function restartMysql()
    {
        $path=app_path('Console/Commands/bin');
        $result_commade=shell_exec("$path/commandRestart");
        if($result_commade!='success')
        {
            return false;
        }
        return true;
    }
}

