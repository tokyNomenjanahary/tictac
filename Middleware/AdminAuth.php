<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
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
        $urlAdmin = $request->getUri();
        $method = $request->method();
        $urlAdmin = explode("/",$urlAdmin);
        $countUrl = count(explode("?",end($urlAdmin)));
        if($countUrl > 1){
            $geturl = explode("?",end($urlAdmin));
            $lastInUrl = $geturl[0];
        }else{
            $lastInUrl = $urlAdmin[4];//end($urlAdmin);
        }

        if ($request->session()->has('ADMIN_USER')) {
            isAccesLink($lastInUrl);
            if(isAccesLink($lastInUrl) == true || $method == "POST" || isAdmin()){
                return $next($request);
            }else{
                return redirect()->route('admin.error-acces');
            }

        } else {
            return redirect()->route('admin.login');
        }
    }
}
