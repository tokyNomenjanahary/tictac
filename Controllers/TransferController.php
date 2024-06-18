<?php


namespace App\Http\Controllers;

use App\User;
use Validator;

use App\Http\Models\Ads\Ads;
use App\Mail\NewUserCreated;
use Illuminate\Http\Request;
use App\Http\Models\Ads\AdFiles;
use App\Http\Models\Ads\AdDetails;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Step3Request;
use App\Http\Controllers\Controller;
use App\Http\Models\Ads\AdDocuments;
use App\Http\Models\Ads\AdProximity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Models\Ads\AdToAmenities;
use App\Repositories\MasterRepository;
use App\Http\Models\Ads\AdToGuarantees;
use App\Http\Models\Ads\AdToAllowedPets;
use App\Http\Models\Ads\AdTemporaryFiles;
use App\Http\Models\Ads\NearbyFacilities;
use App\Http\Models\Ads\AdToPropertyRules;

use App\Http\Models\Ads\AdVisitingDetails;
use App\Http\Models\Ads\UploadedGuarantees;
use App\Http\Models\Ads\AdRentalApplications;
use App\Http\Models\Ads\AdToPropertyFeatures;
use App\Http\Models\Ads\TemporaryUploadedGuarantees;
//use Auth;
class TransferController extends Controller
{

    private $nbPhoto = 8;
    function __construct(MasterRepository $master, AdTemporaryFiles $ad_temp_files, Ads $ads, AdDetails $ad_detais, AdFiles $ad_files, AdToAllowedPets $ad_to_allowed_pets, AdToAmenities $ad_to_amenities, AdToGuarantees $ad_to_guarantees, AdToPropertyFeatures $ad_to_property_features, AdToPropertyRules $ad_to_propery_rules, AdVisitingDetails $ad_visiting_details, NearbyFacilities $near_by_facilities, TemporaryUploadedGuarantees $temp_uploaded_guarantees, UploadedGuarantees $uploaded_guarantees, AdRentalApplications $ad_rental_applications) {

       #$this->middleware('auth', ['except' => ['postAnAd', 'uploadFiles', 'deleteFile', 'uploadGuaranteeFiles', 'deleteGuaranteeFile', 'deleteGuaranteeFileUploaded', 'saveStep1', 'saveStep2', 'saveStep3', 'saveAll', 'saveStep1Sc2', 'saveStep2Sc2', 'saveStep3Sc2', 'saveStep4Sc2', 'saveStep1Sc3', 'saveStep2Sc3', 'saveStep3Sc3', 'saveStep1Sc4', 'saveStep2Sc4', 'saveStep3Sc4', 'saveStep4Sc4', 'saveStep1Sc5', 'saveStep2Sc5', 'deleteFileUploaded', 'saveAdressAnnonce', 'rotateImage', "generateAdUrl"]]);

        $this->master = $master;
        $this->AdTempFiles = $ad_temp_files;
        $this->Ads = $ads;
        $this->AdDetails = $ad_detais;
        $this->AdFiles = $ad_files;
        $this->AdToAllowedPets = $ad_to_allowed_pets;
        $this->AdToAmenities = $ad_to_amenities;
        $this->AdToGuarantees = $ad_to_guarantees;
        $this->AdToPropertyFeatures = $ad_to_property_features;
        $this->AdToPropertyRules = $ad_to_propery_rules;
        $this->AdVisitingDetails = $ad_visiting_details;
        $this->NearbyFacilities = $near_by_facilities;
        $this->TemporaryUploadedGuarantees = $temp_uploaded_guarantees;
        $this->UploadedGuarantees = $uploaded_guarantees;
        $this->AdRentalApplications = $ad_rental_applications;

    }

    public function assurance(Request $request)
    {

        return redirect()->route("api.meslocs")->withInput($request->all());
    }

    public function affiche_transfer($id = null, Request $request) {

        if(!isset($request->step)) {
            \Session::forget("signaledAd");
            \Session::forget("commentSignal");
        }
        $layout = 'outer';
        $url_parameter = $request->segment(1);
        if($url_parameter == "adresse-annonce") {
            $type = $request->segment(2);
            return redirect("/" . $type);
            // countShowForm("form4");
            // $type = $request->segment(2);
            // return view('property/adresse-step', compact("layout", "type"));
        }

        if(is_null(getParameter("standard"))) {
            countShowForm("form5");
        }
        $standard = false;
        if(isset($request->standard)){
            $standard = true;
        }



        $AdInfo = [];
        $adDocuments = array();
        if(!empty($id)) {
            $splitUrl = explode("-", $id);
            if(count(explode("~", $id))>1)
                $splitUrl=explode("~", $id);

            $id = $splitUrl[count($splitUrl)-1];
            if(is_int($id)) {
                $AdInfo = Ads::where('id', $id)->where('status', '1')->where('admin_approve', '1')->first();
            }

        }

        $url_parameter = $request->segment(1);

        if ($request->session()->has('AD_INFO') && !isset($request->step)) {
            $request->session()->forget('AD_INFO');
        }

        $propertyTypes = $this->master->getMasters('property_types');
        $petsAllowed = $this->master->getMasters('allowed_pets');
        $propFeatures = $this->master->getMasters('property_features');
        $buildAmenities = $this->master->getMasters('amenities');
        $guarAsked = $this->master->getMasters('guarantees');
        $propRules = $this->master->getMasters('property_rules');
        $studyLevels = $this->master->getMasters('study_levels');
        $socialInterests = $this->master->getMasters('social_interests');
        $countries = $this->master->getMasters('countries', ['status' => 1]);
        $cities = $this->master->getMasters('cities', ['status' => 1, 'country_id' => 1]);
        $userLifestyles = $this->master->getMasters('user_lifestyles');
        $sous_type_loc = $this->master->getMasters('sous_type_loc');
        $proximities = $this->master->getPointProximity();
        $proximities_array = array();

        $address = $request->adresse;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $title = "À louer, Appartement à".$address.".";
        $description ="c'est une appartement de".$request->surface." m2 qui se situe à ".$address."etc.....";
        $loyer = $request->loyer_hors_charge;
        $surface = $request->surface;

        ///////////CHECK EMAIL/////////////////////////
        $request->email = 'tianatatiantest29512td3@gmail.com';
        $userCheck = DB::table("users")->where("email", $request->email)->first();

        if(is_null($userCheck))
        {
                 $password=$this->passgen1(5);
                 $Id = User::insertGetId([
                 'email' => $request->email,
                 'first_name' => $request->prenom,
                 'last_name' => $request->nom,
                 'password' => bcrypt($password),
                 'verified' => '1',
                 'scenario_register'=> '2',
                 'ip' => get_ip()
             ]);
             $user_profile_array = array();

             if (!empty($request->contact)) {
                 $user_profile_array['mobile_no'] = $request->contact;
             }

             $user_profile_array['user_id'] = $Id;
             DB::table("user_profiles")->insert($user_profile_array);
             $remember_me = true;
             $response['error'] = "ok1";
             $response['pass'] = $password;


            if (Auth::attempt(['email' => $request->email, 'password' => $password], $remember_me)) {
                $response['error'] = "ok2";
                $user_id = Auth::id();
            }
        }

///////////////////////////FIN CHECKOUT/////////////////////////////////////


        return view('property/rentaproperty-meslocs', compact('title', 'description','loyer', 'surface','AdInfo','address', 'latitude', 'longitude', 'propertyTypes','petsAllowed','propFeatures','buildAmenities', 'guarAsked', 'propRules', 'layout', 'adDocuments', "standard", "sous_type_loc", 'proximities', 'proximities_array'));

    }





    function passgen1($nbChar) {
        $chaine ="mnoTUzS5678kVvwxy9WXYZRNCDEFrslq41GtuaHIJKpOPQA23LcdefghiBMbj0";
        srand((double)microtime()*1000000);
        $pass = '';
        for($i=0; $i<$nbChar; $i++){
            $pass .= $chaine[rand()%strlen($chaine)];
            }
        return $pass;
        }


}
