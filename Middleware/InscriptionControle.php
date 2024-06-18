<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class InscriptionControle
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
        if(!empty($_SERVER['HTTP_USER_AGENT'])) {
            if (preg_match('/bot|crawl|curl|dataprovider|bingbot|googlebot|search|get|spider|find|java|majesticsEO|google|yahoo|teoma|contaxe|yandex|libwww-perl|facebookexternalhit/i', $_SERVER['HTTP_USER_AGENT'])) {
                return $next($request);
            }else{
                if(Auth::check()) {

                    if(Auth::user()->is_active == 0){
                        Auth::logout();
                        return redirect('/bloquedUser');
                    }

                    switch (Auth::user()->etape_inscription) {

                        //1 inscri2 - codephone
                        case 1:
                            //jpg
                            return redirect('/register_phone');
                            break;
                        //2 codephone- inscr3
                        case 2:
                            //png
                            return redirect('/colocation/ile-de-france/paris/inscritnormal3');
                            break;
                        case 3:
                            //webp
                            return $next($request);
                            break;

                        default:
                            return redirect('/');
                            break;
                    }

                }else{
                        if(guest_listing_page())
                            return $next($request);
                        return redirect('/connexion');


                }
        }
    }




    }
}
