<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Mail;

class sendMailPremium extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:mailPremium';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'envoie mail pour le support de version premium';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //initialisation de la table si la table est encore vide
        $this->insertTable();

        //envoi du mail
        $this->sendMailUserPremium();

        return 0;
    }

    private function insertTable()
    {
        $table = DB::table("date_dernier_envoie_mail_premium")->get()->first();
        if ($table == null) {
            DB::table("date_dernier_envoie_mail_premium")->insert(['dernier_envoie' => date("Y-m-d")]);
        }
    }

    private function sendMailUserPremium()
    {
        $emails = $this->getPackagesUserMails();

        foreach ($emails as $email) {

            $subject=i18n('mail.support_premium_subject',$email['lang']);

            sendMail($email['email'],'emails.premiumSupport', [
                "lang" => $email['lang'],
                "subject" => $subject
            ]);
        }
    }

    private function getNewPackagesPremium()
    {
        //date en ce moment
        $now = now()->toDateTimeString();

        //selection de dernier date d'envoie mail
        $last = DB::table("date_dernier_envoie_mail_premium")->first()->dernier_envoie;


        //selection des packages dont l'user n'est pas encore reçu l'email
        $new_premiums = DB::table('user_packages')
            ->where('created_at', '>=', $last)
            ->get();

        //update la dernier date d'envoie
        DB::table("date_dernier_envoie_mail_premium")->whereRaw('1=1')
            ->update(["dernier_envoie" => $now]);

        return $new_premiums;
    }

    private function getPackagesUserMails()
    {
        //cette fonction retourne le mail de chaque utilisateur 
        //avec son lang pendant le payement
        $premiums = $this->getNewPackagesPremium();
        $results = [];

        $premiums->each(function ($item) use (&$results) {
            $user_id = $item->user_id;
            $lang = $this->getLangUser($user_id);
            $email = DB::table('users')->where('id', $user_id)->first()->email;
            if ($email)
                $results[] = ['email' => $email, 'lang' => $lang];
        });

        return $results;
    }

    public function getLangUser($user_id)
    {
        $lang = DB::table('users_langue_navigateur')
            ->Where('user_id', $user_id)
            ->first();

        if ($lang == null)
            return 'en';
        else if ($this->lang_valide($lang->lang)) {
            return $lang->lang;
        } else {
            return 'en';
        }
    }

    private function lang_valide($lang)
    {
        $langs = DB::table('langs')->get();

        $lang = $langs->where('code_iso', $lang)->first();

        if ($lang == null)
            return false;

        return true;
    }
}
