<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Package;
use Illuminate\Support\Facades\DB;

class PackagesController extends Controller {
    
    private $perpage;
    
    public function __construct() {
        $this->perpage = config('app.perpage');
    }

    public function index(Request $request) {
        $packages = Package::orderByRaw('created_at DESC')->paginate($this->perpage);
        return view('admin.package.package_list', compact('packages'));
    }
    
    public function editPackage($id, Request $request) {

        $id = base64_decode($id);

        $package = Package::find($id);

        if (!empty($package)) {
            
            if ($request->isMethod('post')) {
   
                $validator = Validator::make($request->all(),
                            [
                            'title' => 'required|min:3|max:100',
                            'description' => 'nullable|max:255',
                            'amount' => 'required|numeric|min:1',
                            'duration' => 'required|integer|min:1',
                            'unite' => 'required',
                            ]
                );

                if ($validator->fails()) {

                    return redirect()->back()->withErrors($validator)->withInput($request->all());
                } else {
                    if(!is_null($request->popular)) {
                        DB::table("packages")->update(['popular' => "0"]);
                        DB::table("packages")->where("id", $id)->update(['popular' => "1"]);
                    } else {
                        DB::table("packages")->where("id", $id)->update(['popular' => "0"]);
                    }
                    $package->title = $request->title;
                    if (!empty($request->description)) {
                        $package->description = $request->description;
                    } else {
                        $package->description = NULL;
                    }
                    $package->amount = $request->amount;
                    $package->duration = $request->duration;
                    $package->unite = $request->unite;

                    if ($package->save()) {

                        $request->session()->flash('status', "Package has been updated successfuly.");
                        return redirect()->back();
                    } else {
                        $request->session()->flash('error', 'Some error occurred. Please try again!');
                        return redirect()->back();
                    }
                }
            }

            return view('admin.package.edit_package', compact('package', 'id'));
            
        } else {
            
            $request->session()->flash('error', 'No package found with this id. Please try again!');
            return redirect()->route('admin.packageList');
            
        }
    }
    
    public function activateDeactivatePackage($id, $status, Request $request) {

        $id = base64_decode($id);
        $status = base64_decode($status);

        if (!empty($status)) {
            $status = '1';
            $msg = 'Package activated successfuly.';
            $msgType = 'status';
        } else {
            $status = '0';
            $msg = 'Package deactivated successfuly.';
            $msgType = 'status';
        }
        $queryStatus = Package::where('id', $id)->update(['is_active' => $status]);

        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }

        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    function listUpselling(Request $request)
    {
        if(isset($request->id))
        {
            $upInfos = DB::table("upselling")->where("id", $request->id)->first();
            $query = "ALTER TABLE ads DROP COLUMN is_" . $upInfos->label;
            DB::statement($query);
            $query = "ALTER TABLE ads DROP COLUMN date_" . $upInfos->label;
            DB::statement($query);
            DB::table("upselling")->where("id", $request->id)->delete();
            $request->session()->flash('status', "Upselling deleted successfuly.");
            return redirect()->back();
        }
        $upsels = DB::table("upselling")->get();
        foreach ($upsels as $key => $upsel) {
            $upsels[$key]->tarifs = DB::table("upselling_tarif")->where("upselling_id", $upsel->id)->get();
        }
        return view('admin.package.upselling_list', compact("upsels"));
    }

    function newUpselling(Request $request)
    {
        if(isset($request->id))
        {
            $upsel = DB::table("upselling")->where("id", $request->id)->first();
            $upsel->tarifs = DB::table("upselling_tarif")->where("upselling_id", $upsel->id)->get();
             return view('admin.package.new_upselling', compact("upsel"));
        }
        return view('admin.package.new_upselling');
    }

    function saveUpselling(Request $request)
    {
        $rules = array("label" => "required","fr_title" => "required","en_title" => "required", "en_description" => "required", "fr_description" => "required");
        foreach ($request->duration as $key => $value) {
            $rules['duration.' . $key] = "required|numeric";
            $rules['price.' . $key] = "required|numeric";
        }
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        } else {
            if(!isset($request->id))
            {
                $label_test = DB::table("upselling")->where('label', str_slug($request->label))->first();
                if(!is_null($label_test))
                {
                    $request->session()->flash('error', "Label already exist, please change");
                    return redirect()->back();
                }
                $id = DB::table("upselling")->insertGetId(
                    ['label' => str_slug($request->label, "_"),'fr_title' => $request->fr_title,'en_title' => $request->en_title , "fr_description" => $request->fr_description, "en_description" => $request->en_description]
                );
                $query = "ALTER TABLE ads ADD COLUMN is_" . str_slug($request->label, "_") . " INT(1) NULL DEFAULT '0'";
                DB::statement($query);
                $query = "ALTER TABLE ads ADD COLUMN date_" . str_slug($request->label, "_") . " DATE NULL DEFAULT NULL";
                DB::statement($query);
                foreach ($request->duration as $key => $dur) {
                    DB::table("upselling_tarif")->insert(
                        ["upselling_id" => $id, "duration" => $dur, "price" => $request->price[$key], "unit" => $request->unit[$key]]
                    );
                }
                $request->session()->flash('status', "Upselling saved successfuly.");
            } else {
                $id = $request->id;
                $label_test = DB::table("upselling")->where('label', str_slug($request->label))->where("id", "<>", $id)->first();
                if(!is_null($label_test))
                {
                    $request->session()->flash('error', "Label already exist, please change");
                    return redirect()->back();
                }

                $upInfos = DB::table("upselling")->where("id", $id)->first();
                DB::table("upselling")->where("id", $id)->update(
                    ['label' => str_slug($request->label, "_"),'fr_title' => $request->fr_title,'en_title' => $request->en_title , "fr_description" => $request->fr_description, "en_description" => $request->en_description]
                );
                $query = "ALTER TABLE ads CHANGE COLUMN is_". $upInfos->label ." is_". str_slug($request->label, "_") ." INT(1) NULL DEFAULT '0'";
                DB::statement($query);
                $query = "ALTER TABLE ads CHANGE COLUMN date_". $upInfos->label ." date_". str_slug($request->label, "_") ." DATE NULL DEFAULT NULL";
                DB::statement($query);
                DB::table("upselling_tarif")->where("upselling_id", $id)->delete();
                foreach ($request->duration as $key => $dur) {
                    DB::table("upselling_tarif")->insert(
                        ["upselling_id" => $id, "duration" => $dur, "price" => $request->price[$key], "unit" => $request->unit[$key]]
                    );
                }
                $request->session()->flash('status', "Upselling updated successfuly.");
            }
        }
        return redirect()->back();
    }

    function listBoostedAds(Request $request)
    {
        $ads = DB::table("boosted_ads")->join("ads", "ads.id", "boosted_ads.ad_id")->join("upselling", "upselling.id", "boosted_ads.boost_id")->join("users", "users.id", "ads.user_id")->join("upselling_tarif", "upselling_tarif.id", "boosted_ads.tarif_id")->select(DB::raw("upselling.*, upselling_tarif.*, ads.title,ads.id as ad_id, users.first_name, users.last_name, boosted_ads.*"))->whereNotNull("boosted_ads.payment_id")->orderBy("boosted_ads.id", "asc")->get();
        return view('admin.package.boosted_ads', compact("ads"));
    }

    function premiumPhrase(Request $request)
    {
        $phrases = DB::table("premium_advantage_phrase")->get();
        return view('admin.package.premium_phrase', compact("phrases"));
    }

    function paymentByCity(Request $request)
    {
        $cities = DB::table('user_packages as up')
        ->select(DB::raw("u.address_register, pay.amount"))
        ->join('users as u', "u.id", "up.user_id")
        ->join("payments as pay", "pay.id", "up.payment_id")
        ->whereNotNull("u.address_register")->get();
        $all_city = [];
        $all_number = [];
        foreach ($cities as $key => $city) {
            if (array_key_exists(trim(getAdressVilleV2($city->address_register)), $all_city)) {
                $new_value = $all_city[trim(getAdressVilleV2($city->address_register))] + $city->amount/100;
                $all_city[trim(getAdressVilleV2($city->address_register))] = $new_value;
                $new_number = $all_number[trim(getAdressVilleV2($city->address_register))] + 1;
                $all_number[trim(getAdressVilleV2($city->address_register))] = $new_number;    
            }else{
                $all_city[trim(getAdressVilleV2($city->address_register))] = $city->amount/100;
                $all_number[trim(getAdressVilleV2($city->address_register))] = 1;
            }            
        }
        arsort($all_city);
        return view('admin.package.payment_by_city', compact("all_city","all_number"));
    }

    function managePhrase(Request $request)
    {
            $phrases = $request->phrase_fr;
            $type_membre = $request->type_membre;
            if(count($phrases) > 0) {
                if(!empty($phrases[0])) {
                    DB::table("premium_advantage_phrase")->delete();
                    foreach ($phrases as $key => $phrase) {
                        if(!empty($phrase)) {
                           DB::table("premium_advantage_phrase")->insert([
                                "phrase_fr" => $phrase,
                                "type_membre" => $type_membre[$key]
                            ]); 
                        }
                        
                    }
                }
                
            }
            $request->session()->flash('status', "Saved successfuly.");
            return redirect()->back();
    }
}
