<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Http\Models\Ads\Ads;
use App\Http\Models\Ads\Favourites;
use App\Http\Models\Ads\AdVisitingDetails;
use App\Http\Models\Ads\VisitRequests; 

class FavoritesController extends Controller {
    
    private $perpage;

    public function __construct() {
        $this->middleware('auth');
        $this->perpage = config('app.perpage');
    }

    public function favoritesList() {
        $user_id = Auth::id();
        $favDetails = Favourites::has('ads.user')->whereHas('ads', function ($query) { $query->where('status', '1')->where('admin_approve', '1'); })->with('user.user_profiles','ads.ad_details','ads.user')->with(['ads.ad_files' => function ($query) { $query->where('media_type', '0')->orderBy('ordre', 'asc'); }])->where('user_id', $user_id)->orderBy('id', 'desc')->paginate($this->perpage);
		return view('favorites/favorites_list',  compact('favDetails'));
    }

}
