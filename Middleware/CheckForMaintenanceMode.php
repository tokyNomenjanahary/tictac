<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Auth;
use App\User;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;



use Closure;

class CheckForMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

	public function str_compare($str_1,$str_2){

		if($str_1 == $str_2) return TRUE;
		else return FALSE;
	   }

    public function handle($request, Closure $next)
    {

	
		$user = Auth::user();
		
		//echo URL::current();

		//Auth::user()->user_type_id;

    	date_default_timezone_set(config('app.timezone'));
    	\Config::set("app.debug", getConfig('debug'));
    	if(!$request->ajax()) {
    		$referer = $_SERVER['HTTP_REFERER'] ?? null;
    		$this->saveUserVisit($request->fullUrl(), $referer);
    	}
    	$tabAssets = ["pdf", "css", "img","images","js", "maintenance", "change_lang"];
		$stringAdmin = "admin2021";
    	$url_parameter = $request->segment(1);
		$ip = get_ip();
    	$ipExclude=["127.0.0.1", "172.69.226.101", "176.189.241.24", "154.126.9.190","154.126.10².139", "154.126.9.44", "154.126.9.44"];
		$resultat = $this->str_compare($url_parameter, $stringAdmin);
		

		if(isMaintenance() && !array_search($request->segment(1), $tabAssets) && !$resultat) {
    		return redirect()->route('maintenance');
    	}

    	if(isBlockedIp($ip)) {
    		//var_dump("blocked");
    		//exit;
			return response(view('bloqued'));
    	}
		
		/*
		$this->cleanIpRequestTable();
		
		if($url_parameter != "recaptcha" && $url_parameter != "validate_recaptcha") {
			if($this->isBot()) {
				return redirect()->route("recaptcha");
			}
			DB::table("ip_request")->insert(
				["date" => date("Y-m-d H:i:s"), "ip" => $ip, "request_path" => $request->path()]
			);
			$userRequests  = $this->getUserRequests(10);
			
			if(count($userRequests) >= 1000) {
				DB::table("ip_request")->where("id", $userRequests[0]->id)->update(
							["bot" => "1"]
						);
			}
			
			$userRequests  = $this->getUserRequests(20);
			if(count($userRequests) >= 2000) {
				DB::table("ip_request")->where("id", $userRequests[0]->id)->update(
							["bot" => "1"]
						);
			}
			
			$userRequests  = $this->getUserRequests(60);
			if(count($userRequests) >= 5000) {
				DB::table("ip_request")->where("id", $userRequests[0]->id)->update(
							["bot" => "1"]
						);
			}
			
			$userRequests  = $this->getUserRequests(120);
			if(count($userRequests) >= 89000) {
				DB::table("ip_request")->where("ip", $ip)->update(
							["bot" => "1", "id" => $userRequests[0]->id]
						);
			}
			
			$userRequests  = $this->getUserRequests(21600);
			if(count($userRequests) >= 65000) {
				DB::table("ip_request")->where("id", $userRequests[0]->id)->update(
							["bot" => "1"]
						);
			}
		}*/
    	
		
        return $next($request);
	}
	
	public function getUserIpAddr(){
		$ipaddress = '';
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		else if(isset($_SERVER['HTTP_FORWARDED']))
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		else if(isset($_SERVER['REMOTE_ADDR']))
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		else
			$ipaddress = 'UNKNOWN';    
		return $ipaddress;
	 }

	 function get_ip() {
		$keys = array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR');
	
		foreach ($keys as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
						return $ip;
					}
				}
			}
		}
	}


    public function saveUserVisit($url, $referer)
    {
    	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    	$hote = $protocol . \Request::getHttpHost();
    	$ip = $this->getUserIpAddr();
    	$array = ["bootstrap-fileinput", "css", "font", "fontawesome","fonts","framework","icons", "images", "img", "js", "logs", "pdf", "scss", "sitemap", "slick", "uploads", "admin"];
    	foreach ($array as $key => $value) {
    		$pos = strpos($url, $value);
    		if($pos !== false) {
    			return;
    		}
    	}
    	\Session::put('urlError', $url);
    	$url = str_replace($hote, "", $url);
    	if($url == "") $url = "/";
    	$dateDelete = getDateByDecalage(-3) . " 00:00:00";
    	DB::table('user_visit')->where("date", "<", $dateDelete)->delete();
    	$data = [
    		"ip" => $ip, "url" => $url,
    		"date" => date('Y-m-d H:i:s')
    	];
    	if(!is_null($referer) && $referer != "") {
    		$data['referer'] = $referer;
    	} 
    	DB::table("user_visit")->insert($data);


	}
	


    
	/**
	*Obtenir les requetes effectuer par l'utilisateur dans une intervalle de temps
	*
	*$t intervalle de temps t en seconde (donc requete effectuer par l'user pendant les $t derniere seconde)
	**/
	
	private function getUserRequests($t)
	{
		$ip = request()->ip();
		$ip_requests = DB::table("ip_request")->where("ip", $ip)->whereRaw("TIME_TO_SEC(TIMEDIFF('".date("Y-m-d H:i:s")."',date)) <= ".$t)->orderBy('date', 'desc')->get();
		return $ip_requests;
	}
	
	private function isBot()
	{
		
		$ip = request()->ip();
		$ip_requests = DB::table("ip_request")->where("ip", $ip)->where("bot", "1")->where("request_path", "<>", "validate_recaptcha")->whereRaw("date = (SELECT MAX(date) FROM ip_request WHERE ip='". $ip ."')")->first();
		return !is_null($ip_requests);
	}
	
	private function cleanIpRequestTable()
	{
		DB::table("ip_request")->where("bot","<>", "1")->whereRaw("TIME_TO_SEC(TIMEDIFF('".date("Y-m-d H:i:s")."',date)) >= 22000")->delete();
	}
}
