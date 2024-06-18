<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Http\Models\Ads\Ads;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Auth;

class ManageAdsController extends Controller {

    private $perpage;

    public function __construct() {
        $this->perpage = 15;
    }

    public function index(Request $request) {

        // $all_users = DB::table('users')->get();
        // foreach($all_users as $s){
        //     if(is_null($s->ip)){
        //         dd("nisy null tao");
        //     }
        // }
        // dd($all_users);

        $select_ad = $where = [];
        $shorting = [];

        //supression d'une annonce
        if(isset($request->ad_id) && !empty($request->ad_id))
        {
            $ad_id = $request->ad_id;
            DB::table('ads')->where('id', $ad_id)->delete();
            $request->session()->flash("status", "Ad deleted succesfully");
            return redirect()->back();
        }

        if (!empty($request->short) && !empty($request->type)) {
            $shorting['short'] = $request->short;
            $shorting['type'] = $request->type;
            if ($request->short == 'date') {
                $orderBy = 'created_at';
            } else {
                return redirect()->route('admin.adList');
            }
            if ($request->type == "ASC" || $request->type == 'asc' || $request->type == "DESC" || $request->type == "desc") {
                $orderBy = $orderBy . ' ' . $request->type;
            } else {
                return redirect()->route('admin.adList');
            }
        }else {
            $orderBy = 'updated_at DESC';
        }

        if (isset($request->ad_type)) {
            $select_ad['ad_type'] = $request->ad_type;
            $select_ad['active'] = $request->active;
            $select_ad['real_user'] = $request->real_user;
            $select_ad['filter_nom'] = $request->filter_nom;
            $select_ad['title'] = $request->title;
            $select_ad['ad_description'] = $request->ad_description;

            $ads = Ads::with('user')->with('ad_details');
            if(!empty($request->ad_type)) {
                $ads = $ads->where("scenario_id", $request->ad_type);
            }
            if(isset($request->title) && !empty($request->title)) {
                $ads = $ads
                ->whereRaw("ads.title LIKE '%".$request->title."%'");

            }

            if(isset($request->ad_description) && !empty($request->ad_description)) {
                $ads = $ads
                ->whereRaw("ads.description LIKE '%".$request->ad_description."%'");

            }

            if(isset($request->active) && $request->active != 2) {
                $ads = $ads->where("admin_approve", $request->active);
            }

            if(isset($request->real_user) && $request->real_user != 2) {
                if($request->real_user == 0) {
                     $ads = $ads->whereNotNull("comunity_id");
                }

                if($request->real_user == 1) {
                     $ads = $ads->whereNull("comunity_id");
                }
            }

            if(isset($request->filter_nom) && !empty($request->filter_nom)) {
                $nomFilter = '%'. strtoupper($request->filter_nom) . '%';
                $ads = $ads->whereHas('user',function ($query) use ($nomFilter) { $query->whereRaw("UPPER(concat(first_name, ' ', last_name)) like ?", [$nomFilter])->orWhereRaw("UPPER(concat(last_name, ' ', first_name)) like ?", [$nomFilter]); });
            }

            $adList = $ads->orderByRaw($orderBy)->paginate($this->perpage);

        } else {
            $adList = Ads::with('user')->with('ad_details')->orderByRaw($orderBy)->paginate($this->perpage);
        }


        return view('admin.ads.listing', compact('adList', 'select_ad', 'shorting'));
    }


    public function indexAlert(Request $request) {
        $select_ad = $where = [];
        $shorting = [];

        //supression d'une annonce
        if(isset($request->ad_id) && !empty($request->ad_id))
        {
            $ad_id = $request->ad_id;
            DB::table('ads')->where('id', $ad_id)->delete();
            $request->session()->flash("status", "Ad deleted succesfully");
            return redirect()->back();
        }

        if (!empty($request->short) && !empty($request->type)) {
            $shorting['short'] = $request->short;
            $shorting['type'] = $request->type;
            if ($request->short == 'date') {
                $orderBy = 'created_at';
            } else {
                return redirect()->route('admin.adList');
            }
            if ($request->type == "ASC" || $request->type == 'asc' || $request->type == "DESC" || $request->type == "desc") {
                $orderBy = $orderBy . ' ' . $request->type;
            } else {
                return redirect()->route('admin.adList');
            }
        }else {
            $orderBy = 'updated_at DESC';
        }

        if (isset($request->ad_type)) {
            $select_ad['ad_type'] = $request->ad_type;
            $select_ad['active'] = $request->active;
            $select_ad['real_user'] = $request->real_user;
            $select_ad['filter_nom'] = $request->filter_nom;
            $ads = Ads::with('user')->with('ad_details');
            if(!empty($request->ad_type)) {
                $ads = $ads->where("scenario_id", $request->ad_type)->where("alert_contact", true);
            }

            if(isset($request->active) && $request->active != 2) {
                $ads = $ads->where("admin_approve", $request->active);
            }

            if(isset($request->real_user) && $request->real_user != 2) {
                if($request->real_user == 0) {
                     $ads = $ads->whereNotNull("comunity_id");
                }

                if($request->real_user == 1) {
                     $ads = $ads->whereNull("comunity_id");
                }
            }

            if(isset($request->filter_nom) && !empty($request->filter_nom)) {
                $nomFilter = '%'. strtoupper($request->filter_nom) . '%';
                $ads = $ads->whereHas('user',function ($query) use ($nomFilter) { $query->whereRaw("UPPER(concat(first_name, ' ', last_name)) like ?", [$nomFilter])->orWhereRaw("UPPER(concat(last_name, ' ', first_name)) like ?", [$nomFilter]); });
            }

            $adList = $ads->orderByRaw($orderBy)->paginate($this->perpage);

        } else {
            $adList = Ads::with('user')->with('ad_details')->where("alert_contact", true)->orderByRaw($orderBy)->paginate($this->perpage);
        }

        return view('admin.ads.listingAlert', compact('adList', 'select_ad', 'shorting'));
    }


    public function boostAd(Request $request) {
        if(!is_null($request->ad_id)) {
            $ad_id = $request->ad_id;
            $user_id = Auth::id();
            $admin = Session::get('ADMIN_USER');
            if(!is_null($user_id) && $user_id != $admin->id) {
                Auth::logout();
                Auth::login($admin, true);
            } else {
               Auth::login($admin, true);
            }
            return redirect("/booster-annonce/ad-" . $ad_id);
        } else {
            return redirect()->back();
        }
    }
    public function trakingAd(Request $request) {
        $select_ad = $where = [];
        $shorting = [];
        $orderBy = '';

        if (!empty($request->short) && !empty($request->type)) {
            $shorting['short'] = $request->short;
            $shorting['type'] = $request->type;
        }
        $type = $request->type;
        switch ($request->type) {
            case 'clic':
                $orderBy = 'total_clic DESC';
                break;
            case 'message':
                $orderBy = 'total_message DESC';
                break;
            case 'toc_toc':
                $orderBy = 'total_toc_toc DESC';
                break;
            case 'phone':
                $orderBy = 'total_phone DESC';
                break;

        }

        if (isset($request->ad_type)) {
            $select_ad['ad_type'] = $request->ad_type;
            $select_ad['active'] = $request->active;
            $select_ad['real_user'] = $request->real_user;
            $select_ad['filter_nom'] = $request->filter_nom;
            $ads = Ads::has('user')->with('user')->leftJoin('ad_tracking', 'ad_tracking.ads_id', '=', 'ads.id')->selectRaw('SUM(ad_tracking.clic) as total_clic, SUM(ad_tracking.message) as total_message, SUM(ad_tracking.toc_toc) as total_toc_toc, SUM(ad_tracking.phone) as total_phone, address')->whereNotNull('ad_tracking.user_id')->groupBy('address');

            $adList = $ads->orderByRaw($orderBy)->paginate($this->perpage);

        } else {
            $adList = Ads::has('user')->with('user')->leftJoin('ad_tracking', 'ad_tracking.ads_id', '=', 'ads.id')->selectRaw('SUM(ad_tracking.clic) as total_clic, SUM(ad_tracking.message) as total_message, SUM(ad_tracking.toc_toc) as total_toc_toc, SUM(ad_tracking.phone) as total_phone, address')->whereNotNull('ad_tracking.user_id')->groupBy('address')->orderByRaw($orderBy)->paginate($this->perpage);
        }
        return view('admin.ads.traking_ad', compact('adList', 'select_ad', 'shorting', 'type'));
    }

    public function popularAd(Request $request) {
        $select_ad = $where = [];
        $shorting = [];
        $orderBy = '';
        $created_at = false;
        if(isset($request->created_at) && $request->created_at == "1") {
            $orderBy = 'ads.created_at DESC,';
            $created_at = true;
        }

        if (!empty($request->short) && !empty($request->type)) {
            $shorting['short'] = $request->short;
            $shorting['type'] = $request->type;
        }
        $type = $request->type;
        switch ($type) {
            case 'clic':
                $orderBy .= 'total_clic DESC';
                break;
            case 'message':
                $orderBy .= 'total_message DESC';
                break;
            case 'toc_toc':
                $orderBy .= 'total_toc_toc DESC';
                break;
            case 'phone':
                $orderBy .= 'total_phone DESC';
                break;
            case 'contact_fb':
                $orderBy .= 'total_contact_fb DESC';
                break;

        }


        if (isset($request->ad_type)) {
            $select_ad['ad_type'] = $request->ad_type;
            $select_ad['active'] = $request->active;
            $select_ad['real_user'] = $request->real_user;
            $select_ad['filter_nom'] = $request->filter_nom;
            $ads = Ads::has('user')
            ->with('user')
            ->leftJoin('ad_tracking', 'ad_tracking.ads_id', '=', 'ads.id')
            ->selectRaw('ads.id as id, ads.title as title, ads.min_rent as min_rent, ads.created_at as created_at, SUM(ad_tracking.clic) as total_clic, SUM(ad_tracking.message) as total_message, SUM(ad_tracking.toc_toc) as total_toc_toc, SUM(ad_tracking.phone) as total_phone, SUM(ad_tracking.contact_fb) as total_contact_fb, address')
            ->groupBy('ad_tracking.ads_id')
            ->whereNotNull('ad_tracking.user_id');


        } else {
            $ads = Ads::has('user')
            ->with('user')
            ->leftJoin('ad_tracking', 'ad_tracking.ads_id', '=', 'ads.id')
            ->selectRaw('ads.id as id, ads.title as title, ads.min_rent as min_rent, ads.created_at as created_at, SUM(ad_tracking.clic) as total_clic, SUM(ad_tracking.message) as total_message, SUM(ad_tracking.toc_toc) as total_toc_toc, SUM(ad_tracking.phone) as total_phone, SUM(ad_tracking.contact_fb) as total_contact_fb, address')
            ->groupBy('ad_tracking.ads_id')
            ->whereNotNull('ad_tracking.user_id');
        }

        if (!empty($request->start) && !empty($request->end)) {
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
            $ads->whereRaw("DATE(ads.created_at) >= '" . $request->start . "'")
                ->whereRaw("DATE(ads.created_at) <= '" . $request->end . "'");
        }

        $adList = $ads->orderByRaw($orderBy)->paginate($this->perpage);


        return view('admin.ads.popular_ad', compact('adList', 'select_ad', 'shorting', 'type', 'created_at', 'start_date', 'end_date'));
    }

    public function signalAd(Request $request) {
        $select_ad = $where = [];
        $shorting = [];
        $dataToSort = [];
        $ad_object = [];
        $orderBy = '';
        $type = $request->type;

        if (!empty($request->short) && !empty($request->type)) {
            $shorting['short'] = $request->short;
            $shorting['type'] = $request->type;
        }

        if (isset($request->ad_type)) {
            $select_ad['ad_type'] = $request->ad_type;
            $select_ad['active'] = $request->active;
            $select_ad['real_user'] = $request->real_user;
            $select_ad['filter_nom'] = $request->filter_nom;
            $ads = Ads::has('user')
            ->with('user')
            ->selectRaw('ads.id as id, ads.title as title, ads.min_rent as min_rent, ads.created_at as created_at, address')->orderBy('created_at', 'desc');
            $adList = $ads->paginate($this->perpage);

        } else {
            $adList = Ads::has('user')
            ->with('user')
            ->selectRaw('ads.id as id, ads.title as title, ads.min_rent as min_rent, ads.created_at as created_at, address')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perpage);
        }

        foreach ($adList as $key => $value) {

            $ad_object["id"] = $value->id;
            $ad_object["title"] = $value->title;
            $ad_object["min_rent"] = $value->min_rent;
            $ad_object["created_at"] = $value->created_at;
            $ad_object["address"] = $value->address;

            $ad_object['total_loue'] = countSignalAd($value->id, 'ad_loue');
            $ad_object['total_no_phone_respond'] = countSignalAd($value->id, 'no_phone_respond');
            $ad_object['total_no_fb_respond'] = countSignalAd($value->id, 'no_fb_respond');
            array_push($dataToSort, $ad_object);
        }

        switch ($type) {
            case 'loue':
                usort($dataToSort, function($a, $b)
                 {
                     if ($a["total_loue"] == $b["total_loue"])
                         return (0);
                     return (($a["total_loue"] < $b["total_loue"]) ? 1 : -1);
                 });
                break;
            case 'no_phone':
                usort($dataToSort, function($a, $b)
                 {
                     if ($a["total_no_phone_respond"] == $b["total_no_phone_respond"])
                         return (0);
                     return (($a["total_no_phone_respond"] < $b["total_no_phone_respond"]) ? 1 : -1);
                 });
                break;
            case 'no_fb':
                usort($dataToSort, function($a, $b)
                 {
                     if ($a["total_no_fb_respond"] == $b["total_no_fb_respond"])
                         return (0);
                     return (($a["total_no_fb_respond"] < $b["total_no_fb_respond"]) ? 1 : -1);
                 });
                break;
        }

        return view('admin.ads.signal_ad', compact('dataToSort', 'select_ad', 'shorting', 'type', 'adList'));
    }

    public function markAdAsFeatured(Request $request) {

        if (!empty($request->ad_id)) {
            if($request->status == 1) {
                $ad = Ads::where("id", base64_decode($request->ad_id))->first();
                $scenario_id = $ad->scenario_id;
                if($this->isMaxFeatured($ad))
                {
                    $roomMatesScenarioIds = ["4","5"];
                    $logementScenarioIds = ["1", "2"];
                    if(in_array($scenario_id, $roomMatesScenarioIds)) {
                        $featured_rooms  = Ads::has('user')->with("user")->whereIn('scenario_id', $roomMatesScenarioIds)->where('is_featured', '1')->orderBy("id")->first();
                        DB::table("ads")->where('id', $featured_rooms->id)->update(
                            ["is_featured" => "0", "featured_date" => date("Y-m-d")]
                        );
                    }

                    if(in_array($scenario_id, $logementScenarioIds)) {
                        $featured_rooms  = Ads::has('user')->with("user")->whereIn('scenario_id', $logementScenarioIds)->where('is_featured', '1')->orderBy("id")->first();
                        DB::table("ads")->where('id', $featured_rooms->id)->update(
                            ["is_featured" => "0", "featured_date" => date("Y-m-d")]
                        );
                    }

                }
            }

            $queryStatus = Ads::where('id', base64_decode($request->ad_id))->update(['is_featured' => $request->status, "featured_date" => date("Y-m-d H:i:s")]);
            if (!empty($queryStatus)) {
                $adData = Ads::where('id', base64_decode($request->ad_id))->first();
                if (!empty($adData)) {
                    if (!empty($request->status)) {
                        $response['error'] = 'no';
                        $response['message'] = '"' . $adData->title . '" ad mark as featured successfuly.';
                    } else {
                        $status = '0';
                        $response['error'] = 'no';
                        $response['message'] = '"' . $adData->title . '" ad removed successfully from mark as featured.';
                    }
                } else {
                    $response['error'] = 'yes';
                    $response['message'] = 'Not able to save your info, please try again.';
                }
            } else {
                $response['error'] = 'yes';
                $response['message'] = 'Not able to save your info, please try again.';
            }
            return response()->json($response);
        }
    }

    private function isMaxFeatured($ad)
    {
        $roomMatesScenarioIds = ["4","5"];
        $logementScenarioIds = ["1", "2"];
        $scenario_id = $ad->scenario_id;

        if(in_array($scenario_id, $roomMatesScenarioIds)) {
            $nbMax = DB::table("config")->where("varname", "max_room_mate_featured")->first()->value;
            $featured_rooms  = Ads::has('user')->with("user")->whereIn('scenario_id', $roomMatesScenarioIds)->where('is_featured', '1')->orderBy("id")->get();

            $nb = count($featured_rooms);
            if($nb >= $nbMax)
            {
                return true;
            }

        }

        if(in_array($scenario_id, $logementScenarioIds)) {

            $nbMax = DB::table("config")->where("varname", "max_room_featured")->first()->value;
            $featured_rooms  = Ads::has('user')->with("user")->whereIn('scenario_id', $logementScenarioIds)->where('is_featured', '1')->orderBy('featured_date', 'ASC')->get();
            $nb = count($featured_rooms);
            $i=0;
             if($nb >= $nbMax)
            {
                return true;
            }

        }
    }

    public function activeDeactiveAd($adId = null, $status, Request $request) {
        if (!empty($status)) {
            $status = '1';
            $msg = 'Ad approved successfuly.';
            $msgType = 'status';
            $type = "active";
            DB::table("raison_deactive")->where("ad_id", base64_decode($adId))->delete();
        } else {
            $status = '0';
            $msg = 'Ad disapproved successfuly.';
            $msgType = 'status';
            $type = "desactive";
        }

        DB::table('approove_ads_details')->insert([
            "user_id" => getUserAdmin()->id,
            "ad_id" => base64_decode($adId),
            "type" => $status
        ]);

        if(isset($request->signal))
        {
            DB::table("signal_ad")->where('ad_id', base64_decode($adId))->update(['treaty' => "1"]);
        }

        if(isset($request->comment))
        {
            DB::table('signal_ad')->where("ad_id", base64_decode($adId))->update(
                ["deactive_comment" => $request->comment]
            );
        }

        $queryStatus = Ads::where('id', base64_decode($adId))->update(['admin_approve' => $status]);
        DB::table("ads")->where("id", base64_decode($adId))->update([
            "ad_treaty" => 1
        ]);
        $ad = Ads::has("user")->with(["ad_details", "user"])->where('id', base64_decode($adId))->first();

        if(!empty($ad->user->email)) {
            $this->sendMailActiveDeactiveAd($type, $ad);
        }


        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }
        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    private function sendMailActiveDeactiveAd($type, $ad)
    {
        $email = $ad->user->email;
        if($type == "active") {

            $subject = __('mail.ad_activation');

        } else {
            $subject = __('mail.ad_desactivation');

        }

        try {

            sendMail($email,'emails.users.activeDeactiveAd',[
                'subject'=>$subject,
                'type' =>$type,
                'adInfo'=>$ad,
                'lang' => getLangUser($ad->user->id)
            ]);


        } catch (Exception $ex) {

        }
        return true;
    }

    public function saveRaisonDeactive(Request $request)
    {
        DB::table('raison_deactive')->insert([
            'ad_id' => $request->ad_id,
            'raison' => $request->raison
        ]);
        echo "true";
    }

    public function checkAd($adId,$status,Request $request)
    {
        if(isAdmin() || is_Admin() || isComunity() || isSuperviseur()){
            if ($status == 1) {
                $status = '1';
                $msg = 'Ad Treated successfuly.';
                $msgType = 'status';
                $type = "active";
            } else {
                $status = '0';
                $msg = 'Ad UnTreated successfuly.';
                $msgType = 'status';
                $type = "desactive";
            }

            DB::table("ads")->where("id", $adId)->update([
                "ad_treaty" => $status
            ]);
            $request->session()->flash($msgType, $msg);
            return redirect()->back();
        }else{
            return redirect()->route('admin.error-acces');
        }

    }

    public function approoveAdsDetails(Request $request)
    {
        if (!empty($request->start) && !empty($request->end)) {
            $search_query['start'] = $request->start;
            $search_query['end'] =$request->end;
            $start_date = date("d/m/Y", strtotime($request->start));
            $end_date = date("d/m/Y", strtotime($request->end));
        } else {
            $search_query['start'] = date("Y-m-d");
            $search_query['end'] = date("Y-m-d");
            $start_date = date("Y-m-d");
            $end_date = date("Y-m-d");
        }



        $details = DB::table('approove_ads_details')
            ->join('users', "users.id", "approove_ads_details.user_id")
            ->join('ads', "ads.id", "approove_ads_details.ad_id")
            ->whereRaw("DATE(approove_ads_details.date) >= '" . $search_query['start'] . "'")
            ->whereRaw("DATE(approove_ads_details.date) <= '" . $search_query['end'] . "'");

        $data = $details->paginate(15);

        return view('admin.ads.approove_ads_details', compact("data", "search_query", 'start_date', 'end_date'));
    }

}
