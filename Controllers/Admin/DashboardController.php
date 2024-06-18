<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\GoogleTagManager;
use App\Http\Models\Signal;
use App\Http\Models\Ads\Ads;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Models\variable;
use Illuminate\Support\Carbon;
use App\Http\Models\domaine;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Psy\Command\WhereamiCommand;

class DashboardController extends Controller
{

    private $perpage;

    public function __construct()
    {
        $this->perpage = config('app.perpage');
    }

    function Config(Request $request)
    {
        return view('admin.config');
    }

    public function togglePayPal($value)
    {
        DB::table("config")->where('varname', "is_active_paypal")->update(['value' => $value]);
        return redirect()->back();
    }

    function saveConfig(Request $request)
    {
        updateConfig("nombre_colocataire_villes", $request->nombre_colocataire_villes);
        updateConfig("nombre_colocation_villes", $request->nombre_colocation_villes);
        updateConfig("nombre_profils_sponsorises_acceuil", $request->nombre_profils_sponsorises_acceuil);
        updateConfig("nombre_annonce_sponsorises_acceuil", $request->nombre_annonce_sponsorises_acceuil);
        updateConfig("nb_per_page_search", $request->nb_per_page_search);

        updateConfig("bitly_token", $request->bitly_token);
        updateConfig("email_blink", $request->email_blink);
        updateConfig("mdp_blink", $request->mdp_blink);
        updateConfig("nb_max_contact", $request->nb_max_contact);
        updateConfig("nb_publication", $request->nb_publication);
        updateConfig("temps_requete_js", $request->temps_requete_js);

        ////
        updateConfig("free_message_flash", $request->free_message_flash);
        ////
        updateConfig("nbr_email", $request->nbr_email);
        updateConfig("nbr_email_moin", $request->nbr_email_moin);
        updateConfig("nbr_annonce", $request->nbr_annonce);

        updateConfig("googletagmanager", $request->googletagmanager);
        updateConfig("google_analytic", $request->google_analytic);
        updateConfig("adsense", $request->adsense);
        updateConfig("pixel_id", $request->pixel_id);
        updateConfig("annonce_ads", $request->annonce_ads);



        # Expiration
        updateConfig("day_delay", $request->delay);
        //nombre max pour les toctoc à chaque envoi sur cron
        updateConfig("nb_max_toctoc", $request->nb_max_toctoc);
        //nombre max de toctoc pour le receveur
        updateConfig("nbr_max_toctoc_receive", $request->nbr_max_toctoc_receive);

        updateConfig("nbr_max_toctoc_receive_exec", $request->nbr_max_toctoc_receive_exec);
        # Nombre de jour pour l'affichage des annonces crées par les community
        updateConfig("nbJourAdsCommunity",$request->nbJourAdsCommunity);

        $request->session()->flash('status', "Config saved successfully");
        return redirect()->back();
    }

    /*etdit photo couverture bailti*/
    public function editPhotoCouverture(Request $request)
    {
        if ($request->file('file_profile_photos')) {

            $file = $request->file('file_profile_photos');
            $destinationPathProfilePic = base_path() . '/storage/uploads/cover_pics/';
            $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPathProfilePic, $file_name);
            // pasteLogo($destinationPathProfilePic . $file_name);


            updateConfig("photo_couverture", $file_name);

            $response['percent'] = calculProfilPercent();
            $response['redirect_url'] = route('edit.profile');
            $response['message'] = __('Photo de couverture a été modifié avec succès.');
            $response['error'] = 'yes';
        } else {
            $response['error'] = 'no';
            $response['percent'] = calculProfilPercent();
            $response['redirect_url'] = route('edit.profile');
            $response['message'] = __('veuillez sélectionner un autre fichier');
        }

        return response()->json($response);
    }



    public function contactShow(Request $request)
    {
        $table = "user_show_tel";

        $date = date("Y-m-d");
        $orderBy = 'user_show_tel.user_id DESC';
        $search_query = [];
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
        }

        $statement = "(SELECT user_id, first_name from user_show_fb INNER JOIN users ON users.id=user_id where date(date) <='" . $search_query['end'] . "' AND date(date) >= '" . $search_query['start'] . "') UNION (SELECT user_id, first_name from user_show_fb INNER JOIN users ON users.id=user_id where date(date) <='" . $search_query['end'] . "' AND date(date) >= '" . $search_query['start'] . "')";
        $users = DB::select(DB::raw($statement));
        foreach ($users as $key => $u) {
            $users[$key]->nb_tel = DB::table("user_show_tel")
                ->where("user_id", $u->user_id)
                ->whereRaw("DATE(user_show_tel.date) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(user_show_tel.date) <= '" . $search_query['end'] . "'")
                ->count();
            $users[$key]->nb_fb = DB::table("user_show_fb")
                ->where("user_id", $u->user_id)
                ->whereRaw("DATE(user_show_fb.date) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(user_show_fb.date) <= '" . $search_query['end'] . "'")
                ->count();
        }
        if (isset($start_date) && isset($end_date))
            return view('admin.contact_show', compact('users', 'start_date', 'end_date'));
        else
            return view('admin.contact_show', compact('users'));
    }

    public function linkSource(Request $request)
    {
        $date = date("Y-m-d");
        $orderBy = 'link_vente_source.id DESC';
        $search_query = [];
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
        }


        $search_query['payment'] = 0;
        if (!empty($request->payment) && $request->payment == 1) {
            $search_query['payment'] = 1;
            $query = DB::table("payments")
                ->select(DB::raw('user_id, device, first_name, link, action, date(link_vente_source.date) as date '))
                ->join("link_vente_source", "link_vente_source.id", "payments.id_link")
                ->join("users", "users.id", "link_vente_source.user_id")
                ->whereRaw("DATE(link_vente_source.date) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(link_vente_source.date) <= '" . $search_query['end'] . "'")
                ->orderByRaw($orderBy);
        } else {
            $query = DB::table("link_vente_source")->join("users", "users.id", "link_vente_source.user_id")
                ->select(DB::raw('user_id, device, first_name, link, action, date(link_vente_source.date) as date'))
                ->whereRaw("DATE(link_vente_source.date) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(link_vente_source.date) <= '" . $search_query['end'] . "'")
                ->orderByRaw($orderBy);
        }

        $users = $query->paginate($this->perpage);
        if (isset($start_date) && isset($end_date))
            return view('admin.link_source', compact('users', 'search_query', 'start_date', 'end_date'));
        else
            return view('admin.link_source', compact('users', 'search_query'));
    }

    public function TocTocClickMail(Request $request)
    {
        $date = date("Y-m-d");
        $date_report = date('d-m-Y');
        $orderBy = 'toctoc_mail_click.id DESC';
        $search_date = [];
        $payment = 0;
        if (!empty($request->date)) {
            $date_report = $request->date;
            $date = date("Y-m-d", strtotime(convertDateWithTiret($request->date)));
            $search_date['date'] = $date;
        }

        $query = DB::table("toctoc_mail_click")->join("users", "users.id", "toctoc_mail_click.user_id")
            ->whereRaw("DATE(date) = '" . $date . "'")
            ->orderByRaw($orderBy);

        $users = $query->paginate($this->perpage);
        return view('admin.click_toctoc_mail', compact('users', 'search_date', 'date_report'));
    }

    public function dashboard(Request $request)
    {
        return view('admin.dashboard');
    }
    public function dashboardCourbes(Request $request)
    {
        $date_report = date("Y-m-d");
        $show = '0';
        if (isset($request->debut) && isset($request->limit)) {
            $date_report_debut = date("Y-m-d", strtotime(convertDateWithTiret($request->debut))) . ' 00:00:00';
            $date_report_limit = date("Y-m-d", strtotime(convertDateWithTiret($request->limit))) . " 23:59:59";
        } else {
            if (isset($request->show)) {
                $show = $request->show;
                switch ($show) {
                    case 'D':
                        if (isset($request->date_report)) {
                            $date_report = date("Y-m-d", strtotime(convertDateWithTiret($request->date_report)));
                            if ($date_report == date("Y-m-d")) {
                                $heureLimit = '23:59:00';
                            } else {
                                $heureLimit = '23:59:00';
                            }
                            $date_report_debut = $date_report . ' 00:00:00';
                            $date_report_limit = $date_report . " " . $heureLimit;
                        } else {
                            $date_report = date('Y-m-d');
                            $date_report_debut = date('Y-m-d') . " 00:00:00";
                            $date_report_limit = $date_report . " " . $heureLimit;
                        }
                        break;
                    case 'W':
                        $date_report_debut = getDateByDecalage(-7) . " 00:00:00";
                        $date_report_limit = date('Y-m-d') . " 23:59:59";
                        break;
                    case 'M':
                        $date_report_debut = date('Y-m-01') . " 00:00:00";
                        $date_report_limit  = date('Y-m-t') . " 23:59:59";
                        break;
                    default:
                        # code...
                        break;
                }
            } else {
                if (isset($request->date_report)) {
                    $date_report = date("Y-m-d", strtotime(convertDateWithTiret($request->date_report)));
                    if ($date_report == date("Y-m-d")) {
                        $heureLimit = date('H:i:s');
                    } else {
                        $heureLimit = '23:59:00';
                    }
                    $date_report_debut = $date_report . ' 00:00:00';
                    $date_report_limit = $date_report . " " . $heureLimit;
                } else {
                    $date_report = date('Y-m-d');
                    $date_report_debut = date('Y-m-d') . " 00:00:00";
                    $date_report_limit = date('Y-m-d 23:59:59');
                }
            }
        }

        $whereRaw = "1=1";
        $adComunity = true;
        if (isset($request->adComunity)) {
            $adComunity = ($request->adComunity == "true") ? true : false;

            if (!$adComunity) {
                $whereRaw .= " AND comunity_id IS NULL";
            }
        }
        $ads_count_sc_1 = Ads::where('scenario_id', '1')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_count_sc_2 = Ads::where('scenario_id', '2')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_count_sc_3 = Ads::where('scenario_id', '3')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_count_sc_4 = Ads::where('scenario_id', '4')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_count_sc_5 = Ads::where('scenario_id', '5')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_real_posted_count = Ads::join("users", "users.id", "ads.user_id")->whereNotNull('email')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();
        $nb_coup_de_foudre = DB::table("coup_de_foudres")->where('created_at', '>=', $date_report_debut)->where('created_at', '<=', $date_report_limit)->count();

        $signal_ad_count = Signal::where("treaty", 0)->join("ads", "ads.id", "signal_ad.ad_id")->where("admin_approve", "0")->where('signal_ad.created_at', '<=', $date_report_limit)->where('signal_ad.created_at', '>=', $date_report_debut)->count();

        $users_count = User::where('user_type_id', 1)->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->count();
        $users_real_count = User::where('user_type_id', 1)->whereNotNull("users.email")->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->count();

        $messageCount = DB::table('messages')->where('created_at', '>=', $date_report_debut)->where('created_at', '<=', $date_report_limit)->count();

        $comunityCount =  Ads::whereNotNull("comunity_id")->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();

        $newsletterSubscriptionCount = DB::table('newsletter')->where('date', '>=', $date_report_debut)->where('date', '<=', $date_report_limit)->count();
        $contactCount = DB::table('contact')->where('date', '>=', $date_report_debut)->where('date', '<=', $date_report_limit)->count();
        $parrainage_active = DB::table('user_parainage')->where("statut", 1)->where('date', '>=', $date_report_debut)->where('date', '<=', $date_report_limit)->count();
        $userSubscriptionCount = DB::table("user_packages")->whereNotNull("payment_id")->where('created_at', '>=', $date_report_debut)->where('created_at', '<=', $date_report_limit)->count();

        $activateAdsCount = DB::table('ads')->whereNotNull("comunity_id")->join("users", "users.id", "ads.user_id")->whereNotNull("users.email")->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();
        $villes = DB::table('search')
            ->select(DB::raw('ville,count(distinct(user_id)) as nb'))
            ->where('date', '<=', $date_report_limit)
            ->where('date', '>=', $date_report_debut)
            ->groupBy("ville")
            ->orderBy('nb', "desc")
            ->paginate(15);
        $pub = DB::table("pub")->where("id", 6)->first();
        $pubClickCount = DB::table("pub_click")->where("id_pub", 6)->where('created_at', $date_report)->whereNull("type")->count();
        $pubClickMail = DB::table("pub_click")->where("id_pub", 6)->where('created_at', $date_report)->where("type", "mail")->count();
        $date_report = date("d/m/Y", strtotime($date_report));
        $date_report_debut = date("d/m/Y", strtotime($date_report_debut));
        $date_report_limit = date("d/m/Y", strtotime($date_report_limit));

        return view('admin.dashboardCourbe', compact('ads_count_sc_1', 'ads_count_sc_2', 'ads_count_sc_3', 'ads_count_sc_4', 'ads_count_sc_5', 'signal_ad_count', 'users_count', "messageCount", "userSubscriptionCount", "comunityCount", "contactCount", "newsletterSubscriptionCount", "users_real_count", "ads_real_posted_count", "pub", "pubClickCount", "nb_coup_de_foudre", "activateAdsCount", "date_report", "show", 'date_report_debut', "date_report_limit", "parrainage_active", "pubClickMail", 'villes', 'adComunity'));
    }
    //ici

    public function affiche_domaine()
    {
        $domaines = DB::table('domaine')->get();
        $proprietes = DB::table('propriete')->get();
        $valeurs = DB::select("SELECT domaine.domaine,propriete.nom,domaine_pro.valeur,domaine_pro.id FROM domaine,propriete,domaine_pro WHERE
        domaine.id=domaine_pro.domaine_id and propriete.id=domaine_pro.propriete_id");
        return view('admin.varlanguage', compact('valeurs', 'domaines', 'proprietes'));
    }
    public function ajout_valeur()
    {
        return view('admin.ajout_valeur');
    }
    public function sauver_valeur(Request $request)
    {
        $resultats = DB::table('propriete')->where('nom', $request->colonne)->get();

        if ($resultats->isEmpty()) {
            $propriete = strtolower($request->colonne);
            $data = array();
            $data['nom'] = $propriete;

            DB::table('propriete')
                ->insert($data);
            Session::put('message5', ' insertion succèss');
            return redirect()->route('affiche_domaine');
        } else {
            Session::put('message', 'cette propriete est déjà existe dans la table');
            return redirect()->route('ajout_valeur');
        }
    }
    public function sauver_value(Request $request)
    {
         //dd($request->all());
        $recent_prop = DB::table('domaine_pro')
            ->where('propriete_id', $request->propriete)
            ->where('domaine_id', $request->categorie)
            ->get();
        // dd($recent_prop);
        if ($recent_prop->isEmpty()) {
            $valeur = $request->valeur;
            $id_propriete = $request->propriete;
            $id_propriete1 = $id_propriete[0];
            $data = array();
            $data['domaine_id'] = $request->categorie;
            $data['propriete_id'] = $id_propriete1;
            $data['valeur'] = $valeur;

            DB::table('domaine_pro')
                ->insert($data);

            Session::put('message2', ' insertion succèss');
            return redirect()->route('affiche_domaine');
        } else {
            Session::put('message3', 'Cette domaine contient déjà un valeur');
            return redirect()->route('ajout_propriete');
        }
    }
    public function supprimer_propriete()
    {
        $props = DB::table('propriete')
            ->get();

        return view('admin.list_propriete', compact('props'));
    }
    public function delete_property($id)
    {
        DB::table('domaine_pro')
            ->where('propriete_id', $id)
            ->delete();

        DB::table('propriete')
            ->where('id', $id)
            ->delete();
        Session::put('message', ' Supression succèss');
        return redirect()->route('supprimer_propriete');
    }
    public function supprimer_domaine($id)
    {
        DB::table('domaine')
            ->where('id', $id)
            ->delete();
        Session::put('message4', ' Supression succèss');
        return redirect()->route('affiche_domaine');
    }
    public function supprimer_valeur($id)
    {
        DB::table('domaine_pro')
            ->where('id', $id)
            ->delete();

        Session::put('message', ' Supression succèss');
        return redirect()->route('affiche_domaine');
    }
    public function editer_valeur($id)
    {
        $devises = DB::table('devises')->get();
        $langues = DB::table('langues')->get();
        $timezones = DB::table('timezone')->get();
        $valeurs = DB::select("SELECT domaine.domaine,propriete.nom,domaine_pro.valeur,domaine_pro.id FROM domaine,propriete,domaine_pro WHERE
    domaine.id=domaine_pro.domaine_id and propriete.id=domaine_pro.propriete_id and domaine_pro.id=$id");

        Session::put('id', $id);

        return view('admin.modification_valeur', compact('valeurs','timezones','langues','devises'));
    }
    public function sauver_modif(Request $request)
    {
        if($request->valeur=='Selectionner la timezone' || $request->valeur=='Selectionner la devise' || $request->valeur=='Selectionner la langue')
        {
                      return redirect()->back()->with('danger', 'veuillez selectionner la valeur');
        }else{
            $id = Session::get('id');
            $data = array();
            $data['valeur'] = $request->valeur;

            $data = DB::table('domaine_pro')
                ->where('id', $id)
                ->update($data);
            Session::put('message3', ' Modification succèss');
            return redirect()->route('affiche_domaine');
        }

    }
    public function editer_propriete($id)
    {
        $edites = DB::table('propriete')
            ->where('id', $id)
            ->get();

        return view('admin.edit_variable', compact('edites'));
    }
    public function editer_domaine($id)
    {
        $edite = DB::table('domaine')
            ->where('id', $id)
            ->first();

        return view('admin.edit_domaine', compact('edite'));
    }
    public function ajout_propriete()
    {
        $domaines = DB::table('domaine')
            ->get();

        $proprietes = DB::table('propriete')
            ->get();
        $devises = DB::table('devises')->get();
        $langues = DB::table('langues')->get();
        $timezones = DB::table('timezone')->get();
        return view('admin.propriete', compact('domaines', 'proprietes', 'devises', 'langues', 'timezones'));
    }

    public function ajout_variable()
    {
        return view('admin.ajout_variable');
    }
    public function sauver_variable(Request $request)
    {
        $data = array();
        $data['domaine'] = $request->variable;

        DB::table('domaine')
            ->insert($data);

        Session::put('message', ' insertion succèss');
        return redirect()->route('affiche_domaine');
    }
    public function sauver_propriete(Request $request)
    {

        $id = $request->id;
        $data = array();
        $data['nom'] = $request->valeur;

        DB::table('propriete')
            ->where('id', $id)
            ->update($data);

        Session::put('message1', 'modification succèss');
        return redirect()->route('supprimer_propriete');
    }
    public function modifier_domaine(Request $request)
    {
        $id = $request->id;

        $data = array();
        $data['domaine'] = $request->variable;

        $data = DB::table('domaine')
            ->where('id', $id)
            ->update($data);
        Session::put('message3', ' Modification succèss');
        return redirect()->route('affiche_domaine');
    }
     public function archivage()
     {
        $adsArchives  = DB::table('ads_archives')->get();
        $UserArchives  = DB::table('archive_users')->get();
        $UserProfileArchives = DB::table('archive_users_profiles')->get();

        return view('admin.user.archiveList',compact('adsArchives','UserArchives','UserProfileArchives'));
     }
     public function archivageAds()
     {
        $adsArchives  = DB::table('ads_archives')->get();
        $UserArchives  = DB::table('archive_users')->get();
        $UserProfileArchives = DB::table('archive_users_profiles')->get();

        return view('admin.user.archiveListAds',compact('adsArchives','UserArchives','UserProfileArchives'));
     }
     public function archivageProfile()
     {
        $adsArchives  = DB::table('ads_archives')->get();
        $UserArchives  = DB::table('archive_users')->get();
        $UserProfileArchives = DB::table('archive_users_profiles')->get();

        return view('admin.user.archiveListProfile',compact('adsArchives','UserArchives','UserProfileArchives'));
     }
    public function dashboardStat(Request $request)
    {
        $date_report = date("Y-m-d");
        $show = '0';
        if (isset($request->debut) && isset($request->limit)) {
            $date_report_debut = date("Y-m-d", strtotime(convertDateWithTiret($request->debut))) . ' 00:00:00';
            $date_report_limit = date("Y-m-d", strtotime(convertDateWithTiret($request->limit))) . " 23:59:59";
        } else {
            if (isset($request->show)) {
                $show = $request->show;
                switch ($show) {
                    case 'D':
                        if (isset($request->date_report)) {
                            $date_report = date("Y-m-d", strtotime(convertDateWithTiret($request->date_report)));
                            if ($date_report == date("Y-m-d")) {
                                $heureLimit = '23:59:00';
                            } else {
                                $heureLimit = '23:59:00';
                            }
                            $date_report_debut = $date_report . ' 00:00:00';
                            $date_report_limit = $date_report . " " . $heureLimit;
                        } else {
                            $date_report = date('Y-m-d');
                            $date_report_debut = date('Y-m-d') . " 00:00:00";
                            $date_report_limit = $date_report . " " . $heureLimit;
                        }
                        break;
                    case 'W':
                        $date_report_debut = getDateByDecalage(-7) . " 00:00:00";
                        $date_report_limit = date('Y-m-d') . " 23:59:59";
                        break;
                    case 'M':
                        $date_report_debut = date('Y-m-01') . " 00:00:00";
                        $date_report_limit  = date('Y-m-t') . " 23:59:59";
                        break;
                    default:
                        # code...
                        break;
                }
            } else {
                if (isset($request->date_report)) {
                    $date_report = date("Y-m-d", strtotime(convertDateWithTiret($request->date_report)));
                    if ($date_report == date("Y-m-d")) {
                        $heureLimit = date('H:i:s');
                    } else {
                        $heureLimit = '23:59:00';
                    }
                    $date_report_debut = $date_report . ' 00:00:00';
                    $date_report_limit = $date_report . " " . $heureLimit;
                } else {
                    $date_report = date('Y-m-d');
                    $date_report_debut = date('Y-m-d') . " 00:00:00";
                    $date_report_limit = date('Y-m-d 23:59:59');
                }
            }
        }

        $whereRaw = "1=1";
        $adComunity = true;
        if (isset($request->adComunity)) {
            $adComunity = ($request->adComunity == "true") ? true : false;

            if (!$adComunity) {
                $whereRaw .= " AND comunity_id IS NULL";
            }
        }
        $ads_count_sc_1 = Ads::where('scenario_id', '1')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_count_sc_2 = Ads::where('scenario_id', '2')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_count_sc_3 = Ads::where('scenario_id', '3')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_count_sc_4 = Ads::where('scenario_id', '4')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_count_sc_5 = Ads::where('scenario_id', '5')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->whereRaw($whereRaw)->count();
        $ads_real_posted_count = Ads::join("users", "users.id", "ads.user_id")->whereNotNull('email')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();
        $nb_coup_de_foudre = DB::table("coup_de_foudres")->where('created_at', '>=', $date_report_debut)->where('created_at', '<=', $date_report_limit)->count();

        $signal_ad_count = Signal::where("treaty", 0)->join("ads", "ads.id", "signal_ad.ad_id")->where("admin_approve", "0")->where('signal_ad.created_at', '<=', $date_report_limit)->where('signal_ad.created_at', '>=', $date_report_debut)->count();

        $users_count = User::where('user_type_id', 1)->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->count();
        $users_real_count = User::where('user_type_id', 1)->whereNotNull("users.email")->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->count();

        $messageCount = DB::table('messages')->where('created_at', '>=', $date_report_debut)->where('created_at', '<=', $date_report_limit)->count();

        $comunityCount =  Ads::whereNotNull("comunity_id")->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();


        $newsletterSubscriptionCount = DB::table('newsletter')->where('date', '>=', $date_report_debut)->where('date', '<=', $date_report_limit)->count();
        $contactCount = DB::table('contact')->where('date', '>=', $date_report_debut)->where('date', '<=', $date_report_limit)->count();
        $parrainage_active = DB::table('user_parainage')->where("statut", 1)->where('date', '>=', $date_report_debut)->where('date', '<=', $date_report_limit)->count();
        $userSubscriptionCount = DB::table("user_packages")->whereNotNull("payment_id")->where('created_at', '>=', $date_report_debut)->where('created_at', '<=', $date_report_limit)->count();

        $activateAdsCount = DB::table('ads')->whereNotNull("comunity_id")->join("users", "users.id", "ads.user_id")->whereNotNull("users.email")->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();
        $villes = DB::table('search')
            ->select(DB::raw('ville,count(distinct(user_id)) as nb'))
            ->where('date', '<=', $date_report_limit)
            ->where('date', '>=', $date_report_debut)
            ->groupBy("ville")
            ->orderBy('nb', "desc")
            ->paginate(15);
        $pub = DB::table("pub")->where("id", 6)->first();
        $pubClickCount = DB::table("pub_click")->where("id_pub", 6)->where('created_at', $date_report)->whereNull("type")->count();
        $pubClickMail = DB::table("pub_click")->where("id_pub", 6)->where('created_at', $date_report)->where("type", "mail")->count();
        $date_report = date("d/m/Y", strtotime($date_report));
        $date_report_debut = date("d/m/Y", strtotime($date_report_debut));
        $date_report_limit = date("d/m/Y", strtotime($date_report_limit));

        return view('admin.dashboardStat', compact('ads_count_sc_1', 'ads_count_sc_2', 'ads_count_sc_3', 'ads_count_sc_4', 'ads_count_sc_5', 'signal_ad_count', 'users_count', "messageCount", "userSubscriptionCount", "comunityCount", "contactCount", "newsletterSubscriptionCount", "users_real_count", "ads_real_posted_count", "pub", "pubClickCount", "nb_coup_de_foudre", "activateAdsCount", "date_report", "show", 'date_report_debut', "date_report_limit", "parrainage_active", "pubClickMail", 'villes', 'adComunity'));
    }

    public function manageDebug($value, Request $request)
    {
        DB::table("config")->where('varname', "debug")->update(['value' => $value]);
        $this->sendMailDebug($value);
        return redirect()->back();
    }

    public function managePayment($value, Request $request)
    {
        DB::table("config")->where('varname', "stripe_checkout")->update(['value' => $value]);
        return redirect()->back();
    }


    public function manageVerifMail($value, Request $request)
    {
        DB::table("config")->where('varname', "verification_mail")->update(['value' => $value]);
        $this->sendMailDebugMail($value);
        return redirect()->back();
    }

    public function manageToctoc($status)
    {
        DB::table('config')->where('varname', 'active_toctoc')->update(['value' => $status]);
        return redirect()->back();
    }

    public function manageNotificationMail($status)
    {
        DB::table('config')->where('varname', 'active_notification_nbr_mail')->update(['value' => $status]);
        return redirect()->back();
    }

    public function manageGuestSearch($status)
    {
        DB::table('config')->where('varname', 'guest_ad_search_listing')->update(['value' => $status]);
        return redirect()->back();
    }

    public function manageTelegramPub($type, $value, Request $request)
    {
        $varname = "telegram_pub_" . $type;
        DB::table("config")->where('varname', $varname)->update(['value' => $value]);
        return redirect()->back();
    }

    private function sendMailDebug($statut)
    {
        if ($statut == 1) {
            $statut = "on";

            $subject = "Debug activé";
        } else {
            $statut = "off";
            $subject = "Debug désactivé";
        }

        try {

            sendMailAdmin('emails.admin.debug',['subject'=>$subject,'statut' =>$statut]);

        } catch (Exception $ex) {
        }
        return true;
    }

    private function sendMailDebugMail($statut)
    {
        if ($statut == 1) {
            $statut = "on";

            $subject = "Verification mail activé";
        } else {
            $statut = "off";

            $subject = "Verification mail désactivé";
        }

        try {


            sendMailAdmin('emails.admin.debugMail',[
                'subject'=>$subject,
                'statut' =>$statut
            ]);

        } catch (Exception $ex) {
        }
        return true;
    }

    public function indicators(Request $request)
    {
        if (isset($request->date_report)) {
            $date_report = date("Y-m-d", strtotime(convertDateWithTiret($request->date_report)));
            if ($date_report == date("Y-m-d")) {
                $heureLimit = date('H:i:s');
            } else {
                $heureLimit = '23:59:00';
            }
            $date_report_debut = $date_report . ' 00:00:00';
            $date_report_limit = $date_report . " " . $heureLimit;
        } else {
            $date_report = date('Y-m-d');
            $date_report_debut = date('Y-m-d') . " 00:00:00";
            $date_report_limit = date('Y-m-d H:i:s');
        }

        $users_sc1 = User::where('user_type_id', '1')->whereNotNull("users.email")->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->where("scenario_register", '1')->count();
        $users_sc2 = User::where('user_type_id', '1')->whereNotNull("users.email")->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->where("scenario_register", '2')->count();
        $users_sc3 = User::where('user_type_id', '1')->whereNotNull("users.email")->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->where("scenario_register", '3')->count();
        $users_sc4 = User::where('user_type_id', '1')->whereNotNull("users.email")->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->where("scenario_register", '4')->count();
        $users_sc5 = User::where('user_type_id', '1')->whereNotNull("users.email")->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->where("scenario_register", '5')->count();
        $users_count = User::where('user_type_id', '1')->whereNotNull("users.email")->where('created_at', '<=', $date_report_limit)->where('created_at', '>=', $date_report_debut)->count();


        $ads_count_sc_1 = Ads::where('scenario_id', '1')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();
        $ads_count_sc_2 = Ads::where('scenario_id', '2')->where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();
        $ads_count = Ads::where('ads.created_at', '>=', $date_report_debut)->where('ads.created_at', '<=', $date_report_limit)->count();
        $table = "user_visit_archive";
        if ($date_report == date("Y-m-d")) {
            $table = "user_visit";
        }

        $unique_users = DB::table($table)->distinct("ip")->where('date', '<=', $date_report_limit)->where('date', '>=', $date_report_debut)->count("ip");
        $showForm1 = nbShowForm("form1", $date_report);
        $showForm2 = nbShowForm("form2", $date_report);
        $showForm3 = nbShowForm("form3", $date_report);
        $showForm4 = nbShowForm("form4", $date_report);
        $showForm5 = nbShowForm("form5", $date_report);
        $validateForm1 = nbValidateForm("form1", $date_report);
        $validateForm2 = nbValidateForm("form2", $date_report);
        $validateForm3 = nbValidateForm("form3", $date_report);
        $validateForm4 = nbValidateForm("form4", $date_report);
        $validateForm5 = nbValidateForm("form5", $date_report);
        $tauxForm1 = 0;
        $tauxForm2 = 0;
        $tauxForm3 = 0;
        $tauxForm4 = 0;
        $tauxForm5 = 0;
        if ($showForm1 != 0) {
            $tauxForm1 = number_format($validateForm1 / $showForm1, 2);
        }
        if ($showForm2 != 0) {
            $tauxForm2 = number_format($validateForm2 / $showForm2, 2);
        }
        if ($showForm3 != 0) {
            $tauxForm3 = number_format($validateForm3 / $showForm3, 2);
        }
        if ($showForm4 != 0) {
            $tauxForm4 = number_format($validateForm4 / $showForm4, 2);
        }
        if ($showForm5 != 0) {
            $tauxForm5 = number_format($validateForm5 / $showForm5, 2);
        }
        if ($unique_users == 0) {
            $tauxRegistration = 0;
            $tauxAds = 0;
        } else {
            $tauxRegistration = number_format($users_count / $unique_users, 2);
            $tauxAds = number_format($ads_count / $unique_users, 2);
        }

        $query = 'select url,count(distinct ip) as nb from ' . $table . ' where date <= "' . $date_report_limit . '" and date >= "' . $date_report_debut . '" group by url order by nb';
        $urls = DB::select(DB::raw($query));
        $date_report = date("d/m/Y", strtotime($date_report));
        return view('admin.indicators', compact('unique_users', 'ads_count_sc_1', 'ads_count_sc_2', "users_sc1", "users_sc2", "users_sc3", "users_sc4", "users_sc5", "tauxRegistration", "tauxAds", "showForm1", "showForm2", "showForm3", "showForm4", "validateForm1", "validateForm2", "validateForm3", "validateForm4", "validateForm5", "showForm5", "tauxForm1", "tauxForm2", "tauxForm3", "tauxForm4", "tauxForm5", "urls", "date_report"));
    }

    private function calculNbDayChart($date_debut, $date_limit)
    {
        $date_debut = convertDateWithTiret($date_debut);
        $date_limit = convertDateWithTiret($date_limit);
        $interval = dateDiff($date_limit, $date_debut);
        $nbDay = $interval->days;
        $nbDay = ($nbDay == 0) ? -7 : -$nbDay;
        return $nbDay;
    }

    public function dashChartData(Request $request)
    {
        $nbDay = $this->calculNbDayChart($request->date_debut, $request->date_limit);
        $date_limit = convertDateWithTiret($request->date_limit);
        $xAxis = $this->dateAxis($nbDay, $date_limit);
        $adsStat =  $this->getStatAds($nbDay, $date_limit);
        $result = [
            "xAxis" => $xAxis,
            "subscription" => $this->getUserSubscriptionStat($nbDay, $date_limit),
            "nbViewSubscription" => $this->getViewSubscription($nbDay, $date_limit),
            "nbViewAchat" => $this->getViewAchat($nbDay, $date_limit)

        ];
        echo json_encode($result);
    }

    function dashChartData2(Request $request)
    {
        $nbDay = $this->calculNbDayChart($request->date_debut, $request->date_limit);
        $date_limit = convertDateWithTiret($request->date_limit);
        $xAxis = $this->dateAxis($nbDay, $date_limit);
        $adsStat =  $this->getStatAds($nbDay, $date_limit);
        $result = [
            "xAxis" => $xAxis,
            "comunityAds" => $this->getStatAdsComunity($nbDay, $date_limit),
            "userRegistration" => $this->getUserStat($nbDay, $date_limit),
            "tauxAds" => $this->getStatPourcentageUserAds($nbDay, $date_limit),
            "userUnique" => $this->getUniqueUsers($nbDay, $date_limit),
            "comment" => $this->getComment($nbDay, $date_limit)

        ];

        echo json_encode($result);
    }
    function dashChartData2h(Request $request)
    {
        $nbDay = $this->calculNbDayChart($request->date_debut, $request->date_limit);
        $date = $request->date_report;
        $date_limit = convertDateWithTiret($request->date_limit);
        $xAxis = $this->dateAxish($nbDay, $date_limit);
        $result = [
            "xAxis" => $xAxis, //heure de 0 à 23
            "cadcom" => $this->getCommenth($date) // nombre de comment chaques heures

        ];
        return json_encode($result);
    }

    public function registerChartData(Request $request)
    {
        $dataForm1 = [];
        $dataForm2 = [];
        $dataForm3 = [];
        $dataForm4 = [];
        $dataForm5 = [];
        $dataTotal = [];
        $form = [];
        $result = [];
        $nbDay = $this->calculNbDayChart($request->date_debut, $request->date_limit);
        $date_limit = convertDateWithTiret($request->date_limit);
        $dateAxis = $this->dateAxis($nbDay, $date_limit);
        foreach ($dateAxis as $i => $date) {

            $showForm1 = nbShowForm("form1", $date);
            $showForm2 = nbShowForm("form2", $date);
            $showForm3 = nbShowForm("form3", $date);
            $showForm4 = nbShowForm("form4", $date);
            $showForm5 = nbShowForm("form5", $date);
            $validateForm1 = nbValidateForm("form1", $date);
            $validateForm2 = nbValidateForm("form2", $date);
            $validateForm3 = nbValidateForm("form3", $date);
            $validateForm4 = nbValidateForm("form4", $date);
            $validateForm5 = nbValidateForm("form5", $date);
            $validateTotal = $validateForm1 + $validateForm2 + $validateForm3;
            $table = "user_visit_archive";
            if ($date == date("Y-m-d")) {
                $table = "user_visit";
            }
            $unique_users = DB::table($table)->distinct("ip")->whereRaw('date(date)="' . $date . '"')->count("ip");

            $tauxForm1 = 0;
            $tauxForm2 = 0;
            $tauxForm3 = 0;
            $tauxForm4 = 0;
            $tauxForm5 = 0;
            $tauxValidationTotal = 0;
            $tauxValidationF3 = 0;
            if ($unique_users != 0) {
                $tauxValidationTotal = round($validateTotal / $unique_users, 2);
            }

            if ($unique_users != 0) {
                $tauxValidationF3 = round($validateForm3 / $unique_users, 2);
            }


            if ($showForm1 != 0) {
                $tauxForm1 = round($validateForm1 / $showForm1, 2);
            }
            if ($showForm2 != 0) {
                $tauxForm2 = round($validateForm2 / $showForm2, 2);
            }
            if ($showForm3 != 0) {
                $tauxForm3 = round($validateForm3 / $showForm3, 2);
            }
            if ($showForm4 != 0) {
                $tauxForm4 = round($validateForm4 / $showForm4, 2);
            }
            if ($showForm5 != 0) {
                $tauxForm5 = round($validateForm5 / $showForm5, 2);
            }

            $dataForm1[] = $tauxForm1;
            $dataForm2[] = $tauxForm2;
            $dataForm3[] = $tauxForm3;
            $dataForm4[] = $tauxForm4;
            $dataForm5[] = $tauxForm5;
            $dataTotal[] = $tauxValidationTotal;
            $dataTotalF3[] = $tauxValidationF3;
        }
        $result = [
            "xAxis" => $dateAxis,
            "formulaire_inscription1" => $dataForm1,
            "formulaire_inscription2" => $dataForm2,
            "formulaire_inscription3" => $dataForm3,
            "formulaire_inscription4" => $dataForm4,
            "formulaire_inscription5" => $dataForm5,
            "taux_validation_total" => $dataTotal,
            "taux_validation_totalF3" => $dataTotalF3

        ];

        echo json_encode($result);
    }

    private function dateAxis($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $result[] = getDateByDecalage($i, $date_limit);
            $i = $i + 1;
        }
        return $result;
    }
    private function dateAxish($nb = -1, $date_limit = null)
    {

        $i = 0;
        $result = [];
        while ($i <= 23) {
            $result[] = $i;
            $i = $i + 1;
        }

        return $result;
    }
    private function getStatAds($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(id) as nb from ads where comunity_id is null and date(created_at) = "' . getDateByDecalage($i, $date_limit) . '"';

            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }

    private function getStatAdsComunity($nb, $date_limit)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(id) as nb from ads where comunity_id is not null and date(created_at) = "' . getDateByDecalage($i, $date_limit) . '"';

            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }

    private function getComment($nb, $date_limit)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(id) as nb from publications_community_stat where date(date_enregistrement) = "' . getDateByDecalage($i, $date_limit) . '"';

            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }
    private function getCommenth($date)
    {
        $i = 0;
        $result = [];
        $now = Carbon::now();
        $originalDate = str_replace('/', '-', $date);
        $newDate = date("Y-m-d", strtotime($originalDate));
        while ($i <= 23) {
            $commentsByHour = DB::table('publications_community_stat')
                ->where(DB::raw('hour(date_enregistrement)'), $i)
                ->whereDate(DB::raw('DATE(date_enregistrement)'), '=', $newDate)
                ->count();

            $result[] = $commentsByHour;
            $i = $i + 1;
        }


        return $result;
    }

    private function getStatPourcentageUserAds($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(distinct user_id) as nb from ads where comunity_id is null and date(created_at) = "' . getDateByDecalage($i, $date_limit) . '"';

            $data = DB::select(DB::raw($statement));
            $nbAds = $data[0]->nb;
            $statement = 'select count(id) as nb from users where email is not null and date(created_at) = "' . getDateByDecalage($i) . '"';


            $data = DB::select(DB::raw($statement));
            $nbUser = $data[0]->nb;
            if ($nbAds == 0 || $nbUser == 0) {
                $result[] = 0;
            } else {
                $result[] = ($nbAds / $nbUser) * 100;
            }

            $i = $i + 1;
        }
        return $result;
    }

    private function getUserStat($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(id) as nb from users where email is not null and date(created_at) = "' . getDateByDecalage($i, $date_limit) . '"';

            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }

    private function getUserSubscriptionStat($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(id) as nb from user_packages where payment_id is not null and date(created_at) = "' . getDateByDecalage($i, $date_limit) . '"';

            $nb_users = DB::table("users")->whereNotNull("ip")->whereRaw("date(created_at) = '" . getDateByDecalage($i, $date_limit) . "'")->count("id");
            if ($nb_users != 0) {
                $data = DB::select(DB::raw($statement));
                $nb = $data[0]->nb;
                $result[] = round(($nb / $nb_users) * 100, 2);
                $i = $i + 1;
            }
        }
        return $result;
    }

    private function getMessagesStat($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(id) as nb from messages where date(created_at) = "' . getDateByDecalage($i, $date_limit) . '"';

            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }


    public function dashChartDataComunity(Request $request)
    {
        $comunities = DB::table('users')->where("user_type_id", 3)->orWhere("user_type_id", 4)->get();
        $nbDay = $this->calculNbDayChart($request->date_debut, $request->date_limit);
        $date_limit = convertDateWithTiret($request->date_limit);
        $xAxis = $this->dateAxis($nbDay, $date_limit);
        echo json_encode(array("xAxis" => $xAxis, "data" => $this->buildComunityChartData($comunities, $nbDay, $date_limit)));
    }

    private function buildComunityChartData($comunities, $nbDay, $date_limit)
    {
        $result = [];
        foreach ($comunities as $key => $comunity) {
            $result[] = array("name" => $comunity->first_name, "data" => $this->getComunityStat($comunity->id, $nbDay, $date_limit));
        }
        return $result;
    }


    private function getComunityStat($com_id, $nbDay, $date_limit)
    {
        $i = $nbDay;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(id) as nb from ads where comunity_id is not null and comunity_id = ' . $com_id . ' and date(created_at) = "' . getDateByDecalage($i, $date_limit) . '"';

            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }


    public function dashChartPubClick(Request $request)
    {
        $pubs = DB::table('pub')->orderBy("id", "desc")->get();
        $nbDay = $this->calculNbDayChart($request->date_debut, $request->date_limit);
        $date_limit = convertDateWithTiret($request->date_limit);
        $xAxis = $this->dateAxis($nbDay, $date_limit);
        echo json_encode(array("xAxis" => $xAxis, "data" => $this->buildPubChartData($pubs, $nbDay, $date_limit)));
    }

    private function buildPubChartData($pubs, $nbDay, $date_limit)
    {
        $result = [];
        foreach ($pubs as $key => $pub) {
            $result[] = array("name" => $pub->label . "(Acceuil)", "data" => $this->getPubStat($pub->id, $nbDay, $date_limit));
            $result[] = array("name" => $pub->label . "(Mail)", "data" => $this->getPubStat($pub->id, $nbDay, $date_limit, 'mail'));
        }
        return $result;
    }

    private function getPubStat($id, $nbDay, $date_limit, $type = null)
    {
        $i = $nbDay;
        $result = [];
        while ($i <= 0) {
            if ($type) {
                $statement = 'select count(id) as nb from pub_click where id_pub = ' . $id . ' and date(created_at) = "' . getDateByDecalage($i, $date_limit) . '" and type="' . $type . '"';
            } else {
                $statement = 'select count(id) as nb from pub_click where id_pub = ' . $id . ' and date(created_at) = "' . getDateByDecalage($i, $date_limit) . '" and type is null';
            }

            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }

    private function getProfilSearchStat($nb = -7, $date_limit = null)
    {
        $i = -7;
        $result = [];
        while ($i <= 0) {
            $statement = 'select count(u.id) as nb from users u inner join user_profiles up on u.id=up.user_id where u.email is not null and up.revenus is not null and date(u.created_at) = "' . getDateByDecalage($i, $date_limit) . '"';

            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }

    private function getUniqueUsers($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $date = getDateByDecalage($i, $date_limit);
            $table = "user_visit_archive";
            if ($date == date("Y-m-d")) {
                $table = "user_visit";
            }
            $statement = 'SELECT count(distinct ip) as nb FROM ' . $table . ' WHERE date(date) = "' . $date . '"';
            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $result[] = $nb;
            $i = $i + 1;
        }
        return $result;
    }

    private function getViewSubscription($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $date = getDateByDecalage($i, $date_limit);

            $statement = 'SELECT count(distinct ip) as nb FROM view_subscription WHERE date(date) = "' . $date . '"';
            $data = DB::select(DB::raw($statement));
            $nb_users = DB::table("users")->whereNotNull("ip")->whereRaw("date(created_at) = '" . $date . "'")->count("id");
            if ($nb_users != 0) {
                $nb = $data[0]->nb;
                $result[] = round(($nb / $nb_users) * 100, 2);
            }
            $i = $i + 1;
        }
        return $result;
    }

    private function getViewAchat($nb = -7, $date_limit = null)
    {
        $i = $nb;
        $result = [];
        while ($i <= 0) {
            $date = getDateByDecalage($i, $date_limit);

            $nb_users = DB::table("users")->whereNotNull("ip")->whereRaw("date(created_at) = '" . $date . "'")->count("id");
            if ($nb_users != 0) {

                $statement = 'SELECT count(distinct ip) as nb FROM view_achat WHERE date(date) = "' . $date . '"';
                $data = DB::select(DB::raw($statement));

                $nb = $data[0]->nb;
                $result[] = round(($nb / $nb_users) * 100, 2);
            }
            $i = $i + 1;
        }
        return $result;
    }

    public function dashChartDataVille(Request $request)
    {
        $result = [];
        $nbDay = $this->calculNbDayChart($request->date_debut, $request->date_limit);
        $date_limit = convertDateWithTiret($request->date_limit);
        $xAxis = $this->dateAxis($nbDay, $date_limit);
        $i = 0;
        foreach ($xAxis as $key => $date) {

            $nb_users = DB::table("users")->whereNotNull("address_register")->whereRaw("date(created_at) = '" . $date . "'")->count("id");
            if ($nb_users != 0) {
                $datas = $this->getDailyDistinctVille($date);
                $cities = $datas['city'];
                $nb = $datas['nb'];
                foreach ($cities as $key => $value) {
                    if (array_key_exists($value, $result) === false) {
                        $result[$value] = [];
                        for ($j = 0; $j < $i; $j++) {
                            $result[$value][] = 0;
                        }
                        $result[$value][] = ($nb[$value] / $nb_users);
                    } else {
                        $result[$value][] = ($nb[$value] / $nb_users);
                    }
                }
                $i++;
                foreach ($result as $key => $value) {
                    if (count($value) < $i) {
                        $result[$key][] = 0;
                    }
                }
            }
        }

        $dataChart = [];
        foreach ($result as $key => $value) {
            $dataChart[] = array("name" => $key, "data" => $value);
        }

        echo json_encode(array('xAxis' => $xAxis, "data" => $dataChart));
    }

    private function getDailyDistinctVille($date)
    {
        $addresses = DB::table('users')->select("address_register")->whereRaw("date(created_at) = '" . $date . "'")->get();
        $cities = [];
        $nbs = [];
        foreach ($addresses as $key => $addresse) {
            if (!is_null($addresse->address_register)) {
                $temp = ucfirst(trim(getAdressVilleV2($addresse->address_register)));
                if (array_search($temp, $cities) === false) {
                    $cities[] = $temp;
                    $nbs[$temp] = 1;
                } else {
                    $nbs[$temp] += 1;
                }
            }
        }
        return array("city" => $cities, "nb" => $nbs);
    }

    public function manageSposorisedAds($value, Request $request)
    {
        DB::table("config")->where('varname', "sponsorised_ads")->update(['value' => $value]);
        return redirect()->back();
    }

    public function manageMaintenance($value, Request $request)
    {
        DB::table("config")->where('varname', "maintenance")->update(['value' => $value]);
        return redirect()->back();
    }

    public function tva($value, Request $request)
    {
        if ($value == 0) {
            DB::table("packages")->where('id', 1)->update(['amount' => 19.90]);
            DB::table("packages")->where('id', 2)->update(['amount' => 23.90]);
            DB::table("packages")->where('id', 3)->update(['amount' => 59.90]);
            DB::table("packages")->where('id', 4)->update(['amount' => 89.90]);
        } else {
            DB::table("packages")->where('id', 1)->update(['amount' => 23.88]);
            DB::table("packages")->where('id', 2)->update(['amount' => 28.68]);
            DB::table("packages")->where('id', 3)->update(['amount' => 71.88]);
            DB::table("packages")->where('id', 4)->update(['amount' => 107.88]);
        }
        DB::table("config")->where('varname', "tva")->update(['value' => $value]);
        return redirect()->back();
    }


    public function manageIconeFB($value, Request $request)
    {
        DB::table("config")->where('varname', "icone_fb")->update(['value' => $value]);
        return redirect()->back();
    }

    public function manageMultilangue($value, Request $request)
    {
        DB::table("config")->where('varname', "langue")->update(['value' => $value]);
        return redirect()->back();
    }

    public function manageGoogleAdsense($value, Request $request)
    {
        DB::table("config")->where('varname', "google_adsense")->update(['value' => $value]);
        return redirect()->back();
    }
    public function mailmaintenance()
    {
        $mail =  DB::table("page_text")->where('index', 'mail_maintenance')->get();
        $mails = $mail[0]->text_fr;

        return view('admin.mailmaintenance')
            ->with('mails', $mails);
    }
    public function updatemailmaintenance(Request $request)
    {
        DB::table("page_text")->where('index', 'mail_maintenance')->update(
            ["text_fr" => $request->mail]
        );

        return redirect('/admin/mailmaintenance')->with('success', 'Stock updated.'); // -> resources/views/stocks/index.blade.php
    }
}
