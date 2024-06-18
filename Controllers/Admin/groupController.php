<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class groupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//facebook pixel_id
		$codeGroup = DB::table('config')->where('varname', 'code_groupe')->first()->value;
        return view('admin.group.code_group', compact('codeGroup'));
       
    }
	
	
	public function editCodeGroup(Request $request) {
            if ($request->isMethod('post')) {
   
                $validator = Validator::make($request->all(),
                            [
                            'code_group' => 'required'
                            ]
                );
                if ($validator->fails()) {
					
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                } else {

                    DB::table('config')
						->where('varname', 'code_groupe')
						->update(['value' => $request->code_group]);

                    $request->session()->flash('status', "true.");
					return redirect()->back();
                }
            }
    }

}
