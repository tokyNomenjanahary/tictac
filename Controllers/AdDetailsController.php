<?php

namespace App\Http\Controllers;

use App\Ads_duplication;
use App\backup_locataire;
use App\FacebookAPI;
use App\Http\Models\Ads\AdDocuments;
use App\Http\Models\Ads\AdProximity;
use App\Http\Models\Ads\Ads;
use App\Http\Models\Ads\VisitRequests;
use App\Http\Models\Ads\AdVisitingDetails;
use App\Http\Models\Ads\Favourites;
use App\Http\Models\Ads\Messages;
use App\Http\Models\SignalTag;
use App\Ratings;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class AdDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['viewAd', 'createURLSlugAd', 'saveReturnInfos', 'addRemoveFavorites']]);
    }

    public function createURLSlugAd()
    {
        $all_ads = Ads::get();
        if (!empty($all_ads)) {
            foreach ($all_ads as $ad) {
                $url_slug = str_slug($ad->title, '-');
                Ads::where('id', $ad->id)->update(['url_slug' => $url_slug]);
            }
        }
    }

    public function signalNoPhone(Request $request)
    {
        $ad_id = $request->ad_id;

        saveSignalAd($ad_id, "no_phone_respond");
        return response()->json('success');
    }

    public function signalNoFb(Request $request)
    {
        $ad_id = $request->ad_id;

        saveSignalAd($ad_id, "no_fb_respond");
        return response()->json('success');
    }

    //function qui va insérer une valeur dans la table ads_duplication
    private function create_ads_duplication($ad_id, $slug)
    {
        Ads_duplication::insert([
            "ads_id" => $ad_id,
            "url_slug" => $slug,
            "title_number" => 0,
            "description_number" => 0,
        ]);
    }

    private function get_id_duplication($url_slg_)
    {
        $id = Ads_duplication::where('url_slug', $url_slg_)->first()->id;
        return $id;
    }

    //fonction qui permet de load a valeur dans la BDD Ads_duplicaion
    private function get_title_number($id)
    {
        return Ads_duplication::find($id)->title_number;
    }

    private function get_description_number($id)
    {
        return Ads_duplication::find($id)->description_number;
    }

    //fonction qui permet de d'incrementer la vaeur de BDD Ads_duplication chaque duplication
    private function incr_Ads_duplication($id)
    {
        $title = $this->get_title_number($id);
        $description = $this->get_description_number($id);
        //incrementation de la valeur de la colone du Bdd ads_duplication
        ++$title;
        ++$description;
        Ads_duplication::where('id', $id)->update([
            "title_number" => $title,
            "description_number" => $description,
        ]);
    }

    private function create($url)
    {
        $k = Ads_duplication::where('url_slug', $url);
        if ($k->count() == 0) {
            return true;
        } else {
            return false;
        }
    }

    //fonction qui retourne le nouvelle nom de la duplication
    private function new_value($old, $num)
    {
        $tab = explode("~~", $old);

        if (count($tab) == 1) {
            $new = $old . " ~~" . $num;
        } else {
            $tab[count($tab) - 1] = $num;
            $new = implode("~~", $tab);
        }
        return $new;
    }

    public function signalAdLoue(Request $request)
    {
        $ad_id = $request->ad_id;

        saveSignalAd($ad_id, "ad_loue");
        return response()->json('success');
    }

    public function matchAd($id)
    {
        $ad = DB::table("ads")->leftJoin("users", "users.id", "ads.user_id")->where("ads.id", $id)->first();
        if (strtolower($ad->first_name . " " . $ad->last_name) == strtolower(Auth::user()->first_name . " " . Auth::user()->last_name)) {
            DB::table("ads")->where("id", $id)->update(
                ["user_id" => Auth::id()]
            );
            DB::table("users")->where("id", $ad->user_id)->delete();
        }
    }

    public function sendMessageFlash(Request $request)
    {
        if ($this->isAllowedMessageFlash()) {
            $user_id = $request->user_id;
            $ad_id = $request->ad_id;
            $sender_ad_id = $request->sender_ad_id;
            $sender_id = Auth::id();
            if (isset($request->sender_id)) {
                $sender_id = $request->sender_id;
            }
            countTrakingAds('toc_toc', $ad_id);

            $userInfo = DB::table('users')->where('id', $user_id)->first();

            $annonce = DB::table('ads')->where('id', $ad_id)->first();
            $userInfo->ad_title = $annonce->title;

            $userInfo->ad_id = $ad_id;
            $userInfo->sender_ad_id = $sender_ad_id;
            DB::table("coup_de_foudres")->insert(
                ["sender_id" => $sender_id, "receiver_id" => $user_id, "ad_id" => $ad_id, "sender_ad_id" => $sender_ad_id, "created_at" => date("Y-m-d H:i:s")]
            );
            if (!is_null($userInfo->email)) {

                $subject = __('mail.message_flash');
                try {
                    sendMail($userInfo->email, "emails.users.messageFlash", ["subject" => $subject, "UserInfo" => $userInfo, "lang" => getLangUser($user_id)]);

                } catch (Exception $ex) {
                }
            }

            echo "true";
        } else {
            echo "false";
        }
    }

    public function saveReturnInfos(Request $request)
    {
        $infos = array('action' => $request->action, 'return_url' => $request->return_url);
        $request->session()->put("returnInfos", $infos);
        if ($request->ajax()) {
            echo "true";
        }
    }

    public function getAllMessagesFlash(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $flashs = DB::table("coup_de_foudres")
                ->join("users", "users.id", "coup_de_foudre.sender_id")
                ->join("user_profiles", "user_profiles.user_id", "users.id")
                ->join("ads", "ads.id", "coup_de_foudre.ad_id")
                ->select(DB::raw("ads.title, coup_de_foudre.created_at,users.first_name,user_profiles.profile_pic"))
                ->where("coup_de_foudre.receiver_id", $userId)
                ->orderBy("coup_de_foudre.id", "DESC")
                ->get();
            DB::table("coup_de_foudres")->where('receiver_id', $userId)
                ->whereNull('read_date')
                ->update(["read_date" => date("Y-m-d H:i:s")]);
            $html = "";
            if (!is_null($flashs) && !empty($flashs)) {
                $html = view('coup_de_foudre/data-flash', compact('flashs'))->render();
            }
            return response()->json(array("html" => $html, "nb" => count($flashs)));
        }
    }

    public function applyToAds($adId, Request $request)
    {

        $ad = Ads::has('user')
            ->with(['user.user_profiles', 'user.user_packages' => function ($query) {
                $query->orderBy('id', 'desc')->first();
            }])
            ->with('type_location')
            ->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }, 'ad_details.property_type', 'nearby_facilities', 'ad_to_allowed_pets.allowed_pets', 'ad_to_property_features.property_features', 'ad_to_amenities.amenities', 'ad_to_guarantees.guarantees', 'ad_to_property_rules.property_rules', 'ad_visiting_details', 'ad_uploaded_guarantees.guarantees'])
            ->where('id', $adId)
            ->first();
        if ($ad) {
            $documents = new AdDocuments();

            $ad_documents = $documents->where('ad_id', $ad->id)->get();

            $savedDocuments = null;

            if (!empty($ad_documents)) {
                $savedDocuments = DB::table('documents')
                    ->where('user_id', Auth::user()->id)
                    ->get();
            }
            return view('property.apply-to-page', compact("ad", "ad_documents", "savedDocuments"));
        } else {
            Session::flash('error', __('addetails.ads_not_exist'));
            return redirect()->route('user.dashboard');
        };
    }

    public function countShowPhone($ads_id)
    {
        countTrakingAds('phone', $ads_id);
        return response()->json('success');
    }

    public function showFbContactCount($ads_id)
    {
        countTrakingAds('contact_fb', $ads_id);
        return response()->json('success');
    }

    public function SendVisitRequest($adId, Request $request)
    {
        $ad = Ads::has('user')
            ->with(['user.user_profiles', 'user.user_packages' => function ($query) {
                $query->orderBy('id', 'desc')->first();
            }])
            ->with('type_location')
            ->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }, 'ad_details.property_type', 'nearby_facilities', 'ad_to_allowed_pets.allowed_pets', 'ad_to_property_features.property_features', 'ad_to_amenities.amenities', 'ad_to_guarantees.guarantees', 'ad_to_property_rules.property_rules', 'ad_visiting_details', 'ad_uploaded_guarantees.guarantees'])
            ->where('id', $adId)
            ->first();
        return view('property.send-visit-page', compact("ad"));
    }

    //Affichage des annonces specialement concu pour google
    public function showVilleTemplate($mot_cles, $ville, $request)
    {
        $mots = DB::table("liste_mot_cles")->where("mot_cles", $mot_cles)->first();
        $mot_cles = ucfirst($mot_cles);
        if (!is_null($mots)) {
            $mot_cles = ucfirst($mots->original_text);
        }
        $searched_id = "";
        $id = 241252; //id de l'annonce utiliser pour generer l'annonce
        $user_id = Auth::id();
        $infosVille = DB::table('france_city')->where("slug", $ville)->first();
        if (is_null($infosVille)) {
            $infosVille = DB::table('villes_france_free')->select(DB::raw("ville_nom_reel as name, ville_slug as slug, ville_latitude_deg as gps_lat, ville_longitude_deg as gps_lng"))->where("ville_slug", $ville)->first();
            if (is_null($infosVille)) {
                abort(404);
            }
        }

        $desactive = false;

        ///random prenom
        if (isset($infosVille->price) && isset($infosVille->user)) {
            $prix = $infosVille->price;
            $prenom = $infosVille->user;
        } else {
            $nb = rand(1, 20453);
            $prix = rand(100, 300);
            $prenom = DB::table('liste_prenoms')->where('id', $nb)->first()->prenoms;
        }

        $isVillePage = true;

        $signal_tags = SignalTag::all();
        $searched_ad = Ads::find($searched_id);
        $scenario = ["Rent a property", "Share an accomodation", "Seek to rent a property", "Seek to share an accomodation", "Seek someone to search together a property"];

        if (isset($id)) {
            if (isset($request->contact) && ($request->contact == true)) {
                countTrakingAds('message', $id);
            }
            //requete pour generer l'annonce
            $ad = Ads::has('user')
                ->with(['user.user_profiles', 'user.user_packages' => function ($query) {
                    $query->orderBy('id', 'desc')->first();
                }])
                ->with('type_location')
                ->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')->orderBy('ordre', 'asc');
                }, 'ad_details.property_type', 'nearby_facilities', 'ad_to_allowed_pets.allowed_pets', 'ad_to_property_features.property_features', 'ad_to_amenities.amenities', 'ad_to_guarantees.guarantees', 'ad_to_property_rules.property_rules', 'ad_visiting_details', 'ad_uploaded_guarantees.guarantees'])
                ->where('id', $id)
                ->first();

            if (!empty($ad)) {
                $ad->address = $infosVille->name;
                $ad->latitude = $infosVille->gps_lat;
                $ad->longitude = $infosVille->gps_lng;
                $ad->title = __("addetails.title_landing", ["mot_cles" => $mot_cles, 'ville' => $infosVille->name]);
                $ad->description = __("addetails.description_landing", ["mot_cles" => $mot_cles, 'ville' => $infosVille->name]);
                $ad->scenario_id = 1;
                $ad->user->first_name = $prenom;
                $ad->min_rent = $prix;
                $suggestion_ads = Ads::has('user')->with(['user.user_profiles'])->where('address', $ad->address)->where('scenario_id', $ad->scenario_id)->where('id', '!=', $ad->id)->where("admin_approve", 1)->orderBy('id', 'desc')->limit(6)->get();
                $floute = true;
                if (isUserSubscribed() || $user_id == $ad->user_id) {
                    $floute = false;
                }
                if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                    if (isUserSubscribed()) {
                        if (Auth::user()->type_design == 1) {
                            $view_name = 'addetails/view-first-second-scen';
                        } else {
                            $view_name = 'addetails/basic/view-first-second-scen';
                        }
                    } else {
                        $view_name = 'addetails/basic/view-first-second-scen';
                    }
                } else {
                    if (isUserSubscribed()) {
                        if (Auth::user()->type_design == 1) {
                            $view_name = 'addetails/view-third-fourth-scen';
                        } else {
                            $view_name = 'addetails/basic/view-third-fourth-scen';
                        }
                    } else {
                        $view_name = 'addetails/basic/view-third-fourth-scen';
                    }
                }

                if ($ad->admin_approve == 1) {
                    if ($ad->user_id != $user_id) {
                        $request->session()->put("lastViewAd", $ad->id);
                    }

                    $ad->messageFlash = false;
                    $messageFlash = DB::table("coup_de_foudres")->where("ad_id", $ad->id)->where("sender_id", $user_id)->first();
                    if (!is_null($messageFlash)) {
                        $ad->messageFlash = true;
                    }

                    $page_title = $ad->title;
                    $page_meta_keyword = $ad->title;
                    $page_description = mb_substr($ad->description, 0, 220);
                    $ad->scenario_type = $scenario[$ad->scenario_id - 1];
                    $favourites = [];
                    $friend_ads_arr = [];

                    if (!empty($ad->user->user_packages) && count($ad->user->user_packages) > 0) {
                        if (strtotime(date('Y-m-d')) > strtotime($ad->user->user_packages[0]->end_date)) {
                            $ad_premium = 'no';
                        } else {
                            $ad_premium = 'yes';
                        }
                    } else {
                        $ad_premium = 'no';
                    }
                    $user_equal_ad = false;
                    $user_provider_equal_facebook = false;
                    if (!empty(Auth::user())) {
                        $user_equal_ad = Auth::user()->id != $ad->user->id;
                        $user_provider_equal_facebook = Auth::user()->provider == 'facebook';
                    }

                    $userProfiles = DB::table('user_profiles')
                        ->leftJoin('cities', 'user_profiles.city_id', 'cities.id')
                        ->leftJoin('countries', 'user_profiles.country_id', 'countries.id')
                        ->where('user_profiles.user_id', $ad->user->id)
                        ->first();

                    $lifeStyles = DB::table('user_to_lifestyles')
                        ->join('user_lifestyles', 'user_to_lifestyles.lifestyle_id', 'user_lifestyles.id')
                        ->where('user_to_lifestyles.user_id', $ad->user->id)
                        ->get();

                    $socialInfo = DB::table('user_to_social_interests')
                        ->join('social_interests', 'social_interests.id', 'user_to_social_interests.social_interest_id')
                        ->where('user_to_social_interests.user_id', $ad->user->id)
                        ->get();
                    $typeMusics = DB::table('user_to_music')
                        ->join('user_type_music', 'user_type_music.id', 'user_to_music.music_id')
                        ->where('user_to_music.user_id', $ad->user->id)
                        ->get();

                    $ad_proximities = AdProximity::with('proximity')->where('ad_id', $ad->id)->get();

                    if (!empty($user_id)) {
                        $layout = 'inner';

                        $userPackage = Auth::user()->user_packages()->select('end_date')->orderBy('id', 'desc')->first();
                        if (!empty($userPackage)) {
                            if (strtotime(date('Y-m-d')) > strtotime($userPackage->end_date)) {
                                $user_premium = 'no';
                            } else {
                                $user_premium = 'yes';
                            }
                        } else {
                            $user_premium = 'no';
                        }

                        if (count(Auth::user()->favorites) > 0) {
                            foreach (Auth::user()->favorites as $favourite) {
                                array_push($favourites, $favourite->ad_id);
                            }
                        }

                        if ($user_id == $ad->user->id) {

                            $fb_friends = User::with('user_friend_list')->where('id', $user_id)->first();
                            $fb_friends_arr = [];
                            if (!empty($fb_friends) && !empty($fb_friends->user_friend_list)) {
                                foreach ($fb_friends->user_friend_list as $fb_friend) {
                                    $fb_friends_arr[] = $fb_friend->fb_friend_id;
                                }
                            }

                            if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                                $friends_scnario = [3, 4];
                            } else if ($ad->scenario_id == 3 || $ad->scenario_id == 4) {
                                $friends_scnario = [1, 2];
                            } else {
                                $friends_scnario = [5];
                            }

                            if (!empty($fb_friends_arr) && count($fb_friends_arr) > 0) {
                                $radius_query_string = '6371 * acos(cos(radians(' . $ad->latitude . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $ad->longitude . ')) + sin(radians(' . $ad->latitude . ')) * sin(radians(latitude))) ';

                                $friend_ads = User::with(['user_profiles', 'ads' => function ($query) use ($friends_scnario, $radius_query_string) {
                                    $query->whereRaw($radius_query_string . '<= 40')->whereIn('scenario_id', $friends_scnario);
                                }])->whereIn('provider_id', $fb_friends_arr)->get();
                                if (!empty($friend_ads) && count($friend_ads) > 0) {
                                    foreach ($friend_ads as $key => $friend_ad) {
                                        if (!empty($friend_ad->ads) && count($friend_ad->ads) > 0) {
                                            $friend_ads_arr[$key]['user_profile'] = [
                                                'first_name' => $friend_ad->first_name,
                                                'last_name' => $friend_ad->last_name,
                                                'profile_pic' => $friend_ad->user_profiles->profile_pic,
                                            ];
                                            foreach ($friend_ad->ads as $friend_add) {
                                                $friend_ads_arr[$key]['ads'][] = [
                                                    'ad_id' => $friend_add->id,
                                                    'ad_title' => $friend_add->title,
                                                    'ad_location' => $friend_add->address,
                                                    'ad_url_slug' => $friend_add->url_slug,
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        $documents = new AdDocuments();
                        $ad_documents = $documents->where('ad_id', $ad->id)->get();
                        //user saved documents
                        $savedDocuments = null;

                        return view($view_name, compact('layout', 'savedDocuments', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads', "user_premium", 'isVillePage'));
                    } else {

                        $documents = new AdDocuments();
                        $ad_documents = $documents->where('ad_id', $ad->id)->get();
                        if (!isset($user_premium)) {
                            $user_premium = 'no';
                        }
                        if (!isset($ad_premium)) {
                            $ad_premium = 'no';
                        }
                        $layout = 'outer';
                        if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                            return view($view_name, compact('layout', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads', "user_premium", 'isVillePage'));
                        } else if ($ad->scenario_id == 3 || $ad->scenario_id == 4 || $ad->scenario_id == 5) {
                            return view($view_name, compact('layout', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads', "user_premium", 'isVillePage'));
                        }
                    }
                } else {
                    $request->session()->flash('error', __('backend_messages.ad_desactive'));
                    return redirect()->route('user.dashboard');
                }
            } else {
                //probleme pour l'user qui n'est pas auth
                $request->session()->flash('error', __('backend_messages.no_ad_found'));
                return redirect()->route('user.dashboard');
            }
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }
    }

    public function duplicateAd($id, Request $request)
    {
        $ad = Ads::find($id);
        $newAd = $ad->replicate();
        $url_slg_ = $newAd->url_slug;
        //test pour verifier s'il y a déja une duplication avant
        //pas besoin d'insérer une nouvelle donné s'il en a déja, il faut juste le mettre à jour
        if (($ad->duplication_ads()->get()->count()) == 0 && ($this->create($url_slg_))) {
            //ici, cette ads n'a pas une donné dans ads_duplication, donc on va le créer
            $this->create_ads_duplication($id, $url_slg_);
        }
        $id_dupli = $this->get_id_duplication($url_slg_); //prenons l'id de ads_duplication
        $this->incr_Ads_duplication($id_dupli); //incrementation de la valeur dans le Bdd ads-duplication
        $newAd->title = $this->new_value($newAd->title, $this->get_title_number($id_dupli));
        $newAd->description = $this->new_value($newAd->description, $this->get_description_number($id_dupli));

        $newAd->save();
        $relations = ["AdDetails", "AdFiles", "NearbyFacilities", "AdToAllowedPets", "AdToPropertyFeatures", "AdToAmenities", "AdToGuarantees", "AdToPropertyRules", "AdVisitingDetails", "AdProximity"];
        foreach ($relations as $key => $relation_name) {
            $relation = "App\Http\Models\Ads\\" . $relation_name;
            $rel = $relation::where("ad_id", $ad->id)->first();
            if (!is_null($rel)) {
                $newRel = $rel->replicate();
                $newRel->ad_id = $newAd->id;
                $newRel->save();
            }
        }
        $request->session()->flash('status', __('backend_messages.duplicate_ad'));
        return redirect()->route("user.dashboard");
    }

    public function getUserAds(Request $request)
    {
        $user_id = Auth::id();
        $ads = DB::table('ads')->where("user_id", $user_id)
            ->where("status", "1")
            ->whereNull('deleted_at')
            ->orderBy("created_at", "desc")
            ->get();
        return response()->json($ads);
    }

    public function viewAd(Request $request, $scenario, $ville = null)
    {
        if ($request->input('inviteLocataire')) {
            if ($request->session()->get('inviteLocataire')) {
                $request->session()->put('inviteLocataire', $request->input('inviteLocataire'));
                $request->session()->put('inviteLocataireUrl', $request->url());
            }
        }

        if ($request->type && $request->type == "mail" && Auth::check()) {
            DB::table("toctoc_mail_click")->insert(["user_id" => Auth::id()]);
        }

        $isVille = DB::table('france_city')->where('slug', $ville)->first();
        $isVille2 = DB::table('villes_france_free')->where('ville_slug', $ville)->first();
        //verification si l'annonce est concu specialement pour google
        if (!is_null($isVille) && !is_null($isVille2)) {
            return $this->showVilleTemplate($scenario, $ville, $request);
        }

        if (!is_null($ville)) {
            $splitVille = explode("~", $ville);
        } else {
            $splitVille = explode("~", $scenario);
        }
        $searched_id = "";
        $length = count($splitVille);
        if ($length > 1 && is_numeric($splitVille[$length - 1]) && is_numeric($splitVille[$length - 2])) {
            $id = $splitVille[$length - 2];
            $searched_id = $splitVille[$length - 1];
        } else if ($length > 0 && is_numeric($splitVille[$length - 1])) {
            $id = $splitVille[$length - 1];
            $dep = DB::table("departments")->where("code", $id)->first();
            if (!is_null($dep)) {
                unset($splitVille[$length - 1]);
                $ville = implode("-", $splitVille);
                return $this->showVilleTemplate($scenario, $ville, $request);
            }
        } else {
            if (!is_null($ville)) {
                return $this->showVilleTemplate($scenario, $ville, $request);
            }
            abort(404);
        }
        //dd("id:".$id,"seach:".$searched_id);
        $user_id = Auth::id();
        $desactive = false;
        if (isset($request->utm_source) && $request->utm_source == "Facebook") {
            $ad = DB::table("ads")->where("id", $id)->first();
            if (Auth::check()) {
                $this->matchAd($id);
            } else {
                $request->session()->put("ad_id_redirection", $id);
                $request->return_url = adUrl($id);
                $request->action = null;
                $this->saveReturnInfos($request);
                $desactive = true;
            }
        }

        $signal_tags = SignalTag::all();
        $searched_ad = Ads::find($searched_id);
        $scenario = ["Rent a property", "Share an accomodation", "Seek to rent a property", "Seek to share an accomodation", "Seek someone to search together a property"];

        if (isset($id)) {

            countViewAd($id);
            if (isset($request->contact) && ($request->contact == true)) {
                countTrakingAds('message', $id);
            }

            $ad = Ads::has('user')
                ->with(['user.user_profiles', 'user.user_packages' => function ($query) {
                    $query->orderBy('id', 'desc')->first();
                }])
                ->with('type_location')
                ->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')->orderBy('ordre', 'asc');
                }, 'ad_details.property_type', 'nearby_facilities', 'ad_to_allowed_pets.allowed_pets', 'ad_to_property_features.property_features', 'ad_to_amenities.amenities', 'ad_to_guarantees.guarantees', 'ad_to_property_rules.property_rules', 'ad_visiting_details', 'ad_uploaded_guarantees.guarantees'])
                ->where('id', $id)
                ->first();
            if($ad){
                if (is_null($ad->ad_details)) {
                    DB::table('ad_details')->insert([
                        'ad_id' => $ad->id,
                        'property_type_id' => 2,
                        'furnished' => '0',
                        'budget' => $ad->budget
                    ]);
                    $ad = Ads::has('user')
                        ->with(['user.user_profiles', 'user.user_packages' => function ($query) {
                            $query->orderBy('id', 'desc')->first();
                        }])
                        ->with('type_location')
                        ->with(['ad_files' => function ($query) {
                            $query->where('media_type', '0')->orderBy('ordre', 'asc');
                        }, 'ad_details.property_type', 'nearby_facilities', 'ad_to_allowed_pets.allowed_pets', 'ad_to_property_features.property_features', 'ad_to_amenities.amenities', 'ad_to_guarantees.guarantees', 'ad_to_property_rules.property_rules', 'ad_visiting_details', 'ad_uploaded_guarantees.guarantees'])
                        ->where('id', $id)
                        ->first();
                }
            }else{
                $request->session()->flash('error', __('backend_messages.no_ad_found'));
                return redirect()->route('user.dashboard');
            }


            if (!empty($ad)) {
                $suggestion_ads = Ads::has('user')->with(['user.user_profiles'])->where('address', $ad->address)->where('scenario_id', $ad->scenario_id)->where('id', '!=', $ad->id)->where("admin_approve", '1')->orderBy('id', 'desc')->limit(6)->get();

                countTrakingAds('clic', $ad->id);

                $floute = true;
                if (isUserSubscribed() || $user_id == $ad->user_id) {
                    $floute = false;
                }
                if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                    if (isUserSubscribed()) {
                        if (Auth::user()->type_design == 1) {
                            $view_name = 'addetails/view-first-second-scen';
                        } else {
                            $view_name = 'addetails/basic/view-first-second-scen';
                        }
                    } else {
                        $view_name = 'addetails/basic/view-first-second-scen';
                    }
                } else {
                    if (isUserSubscribed()) {
                        if (Auth::user()->type_design == 1) {
                            // a revoire 
                            $view_name = 'addetails/view-third-fourth-scen';
                        } else {
                            $view_name = 'addetails/basic/view-third-fourth-scen';
                        }
                    } else {
                        $view_name = 'addetails/basic/view-third-fourth-scen';
                    }
                }

                if ($ad->admin_approve == 1 || $ad->user_id == $user_id || $request->utm_source == "Facebook") {
                    if ($ad->user_id != $user_id) {
                        $request->session()->put("lastViewAd", $ad->id);
                    }

                    $ad->messageFlash = false;
                    $messageFlash = DB::table("coup_de_foudres")->where("ad_id", $ad->id)->where("sender_id", $user_id)->first();
                    if (!is_null($messageFlash)) {
                        $ad->messageFlash = true;
                    }

                    $page_title = $ad->title;
                    $page_meta_keyword = $ad->title;
                    $page_description = mb_substr($ad->description, 0, 159);
                    $ad->scenario_type = $scenario[$ad->scenario_id - 1];
                    $favourites = [];
                    $friend_ads_arr = [];

                    if (!empty($ad->user->user_packages) && count($ad->user->user_packages) > 0) {
                        if (strtotime(date('Y-m-d')) > strtotime($ad->user->user_packages[0]->end_date)) {
                            $ad_premium = 'no';
                        } else {
                            $ad_premium = 'yes';
                        }
                    } else {
                        $ad_premium = 'no';
                    }
                    $user_equal_ad = false;
                    $user_provider_equal_facebook = false;
                    if (!empty(Auth::user())) {
                        $user_equal_ad = Auth::user()->id != $ad->user->id;
                        $user_provider_equal_facebook = Auth::user()->provider == 'facebook';
                    }
                    if ($user_provider_equal_facebook && $ad->user->provider == "facebook" && $user_equal_ad) {
                        //Amis en commun facebook
                        $creatorProvId = $ad->user->provider_id;
                        $friendsFbTemp = DB::table('fb_friend_lists')->where('user_id', Auth::id())->get();
                        $friendsFb = [];
                        foreach ($friendsFbTemp as $key => $friend) {
                            $friend_user = DB::table('users')->where('provider_id', $friend->fb_friend_id)->first();
                            if (!is_null($friend_user)) {
                                $testFriend = DB::table("fb_friend_lists")->where("user_id", $friend_user->id)->where("fb_friend_id", $creatorProvId)->first();
                                if (!is_null($testFriend)) {
                                    $fbAPI = new FacebookAPI($friend->fb_friend_id);
                                    $friend->pdp = $fbAPI->profilePicture()->data->url;
                                    $friendsFb[] = $friend;
                                }
                            }
                        }
                    }
                    $userProfiles = DB::table('user_profiles')
                        ->leftJoin('cities', 'user_profiles.city_id', 'cities.id')
                        ->leftJoin('countries', 'user_profiles.country_id', 'countries.id')
                        ->where('user_profiles.user_id', $ad->user->id)
                        ->first();

                    $lifeStyles = DB::table('user_to_lifestyles')
                        ->join('user_lifestyles', 'user_to_lifestyles.lifestyle_id', 'user_lifestyles.id')
                        ->where('user_to_lifestyles.user_id', $ad->user->id)
                        ->get();

                    $socialInfo = DB::table('user_to_social_interests')
                        ->join('social_interests', 'social_interests.id', 'user_to_social_interests.social_interest_id')
                        ->where('user_to_social_interests.user_id', $ad->user->id)
                        ->get();
                    $typeMusics = DB::table('user_to_music')
                        ->join('user_type_music', 'user_type_music.id', 'user_to_music.music_id')
                        ->where('user_to_music.user_id', $ad->user->id)
                        ->get();

                    $ad_proximities = AdProximity::with('proximity')->where('ad_id', $ad->id)->get();

                    if (!empty($user_id)) {
                        $layout = 'inner';

                        $userPackage = Auth::user()->user_packages()->select('end_date')->orderBy('id', 'desc')->first();
                        if (!empty($userPackage)) {
                            if (strtotime(date('Y-m-d')) > strtotime($userPackage->end_date)) {
                                $user_premium = 'no';
                            } else {
                                $user_premium = 'yes';
                            }
                        } else {
                            $user_premium = 'no';
                        }

                        if (count(Auth::user()->favorites) > 0) {
                            foreach (Auth::user()->favorites as $favourite) {
                                array_push($favourites, $favourite->ad_id);
                            }
                        }

                        if ($user_id == $ad->user->id) {

                            $fb_friends = User::with('user_friend_list')->where('id', $user_id)->first();
                            $fb_friends_arr = [];
                            if (!empty($fb_friends) && !empty($fb_friends->user_friend_list)) {
                                foreach ($fb_friends->user_friend_list as $fb_friend) {
                                    $fb_friends_arr[] = $fb_friend->fb_friend_id;
                                }
                            }

                            if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                                $friends_scnario = [3, 4];
                            } else if ($ad->scenario_id == 3 || $ad->scenario_id == 4) {
                                $friends_scnario = [1, 2];
                            } else {
                                $friends_scnario = [5];
                            }

                            if (!empty($fb_friends_arr) && count($fb_friends_arr) > 0) {
                                $radius_query_string = '6371 * acos(cos(radians(' . $ad->latitude . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $ad->longitude . ')) + sin(radians(' . $ad->latitude . ')) * sin(radians(latitude))) ';

                                $friend_ads = User::with(['user_profiles', 'ads' => function ($query) use ($friends_scnario, $radius_query_string) {
                                    $query->whereRaw($radius_query_string . '<= 40')->whereIn('scenario_id', $friends_scnario);
                                }])->whereIn('provider_id', $fb_friends_arr)->get();
                                if (!empty($friend_ads) && count($friend_ads) > 0) {
                                    foreach ($friend_ads as $key => $friend_ad) {
                                        if (!empty($friend_ad->ads) && count($friend_ad->ads) > 0) {
                                            $friend_ads_arr[$key]['user_profile'] = [
                                                'first_name' => $friend_ad->first_name,
                                                'last_name' => $friend_ad->last_name,
                                                'profile_pic' => $friend_ad->user_profiles->profile_pic,
                                            ];
                                            foreach ($friend_ad->ads as $friend_add) {
                                                $friend_ads_arr[$key]['ads'][] = [
                                                    'ad_id' => $friend_add->id,
                                                    'ad_title' => $friend_add->title,
                                                    'ad_location' => $friend_add->address,
                                                    'ad_url_slug' => $friend_add->url_slug,
                                                ];
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $documents = new AdDocuments();
                        $ad_documents = $documents->where('ad_id', $ad->id)->get();
                        //user saved documents
                        $savedDocuments = null;
                        if (!empty($ad_documents)) {
                            $savedDocuments = DB::table('documents')
                                ->where('user_id', Auth::user()->id)
                                ->get();

                            if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                                if (isset($friendsFb)) {
                                    return view($view_name, compact('layout', 'savedDocuments', 'ad', 'ad_premium', 'user_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'friendsFb', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                } else {
                                    return view($view_name, compact('layout', 'ad', 'savedDocuments', 'ad_premium', 'user_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                }
                            } else if ($ad->scenario_id == 3 || $ad->scenario_id == 4 || $ad->scenario_id == 5) {
                                if (isset($friendsFb)) {
                                    return view($view_name, compact('friendsFb', 'savedDocuments', 'layout', 'ad', 'ad_premium', 'user_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                } else {
                                    $locataire = backup_locataire::select('id')->where('user_account_id', $ad->user_id)->first();
                                    if ($locataire) {
                                        $ratings = Ratings::select('note', DB::raw('COUNT(*) as count'))
                                                        ->where('user_id_locataire', $ad->user_id)
                                                        ->where('note', '<>', 0)
                                                        ->groupBy('note')
                                                        ->get();
                                        $ratings_comments = Ratings::where('user_id_locataire', $ad->user_id)->get();
                                        $total = 0;
                                        $average = 0;
                                        foreach ($ratings as $rating) {
                                            $total = $total + $rating->count;
                                        }
                                        foreach ($ratings as $rating) {
                                            $average = $average + ($rating->note * $rating->count);
                                            $count_per_rate[$rating->note] = number_format(($rating->count / $total) * 100, 2);
                                        }
                                        
                                        $clesAttendues = array(1, 2, 3, 4, 5);

                                        foreach ($clesAttendues as $cle) {
                                            if (!isset($count_per_rate[$cle])) {
                                                $count_per_rate[$cle] = 0;
                                            }
                                        }
                                        krsort($count_per_rate);
                                        return view($view_name, compact('layout', 'ad', 'savedDocuments', 'ad_premium', 'user_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads', 'count_per_rate', 'total', 'average', 'ratings_comments', 'ratings'));
                                    }
                                    return view($view_name, compact('layout', 'ad', 'savedDocuments', 'ad_premium', 'user_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                }
                            }
                        } else {
                            $documents = new AdDocuments();
                            $ad_documents = $documents->where('ad_id', $ad->id)->get();
                            //user saved documents
                            $savedDocuments = null;
                            $layout = 'outer';
                            if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                                if (isset($friendsFb)) {
                                    return view($view_name, compact('friendsFb', 'savedDocuments', 'layout', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                } else {
                                    return view($view_name, compact('layout', 'savedDocuments', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                }
                            } else if ($ad->scenario_id == 3 || $ad->scenario_id == 4 || $ad->scenario_id == 5) {
                                if (isset($friendsFb)) {
                                    return view($view_name, compact('friendsFb', 'savedDocuments', 'layout', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                } else {
                                    return view($view_name, compact('layout', 'savedDocuments', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                }
                            }
                        }
                    } else {

                        if (!isset($user_premium)) {
                            $user_premium = 'no';
                        }
                        if (!isset($ad_premium)) {
                            $ad_premium = 'no';
                        }

                        $documents = new AdDocuments();
                        $ad_documents = $documents->where('ad_id', $ad->id)->get();

                        $layout = 'outer';
                        if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                            return view($view_name, compact('layout', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                        } else if ($ad->scenario_id == 3 || $ad->scenario_id == 4 || $ad->scenario_id == 5) {
                            return view($view_name, compact('layout', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                        }
                    }
                } else {
                    $request->session()->flash('error', __('backend_messages.ad_desactive'));
                    return redirect()->route('user.dashboard');
                }
            } else {
                $request->session()->flash('error', __('backend_messages.no_ad_found'));
                return redirect()->route('user.dashboard');
            }
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }
    }

    public function addRemoveFavorites(Request $request)
    {

        if ($request->ajax()) {

            $response = array();

            $id = base64_decode($request->ad_id);
            $ad_search_id = base64_decode($request->ad_search_id);

            $user_id = Auth::id();

            $favorite = new Favourites;

            $check_exist = Favourites::has('ads.user')
                ->whereHas('ads', function ($query) {
                    $query->where('status', '1')->where('admin_approve', '1');
                })
                ->where('ad_id', $id)->where('user_id', $user_id)->first();

            if (!empty($check_exist)) {

                Favourites::destroy($check_exist->id);
                $response['message'] = 'Removed from favourites';
                $response['action'] = 'Removed';
            } else {

                $favorite->user_id = $user_id;
                $favorite->ad_id = $id;
                $favorite->ad_search_id = $ad_search_id;

                $favorite->save();
                $response['message'] = 'Added to favourites';
                $response['action'] = 'Added';
            }

            $response['count'] = count(Auth::user()->favorites()->has('ads.user')
                ->whereHas('ads', function ($query) {
                    $query->where('status', '1')->where('admin_approve', '1');
                })->get());

            return response()->json($response);
        }
    }

    public function loadRequestToVisit($type, $id, $sender_ad_id, Request $request)
    {

        if ($request->ajax()) {

            $id = $id;
            $sender_ad_id = $sender_ad_id;

            $user_id = Auth::id();

            if ($type == 'offer') {

                $visiting_details = AdVisitingDetails::where('ad_id', $id)->get();
            } else if ($type == 'demand') {

                $visiting_details = AdVisitingDetails::where('ad_id', $sender_ad_id)->get();
            }

            return view('addetails/request_to_visit', compact('visiting_details', 'id', 'sender_ad_id'));
        }
    }

    public function saveRequestToVisit(Request $request)
    {

        if ($request->ajax()) {

            $validator = Validator::make(
                $request->all(),
                [],
                [
                    'check_slot.required' => __('addetails.select_slot'),
                    'end_time.after' => __('addetails.end_time_error'),
                ]
            );

            $validator->sometimes(
                'check_slot',
                'required',
                function ($input) {
                    return empty($input->visit_detail_id);
                }
            );

            $validator->sometimes(
                'date_of_visit',
                'required|date_format:d/m/Y',
                function ($input) {
                    return !empty($input->check_slot);
                }
            );

            $validator->sometimes(
                'start_time',
                'required',
                function ($input) {
                    return !empty($input->check_slot);
                }
            );
            $validator->sometimes(
                'end_time',
                'after:' . $request->start_time,
                function ($input) {
                    return !empty($input->check_slot) && !empty($input->end_time) && !empty($input->start_time);
                }
            );

            $validator->sometimes(
                'note',
                '',
                function ($input) {
                    return !empty($input->check_slot) && !empty($input->note);
                }
            );

            $response = array();

            if ($validator->passes()) {

                $response['error'] = 'no';

                $user_id = Auth::id();

                $visit_requests = new VisitRequests;

                $visit_requests->ad_id = $request->ad_id;
                $visit_requests->sender_ad_id = $request->sender_ad_id;
                $visit_requests->requester_id = $user_id;
                $ad_id = $request->ad_id;

                if (empty($request->check_slot)) {

                    $visit_requests->slot_id = $request->visit_detail_id;
                    $visit_requests->save();
                } else {

                    $visit_requests->visiting_date = date("Y-m-d", strtotime(convertDateWithTiret($request->date_of_visit)));
                    $visit_requests->start_time = date('H:i:s', strtotime($request->start_time));
                    if (!empty($request->end_time)) {
                        $visit_requests->end_time = date('H:i:s', strtotime($request->end_time));
                    }
                    if (!empty($request->note)) {
                        $visit_requests->notes = $request->note;
                    }
                    $visit_requests->save();
                }

                $userInfo = DB::table('users')->join("ads", "ads.user_id", "users.id")->where('ads.id', $ad_id)->first();
                $userInfo->ad_id = $ad_id;

                if (!is_null($userInfo->email)) {
                    $subject = i18n('mail.visit_object', getLangUser($userInfo->id));
                    try {
                        sendMail($userInfo->email, 'emails.users.visit_request', [
                            "subject" => $subject,
                            "UserInfo" => $userInfo,
                            "lang" => getLangUser($userInfo->id)
                        ]);
                    } catch (Exception $ex) {

                    }
                }
            } else {

                $response['error'] = 'yes';
                $response['errors'] = $validator->getMessageBag()->toArray();
            }

            return response()->json($response);
        }
    }

    public function saveMessage(Request $request)
    {

        if ($request->ajax()) {

            $adId = base64_decode($request->ad_id);
            //countTrakingAds('message', $adId);
            $senderAdId = base64_decode($request->sender_ad_id);
            if (empty($senderAdId)) {
                $senderAdId = $adId;
            }
            $senderId = Auth::id();

            $validator = Validator::make($request->all(), ['message' => 'required']);

            $response = array();

            if ($validator->passes()) {

                $sender_ad = Ads::where('id', $senderAdId)->first();
                $ad = Ads::find($adId);

                if (!empty($ad) && !empty($sender_ad)) {

                    $response['error'] = 'no';
                    if (isset($request->receiver_id)) {
                        $receiverId = base64_decode($request->receiver_id);
                    } else {
                        $receiverId = $ad->user_id;
                    }

                    $prenom = null;
                    if (isset($request->prenom)) {
                        $prenom = $request->prenom;
                    }

                    $arrData = Messages::where(function ($query) use ($senderId, $receiverId) {

                        $query->where(function ($query) use ($senderId, $receiverId) {
                            $query->where('sender_id', $senderId)
                                ->where('receiver_id', $receiverId);
                        });
                        $query->orWhere(function ($query) use ($senderId, $receiverId) {
                            $query->where('sender_id', $receiverId)
                                ->where('receiver_id', $senderId);
                        });
                    })
                        ->where(function ($query) use ($adId, $senderAdId) {
                            $query->where(function ($query) use ($adId, $senderAdId) {
                                $query->where('ad_id', $adId)
                                    ->where('sender_ad_id', $senderAdId);
                            });
                            $query->orWhere(function ($query) use ($adId, $senderAdId) {
                                $query->where('ad_id', $senderAdId)
                                    ->where('sender_ad_id', $adId);
                            });
                        })
                        ->where(function ($query) use ($prenom) {
                            if (!is_null($prenom)) {
                                $query->where(function ($query) use ($prenom) {
                                    $query->where('temp_user', $prenom);
                                });
                            }
                        })
                        ->orderBy('id', 'DESC');
                    $arrData = $arrData->first();
                    if (!empty($arrData)) {
                        $threadId = $arrData->thread_id;
                    } else {
                        $threadId = uniqid() . rand(999, 99999);
                    }

                    $message = new Messages;

                    $message->thread_id = $threadId;
                    $message->ad_id = $adId;
                    $message->sender_ad_id = $senderAdId;
                    $message->sender_id = $senderId;
                    $message->receiver_id = $receiverId;
                    $message->message = trim($request->message);
                    if (isset($request->prenom)) {
                        $message->temp_user = $request->prenom;
                    }
                    $message->save();

                    //send Email
                    $messageInfo = DB::table('messages')->select('users.first_name', 'users.last_name', 'users.email')->join('users', 'users.id', 'messages.receiver_id')->where('messages.id', $message->id)->first();
                    if (isset($messageInfo->email) && !is_null($messageInfo->email)) {
                        $this->sendMailNewMessage($messageInfo, $ad->user_id);
                    }

                    $response['message_info'] = ['id' => $message->id, 'message' => trim($request->message), 'created_date' => date('d/m/Y', strtotime($message->created_at)), 'created_time' => date("h:i A", strtotime(getUserDateByTimezone($message->created_at)))];
                }
            } else {
                $response['error'] = 'yes';
                $response['errors'] = $validator->getMessageBag()->toArray();
            }

            return response()->json($response);
        }
    }

    private function sendMailNewMessage($messageInfo, $user_id)
    {
        $subject = i18n('new_message_subject', getLangUser($user_id));

        try {

            sendMail($messageInfo->email, 'emails.users.newMessage', [
                "subject" => $subject,
                "userMessageInfo" => $messageInfo,
                "lang" => getLangUser($user_id)
            ]);
        } catch (Exception $ex) {
        }
        return true;
    }

    public function nbMessageUser(Request $request)
    {
        if (Auth::check()) {
            if (isUserSubscribed()) {
                return 0;
            }

            $userId = Auth::id();
            $nb = DB::table("messages")->where("sender_id", $userId)->count();
            return response()->json(array("nbMessages" => $nb));
        }
    }

    public function viewMessageRooms(Request $request, $id = null)
    {
        $userId = Auth::id();
        $activeThreadMessages = [];
        if ($request->ajax()) {

            $adId = $request->ad_id;
            $senderAdId = $request->sender_ad_id;

            $activeThreadMessages = Messages::where("thread_id", $request->thread_id)
                ->where(function ($query) use ($userId) {
                    $query->where(function ($query) use ($userId) {
                        $query->where('sender_id', $userId)
                            ->where('sender_trash', "0");
                    })->orWhere(function ($query) use ($userId) {
                        $query->where('receiver_id', $userId)
                            ->where('receiver_trash', "0");
                    });
                })
                ->orderBy('id', 'ASC')
                ->get();

            if (!empty($activeThreadMessages) && count($activeThreadMessages) > 0) {

                $updateAsRead = Messages::where('thread_id', $request->thread_id)
                    ->where('receiver_id', $userId)
                    ->where('receiver_trash', "0")
                    ->where('read', '0')
                    ->update(['read' => '1', 'read_date' => date("Y-m-d H:i:s")]);

                Messages::where('receiver_id', $userId)
                    ->where('notif_checked', "0")
                    ->where('receiver_trash', "0")
                    ->update(["notif_checked" => "1"]);
            }

            $active_thread_id = $request->thread_id;

            $otherUserInfo = User::with('user_profiles')
                ->where('id', $request->user_id)
                ->first();

            return view('messages/message-room-data', compact('activeThreadMessages', 'otherUserInfo', 'adId', 'senderAdId', 'active_thread_id'));
        }

        if (!empty($id)) {
            $splitUrl = explode("~", $id);
            $id = $splitUrl[count($splitUrl) - 1];
        }

        //\DB::enableQueryLog();

        $arrMAXIds = [];
        $arrIdsSql = Messages::has('sender')->has('receiver')
            ->where(function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->where('sender_trash', "0");
                })->orWhere(function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->where('receiver_trash', "0");
                });
            })
            ->groupBy('thread_id')
            ->select(DB::raw('MAX(id) as max_id'))
            ->get();

        if (!empty($arrIdsSql)) {
            foreach ($arrIdsSql as $arrId) {
                $arrMAXIds[] = $arrId->max_id;
            }
        }

        $sideBarMessages = Messages::where(function ($query) use ($userId) {
            $query->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->where('sender_trash', "0");
            })->orWhere(function ($query) use ($userId) {
                $query->where('receiver_id', $userId)
                    ->where('receiver_trash', "0");
            });
        })
            ->whereIn('id', $arrMAXIds)
            ->groupBy('thread_id')
            ->orderBy('id', 'DESC')
            ->get();

        $sideBarArray = array();

        if (!empty($sideBarMessages) && count($sideBarMessages) > 0) {
            foreach ($sideBarMessages as $key => $sideBarMessage) {

                if ($sideBarMessage->receiver_id == $userId) {
                    if (array_key_exists($sideBarMessage->ad_id, $sideBarArray)) {
                        $sideBarArray[$sideBarMessage->ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->sender_id)->first()];
                    } else {
                        $sideBarArray[$sideBarMessage->ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->sender_id)->first()];
                        $sideBarArray[$sideBarMessage->ad_id]['ad_info'] = Ads::find($sideBarMessage->ad_id);
                    }
                } else {
                    if (array_key_exists($sideBarMessage->sender_ad_id, $sideBarArray)) {
                        $sideBarArray[$sideBarMessage->sender_ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->receiver_id)->first()];
                    } else {
                        $sideBarArray[$sideBarMessage->sender_ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->receiver_id)->first()];
                        $sideBarArray[$sideBarMessage->sender_ad_id]['ad_info'] = Ads::find($sideBarMessage->sender_ad_id);
                    }
                }
            }
        }

        $active_thread_id = '';
        $otherUSerId = '';
        $adId = '';
        $senderAdId = '';

        if (!empty($sideBarArray)) {
            $i = 0;
            $active_set = 'no';
            foreach ($sideBarArray as $key1 => $sideBar) {
                if ($i == 0) {
                    $first_key = $key1;
                }
                foreach ($sideBar['message_info'] as $key2 => $messageInfo) {
                    if (isset($request->type) && $request->type == "sent") {

                        if ($messageInfo[0]->receiver_id == Auth::id()) {
                            unset($sideBarArray[$key1]['message_info'][$key2]);
                            if (count($sideBarArray[$key1]['message_info']) == 0) {
                                unset($sideBarArray[$key1]);
                            }
                            continue;
                        }
                    } else if (isset($request->type) && $request->type == "unread") {
                        if ($messageInfo[0]->sender_id == Auth::id() || $messageInfo[0]->read != "0") {
                            unset($sideBarArray[$key1]['message_info'][$key2]);
                            if (count($sideBarArray[$key1]['message_info']) == 0) {
                                unset($sideBarArray[$key1]);
                            }
                            continue;
                        }
                    }
                    if (!empty($id) && $id == $key1 && $key2 == 0) {
                        $sideBarArray[$key1]['message_info'][$key2][2] = 'yes';
                        $active_set = 'yes';
                        $active_thread_id = $messageInfo[$key2]->thread_id;

                        if ($messageInfo[$key2]->receiver_id == $userId) {
                            $otherUSerId = $messageInfo[$key2]->sender_id;
                            $adId = base64_encode($messageInfo[$key2]->sender_ad_id);
                            $senderAdId = base64_encode($messageInfo[$key2]->ad_id);
                        } else {
                            $otherUSerId = $messageInfo[$key2]->receiver_id;
                            $adId = base64_encode($messageInfo[$key2]->ad_id);
                            $senderAdId = base64_encode($messageInfo[$key2]->sender_ad_id);
                        }
                    } else {
                        $sideBarArray[$key1]['message_info'][$key2][2] = 'no';
                    }
                }
                $i++;
            }

            if (!empty($sideBarArray)) {
                reset($sideBarArray);
                $first_key = key($sideBarArray);
                if ($active_set == 'no') {
                    reset($sideBarArray[$first_key]['message_info']);
                    $first_key_info = key($sideBarArray[$first_key]['message_info']);
                    $sideBarArray[$first_key]['message_info'][$first_key_info][2] = 'yes';

                    $active_thread_id = $sideBarArray[$first_key]['message_info'][$first_key_info][0]->thread_id;

                    if ($sideBarArray[$first_key]['message_info'][$first_key_info][0]->receiver_id == $userId) {
                        $otherUSerId = $sideBarArray[$first_key]['message_info'][$first_key_info][0]->sender_id;
                        $adId = base64_encode($sideBarArray[$first_key]['message_info'][$first_key_info][0]->sender_ad_id);
                        $senderAdId = base64_encode($sideBarArray[$first_key]['message_info'][$first_key_info][0]->ad_id);
                    } else {
                        $otherUSerId = $sideBarArray[$first_key]['message_info'][$first_key_info][0]->receiver_id;
                        $adId = base64_encode($sideBarArray[$first_key]['message_info'][$first_key_info][0]->ad_id);
                        $senderAdId = base64_encode($sideBarArray[$first_key]['message_info'][$first_key_info][0]->sender_ad_id);
                    }
                }
            }
        }

        if (!empty($sideBarArray)) {
            $activeThreadMessages = Messages::where("thread_id", $active_thread_id)
                ->where(function ($query) use ($userId) {
                    $query->where(function ($query) use ($userId) {
                        $query->where('sender_id', $userId)
                            ->where('sender_trash', "0");
                    })->orWhere(function ($query) use ($userId) {
                        $query->where('receiver_id', $userId)
                            ->where('receiver_trash', "0");
                    });
                })
                ->orderBy('id', 'ASC')
                ->get();
        }
        $prenom = null;
        if (!empty($activeThreadMessages) && count($activeThreadMessages) > 0) {

            $updateAsRead = Messages::where('thread_id', $active_thread_id)
                ->where('receiver_id', $userId)
                ->where('receiver_trash', "0")
                ->where('read', '0')
                ->update(['read' => '1']);
            Messages::where('receiver_id', $userId)
                ->where('notif_checked', "0")
                ->where('receiver_trash', "0")
                ->update(["notif_checked" => "1"]);

            foreach ($activeThreadMessages as $key => $activeMessage) {
                $prenom = $activeMessage->temp_user;
                if (is_null($activeMessage->read_date) && $activeMessage->receiver_id == Auth::id()) {
                    DB::table('messages')->where("id", $activeMessage->id)->update(
                        ["read_date" => date("Y-m-d H:i:s")]
                    );
                }
            }
            $activeThreadMessages = Messages::where("thread_id", $active_thread_id)
                ->where(function ($query) use ($userId) {
                    $query->where(function ($query) use ($userId) {
                        $query->where('sender_id', $userId)
                            ->where('sender_trash', "0");
                    })->orWhere(function ($query) use ($userId) {
                        $query->where('receiver_id', $userId)
                            ->where('receiver_trash', "0");
                    });
                })
                ->orderBy('id', 'ASC')
                ->get();
        }

        $otherUserInfo = User::with('user_profiles')
            ->where('id', $otherUSerId)
            ->first();
        if (!is_null($otherUserInfo) && !is_null($prenom)) {
            $otherUserInfo->temp_user = $prenom;
        }

        return view('messages/messageroom_full', compact('sideBarArray', 'activeThreadMessages', 'otherUserInfo', 'adId', 'senderAdId', 'active_thread_id'));
    }

    public function newMessagesSideBar(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            if (isset($request->max_id)) {
                $maxId = $request->max_id;
            } else {
                $maxId = 0;
            }

            $active_thread = $request->active_thread;

            $arrMAXIds = [];
            $arrIdsSql = Messages::has('sender')->has('receiver')
                ->where(function ($query) use ($userId) {
                    $query->where(function ($query) use ($userId) {
                        $query->where('sender_id', $userId)
                            ->where('sender_trash', "0");
                    })->orWhere(function ($query) use ($userId) {
                        $query->where('receiver_id', $userId)
                            ->where('receiver_trash', "0");
                    });
                })
                ->groupBy('thread_id')
                ->select(DB::raw('MAX(id) as max_id'))
                ->get();

            if (!empty($arrIdsSql)) {
                foreach ($arrIdsSql as $arrId) {
                    $arrMAXIds[] = $arrId->max_id;
                }
            }

            $sideBarMessages = Messages::where(function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->where('sender_trash', "0");
                })->orWhere(function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->where('receiver_trash', "0");
                });
            })
                ->whereIn('id', $arrMAXIds)
                ->groupBy('thread_id')
                ->orderBy('id', 'DESC')
                ->get();

            $sideBarArray = array();
            $sidebarAd = [];

            if (!empty($sideBarMessages) && count($sideBarMessages) > 0) {
                foreach ($sideBarMessages as $key => $sideBarMessage) {
                    if ($sideBarMessage->receiver_id == $userId) {
                        $arrIncludeAdId = Messages::where(function ($query) use ($userId) {
                            $query->where(function ($query) use ($userId) {
                                $query->where('sender_id', $userId)
                                    ->where('sender_trash', "0");
                            })->orWhere(function ($query) use ($userId) {
                                $query->where('receiver_id', $userId)
                                    ->where('receiver_trash', "0");
                            });
                        })
                            ->where("id", ">", $maxId)
                            ->select('ad_id')
                            ->groupBy('ad_id')
                            ->orderBy('id', 'DESC')
                            ->get()->toArray();
                        $arrIncludeAdId = arrayBDDtoSimpleArray("ad_id", $arrIncludeAdId);
                        if (in_array($sideBarMessage->ad_id, $arrIncludeAdId)) {
                            $sidebarAd[] = $sideBarMessage->ad_id;
                            if (array_key_exists($sideBarMessage->ad_id, $sideBarArray)) {
                                $sideBarArray[$sideBarMessage->ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->sender_id)->first()];
                            } else {
                                $sideBarArray[$sideBarMessage->ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->sender_id)->first()];
                                $sideBarArray[$sideBarMessage->ad_id]['ad_info'] = Ads::find($sideBarMessage->ad_id);
                            }
                        }
                    } else {
                        $arrIncludeAdId = Messages::where(function ($query) use ($userId) {
                            $query->where(function ($query) use ($userId) {
                                $query->where('sender_id', $userId)
                                    ->where('sender_trash', "0");
                            })->orWhere(function ($query) use ($userId) {
                                $query->where('receiver_id', $userId)
                                    ->where('receiver_trash', "0");
                            });
                        })
                            ->where("id", ">", $maxId)
                            ->select('sender_ad_id')
                            ->groupBy('sender_ad_id')
                            ->orderBy('id', 'DESC')
                            ->get()->toArray();
                        $arrIncludeAdId = arrayBDDtoSimpleArray("sender_ad_id", $arrIncludeAdId);
                        if (in_array($sideBarMessage->sender_ad_id, $arrIncludeAdId)) {
                            $sidebarAd[] = $sideBarMessage->sender_ad_id;
                            if (array_key_exists($sideBarMessage->sender_ad_id, $sideBarArray)) {
                                $sideBarArray[$sideBarMessage->sender_ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->receiver_id)->first()];
                            } else {
                                $sideBarArray[$sideBarMessage->sender_ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->receiver_id)->first()];
                                $sideBarArray[$sideBarMessage->sender_ad_id]['ad_info'] = Ads::find($sideBarMessage->sender_ad_id);
                            }
                        }
                    }
                }
            }

            $active_thread_id = '';
            $otherUSerId = '';
            $adId = '';
            $senderAdId = '';
            $room_type = null;
            if (!is_null($request->room_type)) {
                $room_type = $request->room_type;
            }

            if (!empty($sideBarArray)) {
                $i = 0;
                $active_set = 'no';
                foreach ($sideBarArray as $key1 => $sideBar) {
                    if ($i == 0) {
                        $first_key = $key1;
                    }
                    foreach ($sideBar['message_info'] as $key2 => $messageInfo) {

                        if ($room_type == 'sent') {

                            if ($messageInfo[0]->receiver_id == Auth::id()) {
                                unset($sideBarArray[$key1]['message_info'][$key2]);
                                if (count($sideBarArray[$key1]['message_info']) == 0) {
                                    unset($sideBarArray[$key1]);
                                }
                                continue;
                            }
                        } else if ($room_type == "unread") {
                            if ($messageInfo[0]->sender_id == Auth::id() || $messageInfo[0]->read != "0") {
                                unset($sideBarArray[$key1]['message_info'][$key2]);
                                if (count($sideBarArray[$key1]['message_info']) == 0) {
                                    unset($sideBarArray[$key1]);
                                }
                                continue;
                            }
                        }
                        if (!empty($id) && $id == $key1 && $key2 == 0) {
                            $sideBarArray[$key1]['message_info'][$key2][2] = 'yes';
                            $active_set = 'yes';
                            $active_thread_id = $messageInfo[$key2]->thread_id;

                            if ($messageInfo[$key2]->receiver_id == $userId) {
                                $otherUSerId = $messageInfo[$key2]->sender_id;
                                $adId = base64_encode($messageInfo[$key2]->sender_ad_id);
                                $senderAdId = base64_encode($messageInfo[$key2]->ad_id);
                            } else {
                                $otherUSerId = $messageInfo[$key2]->receiver_id;
                                $adId = base64_encode($messageInfo[$key2]->ad_id);
                                $senderAdId = base64_encode($messageInfo[$key2]->sender_ad_id);
                            }
                        } else {
                            $sideBarArray[$key1]['message_info'][$key2][2] = 'no';
                        }
                    }
                    $i++;
                }

                if (!empty($sideBarArray)) {
                    reset($sideBarArray);
                    $first_key = key($sideBarArray);
                    if ($active_set == 'no') {
                        reset($sideBarArray[$first_key]['message_info']);
                        $first_key_info = key($sideBarArray[$first_key]['message_info']);
                        $sideBarArray[$first_key]['message_info'][$first_key_info][2] = 'yes';
                        $active_thread_id = $sideBarArray[$first_key]['message_info'][$first_key_info][0]->thread_id;

                        if ($sideBarArray[$first_key]['message_info'][$first_key_info][0]->receiver_id == $userId) {
                            $otherUSerId = $sideBarArray[$first_key]['message_info'][$first_key_info][0]->sender_id;
                            $adId = base64_encode($sideBarArray[$first_key]['message_info'][$first_key_info][0]->sender_ad_id);
                            $senderAdId = base64_encode($sideBarArray[$first_key]['message_info'][$first_key_info][0]->ad_id);
                        } else {
                            $otherUSerId = $sideBarArray[$first_key]['message_info'][$first_key_info][0]->receiver_id;
                            $adId = base64_encode($sideBarArray[$first_key]['message_info'][$first_key_info][0]->ad_id);
                            $senderAdId = base64_encode($sideBarArray[$first_key]['message_info'][$first_key_info][0]->sender_ad_id);
                        }
                    }
                }
            }
            $html = view('messages/sidebarMessages', compact('sideBarArray', 'active_thread', "room_type"))->render();
            return response()->json(["ad_ids" => array_unique($sidebarAd), "html" => $html]);
        }
    }

    public function viewMessageRoomsTrash(Request $request)
    {

        $userId = Auth::id();

        if ($request->ajax()) {

            $activeThreadMessages = Messages::where("thread_id", $request->thread_id)
                ->where(function ($query) use ($userId) {
                    $query->where(function ($query) use ($userId) {
                        $query->where('sender_id', $userId)
                            ->where('sender_trash', "1");
                    })->orWhere(function ($query) use ($userId) {
                        $query->where('receiver_id', $userId)
                            ->where('receiver_trash', "1");
                    });
                })
                ->orderBy('id', 'ASC')
                ->get();

            $active_thread_id = $request->thread_id;

            $otherUserInfo = User::with('user_profiles')
                ->where('id', $request->user_id)
                ->first();

            return view('messages/message-room-data-trash', compact('activeThreadMessages', 'otherUserInfo', 'active_thread_id'));
        }

        //\DB::enableQueryLog();

        $arrMAXIds = [];
        $arrIdsSql = Messages::has('sender')->has('receiver')
            ->where(function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->where('sender_trash', "1");
                })->orWhere(function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->where('receiver_trash', "1");
                });
            })
            ->groupBy('thread_id')
            ->select(DB::raw('MAX(id) as max_id'))
            ->get();

        if (!empty($arrIdsSql)) {
            foreach ($arrIdsSql as $arrId) {
                $arrMAXIds[] = $arrId->max_id;
            }
        }

        $sideBarMessages = Messages::where(function ($query) use ($userId) {
            $query->where(function ($query) use ($userId) {
                $query->where('sender_id', $userId)
                    ->where('sender_trash', "1");
            })->orWhere(function ($query) use ($userId) {
                $query->where('receiver_id', $userId)
                    ->where('receiver_trash', "1");
            });
        })
            ->whereIn('id', $arrMAXIds)
            ->groupBy('thread_id')
            ->orderBy('id', 'DESC')
            ->get();

        $sideBarArray = array();

        if (!empty($sideBarMessages) && count($sideBarMessages) > 0) {
            foreach ($sideBarMessages as $key => $sideBarMessage) {

                if ($sideBarMessage->receiver_id == $userId) {
                    if (array_key_exists($sideBarMessage->ad_id, $sideBarArray)) {
                        $sideBarArray[$sideBarMessage->ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->sender_id)->first()];
                    } else {
                        $sideBarArray[$sideBarMessage->ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->sender_id)->first()];
                        $sideBarArray[$sideBarMessage->ad_id]['ad_info'] = Ads::find($sideBarMessage->ad_id);
                    }
                } else {
                    if (array_key_exists($sideBarMessage->sender_ad_id, $sideBarArray)) {
                        $sideBarArray[$sideBarMessage->sender_ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->receiver_id)->first()];
                    } else {
                        $sideBarArray[$sideBarMessage->sender_ad_id]['message_info'][] = [$sideBarMessage, User::with('user_profiles')->where('id', $sideBarMessage->receiver_id)->first()];
                        $sideBarArray[$sideBarMessage->sender_ad_id]['ad_info'] = Ads::find($sideBarMessage->sender_ad_id);
                    }
                }
            }
        }

        $active_thread_id = '';
        $otherUSerId = '';

        if (!empty($sideBarArray)) {
            foreach ($sideBarArray as $key1 => $sideBar) {
                $first_key = $key1;
                break;
            }

            $sideBarArray[$first_key]['message_info'][0][2] = 'yes';
            $active_thread_id = $sideBarArray[$first_key]['message_info'][0][0]->thread_id;
            if ($sideBarArray[$first_key]['message_info'][0][0]->receiver_id == $userId) {
                $otherUSerId = $sideBarArray[$first_key]['message_info'][0][0]->sender_id;
            } else {
                $otherUSerId = $sideBarArray[$first_key]['message_info'][0][0]->receiver_id;
            }
        }

        $activeThreadMessages = Messages::where("thread_id", $active_thread_id)
            ->where(function ($query) use ($userId) {
                $query->where(function ($query) use ($userId) {
                    $query->where('sender_id', $userId)
                        ->where('sender_trash', "1");
                })->orWhere(function ($query) use ($userId) {
                    $query->where('receiver_id', $userId)
                        ->where('receiver_trash', "1");
                });
            })
            ->orderBy('id', 'ASC')
            ->get();

        $otherUserInfo = User::with('user_profiles')
            ->where('id', $otherUSerId)
            ->first();

        return view('messages/messageroom_full_trash', compact('sideBarArray', 'activeThreadMessages', 'otherUserInfo', 'active_thread_id'));
    }

    public function getMessageRoomUpdates(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {

                $thread_ids = $request->thread_ids;

                $response = array();

                if (!empty($thread_ids)) {
                    foreach ($thread_ids as $thread_id) {
                        $thread_unread_count = Messages::select(DB::raw('count(id) as count'))
                            ->where("thread_id", $thread_id)
                            ->where('read', '0')
                            ->where('receiver_id', $userId)
                            ->where('receiver_trash', "0")
                            ->first();

                        if (!empty($thread_unread_count) && $thread_unread_count->count > 0) {
                            $latest_message = Messages::where("thread_id", $thread_id)
                                ->where(function ($query) use ($userId) {
                                    $query->where(function ($query) use ($userId) {
                                        $query->where('sender_id', $userId)
                                            ->where('sender_trash', "0");
                                    })->orWhere(function ($query) use ($userId) {
                                        $query->where('receiver_id', $userId)
                                            ->where('receiver_trash', "0");
                                    });
                                })
                                ->orderBy('id', 'DESC')
                                ->first();
                            if ($latest_message->receiver_id == $userId) {
                                $response[$thread_id] = ['unread_count' => $thread_unread_count->count, 'message' => $latest_message->message];
                            } else {
                                $response[$thread_id] = ['unread_count' => $thread_unread_count->count];
                            }
                        }
                    }
                }

                return response()->json($response);
            }
        }
    }

    public function getMessageUpdates(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {

                $response = array();

                $message_unread_count = Messages::has('sender')->has('receiver')
                    ->select(DB::raw('count(id) as count'))
                    ->where('read', '0')
                    ->where('receiver_id', $userId)
                    ->where('receiver_trash', "0")
                    ->first();

                $response['unread_message_count'] = $message_unread_count->count;

                return response()->json($response);
            }
        }
    }

    public function getActiveThreadUpdates(Request $request)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            if ($request->ajax()) {

                $thread_id = $request->thread_id;

                $response = array();

                if (!empty($thread_id)) {
                    $unread_messages = Messages::where("thread_id", $thread_id)
                        ->where('receiver_id', $userId)
                        ->where('read', '0')
                        ->where('receiver_trash', "0")
                        ->orderBy('id', 'ASC')
                        ->get();

                    if (!empty($unread_messages) && count($unread_messages) > 0) {

                        $updateAsRead = Messages::where('thread_id', $thread_id)
                            ->where('receiver_id', $userId)
                            ->where('read', '0')
                            ->where('receiver_trash', "0")
                            ->update(['read' => '1']);
                        Messages::where('receiver_id', $userId)
                            ->where('notif_checked', "0")
                            ->where('receiver_trash', "0")
                            ->update(["notif_checked" => "1"]);

                        $userInfo = User::with('user_profiles')
                            ->where('id', $unread_messages[0]->sender_id)
                            ->first();

                        foreach ($unread_messages as $unread_message) {
                            $response['messages'][] = ['id' => $unread_message->id, 'message' => $unread_message->message, 'created_date' => date('d/m/Y', strtotime(getUserDateByTimezone($unread_message->created_at))), 'created_time' => date("h:i A", strtotime(getUserDateByTimezone($unread_message->created_at)))];
                        }
                        $response['user'] = $userInfo;
                    }
                }

                return response()->json($response);
            }
        }
    }

    public function archiveMessages(Request $request)
    {

        $data = $request->all();
        $userId = Auth::id();

        if ($request->post()) {
            if (!empty($data['delete_thread'])) {

                foreach ($data['delete_thread'] as $thread) {

                    $updateSenderTrash = Messages::where('thread_id', $thread)
                        ->where('sender_id', $userId)
                        ->where('sender_trash', '0')
                        ->update(['sender_trash' => '1']);
                    $updateReceiverTrash = Messages::where('thread_id', $thread)
                        ->where('receiver_id', $userId)
                        ->where('receiver_trash', '0')
                        ->update(['receiver_trash' => '1']);
                }

                $request->session()->flash('status', __('backend_messages.message_archived'));
                return redirect()->back();
            } else {
                $request->session()->flash('error', __('backend_messages.something_wrong'));
                return redirect()->back();
            }
        } else {
            $request->session()->flash('error', __('backend_messages.something_wrong'));
            return redirect()->back();
        }
    }

    public function deleteMessages(Request $request)
    {

        $data = $request->all();
        $userId = Auth::id();

        if ($request->post()) {
            if (!empty($data['delete_thread'])) {

                foreach ($data['delete_thread'] as $thread) {

                    $updateSenderTrash = Messages::where('thread_id', $thread)
                        ->where('sender_id', $userId)
                        ->where('sender_trash', '1')
                        ->update(['sender_trash' => '2']);
                    $updateReceiverTrash = Messages::where('thread_id', $thread)
                        ->where('receiver_id', $userId)
                        ->where('receiver_trash', '1')
                        ->update(['receiver_trash' => '2']);
                }

                $request->session()->flash('status', __('backend_messages.message_deleted'));
                return redirect()->back();
            } else {
                $request->session()->flash('error', __('backend_messages.something_wrong'));
                return redirect()->back();
            }
        } else {
            $request->session()->flash('error', __('backend_messages.something_wrong'));
            return redirect()->back();
        }
    }

    public function manageUserCodePromo(Request $request)
    {
        $code = $request->code;
        DB::table("code_promo")->where("code", $code)->first();
    }

    public function get_fb_profile(Request $request)
    {
        $id = $request->userId;
        $user_id = Auth::id();
        $date = date("Y-m-d");
        $nb = DB::table('user_show_fb')->where("user_id", $user_id)->whereRaw("date(date)='" . $date . "'")->count();
        $check = DB::table('user_show_fb')->where("user_id", $user_id)->whereRaw("date(date)='" . $date . "'")->where("user_contacted", $request->user_id)->first();
        if ($nb < getConfig("nb_max_contact") || !is_null($check)) {

            if (is_null($check)) {
                DB::table("user_show_fb")->insert([
                    "user_id" => $user_id,
                    "user_contacted" => $id,
                ]);
            }

            $response = fbLink($request->userId);
            return response()->json($response);
        }
    }

    public function markMessageAsRead(Request $request)
    {
        $id = $request->id;
        $read = DB::table('messages')->where("id", $id)->first();
        if (!is_null($read)) {
            if (is_null($read->read) != 1 && $read->receiver_id == Auth::id()) {
                DB::table('messages')->where("id", $id)->update(
                    ['read' => '1', "read_date" => date("Y-m-d H:i:s")]
                );
            }
        }

        return "true";
    }

    public function getReadMessages(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $userId = Auth::id();
                $response = array();
                $read_messages = Messages::where('sender_id', $userId)
                    ->where('read', '1')
                    ->whereNotNull("read_date")
                    ->where('receiver_trash', "0")
                    ->orderBy('created_at', 'DESC')
                    ->get();
                $read_messages = $this->arrageDataByThread($read_messages);
                if (!empty($read_messages) && count($read_messages) > 0) {
                    foreach ($read_messages as $key => $read_message) {
                        $response['messages'][$key] = ["thread_id" => $key, 'id' => $read_message->id, 'read_date' => dateLocale($read_message->read_date, true)];
                    }
                }
                return response()->json($response);
            }
        }
    }

    private function arrageDataByThread($messages)
    {
        $response = array();
        foreach ($messages as $key => $message) {
            if (!array_key_exists($message->thread_id, $response)) {
                $response[$message->thread_id] = $message;
            }
        }
        return $response;
    }

    public function isAllowedVisitRequest(Request $request)
    {
        $max = getConfig("free_visit_request");
        if (Auth::check()) {
            if (isset($request->user_id)) {
                if (isUserSubscribed() || isUserSubscribed($request->user_id)) {
                    return response()->json(array("allow" => true));
                }
            } else {
                if (isUserSubscribed()) {
                    return response()->json(array("allow" => true));
                }
            }
            $userId = Auth::id();
            $nb = DB::table("visit_requests")->where("requester_id", $userId)->count();
            if ($nb < $max) {
                return response()->json(array("allow" => true));
            } else {
                return response()->json(array("allow" => false));
            }
        }
    }

    public function isAllowedMessage(Request $request)
    {
        $max = getConfig("free_message");
        if (Auth::check()) {
            if (isset($request->user_id)) {
                if (isUserSubscribed() || isUserSubscribed($request->user_id) || isToctocUser()) {
                    return response()->json(array("allow" => true));
                }
            } else {
                if (isUserSubscribed() || isToctocUser()) {
                    return response()->json(array("allow" => true));
                }
            }

            $userId = Auth::id();
            $nb = DB::table("messages")->where("sender_id", $userId)->count();
            if ($nb < $max) {
                return response()->json(array("allow" => true));
            } else {
                return response()->json(array("allow" => false));
            }
        }
    }

    public function isUserAdUserSubscription(Request $request)
    {
        if (Auth::check()) {
            $ad_user = DB::table("ads")->where("id", $request->ad_id)->first()->user_id;
            if (isUserSubscribed() || isUserSubscribed($ad_user)) {
                return "true";
            } else {
                return "false";
            }
        }
    }

    public function isUserAdUserSubscriptionPhone(Request $request)
    {

        countTrakingAds('phone', $request->ad_id);

        if (Auth::check()) {
            $ad_user = DB::table("ads")->where("id", $request->ad_id)->first()->user_id;
            if (isUserSubscribed() || isUserSubscribed($ad_user)) {
                return "true";
            } else {
                return "false";
            }
        }
    }

    public function getReturnPage(Request $request)
    {
        return generateRegisterReturnUrl();
    }

    public function isAllowedMessageFlash()
    {
        //return true;
        $max = getConfig("free_message_flash");
        if (Auth::check()) {
            if (isset($request->user_id)) {
                if (isUserSubscribed() || isUserSubscribed($request->user_id)) {
                    return true;
                }
            } else {
                if (isUserSubscribed()) {
                    return true;
                }
            }
            $userId = Auth::id();
            $nb = DB::table("coup_de_foudres")->where("sender_id", $userId)->where('created_at', '>=', date('Y-m-d') . ' 00:00:00')->count();
            if ($nb < $max) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function desindexeAd($id, Request $request)
    {
        if ($id) {
            DB::table("no_index_ads")->insert(["ad_id" => $id]);
            $request->session()->flash('status', "Ad desindexed");
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function indexeAd($id, Request $request)
    {
        if ($id) {
            DB::table("no_index_ads")->where("ad_id", $id)->delete();
            $request->session()->flash('status', "Ad indexed");
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }
}
