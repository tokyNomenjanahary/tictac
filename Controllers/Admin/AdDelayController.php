<?php

namespace App\Http\Controllers\Admin;

use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdDelayController extends Controller
{
    private $perpage;

    public function __construct() {
        $this->perpage = 5;
    }

    public function list(Request $request)
    {
	
        $now = Carbon::now();
        $daysToAdd_expire = getConfig('day_delay') + 1;
        $dateExp = $now->addDays(-$daysToAdd_expire);

        $adList = Ads::with('user')->with('ad_details')
            
            ->where('status', '=', '1')
            ->whereDay('updated_at', $dateExp->day)
            ->whereMonth('updated_at', $dateExp->month)
            ->whereYear('updated_at', $dateExp->year)
            ->paginate($this->perpage);

        $count_expire = Ads::with('user')->with('ad_details')
            
            ->where('status', '=', '1')
            ->whereDay('updated_at', $dateExp->day)
            ->whereMonth('updated_at', $dateExp->month)
            ->whereYear('updated_at', $dateExp->year)
            ->count();


        $now = Carbon::now();
        $daysToAdd_desactive = getConfig('day_delay');
        $dateDesactive = $now->addDays(-$daysToAdd_desactive);

        $count_desactve = Ads::with('user')->with('ad_details')
            
            ->where('status', '=', '1')
            ->whereDay('updated_at', $dateDesactive->day)
            ->whereMonth('updated_at', $dateDesactive->month)
            ->whereYear('updated_at', $dateDesactive->year)
            ->count();

        return view('admin.ads.delay.listing', compact('adList', 'count_expire', 'count_desactve'));
    }

public function mailAll(Request $request)
    {

        $retour = $request->expire == 1 ? calculDateExpirationAd(true) : calculDateExpirationAd(false);

        if ($retour)
        {
            $response['statut'] = true;
        }else{
            $response['statut'] = false;
        }

        # Mettre à jour
        desactivationAutomatiqueAd();

        return response()->json($response);
    }
}
