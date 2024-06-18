<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FbController extends Controller
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
		$pixelId = DB::table('config')->where('varname', 'pixel_id')->first()->value;
        return view('admin.fb_pixel.fb_pixel', compact('pixelId'));
       
    }
	
	
	public function editPixelId(Request $request) {
            if ($request->isMethod('post')) {
   
                $validator = Validator::make($request->all(),
                            [
                            'pixel_id' => 'required'
                            ]
                );
                if ($validator->fails()) {
					
                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                } else {

                    DB::table('config')
						->where('varname', 'pixel_id')
						->update(['value' => $request->pixel_id]);

                    $request->session()->flash('status', "Package has been updated successfuly.");
					return redirect()->back();
                }
            }
    }

}
