<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Auth;

class SetLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $default_lang='en';

        if (session()->has('locale')) {
            //l'utilisateur selectionne son langue du choix
            App::setlocale(session()->get('locale'));
        }
        else
        {
            $isValid=$this->lang_valide();
            
            if(!$isValid)
            {
                //lang n'existe pas encore donc
                //on utilise la langue par defaut
                $lang=$default_lang;
            }
            else
            {
                //la langue du navigateur existe dans la base 
                //donc on utilise cette langue
                $lang=getLangNav();
            }
            App::setlocale($lang);

        }

        //insert lang
        if(Auth::check())
            insertLangNavigateur();

        return $next($request);
    }

    /**
     *  verifier si le langue du navigateur est valide dans le site
     */

    private function lang_valide()
    {
        $langs=DB::table('langs')->get();

        $lang=$langs->where('code_iso',getLangNav())->first();

        if($lang==null)
            return false;
        
        return true;
    }
}
