<?php

namespace App\Http\Controllers\Admin;

use App\coup_de_foudre;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\user;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Http\Models\Ads\Ads;
use Illuminate\Support\Facades\Mail;
use App\Repositories\MasterRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Ads\AdTemporaryFiles;
use App\Http\Models\Ads\AdFiles;
use ImageOptimizer;
use App\BlinkAPI;
use App\Cron;
use App\MailErrorAdmin;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use PDF;
use App\Http\Models\UserPackage\UserPackage;

use DateTimeZone;
use Illuminate\Support\Arr;

class CommunityController extends Controller
{

    private $perpage;


    function __construct(MasterRepository $master, AdTemporaryFiles $ad_temp_files, AdFiles $ad_files)
    {
        $this->perpage = config('app.perpage');
        $this->master = $master;
        $this->AdTempFiles = $ad_temp_files;
        $this->AdFiles = $ad_files;
    }

    public function toctocListHome() //list toctoc scenario 1 et 3
    {
        //Passing Parameter Data in Views
        $maxToctocReceiveExec = getConfig('nbr_max_toctoc_receive_exec');
        $maxToctocToday = getConfig('nb_max_toctoc');
        $maxToctocReceive = getConfig('nbr_max_toctoc_receive');
        $maxToctocUser = getConfig('free_message_flash');

        $nowInParis = Carbon::now('Europe/Paris')->toDateString(); //Take today's date
        $paris4DaysAgo = Carbon::now('Europe/Paris')->addDay(-4)->toDateString(); //Take the date 4 days ago

        //Take real users and their scenario 1 ads (RentAProperty ads) from 4 days ago until today
        $allUserRentAPropertys = DB::table("users")->where('is_community', '0')
            ->join('ads', 'users.id', '=', 'ads.user_id')
            ->select('users.*', 'ads.*', 'ads.id')
            ->where('scenario_id', '1')
            ->where('status', '1')
            ->whereBetween('ads.updated_at', [$paris4DaysAgo . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->get();

        //Take real users and their scenario 3 ads (SeekToRentAProperty ads) today
        $allUserSeekAPropertys = DB::table("users")->where('is_community', '0')
            ->join('ads', 'users.id', '=', 'ads.user_id')
            ->select('users.*', 'ads.*', 'ads.id')
            ->where('status', '1')
            ->where('scenario_id', '3')
            ->whereBetween('ads.updated_at', [$nowInParis . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->get();

        //get total toctoc per day
        $toctocToday = coup_de_foudre::where('toctoc_auto', 1)
            ->whereBetween('created_at', [$nowInParis . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->count();

        return view('admin.toctocListHome', compact('allUserRentAPropertys'
            , 'allUserSeekAPropertys'
            , 'toctocToday'
            , 'maxToctocToday'
            , 'maxToctocReceive'
            , 'maxToctocReceiveExec'
            , 'maxToctocUser'));
    }

    public function toctocListColocation() //list toctoc scenario 2 et 4
    {
        //Passing Parameter Data in Views
        $maxToctocReceiveExec = getConfig('nbr_max_toctoc_receive_exec');
        $maxToctocToday = getConfig('nb_max_toctoc');
        $maxToctocReceive = getConfig('nbr_max_toctoc_receive');
        $maxToctocUser = getConfig('free_message_flash');

        $nowInParis = Carbon::now('Europe/Paris')->toDateString(); //Take today's date
        $paris4DaysAgo = Carbon::now('Europe/Paris')->addDay(-4)->toDateString(); //Take the date 4 days ago

        //Take real users and their scenario 2 ads (RentAProperty ads) from 4 days ago until today
        $allUserRentAPropertys = DB::table("users")->where('is_community', '0')
            ->join('ads', 'users.id', '=', 'ads.user_id')
            ->select('users.*', 'ads.*', 'ads.id')
            ->where('scenario_id', '2')
            ->where('status', '1')
            ->whereBetween('ads.updated_at', [$paris4DaysAgo . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->get();

        //Take real users and their scenario 4 ads (SeekToRentAProperty ads) from 4 days ago until today
        $allUserSeekAPropertys = DB::table("users")->where('is_community', '0')
            ->join('ads', 'users.id', '=', 'ads.user_id')
            ->select('users.*', 'ads.*', 'ads.id')
            ->where('status', '1')
            ->where('scenario_id', '4')
            ->whereBetween('ads.updated_at', [$nowInParis . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->get();

        //get total toctoc per day
        $toctocToday = coup_de_foudre::where('toctoc_auto', 1)
            ->whereBetween('created_at', [$nowInParis . ' 00:00:00', $nowInParis . ' 23:59:59'])
            ->count();

        return view('admin.toctocListColocation', compact('allUserRentAPropertys'
            , 'allUserSeekAPropertys'
            , 'toctocToday'
            , 'maxToctocToday'
            , 'maxToctocReceive'
            , 'maxToctocReceiveExec'
            , 'maxToctocUser'));
    }


    public function index(Request $request)
    {
        $propertyTypes = $this->master->getMasters('property_types');
        $scenarioTypes = ["1" => "Louer une propriété", "2" => "Partager un logement", "3" => "Chercher à louer une propriété", "4" => "Chercher à partager un logement", "5" => "Chercher quelqu'un pour chercher ensemble un logement"];
        $sous_type_loc = $this->master->getMasters('sous_type_loc');
        if (isset($request->ad_id)) {
            $ad_id = $request->ad_id;
            $ad = DB::table("users")->join("user_profiles", "user_profiles.user_id", "users.id")->join("ads", "ads.user_id", "users.id")->join("ad_details", "ad_details.ad_id", "ads.id")->leftJoin("ad_tags", "ads.id", "ad_tags.ad_id")->where("ads.id", $ad_id)->first();
            $ad->id = $ad_id;
            $ad_files = DB::table("ad_files")->where("ad_id", $ad_id)->get();
            if (!empty($AdFiles)) {
                $ad->ad_files = $ad_files;
            }

            return view('admin.community_manager.add_ads', compact('propertyTypes', 'ad', 'scenarioTypes', 'sous_type_loc'));
        } else {
            return view('admin.community_manager.add_ads', compact('propertyTypes', 'scenarioTypes', 'sous_type_loc'));
        }

    }

    public function listAd(Request $request)
    {
        $comunity = DB::table("users")->where("user_type_id", "3")->get();
        if (isset($request->comunity) && !empty($request->comunity)) {
            $ads = DB::table("ads as a")->select(DB::raw("a.id,a.title,a.description,a.address,a.min_rent,a.url_slug,a.fb_ad_link,u.first_name as prenom_prop,u.last_name as nom_prop,up.fb_profile_link as fb_link, u2.first_name as prenom_comunity, u2.last_name as nom_comunity, pt.property_type"))->join("user_profiles as up", "up.user_id", "a.user_id")->join("ad_details as ad", "ad.ad_id", "a.id")->join("property_types as pt", 'pt.id', "ad.property_type_id")->leftJoin("users as u", "u.id", "a.user_id")->leftJoin("users as u2", "u2.id", "a.comunity_id")->where("comunity_id", $request->comunity)->get();
        } else {
            $ads = DB::table("ads as a")->select(DB::raw("a.id,a.title,a.description,a.address,a.min_rent,a.url_slug,a.fb_ad_link,u.first_name as prenom_prop,u.last_name as nom_prop,up.fb_profile_link as fb_link, u2.first_name as prenom_comunity, u2.last_name as nom_comunity, pt.property_type"))->join("user_profiles as up", "up.user_id", "a.user_id")->join("ad_details as ad", "ad.ad_id", "a.id")->join("property_types as pt", 'pt.id', "ad.property_type_id")->leftJoin("users as u", "u.id", "a.user_id")->leftJoin("users as u2", "u2.id", "a.comunity_id")->whereNotNull("comunity_id")->get();
        }

        return view('admin.community_manager.listing', compact('comunity', 'ads'));

    }

    public function checkAdByFbLink(Request $request)
    {
        $request->ad_fb_link = traiterFbLink($request->ad_fb_link);
        if (isExistAnnonceComunity($request->ad_fb_link)) {
            $response['error'] = 'yes';
            $response['errors'] = 'Ads aready exist';
            return response()->json($response);
        } else {
            $response['error'] = 'no';
            return response()->json($response);
        }

    }

    public function checkAnnonceByProfilInfo(Request $request)
    {
        $request->link_fb = traiterFbLink($request->link_fb);
        $info = ['profil_link' => $request->link_fb, "latitude" => $request->latitude, "longitude" => $request->longitude, "address" => $request->address];
        $adsDoute = checkCommunityAnnonceByProfil($info);
        if (count($adsDoute) > 0) {
            return response()->json($adsDoute);
        }
        return response()->json(array());
    }

    public function checkAnnonce($request)
    {
        $ad = DB::table("ads")->join("users", "users.id", "ads.user_id")->where("users.first_name", $request->first_name)->first();
    }

    public function test(Request $request)
    {
        $cron = new Cron();
        $cron->run();
        return view("emails.users.alert");

    }

    public function saveAd(Request $request)
    {
        $nbShortUrl = rand(1, 2);
        //enlever / a la fin des liens
        /*$api = new BlinkAPI();*/
        $request->link_fb_ad = traiterFbLink($request->link_fb_ad);
        $request->link_fb = traiterFbLink($request->link_fb);

        $latitude = $request->latitude;
        $longitude = $request->longitude;
        modifyVilleAdresse($request->address, $latitude, $longitude);
        if ($request->furnished == "on") {
            $furnished = 1;
        } else {
            $furnished = 0;
        }

        if (isset($request->edit_ad_id)) {
            $ad_id = $request->edit_ad_id;
            $ad = DB::table("ads")->where("ads.id", $ad_id)->first();
            $user_id = $ad->user_id;
            DB::table("users")->where("id", $ad->user_id)->update(
                [
                    'first_name' => $request->prenoms,
                    'last_name' => $request->nom,
                    'email' => $request->email,
                    'user_type_id' => 1,
                    'is_active' => 1,
                    "provider" => "facebook"
                ]
            );
            if ($request->file('pdp')) {
                $file = $request->file('pdp');
                $destinationPathProfilePic = base_path() . '/storage/uploads/profile_pics/';
                $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPathProfilePic, $file_name);
                pasteLogo($destinationPathProfilePic . $file_name);
                ImageOptimizer::optimize($destinationPathProfilePic . $file_name);
                $size = filesize($destinationPathProfilePic . $file_name);
                if ($size > 100000) {
                    compressImage($destinationPathProfilePic . $file_name, removeExtension($file_name), $destinationPathProfilePic);
                }
                DB::table("user_profiles")->where("user_id", $user_id)->update(
                    [
                        'user_id' => $user_id,
                        'sex' => $request->sex,
                        'mobile_no' => $request->mobile_no,
                        'fb_profile_link' => $request->link_fb,
                        'profile_pic' => $file_name
                    ]
                );
            } else {
                DB::table("user_profiles")->where("user_id", $user_id)->update(
                    [
                        'user_id' => $user_id,
                        'fb_profile_link' => $request->link_fb,
                        'sex' => $request->sex,
                        'mobile_no' => $request->mobile_no
                    ]
                );
            }
            DB::table("ads")->where("id", $ad_id)->update(
                [
                    'user_id' => $user_id,
                    "id_user" => $request->session()->get("ADMIN_USER")->id,
                    'title' => $request->title,
                    "description" => $request->description,
                    'address' => $request->address,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'min_rent' => $request->rent_per_month,
                    'scenario_id' => $request->scenario_id,
                    'url_slug' => str_slug($request->title),
                    'available_date' => date("Y-m-d", strtotime(convertDateWithTiret($request->date_of_availablity))),
                    'comunity_id' => $request->session()->get("ADMIN_USER")->id,
                    "status" => '1',
                    "ad_treaty" => '1',
                    "admin_approve" => "1",
                    "fb_ad_link" => $request->link_fb_ad,
                    "sous_type_loc" => ($request->scenario_id == 1 || $request->scenario_id == 3) ? $request->sous_loc_type : null
                ]
            );
            if (isset($request->metro_lines) && count($request->metro_lines) > 0) {
                DB::table('nearby_facilities')->where("ad_id", $ad_id)->delete();
                $metro_lines = $request->metro_lines;
                foreach ($metro_lines as $key => $metro_line) {
                    DB::table('nearby_facilities')->insert([
                        "ad_id" => $ad_id,
                        "name" => $metro_line,
                        "nearbyfacility_type" => "subway_station"
                    ]);
                }
            }

            $ad_details = [
                'ad_id' => $ad_id,
                'property_type_id' => $request->property_type,
                'furnished' => $furnished,
                "min_surface_area" => !empty($request->prop_square_meters) ? $request->prop_square_meters : NULL
            ];
            if ($request->scenario_id == 1 || $request->scenario_id == 2 || $request->scenario_id == 3) {
                $ad_details['accept_as'] = $request->accept_as;
            }
            if ($request->scenario_id == 2 || $request->scenario_id == 4) {
                $ad_details['preferred_gender'] = $request->preferred_gender;
                $ad_details['preferred_occupation'] = $request->preferred_occupation;
            }
            $ad_details['bedrooms'] = $request->no_of_bedrooms;
            $ad_details['bathrooms'] = $request->no_of_bathrooms;

            DB::table("ad_details")->insert($ad_details);

            DB::table("ad_tags")->where("ad_id", $ad_id)->update(
                [
                    'ad_id' => $ad_id,
                    'utm_medium' => $request->utm_medium,
                    'utm_term' => $request->utm_term,
                    "utm_content" => $request->utm_content
                ]
            );
            $ad_info_session = $request->session()->get('AD_INFO');
            if (!empty($ad_info_session)) {
                $unique_id = $ad_info_session["unique_id"];
                DB::table('ad_files')->where("ad_id", $ad_id)->delete();
                $this->saveAdMediaFiles($ad_id, $unique_id);
            }
            $ad = DB::table("ads")->join("ad_details", "ads.id", "ad_details.ad_id")->join("property_types", "property_types.id", "ad_details.property_type_id")->where("ads.id", $ad_id)->first();
            $url = adUrl($ad_id) . '?utm_source=Facebook&utm_campaign=community&utm_medium=' . $request->utm_medium . '&utm_term=' . $request->utm_term . "&utm_content=" . $request->utm_content;

            if ($request->scenario_id == 1 || $request->scenario_id == 2) {
                $messageComunity = 'Hello ' . $request->prenoms . ' votre annonce est prête pour recevoir les notifications des candidats . Inscris toi sur "' . $url . '"';
            } else {
                $messageComunity = 'Hello ' . $request->prenoms . ' votre annonce est prête pour recevoir les notifications des bailleurs . Inscris toi sur "' . $url . '"';
            }
        } else {
            if (isExistAnnonceComunity($request->link_fb_ad)) {
                $request->session()->flash('error', 'Ads aready exist');
                return redirect()->back();
            }

            $user_id = $this->checkUser($request->link_fb);
            if (is_null($user_id)) {
                DB::table("users")->insert(
                    [
                        'first_name' => $request->prenoms,
                        'last_name' => $request->nom,
                        'email' => $request->email,
                        'user_type_id' => 1,
                        'is_community' => 1,
                        'is_active' => 1,
                        "provider" => "facebook"
                    ]
                );

                if ($request->file('pdp')) {
                    $file = $request->file('pdp');
                    $destinationPathProfilePic = base_path() . '/storage/uploads/profile_pics/';
                    $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move($destinationPathProfilePic, $file_name);
                    pasteLogo($destinationPathProfilePic . $file_name);
                    $size = filesize($destinationPathProfilePic . $file_name);
                    if ($size > 100000) {
                        compressImage($destinationPathProfilePic . $file_name, removeExtension($file_name), $destinationPathProfilePic);
                    }
                }
                $user_id = DB::getPdo()->lastInsertId();
                countUsers("insert");
                DB::table("user_profiles")->insert(
                    [
                        'user_id' => $user_id,
                        'sex' => 0,
                        'mobile_no' => $request->mobile_no,
                        'fb_profile_link' => $request->link_fb,
                        'sex' => $request->sex,
                        'profile_pic' => (isset($file_name)) ? $file_name : null
                    ]
                );
            }
            DB::table("ads")->insert(
                [
                    'user_id' => $user_id,
                    "id_user" => $request->session()->get("ADMIN_USER")->id,
                    'title' => $request->title,
                    "description" => $request->description,
                    'address' => $request->address,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'min_rent' => $request->rent_per_month,
                    'scenario_id' => $request->scenario_id,
                    "admin_approve" => "0",
                    'url_slug' => str_slug($request->title),
                    'available_date' => date("Y-m-d", strtotime(convertDateWithTiret($request->date_of_availablity))),
                    'comunity_id' => $request->session()->get("ADMIN_USER")->id,
                    "status" => '1',
                    "ad_treaty" => '1',
                    "admin_approve" => "1",
                    "fb_ad_link" => $request->link_fb_ad,
                    "sous_type_loc" => ($request->scenario_id == 1 || $request->scenario_id == 3) ? $request->sous_loc_type : null
                ]
            );

            $ad_id = DB::getPdo()->lastInsertId();
            countAds("insert");
            if (isset($request->metro_lines) && count($request->metro_lines) > 0) {
                DB::table('nearby_facilities')->where("ad_id", $ad_id)->delete();
                $metro_lines = $request->metro_lines;
                foreach ($metro_lines as $key => $metro_line) {
                    DB::table('nearby_facilities')->insert([
                        "ad_id" => $ad_id,
                        "name" => $metro_line,
                        "nearbyfacility_type" => "subway_station"
                    ]);
                }
            }
            if ($request->signalAd == 1) {
                if ($request->type_signal == "description") {
                    $commentaire = "la description de l'annonce contient des information interdites comme des liens ou numero telephone ou email";
                }
                if ($request->type_signal == "title") {
                    $commentaire = "le titre de l'annonce contient des information interdites comme des liens ou numero telephone ou email";
                }
                DB::table("signal_ad")->insert(
                    ["user_id" => getUserAdmin()->id, "ad_id" => $ad_id, "commentaire" => $commentaire, "created_at" => date("Y-m-d H:i:s"), "updated_at" => date("Y")]
                );
            }

            $ad_details = [
                'ad_id' => $ad_id,
                'property_type_id' => $request->property_type,
                'furnished' => $furnished,
                "min_surface_area" => !empty($request->prop_square_meters) ? $request->prop_square_meters : NULL
            ];
            if ($request->scenario_id == 1 || $request->scenario_id == 2 || $request->scenario_id == 3) {
                $ad_details['accept_as'] = $request->accept_as;
            }
            if ($request->scenario_id == 2 || $request->scenario_id == 4) {
                $ad_details['preferred_gender'] = $request->preferred_gender;
                $ad_details['preferred_occupation'] = $request->preferred_occupation;
            }
            $ad_details['bedrooms'] = $request->no_of_bedrooms;
            $ad_details['bathrooms'] = $request->no_of_bathrooms;

            DB::table("ad_details")->insert($ad_details);

            DB::table("ad_tags")->insert(
                [
                    'ad_id' => $ad_id,
                    "utm_source" => "facebook",
                    "utm_campaign" => "community",
                    'utm_medium' => $request->utm_medium,
                    'utm_term' => $request->utm_term,
                    "utm_content" => $request->utm_content
                ]
            );
            $ad_info_session = Session::get('AD_COMUNITY_INFO');

            if (!empty($ad_info_session)) {
                $unique_id = $ad_info_session["unique_id"];
                $this->saveAdMediaFiles($ad_id, $unique_id);
            }
            $ad = DB::table("ads")->join("ad_details", "ads.id", "ad_details.ad_id")->join("property_types", "property_types.id", "ad_details.property_type_id")->where("ads.id", $ad_id)->first();
            $url = adUrl($ad_id) . '?utm_source=Facebook&utm_campaign=community&utm_medium=' . $request->utm_medium . '&utm_term=' . $request->utm_term . "&utm_content=" . $request->utm_content;

            if ($request->scenario_id == 1 || $request->scenario_id == 2) {
                $messageComunity = 'Hello ' . $request->prenoms . ' ton annonce est prête sur "' . $url . '" pour recevoir les notifications des candidats . Inscris-toi sur le site pour pouvoir activer ton annonce';
            } else {
                $messageComunity = 'Hello ' . $request->prenoms . ' ton annonce est prête sur "' . $url . '" pour recevoir les notifications des bailleurs . Inscris-toi sur le site pour pouvoir activer ton annonce';
            }
        }

        DB::table('ads')->where("id", $ad_id)->update(["short_url" => $url]);

        Session::forget('AD_COMUNITY_INFO');
        $propertyTypes = $this->master->getMasters('property_types');
        $sous_type_loc = $this->master->getMasters('sous_type_loc');
        $scenarioTypes = ["1" => "Louer une propriété", "2" => "Partager un logement", "3" => "Chercher à louer une propriété", "4" => "Chercher à partager un logement", "5" => "Chercher quelqu'un pour chercher ensemble un logement"];
        return view('admin.community_manager.add_ads', compact('propertyTypes', 'messageComunity', 'scenarioTypes', 'sous_type_loc'));
    }

    private function checkUser($link)
    {
        $user = DB::table("user_profiles")->where("fb_profile_link", $link)->first();

        if (!is_null($user)) {
            return $user->user_id;
        }
    }

    public function uploadFilesComunity(Request $request)
    {
        if (count($request->file('file_photos')) > 0 || count($request->file('file_video')) > 0) {
            if (count($request->file('file_photos')) > 0) {
                $files = $request->file('file_photos');
            } else {
                $files = $request->file('file_video');
            }

            $destinationPathImages = base_path() . '/storage/uploads/images_annonces/';
            $destinationPathVideo = base_path() . '/storage/uploads/videos_annonces/';
            if (Session::has('AD_COMUNITY_INFO')) {
                $ad_info_session = Session::get('AD_COMUNITY_INFO');
                $unique_id = $ad_info_session['unique_id'];
            } else {
                $unique_id = uniqid();
                Session::put('AD_COMUNITY_INFO', array('unique_id' => $unique_id));
            }
            $upload_response = array();
            foreach ($files as $file) {

                $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $user_filename = $file->getClientOriginalName();
                $media_type = $request->media_type;

                $AdTempFiles = new $this->AdTempFiles;
                if ($media_type == 0) {
                    $file->move($destinationPathImages, $file_name);
                    pasteLogo($destinationPathImages . $file_name);
                    $size = filesize($destinationPathImages . $file_name);
                    if ($size > 100000) {
                        compressImage($destinationPathImages . $file_name, removeExtension($file_name), $destinationPathImages);
                    }

                } else {
                    $file->move($destinationPathVideo, $file_name);
                }

                $AdTempFiles->unique_id = $unique_id;
                $AdTempFiles->filename = $file_name;
                $AdTempFiles->user_filename = $user_filename;
                $AdTempFiles->media_type = $media_type;
                $AdTempFiles->save();
                if ($media_type == 0) {
                    $upload_response['initialPreview'][] = "<img src='/uploads/images_annonces/" . $file_name . "' class='file-preview-image' alt='" . $user_filename . "' title='" . $user_filename . "'>";
                } else {
                    $upload_response['initialPreview'][] = "<img src='/images/video-file-icon.png' class='file-preview-image' alt='" . $user_filename . "' title='" . $user_filename . "'>";
                }

                $upload_response['initialPreviewConfig'][] = array('caption' => $user_filename, 'size' => $size, 'width' => '120px', 'url' => '/ad/deletefile', 'key' => $unique_id, 'extra' => array('file_name' => $file_name));
            }
            $upload_response['append'] = TRUE;
            return response()->json($upload_response);

        }
    }

    private function saveAdMediaFiles($ad_id, $unique_id, $edit = false)
    {
        $AdTempFiles = new $this->AdTempFiles;
        $temp_files = $AdTempFiles->where('unique_id', $unique_id)->get();
        $temp_files_array = array();
        foreach ($temp_files as $temp_file) {
            $AdFiles = new $this->AdFiles;
            $AdFiles->ad_id = $ad_id;
            $AdFiles->filename = $temp_file->filename;
            $AdFiles->user_filename = $temp_file->user_filename;
            $AdFiles->media_type = $temp_file->media_type;
            $AdFiles->save();
        }
    }

    public function add_comunity(Request $request)
    {
        $types = DB::table('type_user')->get();
        return view('admin.user.add_comunity', compact("types"));
    }

    public function saveComunity(Request $request)
    {
        $user = DB::table("users")->where("email", $request->email)->first();
        if (!is_null($user)) {
            $request->session()->flash("error", "Community already exist, choose another mail address");
            return redirect()->back();
        }
        $aujoud_hui = date('Y-m-d');
        DB::table("users")->insert(
            ["first_name" => $request->first_name, "last_name" => $request->last_name, "email" => $request->email, "password" => bcrypt($request->password), "verified" => "1", "is_active" => "1", "user_type_id" => $request->type,"last_conection" => $aujoud_hui]
        );
        countUsers("insert");

        $request->session()->flash("status", "Community manager created");
        return redirect()->back();
    }

    public function showBitlyToken(Request $request)
    {
        //facebook pixel_id
        $pixelId = DB::table('config')->where('varname', 'pixel_id')->first()->value;
        return view('admin.fb_pixel.fb_pixel', compact('pixelId'));
    }

    public function editBitlyToken(Request $request)
    {
        $ids = DB::table("link_phrase")->select("id")->pluck("id")->toArray();
        $phrase = "";
        if (count($ids) > 0) {
            $min = $ids[0];
            $max = $ids[count($ids) - 1];
        }

        $phrases_link = [];
        $api = new BlinkAPI();
        /*$shortUrl = $api->createShortUrl("https://www.bailti.fr?utm_source=facebook&utm_campaign=community&utm_medium=landingpage");*/
        if (isset($request->nb)) {
            $nb = $request->nb;
        } else {
            $nb = 10;
        }
        $url = "https://www.bailti.fr?utm_source=facebook&utm_campaign=community&utm_medium=landingpage";
        for ($i = 0; $i < $nb; $i++) {
            $nbShortUrl = rand(1, 2);
            if ($nbShortUrl == 1) {
                $shortUrl = bitlyGenerateUrl($url);
            } else {
                $shortUrl = $api->createShortUrl($url);
            }
            if (count($ids) > 0) {
                $idPhrase = rand($min, $max);
                $phrase = DB::table('link_phrase')->where("id", $idPhrase)->first()->phrase_fr;
            }
            $phrase_with_link = str_replace("%lien%", $shortUrl, $phrase);
            $phrases_link[] = $phrase_with_link;
        }


        if ($request->isMethod('post')) {

            $validator = Validator::make($request->all(),
                [
                    'token' => 'required'
                ]
            );
            if ($validator->fails()) {

                return redirect()->back()->withErrors($validator)->withInput($request->all());
            } else {

                updateConfig("bitly_token", $request->token);

                $request->session()->flash('status', "Package has been updated successfuly.");
                return redirect()->back();
            }
        } else {
            $token = getConfig("bitly_token");
            return view('admin.community_manager.bitly', compact('token', "phrases_link", "nb"));
        }
    }


    public function add_daily_report(Request $request)
    {
        if (isset($request->id)) {
            $reportInfo = DB::table("all_reports")->where("id", $request->id)->first();
            $fields = $this->getProfileFields($request->id, $reportInfo);
            $date_report = date("d/m/Y", strtotime($reportInfo->date_report));
            $id = $request->id;
            return view('admin.community_manager.add_daily_report', compact('fields', "date_report", "id"));
        } else {
            $fields = $this->getProfileFields();
            return view('admin.community_manager.add_daily_report', compact('fields'));
        }
    }

    function getProfileFields($id = null, $reportInfo = null)
    {
        $userId = getUserAdmin()->id;
        $profiles = DB::table("profile")->where("user", $userId)->get();
        $results = [];
        foreach ($profiles as $key => $profile) {
            $categories = unserialize($profile->category);
            $results[$profile->id] = array("login" => $profile->login);
            $field_categ = [];
            foreach ($categories as $key2 => $categ) {
                $temps = DB::table("taches")->where("category", $categ)->get();
                foreach ($temps as $key3 => $temp) {

                    if (!is_null($id)) {
                        $values = DB::table("all_reports")->where("profile_id", $profile->id)->where("task_id", $temp->id)->where("date_report", $reportInfo->date_report)->first();
                        if (!is_null($values)) {
                            $temp->value = $values->value;
                        }
                    }
                    $field_categ[] = $temp;
                }
            }
            $results[$profile->id]['fields'] = $field_categ;
        }
        return $results;
    }


    private function getTaskByFieldName($fieldName)
    {
        $temp = explode("_", $fieldName);
        $id_profile = $temp[count($temp) - 1];
        $task = substr($fieldName, 0, strlen($fieldName) - strlen($id_profile) - 1);

        return array("id_profile" => $id_profile, "task" => $task);
    }

    public function saveDailyReport(Request $request)
    {


        /*========== managing validator for all fields==============*/
        $data = $request->all();
        $rules = [];
        foreach ($data as $field_name => $field) {
            $exclude = array('_token', "date_report", "id");
            if (!in_array($field_name, $exclude)) {
                $rule = "";
                $temp = $this->getTaskByFieldName($field_name);
                $task_name = $temp["task"];
                $taskInfo = DB::table("taches")->where("name", $task_name)->first();
                if ($taskInfo->type == "int(11)" && $taskInfo->required == 1) $rule .= "numeric|";
                if ($taskInfo->required == 1) $rule .= "required";
                $rules[$field_name] = $rule;
            }
        }

        $validator = Validator::make($data, $rules);
        /*========== managing validator for all fields==============*/

        if ($validator->fails()) {
            $request->session()->flash('error', "There are some errors, please check and fix them");
            return redirect()->back()->withErrors($validator)->withInput($request->all());

        } else {
            $data['date_report'] = date("Y-m-d", strtotime(convertDateWithTiret($data['date_report'])));
            unset($data["_token"]);
            if (!empty($request->id)) {
                $infos = DB::table("all_reports")->join("profile", "profile.id", "all_reports.profile_id")->where("all_reports.id", $request->id)->first();
                $Ids = DB::table("all_reports")->select(DB::raw("all_reports.id"))->join("profile", "profile.id", "all_reports.profile_id")->where("all_reports.date_report", $infos->date_report)->where("profile.user", $infos->user)->pluck("all_reports.id")->toArray();

                $datecheck = DB::table("all_reports")->join("profile", "profile.id", "all_reports.profile_id")->where("profile.user", $infos->user)->where("date_report", $data['date_report'])->whereNotIn("all_reports.id", $Ids)->count();
                if ($datecheck >= 1) {
                    $request->session()->flash('error', "You have already add your report for " . date("d/m/Y", strtotime($data['date_report'])));
                    return redirect()->back();
                }
                DB::table("all_reports")->whereIn("id", $Ids)->delete();
                foreach ($data as $field_name => $field) {
                    $exclude = array('_token', "date_report", "id");
                    if (!in_array($field_name, $exclude)) {
                        $temp = $this->getTaskByFieldName($field_name);
                        $task_name = $temp["task"];
                        $id_profile = $temp["id_profile"];
                        $taskInfo = DB::table("taches")->where("name", $task_name)->first();
                        DB::table("all_reports")->insert(
                            ["date_report" => $data["date_report"], "profile_id" => $id_profile, "task_id" => $taskInfo->id, "value" => $field]
                        );
                    }
                }
                $request->session()->flash('status', "Report has been updated successfuly.");
                return redirect()->route("admin.all_report");
            } else {
                $datecheck = DB::table("all_reports")->join("profile", "profile.id", "all_reports.profile_id")->where("date_report", $data['date_report'])->where("profile.user", getUserAdmin()->id)->first();
                if (!is_null($datecheck)) {
                    $request->session()->flash('error', "You have already add your report for " . date("d/m/Y", strtotime($data['date_report'])));
                    return redirect()->back();
                }

                foreach ($data as $field_name => $field) {
                    $exclude = array('_token', "date_report", "id");
                    if (!in_array($field_name, $exclude)) {
                        $temp = $this->getTaskByFieldName($field_name);
                        $task_name = $temp["task"];
                        $id_profile = $temp["id_profile"];
                        $taskInfo = DB::table("taches")->where("name", $task_name)->first();
                        DB::table("all_reports")->insert(
                            ["date_report" => $data["date_report"], "profile_id" => $id_profile, "task_id" => $taskInfo->id, "value" => $field]
                        );
                    }
                }
                $request->session()->flash('status', "Report has been added successfuly.");
                return redirect()->back();
            }

        }
    }

    public function AllDailyReport(Request $request)
    {
        //supression d'une annonce
        if ($request->ajax()) {
            $user_id = $request->id_new_user;
            $profiles = DB::table("profile")->where("user", $user_id)->get();
            return view('admin.community_manager.profile_list', compact('profiles'))->render();
        } else {
            if (isset($request->report_id) && !empty($request->report_id)) {
                $report_id = $request->report_id;
                $reportInfo = DB::table('all_reports')->join("profile", "profile.id", "all_reports.profile_id")->where("all_reports.id", $report_id)->first();
                /*$query = "DELETE all_reports FROM all_reports INNER JOIN profile ON profile.id=all_reports.profile_id WHERE profile.user=". $reportInfo->user . " AND date_report='" . $reportInfo->date_report . "'";
                DB::delete($query);*/
                DB::table("all_reports")->where("id", $request->report_id)->delete();
                $request->session()->flash("status", "Report deleted succesfully");
                return redirect()->back();
            }

            if (isSuperviseur()) {
                $users = DB::table("users")->where("user_type_id", "4")->orWhere("user_type_id", "3")->pluck("id")->toArray();
            } else if (isComunity()) {
                $users = [getUserAdmin()->id];
            } else {
                $users = DB::table("users")->where("user_type_id", "<>", "1")->pluck("id")->toArray();
            }
            $fields = [];
            $entetes = [];
            $userId = getUserAdmin()->id;
            $date_report = date("Y-m-d");
            if (isset($request->date_report) && !empty($request->date_report)) {
                $date_report = date("Y-m-d", strtotime(convertDateWithTiret($request->date_report)));
            }
            $orderBy = "taches.label ASC";
            $sort = "";
            $sort_direction = "";
            if (isset($request->sort) && !empty($request->sort)) {

                if ($request->sort != "value") {
                    $orderBy = $request->sort . " " . $request->sort_direction;
                } else {
                    $orderBy = "CAST(value AS UNSIGNED) " . $request->sort_direction;
                }

                $sort = $request->sort;
                if ($request->sort_direction == "asc") $sort_direction = "up";
                else $sort_direction = "down";

            }

            $tasks = DB::table('taches')->get();

            $tache = "";
            $where = [];
            $whereRaw = "1=1 ";
            if (isset($request->task) && !empty($request->task)) {
                array_push($where, ['task_id', $request->task]);
                $tache = $request->task;
            }

            $hors_sla = false;
            if (isset($request->hors_sla) && !is_null($request->hors_sla)) {
                $whereRaw .= "AND taches.sla IS NOT NULL AND CAST(value AS UNSIGNED) < taches.sla ";
                $hors_sla = true;
            }


            $reportsList = DB::table('all_reports')->select(DB::raw("all_reports.value,all_reports.id as id_report,taches.
                label, profile.login, users.first_name, users.email,users.id as user_id"))->join('profile', "profile.id", "all_reports.profile_id")->join("users", "users.id", "profile.user")->join("taches", "taches.id", "all_reports.task_id")->whereIn("users.id", $users)->where($where)->where("date_report", $date_report)->whereRaw($whereRaw)->orderByRaw($orderBy)->get();
            $date_report = date("d/m/Y", strtotime($date_report));
            return view('admin.community_manager.report_list', compact('reportsList', "date_report", "userId", "profile_id", "sort", "sort_direction", "tasks", "tache", "hors_sla"));
        }

    }

    private function arrangeReports($reports)
    {
        $result = [];
        foreach ($reports as $key => $report) {
            $result[$report->task_id] = array("id" => $report->id, "value" => $report->value);
        }
        return $result;
    }

    public function manageReportField(Request $request)
    {
        if (isset($request->column_delete)) {
            DB::table("taches")->where("id", $request->column_delete)->delete();
            $request->session()->flash('status', "Fields has been deleted successfuly.");
            return redirect()->back();
        }
        $fields = DB::table("taches")->join("task_category", "taches.category", "task_category.id")->select(DB::raw("taches.id, taches.label as title,taches.required, taches.type,taches.sla, task_category.label,task_category.id as category_id"))->orderBy("taches.id")->get();
        $categories = DB::table("task_category")->get();
        return view('admin.community_manager.manage_report_field', compact("fields", "categories"));
    }

    function saveDailyReportField(Request $request)
    {
        $rules = array("label_field" => "required", "sla" => "nullable|numeric");
        $validator = Validator::make($request->all(), $rules);
        /*========== managing validator for all fields==============*/

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {

            $required = 0;
            if (!is_null($request->required_field)) {
                $required = 1;
            }
            if (!empty($request->old_label_field)) {
                $res = DB::table("taches")->where('name', str_slug(trim($request->label_field, "_")))->where("id", "<>", $request->id)->first();
                if (!is_null($res)) {
                    $request->session()->flash('error', "Change the label, a field with the same label exists already");
                    return redirect()->back();
                }
                $old_field = str_slug(trim($request->old_label_field), "_");
                DB::table("taches")->where("id", $request->id)->update(
                    ["name" => str_slug($request->label_field, "_"), "label" => $request->label_field, "required" => $required, "category" => $request->category_field, "type" => $request->type_field, "sla" => $request->sla]
                );
                $request->session()->flash('status', "Fields has been updated successfuly.");
            } else {
                $res = DB::table("taches")->where('name', str_slug(trim($request->label_field, "_")))->first();
                if (!is_null($res)) {
                    $request->session()->flash('error', "Change the label, a field with the same label exists already");
                    return redirect()->back();
                }

                DB::table("taches")->insert(
                    ["name" => str_slug($request->label_field, "_"), "label" => $request->label_field, "required" => $required, "category" => $request->category_field, "type" => $request->type_field, "sla" => $request->sla]
                );
                $request->session()->flash('status', "Fields has been added successfuly.");
            }

            return redirect()->back();
        }

    }

    function saveReportData(Request $request)
    {
        if (isset($request->delete_id) && !empty($request->delete_id)) {
            DB::table("report_to_task")->where("report_id", $request->delete_id)->delete();
            DB::table('report')->where("id", $request->delete_id)->delete();
            $request->session()->flash('status', "Report has been deleted successfuly.");
            return redirect()->back();
        }

        $reportTasks = json_decode($request->report_tasks);
        $reportProfile = $request->profil_id;
        if (isset($request->id)) {
            $report_id = $request->id;
            DB::table("report")->where("id", $report_id)->update(
                ["user_type_id" => $reportProfile]
            );
            DB::table("report_to_task")->where("report_id", $report_id)->delete();
            foreach ($reportTasks as $key => $task) {
                DB::table('report_to_task')->insert(
                    ["report_id" => $report_id, "tache_id" => $task]
                );
            }
            $request->session()->flash('status', "Report has been updated successfuly.");
            return redirect()->back();
        } else {
            $report_id = DB::table("report")->insertGetId(
                ["user_type_id" => $reportProfile]
            );

            foreach ($reportTasks as $key => $task) {
                DB::table('report_to_task')->insert(
                    ["report_id" => $report_id, "tache_id" => $task]
                );
            }
            $request->session()->flash('status', "Report has been added successfuly.");
            return redirect()->back();
        }
    }


    function manageTaskCategory(Request $request)
    {
        if (isset($request->id)) {
            DB::table("task_category")->where("id", $request->id)->delete();
            $request->session()->flash('status', "Category has been deleted successfuly.");
            return redirect()->back();
        }
        $categories = DB::table("task_category")->get();
        return view('admin.community_manager.manage_category', compact("categories"));
    }

    function saveCategory(Request $request)
    {
        $rules = array("label" => "required");
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            if (isset($request->id)) {
                DB::table("task_category")->where("id", $request->id)->update(
                    ["label" => $request->label]
                );
                $request->session()->flash('status', "Category has been updated successfuly.");
            } else {
                DB::table("task_category")->insert(
                    ["label" => $request->label]
                );
                $request->session()->flash('status', "Category has been added successfuly.");

            }
            return redirect()->back();
        }
    }

    function saveProfile(Request $request)
    {
        $rules = array("login" => "required");
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            if (isset($request->id)) {
                DB::table("profile")->where("id", $request->id)->update(
                    ["login" => $request->login, "user" => $request->user, "category" => serialize($request->category)]
                );
                $request->session()->flash('status', "Profile has been updated successfuly.");
            } else {
                DB::table("profile")->insert(
                    ["login" => $request->login, "user" => $request->user, "category" => serialize($request->category)]
                );
                $request->session()->flash('status', "Profile has been added successfuly.");

            }
            return redirect()->back();
        }
    }

    function manageProfile(Request $request)
    {
        if (isset($request->id)) {
            DB::table("profile")->where("id", $request->id)->delete();
            $request->session()->flash('status', "Category has been deleted successfuly.");
            return redirect()->back();
        }
        $profiles = DB::table("profile")->join("users", "users.id", "profile.user")->select(DB::raw("profile.id, profile.login,profile.user,profile.category,users.first_name,users.email"))->orderBy("profile.id")->get();
        foreach ($profiles as $key => $profile) {
            $categories = unserialize($profile->category);
            $categ_array = [];
            foreach ($categories as $key2 => $categ) {
                $categ_array[] = DB::table("task_category")->where("id", $categ)->first();
            }
            $profiles[$key]->categories = $categ_array;
        }
        $categories = DB::table("task_category")->get();
        $users = DB::table("users")->where("user_type_id", "<>", "1")->get();
        return view('admin.community_manager.manage_profile', compact("profiles", "categories", "users"));
    }

    function dailyReportList(Request $request)
    {
        $date_report = date("Y-m-d");
        if (isset($request->date_report) && !empty($request->date_report)) {
            $date_report = date("Y-m-d", strtotime(convertDateWithTiret($request->date_report)));
        }
        $orderBy = "taches.label ASC";
        $reports = DB::table('all_reports')->select(DB::raw("taches.
            label, profile.login,all_reports.value"))->join('profile', "profile.id", "all_reports.profile_id")->join("taches", "taches.id", "all_reports.task_id")->where("date_report", $date_report)->orderByRaw($orderBy)->get();
        $date_report = date("d/m/Y", strtotime($date_report));
        return view('admin.community_manager.daily_report_list', compact("reports", "date_report"));
    }

    function linkPhrase(Request $request)
    {
        $phrases = DB::table("link_phrase")->get();
        return view('admin.community_manager.link_phrase', compact("phrases"));
    }

    public function pubCommunity(Request $request)
    {
        $user = getUserAdmin();
        $nb = getConfig("nb_publication");
        $nbJournalier = DB::table('publications_community')->where("id_user", $user->id)->whereRaw("DATE(publications_community.date_enregistrement) = '" . date('Y-m-d') . "'")->count();

        $ids = DB::table("link_phrase")->select("id")->pluck("id")->toArray();
        $phrases = [];
        if (!isPostExpert()) {
            for ($i = 0; $i < $nb; $i++) {
                $phrase = "";
                if (count($ids) > 0) {
                    $min = $ids[0];
                    $max = $ids[count($ids) - 1];
                    $idPhrase = rand($min, $max);
                    $phrase = DB::table('link_phrase')->where("id", $idPhrase)->first()->phrase_fr;
                }
                $phrases[] = $phrase;
            }
        }

        return view('admin.community_manager.publication', compact("phrases", "nb", "nbJournalier"));
    }

    public function ErrorAccesUrl()
    {
        return view('admin.community_manager.erreurAccesUrl');
    }

    function managePhrase(Request $request)
    {
        $phrases = $request->phrase_fr;
        DB::table("link_phrase")->delete();
        if (count($phrases) > 0) {
            if (!empty($phrases[0])) {
                foreach ($phrases as $key => $phrase) {
                    if (!empty($phrase)) {
                        DB::table("link_phrase")->insert([
                            "phrase_fr" => $phrase
                        ]);
                    }

                }
            }

        }
        $request->session()->flash('status', "Saved successfuly.");
        return redirect()->back();
    }

    function regex(Request $request)
    {
        $phrases = DB::table("regex_contact")->get();
        return view('admin.community_manager.regex', compact("phrases"));
    }

    function manageRegex(Request $request)
    {
        if (isset($request->delete_regex)) {
            DB::table("regex_contact")->where('id', $request->delete_regex)->delete();
            $request->session()->flash('status', "Deleted successfuly.");
            return redirect()->back();
        }
        if (isset($request->nom_regex)) {
            DB::table("regex_contact")->insert([
                "name" => $request->nom_regex,
                "regex" => $request->text_regex
            ]);
            $request->session()->flash('status', "Added successfuly.");
            return redirect()->back();
        }
        $regexs = $request->regex;
        $i = 1;
        foreach ($regexs as $key => $regex) {
            if (!empty($regex)) {
                DB::table("regex_contact")->where("id", $i)->update([
                    "regex" => $regex
                ]);
            }
            $i++;
        }
        $request->session()->flash('status', "Saved successfuly.");
        return redirect()->back();
    }

    private function annuler_derniere_slash($lien)
    {
        return rtrim($lien, '/');
    }

    private function to_posts($lien)
    {
        if (strpos($lien, '/?multi_permalinks='))
            $resolu = str_replace('/?multi_permalinks=', '/posts/', $lien);
        else
            $resolu = str_replace('?multi_permalinks=', '/posts/', $lien);
        return $resolu;
    }

    private function get_id_publication($lien)
    {
        $table = explode('/', $lien);
        return end($table);
    }

    private function lien_correct($lien)
    {
        return $this->annuler_derniere_slash($this->to_posts($lien));
    }

    public function savePublication(Request $request)
    {
        $request->lien = $this->lien_correct($request->lien);
        $lienk1 = $request->lien;
        $now = Carbon::now()->addHours(1);

        $pubCheck = DB::table("publications_community")->whereRaw("lien LIKE '%posts/" . $this->get_id_publication($request->lien) . "%'")->first();

        $date_48 = date('d.m.Y H:i:s', strtotime('-2 days'));

        //pour ameliorer il faut verifier aussi l'id du groupe

        if (is_null($pubCheck)) {

            //Controle groupe facebook
            $facebook = NULL;
            $page = NULL;


            if (preg_match("/\bmulti_permalinks\b/i", $request->lien)) {
                $request->lien = strstr($request->lien, '/?multi_permalinks', true);
            }
            if (preg_match("/\bfacebook.com\b/i", $request->lien)) {
                $facebook = TRUE;
                $page = NULL;

                if (preg_match("/\bpermalink\b/i", $request->lien)) {
                    $lienok = strstr($request->lien, '/permalink/', true);
                } else {
                    $lienok = $request->lien;
                }

                if (preg_match("/\bposts\b/i", $request->lien)) {
                    $lienok = strstr($request->lien, '/posts/', true);
                    $page = TRUE;
                }

                DB::table("publications_community_fb")->insert(
                    [
                        "id_user" => $request->session()->get("ADMIN_USER")->id,
                        "lien" => $lienok,
                        "texte" => $request->text,
                        "status" => $request->status,
                        "proxy" => $request->proxy,
                        "login" => $request->login,
                        "mdp" => $request->mdp,
                        "facebook" => $facebook,
                        "date_enregistrement" => $now,
                        "page" => $page

                    ]
                );

            }


            DB::table("publications_community")->insert(
                [
                    "id_user" => $request->session()->get("ADMIN_USER")->id,
                    "lien" => $lienk1,
                    "texte" => $request->text,
                    "status" => $request->status,
                    "proxy" => $request->proxy,
                    "login" => $request->login,
                    "mdp" => $request->mdp,
                    "facebook" => $facebook,
                    "date_enregistrement" => $now,
                    "page" => $page
                ]
            );


            //verification lien
            $lien = $request->lien;
            if (stristr($lien, 'https://') === FALSE) {
                $lien = "https://" . $lien;
            }
            if (stristr($lien, "https://http://") === FALSE) {

            } else {

                $lien = substr($lien, 15);
                $lien = "https://" . $lien;

            }
            $parse = parse_url($lien);
            $lienParse = $parse['host'];
            if (strcmp($lienParse, "facebook.com") == 0) {
                $lienParse = "www." . $lienParse;

            }
            //Entrer Stat
            DB::table("publications_community_stat")->insert(
                [
                    "id_user" => $request->session()->get("ADMIN_USER")->id,
                    "lien" => $lienParse,
                    "texte" => $request->text,
                    "status" => $request->status,
                    "proxy" => $request->proxy,
                    "login" => $request->login,
                    "date_enregistrement" => $now,
                    "mdp" => $request->mdp
                ]
            );

            $response['error'] = 'no';
            //  return true;
        } else {
            $response['error'] = 'yes';
            $timestamp1 = strtotime($pubCheck->date_enregistrement);
            $timestamp2 = strtotime($date_48);

            if ($timestamp1 <= $timestamp2) {
                /////////////////////////////////////////
                //Controle groupe facebook
                $facebook = NULL;
                $page = NULL;
                if (preg_match("/\bfacebook.com\b/i", $request->lien)) {
                    $facebook = TRUE;
                    $page = NULL;

                    if (preg_match("/\bpermalink\b/i", $request->lien)) {
                        $lienok = strstr($request->lien, '/permalink/', true);
                    } else {
                        $lienok = $request->lien;
                    }

                    if (preg_match("/\bposts\b/i", $request->lien)) {
                        $lienok = strstr($request->lien, '/posts/', true);
                        $page = TRUE;
                    }

                    DB::table("publications_community_fb")->insert(
                        [
                            "id_user" => $request->session()->get("ADMIN_USER")->id,
                            "lien" => $lienok,
                            "texte" => $request->text,
                            "status" => $request->status,
                            "proxy" => $request->proxy,
                            "login" => $request->login,
                            "mdp" => $request->mdp,
                            "facebook" => $facebook,
                            "date_enregistrement" => $now,
                            "page" => $page

                        ]
                    );

                }


                DB::table("publications_community")->insert(
                    [
                        "id_user" => $request->session()->get("ADMIN_USER")->id,
                        "lien" => $lienk1,
                        "texte" => $request->text,
                        "status" => $request->status,
                        "proxy" => $request->proxy,
                        "login" => $request->login,
                        "mdp" => $request->mdp,
                        "facebook" => $facebook,
                        "date_enregistrement" => $now,
                        "page" => $page
                    ]
                );


                //verification lien
                $lien = $request->lien;
                if (stristr($lien, 'https://') === FALSE) {
                    $lien = "https://" . $lien;
                }
                if (stristr($lien, "https://http://") === FALSE) {

                } else {

                    $lien = substr($lien, 15);
                    $lien = "https://" . $lien;

                }
                $parse = parse_url($lien);
                $lienParse = $parse['host'];
                if (strcmp($lienParse, "facebook.com") == 0) {
                    $lienParse = "www." . $lienParse;

                }
                //Entrer Stat
                DB::table("publications_community_stat")->insert(
                    [
                        "id_user" => $request->session()->get("ADMIN_USER")->id,
                        "lien" => $lienParse,
                        "texte" => $request->text,
                        "status" => $request->status,
                        "proxy" => $request->proxy,
                        "login" => $request->login,
                        "date_enregistrement" => $now,
                        "mdp" => $request->mdp
                    ]
                );
                /////////////////////////////////////////
                $response['error'] = 'no';
            }


        }
        //FIN
        return response()->json($response);
    }


    public function pubCommunityList(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }

        $search_query['sort'] = null;
        $search_query['desc'] = null;
        $orderBy = "publications_community.id asc";
        if (isset($request->sort)) {
            $search_query['sort'] = $request->sort;
            $orderBy = $request->sort;
            if (isset($request->desc)) {
                $search_query['desc'] = $request->desc;
                $orderBy .= " " . $request->desc;
            } else {
                $orderBy .= " asc";
            }
        }

        if (isAdmin()) {
            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");

        } else {
            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");

        }

        $status = null;
        if (!empty($request->status)) {
            $search_query['status'] = $request->status;
            $pub = $pub->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $status = $request->status;
        }

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community.id_user", $request->user);
            $currentUser = $request->user;
        }
        if (!empty($request->stati)) {
            $stati = $request->stati;

        } else {
            $stati = 20;

        }
        $data = $pub->orderByRaw($orderBy)->paginate($stati);


        return view('admin.community_manager.publication_list', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser", "status", "stati"));
    }


    public function pubByCommunity(Request $request)
    {

        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }


        $pub = DB::table('publications_community_stat')
            ->select('users.id as idd', 'first_name', DB::raw('count(first_name) as total'))
            ->join('users', "users.id", "publications_community_stat.id_user")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy("first_name")
            ->orderByRaw("total desc");


        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_stat.id_user", $request->user);
            $currentUser = $request->user;
        }
        $data = $pub->get();
        $total = 0;

        foreach ($data as $key => $value) {
            $total = $total + $value->total;
        }
        $starts = $search_query['start'];
        $ends = $search_query['end'];
        return view('admin.community_manager.publication_stat_lien', compact("starts", "ends", "data", "search_query", 'start_date', 'end_date', "users", "currentUser", "total"));

    }


    public function monthlyCheck(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }


        $pub = DB::table('publications_community_stat')
            ->select('first_name', DB::raw('count(DISTINCT(DATE(publications_community_stat.date_enregistrement))) as total'))
            ->join('users', "users.id", "publications_community_stat.id_user")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy("first_name")
            ->orderByRaw("total desc");


        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_stat.id_user", $request->user);
            $currentUser = $request->user;
        }
        $data = $pub->get();
        $total = 0;

        foreach ($data as $key => $value) {
            $total = $total + $value->total;
        }

        return view('admin.community_manager.publication_stat_moun', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser", "total"));

    }

    public function dateWork(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }


        $pub = DB::table('publications_community_stat')
            ->select('users.id as idd', 'first_name', DB::raw('count(first_name) as total'))
            ->join('users', "users.id", "publications_community_stat.id_user")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy("first_name")
            ->orderByRaw("total desc");


        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_stat.id_user", $request->user);
            $currentUser = $request->user;
        }
        $data = $pub->get();
        $total = 0;

        foreach ($data as $key => $value) {
            $total = $total + $value->total;
        }
        $starts = $search_query['start'];
        $ends = $search_query['end'];
        if (!empty($request->work)) {
            $work = $request->work;
        } else {
            $work = 2;
        }

        return view('admin.community_manager.publication_stat_work', compact("work", "starts", "ends", "data", "search_query", 'start_date', 'end_date', "users", "currentUser", "total"));

    }


    public function pubCommunityListCheck(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }
        $search_query['sort'] = null;
        $search_query['desc'] = null;
        $orderBy = "publications_community.id desc";
        if (isset($request->sort)) {
            $search_query['sort'] = $request->sort;
            $orderBy = $request->sort;
            if (isset($request->desc)) {
                $search_query['desc'] = $request->desc;
                $orderBy .= " " . $request->desc;
            } else {
                $orderBy .= " asc";
            }
        }

        if (isAdmin()) {
            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
            $pubO = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
            $pubK = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
            $pub1 = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
            $pubn = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");

        } else {
            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
            $pubO = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
            $pubK = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
            $pub1 = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
            $pubn = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");
        }

        $status = null;
        if (!empty($request->status)) {
            $search_query['status'] = $request->status;
            $pub = $pub->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubO = $pubO->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubK = $pubK->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pub1 = $pub1->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubn = $pubn->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $status = $request->status;
        }

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community.id_user", $request->user);
            $pubO = $pubO->where("publications_community.id_user", $request->user);
            $pubK = $pubK->where("publications_community.id_user", $request->user);
            $pub1 = $pub1->where("publications_community.id_user", $request->user);
            $pubn = $pubn->where("publications_community.id_user", $request->user);
            $currentUser = $request->user;
        }
        if (!empty($request->stati)) {
            $stati = $request->stati;
        } else {
            $stati = 20;
        }
        $countStatOk = $pubO->where("publications_community.status_verifie", 1)->get()->count();
        $countStatKo = $pubK->where("publications_community.status_verifie", -1)->get()->count();
        $countStatN = $pubn->where("publications_community.status_verifie", 0)->get()->count();
        $countVerif = $countStatOk + $countStatKo;
        $countTotal = $pub1->get()->count();

        if ($countTotal == 0) {
            $CountOk = 0;
            $CountKo = 0;
            $CountN = 0;
            $countStatOk = 0;
            $countStatKo = 0;
            $countStatN = 0;
            $CountVe = 0;
        } else {
            $CountOk = ($countStatOk / $countTotal) * 100;
            $CountKo = ($countStatKo / $countTotal) * 100;
            $CountN = ($countStatN / $countTotal) * 100;

        }

        if ($countVerif == 0) {
            $CountVe = 0;
        } else {
            $CountVe = ($countStatOk / $countVerif) * 100;
        }


        $CountOk = round($CountOk, 3);
        $CountKo = round($CountKo, 3);
        $CountN = round($CountN, 3);
        $CountVe = round($CountVe, 3);

        $data = $pub->orderByRaw($orderBy)->paginate($stati);
        $starts = $search_query['start'];
        $ends = $search_query['end'];
        return view('admin.community_manager.publication_check', compact("starts", "ends", "data", "search_query", 'start_date', 'end_date', "users", "currentUser", "status", "stati", "CountOk", "CountKo", "CountN", "CountVe"));
    }

    //function utilisé pour la courbe
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

    private function tabTaux($tab)
    {
        $taux = [];
        for ($i = 0; $i < count($tab[0]); $i++) {
            if ($tab[1][$i] == 0) {
                $tab[1][$i] = 1;
            }
            $taux[$i] = round((($tab[0][$i]) / ($tab[1][$i]) * 100));
        }
        return $taux;
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

    private function getStatPubCommunityVerify($nb = -7, $date_limit = null, $request)
    {

        $i = $nb;
        $result = [];
        $result_tout = [];
        $result_final = [];
        $l = 0;  //no request
        if (!empty($request->user) && empty($request->status))  //avec user sans satus
        {
            $l = 1;
        } else if (empty($request->user) && !empty($request->status)) //avec status sans user
        {
            $l = 2;
        } else if (!empty($request->status) && !empty($request->user)) {
            //avec user et staus
            $l = 3;
        } else  //sans user et status
        {
            $l = 0;
        }
        // return [$request->status,$request->user];
        while ($i <= 0) {

            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . getDateByDecalage($i, $date_limit) . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . getDateByDecalage($i, $date_limit) . "'");

            if ($l == 0) {
                //sans user selectionné
                $statement = $statement = 'select count(id) as nb from publications_community where status_verifie !="0" and date(date_enregistrement) = "' . getDateByDecalage($i, $date_limit) . '"' . 'and id_user="' . getUserAdmin()->id . '"';
                $pub = $pub->where("publications_community.id_user", getUserAdmin()->id);
            } else if ($l == 1) {
                //avec user selectionée
                $statement = $statement = 'select count(id) as nb from publications_community where status_verifie !="0" and date(date_enregistrement) = "' . getDateByDecalage($i, $date_limit) . '"' . 'and id_user="' . $request->user . '"';
                $pub = $pub->where("publications_community.id_user", $request->user);
            } else if ($l == 2) {
                //avec status, sans user
                $statement = $statement = 'select count(id) as nb from publications_community where status_verifie !="0" and date(date_enregistrement) = "' . getDateByDecalage($i, $date_limit) . '"' . 'and id_user="' . getUserAdmin()->id . '"' . 'and LOWER(status)="' . strtolower($request->status) . '"';
                $pub = $pub->where("publications_community.id_user", getUserAdmin()->id)
                    ->whereRaw("LOWER(publications_community.status)='" . strtolower($request->status) . "'");
            } else {
                $statement = $statement = 'select count(id) as nb from publications_community where status_verifie !="0" and date(date_enregistrement) = "' . getDateByDecalage($i, $date_limit) . '"' . 'and id_user="' . $request->user . '"' . 'and LOWER(status)="' . strtolower($request->status) . '"';
                $pub = $pub->where("publications_community.id_user", $request->user)
                    ->whereRaw("LOWER(publications_community.status)='" . strtolower($request->status) . "'");
            }
            $data = DB::select(DB::raw($statement));
            $nb = $data[0]->nb;
            $nb_tout = $pub->get()->count();
            $result[] = $nb;
            $result_tout[] = $nb_tout;
            $i = $i + 1;
        }
        $result_final[0] = $result;
        $result_final[1] = $result_tout;
        return $result_final;
    }

    //________________________________________________________________________________________
    public function listStatVerifieData(Request $request)
    {
        $nbDay = $this->calculNbDayChart($request->date_debut, $request->date_limit);
        $date_limit = convertDateWithTiret($request->date_limit);
        $xAxis = $this->dateAxis($nbDay, $date_limit);
        $pubStat = $this->getStatPubCommunityVerify($nbDay, $date_limit, $request);
        $pubStat = $this->tabTaux($pubStat);
        $result = [
            "xAxis" => $xAxis,
            "statut" => $pubStat,

        ];
        echo json_encode($result);
    }
    /***----------------------------------------------------------- */
    /*****  selectioner les status_verified non zero */
    public function graphcommunitylistActiveStatus(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();

        if (!empty($request->start) && !empty($request->end)) {
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }
        //---------------------------------------
        $status = null;
        if (!empty($request->status)) {
            $status = $request->status;
        }

        $currentUser = null;
        if (!empty($request->user)) {
            $currentUser = $request->user;
        }

        return view('admin.community_manager.publication_check_graph', compact('start_date', 'end_date', "users", "currentUser", "status"));

    }

    public function communitylistActiveStatus(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();

        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }

        $search_query['sort'] = null;
        $search_query['desc'] = null;
        $orderBy = "publications_community.id desc";
        if (isset($request->sort)) {
            $search_query['sort'] = $request->sort;
            $orderBy = $request->sort;
            if (isset($request->desc)) {
                $search_query['desc'] = $request->desc;
                $orderBy .= " " . $request->desc;
            } else {
                $orderBy .= " asc";
            }
        }
        //---------------------------------------
        if (isAdmin()) {
            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
            $pubO = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
            $pubK = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
            $pub1 = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
            $pubn = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");

        } else {
            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
            $pubO = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
            $pubK = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
            $pub1 = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
            $pubn = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie !='0' ");
        }

        $status = null;
        if (!empty($request->status)) {
            $search_query['status'] = $request->status;
            $pub = $pub->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubO = $pubO->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubK = $pubK->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pub1 = $pub1->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubn = $pubn->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $status = $request->status;
        }

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community.id_user", $request->user);
            $pubO = $pubO->where("publications_community.id_user", $request->user);
            $pubK = $pubK->where("publications_community.id_user", $request->user);
            $pub1 = $pub1->where("publications_community.id_user", $request->user);
            $pubn = $pubn->where("publications_community.id_user", $request->user);
            $currentUser = $request->user;
        }
        if (!empty($request->stati)) {
            $stati = $request->stati;
        } else {
            $stati = 20;
        }
        $countStatOk = $pubO->where("publications_community.status_verifie", 1)->get()->count();
        $countStatKo = $pubK->where("publications_community.status_verifie", -1)->get()->count();
        $countStatN = $pubn->where("publications_community.status_verifie", 0)->get()->count();
        $countVerif = $countStatOk + $countStatKo;
        $countTotal = $pub1->get()->count();

        if ($countTotal == 0) {
            $CountOk = 0;
            $CountKo = 0;
            $CountN = 0;
            $countStatOk = 0;
            $countStatKo = 0;
            $countStatN = 0;
            $CountVe = 0;
        } else {
            $CountOk = ($countStatOk / $countTotal) * 100;
            $CountKo = ($countStatKo / $countTotal) * 100;
            $CountN = ($countStatN / $countTotal) * 100;

        }

        if ($countVerif == 0) {
            $CountVe = 0;
        } else {
            $CountVe = ($countStatOk / $countVerif) * 100;
        }

        $CountOk = round($CountOk, 3);
        $CountKo = round($CountKo, 3);
        $CountN = round($CountN, 3);
        $CountVe = round($CountVe, 3);
        $data = $pub->orderByRaw($orderBy)->paginate($stati);
        $starts = $search_query['start'];
        $ends = $search_query['end'];
        return view('admin.community_manager.publication_check', compact("starts", "ends", "data", "search_query", 'start_date', 'end_date', "users", "currentUser", "status", "stati", "CountOk", "CountKo", "CountN", "CountVe"));

    }


    public function communitylistRougeStatus(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();

        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }

        $search_query['sort'] = null;
        $search_query['desc'] = null;
        $orderBy = "publications_community.id desc";
        if (isset($request->sort)) {
            $search_query['sort'] = $request->sort;
            $orderBy = $request->sort;
            if (isset($request->desc)) {
                $search_query['desc'] = $request->desc;
                $orderBy .= " " . $request->desc;
            } else {
                $orderBy .= " asc";
            }
        }
        //---------------------------------------
        if (isAdmin()) {
            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
            $pubO = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
            $pubK = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
            $pub1 = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
            $pubn = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");

        } else {
            $pub = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
            $pubO = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
            $pubK = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
            $pub1 = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
            $pubn = DB::table('publications_community')
                ->select('publications_community.*', "users.first_name")
                ->join('users', "users.id", "publications_community.id_user")
                ->where("id_user", $user->id)
                ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
                ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'")
                ->whereRaw("publications_community.status_verifie ='-1' ");
        }

        $status = null;
        if (!empty($request->status)) {
            $search_query['status'] = $request->status;
            $pub = $pub->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubO = $pubO->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubK = $pubK->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pub1 = $pub1->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $pubn = $pubn->whereRaw("LOWER(publications_community.status) = '" . strtolower($request->status) . "'");
            $status = $request->status;
        }

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community.id_user", $request->user);
            $pubO = $pubO->where("publications_community.id_user", $request->user);
            $pubK = $pubK->where("publications_community.id_user", $request->user);
            $pub1 = $pub1->where("publications_community.id_user", $request->user);
            $pubn = $pubn->where("publications_community.id_user", $request->user);
            $currentUser = $request->user;
        }
        if (!empty($request->stati)) {
            $stati = $request->stati;
        } else {
            $stati = 20;
        }
        $countStatOk = $pubO->where("publications_community.status_verifie", 1)->get()->count();
        $countStatKo = $pubK->where("publications_community.status_verifie", -1)->get()->count();
        $countStatN = $pubn->where("publications_community.status_verifie", 0)->get()->count();
        $countVerif = $countStatOk + $countStatKo;
        $countTotal = $pub1->get()->count();

        if ($countTotal == 0) {
            $CountOk = 0;
            $CountKo = 0;
            $CountN = 0;
            $countStatOk = 0;
            $countStatKo = 0;
            $countStatN = 0;
            $CountVe = 0;
        } else {
            $CountOk = ($countStatOk / $countTotal) * 100;
            $CountKo = ($countStatKo / $countTotal) * 100;
            $CountN = ($countStatN / $countTotal) * 100;

        }

        if ($countVerif == 0) {
            $CountVe = 0;
        } else {
            $CountVe = ($countStatOk / $countVerif) * 100;
        }

        $CountOk = round($CountOk, 3);
        $CountKo = round($CountKo, 3);
        $CountN = round($CountN, 3);
        $CountVe = round($CountVe, 3);
        $data = $pub->orderByRaw($orderBy)->paginate($stati);
        $starts = $search_query['start'];
        $ends = $search_query['end'];
        return view('admin.community_manager.publication_check_red', compact("starts", "ends", "data", "search_query", 'start_date', 'end_date', "users", "currentUser", "status", "stati", "CountOk", "CountKo", "CountN", "CountVe"));

    }

    /**--------------------------------- */

    public function activeDeactiveAds(Request $request)
    {
        $AdsId = $request->AdsId;
        $status = $request->status;
        $now = Carbon::now()->addHours(1);
        if ($status == 1) {
            $msg = 'ADS ok successfuly.';
            $msgType = 'status';
        } else {
            $msg = 'ADS ko successfuly.';
            $msgType = 'status';
        }


        $queryStatus = DB::table("publications_community")->where("id", base64_decode($AdsId))->update([
            "status_verifie" => $status,
            "date_verification" => $now,
        ]);

        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }

        return response()->json(array('error' => 'no', 'msg' => $msg));
    }


    public function emploi_temp_comunity()
    {
        $aujoud_hui = date('Y-m-d');
        /*SELECT * FROM `users` WHERE `user_type_id` = 2*/
        /** Selection de tous les users de type 2 */
        $lists_users = DB::table('users')
            ->where('user_type_id', '=', 2)->get();

        $lists_emploi_temps = DB::table('emploi_du_temps')
            ->join('users', 'emploi_du_temps.id_users', '=', 'users.id')
            ->join('heures', 'emploi_du_temps.id_heure', '=', 'heures.id_heure')
            ->join('jours', 'heures.id_jour', '=', 'jours.id_jour')
            ->select('users.first_name', 'emploi_du_temps.*', 'heures.*', 'jours.*')
            ->get();
        $lists_emploi_temps = $lists_emploi_temps->sortBy(function ($value, $key) {
            return $value->id_jour;
        });
        $lists_emploi_temps = $lists_emploi_temps->values()->all();
        $donnes = array();
        $temps = array();

        foreach ($lists_emploi_temps as $l) {

            if ($l->id_users = $l->id_users) {
                $temp = [
                    'id_heure' => $l->id_heure,
                    'h_deb_1' => $l->h_deb_1,
                    'h_deb_2' => $l->h_deb_2,
                    'h_fin_1' => $l->h_fin_1,
                    'h_fin_2' => $l->h_fin_2,
                    'id_jour' => $l->id_jour,
                    'jour' => $l->jour
                ];
                $donne = [$l->id_users => $temp, 'first_name' => $l->first_name];
                array_push($donnes, $donne);
            }
        }

        $data_emploi_temps = array();
        foreach ($donnes as $key => $value) {
            foreach ($value as $key_v => $value_v) {
                $data_emploi_temps[$key_v]['first_name'] = $value['first_name'];
                if ($key_v != "first_name") {
                    $data_emploi_temps[$key_v]['employs_temps'][] = $value_v;
                }
                unset($data_emploi_temps['first_name']);
            }
        }

        $jours = DB::table('jours')->select('*')->get();

        return view('admin.community_manager.emploi_temps_comunity', compact('lists_users', 'data_emploi_temps', 'jours'));
    }

    public function saveEmploiTempComunity(Request $request)
    {
        $comptJ = DB::table('emploi_du_temps')->where('id_users', '=', $request->id_user)->count();
        /*on regarde si la journée est deja pris
        *si c'est 0, on peut ajouter un emploi du temps
        *et si c'est 1 alors l'insertion d'emploi du temps sur ce journé n'est pas faisable
        */
        $no_jour_redondence = DB::table('emploi_du_temps')->where('id_users', '=', $request->id_user)
            ->join('heures', 'emploi_du_temps.id_heure', '=', 'heures.id_heure')
            ->where('heures.id_jour', '=', $request->id_jours)->count();

        if ($comptJ < 7) {
            if ($no_jour_redondence == 0) {
                $id_heures = DB::table('heures')->insertGetId(
                    [
                        "id_heure" => "",//on n'a pas besoin de l'id_heure car c'est AI
                        "h_deb_1" => $request->h_deb_1,
                        "h_deb_2" => $request->h_deb_2,
                        "h_fin_1" => $request->h_fin_1,
                        "h_fin_2" => $request->h_fin_2,
                        "id_jour" => $request->id_jours,
                    ]
                );
                DB::table('emploi_du_temps')->insert(
                    [
                        "id_emploi_temps" => "",//on n'a pas besoin de l'id_emploi_temps car c'est AI
                        "id_users" => $request->id_user,
                        "id_heure" => $id_heures,
                    ]
                );
                $request->session()->flash('status', 'Emploi du temps bien enregistré');
            } else {
                $request->session()->flash('error', 'Son emploi du temps dans ce jour est déjà plein, peux etre vous deviez modifier !');
            }

        } else {
            $request->session()->flash('error', 'Son emploi du temps est déjà plein');
        }

        return redirect()->back();
    }

    public function deleteEmploiTemps($id_heure, Request $request)
    {
        DB::table('emploi_du_temps')->where('id_heure', '=', $id_heure)->delete();
        DB::table('heures')->where('id_heure', '=', $id_heure)->delete();
        $request->session()->flash('status', 'Suppresion avec succès');
        return redirect()->back();
    }

    public function updataEmploiTemps($id_heure, Request $request)
    {
        DB::table("heures")->where("id_heure", $id_heure)->update(
            ["h_deb_1" => $request->h_deb_1,
                "h_fin_1" => $request->h_fin_1,
                "h_deb_2" => $request->h_deb_2,
                "h_fin_2" => $request->h_fin_2]
        );
        $request->session()->flash('status', 'Modification avec succès');
        return redirect()->back();
    }

    public function convertir()
    {
        $pubs = DB::table('publications_community_stat')->get();
        $resultats = array();
        $i = 0;

        foreach ($pubs as $pub) {

            $lien = $pub->lien;

            if (stristr($lien, 'https://') === FALSE) {
                $lien = "https://" . $lien;
                DB::table("publications_community_stat")->where("id", $pub->id)->update([
                    "lien" => $lien,
                ]);
            }

        }


    }


    public function convertir2()
    {
        $pubs = DB::table('publications_community_stat')->get();
        $resultats = array();
        $i = 0;

        foreach ($pubs as $pub) {

            $lien = $pub->lien;

            if (stristr($lien, "https://http://") === FALSE) {


            } else {

                $lien = substr($lien, 15);
                $lien = "https://" . $lien;
                DB::table("publications_community_stat")->where("id", $pub->id)->update([
                    "lien" => $lien,
                ]);

            }


        }


    }

    public function verification()
    {
        $pubs = DB::table('publications_community_fb')->get();
        $resultats = array();
        $i = 0;

        foreach ($pubs as $pub) {

            $lien = $pub->lien;
            if ($pub->page == 0) {
                DB::table("publications_community_fb")->where("id", $pub->id)->update([
                    "page" => NULL,
                ]);
            }
        }


    }


    public function convertir3()
    {

        $pubs = DB::table('publications_community_stat')->get();
        $resultats = array();
        $i = 0;

        foreach ($pubs as $pub) {

            $lien = $pub->lien;
            if (strcmp($lien, "facebook.com") == 0) {
                $lien = "www." . $lien;
            }
        }
    }

    public function statFacebook()
    {
        $pubs = DB::table('publications_community')->get();
        $resultats = array();
        $i = 0;

        foreach ($pubs as $pub) {
            $test = TRUE;
            $lien = $pub->lien;


            if (preg_match("/\bfacebook.com\b/i", $lien)) {
                DB::table("publications_community")->where("id", $pub->id)->update([
                    "facebook" => $test,
                ]);
                if (preg_match("/\bposts\b/i", $lien)) {
                    DB::table("publications_community")->where("id", $pub->id)->update([
                        "page" => $test,
                    ]);
                }
            }

        }
    }


    public function statCommunityList(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }

        $pub = DB::table('publications_community_stat')->select('lien', DB::raw('count(lien) as total'))
            ->orderBy(\DB::raw('count(lien)'), 'DESC')
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy('lien');

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_stat.id_user", $request->user);
            $currentUser = $request->user;
        }
        $data = $pub->get();


        return view('admin.community_manager.publication_stat', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser"));
    }


    public function statCommunityFb(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }


        if (!empty($request->choix)) {

            if ($request->choix == 2) {
                $now = date("Y-m-d");
                $search_query['start'] = strtotime($now . "- 3 months");
                $search_query['end'] = date("Y-m-d");
            }
        }


        $pub = DB::table('publications_community_fb')->select('lien', DB::raw('count(lien) as total'))
            ->orderBy(\DB::raw('count(lien)'), 'DESC')
            ->whereRaw("DATE(publications_community_fb.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_fb.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy('lien');

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_fb.id_user", $request->user);
            $currentUser = $request->user;
        }
        if (!empty($request->choix)) {
            if ($request->choix == 1) {
                $currentChoi = $request->choix;
                $pub = $pub->where("facebook", TRUE)->where("page", null);

            }
            if ($request->choix == 2) {
                $currentChoi = $request->choix;
                $pub = $pub->where("page", TRUE);
            }
        } else {
            $currentChoi = 1;
        }

        $data = $pub->get();


        return view('admin.community_manager.publication_fb', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser", "currentChoi"));
    }

    public function statParCommunity(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }

        $pub = DB::table('publications_community_stat')
            ->select('first_name', DB::raw('count(first_name) as total'))
            ->join('users', "users.id", "publications_community_stat.id_user")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy('first_name')
            ->orderByRaw("total desc");

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_stat.id_user", $request->user);
            $currentUser = $request->user;
        }
        $data = $pub->get();
        $total = 0;

        foreach ($data as $key => $value) {
            $total = $total + $value->total;
        }

        return view('admin.community_manager.publication_stat_comm', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser", "total"));
    }

    public function statParAdsCommunity(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }
        /*
            $pub  = DB::table('publications_community_stat')

            ->orderBy(\DB::raw('count(lien)'), 'DESC')

            ->whereRaw("DATE(publications_community_stat.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_stat.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy('lien');
        */

        $pub = DB::table('ads')
            ->select('first_name', DB::raw('count(first_name) as total'))
            ->join('users', "users.id", "ads.id_user")
            ->whereRaw("DATE(ads.created_at) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(ads.created_at) <= '" . $search_query['end'] . "'")
            ->groupBy("first_name")
            ->orderByRaw("total desc");


        /*
         if(isAdmin())
         {
             $pub = DB::table('publications_community')
             ->select('publications_community.*', "users.first_name")
             ->join('users', "users.id", "publications_community.id_user")
             ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
             ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");

         } else {
             $pub = DB::table('publications_community')
             ->select('publications_community.*', "users.first_name")
             ->join('users', "users.id", "publications_community.id_user")
             ->where("id_user", $user->id)
             ->whereRaw("DATE(publications_community.date_enregistrement) >= '" . $search_query['start'] . "'")
             ->whereRaw("DATE(publications_community.date_enregistrement) <= '" . $search_query['end'] . "'");

         }
         */

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_stat.id_user", $request->user);
            $currentUser = $request->user;
        }
        $data = $pub->get();
        $total = 0;

        foreach ($data as $key => $value) {
            $total = $total + $value->total;
        }

        return view('admin.community_manager.publication_stat_ads_comm', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser", "total"));
    }


    public function doublons(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $now = date("Y-m-d");
            $search_query['start'] = strtotime($now . "- 3 months");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y", $search_query['start']);
            $search_query['start'] = date("d/m/Y", $search_query['start']);
            $end_date = date("d/m/Y");
        }


        $pub = DB::table('publications_community_fb')
            ->select("users.first_name", DB::raw('sum(facebook) as total'))
            ->orderBy(\DB::raw('sum(facebook)'), 'DESC')
            ->join('users', "users.id", "publications_community_fb.id_user")
            ->whereRaw("DATE(publications_community_fb.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_fb.date_enregistrement) <= '" . $search_query['end'] . "'")
            //  ->having('total', '>', 1)
            ->groupBy('users.first_name')
            ->where("facebook", TRUE);
        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_fb.id_user", $request->user);
            $currentUser = $request->user;
        }
        if (!empty($request->choix)) {
            if ($request->choix == 1) {
                $currentChoi = $request->choix;
                $pub = $pub->where("page", null);

            }
            if ($request->choix == 2) {
                $currentChoi = $request->choix;
                $pub = $pub->where("page", TRUE);
            }
        } else {
            $currentChoi = 1;
        }
        $data = $pub->get();
//var_dump($data);
        return view('admin.community_manager.publication_doublons', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser", "currentChoi"));
    }


    public function statParCommunity_test(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y");
            $end_date = date("d/m/Y");
        }


        if (!empty($request->choix)) {

            if ($request->choix == 2) {
                $now = date("Y-m-d");
                $search_query['start'] = strtotime($now . "- 3 months");
                $search_query['end'] = date("Y-m-d");
            }
        }


        $pub = DB::table('publications_community_fb')->select('lien', DB::raw('count(lien) as total'))
            ->orderBy(\DB::raw('count(lien)'), 'DESC')
            ->whereRaw("DATE(publications_community_fb.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_fb.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy('lien');

        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_fb.id_user", $request->user);
            $currentUser = $request->user;
        }
        if (!empty($request->choix)) {
            if ($request->choix == 1) {
                $currentChoi = $request->choix;
                $pub = $pub->where("facebook", TRUE)->where("page", null);

            }
            if ($request->choix == 2) {
                $currentChoi = $request->choix;
                $pub = $pub->where("page", TRUE);
            }
        } else {
            $currentChoi = 1;
        }

        $data = $pub->get();


        return view('admin.community_manager.publication_fb', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser", "currentChoi"));
    }


    public function indicateurVente(Request $request)
    {
        $shorting = [];

        if (!empty($request->short) && !empty($request->type)) {
            $shorting['short'] = $request->short;
            $shorting['type'] = $request->type;

            if ($request->short == 'city') {
                $orderBy = 'u.address_register';
            } elseif ($request->short == 'nbVente') {
                $orderBy = 'pay.amount';
            } else {
                return redirect()->route('admin.indicateur_vente');
            }

            if ($request->type == "ASC" || $request->type == 'asc' || $request->type == "DESC" || $request->type == "desc") {
                $orderBy = $orderBy . ' ' . $request->type;
            } else {
                return redirect()->route('admin.indicateur_vente');
            }
        } else {
            $orderBy = 'u.id DESC';
        }

        if (!empty($request->start) && !empty($request->end)) {

            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;

            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $now = date("Y-m-d", strtotime("now"));
            $search_query['start'] = date('Y-m-d', strtotime('-3 month', strtotime($now)));
            $search_query['end'] = date("Y-m-d");

            $start_date = date("d/m/Y", strtotime('-3 month', strtotime($now)));
            $end_date = date("d/m/Y");
        }

        # Debut
        $cities = DB::table('user_packages as up')
            ->select(DB::raw("u.address_register, pay.amount"))
            ->join('users as u', "u.id", "up.user_id")
            ->join("payments as pay", "pay.id", "up.payment_id")
            ->whereRaw("DATE(pay.created_at) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(pay.created_at) <= '" . $search_query['end'] . "'")
            ->whereNotNull("u.address_register")
            ->orderByRaw($orderBy)
            ->get();

        # Fin

        $all_city = [];
        $all_number = [];

        foreach ($cities as $key => $city) {
            if (array_key_exists(trim(getAdressVilleV2($city->address_register)), $all_city)) {
                $new_value = $all_city[trim(getAdressVilleV2($city->address_register))] + $city->amount / 100;

                $all_city[trim(getAdressVilleV2($city->address_register))] = $new_value;

                $new_number = $all_number[trim(getAdressVilleV2($city->address_register))] + 1;

                $all_number[trim(getAdressVilleV2($city->address_register))] = $new_number;
            } else {
                $all_city[trim(getAdressVilleV2($city->address_register))] = $city->amount / 100;
                $all_number[trim(getAdressVilleV2($city->address_register))] = 1;
            }
        }

        arsort($all_city);

        if (!empty($request->choix)) {
            if ($request->choix == 1) {
                $currentChoi = $request->choix;

            }
            if ($request->choix == 2) {
                $currentChoi = $request->choix;
            }
        } else {
            $currentChoi = 1;
        }

        return view('admin.community_manager.indicateur_vente', compact("all_city", "all_number", "search_query", 'start_date', 'end_date', "currentChoi", 'shorting'));

    }


    public function lienGroup()
    {
        $pubs = DB::table('publications_community')->get();
        $resultats = array();
        $i = 0;

        foreach ($pubs as $pub) {
            $test = TRUE;
            $lien = $pub->lien;

            if (preg_match("/\bfacebook.com\b/i", $lien)) {
                if ($pub->page == FALSE) {
                    $skustr = strstr($lien, '/permalink/', true);

                }
            } else {

            }

        }
    }


    public function saveEditPub(Request $request)
    {
        $id = $request->id;

        //Controle groupe facebook

        DB::table('publications_community')->where("id", $id)->update(
            [
                "lien" => $request->lien,
                "texte" => $request->texte,
                "status" => $request->status,
                "proxy" => $request->proxy,
                "login" => $request->login,
                "mdp" => $request->mdp
            ]
        );
        //verification lien
        $lien = $request->lien;
        if (stristr($lien, 'https://') === FALSE) {
            $lien = "https://" . $lien;
        }
        if (stristr($lien, "https://http://") === FALSE) {

        } else {

            $lien = substr($lien, 15);
            $lien = "https://" . $lien;

        }
        $parse = parse_url($lien);
        $lienParse = $parse['host'];
        if (strcmp($lienParse, "facebook.com") == 0) {
            $lienParse = "www." . $lienParse;

        }
        //entrer dans stat
        DB::table('publications_community_stat')->where("id", $id)->update(
            [
                "lien" => $lienParse,
                "texte" => $request->texte,
                "status" => $request->status,
                "proxy" => $request->proxy,
                "login" => $request->login,
                "mdp" => $request->mdp
            ]
        );
        //FIN
        $request->session()->flash('status', "Modification avec succès");
        return redirect()->back();
    }

    public function deletePub($id, Request $request)
    {
        DB::table('publications_community')->where("id", $id)->delete();
        DB::table('publications_community_stat')->where("id", $id)->delete();
        DB::table('publications_community_fb')->where("id", $id)->delete();
        $request->session()->flash('status', "Supprimé avec succès");
        return redirect()->back();
    }

    public function SaveAllPublication(Request $request)
    {
        $now = Carbon::now();
        $lien = $request->lien;
        $text = $request->text;
        $status = $request->status;
        $login = $request->login;
        $mdp = $request->mdp;
        $proxy = $request->proxy;
        for ($i = 0; $i < count($lien); $i++) {
            if (!is_null($lien[$i])) {
                DB::table("publications_community")->insert(
                    [
                        "id_user" => $request->session()->get("ADMIN_USER")->id,
                        "lien" => $lien[$i],
                        "texte" => $text[$i],
                        "status" => $status[$i],
                        "date_enregistrement" => $now,
                        "proxy" => $proxy[$i],
                        "login" => $login[$i],
                        "mdp" => $mdp[$i]
                    ]
                );
            }
        }

        $request->session()->flash('status', "Enregistré avec succès");
        return redirect()->back();
    }

    public function doublonsTestStat(Request $request)
    {
        $users = getAllCommunity();
        $user = getUserAdmin();
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] = $request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $now = date("Y-m-d");
            $search_query['start'] = strtotime($now . "- 3 months");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("d/m/Y", $search_query['start']);
            $search_query['start'] = date("d/m/Y", $search_query['start']);
            $end_date = date("d/m/Y");
        }
        $pub = DB::table('publications_community_fb')
            ->select("users.first_name", DB::raw('sum(facebook) as total'))
            ->orderBy(\DB::raw('sum(facebook)'), 'DESC')
            ->join('users', "users.id", "publications_community_fb.id_user")
            ->whereRaw("DATE(publications_community_fb.date_enregistrement) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(publications_community_fb.date_enregistrement) <= '" . $search_query['end'] . "'")
            ->groupBy('users.first_name')
            ->where("facebook", TRUE);
        $currentUser = null;
        if (!empty($request->user)) {
            $search_query['user'] = $request->user;
            $pub = $pub->where("publications_community_fb.id_user", $request->user);
            $currentUser = $request->user;
        }
        if (!empty($request->choix)) {
            if ($request->choix == 1) {
                $currentChoi = $request->choix;
                $pub = $pub->where("page", null);

            }
            if ($request->choix == 2) {
                $currentChoi = $request->choix;
                $pub = $pub->where("page", TRUE);
            }
        } else {
            $currentChoi = 1;
        }

        $data = $pub->get();

        return view('admin.community_manager.publication_doublons', compact("data", "search_query", 'start_date', 'end_date', "users", "currentUser", "currentChoi"));
    }

    public function test_mail_admin(Request $request)
    {
        $getEmail = MailErrorAdmin::all();//DB::table('mail_error_admins')->get();$rechercheMail = MailErrorAdmin::where('email',$request->email)->first();

        return view('admin.email_error_admin', compact('getEmail'));
    }

    public function gerer_facture()
    {
        return view('admin.gerer_facture');
    }

    public function facturation(Request $request)
    {
        if ($request->optradio > 0) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|max:100|email',
                'email_admin' => 'required|max:100|email'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());;
            }
        }
        $id_fac = strtotime(now()) - 1644327000;
        $users = DB::table('users')->where('email', $request->email)->first();
        $subject = __('mail.payment_success');

        if ($users) {
            $payment = UserPackage::with(['user', 'package', 'payment'])->where('user_id', $users->id)->orderBy('id', 'DESC')->first();

            if ($payment) {
                $packageDetail = [];
                $UserName = trim($users->first_name) . " " . trim($users->last_name);
                if ($request->ordre_de) {
                    $packageDetail['userName'] = $request->ordre_de;
                } else {
                    $packageDetail['userName'] = $UserName;
                }

                $packageDetail['subject'] = $subject;
                $packageDetail['packageTitle'] = $payment->package->title;
                $packageDetail['packageDuration'] = $payment->package->duration;
                $packageDetail['packageAmount'] = $payment->payment->amount;

                $packageDetail['packageStartDate'] = $payment->start_date;
                $packageDetail['packageEndDate'] = $payment->end_date;
                $packageDetail['unite'] = $payment->package->unite;
                $packageDetail['date'] = date('d-M-Y');
                $packageDetail['adress'] = $users->address_register;
                $packageDetail['id_fac'] = $id_fac;

                //ecriture sur le fichier PDF
                $fichier = PDF::loadView('admin.facture.facture', compact('packageDetail'));

                switch ($request->optradio) {
                    case 0:
                        sendMail($users->email, 'emails.payment', [
                            "packageDetail" => $packageDetail,
                            "subject" => $packageDetail['subject'],
                            "lang" => getLangUser($users->id)
                        ], [
                            "file" => $fichier,
                            "title" => 'Facture.pdf'
                        ]);
                        Session::flash('succes', 'Facture bien envoyé en email de $users->email');
                        return redirect()->back();
                        break;

                    case 1:
                        sendMail($request->email_admin, 'emails.payment', [
                            "packageDetail" => $packageDetail,
                            "subject" => $packageDetail['subject'],
                        ], [
                            "file" => $fichier,
                            "title" => 'Facture.pdf'
                        ]);
                        Session::flash('succes', 'Facture bien envoyé en email admin $users->email_admin');
                        return redirect()->back();
                        break;

                    case 2:
                        sendMail($users->email, 'emails.payment', [
                            "packageDetail" => $packageDetail,
                            "lang" => getLangUser($users->id),
                            "subject" => $packageDetail['subject'],
                        ], [
                            "file" => $fichier,
                            "title" => 'Facture.pdf'
                        ]);
                        sendMail($request->email_admin, 'emails.payment', [
                            "packageDetail" => $packageDetail,
                            "subject"       => isset($this->packageDetail['subject'])?$this->packageDetail['subject']:'payement'
                        ], [
                            "file" => $fichier,
                            "title" => 'Facture.pdf'
                        ]);
                        Session::flash('succes', 'Facture bien envoyé en email admin $users->email_admin');
                        return redirect()->back();
                        break;
                }
            } else {
                Session::flash('error', 'L\'user n\'a pas encore payé un abonnement');
                return redirect()->back();
            }

        } else {
            Session::flash('error', 'L\'email de cet user n\'est pas encore enregistré');
            return redirect()->back();
        }
    }

    public function searchUserSansmail(Request $request)
    {
        $users = User::where('first_name', 'like', "%$request->user_name%")
                    ->orWhere('last_name', 'like', "%$request->user_name%")->get();
        if($users->count() != 0){
            return view('admin.facture.listeUserSansMail',compact('users'));
        }else{
            Session::flash('errorSansUser', 'On ne trouve pas le nom de l\'user que vous voulez chercher');
            return redirect()->back();
        }
    }

    public function factureSansMail(Request $request)
    {
        $id_fac = strtotime(now()) - 1644327000;
        $subject = __('mail.payment_success');
        $user_packages = DB::table('user_packages')->where('user_id',$request->user_id)->first();

        if ($user_packages) {
            $payment = UserPackage::with(['user', 'package', 'payment'])->where('user_id', $request->user_id)->orderBy('id', 'DESC')->first();

            if ($payment) {
                $packageDetail = [];
                $packageDetail['userName'] = $request->ordre_de;

                $packageDetail['subject'] = $subject;
                $packageDetail['packageTitle'] = $payment->package->title;
                $packageDetail['packageDuration'] = $payment->package->duration;
                $packageDetail['packageAmount'] = $payment->payment->amount;

                $packageDetail['packageStartDate'] = $payment->start_date;
                $packageDetail['packageEndDate'] = $payment->end_date;
                $packageDetail['unite'] = $payment->package->unite;
                $packageDetail['date'] = date('d-M-Y');
                $packageDetail['id_fac'] = $id_fac;

                //ecriture sur le fichier PDF
                $fichier = PDF::loadView('admin.facture.facture', compact('packageDetail'));

                switch ($request->optradio) {
                    case 0:
                        sendMail($request->email_user, 'emails.payment', [
                            "packageDetail" => $packageDetail,
                            "subject" => $packageDetail['subject'],
                            "lang" => getLangUser($request->user_id)
                        ], [
                            "file" => $fichier,
                            "title" => 'Facture.pdf'
                        ]);
                        Session::flash('success', 'Facture bien envoyé en email de'.$request->email_user);
                        return redirect("/admin2021/Gerer_Facture");
                        break;

                    case 1:
                        sendMail($request->email_admin, 'emails.payment', [
                            "packageDetail" => $packageDetail,
                            "subject" => $packageDetail['subject'],
                        ], [
                            "file" => $fichier,
                            "title" => 'Facture.pdf'
                        ]);
                        Session::flash('success', 'Facture bien envoyé en email admin'.$request->email_admin);
                        return redirect("/admin2021/Gerer_Facture");
                        break;

                    case 2:
                        sendMail($request->email_user, 'emails.payment', [
                            "packageDetail" => $packageDetail,
                            "lang" => getLangUser($request->user_id),
                            "subject" => $packageDetail['subject'],
                        ], [
                            "file" => $fichier,
                            "title" => 'Facture.pdf'
                        ]);
                        sendMail($request->email_admin, 'emails.payment', [
                            "packageDetail" => $packageDetail
                        ], [
                            "file" => $fichier,
                            "title" => 'Facture.pdf'
                        ]);
                        Session::flash('success', 'Facture bien envoyé en email admin '.$request->email_admin.' et '.$request->email_user);
                        return redirect("/admin2021/Gerer_Facture");
                        break;
                }
            } else {
                Session::flash('errorSansUser', 'L\'user n\'a pas encore payé un abonnement');
                return redirect("/admin2021/Gerer_Facture");
            }

        } else {
            Session::flash('errorSansUser', 'L\'user n\'a pas encore payé un abonnement');
            return redirect("/admin2021/Gerer_Facture");
        }
    }

    public function saveEmailError(Request $request)
    {
        //verification si l'email est deja dans la base
        $rechercheMail = MailErrorAdmin::where('email', $request->email)->first();

        if (!empty($rechercheMail)) {
            Session::flash('error', 'Email déjà dans la base de donnée');
            return redirect()->back();
        } else {
            $mail_admin = new MailErrorAdmin();
            $mail_admin->email = $request->email;
            $mail_admin->save();
            Session::flash('succes', 'Email bien enregistrer');
            return redirect()->back();
        }
    }

    public function updateEmailError($id_email_error, Request $request)
    {
        $emails = 'email_' . $id_email_error;
        //dd($request->$emails);
        //verification si l'email est deja dans la base
        $rechercheMail = MailErrorAdmin::where('email', $request->$emails)->first();
        if (!empty($rechercheMail)) {
            Session::flash('error_update', 'Email déjà dans la base de donnée');
            return redirect()->back();
        } else {
            //Modification de l'email
            MailErrorAdmin::where('id', $id_email_error)->update(['email' => $request->$emails]);

            Session::flash('update', 'Email bien modifier');
            return redirect()->back();
        }
    }

    public function deleteEmailError($id_email_error, Request $request)
    {
        //Suppression de l'email
        MailErrorAdmin::where('id', $id_email_error)->delete();
        Session::flash('delete', 'Email bien supprimer');
        return redirect()->back();
    }

    //liste des toctoc
    public function liste_toctoc($order_by = 'first-name-1')
    {
        // $test = array_diff(['user'=>124,'user_r'=>321], ['user'=>124,'user_r'=>321]);
        // if(!$test){
        //     dd('true');
        // }else{
        //     dd('false');
        // }


        if ($order_by == 'first-name-1') {
            $order = 'desc';
            $order_sql = 'first_name';
        } elseif ($order_by == 'first-name-0') {
            $order = 'asc';
            $order_sql = 'first_name';
        } else {
            $order = 'asc';
            $order_sql = 'first_name';
        }

        //dd($order_sql);
        $now = date('Y-m-d');
        $date_anterieur = date('Y-m-d', strtotime("-4 days", strtotime($now)));
        $date_timezone = new DateTimeZone("Europe/Paris");
        $date = new DateTime("now", $date_timezone);

        $date_envoi = $date->format("H");
        $date_suivant = $date->format("H") + 1;
        $info_user_post_annonce_coloc = DB::table("users")->where("is_community", 0)->join('ads', 'ads.user_id', '=', 'users.id')->where("scenario_id", "=", 2)->where('status', '1')->whereBetween('ads.updated_at', [$date_anterieur . ' 00:00:00', $now . ' 23:59:59'])->orderBy($order_sql, $order)->get();
        $info_user_chercher_logement = DB::table("users")->where("is_community", 0)->join('ads', 'ads.user_id', '=', 'users.id')->where("scenario_id", "=", 4)->where('status', '1')->whereBetween('ads.updated_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->orderBy($order_sql, $order)->get();
        $info_user_post_annonce_louer_logement = DB::table("users")->where("is_community", 0)->join('ads', 'ads.user_id', '=', 'users.id')->where("scenario_id", "=", 1)->where('status', '1')->whereBetween('ads.updated_at', [$date_anterieur . ' 00:00:00', $now . ' 23:59:59'])->orderBy($order_sql, $order)->get();
        $info_user_post_cherche_louer_logement = DB::table("users")->where("is_community", 0)->join('ads', 'ads.user_id', '=', 'users.id')->where("scenario_id", "=", 3)->where('status', '1')->whereBetween('ads.updated_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->orderBy($order_sql, $order)->get();
        //dd($info_user_post_annonce_coloc);
        $list_toctoc = DB::table('coup_de_foudres')->whereBetween('created_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->get();
        $nb_toctoc = count($list_toctoc);

        // function find_user($donnee_ver, $attributes) {
        //     foreach ($donnee_ver as $key => $user) {
        //         if (array_diff($attributes, $user))
        //         {
        //             return false;
        //         }else{
        //             return true;
        //         }
        //     }
        // }

        function can_take($tab, $index)
        {
            foreach ($tab as $key => $val) {
                if ($val['user_id_send'] == $index['user_id_send'] && $val['user_id_receive'] == $index['user_id_receive'])
                    return false;
            }

            return true;
        }

        $envoi_toctoc = function ($chercher, $post, $now) {
            // dd($post);
            $d = 0;
            $visualiser_toctoc = [];
            $verif_doublon = [];
            $array_list_doublant = [];
            foreach ($post as $s) {//$r = receive
                $j = 0;
                $user_id_send = $s->user_id;
                $first_name_send = $s->first_name;
                $ads_id = $s->id;
                $address_send = $s->address;
                $title_send = $s->title;
                $description_send = $s->description;
                $latitude_send = $s->latitude;
                $longitude_send = $s->longitude;
                $id_annonce_send = $s->id;
                $date_post = $s->updated_at;

                foreach ($chercher as $r) {
                    ++$d;//$s = send
                    $i = 0;
                    $user_id_receive = $r->user_id;
                    $first_name_receive = $r->first_name;
                    $address_receive = $r->address;
                    $title_receive = $r->title;
                    $description_receive = $r->description;
                    $latitude_receive = $r->latitude;
                    $longitude_receive = $r->longitude;
                    $id_annonce_receive = $r->id;
                    $mail_user_receive = $r->email;
                    $date_receive = $r->updated_at;
                    $ads_id_receive = $r->id;
                    /** calcul la distance entre deux ville de l'user_send et l'user_receive */
                    $distance = 6366 * acos(cos(deg2rad($latitude_send)) * cos(deg2rad($latitude_receive)) * cos(deg2rad($longitude_receive) - deg2rad($longitude_send)) + sin(deg2rad($latitude_send)) * sin(deg2rad($latitude_receive)));

                    $distance_roud = round($distance, 4);

                    if ($distance_roud <= 40) {
                        $toctoc_j = DB::table("coup_de_foudres")->where('sender_id', $user_id_send)->where('receiver_id', $user_id_receive)->where('toctoc_auto', 1)->whereBetween('created_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->get();

                        $id_date = DB::table("coup_de_foudres")->where('sender_id', $user_id_send)->where('receiver_id', $user_id_receive)->whereBetween('created_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->first();
                        $nbr_toctoc_j = $toctoc_j->count();
                        //dd($id_date);
                        if ($nbr_toctoc_j < 1) {
                            $status = 'New';
                            $style = 'transparent';
                            $date_envoi = 'en cour';
                        } else {
                            $status = 'OK';
                            $style = '#92e792';
                            $date_envoi = $id_date->created_at;
                        }

                        if ($user_id_send != $user_id_receive) {
                            $donnee = [
                                'user_id_send' => (int)$user_id_send,
                                'user_id_receive' => (int)$user_id_receive,
                                'first_name_send' => $first_name_send,
                                'first_name_receive' => $first_name_receive,
                                'address_send' => $address_send,
                                'address_receive' => $address_receive,
                                'updated_at_post' => $date_post,
                                'updated_at_receive' => $date_receive,
                                'title_send' => $title_send,
                                'title_receive' => $title_receive,
                                'description_send' => $description_send,
                                'description_receive' => $description_receive,
                                'ads_id' => $ads_id,
                                'ads_id_receive' => $ads_id_receive,
                                'style' => $style,
                                'status' => $status,
                                'date_envoi' => $date_envoi,
                                //'send_by' =>$send_by,
                            ];
                            array_push($visualiser_toctoc, $donnee);

                        } else {
                            continue;
                        }

                        foreach ($visualiser_toctoc as $key => $val) {
                            if (can_take($array_list_doublant, $val))
                                $array_list_doublant[] = $val;
                        }

                    }
                }
            }
            $visualiser_toctoc = $array_list_doublant;

            return $visualiser_toctoc;
        };
        $colocation = $envoi_toctoc($info_user_chercher_logement, $info_user_post_annonce_coloc, $now);
        $logement_entier = $envoi_toctoc($info_user_post_cherche_louer_logement, $info_user_post_annonce_louer_logement, $now);
        $nbr = count($colocation) + count($logement_entier);
        //dd($nbr);
        //dd($colocation);
        //$test = array_values(Arr::sortRecursive($colocation, function($value){ return $value['date_envoi'];}));
        $colocation = collect($colocation);
        $logement_entier = collect($logement_entier);

        if ($order_by == 'date-envoi-0') {
            $colocation = $colocation->sortBy('date_envoi');
            $logement_entier = $logement_entier->sortBy('date_envoi');
            $$order_by = 'date-envoi-0';
        }
        if ($order_by == 'date-envoi-1') {
            $colocation = $colocation->sortByDesc('date_envoi');
            $logement_entier = $logement_entier->sortByDesc('date_envoi');
            $order_by = 'date-envoi-1';
        }
        if ($order_by == 'post-ad-created-0') {
            $colocation = $colocation->sortBy('updated_at_post');
            $logement_entier = $logement_entier->sortBy('updated_at_post');
            $$order_by = 'post-ad-created-0';
        }
        if ($order_by == 'post-ad-created-1') {
            $colocation = $colocation->sortByDesc('updated_at_post');
            $logement_entier = $logement_entier->sortByDesc('updated_at_post');
            $order_by = 'post-ad-created-1';
        }
        if ($order_by == 'receive-ad-created-0') {
            $colocation = $colocation->sortBy('updated_at_receive');
            $logement_entier = $logement_entier->sortBy('updated_at_receive');
            $$order_by = 'receive-ad-created-0';
        }
        if ($order_by == 'receive-ad-created-1') {
            $colocation = $colocation->sortByDesc('updated_at_receive');
            $logement_entier = $logement_entier->sortByDesc('updated_at_receive');
            $order_by = 'receive-ad-created-1';
        }
        if ($order_by == 'name-receive-0') {
            $colocation = $colocation->sortBy('user_id_receive');
            $logement_entier = $logement_entier->sortBy('user_id_receive');
            $$order_by = 'name-receive-0';
        }
        if ($order_by == 'name-receive-1') {
            $colocation = $colocation->sortByDesc('user_id_receive');
            $logement_entier = $logement_entier->sortByDesc('user_id_receive');
            $order_by = 'name-receive-name-1';
        }

        $nbr_toctoc_envoi_auto = $nbr_max_toctoc_user = DB::table('coup_de_foudres')->where('toctoc_auto', 1)->whereBetween('created_at', [$date->format("Y-m-d 6:00:00"), $date->format("Y-m-d 23:00:00")])->count();

        //dd($colocation);
        return view('admin.list_toctoc', compact('logement_entier', 'colocation', 'date_envoi', 'date_suivant', 'order_by', 'nbr_toctoc_envoi_auto'));
    }

    public function send_toctoc()
    {
        $now = date('Y-m-d');
        //$date_anterieur = date('Y-m-d', strtotime("-4 days", strtotime($now)));

        $info_user_post_annonce_coloc = DB::table("users")->where("is_community", 0)->join('ads', 'ads.user_id', '=', 'users.id')->where("scenario_id", "=", 2)->where('status', '1')->whereBetween('ads.updated_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->get();
        $info_user_chercher_logement = DB::table("users")->where("is_community", 0)->join('ads', 'ads.user_id', '=', 'users.id')->where("scenario_id", "=", 4)->where('status', '1')->whereBetween('ads.updated_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->get();
        $info_user_post_annonce_louer_logement = DB::table("users")->where("is_community", 0)->join('ads', 'ads.user_id', '=', 'users.id')->where("scenario_id", "=", 1)->where('status', '1')->whereBetween('ads.updated_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->get();
        $info_user_post_cherche_louer_logement = DB::table("users")->where("is_community", 0)->join('ads', 'ads.user_id', '=', 'users.id')->where("scenario_id", "=", 3)->where('status', '1')->whereBetween('ads.updated_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->get();
        //dd($info_user_chercher_logement);

        $envoi_toctoc = function ($chercher, $post, $now) {
            // dd($post);
            $visualiser_toctoc = [];
            foreach ($post as $s) {//$r = receive
                $user_id_send = $s->user_id;
                $first_name_send = $s->first_name;
                $ads_id = $s->id;
                $address_send = $s->address;
                $title_send = $s->title;
                $description_send = $s->description;
                $latitude_send = $s->latitude;
                $longitude_send = $s->longitude;

                foreach ($chercher as $r) {//$s = send
                    $i = 0;
                    $user_id_receive = $r->user_id;
                    $first_name_receive = $r->first_name;
                    $address_receive = $r->address;
                    $title_receive = $r->title;
                    $description_receive = $r->description;
                    $latitude_receive = $r->latitude;
                    $longitude_receive = $r->longitude;
                    /** calcul la distance entre deux ville de l'user_send et l'user_receive */
                    $distance = 6366 * acos(cos(deg2rad($latitude_send)) * cos(deg2rad($latitude_receive)) * cos(deg2rad($longitude_receive) - deg2rad($longitude_send)) + sin(deg2rad($latitude_send)) * sin(deg2rad($latitude_receive)));

                    $distance_roud = round($distance, 4);

                    if ($distance_roud <= 40) {
                        $donnee = [
                            'user_id_send' => $user_id_send,
                            'user_id_receive' => $user_id_receive,
                            'first_name_send' => $first_name_send,
                            'first_name_receive' => $first_name_receive,
                            'address_send' => $address_send,
                            'address_receive' => $address_receive,
                            'title_send' => $title_send,
                            'title_receive' => $title_receive,
                            'description_send' => $description_send,
                            'description_receive' => $description_receive,
                        ];
                        array_push($visualiser_toctoc, $donnee);

                        $toctoc_j = DB::table("coup_de_foudres")->where('sender_id', $user_id_send)->where('receiver_id', $user_id_receive)->whereBetween('created_at', [$now . ' 00:00:00', $now . ' 23:59:59'])->get();
                        //dd($toctoc_j);

                        $nbr_toctoc_j = $toctoc_j->count();

                        if ($nbr_toctoc_j < 1) {
                            $nbr_toctoc_j = $toctoc_j->count();
                            $ad_tracking = DB::table('ad_tracking')->where("user_id", $user_id_send)->where("ads_id", $ads_id)->first();

                            $ad = DB::table('ads')->where("id", $ads_id)->first();
                            if ($ad) {
                                DB::table('ads')->where('id', $ads_id)->update(['toc_toc' => intval($ad->toc_toc) + 1]);
                            }

                            if ($ad_tracking) {
                                if ($ad_tracking->toc_toc != 1) {
                                    DB::table('ad_tracking')->where("id", $ad_tracking->id)->update([
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

                            $userInfo = DB::table('users')->where('id', $user_id_send)->first();

                            $annonce = DB::table('ads')->where('id', $ads_id)->first();

                            $userInfo->ad_title = $annonce->title;

                            $userInfo->ad_id = $ads_id;

                            $userInfo->sender_ad_id = $user_id_send;

                            /** enregistrement dans la base et notification sur toctoc*/
                            $base = DB::table("coup_de_foudres")->insert([
                                "sender_id" => $user_id_send,
                                "receiver_id" => $user_id_receive,
                                "ad_id" => $ads_id,
                                "sender_ad_id" => $ads_id,
                                "created_at" => date("Y-m-d H:i:s"),
                                "read_date" => date("Y-m-d H:i:s"),
                                "checked" => 0,
                                "notif_checked" => 0
                            ]);
                        }
                    }
                    ++$i;
                    if ($i >= 500)
                        break;
                }
                $sleep = rand(30, 59) * 60;
                sleep($sleep);
            }
            return $visualiser_toctoc;
        };
        $envoi_toctoc($info_user_chercher_logement, $info_user_post_annonce_coloc, $now);
        $envoi_toctoc($info_user_post_cherche_louer_logement, $info_user_post_annonce_louer_logement, $now);
        Session::flash('success', 'Tous les toctoc sont envoyé avec succès');

        return back();
    }

}
