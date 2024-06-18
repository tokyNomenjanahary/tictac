<?php

namespace App\Http\Controllers;

use App\exectoctoc;
use App\Http\Models\Package;
use Faker\Factory;
use App\coup_de_foudre;
use App\Http\Models\Ads\Ads;
use App\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use mysql_xdevapi\Table;
use Symfony\Component\Intl\Currencies;


class TotocController extends Controller
{

    public function testcron()
    {
        $nowInParis = Carbon::now('Europe/Paris'); //Take today's date
        $data = exectoctoc::find(1);
        $minute = $data->minute;
        $customs = $data->customs;
        $customs1 = $data->customs1;

        //increment chaque minute
        $minute++;
        exectoctoc::find(1)->update(['minute' => $minute]);

        //remise a zero chaque heure pile
        if ($nowInParis->minute == 0) {
            exectoctoc::find(1)->update(['minute' => 0]);
        }

        //filtre execution entre 6h et 23h
        if ($nowInParis->hour >= 6 && $nowInParis->hour <= 23) {
            //execution heure pile ex: 6h 0mn 0s
            if ($nowInParis->minute == 0) {
                //verification toctoc est activer
                verifCron("TotocController", date("h:m:s"));
                if (getConfig("active_toctoc") == 1) {
                    $this->initToctoc();
                }
                $customs++;
                exectoctoc::find(1)->update(['customs' => $customs]);
                exectoctoc::find(1)->update(['customExec' => Carbon::now('Europe/Paris')]);
            }
        }
    }

    public function initToctoc()
    {
        $nowInParis = Carbon::now('Europe/Paris')->toDateString(); //Take today's date
        $paris4DaysAgo = Carbon::now('Europe/Paris')->addDay(-4)->toDateString(); //Take the date 4 days ago

        //scenario 1
        $info_user_post_annonce_louer_logement = DB::table("users")
            ->where("is_community", 0)
            ->join('ads', 'ads.user_id', '=', 'users.id')
            ->where("scenario_id", "=", 1)
            ->where('status', '1')
            ->whereBetween('ads.updated_at', [$paris4DaysAgo . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->get();

        //scenario 3
        $info_user_post_cherche_louer_logement = DB::table("users")
            ->where("is_community", 0)
            ->join('ads', 'ads.user_id', '=', 'users.id')
            ->where("scenario_id", "=", 3)
            ->where('status', '1')
            ->whereBetween('ads.updated_at', [$nowInParis . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->get();

        //envoie scenario 3 vers scenario 1
        $this->ToctocSend($info_user_post_cherche_louer_logement, $info_user_post_annonce_louer_logement, $nowInParis);

        //scenario 2
        $info_user_post_annonce_chambre_colocation = DB::table("users")
            ->where("is_community", 0)
            ->join('ads', 'ads.user_id', '=', 'users.id')
            ->where("scenario_id", "=", 2)
            ->where('status', '1')
            ->whereBetween('ads.updated_at', [$paris4DaysAgo . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->get();

        //scenario 4
        $info_user_post_cherche_chambre_colocation = DB::table("users")
            ->where("is_community", 0)
            ->join('ads', 'ads.user_id', '=', 'users.id')
            ->where("scenario_id", "=", 4)
            ->where('status', '1')
            ->whereBetween('ads.updated_at', [$nowInParis . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->get();

        //envoie scenario 3 vers scenario 1
        $this->ToctocSend($info_user_post_cherche_chambre_colocation, $info_user_post_annonce_chambre_colocation, $nowInParis);
    }


    public function ToctocSend($chercher, $post, $now)
    {
        foreach ($chercher as $s) {

            $user_id_receive = $s->user_id;
            $latitude_receive = $s->latitude;
            $longitude_receive = $s->longitude;
            $mail_user_receive = $s->email;
            $ads_id_receive = $s->id;

            $i = 1;
            foreach ($post as $k => $r) {

                $list_toctoc = DB::table('coup_de_foudres')
                    ->whereBetween('created_at', [$now . ' 00:00:00', $now . ' 23:59:59'])
                    ->get();
                $nb_toctoc = count($list_toctoc);
                if ($nb_toctoc < getConfig('nb_max_toctoc')) {

                    $user_id_send = $r->user_id;
                    $ads_id = $r->id;
                    $latitude_send = $r->latitude;
                    $longitude_send = $r->longitude;
                    $mail_user_send = $r->email;
                    $distance = 6366 * acos(cos(deg2rad($latitude_send))
                            * cos(deg2rad($latitude_receive))
                            * cos(deg2rad($longitude_receive) - deg2rad($longitude_send))
                            + sin(deg2rad($latitude_send)) * sin(deg2rad($latitude_receive)));

                    $distance_roud = round($distance, 4);

                    if ($distance_roud <= 40 && $user_id_receive != $user_id_send) {

                        $toctoc_j = DB::table("coup_de_foudres")
                            ->where('sender_id', $user_id_send)
                            ->where('receiver_id', $user_id_receive)
                            ->whereBetween('created_at', [$now . ' 00:00:00', $now . ' 23:59:59'])
                            ->get();

                        $nbr_toctoc_j = $toctoc_j->count();
                        $nbr_max_toctoc_user = DB::table('coup_de_foudres')
                            ->where('sender_id', $user_id_send)
                            ->whereBetween('created_at', [$now . ' 00:00:00', $now . ' 23:59:59'])
                            ->count();
                        $nbr_max_toctoc_receive = DB::table('coup_de_foudres')
                            ->where('receiver_id', $user_id_receive)
                            ->whereBetween('created_at', [$now . ' 00:00:00', $now . ' 23:59:59'])
                            ->count();

                        if ($nbr_max_toctoc_user < getConfig('free_message_flash')) {
                            if ($nbr_max_toctoc_receive < getConfig('nbr_max_toctoc_receive')) {
                                if ($nbr_toctoc_j < 1) {
                                    if ($i <= getConfig('nbr_max_toctoc_receive_exec')) {
                                        $i++;
                                        $nbr_toctoc_j = $toctoc_j->count();
                                        $ad_tracking = DB::table('ad_tracking')
                                            ->where("user_id", $user_id_send)
                                            ->where("ads_id", $ads_id)
                                            ->first();

                                        $ad = DB::table('ads')
                                            ->where("id", $ads_id)
                                            ->first();
                                        if ($ad) {
                                            DB::table('ads')
                                                ->where('id', $ads_id)
                                                ->update(['toc_toc' => intval($ad->toc_toc) + 1]);
                                        }

                                        if ($ad_tracking) {
                                            if ($ad_tracking->toc_toc != 1) {
                                                DB::table('ad_tracking')
                                                    ->where("id", $ad_tracking->id)
                                                    ->update([
                                                        "toc_toc" => $ad_tracking->toc_toc + 1
                                                    ]);
                                            }
                                        } else {
                                            DB::table('ad_tracking')->insert([
                                                "ads_id" => $ads_id,
                                                "user_id" => $user_id_send,
                                                "toc_toc" => 1
                                            ]);
                                        }

                                        $userInfo = DB::table('users')
                                            ->where('id', $user_id_receive)
                                            ->first();

                                        $annonce = DB::table('ads')
                                            ->where('id', $ads_id_receive)
                                            ->first();
                                        if ($annonce) {
                                            $userInfo->ad_title = $annonce->title;

                                            $userInfo->ad_id = $ads_id_receive;

                                            $userInfo->sender_ad_id = $ads_id;

                                            $userInfo->sender_name = $r->first_name;

                                            DB::table("coup_de_foudres")->insert([
                                                "sender_id" => $user_id_send,
                                                "receiver_id" => $user_id_receive,
                                                "ad_id" => $ads_id,
                                                "sender_ad_id" => $ads_id,
                                                "created_at" => Carbon::now('Europe/Paris'),
                                                "read_date" => Carbon::now('Europe/Paris'),
                                                "checked" => 0,
                                                "notif_checked" => 0,
                                                "toctoc_auto" => 1
                                            ]);

                                            if (!is_null($mail_user_receive)) {

                                                $subject = __('mail.message_flash');

                                                sendMail($userInfo->email, "emails.users.messageFlash", ["subject" => $subject, "UserInfo" => $userInfo, "lang" => getLangUser($user_id_receive)]);

                                            }
                                        }

                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
