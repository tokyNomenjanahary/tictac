<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class antiBotController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$bots = DB::table("ip_request")->where("bot", "1")->where("request_path", "<>", "validate_recaptcha")->get();
		
        return view('admin.bot.listing', compact('bots'));
       
    }

}
