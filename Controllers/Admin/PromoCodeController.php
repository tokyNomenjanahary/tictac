<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Repositories\MasterRepository;
use Illuminate\Support\Facades\DB;


class PromoCodeController extends Controller {
    
    function __construct()
    {
        
    }
    public function index(Request $request) {
        $promotions = DB::table("code_promo as c")->select(DB::raw("c.id,c.libelle,c.code,c.value,c.commentaire,t.libelle_type, c.end_date_validity, t.unite"))->join("type_promo as t", "t.id", "c.type_promo")->get();
        return view('admin.code_promo.listing', compact('promotions')); 
    }

    public function newPromoCode(Request $request) {
         $types = DB::table("type_promo")->get();
        if(isset($request->promo_id)) {
             $promotion = DB::table("code_promo")->where("code_promo.id", $request->promo_id)->first();
             return view('admin.code_promo.new_code_promo', compact('types', "promotion")); 
        } else {
            return view('admin.code_promo.new_code_promo', compact('types')); 
        }
        
    }

    public function savePromoCode(Request $request) {
       $valid_date = date("Y-m-d", strtotime($request->valid_date));
       if(isset($request->promo_id) && !empty($request->promo_id)) {
            DB::table("code_promo")->where("id", $request->promo_id)->update(
                ["libelle" => $request->libelle, "code" => $request->code, "type_promo" => $request->type_promo, "value" => $request->value, "commentaire" => $request->comment, "end_date_validity" => $valid_date]
            );
       } else {
            DB::table("code_promo")->insert(
                ["libelle" => $request->libelle, "code" => $request->code, "type_promo" => $request->type_promo, "value" => $request->value, "commentaire" => $request->comment, "end_date_validity" => $valid_date]
            );
       }
       $request->session()->flash('status', 'Information saved');
       return redirect()->back();
    }
}
