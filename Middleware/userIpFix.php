<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\GeoLocationIP;

use Closure;

class userIpFix
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $ipaddress = '';
   		if(Auth::check()) {
   			$ip = get_ip();
        $user = DB::table("users")->where("id",Auth::id())->first();
        // IP si internet partagé
        if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        // IP derrière un proxy
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
        // Sinon : IP normale
        else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
        $ipaddress = 'UNKNOWN'; 
        if(is_null($user->ip_country) || $user->ip != $ip || empty($user->ip_country)) {
          $api = new GeoLocationIP();
          $data = $api->getIpInfo($ip);
          if(!$request->ajax()) {
            $referer = $_SERVER['HTTP_REFERER'] ?? null;
            $this->saveUserVisit($request->fullUrl(), $referer);
          }
          DB::table("users")->where("id", Auth::id())->update(["ip" => $ip, "ip_country" => ($data->country_name != "Not found") ? $data->country_name : NULL]);
          DB::table("user_visit")->where("ip", $ip)->update(["user_id" => Auth::id()]);
        }
   			
       }
        return $next($request);
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
    	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
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
	

}

