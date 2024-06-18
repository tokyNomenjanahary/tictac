<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\PointProximity;
use Illuminate\Support\Facades\Session;

class PointProximityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proximities = PointProximity::all();
        return view('admin.point_proximity.form', compact('proximities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        foreach ($request->point_proximity as $key => $proximity) {
            if (isset($proximity)) {
                if (isset($request->id[$key])) {
                    $point_proximity = PointProximity::find($request->id[$key]);
                    $point_proximity->title = $proximity;
                    $point_proximity->save();
                }else{
                    $point_proximity = new PointProximity();
                    $point_proximity->title = $proximity;
                    $point_proximity->save();
                }    
            }            
        }
        if (isset($request->deleted_id)) {
            $ids = explode(',', $request->deleted_id);
            PointProximity::destroy($ids);
        }
        $request->session()->flash('status', "Point proximity update successfuly.");
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
