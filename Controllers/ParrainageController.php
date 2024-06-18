<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class ParrainageController extends Controller
{
	public function __construct() {
        $this->middleware('auth');
    }
	public function index() {
		if(!isUserSubscribed()) {
			return view('parrainage.parrainage');
		} else {
			return redirect()->route("user.dashboard");
		}
		
	}
}
