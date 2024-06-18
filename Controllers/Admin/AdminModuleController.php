<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;



class AdminModuleController extends Controller {
    
    private $perpage;
    private $prefix;
    

    function __construct()
    {
        $this->prefix = getConfig('admin_prefix');
        $this->perpage = config('app.perpage');
    }

    private function add_view($page)

    {
    
            //créér dynamiquement un fichier dans le View
    
            $nom_file = $page.".blade.php";
    
            $p=resource_path("views/admin/modules/page/");
    
            $nom_file=$p.$nom_file;
    
            //on va ajouter une contenue de base 
    
            $content="@extends('layouts.adminappinner')\n
"."@push('styles')\n
"."<!--    <link href=\"{{ asset('css/admin/datatables.net-bs/dataTables.bootstrap.min.css') }}\" rel=\"stylesheet\">-->\n
"."@endpush\n
"."@section('content')\n
"."<div class=\"content-wrapper\">
    
    <section class=\"content-header\">
    
                
    
    </section>
    
    <section class=\"content\">
    
    </section>
    
        </div>"."\n
@endsection";
    
            if(!file_exists($nom_file))
    
            {
                if(!is_dir($p))
                {
                    mkdir($p);
                }
    
            file_put_contents($nom_file, $content);
    
            }
    
 }
    
        private function delete_view($page)
    
        {
    
            //supprimer le fichier view en utilisant le dossier trash
    
            $nom_file = $page.".blade.php";
    
            $nom_file_ = $page.".blade.php";
    
            $p=resource_path("views/admin/modules/page/");
    
            $nom_file=$p.$nom_file;
    
            $ret=true;
    
            //si le fichier existe, le déplace dans le dossier trash pour ne pas le perdre directement
    
            if(file_exists($nom_file))
    
            {
                if(!is_dir($p."trash"))
                {
                    mkdir($p."trash");
                }
    
                $ret=rename($nom_file,$p."trash/".$nom_file_);
    
            }
    
            return $ret;
    
     
    
        }

    public function addModule($id = null, Request $Request)
    {
        if(!is_null($id)) {
            $module = DB::table('admin_module')->where("id", $id)->first();

            return view('admin.modules.add', compact("module"));
        }
        return view('admin.modules.add'); 
    }

    public function saveModule(Request $request)
    {

        if(empty($request->nom))

        {

            $request->session()->flash("error", "le champ nom est obligatoire");

            return redirect()->back();

        }
        $nom = $request->nom;

        $route = null;
        $ordre=isset($request->ordre)?$request->ordre : (DB::table('admin_module')->get()->max('ordre'))+1;
        if(!is_null($request->url) && !empty($request->url)) {
            $url = $this->prefix . $request->url;
            $route = app('router')->getRoutes()->match(app('request')->create($url))->getName();
        }
        
        if(isset($request->id)) {
            
            $check = DB::table("admin_module")->where("id", "!=",$request->id)->where(DB::raw('upper(nom)'), strtoupper($nom))->where(DB::raw('upper(route)'), strtoupper($route))->first();
        } else {
            $check = DB::table("admin_module")->where(DB::raw('upper(nom)'), strtoupper($nom))->orWhere(DB::raw('upper(route)'), strtoupper($route))->first();
        }
        
        if(!is_null($check)) {
            $request->session()->flash("error", "Ce module existe déjà");
        } else {
            if(isset($request->id)) {
                DB::table('admin_module')->where("id", $request->id)->update([
                    "nom" => $request->nom,
                    "route" => $route,
                    "url" => $request->url,
                    "ordre" => $ordre,
                    "icone" => $request->icone
                ]);
                $id=$request->id;
            } else {
               DB::table('admin_module')->insert([
                    "nom" => $request->nom,
                    "route" => $route,
                    "url" => $request->url,
                    "ordre" => $ordre,
                    "icone" => $request->icone
                ]); 
                $id=DB::table("admin_module")->where(DB::raw('upper(nom)'), strtoupper($nom))->orWhere(DB::raw('upper(route)'), strtoupper($route))->value('id');
            }


                               
            DB::table('module_user')->insert([

                "id_type_user"=>$request->session()->get("ADMIN_USER")->user_type_id,

                "id_module"=>$id

            ]);
            $request->session()->flash("status", "Enregistré avec succès");
        }
        return redirect()->back();
    }

    public function addModulePage($id = null, Request $request)
    {
        $modules = DB::table('admin_module')->orderBy("ordre")->get();

        if(!is_null($id)) {
            $page = DB::table('module_pages')->where('id', $id)->first();

            return view('admin.modules.add_pages', compact("modules", "page"));
            return view('admin.modules.add_pages', compact("modules", "page")); 
            return view('admin.modules.add_pages', compact("modules", "page"));
            return view('admin.modules.add_pages', compact("modules", "page")); 
            return view('admin.modules.add_pages', compact("modules", "page"));
        }
            return view('admin.modules.add_pages', compact("modules")); 
    }

    public function saveModulePage(Request $request)
    {
        if(empty($request->nom))

                {
        
                    $request->session()->flash("error", "le champ nom est obligatoire");
        
                    return redirect()->back();
        
                }
        $nom = $request->nom;
       
         //l'url par defaut est celui du nom mais espace remplacé par _ 

        $verif_url=$this->prefix."/".str_replace(" ","_",$nom);

        //demande de nom de route de l'url 

        $route=app('router')->getRoutes()->match(app('request')->create($verif_url))->getName();

        //vérifié si route existe 

        if($route)

        {

            $url="/".str_replace(" ","_",$nom);

        }

        else{

            $url="/page/".str_replace(" ","_",$nom);

        }
        if(isset($request->id)) {
            $check = DB::table("module_pages")->where("id", "!=", $request->id)->where(DB::raw('upper(nom)'), strtoupper($nom))->where(DB::raw('upper(route)'), strtoupper($route))->first();
        } else {
            $check = DB::table("module_pages")->where(DB::raw('upper(nom)'), strtoupper($nom))->orWhere(DB::raw('upper(route)'), strtoupper($route))->first();
        }
        
        if(!is_null($check)) {
            $request->session()->flash("error", "Cette page existe déjà");
        } else {
            if(isset($request->id)) {
                DB::table('module_pages')->where("id", $request->id)->update([
                    "nom" => $request->nom,
                    "route" => $route,
                    "url" => $request->url,
                    "module_id" => $request->module_id
                ]);
            } else {
                DB::table('module_pages')->insert([
                    "nom" => $request->nom,
                    "route" => $route,
                    "url" => $url,
                    "module_id" => $request->module_id
                ]);
            }
            $this->add_view(str_replace(" ","_",$nom));
            $request->session()->flash("status", "Enregistré avec succès");
        }
        return redirect()->back();
    }

    public function listModule(Request $request)
    {
        $modules = DB::table('admin_module')->orderBy("ordre")->get();
        return view('admin.modules.list_modules', compact("modules")); 
    }

    public function listPageModule(Request $request)
    {
        $pages = DB::table('module_pages')->select("module_pages.*", "admin_module.nom as module")->join("admin_module", "admin_module.id", "module_pages.module_id")->orderBy("admin_module.ordre")->orderBy("module_pages.id")->get();
        return view('admin.modules.list_page_module', compact("pages")); 
    }

    public function deleteModule($id, Request $request)
    {
        $T=DB::table("module_pages")->where('module_id', $id)->get();

                foreach ($T as $value) {
        
                    $this->delete_view(str_replace(" ","_",$value->nom));
        
                }
        DB::table("admin_module")->where('id', $id)->delete();
        $request->session()->flash('status', "Module supprimé avec succès");
        return redirect()->back();
    }

    public function deletePageModule($id, Request $request)
    {
        $nom=DB::table("module_pages")->where('id', $id)->get();

                $nom=$nom[0]->nom;
        
                $this->delete_view(str_replace(" ","_",$nom));
        DB::table("module_pages")->where('id', $id)->delete();
        $request->session()->flash('status', "Page supprimé avec succès");
        return redirect()->back();
    }

    public function addRoles($id = null, Request $request)
    {
        $modules = DB::table('admin_module')->orderBy("ordre")->get();

        if(!is_null($id)) {
            $role = DB::table('type_user')->where('id', $id)->first();
            $role->modules = DB::table('module_user')->where("id_type_user", $id)->pluck('id_module')->toArray();
            return view('admin.modules.add_role', compact("modules", "role"));
        }
        return view('admin.modules.add_role', compact("modules")); 
    }

    public function ListRoles(Request $request)
    {
        $roles = DB::table('type_user')->get();
        
        return view('admin.modules.list_role', compact("roles")); 
    }

    public function deleteRoles($id, Request $request)
    {
        DB::table("type_user")->where('id', $id)->delete();
        $request->session()->flash('status', "Rôle supprimé avec succès");
        return redirect()->back();
    }

    public function saveRoles(Request $request)
    {
        if(!isset($request->id)) {
            DB::table("type_user")->insert(
                [
                    "designation" => $request->designation
                ]
            );
             $id_type_user = DB::getPdo()->lastInsertId();
        } else {
            DB::table("type_user")->where("id", $request->id)->update(
                [
                    "designation" => $request->designation
                ]
            ); 
             $id_type_user = $request->id;
            DB::table("module_user")->where("id_type_user", $id_type_user)->delete(); 
        }

        $modules = $request->modules;
        foreach ($modules as $key => $module) {
            DB::table("module_user")->insert([
                "id_module" => $module,
                "id_type_user" => $id_type_user
            ]);
        }

        $request->session()->flash("status", "Enregistré avec succès");
        return redirect()->back();

    }
    


}
