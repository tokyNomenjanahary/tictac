<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;
use stdClass;
use App\User;
use Illuminate\Support\Facades\DB;

class antiBotController extends Controller
{
	public function captcha()
	{
		 return view('auth.captcha');
	}
	
	public function verifyCaptcha(Request $request)
	{
		// Ma clé privée
		$secret = "6LdHlWUUAAAAAHaLiDTZPvF6BcUG_5gwD9OQ7M9Q";
		// Paramètre renvoyé par le recaptcha
		$response = $_POST['g-recaptcha-response'];
		// On récupère l'IP de l'utilisateur
		$remoteip = $_SERVER['REMOTE_ADDR'];
		
		$api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" 
			. $secret
			. "&response=" . $response
			. "&remoteip=" . $remoteip ;
		
		$google_check = json_decode(file_get_contents($api_url), true);
		if ($google_check['success'] == true) {
			$clientIP = request()->ip();
			DB::table("ip_request")->insert(
				["date" => date("Y-m-d H:i:s"), "ip" => $clientIP, "request_path" => $request->path()]
			);
			return redirect()->route('home');
		}
		else {
			$request->session()->flash('error', __("captcha.verif_failed"));
			return redirect()->back();
		}
	}
}