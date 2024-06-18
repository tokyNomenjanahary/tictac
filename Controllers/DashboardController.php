<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Carbon\Carbon;
use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;
use App\Http\Models\Ads\AdDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{

    private $perpage;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'reactivateAdGet', 'reactivateAdUpdate',
        ]]);
        $this->perpage = config('app.perpage');
    }

    public function dashboardRegistration(Request $request)
    {
        $this->dashboard(null, $request);
    }

    private function removeIncompleteAds()
    {
        // methode private qui permet de suprimer des annonces incomplete
       $all=DB::table("ads")->where("user_id", Auth::id())->where('complete',0);
       if($all->count()==0)
        return;
       else
         $all->delete();


    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Request $request, $type = null)
    {
        if(isset($request->token))
            if(\explode('-',$request->token)[0]=='EC')
                \Session::flash('error','payement canceled');


        $search_query = $request->query('titre_recherche');
        // suprimer les annonces qui n'est pas complete
        $this->removeIncompleteAds();
        //----------------------------------------
        $registration = false;
        $user_id = Auth::id();
        $nb_ads = DB::table("ads")->where("user_id", $user_id)->count();

        if ($nb_ads == 0) {
            $registration = true;
        }

        $escrocPopup = isPopupEscroc();
        if (!$escrocPopup) {
            DB::table("users")->where("id", Auth::id())->update([
                "popupEscroc" => 1,
            ]);
        }

        if ($type == 'desactive') {
            if ($search_query) {
                $ads = Ads::has('user')->where('user_id', $user_id)->where('status', '0')->where('title', 'like', '%' . $search_query . '%')->orderBy('updated_at', 'desc')->paginate($this->perpage);
                $active_ads_count = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->where('title', 'like', '%' . $search_query . '%')->count();
            } else {
                $ads = Ads::has('user')->where('user_id', $user_id)->where('status', '0')->orderBy('updated_at', 'desc')->paginate($this->perpage);
                $active_ads_count = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->count();
            }
            $inactive_ads_count = $ads->total();
        } else if ($type == null || $type == 'tous') {
            if ($search_query) {
                $ads = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->where('title', 'like', '%' . $search_query . '%')->orderBy('updated_at', 'desc')->paginate($this->perpage);
                $inactive_ads_count = Ads::has('user')->where('user_id', $user_id)->where('status', '0')->where('title', 'like', '%' . $search_query . '%')->count();
            } else {
                $ads = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->orderBy('updated_at', 'desc')->paginate($this->perpage);
                $inactive_ads_count = Ads::has('user')->where('user_id', $user_id)->where('status', '0')->count();
            }
            $active_ads_count = $ads->total();
        } else {
            $ads = null;
            $active_ads_count = 0;
            $inactive_ads_count = 0;
        }

        $ad_details_array = array();

        if (!empty($ads)) {
            foreach ($ads as $key => $ad) {
                $ad_details = Ads::has('user')->with(['ad_details'])->where('id', $ad->id)->first();
                if (!is_null($ad_details->ad_details)) {
                    $property_types = AdDetails::find($ad_details->ad_details->id)->property_type;
                }

                $ad_file = Ads::has('user')->find($ad->id)->ad_files()->where('media_type', '0')->orderBy('ordre', 'asc')->first();

                if ($ad_file) {
                    $ad_file_name = $ad_file->filename;
                    $user_file_name = $ad_file->user_filename;
                } else {
                    $ad_file_name = null;
                    $user_file_name = null;
                }
                $ad_details_array[] = array(
                    'id' => $ad->id,
                    'admin_approve' => $ad->admin_approve,
                    'ad_url_slug' => $ad->url_slug . "~" . $ad->id,
                    'ad_file' => $ad_file_name,
                    'user_file_name' => $user_file_name,
                    'ad_title' => $ad->title,
                    'latitude' => $ad->latitude,
                    'longitude' => $ad->longitude,
                    'address' => $ad->address,
                    'min_rent' => $ad->min_rent,
                    'max_rent' => $ad->max_rent,
                    'scenario_id' => $ad->scenario_id,
                    'property_type_slug' => (isset($property_types)) ? str_slug($property_types->property_type, "-") : null,
                    'property_type' => (isset($property_types)) ? $property_types->property_type : null,
                    'last_updated' => date("d M Y", strtotime($ad->updated_at)),
                    'boosted' => (!empty($ad_details->boosted) ? $ad_details->boosted : ''),
                    "is_logo_urgent" => (isset($ad->is_logo_urgent)) ? $ad->is_logo_urgent : null,
                    "date_logo_urgent" => (isset($ad->date_logo_urgent)) ? $ad->date_logo_urgent : null,
                    "complete" => (boolval($ad->complete)) ? true : false,
                );
            }

        }
        return view('dashboard', compact('ads', 'ad_details_array', 'active_ads_count', 'inactive_ads_count', 'type', 'search_query', "registration", "escrocPopup"));
    }

    public function deactivateAd($id, Request $request)
    {

        $user_id = Auth::id();
        $splitUrl = explode("~", $id);
        $id = $splitUrl[count($splitUrl) - 1];
        $ad = Ads::has('user')->where('user_id', $user_id)->where('status', '1')->where('id', $id)->first();

        if (!empty($ad)) {
            DB::table('ads')->where('id', $ad->id)->update(['status' => '0', 'admin_approve' => '0']);

            $request->session()->flash('status', __('backend_messages.deactivated_ad', ['title' => $ad->title]));

            return redirect()->route('user.dashboard');
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }
    }

    public function activateAd($id, Request $request)
    {

        $user_id = Auth::id();
        $splitUrl = explode("~", $id);
        $id = $splitUrl[count($splitUrl) - 1];
        $ad = Ads::has('user')->where('user_id', $user_id)->where('status', '0')->where('id', $id)->first();

        if (!empty($ad)) {

            DB::table('ads')->where('id', $ad->id)->update(['status' => '1', 'admin_approve' => '1']);

            $request->session()->flash('status', __('backend_messages.message_archived', ['title' => $ad->title]));

            return redirect()->route('user.dashboard');
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }
    }

    public function reactivateAdGet($id, $email, Request $request)
    {
        $ad = Ads::has('user')->where('id', $id)->first();

        return view('addetails.reactivation', compact('ad'));
    }

    public function reactivateAdUpdate($id, $email, Request $request)
    {
        $ad = Ads::has('user')->where('id', $id)->first();

        if (!empty($ad)) {
            $ad->updated_at = Carbon::now();
            $ad->save();
            $response['statut'] = true;
        } else {
            $response['statut'] = false;
        }

        return response()->json($response);
    }

    public function deleteAd($id, Request $request)
    {

        $user_id = Auth::id();
        $splitUrl = explode("~", $id);
        $id = $splitUrl[count($splitUrl) - 1];
        $ad = Ads::has('user')->where('user_id', $user_id)->where('status', '0')->where('id', $id)->first();

        if (!empty($ad)) {
            $ad->delete();
            $request->session()->flash('status', __('backend_messages.ad_deleted', ['title' => $ad->title]));
            countAds("delete");
            return redirect()->route('user.dashboard', ['type' => 'desactive']);
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }
    }

    public function boostAd(Request $request, $adId = null)
    {
        if (isset($_SERVER['HTTP_REFERER'])) {
            $url_return = $_SERVER['HTTP_REFERER'];
        } else {
            $url_return = route("user.dashboard");
        }

        if (isset($request->new)) {
            $url_return = route('searchad', $adId);
        }

        $user_id = Auth::id();
        $user = Auth::user();
        $isAdmin = ($user->user_type_id != 1);
        $splitUrl = explode("~", $adId);
        $id = $splitUrl[count($splitUrl) - 1];
        if (isset($isAdmin)) {
            $ad = Ads::has('user')->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc')->first();
            }, 'ad_details.property_type', 'boosted_ads'])
                ->where('status', '1')
                ->where('id', $id)
                ->first();
        } else {
            $ad = Ads::has('user')->with(['ad_files' => function ($query) {
                $query->where('media_type', '0')->orderBy('ordre', 'asc')->first();
            }, 'ad_details.property_type', 'boosted_ads'])
                ->where('status', '1')
                ->where('id', $id)
                ->where('user_id', $user_id)
                ->first();
        }

        if (empty($ad)) {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));
            return redirect()->route('user.dashboard');
        }

        $upsels = DB::table("upselling")->get();
        foreach ($upsels as $key => $upsel) {
            $upsels[$key]->tarifs = DB::table("upselling_tarif")->where("upselling_id", $upsel->id)->get();
        }

        $lang_description = \App::getLocale() . "_description";
        $lang_title = \App::getLocale() . "_title";
        $currency_symbol = get_current_symbol();

        return view('addetails/boost_ad', compact('ad', "upsels", "lang_description", "lang_title", "url_return", "isAdmin","currency_symbol"));
    }

    public function mesAlertes()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $savedAlerts = DB::table('users_alertes')
                ->where('user_id', Auth::user()->id)
                ->get();

            return view('alert/page', compact('savedAlerts'));
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function deleteAlert($id, Request $request)
    {
        DB::table('users_alertes')->where("id", $id)->delete();
        return redirect()->back();
    }

    public function desactiverAlert($id, Request $request)
    {
        DB::table('users_alertes')->where("id", $id)->update(["is_email" => 0]);
        return redirect()->back();
    }

    public function activerAlert($id, Request $request)
    {
        DB::table('users_alertes')->where("id", $id)->update(["is_email" => 1]);
        return redirect()->back();
    }

    public function viewAlertes($id, Request $request)
    {
        $alert = DB::table('users_alertes')->where("id", $id)->first();
        if (!is_null($alert)) {
            $filters = unserialize($alert->filtres);
            $request->session()->put("userAlert", $filters);
            $url = searchUrl($filters['latitude'], $filters['longitude'], $filters['address'], $filters['scenario_id']) . "?idAlert=" . $id;
            return redirect($url);
        }

    }

    public function removeAlerts(Request $request)
    {
        if (Auth::check()) {
            $ids = $request->ids;
            foreach ($ids as $id) {
                DB::table('users_alertes')->where('id', $id)->delete();
            }
            $request->session()->flash('status', __('alert.success_delete'));
            echo json_encode(array("response" => "done"));
        }
    }

    public function getAllCoupDeFoudreNotif(Request $request)
    {
        if ($request->ajax()) {
            if (Auth::check()) {
                $nbPerPage = 5;
                if (isset($request->page) && $request->page != 0) {
                    $page = $request->page;
                } else {
                    $page = 1;
                }

                $offset = ($page * $nbPerPage) - $nbPerPage;
                $userId = Auth::id();
                $response = array();
                $read_messages = DB::table("coup_de_foudres")->where('receiver_id', $userId)
                    ->orderBy('created_at', 'DESC')
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
                    ->orWhereNull('read_date')
                    ->update(["notif_checked" => "1", "read_date" => date('Y-m-d H:i:s')]);
                $html = view('coup_de_foudre/toctoc-all-notif', compact('read_messages', "count_message", "page", "offset"))->render();
                return response()->json(array("html" => $html, "nb" => count($read_messages)));
            }
        }
    }

}
