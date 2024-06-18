<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Bloqued extends Controller
{
    public function bloqued()
    {
        $user_id = Auth::id();
        $users = DB::table('users')->where('id',$user_id)->first();
        $verif_bloqued = DB::table('blocked_ip')->where('ip',$users->ip)->first();
        if($verif_bloqued){
            return view('bloqued');
        }else{
            return redirect()->route('home');
        }
    }

    public function bloquedUser(){
        return view('bloqued');
    }

}
