<?php

namespace App\Http\Controllers;

use PDF;
use Auth;
use session;
use App\User;
use App;
use App\Count;
use App\FacebookAPI;
use App\UserProfiles;
use App\UserLifestyles;
use App\UserTypeMusics;

use App\Http\Models\Ads\Ads;
use App\Http\Models\Package;
use App\UserSocialInterests;
use Illuminate\Http\Request;

use App\Http\Models\StaticPage;
use App\Http\Models\Ads\Messages;
use App\Http\Models\FeaturedCity;
use App\Libraries\StripeInterface;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Payment\Payment;
use Illuminate\Support\Facades\Mail;
use App\Repositories\MasterRepository;
use Illuminate\Console\Scheduling\Schedule;
use App\Http\Models\UserPackage\UserPackage;
use Illuminate\Auth\AuthenticationException;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $colocNbPerPage;
    private $logementNbPerPage;
    private $master;

    public function __construct(MasterRepository $master)
    {
        $this->colocNbPerPage = config('customConfig.maxColoc');
        $this->logementNbPerPage = config('customConfig.maxLogement');
        $this->master = $master;
    }


    public function adminPage404(Request $request)
    {
        return abort(404);
    }

    function creerCompteStep2(Request $request)
    {
        $socialInterests = $this->master->getMasters('social_interests');
        $typeMusics = $this->master->getMasters('user_type_music');
        $sports = DB::table("user_sport")->orderBy("label")->get();
        $request->session()->flash('stepAccount', "2");
        $step = 2;

        return view('auth.register_step_1', compact('step', 'socialInterests', 'typeMusics', 'sports'));
    }

    function creerCompteStep1(Request $request)
    {
        $textes = DB::table('page_text')
            ->where('index', 'register.collocation_text')
            ->get();
        $langues = App::getLocale();

        $requete = $request->session()->get("userInfoRegister");
        //Definit le scenario d'inscription par defaut a 2 comme dans l'accueil si il est vide
        if (empty($requete['scenario_register'])) {
            $requete['scenario_register'] = 2;
        }
        $scenario = $requete['scenario_register'];
        session::put('langues', $langues);
        session::put('scenario', $scenario);
        $request->session()->flash('stepAccount', "1");
        return view('auth.register_step_1', compact('textes'));
    }

    public function fbRegister(Request $request)
    {
        $request->session()->flash('action', "fb_register");
        $socialInterests = $this->master->getMasters('social_interests');
        $typeMusics = $this->master->getMasters('user_type_music');
        $sports = DB::table("user_sport")->orderBy("label")->get();
        $step = 1;
        return view('auth.register_facebook', compact('step', 'socialInterests', 'typeMusics', 'sports'));
    }

    public function registerPhone(Request $request)
    {
        $user_id = Auth::id();

        $user_profiles = DB::table('user_profiles')->where("user_id", $user_id)->first();
        $user = DB::table('users')->where('id', $user_id)->first();

        if (!empty($user->email)) {
            $email = $user->email;
        } else {
            $email = " ";
        }

        if (!empty($user_profiles->mobile_no)) {
            $numero = $user_profiles->mobile_no;
        } else {
            if (empty($user_profiles->professional_category)) {
                return redirect("/facebook-inscription/etape2");
            }
            return view('auth.add_phone');
        }
        return view('auth.register_phone', compact('numero', 'user_profiles', 'email'));
    }

    public function addPhone(Request $request)
    {
        $user_id = Auth::id();
        //recuperer l user dans table user profiles ayant le numero request->mobile_no
        $validationNumero = DB::table('user_profiles')->where('mobile_no', $request->mobile_no)->first();
        if (!is_null($validationNumero)) {
            $usernum = DB::table('users')->where('id', $validationNumero->user_id)->first();
            $verificationnumero = $usernum->verified_number;
            //annuler si il nexiste pas om met null
            if (isset($verificationnumero) && !$verificationnumero) {
                DB::table('user_profiles')->where('mobile_no', $request->mobile_no)->update(['mobile_no' => null]);
            } else {
                //erreur si existe
                $erreur = i18n('number_phone_exist');
                return view('auth.add_phone', compact("erreur"));
            }
        }
        $user = DB::table('users')->where('id', $user_id)->first();
        if (!empty($user->email)) {
            $email = $user->email;
        } else {
            $email = " ";
        }

        DB::table('user_profiles')->where("user_id", $user_id)->update(['mobile_no' => $request->mobile_no]);
        $numero = $request->mobile_no;
        $user_profiles = DB::table('user_profiles')->where("user_id", $user_id)->first();
        return view('auth.register_phone', compact('numero', 'user_profiles', 'email'));
    }


    public function contactAdmin(Request $request)
    {

        $subject = "Bug dans la deuxième étape de l'inscription";

        $user_id = Auth::id();

        $user_profiles = DB::table('user_profiles')->where("user_id", $user_id)->first();
        $user = DB::table('users')->where('id', $user_id)->first();
        if (!empty($user->email)) {
            $email = $user->email;
        } else {
            $email = " ";
        }
        if (!empty($user_profiles->mobile_no)) {
            $numero = $user_profiles->mobile_no;
        } else {
            $numero = " ";
        }

        sendMailAdmin("emails.users.errorInscriEr", ["subject" => $subject, "email" => $email, "numero" => $numero]);

        return view('auth.register_phone', compact('numero', 'user_profiles', 'email'));
    }

    public function signaler(Request $request)
    {
        $user_id = Auth::id();

        $user_profiles = DB::table('user_profiles')->where("user_id", $user_id)->first();
        $user = DB::table('users')->where('id', $user_id)->first();
        if (!empty($user->email)) {
            $email = $user->email;
        } else {
            $email = " ";
        }
        if (!empty($user_profiles->mobile_no)) {
            $numero = $user_profiles->mobile_no;
        } else {
            $numero = " ";
        }

        $subject = "Bug authentification phone";

        if (!empty($user->signale)) {
            if ($user->signale == false) {

                sendMailAdmin("emails.users.errorInscriPhone", ["subject" => $subject, "email" => $email, "numero" => $numero]);
                DB::table("users")->where('id', $user_id)->update(["signale" => true]);


                $bug = 1;
                return view('auth.register_phone', compact('numero', 'user_profiles', 'bug'));
            } else {
                $bug = 2;
                return view('auth.register_phone', compact('numero', 'user_profiles', 'bug'));
            }
        } else {
            sendMailAdmin("emails.users.errorInscriPhone", ["subject" => $subject, "email" => $email, "numero" => $numero]);
            DB::table("users")->where('id', $user_id)->update(["signale" => true]);


            $bug = 1;
            return view('auth.register_phone', compact('numero', 'user_profiles', 'bug'));
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $ville = null)
    {
        // $user_type_id = Auth::user()->user_type_id;
        // $users = DB::table('module_user')->where('id_type_user', 15)->get()->toArray();
        // foreach($users as $user){
        //     dd($user->id_module);
        // }
        
        $infosAddress = null;
        if (!is_null($ville)) {
            $infosAddress = getCoordByAdress($ville);
        }
        if (Auth::check()) {
            $aujoud_hui = date('Y-m-d');
            $data =  array();
            $data['last_conection'] = $aujoud_hui;
            DB::table('users')->where('id', Auth::id())->update($data);
        }


        $count = Count::first();
        $nbAdsCount =  ($count->ads == 0) ? Ads::count() : $count->ads; //Ads::count();
        $nbUsersCount = ($count->users == 0) ? DB::table("users")->count() : $count->users; //DB::table("users")->count();

        $lang = 'Français';
        if (App::getLocale() == 'en') {
            $lang = 'English';
        } else {
            $lang = 'Français';
        }

        $featured_rooms = [];


        $featured_room_mates = [];
        if (getConfig("sponsorised_ads") == 1) {
            $whereScenario = ['1', '2'];
            //            $nbr_sponsorised_community=4;


            $featured_rooms = Ads::select("ads.id", "ads.title", "ads.address", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic")->join("users", "users.id", "ads.user_id")->join("user_profiles", "users.id", "user_profiles.user_id")
                ->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')->orderBy('ordre', 'asc');
                }, 'ad_details'])
                ->whereIn('scenario_id', $whereScenario)
                ->where('status', '1')
                ->where("is_annonce_acceuil", "1")
                ->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))
                ->where('admin_approve', '1')
                ->limit(getConfig("nombre_annonce_sponsorises_acceuil"))
                ->orderByRaw('boosted DESC, ads.updated_at DESC')
                ->get();




            $whereScenario = ['3', '4', '5'];


            $featured_room_mates = Ads::select("ads.id", "ads.title", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic", "user_profiles.birth_date")
                ->join("users", "users.id", "ads.user_id")
                ->join("user_profiles", "users.id", "user_profiles.user_id")
                ->whereIn('scenario_id', $whereScenario)->where("is_annonce_acceuil", "1")
                ->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))
                ->where('status', '1')
                ->where('admin_approve', '1')
                ->offset(0)->limit(getConfig('nombre_profils_sponsorises_acceuil'))
                ->orderByRaw('boosted DESC, ads.updated_at DESC')
                ->get();

            //            $total_user_sponsor=($featured_rooms->count()+$featured_room_mates->count());
            //            $adsCommunity=$this
            //                ->getLongementSponsoriseComunity($nbr_sponsorised_community-$total_user_sponsor);
            //
            //            if($adsCommunity!==false)
            //            {
            //                $featured_rooms=$featured_rooms
            //                    ->merge($adsCommunity);
            //            }


        }


        if ($request->session()->has("dataRegister")) {
            $dataRegister = $request->session()->get("dataRegister");
            $request->session()->forget("dataRegister");
            //il y a un variable fractured_visits qui provoque un erreur compact(): Undefined variable: fractured_visits, alors j'enleve (Steev)
            return view('home', compact('lang', "nbAdsCount", "nbUsersCount", "featured_rooms", "featured_room_mates", "infosAddress", "dataRegister"));
        }
        return view('home', compact('lang', "nbAdsCount", "nbUsersCount", "featured_rooms", "featured_room_mates", "infosAddress"));
    }

    //    private function getLongementSponsoriseComunity($n)
    //    {
    //        if($n<=0)
    //            return false;
    //
    //        $available=\Illuminate\Support\Carbon::now()
    //                    ->subDays(6)
    //                    ->toDateString();
    //
    //        $whereScenario = ['1', '2'];
    //
    //    $communityAds = Ads::select("ads.id", "ads.title", "ads.address", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic")->join("users", "users.id", "ads.user_id")->join("user_profiles", "users.id", "user_profiles.user_id")
    //            ->with(['ad_files' => function ($query) {
    //                $query->where('media_type', '0')->orderBy('ordre', 'asc');
    //            }, 'ad_details'])
    //            ->where('comunity_id','!=',null)
    //            ->whereIn('scenario_id', $whereScenario)
    //            ->where('status', '1')
    //            ->where('admin_approve', '1')
    //            ->inRandomOrder()
    //            ->limit($n)
    //            ->get();
    //
    //
    //        return $communityAds;
    //
    //    }
    public function index1(Request $request, $ville = null)
    {
        $infosAddress = null;
        if (!is_null($ville)) {
            $infosAddress = getCoordByAdress($ville);
        }

        $nbAdsCount = Ads::count();
        $nbUsersCount = DB::table("users")->count();

        $lang = 'Français';
        if (App::getLocale() == 'en') {
            $lang = 'English';
        } else {
            $lang = 'Français';
        }

        # $lang = 'English';

        # App::setLocale('en');

        //facebook pixel_id
        //$pixelId = DB::table('config')->where('varname', 'pixel_id')->first()->value;
        //$nbTotalNotif = $this->nbTotalNotif();

        $featured_rooms = [];


        $featured_room_mates = [];

        if (getConfig("sponsorised_ads") == 1) {
            $whereScenario = ['1', '2'];

            $featured_rooms = Ads::select("ads.id", "ads.title", "ads.address", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic")->join("users", "users.id", "ads.user_id")->join("user_profiles", "users.id", "user_profiles.user_id")
                ->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')->orderBy('ordre', 'asc');
                }, 'ad_details'])
                ->whereIn('scenario_id', $whereScenario)
                ->where('status', '1')
                ->where("is_annonce_acceuil", "1")
                ->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))
                ->where('admin_approve', '1')
                ->limit(getConfig("nombre_annonce_sponsorises_acceuil"))
                ->orderByRaw('boosted DESC, ads.updated_at DESC')
                ->get();


            $whereScenario = ['3', '4', '5'];

            $featured_room_mates = Ads::select("ads.id", "ads.title", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic", "user_profiles.birth_date")
                ->join("users", "users.id", "ads.user_id")
                ->join("user_profiles", "users.id", "user_profiles.user_id")
                ->whereIn('scenario_id', $whereScenario)->where("is_annonce_acceuil", "1")
                ->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))
                ->where('status', '1')
                ->where('admin_approve', '1')
                ->offset(0)->limit(getConfig('nombre_profils_sponsorises_acceuil'))
                ->orderByRaw('boosted DESC, ads.updated_at DESC')
                ->get();
        }

        if ($request->session()->has("dataRegister")) {
            $dataRegister = $request->session()->get("dataRegister");
            $request->session()->forget("dataRegister");
            return view('home-test1', compact('lang', "nbAdsCount", "nbUsersCount", "featured_rooms", "featured_room_mates", "fractured_visits", "infosAddress", "dataRegister"));
        }
        return view('home-test1', compact('lang', "nbAdsCount", "nbUsersCount", "featured_rooms", "featured_room_mates", "infosAddress"));
    }


    public function getVilleHome(Request $request)
    {
        $request->session()->forget('registerFb');
        if (!empty($request->latitude) && !empty($request->longitude)) {
            $address = $request->address;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            countShowForm("form1");
            
            if($request->address == "Luxembourg"){
                $ville = "Nommern";
                $latitude = "49.8158683";
                $longitude = "6.1296751";
            }elseif($address == "Saint-Denis, Réunion, France"){
                $ville = "Saint-Denis";
                $test =  floatval($request->latitude);
                $lat = round($test,6);
                $latitude = $lat;
                $longitude = "55.448137";
            }else{   
                $ville = registerVilleRecherche($address, $latitude, $longitude);
            }
            $request->session()->put("dataRegister", [
                "address" => $address,
                "latitude" => $latitude,
                "longitude" => $longitude,
                "scenario" => $request->scenario
            ]);
            $request->session()->put('userInfoRegister', [
                "address_register" => $address,
                "latitude_register" => $latitude,
                "longitude_register" => $longitude,
                "scenario_register" => $request->scenario
            ]);

            return json_encode(array("ville" => $ville, "latitude" => $latitude, "longitude" => $longitude));
        } else  //si l'user n'utilise pas l'autocomplete alors -->
        {
            $address = $request->address;
            if($address == "Luxembourg"){
                $address = "Nommern";
                $infos = getCoordByAdress($address);
            }else{
                $infos = getCoordByAdress($address);
            }
            //trouver si l'adresse est dans la table geocode_infos
            //et on peut obtenir sa latitude et longitude
           
            //si on trouve la ville dans la base
            if (!empty($infos) && (!empty($infos['ville'] || !empty($infos['region'])))) {
                $ville = $infos['ville'];
                if (empty($ville)) {
                    $ville = $infos['region'];
                }
                $request->session()->put('userInfoRegister', [
                    "address_register" => $address,
                    "latitude_register" => $infos['latitude'],
                    "longitude_register" => $infos['longitude'],
                    "scenario_register" => $request->scenario
                ]);
            } else //si la ville n'existe pas dans la table
            {
                $ville = null;
                $infos['latitude'] = null; //---juste pour déclancher l'erreur-----
                $infos['longitude'] = null; //--------erreur----------
            }
            return json_encode(array("ville" => $ville, "latitude" => $infos['latitude'], 'longitude' => $infos['longitude']));
        }
    }

    public function indexHtml(Request $Request)
    {
        return redirect()->route("home");
    }

    public function featuredData(Request $request)
    {

        $whereScenario = ['1', '2'];
        $nbAdsCount = Ads::count();
        $nbUsersCount = DB::table("users")->count();

        $page = 1;


        if (!isset($request->lat) && !isset($request->long)) {
            $featured_rooms = Ads::has('user')->with("user")->with("user.user_profiles")->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }, 'ad_details'])->whereIn('scenario_id', $whereScenario)->where('status', '1')->where("is_annonce_acceuil", "1")->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))->where('admin_approve', '1')->limit($this->logementNbPerPage)->orderByRaw('boosted DESC, ads.updated_at DESC')->get();

            $whereScenario = ['4', '5'];


            $featured_room_mates = Ads::has('user')->with(['user.user_profiles', 'ad_details'])->whereIn('scenario_id', $whereScenario)->where("is_annonce_acceuil", "1")->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))->where('status', '1')->where('admin_approve', '1')->offset(0)->limit($this->colocNbPerPage)->orderByRaw('boosted DESC, ads.updated_at DESC')->get();


            $fractured_visits = Ads::has('user')->with("user.user_profiles")->whereHas('ad_visiting_details', function ($query) {
                $query->where('visiting_date', '>=', date('Y-m-d') . ' 00:00:00');
            })->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }, 'ad_visiting_details' => function ($query) {
                $query->where('visiting_date', '>=', date('Y-m-d') . ' 00:00:00')->orderBy('id', 'asc');
            }, 'ad_details'])->where('admin_approve', '1')->where("is_annonce_acceuil", "1")->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))->get();
        } else {
            $lat = trim($request->lat);
            $long = trim($request->long);
            $ville = trim($request->ville);
            $whereScenario = ['1', '2'];

            $featured_rooms = Ads::has('user')->with("user")->with("user.user_profiles")->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }, 'ad_details'])->whereIn('scenario_id', $whereScenario)->where('status', '1')->where("is_annonce_acceuil", "1")->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))->where('admin_approve', '1')->limit($this->logementNbPerPage)->whereRaw("ads.address LIKE '%" . $ville . "%'")->orderByRaw('boosted DESC, ads.updated_at DESC')->get();

            $whereScenario = ['4', '5'];

            $featured_room_mates = Ads::has('user')->with(['user.user_profiles', 'ad_details'])->whereIn('scenario_id', $whereScenario)->where('status', '1')->where("is_annonce_acceuil", "1")->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))->where('admin_approve', '1')->whereRaw("ads.address LIKE '%" . $ville . "%'")->offset(0)->limit($this->colocNbPerPage)->orderByRaw('boosted DESC, ads.updated_at DESC')->get();

            $fractured_visits = Ads::has('user')->with("user.user_profiles")->whereHas('ad_visiting_details', function ($query) {
                $query->where('visiting_date', '>=', date('Y-m-d') . ' 00:00:00');
            })->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }, 'ad_visiting_details' => function ($query) {
                $query->where('visiting_date', '>=', date('Y-m-d') . ' 00:00:00')->orderBy('id', 'asc');
            }, 'ad_details'])->where('admin_approve', '1')->whereRaw("ads.address LIKE '%" . $ville . "%'")->where("is_annonce_acceuil", "1")->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))->limit($this->logementNbPerPage)->get();
        }


        $featured_html = view('home/featured-data', compact('featured_rooms', 'featured_room_mates', 'fractured_visits'))->render();

        $ville_html = view('home/annonce-ville')->render();

        $common_html = view('home/common-page')->render();

        $other_html = view('home/others', compact("nbAdsCount", "nbUsersCount"))->render();

        return response()->json(array("featured_html" => $featured_html, "ville_html" => $ville_html, "common_html" => $common_html, "other_html" => $other_html));
    }

    public function featuredRoomMates(Request $request)
    {
        $featured_room_mate_page = $request->page;
        $whereScenario = ['4', '5'];
        $nb_featured = Ads::select(DB::raw("count(ads.id) as nb"))->has('user')->with(['user.user_profiles', 'ad_details'])->whereIn('scenario_id', $whereScenario)->where('status', '1')->where('is_featured', '1')->where('admin_approve', '1')->orderByRaw('boosted DESC, ads.updated_at DESC')->first()->nb;
        $nb_page = ceil($nb_featured / 8);
        if ($featured_room_mate_page == 0) {
            $featured_room_mate_page = $nb_page;
        }
        if ($featured_room_mate_page == ($nb_page + 1)) {
            $featured_room_mate_page = 1;
        }
        if ($featured_room_mate_page >= 1 && $featured_room_mate_page <= $nb_featured) {
            $offset = ($featured_room_mate_page - 1) * 8;
            $limit = ($featured_room_mate_page) * 8;
            $featured_room_mates = Ads::has('user')->with(['user.user_profiles', 'ad_details'])->whereIn('scenario_id', $whereScenario)->where('status', '1')->where('is_featured', '1')->where('admin_approve', '1')->offset($offset)->limit(8)->orderByRaw('boosted DESC, ads.updated_at DESC')->get();
            echo view("home.featured_room_mates", compact("featured_room_mates", "featured_room_mate_page"));
        }
    }

    public function quiSommesNous(Request $request)
    {
        return view('home.equipes');
    }

    public function recrutement(Request $request)
    {
        $page = DB::table('static_pages')->where('title', 'Recrutement')->first();
        return view('home.recrutement', compact("page"));
    }

    public function PaginateSponsorisedAds(Request $request)
    {
        $page = $request->page;
        if ($page != 0) $pageCalcul = $page - 1;
        else $pageCalcul = 0;
        if (!isset($request->lat) && !isset($request->long)) {
            if ($request->type == "roommate") {
                $whereScenario = ['4', '5'];
                $featured_room_mates = Ads::has('user')->with(['user.user_profiles', 'ad_details'])->whereIn('scenario_id', $whereScenario)->where("is_annonce_acceuil", "1")->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))->where('status', '1')->where('admin_approve', '1')->offset($pageCalcul * $this->colocNbPerPage)->limit($this->colocNbPerPage)->orderByRaw('boosted DESC, ads.updated_at DESC')->get();
                $pageMaxRoomMate = $request->pageMax;
                return view('home/featured_room_mates', compact('featured_room_mates', "pageMaxRoomMate", "page"))->render();
            }
        } else {
            if ($request->type == "roommate") {
                $whereScenario = ['4', '5'];
                $featured_room_mates = Ads::has('user')->with(['user.user_profiles', 'ad_details'])->whereIn('scenario_id', $whereScenario)->where("is_annonce_acceuil", "1")->whereDate('date_annonce_acceuil', '>=', date('Y-m-d'))->where('status', '1')->where('admin_approve', '1')->offset($pageCalcul * $this->colocNbPerPage)->limit($this->colocNbPerPage)->orderByRaw('boosted DESC, ads.updated_at DESC')->get();
                $pageMaxRoomMate = $request->pageMax;
                return view('home/featured_room_mates', compact('featured_room_mates', "pageMaxRoomMate", "page"))->render();
            }
        }
    }


    public function annonceLogement($ville, $lat, $long)
    {
        $lat = trim($lat);
        $long = trim($long);
        return redirect(searchUrl($lat, $long, $ville, 2));
    }

    public function changeLang($locale = null)
    {
        if ($locale == 'en') {
            \LaravelGettext::setLocale('en_US');
        } else if ($locale == 'fr') {
            \LaravelGettext::setLocale('fr_FR');
        } else {
            \LaravelGettext::setLocale('en_US');
        }
    }

    public function faq(Request $request)
    {
        $url_slug = "faq";
        $page_detail = StaticPage::where('url_slug', $url_slug)->where('is_active', '1')->first();
        if (!is_null($page_detail) || !empty($page_detail)) {
            return view('static-page', compact('page_detail'));
        }
    }

    public function page404(Request $request)
    {
        $page404 = true;
        return view('404', compact('page404'));
    }

    public function getNextFeaturedRoomMates(Request $request)
    {
        $offset = $request->offset;
        $limit = $request->limit;
        $featured_room_mates = Ads::has('user')->with(['user.user_profiles', 'ad_details'])->whereIn('scenario_id', $whereScenario)->where('status', '1')->where('is_featured', '1')->where('admin_approve', '1')->offset($offset)->limit($limit)->orderByRaw('boosted DESC, ads.updated_at DESC')->get();
        return view('featured_room_mates', compact('featured_room_mates'));
    }

    public function cgu()
    {
        $page = DB::table('static_pages')->where('title', 'CGU')->first();
        return view('cgu.cgu', compact("page"));
    }

    public function cdc()
    {
        $page = DB::table('static_pages')->where('title', 'Politique de confidentialité')->first();
        return view('cdc.cdc', compact("page"));
    }

    public function maintenance()
    {
        $verif_maintenance = DB::table('config')->where('varname', "maintenance")->first();
        if ($verif_maintenance->value == 0) {
            return redirect()->route('home');
        } else {
            return view('maintenance');
        }
    }

    public function bloqued()
    {
        return view('bloqued');
    }

    public function subscribeNewsletter(Request $request)
    {
        $email = $request->email;
        $mailcheck = DB::table('newsletter')->where('email', $email)->first();

        if (is_null($mailcheck)) {
            DB::table('newsletter')->insert(
                ['email' => $email]
            );
            $this->sendMailUrgent($email);
            return __("acceuil.newsletter_subscribe");
        } else {
            return __("acceuil.newsletter_subscribe_already");
        }
    }

    private function sendMailUrgent($email)
    {
        $subject = __('mail.need_help');

        try {

            sendMail($email1, "emails.admin.newUrgent", ["subject" => $subject, "userEmail" => $email]);
        } catch (Exception $ex) {
        }

        try {

            sendMailAdmin("emails.admin.newUrgent", ["subject" => $subject, "userEmail" => $email]);
        } catch (Exception $ex) {
        }

        return true;
    }

    public function subscribeContact(Request $request)
    {
        $mailcheck = DB::table('contact')->where('email', $request->mail)->first();

        if (is_null($mailcheck)) {
            DB::table('contact')->insert(
                ['email' => $request->mail, "nom" => $request->nom, 'mobile' => $request->mobile, 'message' => $request->message]
            );
        } else {
            $email = $request->mail;
            DB::table('contact')->where('email', $email)->update(
                ['email' => $request->mail, "nom" => $request->nom, 'mobile' => $request->mobile, 'message' => $request->message]
            );
        }
        return __("acceuil.send_message_text");
    }

    public function bailleurSolution()
    {
        return view('home.solution_bailleurs');
    }

    public function locataireSolution()
    {
        return view('home.solution_locataire');
    }

    public function villePage($page)
    {
        $page_title = __('page_title.' . $page);
        $page_description = __('page_description.' . $page);;
        return view('ville.' . $page, compact("page_title", "page_description"));
    }

    public function viewUserProfil(Request $request, $userId, $adId = null)
    {
        if (Auth::check()) {
            if ($request->type && $request->type == "mail") {
                DB::table("toctoc_mail_click")->insert(["user_id" => Auth::id()]);
            }
            $lifeStyles = DB::table('user_to_lifestyles')
                ->join('user_lifestyles', 'user_to_lifestyles.lifestyle_id', 'user_lifestyles.id')
                ->where('user_to_lifestyles.user_id', $userId)
                ->get();
            $socialInfo = DB::table('user_to_social_interests')
                ->join('social_interests', 'social_interests.id', 'user_to_social_interests.social_interest_id')
                ->where('user_to_social_interests.user_id', $userId)
                ->get();
            $typeMusics = DB::table('user_to_music')
                ->join('user_type_music', 'user_type_music.id', 'user_to_music.music_id')
                ->where('user_to_music.user_id', $userId)
                ->get();
            $user_ad = DB::table("ads")->where("user_id", $userId)->first();

            $is_user_has_ad = !is_null($user_ad);

            $layout = 'outer';
            $user = User::with(['user_profiles', 'user_social_interests', 'user_lifestyles', 'user_type_musics'])->where('id', $userId)->first();
            $is_user_premium = (isUserSubscribed() || isUserSubscribed($user->id));
            $page_title = getFirstWord($user->first_name);

            return view('profile.view-profil', compact("user", "socialInfo", "lifeStyles", "layout", "userProfiles", "is_user_premium", "is_user_has_ad", "adId", "page_title", "typeMusics"));
        } else {
            return redirect()->route("login_popup");
        }
    }


    public function insertPubClick(Request $request, $type = null)
    {
        $id = $request->id;
        $ip = request()->ip();
        $check = DB::table("pub_click")->where("ip", $ip)->where("id_pub", $id)->where("created_at", date("Y-m-d"))->first();
        if (is_null($check)) {
            DB::table("pub_click")->insert(
                ["id_pub" => $id, "ip" => $ip, "created_at" => date("Y-m-d"), "type" => $type]
            );
        }
        if ($type == "mail") {
            return redirect(getConfig('telegram_url'));
        }
        return "true";
    }

    public function getNewReceivedMessage(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {
                $unread_message = Messages::where('receiver_id', $userId)
                    ->whereNull('read_date')
                    ->where('receiver_trash', "0")
                    ->where('checked', "0")
                    ->orderBy('id', 'DESC')
                    ->first();
                if (!is_null($unread_message)) {
                    $userInfo = User::with('user_profiles')
                        ->where('id', $unread_message->sender_id)
                        ->first();
                    return view('messages/notif-message-data', compact('unread_message', "userInfo"))->render();
                }
            }
        }
    }

    public function facturevue()
    {
        return view('admin.facture.facture');
    }

    public function envoi_facture()
    {
        $id_fac = strtotime(now()) - 1644327000;
        $user = Auth::user();

        $subject = i18n('mail.payment_success', getLangUser($user->id));

        $payment = UserPackage::with(['user', 'package', 'payment'])->where('user_id', $user->id)->orderBy('id', 'DESC')->first();
        // Mail to user
        //dd($user);
        if ($payment) {
            $packageDetail = [];
            $UserName = trim($user->first_name) . " " . trim($user->last_name);
            $packageDetail['userName'] = $UserName;
            $packageDetail['subject'] = $subject;
            $packageDetail['packageTitle'] = $payment->package->title;
            $packageDetail['packageDuration'] = $payment->package->duration;
            $packageDetail['packageAmount'] = $payment->payment->amount;
            $packageDetail['packageStartDate'] = $payment->start_date;
            $packageDetail['packageEndDate'] = $payment->end_date;
            $packageDetail['unite'] = $payment->package->unite;
            $packageDetail['date'] = date('d-M-Y');
            $packageDetail['adress'] = $user->address_register;
            $packageDetail['id_fac'] = $id_fac;
        } else {
            $date = date('d-M-Y');
            $end_date = date('d-M-Y', strtotime($date . '+ 30 days'));
            $packageDetail = [];
            $UserName = trim($user->first_name) . " " . trim($user->last_name);
            $packageDetail['userName'] = $UserName;
            $packageDetail['subject'] = $subject;
            $packageDetail['packageTitle'] = 'CONFORT'; //$payment->package->title;
            $packageDetail['packageDuration'] = '30'; //$payment->package->duration;
            $packageDetail['packageAmount'] = 500; //$payment->payment->amount;
            $packageDetail['packageStartDate'] = $date; //$payment->start_date;
            $packageDetail['packageEndDate'] = $end_date; //$payment->end_date;
            $packageDetail['unite'] = 'day'; //$payment->package->unite;
            $packageDetail['date'] = $date;
            $packageDetail['adress'] = $user->address_register;
            $packageDetail['id_fac'] = $id_fac;
        }

        $fichier = PDF::loadView('admin.facture.facture', compact('packageDetail'));

        sendMail($user->email, 'emails.payment', [
            "packageDetail" => $packageDetail,
            "subject" => $this->packageDetail['subject'],
            "lang" => getLangUser($user->id)
        ], [
            "file" => $fichier,
            "title" => 'Facture.pdf'
        ]);

        return view('paiement-confirmation', compact("packageDetail"));
    }

    public function getNewRegistration(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {
                $unread_reg = DB::table('users')->orderBy("id", "desc")->first();
                if (!is_null($unread_reg) && $unread_reg->id != $userId) {
                    $nb = DB::table("registration_notif")->where("user_id", $userId)->where("user_notif_id", $unread_reg->id)->count();
                    if ($nb == 0) {
                        $last = DB::table("registration_notif")->orderBy("id", "desc")->first();
                        if (!is_null($last) && $last->user_notif_id != $unread_reg->id) {
                            DB::table("registration_notif")->where("user_notif_id", $last->user_notif_id)->delete();
                        }
                        DB::table("registration_notif")->insert(["user_id" => $userId, "user_notif_id" => $unread_reg->id]);
                        $userInfo = User::with('user_profiles')
                            ->where('id', $unread_reg->id)
                            ->first();
                        return view('auth/notif-registration-data', compact('unread_reg', "userInfo"))->render();
                    }
                }
            }
        }
    }


    public function getNewSubscription(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {
                /*$unread_pack = DB::table('notif_triggered')->orderBy("id", "desc")->first();
                if(is_null($unread_pack)) {
                    $unread_pack = DB::table('user_packages')->orderBy("id", "desc")->first();
                }*/

                $unread_pack = DB::table('notif_triggered')->orderBy("id", "desc")->first();

                if (!is_null($unread_pack) && $unread_pack->user_id != $userId) {
                    $nb = DB::table("subscription_notif")->where("user_id", $userId)->where("user_notif_id", $unread_pack->id)->count();
                    if ($nb == 0) {
                        $last = DB::table("subscription_notif")->orderBy("id", "desc")->first();
                        if (!is_null($last) && $last->user_notif_id != $unread_pack->id) {
                            DB::table("subscription_notif")->where("user_notif_id", $last->user_notif_id)->delete();
                        }
                        DB::table("subscription_notif")->insert(["user_id" => $userId, "user_notif_id" => $unread_pack->id]);
                        $userInfo = User::with('user_profiles')
                            ->where('id', $unread_pack->user_id)
                            ->first();
                        $packageInfo = DB::table('packages')->where("id", $unread_pack->package_id)->first();
                        return view('auth/notif-subscription-data', compact('unread_pack', "userInfo", "packageInfo"))->render();
                    }
                }
            }
        }
    }

    public function getNewCoupFoudre(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {
                $unread_notif = DB::table("coup_de_foudres")->where('receiver_id', $userId)
                    ->whereNull('read_date')
                    ->where('checked', "0")
                    ->orderBy('id', 'DESC')
                    ->first();
                $nb_unread_notif = DB::table("coup_de_foudres")->where('receiver_id', $userId)
                    ->whereNull('read_date')
                    ->orderBy('id', 'DESC')
                    ->count();
                $html = "";
                if (!is_null($unread_notif)) {
                    $userInfo = User::with('user_profiles')
                        ->where('id', $unread_notif->sender_id)
                        ->first();
                    $adInfo = Ads::where("id", $unread_notif->ad_id)->first();
                    $html = view('coup_de_foudre/coup_notif', compact('unread_notif', "userInfo", "adInfo"))->render();
                }
                return response()->json(array("html" => $html, "nb" => $nb_unread_notif));
            }
        }
    }

    public function getNewVisitRequest(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {
                $unread_notif = DB::table("visit_requests")->join("ads", "ads.id", "visit_requests.ad_id")->where('ads.user_id', $userId)
                    ->whereNull('read_date')
                    ->where('checked', "0")
                    ->select(DB::raw("*,visit_requests.created_at"))
                    ->orderBy('visit_requests.id', 'DESC')
                    ->first();
                $nb_unread_notif = DB::table("visit_requests")->join("ads", "ads.id", "visit_requests.ad_id")->where('ads.user_id', $userId)
                    ->whereNull('read_date')
                    ->count();
                $html = "";
                if (!is_null($unread_notif)) {
                    $userInfo = User::with('user_profiles')
                        ->where('id', $unread_notif->requester_id)
                        ->first();
                    $adInfo = Ads::where("id", $unread_notif->ad_id)->first();
                    $html = view('visit_request/notif-visit-request', compact('unread_notif', "userInfo", "adInfo"))->render();
                }
                return response()->json(array("html" => $html, "nb" => $nb_unread_notif));
            }
        }
    }

    public function getNewApplication(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {
                $unread_notif = DB::table("application")->join("ads", "ads.id", "application.ad_id")->where('ads.user_id', $userId)
                    ->whereNull('date_view')
                    ->where('checked', "0")
                    ->select(DB::raw("*,application.created_at"))
                    ->orderBy('application.id', 'DESC')
                    ->first();
                $nb_unread_notif = DB::table("application")->join("ads", "ads.id", "application.ad_id")->where('ads.user_id', $userId)
                    ->whereNull('date_view')
                    ->count();
                $html = "";
                if (!is_null($unread_notif)) {
                    $userInfo = User::with('user_profiles')
                        ->where('id', $unread_notif->user_id)
                        ->first();
                    $adInfo = Ads::where("id", $unread_notif->ad_id)->first();
                    $html = view('applications/notif-application-data', compact('unread_notif', "userInfo", "adInfo"))->render();
                }
                return response()->json(array("html" => $html, "nb" => $nb_unread_notif));
            }
        }
    }

    public function getNewComment(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {
                $unread_notif = DB::table("comments")->join("ads", "ads.id", "comments.ad_id")->where('ads.user_id', $userId)
                    ->whereNull('read_date')
                    ->where('checked', "0")
                    ->select(DB::raw("*,comments.created_at"))
                    ->orderBy('comments.id', 'DESC')
                    ->first();
                $nb_unread_notif = DB::table("comments")->join("ads", "ads.id", "comments.ad_id")->where('ads.user_id', $userId)
                    ->whereNull('read_date')
                    ->count();
                $html = "";
                if (!is_null($unread_notif)) {
                    $userInfo = User::with('user_profiles')
                        ->where('id', $unread_notif->user_id)
                        ->first();
                    $adInfo = Ads::where("id", $unread_notif->ad_id)->first();
                    $html = view('comments/comment-notif', compact('unread_notif', "userInfo", "adInfo"))->render();
                }
                return response()->json(array("html" => $html, "nb" => $nb_unread_notif));
            }
        }
    }

    public function isExistTriggerSubsNotif($userId)
    {
        $unread_pack = DB::table('notif_triggered')->whereRaw("checked NOT LIKE '%|" . $userId . "|%'")->orderBy("id", "desc")->first();
        return !is_null($unread_pack);
    }

    public function markMessagesAsCheckedNotif(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            Messages::where('receiver_id', $userId)
                ->whereNull('read_date')
                ->where('receiver_trash', "0")
                ->where('checked', "0")
                ->orderBy('id', 'DESC')
                ->update(["checked" => "1"]);
            DB::table("coup_de_foudres")->where('receiver_id', $userId)
                ->whereNull('read_date')
                ->where('checked', "0")
                ->update(["checked" => "1"]);
            DB::table("visit_requests")->join("ads", "ads.id", "visit_requests.ad_id")->where('ads.user_id', $userId)
                ->whereNull('read_date')
                ->where('checked', "0")
                ->update(["checked" => "1"]);

            DB::table("application")->join("ads", "ads.id", "application.ad_id")
                ->where('ads.user_id', $userId)
                ->whereNull('date_view')
                ->where('checked', "0")
                ->update(["checked" => "1"]);
            DB::table("comments")->join("ads", "ads.id", "comments.ad_id")
                ->where('ads.user_id', $userId)
                ->whereNull('read_date')
                ->where('checked', "0")
                ->update(["checked" => "1"]);
            /*$sql = 'update users as u join users as u1 on u.id=u1.id set u.checked=concat(u1.checked,"|'. $userId .'|") where u.checked not like "%|' .$userId. '|%"';
                DB::statement($sql);
            if($this->isExistTriggerSubsNotif($userId)) {
                $sql = 'update notif_triggered as u join notif_triggered as u1 on u.id=u1.id set u.checked=concat(u1.checked,"|'. $userId .'|") where u.checked not like "%|' .$userId.'|%"';
                DB::statement($sql);
            } else {
                $sql = 'update user_packages as u join user_packages as u1 on u.id=u1.id set u.checked=concat(u1.checked,"|'. $userId .'|") where u.checked not like "%|' .$userId.'|%"';
                DB::statement($sql);
            }*/
        }
    }


    public function getAllMessageNotif(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $nbPerPage = 5;
                if (isset($request->page) && $request->page != 0) {
                    $page = $request->page;
                } else $page = 1;
                $offset = ($page * $nbPerPage) - $nbPerPage;
                $userId = Auth::id();
                $response = array();
                $read_messages = Messages::where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->offset($offset)
                    ->limit($nbPerPage)
                    ->get();
                $count_message = Messages::where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->count();
                if (!empty($read_messages) && count($read_messages) > 0) {
                    foreach ($read_messages as $key => $read_message) {
                        $user = User::with('user_profiles')
                            ->where('id', $read_message->sender_id)
                            ->first();
                        $adInfo = Ads::where("id", $read_message->ad_id)->first();
                        $read_message->userInfo = $user;
                        $read_message->adInfo = $adInfo;
                    }
                }
                Messages::where('receiver_id', $userId)
                    ->where('notif_checked', "0")
                    ->where('receiver_trash', "0");

                $html = view('messages/message-all-notif', compact('read_messages', "count_message", "page", "offset"))->render();
                return response()->json(array("html" => $html));
            }
        }
    }

    public function getAllCoupDeFoudreNotif(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $nbPerPage = 5;
                if (isset($request->page) && $request->page != 0) {
                    $page = $request->page;
                } else $page = 1;
                $offset = ($page * $nbPerPage) - $nbPerPage;
                $userId = Auth::id();
                $response = array();
                $read_messages = DB::table("coup_de_foudres")->where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->offset($offset)
                    ->limit($nbPerPage)
                    ->get();
                $count_message = DB::table("coup_de_foudres")->where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->count();
                if (!empty($read_messages) && count($read_messages) > 0) {
                    foreach ($read_messages as $key => $read_message) {
                        $user = User::with('user_profiles')
                            ->where('id', $read_message->sender_id)
                            ->first();
                        $adInfo = Ads::where("id", $read_message->ad_id)->first();
                        $read_message->userInfo = $user;
                        $read_message->adInfo = $adInfo;
                    }
                }
                DB::table("coup_de_foudres")->where('receiver_id', $userId)
                    ->where('notif_checked', "0")
                    ->update(["notif_checked" => "1"]);
                $html = view('coup_de_foudre/coup-all-notif', compact('read_messages', "count_message", "page", "offset"))->render();
                return response()->json(array("html" => $html));
            }
        }
    }

    public function getAllApplicationNotif(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $nbPerPage = 5;
                if (isset($request->page) && $request->page != 0) {
                    $page = $request->page;
                } else $page = 1;
                $offset = ($page * $nbPerPage) - $nbPerPage;
                $userId = Auth::id();
                $response = array();
                $read_messages = DB::table("application")->join("ads", "ads.id", "application.ad_id")->where('ads.user_id', $userId)
                    ->orderBy('application.created_at', 'DESC')
                    ->offset($offset)
                    ->select(DB::raw("application.user_id, application.created_at, ads.title, application.ad_id"))
                    ->limit($nbPerPage)
                    ->get();

                $count_message = DB::table("application")->join("ads", "ads.id", "application.ad_id")->where('ads.user_id', $userId)
                    ->count();

                if (!empty($read_messages) && count($read_messages) > 0) {
                    foreach ($read_messages as $key => $read_message) {
                        $user = User::with('user_profiles')
                            ->where('id', $read_message->user_id)
                            ->first();
                        $adInfo = Ads::where("id", $read_message->ad_id)->first();
                        $read_message->userInfo = $user;
                        $read_message->adInfo = $adInfo;
                    }
                }
                DB::table("application")->join("ads", "ads.id", "application.ad_id")->where('ads.user_id', $userId)
                    ->where('notif_checked', "0")
                    ->update(["notif_checked" => "1"]);
                $html = view('applications/all-application-notif', compact('read_messages', "count_message", "page", "offset"))->render();
                return response()->json(array("html" => $html));
            }
        }
    }

    public function getAllCommentsNotif(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $nbPerPage = 5;
                if (isset($request->page) && $request->page != 0) {
                    $page = $request->page;
                } else $page = 1;
                $offset = ($page * $nbPerPage) - $nbPerPage;
                $userId = Auth::id();
                $response = array();
                $read_messages = DB::table("comments")->join("ads", "ads.id", "comments.ad_id")->where('ads.user_id', $userId)
                    ->orderBy('comments.created_at', 'DESC')
                    ->offset($offset)
                    ->select(DB::raw("comments.user_id, comments.created_at, ads.title, comments.ad_id, comments.text"))
                    ->limit($nbPerPage)
                    ->get();

                $count_message = DB::table("comments")->join("ads", "ads.id", "comments.ad_id")->where('ads.user_id', $userId)
                    ->count();

                if (!empty($read_messages) && count($read_messages) > 0) {
                    foreach ($read_messages as $key => $read_message) {
                        $user = User::with('user_profiles')
                            ->where('id', $read_message->user_id)
                            ->first();
                        $adInfo = Ads::where("id", $read_message->ad_id)->first();
                        $read_message->userInfo = $user;
                        $read_message->adInfo = $adInfo;
                    }
                }
                DB::table("comments")->join("ads", "ads.id", "comments.ad_id")->where('ads.user_id', $userId)
                    ->where('notif_checked', "0")
                    ->update(["notif_checked" => "1"]);
                $html = view('comments/all-comments-notif', compact('read_messages', "count_message", "page", "offset"))->render();
                return response()->json(array("html" => $html));
            }
        }
    }

    function getAllVisitRequestNotif(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $nbPerPage = 5;
                if (isset($request->page) && $request->page != 0) {
                    $page = $request->page;
                } else $page = 1;
                $offset = ($page * $nbPerPage) - $nbPerPage;
                $userId = Auth::id();
                $response = array();
                $read_messages = DB::table("visit_requests")->join("ads", "ads.id", "visit_requests.ad_id")->where('ads.user_id', $userId)
                    ->orderBy('visit_requests.created_at', 'DESC')
                    ->offset($offset)
                    ->select(DB::raw("visit_requests.requester_id, visit_requests.created_at, ads.title"))
                    ->limit($nbPerPage)
                    ->get();

                $count_message = DB::table("visit_requests")->join("ads", "ads.id", "visit_requests.ad_id")->where('ads.user_id', $userId)
                    ->count();
                if (!empty($read_messages) && count($read_messages) > 0) {
                    foreach ($read_messages as $key => $read_message) {
                        $user = User::with('user_profiles')
                            ->where('id', $read_message->requester_id)
                            ->first();
                        $read_message->userInfo = $user;
                    }
                }
                DB::table("visit_requests")->join("ads", "ads.id", "visit_requests.ad_id")->where('ads.user_id', $userId)
                    ->where('notif_checked', "0")
                    ->update(["notif_checked" => "1"]);
                $html = view('visit_request/visit-all-notif', compact('read_messages', "count_message", "page", "offset"))->render();
                return response()->json(array("html" => $html));
            }
        }
    }


    function getNbNotification(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $userId = Auth::id();
                $count_message = Messages::where('receiver_id', $userId)
                    ->where('notif_checked', "0")
                    ->where('receiver_trash', "0")
                    ->orderBy('id', 'DESC')
                    ->count();
                $count_flash = DB::table("coup_de_foudres")->where('receiver_id', $userId)
                    ->where('notif_checked', "0")
                    ->orderBy('id', 'DESC')
                    ->count();
                $count_visit = DB::table("visit_requests")->join("ads", "ads.id", "visit_requests.ad_id")->where('ads.user_id', $userId)
                    ->where('notif_checked', "0")
                    ->count();
                $count_candidature = DB::table("application")->join("ads", "ads.id", "application.ad_id")->where('ads.user_id', $userId)
                    ->where('notif_checked', "0")
                    ->count();
                $count_pending = DB::select('SELECT COUNT(id) AS nb FROM application WHERE user_id=? AND status=0 AND pending_checked=0', array($userId));//$app_states['waiting'];
                $count_pending = $count_pending[0]->nb;
                $count_accepted = $accepted = DB::select('SELECT COUNT(id) AS nb FROM application WHERE user_id=? AND status=1 AND accepted_checked=0', array($userId));//$app_states['accepted'];
                $count_accepted = $count_accepted[0]->nb;
                $count_refused = DB::select('SELECT COUNT(id) AS nb FROM application WHERE user_id=? AND status=2 AND refused_checked=0', array($userId));//$app_states['declined'];
                $count_refused = $count_refused[0]->nb;
                $count_candidature = $count_candidature + $count_pending + $count_accepted + $count_refused;
                $count_comment = DB::table("comments")->join("ads", "ads.id", "comments.ad_id")->where('ads.user_id', $userId)
                    ->where('notif_checked', "0")
                    ->count();

                $favourite_count = count(Auth::user()->favorites()->has('ads.user')->whereHas('ads', function ($query) {
                    $query->where('status', '1')->where('admin_approve', '1');
                })->get());

                return response()->json(array("message" => $count_message, "flash" => $count_flash, "visit" => $count_visit, "application" => $count_candidature, "comments" => $count_comment, "favori" => $favourite_count));
            }
        }
    }


    function nbTotalNotif()
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $count_message = Messages::where('receiver_id', $userId)
                ->where('notif_checked', "0")
                ->where('receiver_trash', "0")
                ->orderBy('id', 'DESC')
                ->count();
            $count_flash = DB::table("coup_de_foudres")->where('receiver_id', $userId)
                ->where('notif_checked', "0")
                ->orderBy('id', 'DESC')
                ->count();
            $count_visit = DB::table("visit_requests")->join("ads", "ads.id", "visit_requests.ad_id")->where('ads.user_id', $userId)
                ->where('notif_checked', "0")
                ->count();
            $count_candidature = DB::table("application")->join("ads", "ads.id", "application.ad_id")->where('ads.user_id', $userId)
                ->where('notif_checked', "0")
                ->count();
            return ($count_message + $count_flash + $count_visit + $count_candidature);
        }
        return 0;
    }

    public function contactUs(Request $request)
    {
        $standard = false;
        return view('home/contact', compact("standard"));
    }

    public function sendMessageAnnonceur(Request $request)
    {
        $ad_id = $request->ad_id;
        $message = $request->message;
        $ad = Ads::has("user")->with(["user"])->where('id', $ad_id)->first();
        if (!empty($message)) {
            $ad->message = $message;
            if (!empty($ad->user->email)) {
                $this->sendMailAnnonceur($ad);
            }
        }
    }

    private function sendMailAnnonceur($ad)
    {
        App::setlocale(getLangUser($ad->user->id));
        $email = $ad->user->email;
        $subject = __('mail.contact_annonceur');

        try {

            sendMail($email, 'emails.users.contactAnnonceur', [
                'subject' => $subject,
                'adInfo'  => $ad,
                'lang'    => getLangUser($ad->user->id)
            ]);
        } catch (Exception $ex) {
        }
        return true;
    }

    function saveTempInfos(Request $request)
    {
        $request->session()->put('userInfoRegister', $request->all());
        echo "true";
    }

    function saveFbLoyer(Request $request) //redirection apres etape2 facebook
    {
        //        dd($request);
        $num_validator = DB::table("user_profiles")->where("mobile_no", $request->mobile_no)->exists();
        if ($num_validator == true) {
            return back()->with('tab', [
                'num_duplication' => 'Le numéro est déjà utilisé',
                'mobile_no' => $request->mobile_no,
                'origin_country' => $request->origin_country,
                'iso_code' => $request->iso_code,
                'dial_code' => $request->dial_code,
                'valid_number' => $request->valid_number,
                'professional_category' => $request->professional_category
            ]);
        }

        $authUser = $request->session()->get('authUser');
        if (is_null($authUser)) {
            $authUser = Auth::id();
        } else {
            $authUser = $authUser->id;
        }
        $aujoud_hui = date('Y-m-d');
        $data = array();
        $data['last_conection'] = $aujoud_hui;
        DB::table('users')->where('id', $authUser)->update($data);
        DB::table("user_profiles")->where("user_id", $authUser)->update([
            'professional_category' => $request->professional_category,
            'profession' => $request->profession,
            'mobile_no' => $request->mobile_no,
            'iso_code' => $request->iso_code,
            'dial_code' => $request->dial_code,
            'origin_country' => $request->origin_country,
            'origin_country_code' => $request->origin_country_code
        ]);
        //retour a etape1 pour verification de numero
        DB::table("users")->where("id", $authUser)->update(['etape_inscription' => 1]);

        //        $authUser =  DB::table('users')->where('id',Auth::id())->first();
        //        if($authUser){
        //            if(is_null($authUser->email)){
        //                return redirect()->route('useremail');
        //            }else{
        //                $subject = __('login.registered_with');
        //
        //                # Envoi Email
        //                sendMail($authUser->email,'emails.users.registration-email',[
        //                    "MailSubject" => $subject ,
        //                    'lang'  => getLangUser(Auth::id())
        //                ]);
        //                return redirect()->route('user.dashboard');
        //            }
        //        }else{
        //            return redirect()->route('user.dashboard');
        //        }
        return redirect()->route('user.dashboard');



        // DB::table("user_profiles")->where("user_id", $authUser->id)->update([
        //     "professional_category" => $request->professional_category,
        //     "school" => (isset($request->school)) ? $request->school : NULL,
        //     "profession" => (isset($request->profession)) ? $request->profession : NULL,
        //     "iso_code" => $iso_code,
        //     "dial_code" => $dial_code,
        //     "mobile_no" => $mobile_no,
        //     "revenus" => $request->revenus,
        //     "origin_country" => (isset($request->origin_country)) ? $request->origin_country : NULL,
        //     "origin_country_code" => (isset($request->origin_country_code)) ? $request->origin_country_code : NULL,
        // ]);


        // $infoRegister = $request->session()->get("userInfoRegister");
        // if(!is_null($infoRegister) && isset($infoRegister['latitude_register']) && !empty($infoRegister['latitude_register']) && !is_null($infoRegister['latitude_register']))
        // {
        //     if($request->session()->has("authUser")) {
        //         $authUser = $request->session()->get("authUser");
        //         $iso_code = NULL;
        //         $dial_code = NULL;
        //         if (!empty($request->iso_code)) {
        //             $iso_code = trim($request->iso_code);
        //         }

        //         if (!empty($request->dial_code)) {
        //             $dial_code = trim($request->dial_code);
        //         }

        //         if (!empty($request->valid_number)) {
        //             $mobile_no =  manageMobileNo($request->dial_code, trim($request->valid_number));
        //         } else {
        //             $mobile_no =  manageMobileNo($request->dial_code, trim($request->mobile_no));
        //         }

        //         DB::table("user_profiles")->where("user_id", $authUser->id)->update([
        //             "professional_category" => $request->professional_category,
        //             "school" => (isset($request->school)) ? $request->school : NULL,
        //             "profession" => (isset($request->profession)) ? $request->profession : NULL,
        //             "iso_code" => $iso_code,
        //             "dial_code" => $dial_code,
        //             "mobile_no" => $mobile_no,
        //             "revenus" => $request->revenus,
        //             "origin_country" => (isset($request->origin_country)) ? $request->origin_country : NULL,
        //             "origin_country_code" => (isset($request->origin_country_code)) ? $request->origin_country_code : NULL,
        //         ]);
        //         DB::table("users")->where("id", $authUser->id)->update(['etape_inscription' => 2 ]);
        //         Auth::login($authUser, true);

        //         if(isset($request->accept_as)) {
        //             $infoRegister['accept_as'] = $request->accept_as;
        //         }

        //         $user_id = $authUser->id;

        //         if (!empty($request->type_musics) && count($request->type_musics) > 0) {
        //             UserTypeMusics::where('user_id', $user_id)->delete();
        //             foreach ($request->type_musics as $type_music) {
        //                 $userTypeMusic = new UserTypeMusics;
        //                 $userTypeMusic->user_id = $user_id;
        //                 $userTypeMusic->music_id = $type_music;
        //                 $userTypeMusic->save();
        //             }
        //         }

        //         if (!empty($request->social_interests) && count($request->social_interests) > 0) {
        //             UserSocialInterests::where('user_id', $user_id)->delete();
        //             foreach ($request->social_interests as $social_interests) {
        //                 $userSocialInterest = new UserSocialInterests;
        //                 $userSocialInterest->user_id = $user_id;
        //                 $userSocialInterest->social_interest_id = $social_interests;
        //                 $userSocialInterest->save();
        //             }
        //         }

        //         if (!empty($request->sports) && count($request->sports) > 0) {
        //             UserTypeMusics::where('user_id', $user_id)->delete();
        //             foreach ($request->sports as $sport) {
        //                 $infos = [
        //                     "user_id" => $user_id,
        //                     "sport_id" => $sport
        //                 ];
        //                 DB::table('user_to_sport')->insert($infos);
        //             }
        //         }


        //         $request->session()->put("userInfoRegister", $infoRegister);

        //         if(getInfoRegister('scenario_register') == 3) {
        //             return redirect("/louer-une-propriete" . getPathInscription() . "4");
        //         } else {
        //             countShowForm("form3");
        //             return redirect(searchUrl( $infoRegister['latitude_register'],  $infoRegister['longitude_register'],  $infoRegister['address_register'],  $infoRegister['scenario_register']) . getPathInscription() . "3");
        //         }
        //     }


    }
    public function useremail()
    {

        return view("auth.userfbemail");
    }
    public function save_mail(Request $request)
    {

        $UserName = $request->email;
        $data = array();
        $data['email'] = $UserName;
        DB::table('users')->where('id', Auth::id())->update($data);

        $subject = __('login.registered_with');

        # Envoi Email
        sendMail($UserName, 'emails.users.registration-email', [
            "subject" => $subject,
            "MailSubject" => $subject,
            'lang'  => getLangUser(Auth::id())
        ]);
        $a = __('register.change_usermailsessi');
        session::put('message5', $a);
        return redirect()->route('user.dashboard');
    }

    public function listSchool(Request $request)
    {
        $schools = DB::table("schools")->get();
        $results = [];
        foreach ($schools as $key => $school) {
            $results[] = $school->name;
        }
        return json_encode($results);
    }

    public function listMetier(Request $request)
    {
        $schools = DB::table("metier")->get();
        $results = [];
        foreach ($schools as $key => $school) {
            $results[] = $school->name;
        }
        return json_encode($results);
    }

    public function reportError(Request $request)
    {
        sendMailError();
        echo "true";
    }

    function loadRegister(Request $request)
    {
        if ($request->ajax()) {
            $ajax = true;
            return view('auth/register_forms', compact("ajax"));
        }
    }

    public function postAds(Request $request, $type = null)
    {
        if (is_null($type)) {
            $request->session()->flash('action', "publiez_annonce");
        } else {
            $request->session()->flash('action', "publiez_annonce");
            $request->session()->flash('type', "logement");
        }

        return view('common.choosescen');
    }

    public function NotifBailti(Request $request)
    {
        $ip = $request->ip;
        $domaine = $request->domaine;
        sendMailSecurity($ip, $domaine);
    }

    public function listMetro(Request $request)
    {
        if (isset($request->address) && !empty($request->address)) {
            $address = $request->address;
        } else {
            $addressInfo = $request->session()->get("ADRESS_INFO");
            $address = $addressInfo->address;
        }
        $ville = trim(getAdressVilleV2($address));
        $metros = DB::table("metro_lines")->where('ville', $ville)->get();
        $results = [];
        foreach ($metros as $key => $metro) {
            $results[] = $metro->name;
        }
        return json_encode($results);
    }

    public function regionSlug(Request $request)
    {
        $infos = DB::table("geocode_infos")->get();
        foreach ($infos as $key => $info) {
            DB::table("geocode_infos")->where("id", $info->id)->update([
                "region_slug" => str_slug($info->region)
            ]);
        }
    }


    public function getAllMessageNotifControle(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $nbPerPage = 5;
                if (isset($request->page) && $request->page != 0) {
                    $page = $request->page;
                } else $page = 1;
                $offset = ($page * $nbPerPage) - $nbPerPage;
                $userId = Auth::id();
                $response = array();
                $read_messages = Messages::where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->offset($offset)
                    ->limit($nbPerPage)
                    ->get();
                $count_message = Messages::where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
                    ->count();
                if (!empty($read_messages) && count($read_messages) > 0) {
                    foreach ($read_messages as $key => $read_message) {
                        $user = User::with('user_profiles')
                            ->where('id', $read_message->sender_id)
                            ->first();
                        $adInfo = Ads::where("id", $read_message->ad_id)->first();
                        $read_message->userInfo = $user;
                        $read_message->adInfo = $adInfo;
                    }
                }
                Messages::where('receiver_id', $userId)
                    ->where('notif_checked', "0")
                    ->where('receiver_trash', "0")
                    ->update(["notif_checked" => "1"]);
                $html = view('messages/message-all-notif', compact('read_messages', "count_message", "page", "offset"))->render();
                return response()->json(array("html" => $html));
            }
        }
    }

    public function basculer(Request $request)
    {
        # App::setLocale('en');
        if (isset($request->langue)) {
            if ($request->langue == 'fr') {
                App::setLocale('fr');
            } else {
                App::setLocale('en');
            }

            session()->put('locale', $request->langue);
        }

        # App::getLocale();

        return redirect()->back();
    }

    public function error_geoapify(Request $request)
    {
        sendMailAdmin("emails.MailErrorGeoapify", []);
    }
}
