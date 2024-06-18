<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Models\StaticPage;
use Illuminate\Support\Facades\DB;


class ManageNotifController extends Controller {
    
    private $perpage;
    
    public function __construct() {
        $this->perpage = config('app.perpage');
    }
    
    public function subscriptionNotif(Request $request) {
        $nb = DB::table("users")->count();
        $users = DB::table('user_packages')->join("users", "users.id", "user_packages.user_id")->whereNotNull("first_name")->orderBy('user_packages.id', "desc")->orderBy('first_name')->limit(100)->get();
        $packages = DB::table('packages')->get();
        return view('admin.notif.trigger_notif', compact("users", "packages"));
        
    }

    public function saveNotifSubscription(Request $request)
    {
        DB::table("notif_triggered")->insert([
            "package_id" => $request->user_package,
            "user_id" => $request->user_prenom
        ]);
        $request->session()->flash("status", "Sauvegarder avec succès");
        return redirect()->back();
    }

}
