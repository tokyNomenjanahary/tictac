<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Package;
use Illuminate\Support\Facades\DB;

class ElementsController extends Controller {
    
    private $perpage;
    
    public function __construct() {
        $this->perpage = config('app.perpage');
    }

    function listElements(Request $request)
    {
        $elements = DB::table("deactivate_element")->get();
        return view('admin.elements.element_list', compact("elements"));
    }

    function manageElements(Request $request)
    {
            $selectors = $request->selector;
            $comments = $request->comments;
            $hides = $request->hide;
            if(count($selectors) > 0) {
                DB::table("deactivate_element")->delete();
                if(!empty($selectors[0])) {
                    foreach ($selectors as $key => $selector) {
                        if(!empty($selector)) {
                           DB::table("deactivate_element")->insert([
                                "selector" => $selector,
                                "comment" => $comments[$key],
                                "hide" => intval($hides[$key])
                            ]); 
                        }
                        
                    }
                }
                
            }
            $request->session()->flash('status', "Saved successfuly.");
            return redirect()->back();
    }
}
