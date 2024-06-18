<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MailErrorAdmin;
use Illuminate\Support\Facades\DB;
use Mail;


class GeopifyController extends Controller
{

    private function sendErrorMailAdmin($page,$url='')
    {
        $getEmail = MailErrorAdmin::all();

        $details = [
            'body' => $page,
            'url'  =>$url
        ];
        foreach ($getEmail as $l) {
            sendMail($l->email,"emails.MailErrorGeoapify",["details"=>$details,"subject"=>"Error geoapify"]);
        }
    }

    private function selectKeyToUse($gestion_geo_id,$index)
    {
        /**index= index du clé utiliser selon la gestion du geoapify
         *  cette fonction est juste utilisé pour selectionner le clé utilisé en cours
         */
        $allKeyPage=DB::table('geoapify_key')->where('gestion_geocode_id',$gestion_geo_id)->get();
        //si cle clé est vide, informer l'admin par email que le geoapify ne fonction pas
        if(!$allKeyPage->count())
        {
            return null;
        }
        $keyNext = $allKeyPage->takeUntil(function ($item,$k) use ($index){
            return  $k==$index;
        });

        $result=$keyNext->last();
        //on update le dernier date d'utilisation de cette clé (last_use)
        if($result->last_use!=date("m/d/Y"))
            DB::table('geoapify_key')->where('id',$result->id)->update(['last_use'=>date("m/d/Y")]);

        return $result;
    }




    public function update_gestion_geoapify(Request $request,$page)
    {
        /**
         *  ON PASSE A CETTE METHODE A CHAQUE APPEL AU GEOAPIFY
         *
         *   Ici on incremente le 'api_count' du table "gestion_geoapify"
         *   et d'incrementer le 'key_index' si le api_count est maximal
         *   pour changer le cle automatiquement
         *
         */

         $url=$request->url;


        // on selection d'abord la table gestion_geoapify de la page en parametre
        $geoapify=DB::table('gestion_geoapify')
                    ->where('page',$page);
        //on va utiliser cette resultat pour selectioner les donnés
        $geoapify_first=$geoapify->first();

        if($geoapify->exists())
        {
            //on incremente le nombre d'appel
            $geoapify->update(['api_count'=>$geoapify_first->api_count+1]);

            //prenons le nombre d'appel de l'api geoapify
            $count=$geoapify_first->api_count;
            $max_count=$geoapify_first->max_count;
            //on va verifier si le nombre d'appel est maximum
            //maintenant on va selectionner les nombres de clés lièr à cette gestion_geoapify
            $count_key=DB::table('geoapify_key')
            ->where('gestion_geocode_id',$geoapify_first->id)->count();
            if($geoapify_first->key_index > $count_key)
            {
                //on repart au debut dans ce cas
                $geoapify->update(['key_index'=>1,'api_count'=>0]);
            }

            if($count>=$max_count)
            {

                if ($count_key!=1) {
                    if ($geoapify_first->key_index == $count_key) {
                        //ici, le key_index est déja maximal, on ne peut plus incrementer
                        //donc on repasse à 1

                        $useKey=$this->selectKeyToUse($geoapify_first->id,1);
                        if ($useKey->last_use==date("m/d/Y")) {
                            if($count==$max_count+1|| $count==$max_count+200)
                                $this->sendErrorMailAdmin($page.' (Nombre de clé insuffisant veuillez ajouter une nouveau clé)',$url);
                        }
                        else
                        {
                            $geoapify->update(['key_index'=>1,'api_count'=>0]);
                        }

                    } else {
                        //incremente key_index pour changer le clé
                        $next_index_key=$geoapify_first->key_index+1;
                        $useKey=$this->selectKeyToUse($geoapify_first->id,$next_index_key);
                        if ($useKey->last_use==date("m/d/Y")) {
                            if ($count==$max_count+1|| $count==$max_count+200)
                                $this->sendErrorMailAdmin($page.' (Nombre de clé insuffisant veuillez ajouter une nouveau clé)',$url);
                        }
                        else
                        {
                            $geoapify->update(['key_index'=>$next_index_key,'api_count'=>0]);
                        }
                    }
                }
                else
                {
                    if ($count==$max_count+1|| $count==$max_count+200) {

                        $geoapify->update(['key_index'=>1]);
                        $this->sendErrorMailAdmin($page.' (Nombre de clé insuffisant veuillez ajouter une nouveau clé)',$url);
                    }

                    else {
                        $useKey=$this->selectKeyToUse($geoapify_first->id,1);
                        if($useKey->last_use!=date("m/d/Y"))
                        {
                            $geoapify->update(['api_count'=>0]);
                        }
                    }
                }

            }
        }

    }

    public function geoapify_key($page)
    {
        //prenons la geoapify du page
        $geoapify=DB::table('gestion_geoapify')->where('page',$page)->first();
        if(!$geoapify)
        {
//            $this->sendErrorMailAdmin($page." ( n'existe pas dans la table gestion)");
            return '562f09ead9ab4a9aa12b7d38210428dd';
        }

        //prenons les listes des clés lièr à cette page
        $key=DB::table('geoapify_key')->where('gestion_geocode_id',$geoapify->id)->get();

        //si clé est vide, informer l'admin par email que le geoapify ne fonction pas
        if(!$key->count())
        {
            return '562f09ead9ab4a9aa12b7d38210428dd';
        }

        /**
         *  prenons maintenant le clé qu'on utilise actuelement selon la table gestion_geoapify
         *  ( colone `key_index` )
         *
         *  `key_index` est l'index de clés que la page utilise actuel
         *   cette indexe change automatiquement si le nombre de demande de l'api geoapify dépasse le max
         *
         */
        $key=$this->selectKeyToUse($geoapify->id,$geoapify->key_index);
        //si on echoue la selection
        if($key==null)
            return '562f09ead9ab4a9aa12b7d38210428dd';
        else
        {
            return $key->cle;
        }

    }

}
