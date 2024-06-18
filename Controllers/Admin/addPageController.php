<?php



namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\View;



class addPageController extends Controller

{

    public function index($page)

    {



      //verifier d'abord si view existe

      $nom_file = $page.".blade.php";

      $p=resource_path("views/admin/modules/page/");

      $nom_file=$p.$nom_file;

      if(file_exists($nom_file))

      {

        return view("admin/modules/page/".$page);

      }

      else{

        return abort(404);  //retourner sur la page 404

      }

    }

}
