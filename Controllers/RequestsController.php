<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Models\Ads\Ads;
use App\Http\Models\Ads\AdVisitingDetails;
use App\Http\Models\Ads\VisitRequests;

class RequestsController extends Controller
{

    private $perpage;

    public function __construct()
    {
        $this->middleware('auth');
        $this->perpage = config('app.perpage');
    }

    public function requests($type, $ad_id, Request $request)
    {
        $ad_id = str_replace("~", "-", $ad_id);
        //dd($ad_id);
        $splitUrl = explode("-", $ad_id);
        //dd($splitUrl);
        $ad_id = $splitUrl[count($splitUrl) - 1];
        $user_id = Auth::id();
        $types = array(
            "envoyes" => "sent",
            "recu" => "received",
            "accepte" => "accepted",
            "refuse" => "declined",
            "visite" => "visit",
        );

        if (isset($types[$type])) {
            $type = $types[$type];
        }
        if (!empty($ad_id)) {

            $ad = Ads::where('id', $ad_id)->where('user_id', $user_id)->where('status', '1')->first();

            if (!empty($ad)) {
                if ($type == 'visit' || $type == 'received') {
                    if ($ad->scenario_id == '1' || $ad->scenario_id == '2' || $ad->scenario_id == '5') {
                        $requests = VisitRequests::has('sender_ads.user')->has('receiver_ads.user')->with(['sender_ads.user.user_profiles', 'sender_ads.ad_uploaded_guarantees.guarantees', 'slots'])->where('ad_id', $ad_id)->where('accepted', '0')->orderBy('created_at', 'DESC')->paginate($this->perpage);
                        return view('requests/request-all', compact('ad', 'type', 'requests'));
                    } else if ($ad->scenario_id == '3' || $ad->scenario_id == '4') {
                        $requests = VisitRequests::has('sender_ads.user')->has('receiver_ads.user')->with(['sender_ads.user', 'sender_ads.ad_files' => function ($query) {
                            $query->where('media_type', '0')->orderBy('ordre', 'asc');
                        }, 'slots'])->where('ad_id', $ad_id)->where('accepted', '0')->orderBy('created_at', 'DESC')->paginate($this->perpage);

                        return view('requests/request-all-2', compact('ad', 'type', 'requests'));
                    }
                } else if ($type == 'sent') {
                    if ($ad->scenario_id == '1' || $ad->scenario_id == '2' || $ad->scenario_id == '5') {
                        $requests = VisitRequests::has('sender_ads.user')->has('receiver_ads.user')->with(['receiver_ads.user.user_profiles', 'slots'])->where('sender_ad_id', $ad_id)->orderBy('created_at', 'DESC')->paginate($this->perpage);

                        return view('requests/request-all', compact('ad', 'type', 'requests'));
                    } else if ($ad->scenario_id == '3' || $ad->scenario_id == '4') {
                        $requests = VisitRequests::has('sender_ads.user')->has('receiver_ads.user')->with(['receiver_ads.user', 'receiver_ads.ad_files' => function ($query) {
                            $query->where('media_type', '0')->orderBy('ordre', 'asc');
                        }, 'slots'])->where('sender_ad_id', $ad_id)->orderBy('created_at', 'DESC')->paginate($this->perpage);
                        return view('requests/request-all-2', compact('ad', 'type', 'requests'));
                    }
                } else if ($type == 'accepted') {
                    if ($ad->scenario_id == '1' || $ad->scenario_id == '2' || $ad->scenario_id == '5') {
                        $requests = VisitRequests::has('sender_ads.user')->has('receiver_ads.user')->with(['sender_ads.user.user_profiles', 'sender_ads.ad_uploaded_guarantees.guarantees', 'slots'])->where('ad_id', $ad_id)->where('accepted', '1')->orderBy('updated_at', 'DESC')->paginate($this->perpage);
                        return view('requests/request-all', compact('ad', 'type', 'requests'));
                    } else if ($ad->scenario_id == '3' || $ad->scenario_id == '4') {
                        $requests = VisitRequests::has('sender_ads.user')->has('receiver_ads.user')->with(['sender_ads.user', 'sender_ads.ad_files' => function ($query) {
                            $query->where('media_type', '0')->orderBy('ordre', 'asc');
                        }, 'slots'])->where('ad_id', $ad_id)->where('accepted', '1')->orderBy('updated_at', 'DESC')->paginate($this->perpage);
                        return view('requests/request-all-2', compact('ad', 'type', 'requests'));
                    }
                } else if ($type == 'declined') {
                    if ($ad->scenario_id == '1' || $ad->scenario_id == '2' || $ad->scenario_id == '5') {
                        $requests = VisitRequests::has('sender_ads.user')->has('receiver_ads.user')->with(['sender_ads.user.user_profiles', 'sender_ads.ad_uploaded_guarantees.guarantees', 'slots'])->where('ad_id', $ad_id)->where('accepted', '2')->orderBy('updated_at', 'DESC')->paginate($this->perpage);
                        return view('requests/request-all', compact('ad', 'type', 'requests'));
                    } else if ($ad->scenario_id == '3' || $ad->scenario_id == '4') {
                        $requests = VisitRequests::has('sender_ads.user')->has('receiver_ads.user')->with(['sender_ads.user', 'sender_ads.ad_files' => function ($query) {
                            $query->where('media_type', '0')->orderBy('ordre', 'asc');
                        }, 'slots'])->where('ad_id', $ad_id)->where('accepted', '2')->orderBy('updated_at', 'DESC')->paginate($this->perpage);
                        return view('requests/request-all-2', compact('ad', 'type', 'requests'));
                    }
                } else {
                    $request->session()->flash('error', __('backend_messages.no_ad_found'));
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

    public function acceptRequest($request_id, $ad_id, Request $request)
    {

        $request_id = base64_decode($request_id);
        $ad_id = base64_decode($ad_id);
        $user_id = Auth::id();

        if (!empty($request_id) && !empty($ad_id)) {

            $ad = Ads::where('id', $ad_id)->where('user_id', $user_id)->where('status', '1')->first();

            if (!empty($ad)) {
                $visit_request = VisitRequests::find($request_id);

                if (!empty($visit_request)) {
                    $visit_request->accepted = '1';
                    $visit_request->save();

                    return redirect('/requests/accepted/' . base64_encode($ad_id));
                } else {
                    $request->session()->flash('error', __('backend_messages.no_ad_found'));
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

    public function declineRequest($request_id, $ad_id, Request $request)
    {

        $request_id = base64_decode($request_id);
        $ad_id = base64_decode($ad_id);
        $user_id = Auth::id();

        if (!empty($request_id) && !empty($ad_id)) {

            $ad = Ads::where('id', $ad_id)->where('user_id', $user_id)->where('status', '1')->first();

            if (!empty($ad)) {
                $visit_request = VisitRequests::find($request_id);

                if (!empty($visit_request)) {
                    $visit_request->accepted = '2';
                    $visit_request->save();

                    return redirect('/requests/declined/' . base64_encode($ad_id));
                } else {
                    $request->session()->flash('error', __('backend_messages.no_ad_found'));
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

    public function cancelRequest($request_id, $ad_id, Request $request)
    {

        $request_id = base64_decode($request_id);
        $ad_id = base64_decode($ad_id);
        $user_id = Auth::id();

        if (!empty($request_id) && !empty($ad_id)) {

            $ad = Ads::where('id', $ad_id)->where('user_id', $user_id)->where('status', '1')->first();

            if (!empty($ad)) {
                $visit_request = VisitRequests::find($request_id);

                if (!empty($visit_request)) {
                    if ($visit_request->accepted != '1') {
                        $visit_request->accepted = '3';
                        $visit_request->save();
                        $request->session()->flash('status', __('backend_messages.request_canceled'));
                    } else {
                        $request->session()->flash('error', __('backend_messages.request_cant_canceled'));
                    }
                    return redirect('/requests/sent/' . base64_encode($ad_id));
                } else {
                    $request->session()->flash('error', __('backend_messages.no_ad_found'));
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
}
