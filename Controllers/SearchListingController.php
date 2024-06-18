<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use App\User;
use App\Http\Models\Ads\Ads;
use App\Http\Models\Ads\AdDetails;
use App\Http\Models\FeaturedCity;
use Illuminate\Support\Facades\View;
use App\Http\Models\Ads\Favourites;
use App\Http\Models\Ads\AdVisitingDetails;
use App\Http\Models\Ads\VisitRequests;
use App\Http\Models\Ads\Messages;
use App\Http\Models\SignalTag;
use App\Http\Models\Ads\AdDocuments;
use App\Http\Models\Ads\AdProximity;
use Illuminate\Support\Carbon;
use App\Http\Controllers\stdClass;

use App\Repositories\MasterRepository;
use Session;

class SearchListingController extends Controller
{
    private $perpage;
    private $colocNbPerPage;
    private $logementNbPerPage;
    private $nbJour;
    private $nbJourAdsCommunity;//nombre de jour pour l'affichage des annonces crées par les community

    public function __construct(MasterRepository $master)
    {
        if (!guest_listing_page()) {
            $this->middleware('auth', ['except' => ['viewSearchResults', "adsVilles"]]);
        }

        $this->colocNbPerPage = getConfig("nombre_colocataire_villes");
        $this->logementNbPerPage = getConfig("nombre_colocation_villes");
        $this->master = $master;
        $this->nbJour = getConfig("nbr_annonce");
        $this->nbJourAdsCommunity = getConfig("nbJourAdsCommunity");
        $this->perpage = getConfig("nb_per_page_search");
    }

    private function pageTitle($scenario_id, $address = null)
    {
        $ville = "";
        if (!is_null($address)) {
            $ville = getAddressVille($address);
        }
        switch ($scenario_id) {
            case '1':
                return __('searchlisting.trouve_logement', ["ville" => $ville]);
                break;
            case '2':
                return __('searchlisting.trouve_colocation', ["ville" => $ville]);
                # code...
                break;
            case '3':
                return __('searchlisting.trouve_locataire', ["ville" => $ville]);
                # code...
                break;
            case '4':
                return __('searchlisting.trouve_colocataire', ["ville" => $ville]);
                # code...
                break;
            case '5':
                return __('searchlisting.monter_colocation_title', ["ville" => $ville]);
                # code...
                break;

            default:
                return __('searchlisting.rechercher_annonce', ["ville" => $ville]);
                # code...
                break;
        }
    }

// ATO -2
//verification si l'annonce existe
    public function searchAnnonce(Request $request)
    {
        // verification
        $request->session()->forget('registerFb');
        $nbJour = getConfig("nbr_annonce");
        $itemLat = $request->itemLat;
        if (!empty($request->radius)) {
            $radius = $request->radius > 4 ? $request->radius : 4;
        } else {
            $radius = 40;
        }
        //pour le recherche en tableau de bord
        if (!empty($itemLat)) {
            if (count($itemLat) > 0) {
                $address = collect($itemLat)->first();
                $address = $address['add'];
            }
        } else { //recherche en page d'accueil
            $address = $request->address;
            $itemLat = [
                [
                    "lat" => $request->latitude,
                    "log" => $request->longitude,
                    "add" => $address
                ]
            ];
            $request->itemLat = $itemLat;

        }
        if (isset($request->scenario_id)) {
            $lat = $request->latitude;
            $long = $request->longitude;
            $scenario_id = $request->scenario_id;
        } else {
            $lat = $request->session()->get("latitude");
            $long = $request->session()->get("longitude");
            $scenario_id = $request->session()->get("scenario_id");
        }
        $radius_query_string = $this->radius_query($lat, $long);

        $query = Ads::with('user.user_profiles')->with('ad_details')->with('nearby_facilities')->with(['ad_files' => function ($query) {
            $query->where('media_type', '0')->orderBy('ordre', 'asc');
        }]);

        if ($scenario_id == 1) {
            $whereScenario = [['scenario_id', '1']];
        } elseif ($scenario_id == 2) {
            $whereScenario = [['scenario_id', '2']];
        } elseif ($scenario_id == 3) {
            $whereScenario = [['scenario_id', '3']];
        } elseif ($scenario_id == 4) {
            $whereScenario = [['scenario_id', '4']];
        } elseif ($scenario_id == 5) {
            $whereScenario = [['scenario_id', '5']];
        } elseif ($scenario_id == 6) {
            $whereScenario = [['scenario_id', '6']];
        } else {
            $scenario__id = $request->session()->get("scenario_id");
            if (!empty($scenario__id)) {
                $whereScenario = [['scenario_id', $scenario__id]];
            } else {
                $whereScenario = [['scenario_id', '1']];
            }
        }
        if (isset($itemLat)) {
            if (count($itemLat) > 1) {
                $i = 0;
                foreach ($itemLat as $key => $value) {
                    $lat_ = $value["lat"];
                    $log_ = $value["log"];
                    if ($i === 0) {
                        $query = $query->WhereRaw("{$this->radius_query($lat_,$log_)} < ?", [$radius])->where($whereScenario)->whereRaw("datediff(now(), ads.updated_at) <= " . $nbJour);
                    } else {
                        $query = $query->orWhereRaw("{$this->radius_query($lat_,$log_)} < ?", [$radius])->where($whereScenario)->whereRaw("datediff(now(), ads.updated_at) <= " . $nbJour);
                    }
                    $i++;
                }
            } else {
                foreach ($itemLat as $key => $value) {
                    $lat_ = $value["lat"];
                    $log_ = $value["log"];
                    $query = $query->WhereRaw("{$this->radius_query($lat_,$log_)} < ?", [$radius])->where($whereScenario)->whereRaw("datediff(now(), ads.updated_at) <= " . $nbJour);

                }
            }
        } else {
            $query = $query->WhereRaw("{$radius_query_string} < ?", [$radius])->where($whereScenario);
        }


        $is_ads = $query->where('status', '1')->where('admin_approve', '1')->where($whereScenario)->whereRaw("datediff(now(), ads.updated_at) <= " . $nbJour)->exists();
        if ($is_ads == false) {
            Session::flash('message', __("alert.no_ads_found"));
        }

        $itemLat = $request->itemLat;
        $request->session()->forget("itemLat");

        $request->session()->put("itemLat", $itemLat);

        if (isset($request->scenario_id)) {
            if (!empty($itemLat)) {
                foreach ($itemLat as $value) {
                    $latitude = $value["lat"];
                    $longitude = $value["log"];
                }
            } else {
                $latitude = $request->latitude;
                $longitude = $request->longitude;
            }

            $scenario_id = $request->scenario_id;
            $request->session()->put("latitude", $latitude);
            $request->session()->put("longitude", $longitude);
            $request->session()->put("scenario_id", $scenario_id);
            $request->session()->put("address", $address);
        } else {
            $latitude = $request->session()->get("latitude");
            $longitude = $request->session()->get("longitude");
            $scenario_id = $request->session()->get("scenario_id");
            $address = $request->session()->get("address");
        }


        if (isset($request->map)) {
            return redirect(searchUrl($latitude, $longitude, $address, $scenario_id) . "?map=true");
        } else {
            if (is_null($latitude) || is_null($longitude)) {
                return redirect()->route('home');
            }
            return redirect(searchUrl($latitude, $longitude, $address, $scenario_id));
        }
    }

    //ATO
    public function viewSearchDemandeResults($scenario, $region, $ville, Request $request)
    {
        if (isset($request->scenario_id)) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $scenario_id = $request->scenario_id;
            $address = $request->address;
            $request->session()->put("latitude", $latitude);
            $request->session()->put("longitude", $longitude);
            $request->session()->put("scenario_id", $scenario_id);
            $request->session()->put("address", $address);
        } else {
            $infosAdress = getCoordByAdressV2($ville, $region);
            if (!is_null($infosAdress)) {
                $latitude = $infosAdress['latitude'];
                $longitude = $infosAdress["longitude"];
                $scenario_id = getScenIdByUrl($scenario);
                $scenario_id = config("customConfig.permutedScenario")[$scenario_id - 1];
                $address = $infosAdress['address'];
                $request->latitude = $latitude;
                $request->longitude = $longitude;
                $request->scenario_id = $scenario_id;
                $request->address = $address;
                $request->session()->put("latitude", $latitude);
                $request->session()->put("longitude", $longitude);
                $request->session()->put("scenario_id", $scenario_id);
                $request->session()->put("address", $address);
            }
        }


        if (isset($request->map)) {
            (isset($request->id)) ? $id = $request->id : $id = 0;
            return $this->searchAdsMap($id, $request);
        } else {
            $id = null;
            if (isset($request->id)) {
                $id = $request->id;
                $user_id = Auth::id();
                $ad = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->where('id', $id)->first();

                if (!empty($ad)) {
                    $request->latitude = $ad->latitude;
                    $request->longitude = $ad->longitude;
                    $scenario_id = config("customConfig.permutedScenario")[$ad->scenario_id - 1];
                    $request->scenario_id = $scenario_id;
                    $request->address = $ad->address;
                    $request->session()->put("latitude", $request->latitude);
                    $request->session()->put("longitude", $request->longitude);
                    $request->session()->put("scenario_id", $request->scenario_id);
                    $request->session()->put("address", $request->address);
                } else {
                    $request->session()->flash('error', __('backend_messages.no_ad_found'));
                    return redirect()->route('user.dashboard');
                }
            }
            return $this->searchAdsScenId($id, $request);
        }
    }

    public function villeHome(Request $request, $ville = null)
    {
        // if (count($_GET) > 0) {
        return redirect("/");
        // }

        $infosAddress = null;
        if (!is_null($ville)) {
            $infosAddress = getCoordByAdress($ville);
        }
        if (!is_null($url_parameter) && is_null($ville)) {
            return redirect("/");
        }
        $nbAdsCount = Ads::count();
        $nbUsersCount = DB::table("users")->count();

        $lang = 'Français';
        if (\App::getLocale() == 'en') {
            $lang = 'English';
        } else {
            $lang = 'Français';
        }

        $nbMax = 4;
        $lat = trim($infosAddress['latitude']);
        $long = trim($infosAddress['longitude']);
        $ville = trim($infosAddress['ville']);
        $whereScenario = ['1', '2'];
        $featured_rooms_ville = Ads::select("ads.id", "ads.title", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic")->join("users", "users.id", "ads.user_id")->join("user_profiles", "users.id", "user_profiles.user_id")->with(['ad_files' => function ($query) {
            $query->where('media_type', '0')->orderBy('ordre', 'asc');
        }, 'ad_details'])->whereIn('scenario_id', $whereScenario)->where('status', '1')->where('admin_approve', '1')->limit($this->logementNbPerPage)->whereRaw("ads.address LIKE '%?%'", [$ville])->orderByRaw('ads.updated_at DESC')->groupBy("ads.id")->get();
        $whereScenario = ['4', '5'];
        $featured_room_mates_ville = Ads::select("ads.id", "ads.title", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic", "user_profiles.birth_date")->join("users", "users.id", "ads.user_id")->join("user_profiles", "users.id", "user_profiles.user_id")->whereIn('scenario_id', $whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("ads.address LIKE '%?%'", [$ville])->offset(0)->limit($this->colocNbPerPage)->orderByRaw('ads.updated_at DESC')->groupBy("ads.id")->get();

        $fractured_visits = [];
        $featured_room_mates = [];
        $featured_rooms = [];
        // supression du variable step qui est inutil
        if (isset($step)) {
            return view('home', compact('lang', "nbAdsCount", "step", "nbUsersCount", "featured_rooms", "featured_room_mates", "fractured_visits", "infosAddress", "featured_rooms_ville", "featured_room_mates_ville"));
        }

        return view('home', compact('lang', "nbAdsCount", "nbUsersCount", "featured_rooms", "featured_room_mates", "fractured_visits", "infosAddress", "featured_rooms_ville", "featured_room_mates_ville"));
    }

    private function pageNantes(Request $request)
    {
        $id = 90928;
        $user_id = Auth::id();
        $desactive = false;
        $signal_tags = SignalTag::all();
        $searched_id = 0;
        $searched_ad = Ads::find($searched_id);
        $scenario = ["Rent a property", "Share an accomodation", "Seek to rent a property", "Seek to share an accomodation", "Seek someone to search together a property"];
        if (isset($id)) {
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


            if (!empty($ad)) {
                $suggestion_ads = Ads::has('user')->with(['user.user_profiles'])->where('address', $ad->address)->where('scenario_id', $ad->scenario_id)->where('id', '!=', $ad->id)->orderBy('id', 'desc')->limit(6)->get();
                $suggestion_ads = Ads::has('user')->with(['user.user_profiles'])->where('address', $ad->address)->where('scenario_id', $ad->scenario_id)->where('id', '!=', $ad->id)->orderBy('id', 'desc')->limit(4)->get();
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
                            } elseif ($ad->scenario_id == 3 || $ad->scenario_id == 4) {
                                $friends_scnario = [1, 2];
                            } else {
                                $friends_scnario = [5];
                            }

                            if (!empty($fb_friends_arr) && count($fb_friends_arr) > 0) {
                                $radius_query_string = $this->radius_query($ad->latitude, $ad->longitude);

                                $friend_ads = User::with(['user_profiles', 'ads' => function ($query) use ($friends_scnario, $radius_query_string) {
                                    $query->whereRaw($radius_query_string . '<= 40')->whereIn('scenario_id', $friends_scnario);
                                }])->whereIn('provider_id', $fb_friends_arr)->get();
                                if (!empty($friend_ads) && count($friend_ads) > 0) {
                                    foreach ($friend_ads as $key => $friend_ad) {
                                        if (!empty($friend_ad->ads) && count($friend_ad->ads) > 0) {
                                            $friend_ads_arr[$key]['user_profile'] = [
                                                'first_name' => $friend_ad->first_name,
                                                'last_name' => $friend_ad->last_name,
                                                'profile_pic' => $friend_ad->user_profiles->profile_pic
                                            ];
                                            foreach ($friend_ad->ads as $friend_add) {
                                                $friend_ads_arr[$key]['ads'][] = [
                                                    'ad_id' => $friend_add->id,
                                                    'ad_title' => $friend_add->title,
                                                    'ad_location' => $friend_add->address,
                                                    'ad_url_slug' => $friend_add->url_slug
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
                            } elseif ($ad->scenario_id == 3 || $ad->scenario_id == 4 || $ad->scenario_id == 5) {
                                if (isset($friendsFb)) {
                                    return view($view_name, compact('friendsFb', 'savedDocuments', 'layout', 'ad', 'ad_premium', 'user_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                } else {
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
                            } elseif ($ad->scenario_id == 3 || $ad->scenario_id == 4 || $ad->scenario_id == 5) {
                                if (isset($friendsFb)) {
                                    return view($view_name, compact('friendsFb', 'savedDocuments', 'layout', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                } else {
                                    return view($view_name, compact('layout', 'savedDocuments', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                                }
                            }
                        }
                    } else {
                        $documents = new AdDocuments();
                        $ad_documents = $documents->where('ad_id', $ad->id)->get();

                        $layout = 'outer';
                        if ($ad->scenario_id == 1 || $ad->scenario_id == 2) {
                            return view($view_name, compact('layout', 'ad', 'ad_premium', 'user_id', 'favourites', 'searched_id', 'friend_ads_arr', 'ad_documents', 'user_id', 'signal_tags', 'socialInfo', 'userProfiles', 'lifeStyles', 'page_title', "page_meta_keyword", "page_description", "typeMusics", "desactive", "floute", 'ad_proximities', 'suggestion_ads'));
                        } elseif ($ad->scenario_id == 3 || $ad->scenario_id == 4 || $ad->scenario_id == 5) {
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

    public function adsVilles(Request $request)
    {
        $ville = $request->ville;
        $infosAddress = null;
        if (!is_null($ville)) {
            $infosAddress = getCoordByAdress($ville);
        }

        $whereScenario = ['1', '2'];

        $nbMax = 4;


        $nbMax = 4;
        $lat = trim($infosAddress['latitude']);
        $long = trim($infosAddress['longitude']);
        $ville = trim($infosAddress['ville']);
        $whereScenario = ['1', '2'];

        $featured_rooms_ville = Ads::select("ads.id", "ads.title", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic")->join("users", "users.id", "ads.user_id")->join("user_profiles", "users.id", "user_profiles.user_id")->with(['ad_files' => function ($query) {
            $query->where('media_type', '0')->orderBy('ordre', 'asc');
        }, 'ad_details'])->whereIn('scenario_id', $whereScenario)->where('status', '1')->where('admin_approve', '1')->limit($this->logementNbPerPage)->whereRaw("ads.address LIKE '%" . $ville . "%'")->orderByRaw('ads.updated_at DESC')->groupBy("ads.id")->get();
        $whereScenario = ['4', '5'];
        $featured_room_mates_ville = Ads::select("ads.id", "ads.title", "ads.description", "ads.updated_at", "ads.min_rent", "users.first_name", "user_profiles.profile_pic", "user_profiles.birth_date")->join("users", "users.id", "ads.user_id")->join("user_profiles", "users.id", "user_profiles.user_id")->whereIn('scenario_id', $whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("ads.address LIKE '%" . $ville . "%'")->offset(0)->limit($this->colocNbPerPage)->orderByRaw('ads.updated_at DESC')->groupBy("ads.id")->get();


        return view('home/adsVille', compact("featured_rooms_ville", "featured_room_mates_ville", "ville"));
    }

    /* ATO 1 */
    public function viewSearchResults($scenario, $region, $ville, Request $request)
    {
        if (session('inviteLocataire')) {
            session()->forget('inviteLocataire');
            return redirect(session('inviteLocataireUrl'));
        }
        if (!Auth::check() && !guest_listing_page()) {
            //     $url = $request->url();
            //     $temp = explode("/", $url);
            //     if ($temp[3] != 'colocation' || !is_null($request->page)) {
            return redirect("/");
            // }
            // return $this->villeHome($ville, $request);
        }

        if (!empty($request->r)) {
            $request->radius = $request->r;
        }

        if (isset($request->scenario_id)) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $scenario_id = $request->scenario_id;
            $address = $request->address;
            $request->session()->put("latitude", $latitude);
            $request->session()->put("longitude", $longitude);
            $request->session()->put("scenario_id", $scenario_id);
            $request->session()->put("address", $address);
        } else {
            $infosAdress = getCoordByAdressV2($ville, $region);

            if (!is_null($infosAdress)) {
                $latitude = $infosAdress['latitude'];
                $longitude = $infosAdress["longitude"];
                $scenario_id = getScenIdByUrl($scenario);
                $address = $infosAdress['address'];
                $request->latitude = $latitude;
                $request->longitude = $longitude;
                $request->scenario_id = $scenario_id;
                $request->address = $address;
                $request->session()->put("latitude", $latitude);
                $request->session()->put("longitude", $longitude);
                $request->session()->put("scenario_id", $scenario_id);
                $request->session()->put("address", $address);
            }
        }


        if (isset($request->map)) {
            (isset($request->id)) ? $id = $request->id : $id = 0;

            return $this->searchAdsMap($id, $request);
        } else {
            $id = null;
            if (isset($request->id)) {
                $id = $request->id;
                $user_id = Auth::id();
                $ad = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->where('id', $id)->first();


                if (!empty($ad)) {
                    $request->latitude = $ad->latitude;
                    $request->longitude = $ad->longitude;
                    $scenario_id = config("customConfig.permutedScenario")[$ad->scenario_id - 1];
                    $request->scenario_id = $scenario_id;
                    $request->address = $ad->address;
                    $request->session()->put("latitude", $request->latitude);
                    $request->session()->put("longitude", $request->longitude);
                    $request->session()->put("scenario_id", $request->scenario_id);
                    $request->session()->put("address", $request->address);
                } else {
                    $request->session()->flash('error', __('backend_messages.no_ad_found'));
                    return redirect()->route('user.dashboard');
                }
            }
            return $this->searchAdsScenId($id, $request);
        }
    }

    public function searchAds($id, Request $request)
    {
        updateSearchUrl();
        $splitUrl = explode("-", $id);
        if (count(explode("~", $id)) > 1) {
            $splitUrl = explode("~", $id);
        }
        $id = $splitUrl[count($splitUrl) - 1];

        $user_id = Auth::id();
        $ad = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->where('id', $id)->first();

        if (!empty($ad)) {
            $layout = 'inner';
            $scenario_id = config("customConfig.permutedScenario")[$ad->scenario_id - 1];
            return redirect(searchUrl($ad->latitude, $ad->longitude, $ad->address, $scenario_id) . "?id=" . $id);
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }
    }

    //ATO 2
    public function searchAdsScenId($id = null, Request $request = null)
    {

        updateSearchUrl();
        if (isset($request->scenario_id)) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $scenario_id = $request->scenario_id;
            $address = $request->address;
            $request->session()->put("latitude", $latitude);
            $request->session()->put("longitude", $longitude);
            $request->session()->put("scenario_id", $scenario_id);
            $request->session()->put("address", $address);
        } else {
            $latitude = $request->session()->get("latitude");
            $longitude = $request->session()->get("longitude");
            $scenario_id = $request->session()->get("scenario_id");
            $address = $request->session()->get("address");
        }

        $layout = 'inner';
        $user_id = Auth::id();
        if (is_null($id)) {
            $id = 0;
        }

        if (is_null($user_id)) {
            $layout = 'outer';
            $user_id = 0;
        }

        $countries = DB::table('countries')->get();
        $lifestyles = DB::table('user_lifestyles')->get();
        $socialInterests = DB::table('social_interests')->get();
        $sous_type_loc = DB::table('sous_type_loc')->get();
        $proximities = $this->master->getPointProximity();


        if ($scenario_id == 3 || $scenario_id == 4 || $scenario_id == 5) {
            return $this->searchAdFirstScenario($id, $user_id, $latitude, $longitude, $scenario_id, $address, $request, $layout, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
        } elseif ($scenario_id == 1 || $scenario_id == 2) {
            return $this->searchAdThirdScenario($id, $user_id, $latitude, $longitude, $scenario_id, $address, $request, $layout, null, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities, $proximities);
        }
    }

    public function searchAdsMap($id, Request $request)
    {
        updateSearchUrl();
        $id = $id;
        $user_id = Auth::id();
        $url_parameter = $request->segment(1);
        $ad = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->where('id', $id)->whereIn('scenario_id', ['3', '4'])->first();
        $countries = DB::table('countries')->get();
        $lifestyles = DB::table('user_lifestyles')->get();
        $socialInterests = DB::table('social_interests')->get();
        $sous_type_loc = DB::table('sous_type_loc')->get();
        $proximities = $this->master->getPointProximity();

        if (!empty($ad)) {
            $layout = 'inner';
            $scenario_id = config("customConfig.permutedScenario")[$ad->scenario_id - 1];
            return $this->searchAdThirdScenarioMap($id, $user_id, $ad->latitude, $ad->longitude, $scenario_id, $ad->address, $request, $layout, null, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
        } elseif (isset($request->scenario_id)) {
            $id = 0;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $scenario_id = $request->scenario_id;
            $address = $request->address;
            $request->session()->put("latitude", $latitude);
            $request->session()->put("longitude", $longitude);
            $request->session()->put("scenario_id", $scenario_id);
            $request->session()->put("address", $address);
            if (Auth::check()) {
                $layout = 'inner';
            } else {
                $layout = 'outer';
            }


            return $this->searchAdThirdScenarioMap($id, $user_id, $latitude, $longitude, $scenario_id, $address, $request, $layout, null, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
        } elseif ($request->session()->has('scenario_id')) {
            $latitude = $request->session()->get("latitude");
            $longitude = $request->session()->get("longitude");
            $scenario_id = $request->session()->get("scenario_id");
            $address = $request->session()->get("address");
            $id = 0;
            if (Auth::check()) {
                $layout = 'inner';
            } else {
                $layout = 'outer';
            }

            return $this->searchAdThirdScenarioMap($id, $user_id, $latitude, $longitude, $scenario_id, $address, $request, $layout, null, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }
    }


    public function searchAdsLocation($location_id, $scenario_id, Request $request)
    {
        $location_id = base64_decode($location_id);
        $scenario_id = base64_decode($scenario_id);
        $countries = DB::table('countries')->get();
        $lifestyles = DB::table('user_lifestyles')->get();
        $socialInterests = DB::table('social_interests')->get();
        $sous_type_loc = DB::table('sous_type_loc')->get();
        $proximities = $this->master->getPointProximity();
        if ($location_id == 0) {
            $location_id = null;
        }
        if (isset($request->lat)) {
            $layout = 'outer';
            $user_id = Auth::id();
            $id = null;

            if ($scenario_id == 1 || $scenario_id == 2 || $scenario_id == 5) {
                return $this->searchAdFirstScenario($id, $user_id, $request->lat, $request->long, $scenario_id, $request, $layout, $location_id, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
            } elseif ($scenario_id == 3 || $scenario_id == 4) {
                return $this->searchAdThirdScenario($id, $user_id, $request->lat, $request->long, $scenario_id, $request->address, $request, $layout, $location_id, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
            }
        } else {
            $featured_city = FeaturedCity::with('location_data')->where('is_active', '1')->where('location_id', $location_id)->first();
            if (!empty($featured_city)) {
                $layout = 'outer';
                $user_id = Auth::id();
                $id = null;

                if ($scenario_id == 1 || $scenario_id == 2 || $scenario_id == 5) {
                    return $this->searchAdFirstScenario($id, $user_id, $featured_city->location_data->latitude, $featured_city->location_data->longitude, $scenario_id, $request, $layout, $location_id, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
                } elseif ($scenario_id == 3 || $scenario_id == 4) {
                    return $this->searchAdThirdScenario($id, $user_id, $featured_city->location_data->latitude, $featured_city->location_data->longitude, $scenario_id, $featured_city->location_data->city, $request, $layout, $location_id, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
                }
            } else {
                $request->session()->flash('error', __('backend_messages.no_ad_found'));
                return redirect()->route('home');
            }
        }
    }

    public function searchAdsLocationMap($location_id, $scenario_id, Request $request)
    {
        $location_id = base64_decode($location_id);
        $scenario_id = base64_decode($scenario_id);
        $countries = DB::table('countries')->get();
        $lifestyles = DB::table('user_lifestyles')->get();
        $socialInterests = DB::table('social_interests')->get();
        $sous_type_loc = DB::table('sous_type_loc')->get();
        $proximities = $this->master->getPointProximity();
        if ($scenario_id == 3 || $scenario_id == 4) {
            $featured_city = FeaturedCity::with('location_data')->where('is_active', '1')->where('location_id', $location_id)->first();

            if (!empty($featured_city)) {
                $layout = 'outer';
                $user_id = Auth::id();
                $id = null;

                return $this->searchAdThirdScenarioMap($id, $user_id, $featured_city->location_data->latitude, $featured_city->location_data->longitude, $scenario_id, $featured_city->location_data->city, $request, $layout, $location_id, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities);
            } else {

                $request->session()->flash('error', __('backend_messages.no_ad_found'));
                return redirect()->route('user.dashboard');
            }
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }
    }

    public function searchAdFirstScenario($id, $user_id, $lat, $long, $scenario_id, $address, $request, $layout, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities)
    {
        if (isUserSubscribed()) {
            if (Auth::user()->type_design == 1) {
                $view_name = "searchlisting/first-scen-data";
            } else {
                $view_name = "searchlisting/basic/first-scen-data";
            }
        } else {
            $view_name = "searchlisting/basic/first-scen-data";
        }
        $typeMusics = DB::table('user_type_music')->get();
        $favourites = [];
        $page_title = $this->pageTitle($scenario_id, $address);
        $orderTop = "IF((is_top_list = 1 AND date_top_list >= NOW()), 1, 2) ASC";
        if ($request->isAlert == "1") {
            $data = $request->all();
            $data['address'] = $address;
            $data['scenario_id'] = $scenario_id;
            $data['latitude'] = $lat;
            $data['longitude'] = $long;
            if (!empty($request->idAlert)) {
                $this->updateAlert($request->idAlert, $data);
            } else {
                $this->saveAlert($data);
            }
        }
        if ($layout == 'inner' && $user_id != 0) {
            if (count(Auth::user()->favorites) > 0) {
                foreach (Auth::user()->favorites as $favourite) {
                    array_push($favourites, $favourite->ad_id);
                }
            }
        }

        if (!empty($request->radius)) {
            $radius = $request->radius > 4 ? $request->radius : 4;
        } else {
            $radius = 40;
        }

        if ($scenario_id == 3) {
            $whereScenario = [['scenario_id', '3']];
        } elseif ($scenario_id == 4) {
            $whereScenario = [['scenario_id', '4']];
        } elseif ($scenario_id == 5) {
            $whereScenario = [['scenario_id', '5']];
        }

        $radius_query_string = $this->radius_query($lat, $long);

        if ($request->ajax()) {

            $request->session()->put("SEARCH_FILTERS", $request->all());

            $school = $request->school;
            $smoker = $request->smoker;
            $country = $request->country;
            $city = $request->city;
            $common_friend = ($request->common_friend == "true") ? true : false;

            $with_image = $request->with_image;

            $urgent = $request->urgent;
            $whereRawArray = [];
            $sous_type_loc = $request->sous_type_loc;
            $age = $request->age;


            $property_types = !empty($request->property_types) ? json_decode($request->property_types) : [];

            $property_rules = !empty($request->property_rules) ? json_decode($request->property_rules) : [];

            $garanty = !empty($request->garanty) ? json_decode($request->garanty) : [];

            $lifestyles = !empty($request->lifestyle) ? json_decode($request->lifestyle) : [];

            $social_interests = !empty($request->social_interest) ? json_decode($request->social_interest) : [];

            $facilities = !empty($request->facilities) ? json_decode($request->facilities) : [];

            $visit_details = !empty($request->visiting_detail) ? json_decode($request->visiting_detail) : [];

            $gender = !empty($request->gender) ? json_decode($request->gender) : [];

            $occupation = !empty($request->occupation) ? json_decode($request->occupation) : [];

            $musics = !empty($request->musics) ? json_decode($request->musics) : [];

            $proximities = !empty($request->proximity) ? json_decode($request->proximity) : [];


            $query = Ads::with('user.user_profiles')->with('ad_details')->with('nearby_facilities')->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }]);

            $selectRaw = 'ads.*';
            if (isStatUser()) {
                $selectRaw = 'ads.*, SUM(ad_tracking.clic) as total_clic, SUM(ad_tracking.message) as total_message, SUM(ad_tracking.toc_toc) as total_toc_toc, SUM(ad_tracking.phone) as total_phone, SUM(ad_tracking.contact_fb) as total_contact_fb';
                $query = $query->leftJoin('ad_tracking', 'ad_tracking.ads_id', '=', 'ads.id');
            }


            if (!empty($request->sort)) {
                switch ($request->sort) {
                    case 2:
                        $orderBy = 'ads.min_rent ASC';
                        break;
                    case 3:
                        $orderBy = 'ads.min_rent DESC';
                        break;
                    case 4:
                        $orderBy = 'ads.updated_at ASC';
                        break;
                    default:
                        $orderBy = 'ads.updated_at DESC';
                        break;
                }
            } else {
                $orderBy = 'ads.updated_at DESC';
            }

            if (!empty($request->stat)) {
                switch ($request->stat) {
                    case 1:
                        $orderBy = 'total_clic';
                        break;
                    case 2:
                        $orderBy = 'total_message';
                        break;
                    case 3:
                        $orderBy = 'total_toc_toc';
                        break;
                    case 4:
                        $orderBy = 'total_phone';
                        break;
                    case 5:
                        $orderBy = 'total_contact_fb';
                        break;
                }

                $orderBy .= ' ' . $request->type_stat;
            }

            if (!empty($age) || count($occupation) > 0 || count($gender) > 0 || count($property_rules) > 0 || $sous_type_loc != 0 || !empty($urgent) || !empty($with_image) || count($property_types) > 0 || count($visit_details) > 0 || count($facilities) > 0 || $common_friend || !empty($school) || $smoker != 2 || !empty($city) || count($lifestyles) > 0 || count($social_interests) > 0 || !empty($request->bedrooms) || !empty($request->bathrooms) || !empty($request->kitchen) || (isset($request->kitchen) && $request->kitchen == 0) || !empty($request->furnished) || (isset($request->furnished) && $request->furnished == 0) || !empty($request->min_rent) || !empty($request->max_rent) || !empty($request->min_area) || !empty($request->max_area) || !empty($request->sort) || count($musics) > 0 || count($proximities) > 0 || $request->alcool != 2) {
                $join_profil = false;
                $join_lifestyle = false;
                $join_music = false;
                $join_interest = false;
                $join_proximity = false;
                $join_details = false;
                $join_visit = false;
                $whereRaw = "1=1 ";
                if (!empty($school)) {
                    $join_profil = true;
                    $whereRaw .= " AND UPPER(user_profiles.school) = ?";
                    $whereRawArray[] = trim(strtoupper($school));
                }

                if (!empty($request->profession)) {
                    $join_profil = true;
                    $whereRaw .= " AND UPPER(user_profiles.profession) = ?";
                    $whereRawArray[] = trim(strtoupper($request->profession));
                }
                if (!empty($city)) {
                    $join_profil = true;
                    $whereRaw .= " AND user_profiles.city = '" . $city . "'";
                }
                if (!empty($request->alcool) && $request->alcool != 2) {
                    $join_profil = true;
                    $whereRaw .= " AND user_profiles.alcool = '" . $request->alcool . "'";
                }

                if (!empty($smoker) && $smoker != 2) {
                    $join_profil = true;
                    $whereRaw .= " AND user_profiles.smoker = " . $smoker;
                }

                if (!empty($with_image) && $with_image != 0) {
                    $join_profil = true;
                    $whereRaw .= " AND user_profiles.profile_pic IS NOT NULL";
                }

                if (!empty($urgent) && $urgent != 0) {
                    $whereRaw .= " AND ads.is_logo_urgent=1 AND date_logo_urgent >= NOW() ";
                }

                if (count($gender) > 0) {
                    $join_profil = true;
                    foreach ($gender as $i => $gen) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_profiles.sex = '" . $gen . "'";
                        } else {
                            $whereRaw .= " Or user_profiles.sex = '" . $gen . "'";
                        }
                    }
                    $whereRaw .= ") AND user_profiles.sex IS NOT NULL AND user_profiles.sex <> ''";
                }

                if (count($occupation) > 0) {
                    $join_profil = true;
                    foreach ($occupation as $i => $occup) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_profiles.professional_category = '" . $occup . "'";
                        } else {
                            $whereRaw .= " Or user_profiles.professional_category = '" . $occup . "'";
                        }
                    }
                    $whereRaw .= ") AND user_profiles.professional_category IS NOT NULL AND user_profiles.professional_category <> ''";
                }

                if (count($musics) > 0) {
                    $join_music = true;
                    foreach ($musics as $i => $music) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_music.music_id = " . $music;
                        } else {
                            $whereRaw .= " Or user_to_music.music_id = " . $music;
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($lifestyles) > 0) {
                    $join_lifestyle = true;
                    foreach ($lifestyles as $i => $lifestyle) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_lifestyles.lifestyle_id = " . $lifestyle;
                        } else {
                            $whereRaw .= " Or user_to_lifestyles.lifestyle_id = " . $lifestyle;
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($proximities) > 0) {
                    $join_proximity = true;
                    foreach ($proximities as $i => $proximity) {
                        if ($i == 0) {
                            $whereRaw .= " AND (ad_proximity.point_proximity_id = " . $proximity;
                        } else {
                            $whereRaw .= " Or ad_proximity.point_proximity_id = " . $proximity;
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($social_interests) > 0) {
                    $join_interest = true;
                    foreach ($social_interests as $i => $social) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_social_interests.social_interest_id = " . $social;
                        } else {
                            $whereRaw .= " Or user_to_social_interests.social_interest_id = " . $social;
                        }
                    }
                    $whereRaw .= ")";
                }
                if ($common_friend) {
                    $whereRaw .= " AND ((select count(fb_friend_lists.id) from fb_friend_lists where fb_friend_lists.fb_friend_id in (SELECT fb_friend_id FROM fb_friend_lists WHERE user_id=" . $user_id . ") and fb_friend_lists.user_id=ads.user_id ) > 0)";
                }
                if (count($facilities) > 0) {
                    foreach ($facilities as $i => $facility) {
                        if ($i == 0) {
                            $whereRaw .= " AND ((SELECT COUNT(nearby_facilities.id) FROM nearby_facilities WHERE nearby_facilities.latitude=" . $facility->lat . " AND nearby_facilities.longitude=" . $facility->lng . " AND nearby_facilities.ad_id = ads.id) > 0";
                        } else {
                            $whereRaw .= " OR (SELECT COUNT(nearby_facilities.id) FROM nearby_facilities WHERE nearby_facilities.latitude=" . $facility->lat . " AND nearby_facilities.longitude=" . $facility->lng . "AND nearby_facilities.ad_id = ads.id) > 0";
                        }
                    }
                    $whereRaw .= ")";
                }

                if ($sous_type_loc != 0) {
                    $whereRaw .= " AND sous_type_loc =" . $sous_type_loc;
                }

                if (!empty($request->min_rent) && empty($request->max_rent)) {
                    $whereRaw .= " AND ads.min_rent >= " . $request->min_rent;
                }

                if (!empty($request->max_rent) && empty($request->min_rent)) {
                    $whereRaw .= " AND ads.min_rent <= " . $request->max_rent;
                }

                if (!empty($request->min_rent) && !empty($request->max_rent)) {
                    $whereRaw .= " AND (ads.min_rent >= " . $request->min_rent . " AND ads.min_rent <= " . $request->max_rent . " AND ads.max_rent IS NULL) OR (ads.min_rent >= " . $request->min_rent . " AND ads.max_rent >= " . $request->max_rent . " AND ads.min_rent <= " . $request->max_rent . ")";
                }

                if (!empty($request->min_area) && empty($request->max_area)) {
                    $whereRaw .= " AND ad_details.min_surface_area >= " . $request->min_area;
                }

                if (!empty($request->max_area) && empty($request->min_area)) {
                    $whereRaw .= " AND ad_details.max_surface_area <= " . $request->max_area;
                }

                if (!empty($request->min_area) && !empty($request->max_area)) {
                    $whereRaw .= " AND (ad_details.min_surface_area >= " . $request->min_area . " AND ad_details.min_surface_area <= " . $request->max_area . " AND ad_details.max_surface_area IS NULL) OR (ad_details.min_surface_area >= " . $request->min_area . " AND ad_details.max_surface_area >= " . $request->max_area . " AND ad_details.min_surface_area <= " . $request->max_area . ")";
                }

                if (count($property_rules) > 0) {
                    $whereRaw .= "AND (";
                    foreach ($property_rules as $key => $rule) {
                        if ($key != 0) {
                            $whereRaw .= "OR ";
                        }
                        $whereRaw .= " (SELECT COUNT(*) FROM ad_to_property_rules WHERE ad_id=ads.id AND property_rules_id = " . $rule . ")";
                    }
                    $whereRaw .= ")";
                }
                if (count($visit_details) > 0) {
                    $join_visit = true;
                    foreach ($visit_details as $i => $visit) {
                        $first = true;
                        if ($i == 0) {
                            if (isset($visit->date_visit)) {
                                $whereRaw .= " AND ((ad_visiting_details.visiting_date = '" . $visit->date_visit . "'";
                                $first = false;
                            }
                            if (isset($visit->start_time) && !empty($visit->start_time)) {
                                if ($first) {
                                    $whereRaw .= " AND ((ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                }
                            }
                            if (isset($visit->end_time) && !empty($visit->end_time)) {
                                if ($first) {
                                    $whereRaw .= " AND ((ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                }
                            }
                        } else {
                            if (isset($visit->date_visit)) {
                                $whereRaw .= " OR (ad_visiting_details.visiting_date = '" . $visit->date_visit . "'";
                                $first = false;
                            }
                            if (isset($visit->start_time) && !empty($visit->start_time)) {
                                if ($first) {
                                    $whereRaw .= " OR (ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                }
                            }
                            if (isset($visit->end_time) && !empty($visit->end_time)) {
                                if ($first) {
                                    $whereRaw .= " OR (ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                }
                            }
                        }
                        $whereRaw .= ")";
                    }
                    $whereRaw .= ")";
                }

                $where = [];
                if (!empty($request->bedrooms)) {
                    array_push($where, ['ad_details.bedrooms', '>=', $request->bedrooms]);
                }
                if (!empty($request->bathrooms)) {
                    array_push($where, ['ad_details.bathrooms', '>=', $request->bathrooms]);
                }
                if (!empty($request->kitchen) || (isset($request->kitchen) && $request->kitchen == 0)) {
                    if (isset($request->kitchen) && $request->kitchen == 0) {
                        array_push($where, ['ad_details.kitchen_separated', null]);
                    } else {
                        array_push($where, ['ad_details.kitchen_separated', $request->kitchen]);
                    }
                }
                if (!empty($request->furnished) || (isset($request->furnished) && $request->furnished == 0)) {
                    array_push($where, ['ad_details.furnished', $request->furnished]);
                }


                $query = ($join_profil) ? $query->leftJoin('user_profiles', 'user_profiles.user_id', 'ads.user_id') : $query;
                $query = ($join_lifestyle) ? $query->leftJoin('user_to_lifestyles', 'user_to_lifestyles.user_id', 'ads.user_id') : $query;
                $query = ($join_music) ? $query->leftJoin('user_to_music', 'user_to_music.user_id', 'ads.user_id') : $query;
                $query = ($join_interest) ? $query->leftJoin('user_to_social_interests', 'user_to_social_interests.user_id', 'ads.user_id') : $query;
                $query = ($join_proximity) ? $query->leftJoin('ad_proximity', 'ad_proximity.ad_id', 'ads.id') : $query;
                $query = ($join_visit) ? $query->leftJoin('ad_visiting_details', 'ad_visiting_details.ad_id', 'ads.id') : $query;
                $query = $query->join('ad_details', 'ad_details.ad_id', '=', 'ads.id');


                if (count($where) > 0) {
                    if (count($property_types) > 0) {
                        $ads = $query->select(DB::raw($selectRaw))->where($whereScenario)
                            ->where('status', '1')->where('admin_approve', '1')
                            ->whereRaw($radius_query_string . '<= ' . $radius)
                            ->whereRaw($whereRaw, $whereRawArray)
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->whereIn('ad_details.property_type_id', $property_types)
                            ->where($where)
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->groupBy('ads.id')
                            ->paginate($this->perpage);
                    } else {
                        $ads = $query->select(DB::raw($selectRaw))->where($whereScenario)
                            ->where('ads.status', '1')
                            ->where('ads.admin_approve', '1')
                            ->whereRaw($radius_query_string . '<= ' . $radius)->where($where)
                            ->whereRaw($whereRaw, $whereRawArray)
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->groupBy('ads.id')
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->paginate($this->perpage);
                    }
                } else {
                    if (count($property_types) > 0) {
                        $ads = $query->select(DB::raw($selectRaw))->where($whereScenario)
                            ->where('status', '1')->where('admin_approve', '1')
                            ->whereRaw($radius_query_string . '<= ' . $radius)
                            ->whereRaw($whereRaw, $whereRawArray)
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->whereIn('ad_details.property_type_id', $property_types)
                            ->groupBy('ads.id')
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->paginate($this->perpage);
                    } else {
                        $ads = $query->select(DB::raw($selectRaw))
                            ->where($whereScenario)
                            ->where('status', '1')->where('admin_approve', '1')
                            ->whereRaw($radius_query_string . '<= ' . $radius)
                            ->whereRaw($whereRaw, $whereRawArray)
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->groupBy('ads.id')
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->paginate($this->perpage);
                    }
                }
            } else {
                $ads = Ads::with('user.user_profiles')->with('ad_details')->with('nearby_facilities')->select(DB::raw('ads.*'))->where('user_id', '!=', $user_id)->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->whereRaw($radius_query_string . '<= ' . $radius)->orderBy("ads.updated_at", "DESC")->orderByRaw($orderBy)->paginate($this->perpage);
            }

            return view($view_name . '-all', compact('id', 'ads', "address", "lat", "long", "scenario_id", 'favourites', 'layout', 'proximities'));
        }

        $propertyTypes = $this->master->getMasters('property_types');
        $propRules = $this->master->getMasters('property_rules');
        $page_description = searchPageDescription($scenario_id, $address);
        $page_meta_keyword = searchPageKeyword($scenario_id, $address);

        //deplacer form 3 vers liste d'annonce
        $socialInterests = $this->master->getMasters('social_interests');
        $guarAsked = $this->master->getMasters('guarantees');
        $sports = $this->master->getMasters('user_sport');
        if (isUserSubscribed()) {
            if (Auth::user()->type_design == 1) {
                return view('searchlisting/search-first-scen', compact('id', "address", "lat", "long", 'propertyTypes', 'propRules', 'scenario_id', 'favourites', 'layout', 'countries', 'lifestyles', 'socialInterests', 'sous_type_loc', "page_title", "page_description", "page_meta_keyword", "typeMusics", "socialInterests", "guarAsked", "sports", 'proximities'));
            } else {
                return view('searchlisting/basic/search-first-scen', compact('id', "address", "lat", "long", 'propertyTypes', 'propRules', 'scenario_id', 'favourites', 'layout', 'countries', 'lifestyles', 'socialInterests', 'sous_type_loc', "page_title", "page_description", "page_meta_keyword", "typeMusics", "socialInterests", "guarAsked", "sports", 'proximities'));
            }
        } else {
            return view('searchlisting/basic/search-first-scen', compact('id', "address", "lat", "long", 'propertyTypes', 'propRules', 'scenario_id', 'favourites', 'layout', 'countries', 'lifestyles', 'socialInterests', 'sous_type_loc', "page_title", "page_description", "page_meta_keyword", "typeMusics", "socialInterests", "guarAsked", "sports", 'proximities'));
        }
    }

    private function updateAlert($id, $data)
    {
        DB::table("users_alertes")->where("id", $id)->update(
            [
                "user_id" => Auth::id(),
                "filtres" => serialize($data)
            ]
        );
    }

    private function saveAlert($data)
    {
        $serData = serialize($data);
        DB::table("users_alertes")->where("filtres", $serData)->where("user_id", Auth::id())->delete();
        DB::table("users_alertes")->insert(
            [
                "user_id" => Auth::id(),
                "filtres" => serialize($data)
            ]
        );
    }

    private function radius_query($lat, $long)
    {
        if (empty($long) || empty($lat)) {
            $lat = 48.8546;
            $long = 2.34771;
        }

        return '6371 * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $long . ')) + sin(radians(' . $lat . ')) * sin(radians(latitude))) ';
    }

    // ATO 3
    public function searchAdThirdScenario($id, $user_id, $lat, $long, $scenario_id, $address, $request, $layout, $location_id, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities)
    {
        //liste des villes à chercher
        $itemLat = $request->session()->get("itemLat");
        if (empty($long) || empty($lat)) {
            $lat = 48.8546;
            $long = 2.34771;
        }

        if (isUserSubscribed()) {
            if (Auth::user()->type_design == 1) {
                $view_name = "searchlisting/third-scen-data";
            } else {
                $view_name = "searchlisting/basic/third-scen-data";
            }
        } else {
            $view_name = "searchlisting/basic/third-scen-data";
        }
        $typeMusics = DB::table('user_type_music')->get();
        $page_title = $this->pageTitle($scenario_id, $address);
        $favourites = [];

        if ($request->isAlert == "1") {
            $data = $request->all();
            $data['address'] = $address;
            $data['scenario_id'] = $scenario_id;
            $data['latitude'] = $lat;
            $data['longitude'] = $long;
            if (!empty($request->idAlert)) {
                $this->updateAlert($request->idAlert, $data);
            } else {
                $this->saveAlert($data);
            }
        }


        $orderTop = "IF((is_top_list = 1 AND date_top_list >= NOW()), 1, 2) ASC";

        if ($layout == 'inner' && $user_id != 0) {
            if (count(Auth::user()->favorites) > 0) {
                foreach (Auth::user()->favorites as $favourite) {
                    array_push($favourites, $favourite->ad_id);
                }
            }
        }

        if (!empty($request->radius)) {
            $radius = $request->radius;
        } else {
            $radius = 40;
        }

        if (!empty($request->address)) {
            $address = $request->address;
        }

        if (!empty($request->lat)) {
            $lat = $request->lat;
        }

        if (!empty($request->lang)) {
            $long = $request->lang;
        }

        if ($scenario_id == 1) {
            $whereScenario = [['scenario_id', '1']];
        } elseif ($scenario_id == 2) {
            $whereScenario = [['scenario_id', '2']];
        }


        $radius_query_string = $this->radius_query($lat, $long);

        if (!empty($request->page)) {
            $request->session()->put("pageSearch", $request->page);
        }


        if (!empty($request->grille)) {
            $grille = $request->grille;
        } else {
            $grille = 1;
        }


        if ($request->ajax()) {
            $request->session()->put("SEARCH_FILTERS", $request->all());

            $school = $request->school;
            $smoker = $request->smoker;
            $country = $request->country;
            $city = $request->city;
            $common_friend = ($request->common_friend == "true") ? true : false;
            $with_image = $request->with_image;
            $urgent = $request->urgent;
            $whereRawArray = [];
            $sous_type_loc = $request->sous_type_loc;
            $age = $request->age;


            $property_types = !empty($request->property_types) ? json_decode($request->property_types) : [];

            $property_rules = !empty($request->property_rules) ? json_decode($request->property_rules) : [];

            $garanty = !empty($request->garanty) ? json_decode($request->garanty) : [];

            $lifestyles = !empty($request->lifestyle) ? json_decode($request->lifestyle) : [];

            $social_interests = !empty($request->social_interest) ? json_decode($request->social_interest) : [];

            $facilities = !empty($request->facilities) ? json_decode($request->facilities) : [];

            $visit_details = !empty($request->visiting_detail) ? json_decode($request->visiting_detail) : [];

            $gender = !empty($request->gender) ? json_decode($request->gender) : [];

            $occupation = !empty($request->occupation) ? json_decode($request->occupation) : [];

            $musics = !empty($request->musics) ? json_decode($request->musics) : [];

            $proximities = !empty($request->proximity) ? json_decode($request->proximity) : [];

            $query = Ads::where($whereScenario)->with('user.user_profiles')->with('ad_details')->with('nearby_facilities')->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }]);

            $selectRaw = 'ad_details.*, ads.*';


            if (!empty($request->sort)) {
                switch ($request->sort) {
                    case 2:
                        $orderBy = 'ads.min_rent ASC';
                        break;
                    case 3:
                        $orderBy = 'ads.min_rent DESC';
                        break;
                    case 4:
                        $orderBy = 'ads.updated_at ASC';
                        break;
                    default:
                        $orderBy = 'ads.updated_at DESC';
                        break;
                }
            } else {
                $orderBy = 'ads.updated_at DESC';
            }

            if (!empty($request->stat)) {
                switch ($request->stat) {
                    case 1:
                        $orderBy = 'ads.view';
                        break;
                    case 2:
                        $orderBy = 'ads.clic';
                        break;
                    case 3:
                        $orderBy = 'ads.message';
                        break;
                    case 4:
                        $orderBy = 'ads.toc_toc';
                        break;
                    case 5:
                        $orderBy = 'ads.phone';
                        break;
                    case 6:
                        $orderBy = 'ads.contact_fb';
                        break;
                }

                $orderBy .= ' ' . $request->type_stat;
            }

            if (!empty($age) || count($garanty) > 0 || count($occupation) > 0 || count($gender) > 0 || $sous_type_loc != 0 || !empty($with_image) || !empty($urgent) || count($property_types) > 0 || count($visit_details) > 0 || count($facilities) > 0 || $common_friend || !empty($school) || $smoker != 2 || !empty($city) || count($lifestyles) > 0 || count($social_interests) > 0 || !empty($request->bedrooms) || !empty($request->bathrooms) || !empty($request->kitchen) || (isset($request->kitchen) && $request->kitchen == 0) || !empty($request->furnished) || (isset($request->furnished) && $request->furnished == 0) || !empty($request->min_rent) || !empty($request->max_rent) || !empty($request->min_area) || !empty($request->max_area) || !empty($request->sort) || count($property_rules) > 0 || count($musics) > 0 || $request->alcool != 2 || count($proximities) > 0 || !empty($request->metro_line)) {
                $join_profil = false;
                $join_lifestyle = false;
                $join_music = false;
                $join_interest = false;
                $join_proximity = false;
                $join_details = false;
                $join_visit = false;
                $whereRaw = "1=1 ";
                if (!empty($school)) {
                    $whereRaw .= " AND UPPER(user_profiles.school) = ?";
                    $whereRawArray[] = trim(strtoupper($school));
                    $join_profil = true;
                }

                if (!empty($request->profession)) {
                    $whereRaw .= " AND UPPER(user_profiles.profession) = ?";
                    $whereRawArray[] = trim(strtoupper($request->profession));
                    $join_profil = true;
                }
                if (!empty($city)) {
                    $whereRaw .= " AND user_profiles.city = '" . $city . "'";
                    $join_profil = true;
                }
                if (!empty($smoker) && $smoker != 2) {
                    $whereRaw .= " AND user_profiles.smoker = '" . $smoker . "'";
                    $join_profil = true;
                }

                if (!empty($request->alcool) && $request->alcool != 2) {
                    $whereRaw .= " AND user_profiles.alcool = '" . $request->alcool . "'";
                    $join_profil = true;
                }

                if (!empty($with_image) && $with_image != 0) {
                    $whereRaw .= " AND (SELECT COUNT(*) FROM ad_files WHERE ad_id=ads.id)  > 0 ";
                }

                if (!empty($urgent) && $urgent != 0) {
                    $whereRaw .= " AND ads.is_logo_urgent=1 AND date_logo_urgent >= NOW() ";
                }

                if ($sous_type_loc != 0) {
                    $whereRaw .= " AND sous_type_loc =" . $sous_type_loc;
                }

                if (count($gender) > 0) {
                    $join_profil = true;
                    foreach ($gender as $i => $gen) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_profiles.sex = '" . $gen . "'";
                        } else {
                            $whereRaw .= " Or user_profiles.sex = '" . $gen . "'";
                        }
                    }
                    $whereRaw .= ") AND user_profiles.sex IS NOT NULL AND user_profiles.sex <> ''";
                }

                if (!empty($request->metro_line)) {
                    $whereRaw .= " AND ((select count(nearby_facilities.id) from nearby_facilities INNER JOIN metro_lines ON metro_lines.name=nearby_facilities.name where nearby_facilities.ad_id=ads.id and metro_lines.station_lines like '%|" . $request->metro_line . "|%') > 0)";
                }

                if (count($garanty) > 0) {
                    $whereRaw .= "AND (";
                    foreach ($garanty as $key => $gar) {
                        if ($key != 0) {
                            $whereRaw .= "OR ";
                        }
                        $whereRaw .= " (SELECT COUNT(*) FROM ad_to_guarantees WHERE ad_id=ads.id AND ad_to_guarantees.guarantees_id = " . $gar . ")";
                    }
                    $whereRaw .= ")";
                }

                if (count($occupation) > 0) {
                    $join_profil = true;
                    foreach ($occupation as $i => $occup) {
                        if ($i == 0) {
                            $whereRaw .= ' AND (user_profiles.professional_category = "' . $occup . '"';
                        } else {
                            $whereRaw .= ' Or user_profiles.professional_category = "' . $occup . '"';
                        }
                    }
                    $whereRaw .= ") AND user_profiles.professional_category IS NOT NULL AND user_profiles.professional_category <> ''";
                }

                if (count($property_rules) > 0) {
                    $whereRaw .= "AND (";
                    foreach ($property_rules as $key => $rule) {
                        if ($key != 0) {
                            $whereRaw .= "OR ";
                        }
                        $whereRaw .= " (SELECT COUNT(*) FROM ad_to_property_rules WHERE ad_id=ads.id AND property_rules_id = " . $rule . ")";
                    }
                    $whereRaw .= ")";
                }

                if (count($musics) > 0) {
                    $join_music = true;
                    foreach ($musics as $i => $music) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_music.music_id = " . $music;
                        } else {
                            $whereRaw .= " Or user_to_music.music_id = " . $music;
                        }
                    }
                    $whereRaw .= ")";
                }
                if (count($lifestyles) > 0) {
                    $join_lifestyle = true;
                    foreach ($lifestyles as $i => $lifestyle) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_lifestyles.lifestyle_id = " . $lifestyle;
                        } else {
                            $whereRaw .= " Or user_to_lifestyles.lifestyle_id = " . $lifestyle;
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($proximities) > 0) {
                    $join_proximity = true;
                    foreach ($proximities as $i => $proximity) {
                        if ($i == 0) {
                            $whereRaw .= " AND (ad_proximity.point_proximity_id = " . $proximity;
                        } else {
                            $whereRaw .= " Or ad_proximity.point_proximity_id = " . $proximity;
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($social_interests) > 0) {
                    $join_interest = true;
                    foreach ($social_interests as $i => $social) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_social_interests.social_interest_id = " . $social;
                        } else {
                            $whereRaw .= " Or user_to_social_interests.social_interest_id = " . $social;
                        }
                    }
                    $whereRaw .= ")";
                }

                if ($common_friend) {
                    $whereRaw .= " AND ((select count(fb_friend_lists.id) from fb_friend_lists where fb_friend_lists.fb_friend_id in (SELECT fb_friend_id FROM fb_friend_lists WHERE user_id=" . $user_id . ") and fb_friend_lists.user_id=ads.user_id ) > 0)";
                }

                if (count($facilities) > 0) {
                    foreach ($facilities as $i => $facility) {
                        if ($i == 0) {
                            $whereRaw .= " AND ((SELECT COUNT(nearby_facilities.id) FROM nearby_facilities WHERE nearby_facilities.latitude=" . $facility->lat . " AND nearby_facilities.longitude=" . $facility->lng . " AND nearby_facilities.ad_id = ads.id) > 0";
                        } else {
                            $whereRaw .= " OR (SELECT COUNT(nearby_facilities.id) FROM nearby_facilities WHERE nearby_facilities.latitude=" . $facility->lat . " AND nearby_facilities.longitude=" . $facility->lng . "AND nearby_facilities.ad_id = ads.id) > 0";
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($visit_details) > 0) {
                    $join_visit = true;
                    foreach ($visit_details as $i => $visit) {
                        $first = true;
                        if ($i == 0) {
                            if (isset($visit->date_visit)) {
                                $whereRaw .= " AND ((ad_visiting_details.visiting_date = '" . $visit->date_visit . "'";
                                $first = false;
                            }
                            if (isset($visit->start_time) && !empty($visit->start_time)) {
                                if ($first) {
                                    $whereRaw .= " AND ((ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                }
                            }
                            if (isset($visit->end_time) && !empty($visit->end_time)) {
                                if ($first) {
                                    $whereRaw .= " AND ((ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                }
                            }
                        } else {
                            if (isset($visit->date_visit)) {
                                $whereRaw .= " OR (ad_visiting_details.visiting_date = '" . $visit->date_visit . "'";
                                $first = false;
                            }
                            if (isset($visit->start_time) && !empty($visit->start_time)) {
                                if ($first) {
                                    $whereRaw .= " OR (ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                }
                            }
                            if (isset($visit->end_time) && !empty($visit->end_time)) {
                                if ($first) {
                                    $whereRaw .= " OR (ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                }
                            }
                        }
                        $whereRaw .= ")";
                    }
                    $whereRaw .= ")";
                }

                $where = [];
                if (!empty($request->bedrooms)) {
                    $join_details = true;
                    array_push($where, ['ad_details.bedrooms', '>=', $request->bedrooms]);
                }
                if (!empty($request->bathrooms)) {
                    $join_details = true;
                    array_push($where, ['ad_details.bathrooms', '>=', $request->bathrooms]);
                }

                if (!empty($request->kitchen) || (isset($request->kitchen) && $request->kitchen == 0)) {
                    $join_details = true;
                    if (isset($request->kitchen) && $request->kitchen == 0) {
                        array_push($where, ['ad_details.kitchen_separated', null]);
                    } else {
                        array_push($where, ['ad_details.kitchen_separated', $request->kitchen]);
                    }
                }
                if (!empty($request->furnished) || (isset($request->furnished) && $request->furnished == 0)) {
                    $join_details = true;
                    array_push($where, ['ad_details.furnished', $request->furnished]);
                }

                if (!empty($request->min_rent) && empty($request->max_rent)) {
                    array_push($where, ['ads.min_rent', '>=', $request->min_rent]);
                }

                if (!empty($request->max_rent) && empty($request->min_rent)) {
                    array_push($where, ['ads.min_rent', '<=', $request->max_rent]);
                }

                if (!empty($request->min_rent) && !empty($request->max_rent)) {
                    array_push($where, ['ads.min_rent', '>=', $request->min_rent]);
                    array_push($where, ['ads.min_rent', '<=', $request->max_rent]);
                }

                if (!empty($request->min_area)) {
                    array_push($where, ['ad_details.min_surface_area', '!=', 0]);
                    array_push($where, ['ad_details.min_surface_area', '>=', $request->min_area]);
                }

                if (!empty($request->max_area)) {
                    array_push($where, ['ad_details.min_surface_area', '!=', 0]);
                    array_push($where, ['ad_details.min_surface_area', '<=', $request->max_area]);
                }

                $query = ($join_profil) ? $query->leftJoin('user_profiles', 'user_profiles.user_id', 'ads.user_id') : $query;
                $query = ($join_lifestyle) ? $query->leftJoin('user_to_lifestyles', 'user_to_lifestyles.user_id', 'ads.user_id') : $query;
                $query = ($join_music) ? $query->leftJoin('user_to_music', 'user_to_music.user_id', 'ads.user_id') : $query;
                $query = ($join_interest) ? $query->leftJoin('user_to_social_interests', 'user_to_social_interests.user_id', 'ads.user_id') : $query;
                $query = ($join_proximity) ? $query->leftJoin('ad_proximity', 'ad_proximity.ad_id', 'ads.id') : $query;
                $query = ($join_visit) ? $query->leftJoin('ad_visiting_details', 'ad_visiting_details.ad_id', 'ads.id') : $query;
                $query = $query->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->join('ad_details', 'ad_details.ad_id', '=', 'ads.id');

                if (count($where) > 0) {
                    if (count($property_types) > 0) {
                        $ads = $query->select(DB::raw($selectRaw))
                            ->where($whereScenario)
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->where('status', '1')
                            ->where('admin_approve', '1')
                            ->whereRaw($radius_query_string . '<= ' . $radius)
                            ->whereRaw($whereRaw, $whereRawArray)
                            ->whereIn('ad_details.property_type_id', $property_types)
                            ->where($where)
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->groupBy('ads.id')
                            ->paginate($this->perpage);
                    } else {
                        $ads = $query->select(DB::raw($selectRaw))
                            ->where($whereScenario)
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->where('status', '1')
                            ->where('admin_approve', '1')
                            ->whereRaw($whereRaw, $whereRawArray)
                            ->whereRaw($radius_query_string . '<= ' . $radius)
                            ->where($where)
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            //->groupBy('ads.id')
                            ->paginate($this->perpage);
                    }
                } else {
                    if (count($property_types) > 0) {
                        $ads = $query->select(DB::raw($selectRaw))
                            ->where($whereScenario)
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->where('status', '1')
                            ->where('admin_approve', '1')
                            ->whereRaw($whereRaw, $whereRawArray)
                            ->whereRaw($radius_query_string . '<= ' . $radius)
                            ->whereIn('ad_details.property_type_id', $property_types)
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->paginate($this->perpage);
                    } else {
                        $query = $query->select(DB::raw($selectRaw))
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->where('status', '1')
                            ->where('admin_approve', '1')
                            ->whereRaw($whereRaw, $whereRawArray);

                        if (isset($itemLat)) {
                            if (count($itemLat) > 1) {
                                $i = 0;
                                foreach ($itemLat as $key => $value) {
                                    $lat_ = $value["lat"];
                                    $log_ = $value["log"];
                                    if ($i == 0) {
                                        $query = $query->WhereRaw("{$this->radius_query($lat_,$log_)} < ?", [$radius])->where($whereScenario)->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour);
                                    } else {
                                        $query = $query->orWhereRaw("{$this->radius_query($lat_,$log_)} < ?", [$radius])->where($whereScenario)->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour);
                                    }
                                    ++$i;
                                }
                            } else {
                                foreach ($itemLat as $key => $value) {
                                    $lat_ = $value["lat"];
                                    $log_ = $value["log"];
                                    $query = $query->WhereRaw("{$this->radius_query($lat_,$log_)} < ?", [$radius])->where($whereScenario)->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour);
                                }
                            }
                        } else {
                            $query = $query->WhereRaw("{$radius_query_string} < ?", [$radius])->where($whereScenario)->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour);
                        }
                        $ads = $query->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->paginate($this->perpage);


                        $isads = $query->groupBy('ads.id')
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->exists();
                    }
                }
            } else {
                $ads = Ads::with('user.user_profiles')->with('ad_details')->with('nearby_facilities')->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')->orderBy('ordre', 'asc');
                }])->join('ad_details', 'ad_details.ad_id', '=', 'ads.id')->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->whereRaw($radius_query_string . '<= ' . $radius)->orderByRaw($orderTop)->orderByRaw($orderBy)->paginate($this->perpage);
                $isads = Ads::with('user.user_profiles')->with('ad_details')->with('nearby_facilities')->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')->orderBy('ordre', 'asc');
                }])->join('ad_details', 'ad_details.ad_id', '=', 'ads.id')->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->whereRaw($radius_query_string . '<= ' . $radius)->orderByRaw($orderTop)->orderByRaw($orderBy)->exists();
            }
            if (!empty($isads)) {
                if ($isads == false) {
                    Session::flash('message', __("alert.no_ads_found"));
                    return back()->withErrors(['field_name' => ['Your custom message here.']]);
                }
            }

            $filterAdsCommunity = array();
            $now = Carbon::now();
            $nbrJourAdsCommutyExp = $now->addDays(-$this->nbJourAdsCommunity);

            return view($view_name . '-all', compact('id', 'itemLat', 'ads', 'address', "lat", "long", "scenario_id", 'favourites', 'layout', 'proximities', 'grille', 'nbrJourAdsCommutyExp'));
        }
        saveUserSearch($address, $scenario_id);
        $ads = Ads::has('user')->with('user.user_profiles')->with('ad_details')->with('nearby_facilities')->with(['ad_files' => function ($query) {
            $query->where('media_type', '0')->orderBy('id', 'asc');
        }])->join('ad_details', 'ad_details.ad_id', '=', 'ads.id')->select(DB::raw($radius_query_string . 'as distance, ad_details.*, ads.*'))->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->whereRaw($radius_query_string . '<= ' . $radius)->orderByRaw($orderTop)->orderByRaw('ads.updated_at DESC')->orderBy('distance', 'ASC')->paginate($this->perpage);


        $propertyTypes = $this->master->getMasters('property_types');
        $propRules = $this->master->getMasters('property_rules');
        $page_description = searchPageDescription($scenario_id, $address);
        $page_meta_keyword = searchPageKeyword($scenario_id, $address);

        //deplacer form 3 dans la page liste d'annonce
        $socialInterests = $this->master->getMasters('social_interests');
        $guarAsked = $this->master->getMasters('guarantees');
        $sports = $this->master->getMasters('user_sport');


        if (isUserSubscribed()) {
            if (Auth::user()->type_design == 1) {
                return view('searchlisting/search-third-scen', compact('itemLat', 'id', 'propertyTypes', 'propRules', 'scenario_id', 'address', 'lat', 'long', 'radius', 'favourites', 'layout', 'location_id', 'countries', 'lifestyles', 'socialInterests', 'sous_type_loc', "page_title", "page_description", "page_meta_keyword", "typeMusics", 'socialInterests', 'guarAsked', 'sports', 'proximities', 'grille'));
            } else {
                return view('searchlisting/basic/search-third-scen', compact('itemLat', 'id', 'propertyTypes', 'propRules', 'scenario_id', 'address', 'lat', 'long', 'radius', 'favourites', 'layout', 'location_id', 'countries', 'lifestyles', 'socialInterests', 'sous_type_loc', "page_title", "page_description", "page_meta_keyword", "typeMusics", 'socialInterests', 'guarAsked', 'sports', 'proximities', 'grille'));
            }
        } else {
            return view('searchlisting/basic/search-third-scen', compact('itemLat', 'id', 'propertyTypes', 'propRules', 'scenario_id', 'address', 'lat', 'long', 'radius', 'favourites', 'layout', 'location_id', 'countries', 'lifestyles', 'socialInterests', 'sous_type_loc', "page_title", "page_description", "page_meta_keyword", "typeMusics", 'socialInterests', 'guarAsked', 'sports', 'proximities', 'grille'));
        }
    }

    public function type_zone($type, $var = null)
    {
        if ($type_zone == 0) /*zone de recherche radius*/ {
            $whereRaw .= " AND (" . $var . '<= ' . $type_zone . ")";
        }

        if ($type_zone == 1 || $var == 2) /*zone de recherche dessin*/ {
            if (count($polylines) > 0) {
                foreach ($polylines as $i => $polyline) {
                    if ($i == 0) {
                        $whereRaw .= " AND (MBRCoveredBy(POINT(ads.latitude, ads.longitude), ST_GEOMFROMTEXT('" . $this->constructLineString($polyline) . "')) = 1";
                    } else {
                        $whereRaw .= " OR MBRCoveredBy(POINT(ads.latitude, ads.longitude), ST_GEOMFROMTEXT('" . $this->constructLineString($polyline) . "')) = 1";
                    }
                }
                $whereRaw .= ")";
            }
        }
    }

    public function searchAdThirdScenarioMap($id, $user_id, $lat, $long, $scenario_id, $address, $request, $layout, $location_id, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities)
    {
        if (isUserSubscribed()) {
            if (Auth::user()->type_design == 1) {
                $view_name = "searchlisting/third-scen-data-map";
            } else {
                $view_name = "searchlisting/basic/third-scen-data-map";
            }
        } else {
            $view_name = "searchlisting/basic/third-scen-data-map";
        }
        $nbLimitMap = 6000;
        $typeMusics = DB::table('user_type_music')->get();
        $map = true;
        $response = array();
        $page_title = $this->pageTitle($scenario_id, $address);
        $orderTop = "IF((is_top_list = 1 AND date_top_list >= NOW()), 1, 2) ASC";
        $favourites = [];

        if ($layout == 'inner') {
            if (count(Auth::user()->favorites) > 0) {
                foreach (Auth::user()->favorites as $favourite) {
                    array_push($favourites, $favourite->ad_id);
                }
            }
        }

        if (!empty($request->radius)) {
            $radius = $request->radius > 4 ? $request->radius : 4;
        } else {
            $radius = 40;
        }

        if (!empty($request->lat)) {
            $lat = $request->lat;
        }

        if (!empty($request->lang)) {
            $long = $request->lang;
        }

        if ($scenario_id == 1) {
            $whereScenario = [['scenario_id', '1']];
        } elseif ($scenario_id == 2) {
            $whereScenario = [['scenario_id', '2']];
        } elseif ($scenario_id == 3) {
            $whereScenario = [['scenario_id', '3']];
        } elseif ($scenario_id == 4) {
            $whereScenario = [['scenario_id', '4']];
        } elseif ($scenario_id == 5) {
            $whereScenario = [['scenario_id', '5']];
        }

        $radius_query_string = $this->radius_query($lat, $long);


        if ($request->ajax()) {
            $request->session()->put("SEARCH_FILTERS", $request->all());

            $school = $request->school;
            $smoker = $request->smoker;
            $country = $request->country;
            $city = $request->city;
            $common_friend = ($request->common_friend == "true") ? true : false;
            $with_image = $request->with_image;
            $urgent = $request->urgent;
            $whereRawArray = [];
            $sous_type_loc = $request->sous_type_loc;
            $age = $request->age;


            $property_types = !empty($request->property_types) ? json_decode($request->property_types) : [];

            $property_rules = !empty($request->property_rules) ? json_decode($request->property_rules) : [];

            $garanty = !empty($request->garanty) ? json_decode($request->garanty) : [];

            $lifestyles = !empty($request->lifestyle) ? json_decode($request->lifestyle) : [];

            $social_interests = !empty($request->social_interest) ? json_decode($request->social_interest) : [];

            $facilities = !empty($request->facilities) ? json_decode($request->facilities) : [];

            $visit_details = !empty($request->visiting_detail) ? json_decode($request->visiting_detail) : [];

            $gender = !empty($request->gender) ? json_decode($request->gender) : [];

            $occupation = !empty($request->occupation) ? json_decode($request->occupation) : [];

            $musics = !empty($request->musics) ? json_decode($request->musics) : [];

            $proximities = !empty($request->proximity) ? json_decode($request->proximity) : [];

            //$type_zone = !empty($request->type_zone) ? json_decode($request->type_zone) : [];

            $notInData = [];
            if (isset($request->excluded)) {
                $notInData = $request->excluded;
            }
            if (count($proximities) > 0 || count($garanty) > 0 || !empty($age) || count($property_types) > 0 || count($gender) > 0 || $sous_type_loc != 0 || count($occupation) > 0 || !empty($request->bedrooms) || !empty($request->bathrooms) || !empty($request->kitchen) || (isset($request->kitchen) && $request->kitchen == 0) || !empty($request->furnished) || (isset($request->furnished) && $request->furnished == 0) || !empty($request->min_rent) || !empty($request->max_rent) || !empty($request->sort) || !empty($with_image) || !empty($urgent) || !empty($school) || !empty($city) || $smoker != 2 || count($lifestyles) > 0 || count($social_interests) > 0 || !empty($request->min_area) || !empty($request->max_area) || count($facilities) > 0 || $common_friend || count($property_rules) > 0 || count($polylines) > 0 || !empty($type_zone) || count($musics) > 0 || $request->alcool != 2 || !empty($request->metro_line)) {
                $join_profil = false;
                $join_lifestyle = false;
                $join_music = false;
                $join_interest = false;
                $join_proximity = false;
                $join_details = false;
                $join_visit = false;
                $whereRaw = "1=1 ";
                if (!empty($school)) {
                    $whereRaw .= " AND UPPER(user_profiles.school) = ?";
                    $whereRawArray[] = trim(strtoupper($school));
                    $join_profil = true;
                }

                if (!empty($request->profession)) {
                    $whereRaw .= " AND UPPER(user_profiles.profession) = ?";
                    $whereRawArray[] = trim(strtoupper($request->profession));
                    $join_profil = true;
                }
                if (!empty($city)) {
                    $whereRaw .= " AND user_profiles.city = '" . $city . "'";
                    $join_profil = true;
                }
                if (!empty($smoker) && $smoker != 2) {
                    $whereRaw .= " AND user_profiles.smoker = '" . $smoker . "'";
                    $join_profil = true;
                }

                if (!empty($request->alcool) && $request->alcool != 2) {
                    $whereRaw .= " AND user_profiles.alcool = '" . $request->alcool . "'";
                    $join_profil = true;
                }

                if (!empty($with_image) && $with_image != 0) {
                    $whereRaw .= " AND (SELECT COUNT(*) FROM ad_files WHERE ad_id=ads.id)  > 0 ";
                }

                if (!empty($urgent) && $urgent != 0) {
                    $whereRaw .= " AND ads.is_logo_urgent=1 AND date_logo_urgent >= NOW() ";
                }

                if ($sous_type_loc != 0) {
                    $whereRaw .= " AND sous_type_loc =" . $sous_type_loc;
                }

                if (count($gender) > 0) {
                    $join_profil = true;
                    foreach ($gender as $i => $gen) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_profiles.sex = '" . $gen . "'";
                        } else {
                            $whereRaw .= " Or user_profiles.sex = '" . $gen . "'";
                        }
                    }
                    $whereRaw .= ") AND user_profiles.sex IS NOT NULL AND user_profiles.sex <> ''";
                }

                if (!empty($request->metro_line)) {
                    $whereRaw .= " AND ((select count(nearby_facilities.id) from nearby_facilities INNER JOIN metro_lines ON metro_lines.name=nearby_facilities.name where nearby_facilities.ad_id=ads.id and metro_lines.station_lines like '%|" . $request->metro_line . "|%') > 0)";
                }

                if (count($garanty) > 0) {
                    $whereRaw .= "AND (";
                    foreach ($garanty as $key => $gar) {
                        if ($key != 0) {
                            $whereRaw .= "OR ";
                        }
                        $whereRaw .= " (SELECT COUNT(*) FROM ad_to_guarantees WHERE ad_id=ads.id AND ad_to_guarantees.guarantees_id = " . $gar . ")";
                    }
                    $whereRaw .= ")";
                }

                if (count($occupation) > 0) {
                    $join_profil = true;
                    foreach ($occupation as $i => $occup) {
                        if ($i == 0) {
                            $whereRaw .= ' AND (user_profiles.professional_category = "' . $occup . '"';
                        } else {
                            $whereRaw .= ' Or user_profiles.professional_category = "' . $occup . '"';
                        }
                    }
                    $whereRaw .= ") AND user_profiles.professional_category IS NOT NULL AND user_profiles.professional_category <> ''";
                }

                if (count($property_rules) > 0) {
                    $whereRaw .= "AND (";
                    foreach ($property_rules as $key => $rule) {
                        if ($key != 0) {
                            $whereRaw .= "OR ";
                        }
                        $whereRaw .= " (SELECT COUNT(*) FROM ad_to_property_rules WHERE ad_id=ads.id AND property_rules_id = " . $rule . ")";
                    }
                    $whereRaw .= ")";
                }

                if (count($musics) > 0) {
                    $join_music = true;
                    foreach ($musics as $i => $music) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_music.music_id = " . $music;
                        } else {
                            $whereRaw .= " Or user_to_music.music_id = " . $music;
                        }
                    }
                    $whereRaw .= ")";
                }
                if (count($lifestyles) > 0) {
                    $join_lifestyle = true;
                    foreach ($lifestyles as $i => $lifestyle) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_lifestyles.lifestyle_id = " . $lifestyle;
                        } else {
                            $whereRaw .= " Or user_to_lifestyles.lifestyle_id = " . $lifestyle;
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($proximities) > 0) {
                    $join_proximity = true;
                    foreach ($proximities as $i => $proximity) {
                        if ($i == 0) {
                            $whereRaw .= " AND (ad_proximity.point_proximity_id = " . $proximity;
                        } else {
                            $whereRaw .= " Or ad_proximity.point_proximity_id = " . $proximity;
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($social_interests) > 0) {
                    $join_interest = true;
                    foreach ($social_interests as $i => $social) {
                        if ($i == 0) {
                            $whereRaw .= " AND (user_to_social_interests.social_interest_id = " . $social;
                        } else {
                            $whereRaw .= " Or user_to_social_interests.social_interest_id = " . $social;
                        }
                    }
                    $whereRaw .= ")";
                }

                if ($common_friend) {
                    $whereRaw .= " AND ((select count(fb_friend_lists.id) from fb_friend_lists where fb_friend_lists.fb_friend_id in (SELECT fb_friend_id FROM fb_friend_lists WHERE user_id=" . $user_id . ") and fb_friend_lists.user_id=ads.user_id ) > 0)";
                }

                if (count($facilities) > 0) {
                    foreach ($facilities as $i => $facility) {
                        if ($i == 0) {
                            $whereRaw .= " AND ((SELECT COUNT(nearby_facilities.id) FROM nearby_facilities WHERE nearby_facilities.latitude=" . $facility->lat . " AND nearby_facilities.longitude=" . $facility->lng . " AND nearby_facilities.ad_id = ads.id) > 0";
                        } else {
                            $whereRaw .= " OR (SELECT COUNT(nearby_facilities.id) FROM nearby_facilities WHERE nearby_facilities.latitude=" . $facility->lat . " AND nearby_facilities.longitude=" . $facility->lng . "AND nearby_facilities.ad_id = ads.id) > 0";
                        }
                    }
                    $whereRaw .= ")";
                }

                if (count($visit_details) > 0) {
                    $join_visit = true;
                    foreach ($visit_details as $i => $visit) {
                        $first = true;
                        if ($i == 0) {
                            if (isset($visit->date_visit)) {
                                $whereRaw .= " AND ((ad_visiting_details.visiting_date = '" . $visit->date_visit . "'";
                                $first = false;
                            }
                            if (isset($visit->start_time) && !empty($visit->start_time)) {
                                if ($first) {
                                    $whereRaw .= " AND ((ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                }
                            }
                            if (isset($visit->end_time) && !empty($visit->end_time)) {
                                if ($first) {
                                    $whereRaw .= " AND ((ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                }
                            }
                        } else {
                            if (isset($visit->date_visit)) {
                                $whereRaw .= " OR (ad_visiting_details.visiting_date = '" . $visit->date_visit . "'";
                                $first = false;
                            }
                            if (isset($visit->start_time) && !empty($visit->start_time)) {
                                if ($first) {
                                    $whereRaw .= " OR (ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.start_time = '" . $visit->start_time . "'";
                                }
                            }
                            if (isset($visit->end_time) && !empty($visit->end_time)) {
                                if ($first) {
                                    $whereRaw .= " OR (ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                    $first = false;
                                } else {
                                    $whereRaw .= " AND ad_visiting_details.end_time = '" . $visit->end_time . "'";
                                }
                            }
                        }
                        $whereRaw .= ")";
                    }
                    $whereRaw .= ")";
                }
                if (!empty($request->sort)) {
                    if ($request->sort == 3) {
                        $orderBy = 'ads.min_rent DESC';
                    } elseif ($request->sort == 2) {
                        $orderBy = 'ads.min_rent ASC';
                    } else {
                        $orderBy = 'ads.updated_at DESC';
                    }
                } else {
                    $orderBy = 'ads.updated_at DESC';
                }

                $where = [];
                if (!empty($request->bedrooms)) {
                    $join_details = true;
                    array_push($where, ['ad_details.bedrooms', '>=', $request->bedrooms]);
                }
                if (!empty($request->bathrooms)) {
                    $join_details = true;
                    array_push($where, ['ad_details.bathrooms', '>=', $request->bathrooms]);
                }

                if (!empty($request->kitchen) || (isset($request->kitchen) && $request->kitchen == 0)) {
                    $join_details = true;
                    if (isset($request->kitchen) && $request->kitchen == 0) {
                        array_push($where, ['ad_details.kitchen_separated', null]);
                    } else {
                        array_push($where, ['ad_details.kitchen_separated', $request->kitchen]);
                    }
                }
                if (!empty($request->furnished) || (isset($request->furnished) && $request->furnished == 0)) {
                    $join_details = true;
                    array_push($where, ['ad_details.furnished', $request->furnished]);
                }

                if (!empty($request->min_rent) && empty($request->max_rent)) {
                    array_push($where, ['ads.min_rent', '>=', $request->min_rent]);
                }

                if (!empty($request->max_rent) && empty($request->min_rent)) {
                    array_push($where, ['ads.min_rent', '<=', $request->max_rent]);
                }

                if (!empty($request->min_rent) && !empty($request->max_rent)) {
                    array_push($where, ['ads.min_rent', '>=', $request->min_rent]);
                    array_push($where, ['ads.min_rent', '<=', $request->max_rent]);
                }

                if (!empty($request->min_area)) {
                    array_push($where, ['ad_details.min_surface_area', '!=', 0]);
                    array_push($where, ['ad_details.min_surface_area', '>=', $request->min_area]);
                }

                if (!empty($request->max_area)) {
                    array_push($where, ['ad_details.min_surface_area', '!=', 0]);
                    array_push($where, ['ad_details.min_surface_area', '<=', $request->max_area]);
                }
                $query = Ads::with('ad_details')->with('nearby_facilities')->with(['user.user_profiles', 'user.user_packages' => function ($query) {
                    $query->orderBy('id', 'desc')->first();
                }])->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')
                        ->orderBy('ordre', 'asc');
                }]);
                $query = ($join_profil) ? $query->leftJoin('user_profiles', 'user_profiles.user_id', 'ads.user_id') : $query;
                $query = ($join_lifestyle) ? $query->leftJoin('user_to_lifestyles', 'user_to_lifestyles.user_id', 'ads.user_id') : $query;
                $query = ($join_music) ? $query->leftJoin('user_to_music', 'user_to_music.user_id', 'ads.user_id') : $query;
                $query = ($join_interest) ? $query->leftJoin('user_to_social_interests', 'user_to_social_interests.user_id', 'ads.user_id') : $query;
                $query = ($join_proximity) ? $query->leftJoin('ad_proximity', 'ad_proximity.ad_id', 'ads.id') : $query;
                $query = ($join_visit) ? $query->leftJoin('ad_visiting_details', 'ad_visiting_details.ad_id', 'ads.id') : $query;
                $query = $query->join('ad_details', 'ad_details.ad_id', '=', 'ads.id');

                if ($type_zone == 0) /*zone de recherche radius*/ {
                    $whereRaw .= " AND (" . $radius_query_string . '<= ' . $radius . ")";
                }

                if ($type_zone == 1 || $type_zone == 2) /*zone de recherche dessin*/ {
                    if (count($polylines) > 0) {
                        foreach ($polylines as $i => $polyline) {
                            if ($i == 0) {
                                $whereRaw .= " AND (MBRCoveredBy(POINT(ads.latitude, ads.longitude), ST_GEOMFROMTEXT('" . $this->constructLineString($polyline) . "')) = 1";
                            } else {
                                $whereRaw .= " OR MBRCoveredBy(POINT(ads.latitude, ads.longitude), ST_GEOMFROMTEXT('" . $this->constructLineString($polyline) . "')) = 1";
                            }
                        }
                        $whereRaw .= ")";
                    }
                }


                if ($common_friend) {
                    $whereRaw .= " AND ((select count(fb_friend_lists.id) from fb_friend_lists where fb_friend_lists.fb_friend_id in (SELECT fb_friend_id FROM fb_friend_lists WHERE user_id=" . $user_id . ") and fb_friend_lists.user_id=ads.user_id ) > 0)";
                }

                if (count($where) > 0) {
                    if (count($property_types) > 0) {
                        $tempAds = $query->select(DB::raw($radius_query_string . 'as distance, ad_details.*, ads.*'))
                            ->where($whereScenario)
                            ->where('status', '1')
                            ->where('admin_approve', '1')
                            ->whereRaw($whereRaw, $whereRawArray)
                            ->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)
                            ->whereIn('ad_details.property_type_id', $property_types)
                            ->where($where)
                            ->whereNotIn('ads.id', $notInData)
                            ->limit($nbLimitMap)
                            ->groupBy('ads.id')
                            ->orderByRaw($orderTop)
                            ->orderByRaw($orderBy)
                            ->orderBy('distance', 'ASC');
                    } else {
                        $tempAds = $query->select(DB::raw($radius_query_string . 'as distance, ad_details.*, ads.*'))->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->whereRaw($whereRaw, $whereRawArray)->where($where)->whereNotIn('ads.id', $notInData)->groupBy('ads.id')->limit($nbLimitMap)->orderByRaw($orderTop)->orderByRaw($orderBy)->orderBy('distance', 'ASC');
                    }
                } else {
                    if (count($property_types) > 0) {
                        $tempAds = $query->select(DB::raw($radius_query_string . 'as distance, ad_details.*, ads.*'))->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->whereRaw($whereRaw, $whereRawArray)->whereIn('ad_details.property_type_id', $property_types)->whereNotIn('ads.id', $notInData)->limit($nbLimitMap)->groupBy('ads.id')->orderByRaw($orderTop)->orderByRaw($orderBy)->orderBy('distance', 'ASC');
                    } else {
                        $tempAds = $query->select(DB::raw($radius_query_string . 'as distance, ad_details.*, ads.*'))->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->whereRaw($whereRaw, $whereRawArray)->whereNotIn('ads.id', $notInData)->limit($nbLimitMap)->groupBy('ads.id')->orderByRaw($orderTop)->orderByRaw($orderBy)->orderBy('distance', 'ASC');
                    }
                }
            } else {
                if (!empty($request->sort)) {
                    if ($request->sort == 3) {
                        $orderBy = 'ads.min_rent DESC';
                    } elseif ($request->sort == 2) {
                        $orderBy = 'ads.min_rent ASC';
                    } elseif ($request->sort == 4) {
                        $orderBy = 'ads.updated_at ASC';
                    } else {
                        $orderBy = 'ads.updated_at DESC';
                    }
                } else {
                    $orderBy = 'ads.updated_at DESC';
                }
                $tempAds = Ads::with('ad_details')->with('nearby_facilities')->with(['user.user_profiles', 'user.user_packages' => function ($query) {
                    $query->orderBy('id', 'desc')->first();
                }])->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')->orderBy('ordre', 'asc');
                }])->join('ad_details', 'ad_details.ad_id', '=', 'ads.id')->select(DB::raw($radius_query_string . 'as distance, ad_details.*, ads.*'))->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereNotIn('ads.id', $notInData)->limit($nbLimitMap)->groupBy('ads.id')->orderByRaw($orderTop)->orderByRaw($orderBy)->orderBy('distance', 'ASC');
            }

            $AllAds = $tempAds->get();
            $ads = $tempAds->paginate($this->perpage);
            $totalResults = $ads->total();
            $currentPage = $ads->currentPage();
            $count = $ads->count();
            $perPage = $ads->perPage();

            $countFrom = (($currentPage - 1) * $perPage) + 1;
            $countTo = $countFrom + $count - 1;

            if ($countTo < $countFrom) {
                $countFrom = 0;
            }
            $view = View::make($view_name, compact('id', 'ads', 'favourites', 'layout'));

            $viewContent = $view->render();

            $response['lat'] = $lat;
            $response['long'] = $long;

            $response['ads'] = array();

            $latlong = array();


            foreach ($AllAds as $key => $ad) {
                if (in_array($ad->latitude . "," . $ad->longitude, $latlong)) {
                    $key = array_search($ad->latitude . "," . $ad->longitude, $latlong);
                    if (count($ad->ad_files) > 0) {
                        $property_pic = '/uploads/images_annonces/' . $ad->ad_files[0]->filename;
                    } else {
                        $property_pic = '';
                    }
                    if (date_create($ad->available_date) > date_create('today')) {
                        $availablity = __('searchlisting.available') . ' ' . date('j M', strtotime($ad->available_date));
                    } else {
                        $availablity = __('searchlisting.available_now');
                    }

                    if (!empty($ad->user->user_packages) && count($ad->user->user_packages) > 0) {
                        if (strtotime(date('Y-m-d')) > strtotime($ad->user->user_packages[0]->end_date)) {
                            $ad_premium = __('searchlisting.membre_basique');
                        } else {
                            $ad_premium = __('searchlisting.membre_premium');
                        }
                    } else {
                        $ad_premium = __('searchlisting.membre_basique');
                    }

                    array_push($response['ads'][$key]['details'], ["id" => $ad->id, 'href' => adUrl($ad->ad_id, $id), 'price' => $ad->min_rent, 'property_pic' => $property_pic, 'availablity' => $availablity, 'bed' => '<b>' . $ad->bedrooms . '</b> ' . __("searchlisting.bedroom"), 'bath' => '<b>' . $ad->bathrooms . '</b> ' . __("searchlisting.bathroom"), 'posted' => __("searchlisting.posted") . ' ' . translateDuration($ad->updated_at), 'ad_premium' => $ad_premium, "bed_number" => $ad->bedrooms, "bath_number" => $ad->bathrooms]);
                } else {
                    if (count($ad->ad_files) > 0) {
                        $property_pic = '/uploads/images_annonces/' . $ad->ad_files[0]->filename;
                    } else {
                        $property_pic = '';
                    }
                    if (date_create($ad->available_date) > date_create('today')) {
                        $availablity = __('searchlisting.available') . ' ' . date('j M', strtotime($ad->available_date));
                    } else {
                        $availablity = __('searchlisting.available_now');
                    }

                    if (!empty($ad->user->user_packages) && count($ad->user->user_packages) > 0) {
                        if (strtotime(date('Y-m-d')) > strtotime($ad->user->user_packages[0]->end_date)) {
                            $ad_premium = __('searchlisting.membre_basique');
                        } else {
                            $ad_premium = __('searchlisting.membre_premium');
                        }
                    } else {
                        $ad_premium = __('searchlisting.membre_basique');
                    }

                    $response['ads'][] = ['lat' => $ad->latitude, 'long' => $ad->longitude, 'details' => [["id" => $ad->id, 'href' => adUrl($ad->ad_id, $id), 'price' => $ad->min_rent, 'property_pic' => $property_pic, 'availablity' => $availablity, 'bed' => '<b>' . $ad->bedrooms . '</b> ' . __("searchlisting.bedroom"), 'bath' => '<b>' . $ad->bathrooms . '</b> ' . __("searchlisting.bathroom"), 'posted' => __("searchlisting.posted") . ' ' . translateDuration($ad->updated_at), 'ad_premium' => $ad_premium, "bed_number" => $ad->bedrooms, "bath_number" => $ad->bathrooms]]];


                    $latlong[] = $ad->latitude . "," . $ad->longitude;
                }
            }

            $finalResponse = ['htmlResponse' => $viewContent, 'mapResponse' => $response, 'countFrom' => $countFrom, 'countTo' => $countTo, 'count' => $count, 'totalResults' => $totalResults];
            return response()->json($finalResponse);
        }

        $tempAds = Ads::with('ad_details')->with('nearby_facilities')->with(['user.user_profiles', 'user.user_packages' => function ($query) {
            $query->orderBy('id', 'desc')->first();
        }])->with(['ad_files' => function ($query) {
            $query->where('media_type', '0')->orderBy('ordre', 'asc');
        }])->join('ad_details', 'ad_details.ad_id', '=', 'ads.id')->select(DB::raw($radius_query_string . 'as distance, ad_details.*,ads.*'))->where($whereScenario)->where('status', '1')->where('admin_approve', '1')->whereRaw("datediff(now(), ads.updated_at) <= " . $this->nbJour)->whereRaw($radius_query_string . '<= ' . $radius)->limit($nbLimitMap)->orderByRaw($orderTop)->orderByRaw('ads.updated_at DESC')->orderBy('distance', 'ASC');
        $AllAds = $tempAds->get();
        $ads = $tempAds->paginate($this->perpage);
        $totalResults = $ads->total();
        $currentPage = $ads->currentPage();
        $count = $ads->count();
        $perPage = $ads->perPage();

        $countFrom = (($currentPage - 1) * $perPage) + 1;
        $countTo = $countFrom + $count - 1;

        if ($countTo < $countFrom) {
            $countFrom = 0;
        }

        $response['lat'] = $lat;
        $response['long'] = $long;

        $response['ads'] = array();

        $latlong = array();
        foreach ($AllAds as $key => $ad) {
            if (in_array($ad->latitude . "," . $ad->longitude, $latlong)) {
                $key = array_search($ad->latitude . "," . $ad->longitude, $latlong);
                if (count($ad->ad_files) > 0) {
                    $property_pic = '/uploads/images_annonces/' . $ad->ad_files[0]->filename;
                } else {
                    $property_pic = '';
                }
                if (date_create($ad->available_date) > date_create('today')) {
                    $availablity = __('searchlisting.available') . ' ' . date('j M', strtotime($ad->available_date));
                } else {
                    $availablity = __('searchlisting.available_now');
                }

                if (!empty($ad->user->user_packages) && count($ad->user->user_packages) > 0) {
                    if (strtotime(date('Y-m-d')) > strtotime($ad->user->user_packages[0]->end_date)) {
                        $ad_premium = __('searchlisting.membre_basique');
                    } else {
                        $ad_premium = __('searchlisting.membre_premium');
                    }
                } else {
                    $ad_premium = __('searchlisting.membre_basique');
                }

                array_push($response['ads'][$key]['details'], ["id" => $ad->id, 'href' => adUrl($ad->ad_id, $id), 'price' => $ad->min_rent, 'property_pic' => $property_pic, 'availablity' => $availablity, 'bed' => '<b>' . $ad->bedrooms . '</b> ' . __("searchlisting.bedroom"), 'bath' => '<b>' . $ad->bathrooms . '</b> ' . __("searchlisting.bathroom"), 'posted' => __("searchlisting.posted") . ' ' . translateDuration($ad->updated_at), 'ad_premium' => $ad_premium, "bed_number" => $ad->bedrooms, "bath_number" => $ad->bathrooms]);
            } else {
                if (count($ad->ad_files) > 0) {
                    $property_pic = '/uploads/images_annonces/' . $ad->ad_files[0]->filename;
                } else {
                    $property_pic = '';
                }
                if (date_create($ad->available_date) > date_create('today')) {
                    $availablity = __('searchlisting.available') . ' ' . date('j M', strtotime($ad->available_date));
                } else {
                    $availablity = __('searchlisting.available_now');
                }

                if (!empty($ad->user->user_packages) && count($ad->user->user_packages) > 0) {
                    if (strtotime(date('Y-m-d')) > strtotime($ad->user->user_packages[0]->end_date)) {
                        $ad_premium = __('searchlisting.membre_basique');
                    } else {
                        $ad_premium = __('searchlisting.membre_premium');
                    }
                } else {
                    $ad_premium = __('searchlisting.membre_basique');
                }

                $response['ads'][] = ['lat' => $ad->latitude, 'long' => $ad->longitude, 'details' => [["id" => $ad->id, 'href' => adUrl($ad->ad_id, $id), 'price' => $ad->min_rent, 'property_pic' => $property_pic, 'availablity' => $availablity, 'bed' => '<b>' . $ad->bedrooms . '</b> ' . __("searchlisting.bedroom"), 'bath' => '<b>' . $ad->bathrooms . '</b> ' . __("searchlisting.bathroom"), 'posted' => __("searchlisting.posted") . ' ' . translateDuration($ad->updated_at), 'ad_premium' => $ad_premium, "bed_number" => $ad->bedrooms, "bath_number" => $ad->bathrooms]]];

                $latlong[] = $ad->latitude . "," . $ad->longitude;
            }
        }

        $jsonResponse = response()->json($response)->getContent();

        $propertyTypes = $this->master->getMasters('property_types');
        $propRules = $this->master->getMasters('property_rules');
        $page_description = searchPageDescription($scenario_id, $address);
        $page_meta_keyword = searchPageKeyword($scenario_id, $address);

        if (isUserSubscribed()) {
            if (Auth::user()->type_design == 1) {
                return view('searchlisting/search-third-scen-map', compact('id', 'ads', 'propertyTypes', 'propRules', 'scenario_id', 'address', 'lat', 'long', 'favourites', 'layout', 'countFrom', 'countTo', 'count', 'totalResults', 'jsonResponse', 'location_id', "page_title", "countries", "lifestyles", "socialInterests", "sous_type_loc", "map", "page_description", "page_meta_keyword", "typeMusics", "proximities"));
            } else {
                return view('searchlisting/basic/search-third-scen-map', compact('id', 'ads', 'propertyTypes', 'propRules', 'scenario_id', 'address', 'lat', 'long', 'favourites', 'layout', 'countFrom', 'countTo', 'count', 'totalResults', 'jsonResponse', 'location_id', "page_title", "countries", "lifestyles", "socialInterests", "sous_type_loc", "map", "page_description", "page_meta_keyword", "typeMusics", "proximities"));
            }
        } else {
            return view('searchlisting/basic/search-third-scen-map', compact('id', 'ads', 'propertyTypes', 'propRules', 'scenario_id', 'address', 'lat', 'long', 'favourites', 'layout', 'countFrom', 'countTo', 'count', 'totalResults', 'jsonResponse', 'location_id', "page_title", "countries", "lifestyles", "socialInterests", "sous_type_loc", "map", "page_description", "page_meta_keyword", "typeMusics", "proximities"));
        }
    }

    public function infosAnnoncePopup(Request $request)
    {
        $id = $request->ad_id;
        $ad = DB::table("ads")->select(DB::raw("ad_details.*, ads.*"))->join("ad_details", "ad_details.ad_id", "ads.id")->where("ads.id", $id)->first();
        $ad->ad_files = DB::table("ad_files")->where("ad_id", $id)->orderBy("ordre", "asc")->get();
        return view("searchlisting/map-popup", compact("ad"));
    }

    public function constructLineString($paths)
    {
        $result = "LineString(";
        foreach ($paths as $key => $path) {
            if ($key != 0) {
                $result .= ",";
            }
            $result .= $path->lat . " " . $path->lng;
        }
        $result .= ")";
        return $result;
    }


    public function searchAdThirdScenario_fixe($id, $user_id, $lat, $long, $scenario_id, $address, $request, $layout, $location_id, $countries, $lifestyles, $socialInterests, $sous_type_loc, $proximities)
    {
        if (isUserSubscribed()) {
            if (Auth::user()->type_design == 1) {
                $view_name = "searchlisting/third-scen-data";
            } else {
                $view_name = "searchlisting/basic/third-scen-data";
            }
        } else {
            $view_name = "searchlisting/basic/third-scen-data";
        }
        $typeMusics = DB::table('user_type_music')->get();
        $page_title = $this->pageTitle($scenario_id, $address);
        $favourites = [];

        if ($request->isAlert == "1") {
            $data = $request->all();
            $data['address'] = $address;
            $data['scenario_id'] = $scenario_id;
            $data['latitude'] = $lat;
            $data['longitude'] = $long;
            if (!empty($request->idAlert)) {
                $this->updateAlert($request->idAlert, $data);
            } else {
                $this->saveAlert($data);
            }
        }


        $orderTop = "IF((is_top_list = 1 AND date_top_list >= NOW()), 1, 2) ASC";

        if ($layout == 'inner' && $user_id != 0) {
            if (count(Auth::user()->favorites) > 0) {
                foreach (Auth::user()->favorites as $favourite) {
                    array_push($favourites, $favourite->ad_id);
                }
            }
        }

        if (!empty($request->radius)) {
            $radius = $request->radius > 4 ? $request->radius : 4;

        } else {
            $radius = 40;
        }

        if (!empty($request->address)) {
            $address = $request->address;
        }

        if (!empty($request->lat)) {
            $lat = $request->lat;
        }

        if (!empty($request->lang)) {
            $long = $request->lang;
        }

        if ($scenario_id == 1) {
            $whereScenario = [['scenario_id', '1']];
        } elseif ($scenario_id == 2) {
            $whereScenario = [['scenario_id', '2']];
        } elseif ($scenario_id == 3) {
            $whereScenario = [['scenario_id', '3']];
        } elseif ($scenario_id == 4) {
            $whereScenario = [['scenario_id', '4']];
        } elseif ($scenario_id == 5) {
            $whereScenario = [['scenario_id', '5']];
        } elseif ($scenario_id == 6) {
            $whereScenario = [['scenario_id', '6']];
        }

        $radius_query_string = $this->radius_query($lat, $long);

        if (!empty($request->page)) {
            $request->session()->put("pageSearch", $request->page);
        }


        if (!empty($request->grille)) {
            $grille = $request->grille;
        } else {
            $grille = 1;
        }

        if ($request->ajax()) {
            $request->session()->put("SEARCH_FILTERS", $request->all());

            $school = $request->school;
            $smoker = $request->smoker;
            $country = $request->country;
            $city = $request->city;
            $common_friend = ($request->common_friend == "true") ? true : false;
            $with_image = $request->with_image;
            $urgent = $request->urgent;
            $whereRawArray = [];
            $sous_type_loc = $request->sous_type_loc;
            $age = $request->age;


            $property_types = !empty($request->property_types) ? json_decode($request->property_types) : [];

            $property_rules = !empty($request->property_rules) ? json_decode($request->property_rules) : [];

            $garanty = !empty($request->garanty) ? json_decode($request->garanty) : [];

            $lifestyles = !empty($request->lifestyle) ? json_decode($request->lifestyle) : [];

            $social_interests = !empty($request->social_interest) ? json_decode($request->social_interest) : [];

            $facilities = !empty($request->facilities) ? json_decode($request->facilities) : [];

            $visit_details = !empty($request->visiting_detail) ? json_decode($request->visiting_detail) : [];

            $gender = !empty($request->gender) ? json_decode($request->gender) : [];

            $occupation = !empty($request->occupation) ? json_decode($request->occupation) : [];

            $musics = !empty($request->musics) ? json_decode($request->musics) : [];

            $proximities = !empty($request->proximity) ? json_decode($request->proximity) : [];

            $query = Ads::with('user.user_profiles')->with('ad_details')->with('nearby_facilities')->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc');
            }]);

            $selectRaw = 'ad_details.*, ads.*';


            if (!empty($request->sort)) {
                switch ($request->sort) {
                    case 2:
                        $orderBy = 'ads.min_rent ASC';
                        break;
                    case 3:
                        $orderBy = 'ads.min_rent DESC';
                        break;
                    case 4:
                        $orderBy = 'ads.updated_at ASC';
                        break;
                    default:
                        $orderBy = 'ads.updated_at DESC';
                        break;
                }
            } else {
                $orderBy = 'ads.updated_at DESC';
            }

            if (!empty($request->stat)) {
                switch ($request->stat) {
                    case 1:
                        $orderBy = 'ads.view';
                        break;
                    case 2:
                        $orderBy = 'ads.clic';
                        break;
                    case 3:
                        $orderBy = 'ads.message';
                        break;
                    case 4:
                        $orderBy = 'ads.toc_toc';
                        break;
                    case 5:
                        $orderBy = 'ads.phone';
                        break;
                    case 6:
                        $orderBy = 'ads.contact_fb';
                        break;
                }

                $orderBy .= ' ' . $request->type_stat;
            }
        }
    }

    public function getUserPhone(Request $request)
    {
        $date = date("Y-m-d");
        $user_id = Auth::id();
        $nb = DB::table('user_show_tel')->where("user_id", $user_id)->whereRaw("date(date)='" . $date . "'")->count();
        $check = DB::table('user_show_tel')->where("user_id", $user_id)->whereRaw("date(date)='" . $date . "'")->where("user_contacted", $request->user_id)->first();
        if ($nb < getConfig("nb_max_contact") || !is_null($check)) {
            $user_profiles = DB::table('user_profiles')->where("user_id", $request->user_id)->first();


            if (is_null($check)) {
                DB::table("user_show_tel")->insert([
                    "user_id" => $user_id,
                    "user_contacted" => $request->user_id
                ]);
            }

            return $user_profiles->mobile_no;
        }
    }
}
