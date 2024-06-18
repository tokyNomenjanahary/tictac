<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\GeoLocationIP;

use Closure;

class userIp
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Check that the user is logged in
        if (Auth::check()) {
            //Get session ip
            if (config('app.env') == 'Local' || config('app.env') == 'Serveur_Test') {
                $ip = '154.126.112.16';
            } else {
                $ip = get_ip();
            }
            //Get logged in user information
            $user = DB::table("users")
                ->where("id", Auth::id())
                ->select('ip', 'ip_country')
                ->first();
            //Tests if user information is correct
            if ($user) {
                //Test if the user's IP address information needs to be updated
                if ($user->ip_country == null || $user->ip != $ip || $user->ip_country == "Not found") {
                    //Take the full information of the IP address
                    $data = \Location::get($ip);
                    //API response check
                    if (!$data) {
                        //Returns not found if the IP address information is not found
                        $country_name = "Not found";
                    } else {
                        //Returns IP address information from the API
                        $country_name = $data->countryName;
                    }
                    //Update user information in database
                    DB::table("users")
                        ->where("id", Auth::id())
                        ->update(["ip" => $ip, "ip_country" => $country_name]);
                    DB::table("user_visit")
                        ->where("ip", $ip)
                        ->update(["user_id" => Auth::id()]);
                }
            }
        }
        return $next($request);
    }
}
