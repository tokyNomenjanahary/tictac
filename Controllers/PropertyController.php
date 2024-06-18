<?php


namespace App\Http\Controllers;

use App\User;
use Validator;

use App\Http\Models\Ads\Ads;
use App\Http\Models\Ads\Ads_duplication;

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
use Illuminate\Validation\Rule;

//use Auth;

class PropertyController extends Controller
{
    private $nbPhoto = 8;

    function __construct(MasterRepository $master, AdTemporaryFiles $ad_temp_files, Ads $ads, AdDetails $ad_detais, AdFiles $ad_files, AdToAllowedPets $ad_to_allowed_pets, AdToAmenities $ad_to_amenities, AdToGuarantees $ad_to_guarantees, AdToPropertyFeatures $ad_to_property_features, AdToPropertyRules $ad_to_propery_rules, AdVisitingDetails $ad_visiting_details, NearbyFacilities $near_by_facilities, TemporaryUploadedGuarantees $temp_uploaded_guarantees, UploadedGuarantees $uploaded_guarantees, AdRentalApplications $ad_rental_applications)
    {

        $this->middleware('auth', ['except' => ['postAnAd', 'saveStepm1', 'uploadFiles', 'deleteFile', 'uploadGuaranteeFiles', 'deleteGuaranteeFile', 'deleteGuaranteeFileUploaded', 'saveStep1', 'saveStep2', 'saveStep3', 'saveAll', 'saveStep1Sc2', 'saveStep2Sc2', 'saveStep3Sc2', 'saveStep4Sc2', 'saveStep1Sc3', 'saveStep2Sc3', 'saveStep3Sc3', 'saveStep1Sc4', 'saveStep2Sc4', 'saveStep3Sc4', 'saveStep4Sc4', 'saveStep1Sc5', 'saveStep2Sc5', 'deleteFileUploaded', 'saveAdressAnnonce', 'rotateImage', "generateAdUrl"]]);

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
        date_default_timezone_set(config('app.timezone'));
    }

    public function confirmAnnonce(Request $request)
    {
        $ad = Ads::where('user_id', Auth::id())->orderBy('id', 'DESC')->first();
        // Mail to user
        if (!is_null($ad)) {
            return view('property.ad-creation-confirmation', compact("ad"));
        } else {
            return redirect()->route("user.dashboard");
        }
    }

    public function deleteAd(Request $request, $id = null)
    {
        DB::table('ads')->where('id', $id)->delete();
        $request->session()->flash('status', __("backend_messages.ad__delete"));
        return redirect()->route("user.dashboard");
    }

    public function deactiveAd(Request $request, $id = null)
    {
        DB::table('ads')->where('id', $id)->update(['admin_approve' => '0']);
        $request->session()->flash('status', __("backend_messages.ad__disabled"));
        return redirect()->route("user.dashboard");
    }


    public function postAnAd(Request $request, $id = null)
    {
        if (!isset($request->step)) {
            \Session::forget("signaledAd");
            \Session::forget("commentSignal");
        }
        $layout = 'outer';
        $url_parameter = $request->segment(1);
        // dd($url_parameter);
        if ($url_parameter == "adresse-annonce") {
            $type = $request->segment(2);
            return redirect("/" . $type);
        }

        if (is_null(getParameter("standard"))) {
            countShowForm("form5");
        }
        $standard = false;
        if (isset($request->standard)) {
            $standard = true;
        }
        $address = '';
        $latitude = '';
        $longitude = '';

        if ($request->post() && $request->Search == 'Search') {
            if (!empty($request->address) && !empty($request->latitude) && !empty($request->longitude)) {
                $address = $request->address;
                $latitude = $request->latitude;
                $longitude = $request->longitude;
            }
        }

        $AdInfo = [];
        $adDocuments = array();
        if (!empty($id)) {
            //$id = base64_decode($id);
            $splitUrl = explode("-", $id);
            if (count(explode("~", $id)) > 1) {
                $splitUrl = explode("~", $id);
            }
            $id = $splitUrl[count($splitUrl) - 1];
            if (is_int($id)) {
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


        if ($url_parameter == 'louer-une-propriete' || (!empty($AdInfo) && $AdInfo->scenario_id == '3')) {

            return view('property/rentaproperty', compact('AdInfo', 'address', 'latitude', 'longitude', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'layout', 'adDocuments', "standard", "sous_type_loc", 'proximities', 'proximities_array'));
        } else if ($url_parameter == 'partager-un-logement' || (!empty($AdInfo) && $AdInfo->scenario_id == '4')) {


            return view('property/shareanaccommodation', compact('AdInfo', 'address', 'latitude', 'longitude', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'studyLevels', 'socialInterests', 'countries', 'cities', 'userLifestyles', 'layout', 'adDocuments', "standard", 'proximities', 'proximities_array'));
        } else if ($url_parameter == 'chercher-a-louer-une-propriete' || (!empty($AdInfo) && $AdInfo->scenario_id == '1')) {

            return view('property/seekrentaproperty', compact('AdInfo', 'address', 'latitude', 'longitude', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'layout', 'adDocuments', "standard", "sous_type_loc", 'proximities', 'proximities_array'));
        } else if ($url_parameter == 'chercher-a-partager-un-logement' || (!empty($AdInfo) && $AdInfo->scenario_id == '2')) {
            return view('property/seekshareanaccom', compact('AdInfo', 'address', 'latitude', 'longitude', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'studyLevels', 'socialInterests', 'countries', 'cities', 'userLifestyles', 'layout', 'adDocuments', "standard", 'proximities', 'proximities_array'));
        } else if ($url_parameter == 'chercher-ensemble-une-propriete' || (!empty($AdInfo) && $AdInfo->scenario_id == '5')) {

            return view('property/seekcompoundasearch', compact('AdInfo', 'address', 'latitude', 'longitude', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'studyLevels', 'socialInterests', 'countries', 'cities', 'userLifestyles', 'layout', 'adDocuments', "standard", 'proximities', 'proximities_array'));
        }
    }

    public function editAnAd($id, Request $request)
    {
        if (!isset($request->step)) {
            \Session::forget("signaledAd");
        }

        $standard = true;
        if ($request->session()->has('AD_INFO') && !isset($request->step)) {
            $request->session()->forget('AD_INFO');
        }

        $splitUrl = explode("-", $id);
        if (count(explode("~", $id)) > 1) {
            $splitUrl = explode("~", $id);
        }
        $id = $splitUrl[count($splitUrl) - 1];

        $ad_to_guarantees = DB::table('ad_to_guarantees')->where('ad_id', $id)->exists();
        if (!$ad_to_guarantees) {
            DB::table('ad_to_guarantees')->insert(['ad_id' => $id, 'guarantees_id' => 1]);
        }

        $adDocuments = $this->getAllDocuments($id);
        $propertyTypes = $this->master->getMasters('property_types');
        $petsAllowed = $this->master->getMasters('allowed_pets');
        $propFeatures = $this->master->getMasters('property_features');
        $buildAmenities = $this->master->getMasters('amenities');
        $guarAsked = $this->master->getMasters('guarantees');
        $propRules = $this->master->getMasters('property_rules');
        $sous_type_loc = $this->master->getMasters('sous_type_loc');
        $proximities = $this->master->getPointProximity();

        $user_id = Auth::id();

        //verification si l'id de l'annonce est dans la base de donnée
        $verifDB = DB::table('ads')->where('id', $id)->first();

        if (!empty($verifDB)) {
            //on cherche dans table Ads le min_rent de l'annonce
            $actual_rent = Ads::where('id', $id)->first()->min_rent;
            $rental_appli = Ads::with(['ad_rental_applications'])->where('id', $id)->where('status', '1')->first();
            //sur la modification de scénario 3, le prix n'est pas obligatoire
            //alors s'il n'y a pas de prix, on assigne une valeur de 0 à $actual_rent
            if (empty($actual_rent)) {
                $actual_rent = 0;
            }
            if ($rental_appli) {
                if ($rental_appli->ad_rental_applications == null) {
                    DB::table('ad_rental_applications')->insert(
                        ['ad_id' => $id, 'actual_renting_price' => $actual_rent]
                    );
                }
            }
        } else {
            //on retourne en 404 si on ne trouve pas l'id de l'annonce
            return abort(404);
        }
        $actual_rent = $actual_rent == null ? 0 : $actual_rent;

        $ad_details = Ads::with(['ad_details', 'ad_files' => function ($query) {
            $query->where('media_type', '0')->orderBy('ordre', 'asc');
        }, 'nearby_facilities', 'ad_to_allowed_pets', 'ad_to_property_features', 'ad_to_amenities', 'ad_to_guarantees', 'ad_to_property_rules', 'ad_visiting_details', 'ad_rental_applications', 'ad_uploaded_guarantees', 'ad_proximities'])->where('id', $id)->where('status', '1')->first();
        $edite = 1;
        $edit = true;
        $layout = 'inner';
        if (!empty($ad_details)) {

            if (!empty($ad_details->ad_to_allowed_pets) && count($ad_details->ad_to_allowed_pets) > 0) {
                foreach ($ad_details->ad_to_allowed_pets as $allowed_pets) {
                    $allowed_pets_array[] = $allowed_pets->allowed_pets_id;
                }
            } else {
                $allowed_pets_array = array();
            }

            if (!empty($ad_details->ad_to_property_features) && count($ad_details->ad_to_property_features) > 0) {
                foreach ($ad_details->ad_to_property_features as $property_features) {
                    $property_features_array[] = $property_features->property_features_id;
                }
            } else {
                $property_features_array = array();
            }

            if (!empty($ad_details->ad_to_amenities) && count($ad_details->ad_to_amenities) > 0) {
                foreach ($ad_details->ad_to_amenities as $amenities) {
                    $amenities_array[] = $amenities->amenities_id;
                }
            } else {
                $amenities_array = array();
            }

            if (!empty($ad_details->ad_to_guarantees) && count($ad_details->ad_to_guarantees) > 0) {
                foreach ($ad_details->ad_to_guarantees as $guarantees) {
                    $guarantees_array[] = $guarantees->guarantees_id;
                }
            } else {
                $guarantees_array = array();
            }

            if (!empty($ad_details->ad_to_property_rules) && count($ad_details->ad_to_property_rules) > 0) {
                foreach ($ad_details->ad_to_property_rules as $property_rules) {
                    $property_rules_array[] = $property_rules->property_rules_id;
                }
            } else {
                $property_rules_array = array();
            }

            $ad_proximities = AdProximity::with('proximity')->where('ad_id', $ad_details->id)->get();

            if (!empty($ad_proximities) && count($ad_proximities) > 0) {
                foreach ($ad_proximities as $ads_proximity) {
                    $proximities_array[] = $ads_proximity->point_proximity_id;
                }
            } else {
                $proximities_array = array();
            }

            if ($ad_details->scenario_id == 1) {
                return view('property/rentaproperty', compact('edite', 'ad_details', 'allowed_pets_array', 'property_features_array', 'amenities_array', 'guarantees_array', 'property_rules_array', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'layout', 'adDocuments', 'standard', "sous_type_loc", "edit", 'proximities', 'proximities_array'));
            } else if ($ad_details->scenario_id == 2) {
                return view('property/shareanaccommodation', compact('edite', 'ad_details', 'allowed_pets_array', 'property_features_array', 'amenities_array', 'guarantees_array', 'property_rules_array', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'layout', 'adDocuments', 'standard', "edit", 'proximities', 'proximities_array'));
            } else if ($ad_details->scenario_id == 3) {
                return view('property/seekrentaproperty', compact('edite', 'ad_details', 'allowed_pets_array', 'property_features_array', 'amenities_array', 'guarantees_array', 'property_rules_array', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'layout', 'standard', "sous_type_loc", "edit", 'proximities', 'proximities_array'));
            } else if ($ad_details->scenario_id == 4) {
                return view('property/seekshareanaccom', compact('edite', 'ad_details', 'allowed_pets_array', 'property_features_array', 'amenities_array', 'guarantees_array', 'property_rules_array', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'layout', 'adDocuments', 'standard', "edit", 'proximities', 'proximities_array'));
            } else if ($ad_details->scenario_id == 5) {
                return view('property/seekcompoundasearch', compact('edite', 'ad_details', 'allowed_pets_array', 'property_features_array', 'amenities_array', 'guarantees_array', 'property_rules_array', 'propertyTypes', 'petsAllowed', 'propFeatures', 'buildAmenities', 'guarAsked', 'propRules', 'layout', 'adDocuments', 'standard', "edit", 'proximities', 'proximities_array'));
            }
        } else {
            $request->session()->flash('error', __('backend_messages.no_ad_found'));

            return redirect()->route('user.dashboard');
        }
    }

    private function getMaxTemporaryFilesOrdre($unique_id)
    {
        $file = AdTemporaryFiles::where("unique_id", $unique_id)->orderBy('ordre', "desc")->first();
        if ($file) {
            return $file->ordre + 1;
        } else {
            return 0;
        }
    }

    public function uploadFiles(Request $request)
    {

        if (count($request->file('file_photos')) > 0 || count($request->file('file_video')) > 0) {
            if (count($request->file('file_photos')) > 0) {
                $files = $request->file('file_photos');
            } else {
                $files = $request->file('file_video');
            }

            $destinationPathImages = base_path() . '/storage/uploads/images_annonces/';
            $destinationPathVideo = base_path() . '/storage/uploads/videos_annonces/';
            if ($request->session()->has('AD_INFO')) {
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
            } else {
                $unique_id = uniqid();
                $request->session()->put('AD_INFO', array('unique_id' => $unique_id));
            }
            $ordre = $this->getMaxTemporaryFilesOrdre($unique_id);
            $upload_response = array();
            foreach ($files as $file) {

                $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $user_filename = $file->getClientOriginalName();
                $media_type = $request->media_type;

                $AdTempFiles = new $this->AdTempFiles;
                if ($media_type == 0) {
                    $file->move($destinationPathImages, $file_name);
                    pasteLogo($destinationPathImages . $file_name);
                    $size = filesize($destinationPathImages . $file_name);
                    if ($size > 40000) {
                        compressImage($destinationPathImages . $file_name, removeExtension($file_name), $destinationPathImages, 65, 9);
                    }
                    copy($destinationPathImages . $file_name, $destinationPathImages . "ancien_" . $file_name);
                } else {
                    $file->move($destinationPathVideo, $file_name);
                }

                $AdTempFiles->unique_id = $unique_id;
                $AdTempFiles->filename = $file_name;
                $AdTempFiles->user_filename = $user_filename;
                $AdTempFiles->media_type = $media_type;
                $AdTempFiles->ordre = $ordre;
                $AdTempFiles->save();
                $ordre++;
                if ($media_type == 0) {
                    $upload_response['initialPreview'][] = "<img src='/uploads/images_annonces/" . $file_name . "' class='file-preview-image' alt='" . $user_filename . "' title='" . $user_filename . "'>";
                } else {
                    $upload_response['initialPreview'][] = "<img src='/images/video-file-icon.png' class='file-preview-image' alt='" . $user_filename . "' title='" . $user_filename . "'>";
                }
                $upload_response['initialPreviewConfig'][] = array('caption' => $user_filename, 'size' => $size, 'width' => '120px', 'url' => '/ad/deletefile', 'key' => $unique_id, 'extra' => array('file_name' => $file_name, 'type' => "temp", "id" => $AdTempFiles->id));
            }
            $upload_response['append'] = TRUE;
            return response()->json($upload_response);
        }
    }

    public function deleteFile(Request $request)
    {
        $post_data = $request->input();

        if (!empty($post_data)) {
            $AdTempFiles = new $this->AdTempFiles;
            $deletedRow = $AdTempFiles->where('unique_id', $post_data['key'])->where('filename', $post_data['file_name'])->delete();
        }
        return response()->json(array());
    }

    public function deleteFileUploaded(Request $request)
    {
        dd($request->all());
        $post_data = $request->input();

        if (!empty($post_data)) {
            $AdFiles = new $this->AdFiles;
            $deletedRow = $AdFiles->find($post_data['key'])->delete();
        }
        return response()->json(array());
    }

    public function uploadGuaranteeFiles(Request $request)
    {

        if (!empty($request->file('file_guarantees'))) {

            $file = $request->file('file_guarantees');
            $destinationPath = base_path() . '/storage/uploads/ads_guarantees/';

            if ($request->session()->has('AD_INFO')) {
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
            } else {
                $unique_id = uniqid();
                $request->session()->put('AD_INFO', array('unique_id' => $unique_id));
            }

            $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $user_filename = $file->getClientOriginalName();
            $index_id = $request->index;

            $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
            $user_id = Auth::id();

            if (!empty($request->key) && !empty($request->ad_id) && !empty($user_id)) {
                $ad_details = Ads::where('id', $request->ad_id)->where('user_id', $user_id)->first();
                if (!empty($ad_details)) {
                    $UploadedGuarantees = new $this->UploadedGuarantees;
                    $UploadedGuarantees->find($request->key)->delete();
                }
            }
            //delete from temporary DB table from same index_id and unique_id
            $deletedRow = $TemporaryUploadedGuarantees->where('unique_id', $unique_id)->where('index_id', $index_id)->delete();

            $file->move($destinationPath, $file_name);

            $TemporaryUploadedGuarantees->index_id = $index_id;
            $TemporaryUploadedGuarantees->unique_id = $unique_id;
            $TemporaryUploadedGuarantees->filename = $file_name;
            $TemporaryUploadedGuarantees->user_filename = $user_filename;
            $TemporaryUploadedGuarantees->save();

            $response = ['user_filename' => $user_filename, 'filename' => $file_name, 'unique_id' => $unique_id];
            return response()->json($response);
        }
    }

    public function deleteGuaranteeFile(Request $request)
    {

        if ($request->ajax()) {
            $get_data = $request->input();
            if (!empty($get_data)) {
                $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
                $deletedRow = $TemporaryUploadedGuarantees->where('unique_id', $get_data['unique_id'])->where('filename', $get_data['filename'])->delete();
            }
        }

        return response()->json(array());
    }

    public function deleteGuaranteeFileUploaded(Request $request)
    {

        if ($request->ajax()) {
            $get_data = $request->input();
            if (!empty($get_data)) {
                $user_id = Auth::id();
                if (!empty($get_data['key']) && !empty($get_data['ad_id']) && !empty($user_id)) {
                    $ad_details = Ads::where('id', $get_data['ad_id'])->where('user_id', $user_id)->first();
                    if (!empty($ad_details)) {
                        $UploadedGuarantees = new $this->UploadedGuarantees;
                        $UploadedGuarantees->find($get_data['key'])->delete();
                    }
                }
            }
        }

        return response()->json(array());
    }

    public function saveAdressAnnonce(Request $request)
    {

        $request->session()->put('ADRESS_INFO', (object)array('address' => $request->address, 'latitude' => $request->latitude, "longitude" => $request->longitude));
        countValidateForm("form4");
        return redirect($request->url);
    }

    private function saveProximity($ad_id, $proximities)
    {
        DB::table('ad_proximity')->where("ad_id", $ad_id)->delete();
        foreach ($proximities as $key => $proximity) {
            DB::table('ad_proximity')->insert([
                "ad_id" => $ad_id,
                "point_proximity_id" => $proximity
            ]);
        }
    }

    private function saveMetrolines($ad_id, $metro_lines)
    {
        DB::table('nearby_facilities')->where("ad_id", $ad_id)->delete();
        foreach ($metro_lines as $key => $metro_line) {
            DB::table('nearby_facilities')->insert([
                "ad_id" => $ad_id,
                "name" => $metro_line,
                "nearbyfacility_type" => "subway_station"
            ]);
        }
    }

    private function saveAllowedPets($ad_id, $allowed_pets)
    {
        DB::table("ad_to_allowed_pets")->where("ad_id", $ad_id)->delete();
        foreach ($allowed_pets as $all_pet) {

            $AdToAllowedPets = new $this->AdToAllowedPets;

            $AdToAllowedPets->ad_id = $ad_id;
            $AdToAllowedPets->allowed_pets_id = $all_pet;

            $AdToAllowedPets->save();
        }
    }

    private function savePropFeatures($ad_id, $propFeatures)
    {
        DB::table("ad_to_property_features")->where("ad_id", $ad_id)->delete();
        foreach ($propFeatures as $prop_feat) {

            $AdToPropertyFeatures = new $this->AdToPropertyFeatures;

            $AdToPropertyFeatures->ad_id = $ad_id;
            $AdToPropertyFeatures->property_features_id =
                $prop_feat;

            $AdToPropertyFeatures->save();
        }
    }

    private function saveBuildingAmenities($ad_id, $amenities)
    {
        DB::table("ad_to_amenities")->where("ad_id", $ad_id)->delete();
        foreach ($amenities as $build_amen) {

            $AdToAmenities = new $this->AdToAmenities;

            $AdToAmenities->ad_id = $ad_id;
            $AdToAmenities->amenities_id = $build_amen;

            $AdToAmenities->save();
        }
    }

    private function savePropertyRules($ad_id, $rules)
    {
        DB::table("ad_to_property_rules")->where("ad_id", $ad_id)->delete();
        foreach ($rules as $prop_rule) {

            $AdToPropertyRules = new $this->AdToPropertyRules;

            $AdToPropertyRules->ad_id = $ad_id;
            $AdToPropertyRules->property_rules_id = $prop_rule;

            $AdToPropertyRules->save();
        }
    }

    private function saveAdGuarantee($ad_id, $guarantees)
    {
        DB::table("ad_to_guarantees")->where("ad_id", $ad_id)->delete();
        foreach ($guarantees as $key => $guar) {
            if ($guar != 0) {
                DB::table("ad_to_guarantees")->insert(
                    ["ad_id" => $ad_id, "guarantees_id" => $guar, "created_at" => date("Y-m-d"), "updated_at" => date("Y-m-d H:i:s")]
                );
            }
        }
    }

    private function sendMailAdminUserRegistration($user)
    {


        $subject = __('mail.user_registration');

        try {
            sendMailAdmin('emails.admin.newUserCreated', [
                "subject" => $subject,
                "user" => $user
            ]);
        } catch (Exception $ex) {
        }


        return true;
    }

    public function registerWithAd($data)
    {
        $email = $data->email;
        $token = time() . str_random(30);
        $userCheck = User::where("email", $email)->first();
        if (!is_null($userCheck)) {
            $user_id = $userCheck->id;
            User::where("id", $user_id)->update([
                'first_name' => trim($data->first_name),
                'last_name' => trim($data->last_name),
                'email' => $email,
                'password' => bcrypt($data->password),
                'verified' => '0',
                'verification_token' => $token,
                'ip' => get_ip(),
                'scenario_register' => $data->scenario_id,
                'address_register' => $data->address
            ]);

            $user = $userCheck;
        } else {
            $user = User::create([
                'first_name' => trim($data->first_name),
                'last_name' => trim($data->last_name),
                'email' => $email,
                'password' => bcrypt($data->password),
                'verified' => '0',
                'verification_token' => $token,
                'ip' => get_ip(),
                'scenario_register' => $data->scenario_id,
                'address_register' => $data->address
            ]);
            countUsers("insert");
        }

        DB::table("users")->where("id", $user->id)->update(["scenario_register" => $data->scenario_id, "address_register" => $data->address]);

        $userInfo = (object)array(
            "email" => $email,
            "first_name" => $data->first_name,
            "last_name" => $data->last_name,
            "postal_code" => trim($data->postal_code),
            "mobile_no" => manageMobileNo($data->dial_code, trim($data->mobile_no))
        );


        $this->sendMailAdminUserRegistration($userInfo);

        $user_profile_array = array();
        if (!empty($data->sex)) ;
        $user_profile_array['sex'] = $data->sex;

        if (!empty($data->iso_code)) {
            $user_profile_array['iso_code'] = trim($data->iso_code);
        }

        if (!empty($data->dial_code)) {
            $user_profile_array['dial_code'] = trim($data->dial_code);
        }

        if (!empty($data->valid_number)) {
            $user_profile_array['mobile_no'] = trim($data->valid_number);
            $len = strlen($data->dial_code);
            if ($data->valid_number[$len] == 0) {
                $user_profile_array['mobile_no'] = "+" . $data->dial_code . substr($data->valid_number, $len);
            }
        } else {
            $user_profile_array['mobile_no'] = trim($data->mobile_no);
        }

        $user_profile_array['postal_code'] = trim($data->postal_code);
        $user_profile_array['birth_date'] = date("Y-m-d", strtotime($data->birth_date));

        $user->user_profiles()->create($user_profile_array);

        Auth::login($user, true);

        $subject = __('login.registered_with');

        $UserName = trim($data->first_name);
        $VerificationLink = url('/users/verify/email') . '/' . $token;


        try {
            sendMail($email, 'emails.users.registration', [
                "subject" => $subject,
                "MailSubject" => $subject,
                "UserName" => $UserName,
                "userId" => $user->id,
                "VerificationLink" => $VerificationLink

            ]);
        } catch (Exception $ex) {
        }
        return $user->id;
    }

    public function saveStep1(Request $request)
    {
        //scenario_1
        if ($request->contact_continue == '0') {
            $strings = array($request->title, $request->description);
            if (!isInfoWithoutContact($strings)) {
                return response()->json(['error' => "contact_error", "message" => __('backend_messages.error_contact_ad')]);
            }
            $request->session()->forget('signaledAd');
        } else {
            $request->session()->put('signaledAd', true);
        }

        $data = $request->all();
        if (!array_key_exists("latitude", $data)) {
            $addressInfo = $request->session()->get("ADRESS_INFO");
            $latitude = $addressInfo->latitude;
            $longitude = $addressInfo->longitude;
            $address = $addressInfo->address;
        } else {
            $address = $request->address;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
        }


        modifyVilleAdresse($request->address, $latitude, $longitude);

        $latitude = round($latitude, 6);
        $longitude = round($longitude, 6);
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['address'] = $address;
        $data['actual_address'] = $address;
        $sc_id = $data['scenario_id'];
        $response = array();
        $test = getConfig("annonce_ads");
        $pieces = explode(" , ", $test);


        if ($data['button_clicked'] == "savestandard") {
            foreach ($pieces as $valeur) {

                if (preg_match("/\b" . $valeur . "\b/i", $data['title'])) {
                    $data['alert_contact'] = true;
                }
                if (preg_match("/\b" . $valeur . "\b/i", $data['description'])) {
                    $data['alert_contact'] = true;
                }
            }

            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn(Ads::where('user_id', Auth::id())->pluck('title')->toArray()),],
                    'description' => 'required|min:10|max:2000|unique:ads',
                    'rent_per_month' => ($sc_id == 1 || $sc_id == 2) ? "required|numeric" : '',
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : '',
                    'prop_square_meters' => array_key_exists("prop_square_meters", $data) ? 'required|numeric|min:1' : '',
                    'date_of_availablity' => array_key_exists("date_of_availablity", $data) ? 'required|date_format:d/m/Y' : ''

                ],
                [
                    'latitude.required' => __('property.adress_error'),
                    'longitude.required' => __('property.adress_error'),
                    'title.not_in' => __('backend_messages.value_taken'),
                ]
            );

        } else {
            if (!(isset($request->edite) && $request->edite == "edite")) {
                $validator = Validator::make(
                    $data,
                    [
                        'title' => ['required', 'min:3', 'max:100', Rule::notIn(Ads::where('user_id', Auth::id())->pluck('title')->toArray()),],
                        'description' => ['required', 'min:10', 'max:2000', Rule::unique('ads')->ignore($request->ad_id),],
                        'address' => 'required',
                        'latitude' => 'required',
                        'longitude' => 'required',
                        'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                        'password' => array_key_exists("email", $data) ? 'required' : '',
                        'first_name' => array_key_exists("email", $data) ? 'required' : '',
                        'last_name' => array_key_exists("email", $data) ? 'required' : '',
                        'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                    ],
                    [
                        'latitude.required' => __('property.adress_error'),
                        'longitude.required' => __('property.adress_error'),
                        'title.not_in' => __('backend_messages.value_taken')
                    ]
                );
            } else {
                $refuse = Ads::where('user_id', Auth::id())->pluck('title');
                $ad_title = Ads::where('id', $request->ad_id)->get();
                $key_title = $refuse->search($ad_title->pluck('title')->first());

                if ($key_title !== false)
                    $refuse->splice($key_title, 1);
                $validator = Validator::make(
                    $data,
                    [
                        'title' => ['required', 'min:3', 'max:100', Rule::notIn($refuse->toArray()),],
                        'description' => ['required', 'min:10', 'max:2000', Rule::unique('ads')->ignore($request->ad_id),],
                        'address' => 'required',
                        'latitude' => 'required',
                        'longitude' => 'required',
                        'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                        'password' => array_key_exists("email", $data) ? 'required' : '',
                        'first_name' => array_key_exists("email", $data) ? 'required' : '',
                        'last_name' => array_key_exists("email", $data) ? 'required' : '',
                        'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                    ],
                    [
                        'latitude.required' => __('property.adress_error'),
                        'longitude.required' => __('property.adress_error'),
                        'title.not_in' => __('backend_messages.value_taken')
                    ]
                );
            }
        }


        if ($validator->passes()) {

            $response['error'] = 'no';

            if ($request->session()->has('AD_INFO')) {
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
            } else {
                $unique_id = uniqid();
            }

            if ($data['button_clicked'] == "savestandard") {
                $request->session()->put('AD_INFO', array('unique_id' => $unique_id, 'betaVersion' => "true", 'step1_data' => $data));
            } else {
                $user_id = Auth::id();
                if (isset($data['email'])) {
                    $user_id = $this->registerWithAd((object)$data);
                }
                $step1_data = $data;
                $AdTempFiles = new $this->AdTempFiles;
                $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
                $Ads = new $this->Ads;
                if (!empty($data['ad_id'])) {
                    $ad_id = $data['ad_id'];
                    $action = 'edit';
                } else {
                    $action = "add";
                }
                $ad = (object)array(
                    "title" => $data['title'],
                    "description" => $data['description'],
                    "adress" => (!empty($data['actual_address'])) ? $data['actual_address'] : $data['address']
                );
                if (isset($ad_id)) {
                    //verifier s'il y a une modification pour le reverifier du coté admin
                    $Ads = $Ads::find($ad_id);
                    if ($Ads->title !== $request->title || $Ads->description !== $request->description) {
                        $Ads->ad_treaty = 0;
                    }
                }

                if ($action != "edit" && Auth::check()) {
                    $Ads->user_id = $user_id;
                    $Ads->ad_treaty = 0;
                    $Ads->updated_at = date("Y-m-d H:i:s");
                }
                $Ads->title = $step1_data['title'];
                $Ads->description = $step1_data['description'];
                if (!empty($step1_data['actual_address'])) {
                    $Ads->address = $step1_data['actual_address'];
                } else {
                    $Ads->address = $step1_data['address'];
                }
                $Ads->latitude = $step1_data['latitude'];
                $Ads->longitude = $step1_data['longitude'];
                $Ads->scenario_id = $step1_data['scenario_id'];
                $Ads->status = '1';
                if (isset($step1_data['alert_contact'])) {
                    $Ads->alert_contact = $step1_data['alert_contact'];
                }
                $Ads->url_slug = str_slug($step1_data['title'], '-');
                if (isset($step1_data['ad_url'])) {
                    $Ads->custom_url = $step1_data['ad_url'];
                }
                $Ads->admin_approve = '1';
                $Ads->save();
                countAds("insert");
                $ad_id = $Ads->id;
                if (isset($step1_data['proximity'])) {
                    $this->saveProximity($ad_id, $step1_data['proximity']);
                }
                if (isset($step1_data['metro_lines'])) {
                    $this->saveMetroLines($ad_id, $step1_data['metro_lines']);
                }

                $this->saveAdMediaFiles($ad_id, $unique_id);
                $this->deleteAncienFiles($ad_id);
                $request->session()->put('AD_INFO', array('unique_id' => $unique_id, "ad_id" => $ad_id, "action" => $action));
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];
        if ($data['button_clicked'] == "savestandard") {
            if (Auth::check()) {
                $response['user_type'] = "logged_in";
            } else {
                $response['user_type'] = "guest";
            }
            $response['redirect_url'] = route('save.all');
            if (isset($request->session()->get('AD_INFO')['step1_data']['email'])) {
                $response['redirect_url'] = route('registerWithAd');
            }
        }

        return response()->json($response);
    }

    private function validatorpost_fixe($ad_id, $visit_data)
    {
        DB::table('ad_visiting_details')->where("ad_id", $ad_id)->delete();
        foreach ($visit_data['date_of_visit'] as $key => $visit_detail) {
            if (!empty($visit_detail)) {

                $AdVisitingDetails = new $this->AdVisitingDetails;

                $AdVisitingDetails->ad_id = $ad_id;
                $AdVisitingDetails->visiting_date = date("Y-m-d", strtotime(convertDateWithTiret($visit_detail)));
                $AdVisitingDetails->start_time = date('H:i:s', strtotime($visit_data['start_time'][$key]));
                if ($visit_data['end_time'][$key] != '') {
                    $AdVisitingDetails->end_time = date('H:i:s', strtotime($visit_data['end_time'][$key]));
                }
                if ($visit_data['note'][$key] != '') {
                    $AdVisitingDetails->notes = $visit_data['note'][$key];
                }

                $AdVisitingDetails->save();
            }
        }
    }

    public function saveStepm1(Request $request)
    {
        if ($request->contact_continue == '0') {
            $strings = array($request->title, $request->description);
            if (!isInfoWithoutContact($strings)) {
                return response()->json(['error' => "contact_error", "message" => __('backend_messages.error_contact_ad')]);
            }
            $request->session()->forget('signaledAd');
        } else {
            $request->session()->put('signaledAd', true);
        }

        $data = $request->all();
        if (!array_key_exists("latitude", $data)) {
            $addressInfo = $request->session()->get("ADRESS_INFO");
            $latitude = $addressInfo->latitude;
            $longitude = $addressInfo->longitude;
            $address = $addressInfo->address;
        } else {
            $address = $request->address;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
        }


        modifyVilleAdresse($request->address, $latitude, $longitude);


        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['address'] = $address;
        $data['actual_address'] = $address;
        $sc_id = $data['scenario_id'];
        $response = array();
        if ($data['button_clicked'] == "savestandard") {
            $validator = Validator::make(
                $data,
                [
                    'title' => 'required|min:3|max:100|unique:ads',
                    'description' => 'required|min:10|max:2000|unique:ads',
                    'rent_per_month' => ($sc_id == 1 || $sc_id == 2) ? "required|numeric" : '',
                    'email' => 'required|max:100|email',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'prop_square_meters' => array_key_exists("prop_square_meters", $data) ? 'required|numeric|min:1' : '',
                    'date_of_availablity' => array_key_exists("date_of_availablity", $data) ? 'required|date_format:d/m/Y' : ''

                ],
                [
                    'latitude.required' => __('property.adress_error'),
                    'longitude.required' => __('property.adress_error')
                ]
            );
        } else {
            $validator = Validator::make(
                $data,
                [
                    'title' => 'required|min:3|max:100',
                    'description' => 'required|min:10|max:2000',
                    'address' => 'required',
                    'latitude' => 'required',
                    'longitude' => 'required',
                    'email' => 'required|max:100|email',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                ],
                [
                    'latitude.required' => __('property.adress_error'),
                    'longitude.required' => __('property.adress_error')
                ]
            );
        }


        if ($validator->passes()) {
            $user_id = Auth::id();
            if (is_null($user_id)) {
                $remember_me = true;
                if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
                    $user_id = Auth::id();
                } else {
                    $request->session()->flash('error', __('backend_messages.wrong_cred'));
                    return redirect()->back();
                }
            }
            $response['error'] = 'no';

            if ($request->session()->has('AD_INFO')) {
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
            } else {
                $unique_id = uniqid();
            }

            if ($data['button_clicked'] == "savestandard") {
                $request->session()->put('AD_INFO', array('unique_id' => $unique_id, 'betaVersion' => "true", 'step1_data' => $data));
            } else {
                $user_id = Auth::id();
                if (isset($data['email'])) {
                    // $user_id = $this->registerWithAd((object) $data);
                }
                $step1_data = $data;
                $AdTempFiles = new $this->AdTempFiles;
                $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
                $Ads = new $this->Ads;
                if (!empty($data['ad_id'])) {
                    $ad_id = $data['ad_id'];
                    $action = 'edit';
                } else {
                    $action = "add";
                }
                $ad = (object)array(
                    "title" => $data['title'],
                    "description" => $data['description'],
                    "adress" => (!empty($data['actual_address'])) ? $data['actual_address'] : $data['address']
                );
                if (isset($ad_id)) {
                    $Ads = $Ads::find($ad_id);
                }

                if ($action != "edit" && Auth::check()) {
                    $Ads->user_id = $user_id;
                    $Ads->ad_treaty = 0;
                    $Ads->updated_at = date("Y-m-d H:i:s");
                }
                $Ads->title = $step1_data['title'];
                $Ads->description = $step1_data['description'];
                if (!empty($step1_data['actual_address'])) {
                    $Ads->address = $step1_data['actual_address'];
                } else {
                    $Ads->address = $step1_data['address'];
                }
                $Ads->latitude = $step1_data['latitude'];
                $Ads->longitude = $step1_data['longitude'];
                $Ads->scenario_id = $step1_data['scenario_id'];
                $Ads->status = '1';
                $Ads->url_slug = str_slug($step1_data['title'], '-');
                if (isset($step1_data['ad_url'])) {
                    $Ads->custom_url = $step1_data['ad_url'];
                }
                $Ads->admin_approve = '0';
                $Ads->save();
                countAds("insert");
                $ad_id = $Ads->id;
                if (isset($step1_data['proximity'])) {
                    $this->saveProximity($ad_id, $step1_data['proximity']);
                }
                if (isset($step1_data['metro_lines'])) {
                    $this->saveMetroLines($ad_id, $step1_data['metro_lines']);
                }
                /*** Enregistrement des images ***/
                $this->saveAdMediaFiles($ad_id, $unique_id);
                $this->deleteAncienFiles($ad_id);
                $request->session()->put('AD_INFO', array('unique_id' => $unique_id, "ad_id" => $ad_id, "action" => $action));
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];
        if ($data['button_clicked'] == "savestandard") {
            if (Auth::check()) {
                $response['user_type'] = "logged_in";
            } else {
                $response['user_type'] = "guest";
            }
            $response['redirect_url'] = route('save.all');
            if (isset($request->session()->get('AD_INFO')['step1_data']['email'])) {
                $response['redirect_url'] = route('save.all');
            }
        }

        return response()->json($response);
    }

    public function saveStep2(Request $request)
    {
        $data = $request->all();
        $response = array();
        $validator = Validator::make($data, [
            'rent_per_month' => 'required|numeric|min:1',
            'property_type' => 'required',
            'prop_square_meters' => 'required|numeric|min:1',
            'date_of_availablity' => 'required|date_format:d/m/Y'
        ]);

        $validator->sometimes(
            'utility_cost',
            'required|numeric|min:1',
            function ($input) {
                return $input->add_utility_cost == 1;
            }
        );

        $validator->sometimes(
            'deposit_price',
            'numeric|min:1',
            function ($input) {
                return $input->deposit_price != '';
            }
        );

        $validator->sometimes(
            'broker_fees',
            'numeric|min:1',
            function ($input) {
                return $input->broker_fees != '';
            }
        );

        if ($validator->passes()) {
            if (!empty($data['rent_per_month']) && !empty($data['deposit_price']) && !empty($data['broker_fees'])) {
                $data['rent_per_month'] = save_conversion_devise($data['rent_per_month']);
                $data['deposit_price'] = save_conversion_devise($data['deposit_price']);
                $data['broker_fees'] = save_conversion_devise($data['broker_fees']);
            }
            $step2_data = $data;
            $response['error'] = 'no';

            if ($request->session()->has('AD_INFO')) {
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
                $ad_id = $ad_info_session['ad_id'];
                $Ads = Ads::where('id', $ad_id)->first();

                # nrh
                $Ads->min_rent = $step2_data['rent_per_month'];

                if (isset($step2_data['date_of_availablity'])) {
                    $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step2_data['date_of_availablity'])));
                }
                if (isset($step2_data['sous_loc_type'])) {
                    $Ads->sous_type_loc = $step2_data['sous_loc_type'];
                }
                $Ads->complete = 1;
                $Ads->admin_approve = '1';
                $Ads->save();
                countAds("insert");
                $this->saveAllDocuments($ad_id, $step2_data);

                $AdDetails = AdDetails::where('ad_id', $ad_id)->first();

                if (is_null($AdDetails)) {
                    $AdDetails = new $this->AdDetails;
                }

                $AdDetails->ad_id = $ad_id;
                $AdDetails->property_type_id = $step2_data['property_type'];
                $AdDetails->min_surface_area = $step2_data['prop_square_meters'];


                $AdDetails->bedrooms = $step2_data['no_of_bedrooms'];
                $AdDetails->bathrooms = $step2_data['no_of_bathrooms'];

                if (!empty($step2_data['no_of_part_bathrooms']) || (isset($step2_data['no_of_part_bathrooms']) && $step2_data['no_of_part_bathrooms'] == 0)) {
                    $AdDetails->partial_bathrooms = $step2_data['no_of_part_bathrooms'];
                }
                if (!empty($step2_data['separate_kitchen']) || (isset($step2_data['separate_kitchen']) && $step2_data['separate_kitchen'] == 0)) {
                    $AdDetails->kitchen_separated = $step2_data['separate_kitchen'];
                }
                if (!empty($step2_data['min_stay_months']) || (isset($step2_data['min_stay_months']) && $step2_data['min_stay_months'] == 0)) {
                    $AdDetails->minimum_stay = $step2_data['min_stay_months'];
                }
                if (!empty($step2_data['deposit_price'])) {
                    $AdDetails->deposit_price = $step2_data['deposit_price'];
                }
                if (!empty($step2_data['add_utility_cost'])) {
                    $AdDetails->add_utility_costs = $step2_data['add_utility_cost'];
                }
                if (!empty($step2_data['utility_cost'])) {
                    $AdDetails->utility_cost = $step2_data['utility_cost'];
                }

                $AdDetails->furnished = $step2_data['furnished'];

                /*$AdDetails->furnished = $step3_data['furnished'];*/
                if (!empty($step2_data['broker_fees'])) {
                    $AdDetails->broker_fees = $step2_data['broker_fees'];
                }

                if (isset($step2_data['guarantee_asked'])) {
                    $this->saveAdGuarantee($ad_id, $step2_data['guarantee_asked']);
                }

                if (isset($step2_data['allowed_pets'])) {
                    $this->saveAllowedPets($ad_id, $step2_data['allowed_pets']);
                }

                if (isset($step2_data['property_rules'])) {
                    $this->savePropertyRules($ad_id, $step2_data['property_rules']);
                }

                if (isset($step2_data['prop_feature'])) {
                    $this->savePropFeatures($ad_id, $step2_data['prop_feature']);
                }

                if (isset($step2_data['building_amenities'])) {
                    $this->saveBuildingAmenities($ad_id, $step2_data['building_amenities']);
                }

                $AdDetails->save();
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];

        return response()->json($response);
    }

    private function saveDetailVisit($ad_id, $visit_data)
    {
        DB::table('ad_visiting_details')->where("ad_id", $ad_id)->delete();
        foreach ($visit_data['date_of_visit'] as $key => $visit_detail) {
            if (!empty($visit_detail)) {

                $AdVisitingDetails = new $this->AdVisitingDetails;

                $AdVisitingDetails->ad_id = $ad_id;
                $AdVisitingDetails->visiting_date = date("Y-m-d", strtotime(convertDateWithTiret($visit_detail)));
                $AdVisitingDetails->start_time = date('H:i:s', strtotime($visit_data['start_time'][$key]));
                if ($visit_data['end_time'][$key] != '') {
                    $AdVisitingDetails->end_time = date('H:i:s', strtotime($visit_data['end_time'][$key]));
                }
                if ($visit_data['note'][$key] != '') {
                    $AdVisitingDetails->notes = $visit_data['note'][$key];
                }

                $AdVisitingDetails->save();
            }
        }
    }

    public function saveStep3(\App\Http\Requests\Step3Request $request) // Request custom
    {
        $data = $request->all();
        $response = array();


        $response['redirect_url'] = route('user.dashboard');
        if ($request->session()->has('AD_INFO')) {

            $ad_info_session = $request->session()->get('AD_INFO');
            $unique_id = $ad_info_session['unique_id'];
            $ad_id = $ad_info_session['ad_id'];
            $this->saveDetailVisit($ad_id, $data);
            $action = $ad_info_session['action'];
            if ($action == "add") {
                $response['redirect_url'] = route('confirm-ad-creation');
            }
        }


        $response['button_clicked'] = $data['button_clicked'];
        $response['user_type'] = $data['user'];
        /*if(isset($request->session()->get('AD_INFO')['step1_data']['email'])) {
            $response['redirect_url'] = route('registerWithAd');
        }*/

        return response()->json($response);
    }


    private function sendMailNewAd($ad)
    {


        $subject = __('mail.ad_creation');

        try {

            sendMailAdmin("emails.admin.newAd", ["subject" => $subject, "ad" => $ad, "currentDate" => date("Y/m/d H:i:sa")]);
        } catch (Exception $ex) {
        }


        return true;
    }

    private function sendMailCompleteAd($ad)
    {
        $email = Auth::user()->email;

        $subject = __('mail.improve_ad');

        try {

            sendMail($email, 'emails.users.completead', [
                'subject' => $subject,
                'userAd' => $ad,
                'lang' => getLangUser(Auth::id())
            ]);
        } catch (Exception $ex) {
        }
        return true;
    }


    private function sendMailCodeGroupe()
    {
        $adCreated = DB::table("ads")->where("user_id", Auth::id())->first();
        $sentCodeGroup = DB::table("code_groupe")->where("user_id", Auth::id())->first();

        if (is_null($sentCodeGroup) && is_null($adCreated)) {


            $subject = __('mail.code_groupe');

            $code = DB::table("config")->where("varname", "code_groupe")->first()->value;
            DB::table("code_groupe")->insert(
                ['user_id' => Auth::id(), "code" => $code]
            );
            try {

                sendMail(Auth::user()->email, "emails.users.codeGroupe", ["subject" => $subject, "code" => $code]);
            } catch (Exception $ex) {
            }
        }
        return true;
    }

    public function saveAllBetaVersion(Request $request)
    {
        if ($request->session()->has('AD_INFO')) {
            $ad_info_session = $request->session()->get('AD_INFO');
            if (!empty($ad_info_session["step1_data"] ["rent_per_month"])) {
                $ad_info_session["step1_data"] ["rent_per_month"] = save_conversion_devise($ad_info_session["step1_data"] ["rent_per_month"]);
            }
            $ad = (object)array(
                "title" => $ad_info_session['step1_data']['title'],
                "description" => $ad_info_session['step1_data']['description'],
                "adress" => (!empty($step1_data['actual_address'])) ? $ad_info_session['step1_data']['actual_address'] : $ad_info_session['step1_data']['address']
            );
            $step1_data = $ad_info_session['step1_data'];
            $unique_id = $ad_info_session['unique_id'];
            if (!empty(User::find(Auth::id())->email)) {
                if (empty($step1_data['ad_id'])) {
                    if (Auth::check()) {
                        $this->sendMailNewAd($ad);
                        //$this->sendMailCodeGroupe();
                    }
                }
            }

            $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
            $Ads = new $this->Ads;
            $AdDetails = new $this->AdDetails;
            $user_id = Auth::id();
            $Ads->user_id = $user_id;
            $Ads->title = $step1_data['title'];
            $Ads->description = $step1_data['description'];
            $Ads->address = $step1_data['address'];
            $Ads->latitude = $step1_data['latitude'];
            $Ads->longitude = $step1_data['longitude'];
            $Ads->available_date = date("Y-m-d");
            $Ads->scenario_id = $step1_data['scenario_id'];
            if (isset($step1_data['alert_contact'])) {
                $Ads->alert_contact = $step1_data['alert_contact'];
            }
            # nrh
            $Ads->min_rent = $step1_data['rent_per_month'];
            $Ads->budget = $step1_data['rent_per_month'];

            $Ads->status = '1';
            $Ads->url_slug = str_slug($step1_data['title'], '-');
            if (isset($step1_data['sous_loc_type'])) {
                $Ads->sous_type_loc = $step1_data['sous_loc_type'];
            }

            if (isset($step1_data['date_of_availablity'])) {
                $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step1_data['date_of_availablity'])));
            }

            if (isset($step1_data['ad_url'])) {
                $Ads->custom_url = $step1_data['ad_url'];
            }
            $Ads->complete = 1;

            $Ads->save();
            countAds("insert");
            $ad_id = $Ads->id;
            $AdDetails->ad_id = $ad_id;


            $AdDetails->budget = $step1_data['rent_per_month'];

            $AdDetails->property_type_id = $step1_data['property_type'];
            if ($step1_data['scenario_id'] == 3 || $step1_data['scenario_id'] == 4 || $step1_data['scenario_id'] == 5) {

                if (!isset($step1_data['furnished']) || count($step1_data['furnished']) > 1 || count($step1_data['furnished']) == 0) {
                    $furnished = '2';
                } else {
                    $furnished = $step1_data['furnished'][0];
                }
                $AdDetails->furnished = $furnished;
            } else {
                $AdDetails->furnished = $step1_data['furnished'];
            }

            if (isset($step1_data['metro_lines'])) {
                DB::table('nearby_facilities')->where("ad_id", $ad_id)->delete();
                $metro_lines = $step1_data['metro_lines'];
                foreach ($metro_lines as $key => $metro_line) {
                    DB::table('nearby_facilities')->insert([
                        "ad_id" => $ad_id,
                        "name" => $metro_line,
                        "nearbyfacility_type" => "subway_station"
                    ]);
                }
            }

            if (isset($step1_data['proximity'])) {
                DB::table('ad_proximity')->where("ad_id", $ad_id)->delete();
                $proximities = $step1_data['proximity'];

                foreach ($proximities as $key => $proximity) {
                    DB::table('ad_proximity')->insert([
                        "ad_id" => $ad_id,
                        "point_proximity_id" => $proximity
                    ]);
                }
            }

            if (isset($step1_data['prop_square_meters'])) {
                $AdDetails->min_surface_area = $step1_data['prop_square_meters'];
            }

            if (isset($step1_data['guarantee_type'])) {
                $guarantees = $step1_data['guarantee_type'];
                foreach ($guarantees as $key => $guar) {
                    if ($guar != 0) {
                        DB::table("ad_to_guarantees")->insert(
                            ["ad_id" => $ad_id, "guarantees_id" => $guar, "created_at" => date("Y-m-d"), "updated_at" => date("Y-m-d H:i:s")]
                        );
                    }
                }
            }

            if ($step1_data["scenario_id"] == 1 || $step1_data["scenario_id"] == 2) {

                $this->saveAdMediaFiles($ad_id, $unique_id);
                $this->deleteAncienFiles($ad_id);
            }

            $AdDetails->save();
            if (!empty(User::find(Auth::id())->email)) {
                if (calculAdPercent($ad_id) < 100) {
                    $this->sendMailCompleteAd(array('url_slug' => str_slug($step1_data['title']) . '-' . $ad_id));
                }
            }
            $this->signalAd($ad_id);
            countValidateForm("form5");
            return str_slug($step1_data['title']) . '-' . $ad_id;
        }
    }

    private function signalAd($ad_id)
    {
        if (\Session::has("signaledAd")) {
            if (\Session::has("commentSignal")) {
                $comment = \Session::get('commentSignal');
            } else {
                $comment = "Annonce avec coordonnées dans la description ou le titre";
            }
            DB::table('signal_ad')->where('ad_id', $ad_id)->delete();
            DB::table('signal_ad')->insert([
                "ad_id" => $ad_id,
                "user_id" => Auth::id(),
                "commentaire" => $comment,
                "done" => '0'
            ]);
            \Session::forget("commentSignal");
            \Session::forget("signaledAd");
        } else {
            DB::table('signal_ad')->where('ad_id', $ad_id)->delete();
        }
    }

    private function saveAdMediaFiles($ad_id, $unique_id)
    {
        $AdTempFiles = new $this->AdTempFiles;
        $temp_files = $AdTempFiles->where('unique_id', $unique_id)->get();
        foreach ($temp_files as $temp_file) {
            $AdFiles = new $this->AdFiles;
            $AdFiles->ad_id = $ad_id;
            $AdFiles->filename = $temp_file->filename;
            $AdFiles->user_filename = $temp_file->user_filename;
            $AdFiles->media_type = $temp_file->media_type;
            $AdFiles->ordre = $temp_file->ordre;
            $AdFiles->save();
        }
    }

    private function saveAdGuaranteeFiles($ad_id, $unique_id, $step4_data)
    {
        $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
        $temp_guarantees = $TemporaryUploadedGuarantees->where('unique_id', $unique_id)->get();
        $temp_guarantees_array = array();
        foreach ($temp_guarantees as $temp_guarantee) {
            if (!empty($step4_data['guarantee_type_' . $temp_guarantee->index_id])) {
                $UploadedGuarantees = new $this->UploadedGuarantees;

                $UploadedGuarantees->ad_id = $ad_id;
                $UploadedGuarantees->guarantee_id = $step4_data['guarantee_type_' . $temp_guarantee->index_id];
                $UploadedGuarantees->filename = $temp_guarantee->filename;
                $UploadedGuarantees->user_filename = $temp_guarantee->user_filename;
                $UploadedGuarantees->save();
            }
        }
    }


    private function saveStep1_data($step1_data, $index, $db, $ad_id)
    {
        if (isset($step1_data[$index])) {
            DB::table($db)->where("ad_id", $ad_id)->delete();
            $data = $step1_data[$index];
            foreach ($data as $key => $value) {

                if ($db != "ad_proximity") {
                    DB::table('nearby_facilities')->insert([
                        "ad_id" => $ad_id,
                        "name" => $value,
                        "nearbyfacility_type" => "subway_station"
                    ]);
                } else {
                    DB::table('ad_proximity')->insert([
                        "ad_id" => $ad_id,
                        "point_proximity_id" => $value
                    ]);
                }
            }
        }
    }


    public function saveAll(Request $request)
    {
        if ($request->session()->has('AD_INFO')) {
            $ad_info_session = $request->session()->get('AD_INFO');
            if (!empty($ad_info_session["step1_data"] ["rent_per_month"])) {
                $ad_info_session["step1_data"] ["rent_per_month"] = save_conversion_devise($ad_info_session["step1_data"] ["rent_per_month"]);
            }
            if (isset($ad_info_session['betaVersion'])) {
                $title_ad = $this->saveAllBetaVersion($request);
                $request->session()->forget('AD_INFO');
                $request->session()->flash('status', __('backend_messages.ad_success_posted'));
                if (!is_null($title_ad)) {
                    return redirect(route('confirm-ad-creation'));
                } else {
                    return redirect()->route('home');
                }
            } else {
                if (!empty($ad_info_session['unique_id']) && !empty($ad_info_session['step1_data']) && !empty($ad_info_session['step2_data'])) {


                    $ad = (object)array(
                        "title" => $ad_info_session['step1_data']['title'],
                        "description" => $ad_info_session['step1_data']['description'],
                        "adress" => (!empty($step1_data['actual_address'])) ? $ad_info_session['step1_data']['actual_address'] : $ad_info_session['step1_data']['address']
                    );
                    if (!empty(User::find(Auth::id())->email)) {
                        if (empty($ad_info_session['step1_data']['ad_id'])) {
                            if (Auth::check()) {
                                $this->sendMailNewAd($ad);
                                //$this->sendMailCodeGroupe();
                            }
                        }
                    }


                    if ($ad_info_session['step1_data']['scenario_id'] == '2' || $ad_info_session['step1_data']['scenario_id'] == '4') {

                        if (!empty($ad_info_session['step3_data']) && !empty($ad_info_session['step4_data'])) {
                            if (Auth::check()) {


                                $user_id = Auth::id();
                                $unique_id = $ad_info_session['unique_id'];
                                $step1_data = $ad_info_session['step1_data'];
                                $step2_data = $ad_info_session['step2_data'];
                                $step3_data = $ad_info_session['step3_data'];
                                $step4_data = $ad_info_session['step4_data'];


                                if (!empty($step1_data['ad_id'])) {
                                    $ad_id = $step1_data['ad_id'];
                                    $action = 'edit';
                                } else {
                                    $action = 'add';
                                }

                                $AdTempFiles = new $this->AdTempFiles;
                                $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
                                $Ads = new $this->Ads;
                                $AdDetails = new $this->AdDetails;

                                if ($ad_info_session['step1_data']['scenario_id'] == '2') {
                                    $temp_files = $AdTempFiles->where('unique_id', $unique_id)->get();
                                    $temp_files_array = array();
                                    foreach ($temp_files as $temp_file) {
                                        $temp_files_array[] = array('filename' => $temp_file->filename, 'user_filename' => $temp_file->user_filename, 'media_type' => $temp_file->media_type);
                                    }
                                } else if ($ad_info_session['step1_data']['scenario_id'] == '4') {
                                    $temp_guarantees = $TemporaryUploadedGuarantees->where('unique_id', $unique_id)->get();
                                    $temp_guarantees_array = array();
                                    foreach ($temp_guarantees as $temp_guarantee) {
                                        $temp_guarantees_array[] = array('index_id' => $temp_guarantee->index_id, 'filename' => $temp_guarantee->filename, 'user_filename' => $temp_guarantee->user_filename);
                                    }
                                }

                                if ($action == 'edit') {
                                    $Ads = $Ads::find($ad_id);
                                }

                                if ($action != "edit") {
                                    $Ads->user_id = $user_id;
                                    $Ads->ad_treaty = 0;
                                    $Ads->updated_at = date("Y-m-d H:i:s");
                                }
                                $Ads->title = $step1_data['title'];
                                $Ads->description = $step1_data['description'];
                                if (!empty($step1_data['actual_address'])) {
                                    $Ads->address = $step1_data['actual_address'];
                                } else {
                                    $Ads->address = $step1_data['address'];
                                }
                                $Ads->latitude = $step1_data['latitude'];
                                $Ads->longitude = $step1_data['longitude'];

                                if (!empty($step3_data['rent_per_month'])) {

                                    $Ads->min_rent = $step3_data['rent_per_month'];
                                }

                                if (!empty($step3_data['rent_per_month_max'])) {
                                    $Ads->max_rent = $step3_data['rent_per_month_max'];
                                }
                                $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step2_data['date_of_availablity'])));
                                $Ads->scenario_id = $step1_data['scenario_id'];
                                $Ads->status = '1';
                                if (isset($step1_data['alert_contact'])) {
                                    $Ads->alert_contact = $step1_data['alert_contact'];
                                }

                                $Ads->url_slug = str_slug($step1_data['title'], '-');

                                if (isset($step1_data['sous_loc_type'])) {
                                    $Ads->sous_type_loc = $step1_data['sous_loc_type'];
                                }
                                if (isset($step1_data['ad_url'])) {
                                    $Ads->custom_url = $step1_data['ad_url'];
                                }
                                $Ads->save();
                                countAds("insert");

                                if (!empty($step1_data['ad_id'])) {
                                    $ad_id = $step1_data['ad_id'];
                                } else {
                                    $ad_id = $Ads->id;
                                }

                                if (isset($step1_data['metro_lines'])) {
                                    DB::table('nearby_facilities')->where("ad_id", $ad_id)->delete();
                                    $metro_lines = $step1_data['metro_lines'];
                                    foreach ($metro_lines as $key => $metro_line) {
                                        DB::table('nearby_facilities')->insert([
                                            "ad_id" => $ad_id,
                                            "name" => $metro_line,
                                            "nearbyfacility_type" => "subway_station"
                                        ]);
                                    }
                                }

                                if (isset($step1_data['proximity'])) {
                                    DB::table('ad_proximity')->where("ad_id", $ad_id)->delete();
                                    $proximities = $step1_data['proximity'];
                                    foreach ($proximities as $key => $proximity) {
                                        DB::table('ad_proximity')->insert([
                                            "ad_id" => $ad_id,
                                            "point_proximity_id" => $proximity
                                        ]);
                                    }
                                }

                                $this->saveAllDocuments($ad_id, $step2_data);

                                if ($action == 'edit') {
                                    $AdDetails = $AdDetails::where('ad_id', $ad_id)->first();
                                }

                                $AdDetails->ad_id = $ad_id;
                                $AdDetails->property_type_id = $step3_data['property_type'];
                                $AdDetails->min_surface_area = $step2_data['prop_square_meters'];
                                if (!empty($step2_data['prop_square_meters_max'])) {
                                    $AdDetails->max_surface_area = $step2_data['prop_square_meters_max'];
                                }
                                $AdDetails->preferred_gender = $step2_data['preferred_gender'];
                                $AdDetails->preferred_occupation = $step2_data['preferred_occupation'];
                                $AdDetails->age_range_from = $step2_data['preferred_age_range_from'];
                                $AdDetails->age_range_to = $step2_data['preferred_age_range_to'];
                                $AdDetails->bedrooms = $step3_data['no_of_bedrooms'];
                                $AdDetails->bathrooms = $step3_data['no_of_bathrooms'];
                                if (!empty($step3_data['no_of_part_bathrooms']) || (isset($step3_data['no_of_part_bathrooms']) && $step3_data['no_of_part_bathrooms'] == 0)) {
                                    $AdDetails->partial_bathrooms = $step3_data['no_of_part_bathrooms'];
                                }
                                if (!empty($step3_data['separate_kitchen']) || (isset($step3_data['separate_kitchen']) && $step3_data['separate_kitchen'] == 0)) {
                                    $AdDetails->kitchen_separated = $step3_data['separate_kitchen'];
                                }
                                if (!empty($step2_data['min_stay_months']) || (isset($step2_data['min_stay_months']) && $step2_data['min_stay_months'] == 0)) {
                                    $AdDetails->minimum_stay = $step2_data['min_stay_months'];
                                }
                                if (!empty($step3_data['deposit_price'])) {
                                    $AdDetails->deposit_price = $step3_data['deposit_price'];
                                }
                                if (!empty($step3_data['add_utility_cost'])) {
                                    $AdDetails->add_utility_costs = $step3_data['add_utility_cost'];
                                }
                                if (!empty($step3_data['utility_cost'])) {
                                    $AdDetails->utility_cost = $step3_data['utility_cost'];
                                }
                                $AdDetails->no_of_roommates = $step3_data['no_of_roommates'];
                                if ($step1_data['scenario_id'] == 4) {
                                    if (!isset($step3_data['furnished']) || count($step3_data['furnished']) > 1 || count($step3_data['furnished']) == 0) {
                                        $furnished = '2';
                                    } else {
                                        $furnished = $step3_data['furnished'][0];
                                    }
                                    $AdDetails->furnished = $furnished;
                                } else {
                                    $AdDetails->furnished = $step3_data['furnished'];
                                }
                                /*$AdDetails->furnished = $step3_data['furnished'];*/
                                if (!empty($step3_data['broker_fees'])) {
                                    $AdDetails->broker_fees = $step3_data['broker_fees'];
                                }

                                $AdDetails->save();

                                if ($ad_info_session['step1_data']['scenario_id'] == '2') {
                                    if (count($temp_files_array) > 0) {
                                        foreach ($temp_files_array as $files) {

                                            $AdFiles = new $this->AdFiles;

                                            $AdFiles->ad_id = $ad_id;
                                            $AdFiles->filename = $files['filename'];
                                            $AdFiles->user_filename = $files['user_filename'];
                                            $AdFiles->media_type = $files['media_type'];

                                            $AdFiles->save();
                                        }
                                    }
                                } else if ($ad_info_session['step1_data']['scenario_id'] == '4') {
                                    if (count($temp_guarantees_array) > 0) {
                                        foreach ($temp_guarantees_array as $guarantees) {
                                            if (!empty($step4_data['guarantee_type_' . $guarantees['index_id']])) {
                                                $UploadedGuarantees = new $this->UploadedGuarantees;

                                                $UploadedGuarantees->ad_id = $ad_id;
                                                $UploadedGuarantees->guarantee_id = $step4_data['guarantee_type_' . $guarantees['index_id']];
                                                $UploadedGuarantees->filename = $guarantees['filename'];
                                                $UploadedGuarantees->user_filename = $guarantees['user_filename'];
                                                $UploadedGuarantees->save();
                                            }
                                        }
                                    }
                                }

                                if ($ad_info_session['step1_data']['scenario_id'] == '4') {

                                    $AdRentalApplications = new $this->AdRentalApplications;

                                    if ($action == 'edit') {
                                        $AdRentalApplications = $AdRentalApplications::where('ad_id', $ad_id)->first();
                                    }
                                    if (is_null($AdRentalApplications)) {
                                        DB::table('ad_rental_applications')->insert(
                                            ['ad_id' => $ad_id, 'actual_renting_price' => 0]
                                        );
                                        $AdRentalApplications = AdRentalApplications::where('ad_id', $ad_id)->first();
                                    }

                                    $AdRentalApplications->ad_id = $ad_id;
                                    $AdRentalApplications->actual_renting_price = $step4_data['actual_renting_price'];
                                    if (!empty($step4_data['occupation']) || (isset($step4_data['occupation']) && $step4_data['occupation'] == 0)) {
                                        $AdRentalApplications->occupation = $step4_data['occupation'];
                                    }
                                    $AdRentalApplications->save();
                                }

                                if ($action == 'edit') {
                                    $AdToAllowedPets = new $this->AdToAllowedPets;
                                    $AdToAllowedPets->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step3_data['allowed_pets'])) {
                                    foreach ($step3_data['allowed_pets'] as $all_pet) {

                                        $AdToAllowedPets = new $this->AdToAllowedPets;

                                        $AdToAllowedPets->ad_id = $ad_id;
                                        $AdToAllowedPets->allowed_pets_id = $all_pet;

                                        $AdToAllowedPets->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdToAmenities = new $this->AdToAmenities;
                                    $AdToAmenities->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step3_data['building_amenities'])) {
                                    foreach ($step3_data['building_amenities'] as $build_amen) {

                                        $AdToAmenities = new $this->AdToAmenities;

                                        $AdToAmenities->ad_id = $ad_id;
                                        $AdToAmenities->amenities_id = $build_amen;

                                        $AdToAmenities->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdToGuarantees = new $this->AdToGuarantees;
                                    $AdToGuarantees->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step3_data['guarantee_asked'])) {
                                    foreach ($step3_data['guarantee_asked'] as $guar_ask) {

                                        $AdToGuarantees = new $this->AdToGuarantees;

                                        $AdToGuarantees->ad_id = $ad_id;
                                        $AdToGuarantees->guarantees_id = $guar_ask;

                                        $AdToGuarantees->save();
                                    }
                                }
                                if ($action == 'edit') {
                                    $AdToPropertyFeatures = new $this->AdToPropertyFeatures;
                                    $AdToPropertyFeatures->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step3_data['prop_feature'])) {
                                    foreach ($step3_data['prop_feature'] as $prop_feat) {

                                        $AdToPropertyFeatures = new $this->AdToPropertyFeatures;

                                        $AdToPropertyFeatures->ad_id = $ad_id;
                                        $AdToPropertyFeatures->property_features_id =
                                            $prop_feat;

                                        $AdToPropertyFeatures->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdToPropertyRules = new $this->AdToPropertyRules;
                                    $AdToPropertyRules->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step3_data['property_rules'])) {
                                    foreach ($step3_data['property_rules'] as $prop_rule) {

                                        $AdToPropertyRules = new $this->AdToPropertyRules;

                                        $AdToPropertyRules->ad_id = $ad_id;
                                        $AdToPropertyRules->property_rules_id = $prop_rule;

                                        $AdToPropertyRules->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdVisitingDetails = new $this->AdVisitingDetails;
                                    $AdVisitingDetails->where('ad_id', $ad_id)->delete();
                                }

                                if ($ad_info_session['step1_data']['scenario_id'] == '2') {
                                    foreach ($step4_data['date_of_visit'] as $key => $visit_detail) {
                                        if (!empty($visit_detail)) {

                                            $AdVisitingDetails = new $this->AdVisitingDetails;

                                            $AdVisitingDetails->ad_id = $ad_id;
                                            $AdVisitingDetails->visiting_date = date("Y-m-d", strtotime(convertDateWithTiret($visit_detail)));
                                            $AdVisitingDetails->start_time = date('H:i:s', strtotime($step4_data['start_time'][$key]));
                                            if ($step4_data['end_time'][$key] != '') {
                                                $AdVisitingDetails->end_time = date('H:i:s', strtotime($step4_data['end_time'][$key]));
                                            }
                                            if ($step4_data['note'][$key] != '') {
                                                $AdVisitingDetails->notes = $step4_data['note'][$key];
                                            }

                                            $AdVisitingDetails->save();
                                        }
                                    }
                                }

                                if ($action == 'edit') {
                                    $NearbyFacilities = new $this->NearbyFacilities;
                                    $NearbyFacilities->where('ad_id', $ad_id)->delete();
                                }

                                if (array_key_exists('near_by_facilities', $step1_data) && !empty($step1_data['near_by_facilities'])) {

                                    foreach ($step1_data['near_by_facilities'] as $near_by_fac) {
                                        $explode_near_by = explode("#", $near_by_fac);

                                        $NearbyFacilities = new $this->NearbyFacilities;

                                        $NearbyFacilities->ad_id = $ad_id;
                                        $NearbyFacilities->latitude = $explode_near_by[0];
                                        $NearbyFacilities->longitude = $explode_near_by[1];
                                        $NearbyFacilities->name = $explode_near_by[2];
                                        $NearbyFacilities->nearbyfacility_type = $explode_near_by[3];

                                        $NearbyFacilities->save();
                                    }
                                }

                                $request->session()->forget('AD_INFO');
                                if ($action == 'edit') {

                                    $request->session()->flash('status', __('backend_messages.ad_success_editing'));
                                } else {
                                    $request->session()->flash('status', __('backend_messages.ad_success_posted'));
                                }

                                $this->signalAd($ad_id);

                                //send mail complete ad
                                if (!empty(User::find(Auth::id())->email)) {
                                    if (calculAdPercent($ad_id) < 100) {
                                        $this->sendMailCompleteAd(array('url_slug' => str_slug($step1_data['title']) . '-' . $ad_id));
                                    }
                                }
                                if (!empty($step1_data['redirected_ad_id'])) {
                                    $redirected_ad_info = Ads::has('user')->where('status', '1')->where('admin_approve', '1')->where('id', $step1_data['redirected_ad_id'])->first();
                                    if (!empty($redirected_ad_info)) {
                                        return redirect(adUrl($step1_data['redirected_ad_id'], $ad_id));
                                    } else {
                                        if ($action == 'edit') {
                                            return redirect()->route('user.dashboard');
                                        } else {
                                            return redirect(route('confirm-ad-creation'));
                                        }
                                    }
                                } else {
                                    if ($action == 'edit') {
                                        return redirect()->route('user.dashboard');
                                    } else {
                                        return redirect(route('confirm-ad-creation'));
                                    }
                                }
                            } else {
                                if ($request->session()->has('AD_INFO')) {
                                    $request->session()->forget('AD_INFO');
                                }
                                $request->session()->flash('status', __('backend_messages.loged_out_inactivity'));
                                return redirect()->route('home');
                            }
                        } else {

                            if ($request->session()->has('AD_INFO')) {
                                $request->session()->forget('AD_INFO');
                            }
                            $request->session()->flash('status', __('backend_messages.session_not_valid'));
                            return redirect()->route('home');
                        }
                    } else if ($ad_info_session['step1_data']['scenario_id'] == '1' || $ad_info_session['step1_data']['scenario_id'] == '3') {

                        if (!empty($ad_info_session['step3_data'])) {

                            if (Auth::check()) {
                                // The user is logged in...

                                $user_id = Auth::id();
                                $unique_id = $ad_info_session['unique_id'];
                                $step1_data = $ad_info_session['step1_data'];
                                $step2_data = $ad_info_session['step2_data'];
                                $step3_data = $ad_info_session['step3_data'];

                                if (!empty($step1_data['ad_id'])) {
                                    $ad_id = $step1_data['ad_id'];
                                    $action = 'edit';
                                } else {
                                    $action = 'add';
                                }

                                $AdTempFiles = new $this->AdTempFiles;
                                $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
                                $Ads = new $this->Ads;
                                $AdDetails = new $this->AdDetails;

                                //echo "<pre>"; print_r($ad_info_session); echo "</pre>";die;
                                if ($ad_info_session['step1_data']['scenario_id'] == '1') {

                                    $temp_files = $AdTempFiles->where('unique_id', $unique_id)->get();
                                    $temp_files_array = array();
                                    foreach ($temp_files as $temp_file) {
                                        $temp_files_array[] = array('filename' => $temp_file->filename, 'user_filename' => $temp_file->user_filename, 'media_type' => $temp_file->media_type);
                                    }
                                    /*if($request->session()->has("userInfoRegister")) {
                                        $inforRegister = $request->session()->get("userInfoRegister");
                                        $AdDetails->accept_as = $inforRegister['accept_as'];
                                        $request->session()->forget("userInfoRegister");
                                    }*/
                                } else if ($ad_info_session['step1_data']['scenario_id'] == '3') {
                                    $temp_guarantees = $TemporaryUploadedGuarantees->where('unique_id', $unique_id)->get();
                                    $temp_guarantees_array = array();
                                    foreach ($temp_guarantees as $temp_guarantee) {
                                        $temp_guarantees_array[] = array('index_id' => $temp_guarantee->index_id, 'filename' => $temp_guarantee->filename, 'user_filename' => $temp_guarantee->user_filename);
                                    }
                                }

                                if ($action == 'edit') {
                                    $Ads = $Ads::find($ad_id);
                                }

                                if ($action != "edit") {
                                    $Ads->user_id = $user_id;
                                    $Ads->ad_treaty = 0;
                                    $Ads->updated_at = date("Y-m-d H:i:s");
                                }
                                $Ads->title = $step1_data['title'];
                                $Ads->description = $step1_data['description'];
                                if (!empty($step1_data['actual_address'])) {
                                    $Ads->address = $step1_data['actual_address'];
                                } else {
                                    $Ads->address = $step1_data['address'];
                                }
                                $Ads->latitude = $step1_data['latitude'];
                                $Ads->longitude = $step1_data['longitude'];
                                if (!empty($step2_data['rent_per_month'])) {
                                    $Ads->min_rent = $step2_data['rent_per_month'];

                                    # nrh update
                                    # $Ads->budget = $step2_data['rent_per_month'];

                                }

                                if (!empty($step2_data['rent_per_month_max'])) {
                                    $Ads->max_rent = $step2_data['rent_per_month_max'];
                                }
                                $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step2_data['date_of_availablity'])));
                                $Ads->scenario_id = $step1_data['scenario_id'];
                                $Ads->status = '1';

                                $Ads->url_slug = str_slug($step1_data['title'], '-');

                                if (isset($step2_data['sous_loc_type'])) {
                                    $Ads->sous_type_loc = $step2_data['sous_loc_type'];
                                }

                                if (isset($step1_data['ad_url'])) {
                                    $Ads->custom_url = $step1_data['ad_url'];
                                }

                                $Ads->save();
                                countAds("insert");

                                if (!empty($step1_data['ad_id'])) {
                                    $ad_id = $step1_data['ad_id'];
                                } else {
                                    $ad_id = $Ads->id;
                                }

                                if (isset($step1_data['metro_lines'])) {
                                    DB::table('nearby_facilities')->where("ad_id", $ad_id)->delete();
                                    $metro_lines = $step1_data['metro_lines'];
                                    foreach ($metro_lines as $key => $metro_line) {
                                        DB::table('nearby_facilities')->insert([
                                            "ad_id" => $ad_id,
                                            "name" => $metro_line,
                                            "nearbyfacility_type" => "subway_station"
                                        ]);
                                    }
                                }

                                if (isset($step1_data['proximity'])) {
                                    DB::table('ad_proximity')->where("ad_id", $ad_id)->delete();
                                    $proximities = $step1_data['proximity'];
                                    foreach ($proximities as $key => $proximity) {
                                        DB::table('ad_proximity')->insert([
                                            "ad_id" => $ad_id,
                                            "point_proximity_id" => $proximity
                                        ]);
                                    }
                                }

                                $this->saveAllDocuments($ad_id, $step2_data);

                                if ($action == 'edit') {
                                    $AdDetails = $AdDetails::where('ad_id', $ad_id)->first();
                                }

                                $AdDetails->ad_id = $ad_id;
                                $AdDetails->property_type_id = $step2_data['property_type'];
                                $AdDetails->min_surface_area = $step2_data['prop_square_meters'];
                                if (!empty($step2_data['prop_square_meters_max'])) {
                                    $AdDetails->max_surface_area = $step2_data['prop_square_meters_max'];
                                }
                                $AdDetails->bedrooms = $step2_data['no_of_bedrooms'];
                                $AdDetails->bathrooms = $step2_data['no_of_bathrooms'];
                                if (!empty($step2_data['no_of_part_bathrooms']) || (isset($step2_data['no_of_part_bathrooms']) && $step2_data['no_of_part_bathrooms'] == 0)) {
                                    $AdDetails->partial_bathrooms = $step2_data['no_of_part_bathrooms'];
                                }
                                if (!empty($step2_data['leasing_fee']) || (isset($step2_data['leasing_fee']) && $step2_data['leasing_fee'] == 0)) {
                                    $AdDetails->leasing_fees = $step2_data['leasing_fee'];
                                }
                                if (!empty($step2_data['separate_kitchen'])) {
                                    $AdDetails->kitchen_separated = $step2_data['separate_kitchen'];
                                }
                                if (!empty($step2_data['min_stay_months']) || (isset($step2_data['min_stay_months']) && $step2_data['min_stay_months'] == 0)) {
                                    $AdDetails->minimum_stay = $step2_data['min_stay_months'];
                                }
                                if (!empty($step2_data['deposit_price'])) {
                                    $AdDetails->deposit_price = $step2_data['deposit_price'];
                                }
                                if (!empty($step2_data['add_utility_cost'])) {
                                    $AdDetails->add_utility_costs = $step2_data['add_utility_cost'];
                                }
                                if (!empty($step2_data['utility_cost'])) {
                                    $AdDetails->utility_cost = $step2_data['utility_cost'];
                                }
                                if ($step1_data['scenario_id'] == 3) {
                                    if (!isset($step2_data['furnished']) || count($step2_data['furnished']) > 1 || count($step2_data['furnished']) == 0) {
                                        $furnished = '2';
                                    } else {
                                        $furnished = $step2_data['furnished'][0];
                                    }
                                    $AdDetails->furnished = $furnished;
                                } else {
                                    $AdDetails->furnished = $step2_data['furnished'];
                                }

                                if (!empty($step2_data['apl_right']) || (isset($step2_data['apl_right']) && $step2_data['apl_right'] == 0)) {
                                    $AdDetails->apl_rights = $step2_data['apl_right'];
                                }
                                if (!empty($step2_data['advertising_as']) || (isset($step2_data['advertising_as']) && $step2_data['advertising_as'] == 0)) {
                                    $AdDetails->advertising_as = $step2_data['advertising_as'];
                                }
                                if (!empty($step1_data['accept_as']) || (isset($step1_data['accept_as']) && $step1_data['accept_as'] == 0)) {
                                    $AdDetails->accept_as = $step1_data['accept_as'];
                                }
                                if (!empty($step2_data['broker_fees'])) {
                                    $AdDetails->broker_fees = $step2_data['broker_fees'];
                                }

                                $AdDetails->save();

                                if ($ad_info_session['step1_data']['scenario_id'] == '1') {
                                    if (count($temp_files_array) > 0) {
                                        foreach ($temp_files_array as $files) {

                                            $AdFiles = new $this->AdFiles;

                                            $AdFiles->ad_id = $ad_id;
                                            $AdFiles->filename = $files['filename'];
                                            $AdFiles->user_filename = $files['user_filename'];
                                            $AdFiles->media_type = $files['media_type'];

                                            $AdFiles->save();
                                        }
                                    }
                                } else if ($ad_info_session['step1_data']['scenario_id'] == '3') {
                                    if (count($temp_guarantees_array) > 0) {
                                        foreach ($temp_guarantees_array as $guarantees) {
                                            if (!empty($step3_data['guarantee_type_' . $guarantees['index_id']])) {
                                                $UploadedGuarantees = new $this->UploadedGuarantees;

                                                $UploadedGuarantees->ad_id = $ad_id;
                                                $UploadedGuarantees->guarantee_id = $step3_data['guarantee_type_' . $guarantees['index_id']];
                                                $UploadedGuarantees->filename = $guarantees['filename'];
                                                $UploadedGuarantees->user_filename = $guarantees['user_filename'];
                                                $UploadedGuarantees->save();
                                            }
                                        }
                                    }
                                }

                                if ($ad_info_session['step1_data']['scenario_id'] == '3') {

                                    $AdRentalApplications = new $this->AdRentalApplications;
                                    if ($action == 'edit') {
                                        $AdRentalApplications = $AdRentalApplications::where('ad_id', $ad_id)->first();
                                    }
                                    if (is_null($AdRentalApplications)) {
                                        DB::table('ad_rental_applications')->insert(
                                            ['ad_id' => $ad_id, 'actual_renting_price' => 0]
                                        );
                                        $AdRentalApplications = AdRentalApplications::where('ad_id', $ad_id)->first();
                                    }
                                    $AdRentalApplications->ad_id = $ad_id;
                                    $AdRentalApplications->actual_renting_price = $step3_data['actual_renting_price'];
                                    if (!empty($step3_data['occupation']) || (isset($step3_data['occupation']) && $step3_data['occupation'] == 0)) {
                                        $AdRentalApplications->occupation = $step3_data['occupation'];
                                    }
                                    $AdRentalApplications->job_title = $step3_data['job_title'];
                                    if (!empty($step3_data['monthly_salary'])) {
                                        $AdRentalApplications->monthly_salary = $step3_data['monthly_salary'];
                                    }
                                    if (!empty($step3_data['start_date'])) {
                                        $AdRentalApplications->start_date = date("Y-m-d", strtotime(convertDateWithTiret($step3_data['start_date'])));
                                    }
                                    if (!empty($step3_data['company_name'])) {
                                        $AdRentalApplications->company_name = $step3_data['company_name'];
                                    }
                                    $AdRentalApplications->save();
                                }

                                if ($action == 'edit') {
                                    $AdToAllowedPets = new $this->AdToAllowedPets;
                                    $AdToAllowedPets->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step2_data['allowed_pets'])) {
                                    foreach ($step2_data['allowed_pets'] as $all_pet) {

                                        $AdToAllowedPets = new $this->AdToAllowedPets;

                                        $AdToAllowedPets->ad_id = $ad_id;
                                        $AdToAllowedPets->allowed_pets_id = $all_pet;

                                        $AdToAllowedPets->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdToAmenities = new $this->AdToAmenities;
                                    $AdToAmenities->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step2_data['building_amenities'])) {
                                    foreach ($step2_data['building_amenities'] as $build_amen) {

                                        $AdToAmenities = new $this->AdToAmenities;

                                        $AdToAmenities->ad_id = $ad_id;
                                        $AdToAmenities->amenities_id = $build_amen;

                                        $AdToAmenities->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdToGuarantees = new $this->AdToGuarantees;
                                    $AdToGuarantees->where('ad_id', $ad_id)->delete();
                                }

                                if (isset($step3_data['guarantee_type'])) {
                                    DB::table("ad_to_guarantees")->where("ad_id", $ad_id)->delete();
                                    $guarantees = $step3_data['guarantee_type'];
                                    foreach ($guarantees as $key => $guar) {
                                        if ($guar != 0) {
                                            DB::table("ad_to_guarantees")->insert(
                                                ["ad_id" => $ad_id, "guarantees_id" => $guar, "created_at" => date("Y-m-d"), "updated_at" => date("Y-m-d H:i:s")]
                                            );
                                        }
                                    }
                                }

                                if (!empty($step2_data['guarantee_asked'])) {
                                    foreach ($step2_data['guarantee_asked'] as $guar_ask) {

                                        $AdToGuarantees = new $this->AdToGuarantees;

                                        $AdToGuarantees->ad_id = $ad_id;
                                        $AdToGuarantees->guarantees_id = $guar_ask;

                                        $AdToGuarantees->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdToPropertyFeatures = new $this->AdToPropertyFeatures;
                                    $AdToPropertyFeatures->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step2_data['prop_feature'])) {
                                    foreach ($step2_data['prop_feature'] as $prop_feat) {

                                        $AdToPropertyFeatures = new $this->AdToPropertyFeatures;

                                        $AdToPropertyFeatures->ad_id = $ad_id;
                                        $AdToPropertyFeatures->property_features_id = $prop_feat;

                                        $AdToPropertyFeatures->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdToPropertyRules = new $this->AdToPropertyRules;
                                    $AdToPropertyRules->where('ad_id', $ad_id)->delete();
                                }

                                if (!empty($step2_data['property_rules'])) {
                                    foreach ($step2_data['property_rules'] as $prop_rule) {

                                        $AdToPropertyRules = new $this->AdToPropertyRules;

                                        $AdToPropertyRules->ad_id = $ad_id;
                                        $AdToPropertyRules->property_rules_id = $prop_rule;

                                        $AdToPropertyRules->save();
                                    }
                                }

                                if ($action == 'edit') {
                                    $AdVisitingDetails = new $this->AdVisitingDetails;
                                    $AdVisitingDetails->where('ad_id', $ad_id)->delete();
                                }

                                if ($ad_info_session['step1_data']['scenario_id'] == '1') {
                                    foreach ($step3_data['date_of_visit'] as $key => $visit_detail) {
                                        if (!empty($visit_detail)) {

                                            $AdVisitingDetails = new $this->AdVisitingDetails;

                                            $AdVisitingDetails->ad_id = $ad_id;
                                            $AdVisitingDetails->visiting_date = date("Y-m-d", strtotime(convertDateWithTiret($visit_detail)));
                                            $AdVisitingDetails->start_time = date('H:i:s', strtotime($step3_data['start_time'][$key]));
                                            if ($step3_data['end_time'][$key] != '') {
                                                $AdVisitingDetails->end_time = date('H:i:s', strtotime($step3_data['end_time'][$key]));
                                            }
                                            if ($step3_data['note'][$key] != '') {
                                                $AdVisitingDetails->notes = $step3_data['note'][$key];
                                            }

                                            $AdVisitingDetails->save();
                                        }
                                    }
                                }


                                if ($action == 'edit') {
                                    $NearbyFacilities = new $this->NearbyFacilities;
                                    $NearbyFacilities->where('ad_id', $ad_id)->delete();
                                }

                                if (array_key_exists('near_by_facilities', $step1_data) && !empty($step1_data['near_by_facilities'])) {

                                    foreach ($step1_data['near_by_facilities'] as $near_by_fac) {
                                        $explode_near_by = explode("#", $near_by_fac);

                                        $NearbyFacilities = new $this->NearbyFacilities;

                                        $NearbyFacilities->ad_id = $ad_id;
                                        $NearbyFacilities->latitude = $explode_near_by[0];
                                        $NearbyFacilities->longitude = $explode_near_by[1];
                                        $NearbyFacilities->name = $explode_near_by[2];
                                        $NearbyFacilities->nearbyfacility_type = $explode_near_by[3];

                                        $NearbyFacilities->save();
                                    }
                                }
                                $request->session()->forget('AD_INFO');
                                if ($action == 'edit') {
                                    $request->session()->flash('status', __('backend_messages.ad_success_editing'));
                                } else {
                                    $request->session()->flash('status', __('backend_messages.ad_success_posted'));
                                }

                                $this->signalAd($ad_id);


                                if (calculAdPercent($ad_id) < 100) {
                                    $this->sendMailCompleteAd(array('url_slug' => str_slug($step1_data['title']) . '-' . $ad_id));
                                }
                                if (!empty($step1_data['redirected_ad_id'])) {
                                    $redirected_ad_info = Ads::has('user')->where('status', '1')->where('admin_approve', '1')->where('id', $step1_data['redirected_ad_id'])->first();
                                    if (!empty($redirected_ad_info)) {
                                        return redirect(adUrl($step1_data['redirected_ad_id'], $ad_id));
                                    } else {
                                        if ($action == 'edit') {
                                            return redirect()->route('user.dashboard');
                                        } else {
                                            return redirect(route('confirm-ad-creation'));
                                        }
                                    }
                                } else {
                                    if ($action == 'edit') {
                                        return redirect()->route('user.dashboard');
                                    } else {
                                        return redirect(route('confirm-ad-creation'));
                                    }
                                }
                            } else {

                                if ($request->session()->has('AD_INFO')) {
                                    $request->session()->forget('AD_INFO');
                                }
                                $request->session()->flash('status', __('backend_messages.logged_out_inactivity'));
                                return redirect()->route('home');
                            }
                        } else {
                            if ($request->session()->has('AD_INFO')) {
                                $request->session()->forget('AD_INFO');
                            }
                            $request->session()->flash('status', __('backend_messages.session_not_valid'));
                            return redirect()->route('home');
                        }
                    } else if ($ad_info_session['step1_data']['scenario_id'] == '5') {
                        if (Auth::check()) {


                            $user_id = Auth::id();
                            $unique_id = $ad_info_session['unique_id'];
                            $step1_data = $ad_info_session['step1_data'];
                            $step2_data = $ad_info_session['step2_data'];

                            if (!empty($step1_data['ad_id'])) {
                                $ad_id = $step1_data['ad_id'];
                                $action = 'edit';
                            } else {
                                $action = 'add';
                            }

                            $Ads = new $this->Ads;
                            $AdDetails = new $this->AdDetails;

                            if ($action == 'edit') {
                                $Ads = $Ads::find($ad_id);
                            }


                            if ($action != 'edit') {
                                $Ads->user_id = $user_id;
                                $Ads->ad_treaty = 0;
                                $Ads->updated_at = date("Y-m-d H:i:s");
                            }

                            $Ads->title = $step1_data['title'];
                            $Ads->description = $step1_data['description'];
                            if (!empty($step1_data['actual_address'])) {
                                $Ads->address = $step1_data['actual_address'];
                            } else {
                                $Ads->address = $step1_data['address'];
                            }
                            $Ads->latitude = $step1_data['latitude'];
                            $Ads->longitude = $step1_data['longitude'];

                            if (!empty($step2_data['rent_per_month'])) {
                                # nrh
                                $Ads->min_rent = $step2_data['rent_per_month'];
                            }

                            if (!empty($step2_data['rent_per_month_max'])) {
                                $Ads->max_rent = $step2_data['rent_per_month_max'];
                            }
                            $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step2_data['date_of_availablity'])));
                            $Ads->scenario_id = $step1_data['scenario_id'];
                            $Ads->status = '1';
                            /*if($action != 'edit') {
                                $Ads->admin_approve = '0';
                            }*/
                            $Ads->url_slug = str_slug($step1_data['title'], '-');
                            if (isset($step1_data['ad_url'])) {
                                $Ads->custom_url = $step1_data['ad_url'];
                            }
                            $Ads->save();
                            countAds("insert");

                            if (!empty($step1_data['ad_id'])) {
                                $ad_id = $step1_data['ad_id'];
                            } else {
                                $ad_id = $Ads->id;
                            }

                            $this->saveStep1_data($step1_data, "metro_lines", "nearby_facilities", $ad_id);


                            $this->saveStep1_data($step1_data, "proximity", "ad_proximity", $ad_id);


                            $this->saveAllDocuments($ad_id, $step2_data);

                            if ($action == 'edit') {
                                $AdDetails = $AdDetails::where('ad_id', $ad_id)->first();
                            }

                            $AdDetails->ad_id = $ad_id;
                            $AdDetails->property_type_id = $step2_data['property_type'];
                            $AdDetails->min_surface_area = $step2_data['prop_square_meters'];
                            if (!empty($step2_data['prop_square_meters_max'])) {
                                $AdDetails->max_surface_area = $step2_data['prop_square_meters_max'];
                            }
                            $AdDetails->bedrooms = $step2_data['no_of_bedrooms'];
                            $AdDetails->bathrooms = $step2_data['no_of_bathrooms'];

                            if (!empty($step2_data['separate_kitchen'])) {
                                $AdDetails->kitchen_separated = $step2_data['separate_kitchen'];
                            }

                            if (!isset($step2_data['furnished']) || count($step2_data['furnished']) > 1 || count($step2_data['furnished']) == 0) {
                                $furnished = '2';
                            } else {
                                $furnished = $step2_data['furnished'][0];
                            }
                            $AdDetails->furnished = $furnished;
                            /*$AdDetails->furnished = $step2_data['furnished'];
                            */

                            $AdDetails->save();

                            if ($action == 'edit') {
                                $AdToAllowedPets = new $this->AdToAllowedPets;
                                $AdToAllowedPets->where('ad_id', $ad_id)->delete();
                            }

                            if (!empty($step2_data['allowed_pets'])) {
                                foreach ($step2_data['allowed_pets'] as $all_pet) {

                                    $AdToAllowedPets = new $this->AdToAllowedPets;

                                    $AdToAllowedPets->ad_id = $ad_id;
                                    $AdToAllowedPets->allowed_pets_id = $all_pet;

                                    $AdToAllowedPets->save();
                                }
                            }

                            if ($action == 'edit') {
                                $AdToAmenities = new $this->AdToAmenities;
                                $AdToAmenities->where('ad_id', $ad_id)->delete();
                            }

                            if (!empty($step2_data['building_amenities'])) {
                                foreach ($step2_data['building_amenities'] as $build_amen) {

                                    $AdToAmenities = new $this->AdToAmenities;

                                    $AdToAmenities->ad_id = $ad_id;
                                    $AdToAmenities->amenities_id = $build_amen;

                                    $AdToAmenities->save();
                                }
                            }

                            if ($action == 'edit') {
                                $AdToPropertyFeatures = new $this->AdToPropertyFeatures;
                                $AdToPropertyFeatures->where('ad_id', $ad_id)->delete();
                            }

                            if (!empty($step2_data['prop_feature'])) {
                                foreach ($step2_data['prop_feature'] as $prop_feat) {

                                    $AdToPropertyFeatures = new $this->AdToPropertyFeatures;

                                    $AdToPropertyFeatures->ad_id = $ad_id;
                                    $AdToPropertyFeatures->property_features_id = $prop_feat;

                                    $AdToPropertyFeatures->save();
                                }
                            }

                            if ($action == 'edit') {
                                $AdToPropertyRules = new $this->AdToPropertyRules;
                                $AdToPropertyRules->where('ad_id', $ad_id)->delete();
                            }

                            if (!empty($step2_data['property_rules'])) {
                                foreach ($step2_data['property_rules'] as $prop_rule) {

                                    $AdToPropertyRules = new $this->AdToPropertyRules;

                                    $AdToPropertyRules->ad_id = $ad_id;
                                    $AdToPropertyRules->property_rules_id = $prop_rule;

                                    $AdToPropertyRules->save();
                                }
                            }

                            if ($action == 'edit') {
                                $NearbyFacilities = new $this->NearbyFacilities;
                                $NearbyFacilities->where('ad_id', $ad_id)->delete();
                            }

                            if (array_key_exists('near_by_facilities', $step1_data) && !empty($step1_data['near_by_facilities'])) {

                                foreach ($step1_data['near_by_facilities'] as $near_by_fac) {
                                    $explode_near_by = explode("#", $near_by_fac);

                                    $NearbyFacilities = new $this->NearbyFacilities;

                                    $NearbyFacilities->ad_id = $ad_id;
                                    $NearbyFacilities->latitude = $explode_near_by[0];
                                    $NearbyFacilities->longitude = $explode_near_by[1];
                                    $NearbyFacilities->name = $explode_near_by[2];
                                    $NearbyFacilities->nearbyfacility_type = $explode_near_by[3];

                                    $NearbyFacilities->save();
                                }
                            }

                            $request->session()->forget('AD_INFO');


                            if ($action == 'edit') {
                                $request->session()->flash('status', __('backend_messages.ad_success_editing'));
                            } else {

                                $request->session()->flash('status', __('backend_messages.ad_success_posted'));
                            }

                            $this->signalAd($ad_id);

                            //send mail complete ad
                            if (calculAdPercent($ad_id) < 100) {
                                $this->sendMailCompleteAd(array('url_slug' => str_slug($step1_data['title']) . '-' . $ad_id));
                            }

                            if (!empty($step1_data['redirected_ad_id'])) {
                                $redirected_ad_info = Ads::has('user')->where('status', '1')->where('admin_approve', '1')->where('id', $step1_data['redirected_ad_id'])->first();
                                if (!empty($redirected_ad_info)) {
                                    return redirect(adUrl($step1_data['redirected_ad_id'], $ad_id, $ad_id));
                                } else {
                                    if ($action == 'edit') {
                                        return redirect()->route('user.dashboard');
                                    } else {
                                        return redirect(route('confirm-ad-creation'));
                                    }
                                }
                            } else {
                                if ($action == 'edit') {
                                    return redirect()->route('user.dashboard');
                                } else {
                                    return redirect(route('confirm-ad-creation'));
                                }
                            }
                        } else {

                            if ($request->session()->has('AD_INFO')) {
                                $request->session()->forget('AD_INFO');
                            }
                            $request->session()->flash('status', __('backend_messages.loged_out_inactivity'));
                            return redirect()->route('home');
                        }
                    }
                    if (isset($ad_id)) {
                        $this->deleteAncienFiles($ad_id);
                    }

                    /*if(calculAdPercent($ad_id) < 100) {
                        $this->sendMailCompleteAd(array('url_slug' => str_slug( $step1_data['title']) . '-' . $ad_id));
                    }*/
                } else {
                    if ($request->session()->has('AD_INFO')) {
                        $request->session()->forget('AD_INFO');
                    }
                    $request->session()->flash('status', __('backend_messages.session_not_valid'));
                    return redirect()->route('home');
                }
            }
        } else {
            $request->session()->flash('status', __('backend_messages.session_expired'));
            return redirect()->route('home');
        }
    }

    //scenario_2
    public function saveStep1Sc2(Request $request)
    {
        if ($request->contact_continue == '0') {
            $strings = array($request->title, $request->description);
            if (!isInfoWithoutContact($strings)) {
                return response()->json(['error' => "contact_error", "message" => __('backend_messages.error_contact_ad')]);
            }
            $request->session()->forget('signaledAd');
        } else {
            $request->session()->put('signaledAd', true);
        }

        $data = $request->all();
        if (!array_key_exists("latitude", $data)) {
            $addressInfo = $request->session()->get("ADRESS_INFO");
            $latitude = $addressInfo->latitude;
            $longitude = $addressInfo->longitude;
            $address = $addressInfo->address;
        } else {
            $address = $request->address;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
        }


        modifyVilleAdresse($request->address, $latitude, $longitude);

        $latitude = round($latitude, 6);
        $longitude = round($longitude, 6);
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['address'] = $address;
        $data['actual_address'] = $address;
        $response = array();
        //stepp


        if (!(isset($request->edite) && $request->edite == "edite")) {
            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn(Ads::where('user_id', Auth::id())->pluck('title')->toArray()),],
                    'description' => 'required|min:10|max:2000|unique:ads',
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                ],
                [
                    'latitude.required' => 'Please input a valid address!',
                    'longitude.required' => 'Please input a valid address!',
                    'title.not_in' => __('backend_messages.value_taken')
                ]
            );
        } else {

            $refuse = Ads::where('user_id', Auth::id())->pluck('title');
            $ad_title = Ads::where('id', $request->ad_id)->get();
            $key_title = $refuse->search($ad_title->pluck('title')->first());
            //tedavina ny key de esosina anaty tableau rehefa
            if ($key_title !== false)
                $refuse->splice($key_title, 1);
            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn($refuse->toArray()),],
                    'description' => ['required', 'min:10', 'max:2000', Rule::unique('ads')->ignore($request->ad_id),],
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                ],
                [
                    'latitude.required' => 'Please input a valid address!',
                    'longitude.required' => 'Please input a valid address!',
                    'title.not_in' => __('backend_messages.value_taken')
                ]
            );
        }

        if ($validator->passes()) {
            $user_id = Auth::id();
            if (isset($data['email'])) {
                $user_id = $this->registerWithAd((object)$data);
            }
            $response['error'] = 'no';
            $unique_id = uniqid();
            $step1_data = $data;
            $AdTempFiles = new $this->AdTempFiles;
            $TemporaryUploadedGuarantees = new $this->TemporaryUploadedGuarantees;
            $Ads = new $this->Ads;
            if (!empty($data['ad_id'])) {
                $ad_id = $data['ad_id'];
                $action = 'edit';
            } else {
                $action = "add";
            }
            $ad = (object)array(
                "title" => $data['title'],
                "description" => $data['description'],
                "adress" => (!empty($data['actual_address'])) ? $data['actual_address'] : $data['address']
            );
            if (isset($ad_id)) {
                $Ads = $Ads::find($ad_id);
                //verifier s'il y a une changement, alors il faut que l'admin doivent le checker
                if ($Ads->title !== $request->title || $Ads->description !== $request->description) {
                    $Ads->ad_treaty = 0;
                }
            }

            if ($action != "edit" && Auth::check()) {
                $Ads->user_id = $user_id;
                $Ads->ad_treaty = 0;
                $Ads->updated_at = date("Y-m-d H:i:s");
            }
            $Ads->title = $step1_data['title'];
            $Ads->description = $step1_data['description'];
            if (!empty($step1_data['actual_address'])) {
                $Ads->address = $step1_data['actual_address'];
            } else {
                $Ads->address = $step1_data['address'];
            }
            $Ads->latitude = $step1_data['latitude'];
            $Ads->longitude = $step1_data['longitude'];
            $Ads->scenario_id = $step1_data['scenario_id'];
            $Ads->status = '1';
            $Ads->url_slug = str_slug($step1_data['title'], '-');
            if (isset($step1_data['ad_url'])) {
                $Ads->custom_url = $step1_data['ad_url'];
            }
            $Ads->admin_approve = '1';
            $Ads->save();
            countAds("insert");
            $ad_id = $Ads->id;
            if (isset($step1_data['proximity'])) {
                $this->saveProximity($ad_id, $step1_data['proximity']);
            }
            if (isset($step1_data['metro_lines'])) {
                $this->saveMetroLines($ad_id, $step1_data['metro_lines']);
            }
            if (isset($step1_data['sous_loc_type'])) {
                $Ads->sous_type_loc = $step1_data['sous_loc_type'];
            }
            $request->session()->put('AD_INFO', array('unique_id' => $unique_id, "ad_id" => $ad_id, "action" => $action));
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];
        return response()->json($response);
    }

    public function saveStep2Sc2(Request $request)
    {

        $data = $request->all();
        $response = array();

        $validator = Validator::make($data, [
            'date_of_availablity' => 'required|date_format:d/m/Y',
            'prop_square_meters' => 'required|numeric|min:1'
        ]);


        if ($validator->passes()) {

            $response['error'] = 'no';

            if ($request->session()->has('AD_INFO')) {
                $step2_data = $data;
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
                $ad_id = $ad_info_session['ad_id'];
                $Ads = Ads::where('id', $ad_id)->first();
                if (isset($step2_data['date_of_availablity'])) {
                    $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step2_data['date_of_availablity'])));
                }
                $Ads->save();
                countAds("insert");
                $this->saveAllDocuments($ad_id, $step2_data);
                $AdDetails = AdDetails::where('ad_id', $ad_id)->first();
                if (is_null($AdDetails)) {
                    $AdDetails = new $this->AdDetails;
                }
                $AdDetails->ad_id = $ad_id;
                $AdDetails->min_surface_area = $step2_data['prop_square_meters'];

                if (!empty($step2_data['min_stay_months']) || (isset($step2_data['min_stay_months']) && $step2_data['min_stay_months'] == 0)) {
                    $AdDetails->minimum_stay = $step2_data['min_stay_months'];
                }
                $AdDetails->preferred_gender = $step2_data['preferred_gender'];
                $AdDetails->preferred_occupation = $step2_data['preferred_occupation'];
                if (!empty($step2_data['preferred_age_range_from'])) {
                    $tabAge = explode("-", $step2_data['preferred_age_range_from']);
                    $AdDetails->age_range_from = $tabAge[0];
                    if (count($tabAge) == 2 && !empty($tabAge[1])) {
                        $AdDetails->age_range_to = $tabAge[1];
                    }
                } else {
                    $AdDetails->age_range_from = null;
                    $AdDetails->age_range_from = null;
                }
                $AdDetails->save();
                $this->saveAdMediaFiles($ad_id, $unique_id);
                $this->deleteAncienFiles($ad_id);
                //$request->session()->push('AD_INFO', array('step2_data' => $data));
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];
        return response()->json($response);
    }

    private function saveGuaranteeAsked($ad_id, $guarantees)
    {
        DB::table("ad_to_guarantees")->where("ad_id", $ad_id)->delete();
        foreach ($guarantees as $guar_ask) {

            $AdToGuarantees = new $this->AdToGuarantees;

            $AdToGuarantees->ad_id = $ad_id;
            $AdToGuarantees->guarantees_id = $guar_ask;

            $AdToGuarantees->save();
        }
    }

    public function saveStep3Sc2(Request $request)
    {

        $data = $request->all();
        $response = array();
        $validator = Validator::make($data, [
            'rent_per_month' => 'required|numeric|min:1',
            'property_type' => 'required'
        ]);

        $validator->sometimes(
            'utility_cost',
            'required|numeric|min:1',
            function ($input) {
                return $input->add_utility_cost == 1;
            }
        );

        $validator->sometimes(
            'deposit_price',
            'numeric|min:1',
            function ($input) {
                return $input->deposit_price != '';
            }
        );

        $validator->sometimes(
            'broker_fees',
            'numeric|min:1',
            function ($input) {
                return $input->broker_fees != '';
            }
        );

        if ($validator->passes()) {
            if (!empty($data['rent_per_month']) && !empty($data['deposit_price'])) {
                $data['rent_per_month'] = save_conversion_devise($data['rent_per_month']);
                $data['deposit_price'] = save_conversion_devise($data['deposit_price']);
            }
            $response['error'] = 'no';

            if ($request->session()->has('AD_INFO')) {
                $step2_data = $data;
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
                $ad_id = $ad_info_session['ad_id'];
                $Ads = Ads::where('id', $ad_id)->first();

                # nrh
                $Ads->min_rent = $step2_data['rent_per_month'];

                $Ads->complete = 1;
                $Ads->admin_approve = '1';
                $Ads->save();
                countAds("insert");
                $AdDetails = AdDetails::where('ad_id', $ad_id)->first();
                if (is_null($AdDetails)) {
                    $AdDetails = new $this->AdDetails;
                }
                $AdDetails->ad_id = $ad_id;
                $AdDetails->property_type_id = $step2_data['property_type'];


                $AdDetails->bedrooms = $step2_data['no_of_bedrooms'];
                $AdDetails->bathrooms = $step2_data['no_of_bathrooms'];
                $AdDetails->no_of_roommates = $step2_data['no_of_roommates'];
                if (!empty($step2_data['no_of_part_bathrooms']) || (isset($step2_data['no_of_part_bathrooms']) && $step2_data['no_of_part_bathrooms'] == 0)) {
                    $AdDetails->partial_bathrooms = $step2_data['no_of_part_bathrooms'];
                }
                if (!empty($step2_data['separate_kitchen']) || (isset($step2_data['separate_kitchen']) && $step2_data['separate_kitchen'] == 0)) {
                    $AdDetails->kitchen_separated = $step2_data['separate_kitchen'];
                }
                if (!empty($step2_data['deposit_price'])) {
                    $AdDetails->deposit_price = $step2_data['deposit_price'];
                }
                if (!empty($step2_data['add_utility_cost'])) {
                    $AdDetails->add_utility_costs = $step2_data['add_utility_cost'];
                }
                if (!empty($step2_data['utility_cost'])) {
                    $AdDetails->utility_cost = $step2_data['utility_cost'];
                }

                $AdDetails->furnished = $step2_data['furnished'];

                /*$AdDetails->furnished = $step3_data['furnished'];*/
                if (!empty($step2_data['broker_fees'])) {
                    $AdDetails->broker_fees = $step2_data['broker_fees'];
                }

                if (isset($step2_data['guarantee_asked'])) {
                    $this->saveGuaranteeAsked($ad_id, $step2_data['guarantee_asked']);
                }

                if (isset($step2_data['allowed_pets'])) {
                    $this->saveAllowedPets($ad_id, $step2_data['allowed_pets']);
                }

                if (isset($step2_data['property_rules'])) {
                    $this->savePropertyRules($ad_id, $step2_data['property_rules']);
                }

                if (isset($step2_data['prop_feature'])) {
                    $this->savePropFeatures($ad_id, $step2_data['prop_feature']);
                }

                if (isset($step2_data['building_amenities'])) {
                    $this->saveBuildingAmenities($ad_id, $step2_data['building_amenities']);
                }

                $AdDetails->save();

                //$request->session()->push('AD_INFO', array('step2_data' => $data));
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];

        return response()->json($response);
    }

    public function saveStep4Sc2( /*\App\Http\Requests\Step3Request*/ Step3Request $request)
    {
        //return response()->json(['id' => 'ok']);
        $data = $request->all();
        $response = array();
        $response['redirect_url'] = route('user.dashboard');
        if ($request->session()->has('AD_INFO')) {
            $ad_info_session = $request->session()->get('AD_INFO');
            $unique_id = $ad_info_session['unique_id'];
            $ad_id = $ad_info_session['ad_id'];
            $this->saveDetailVisit($ad_id, $data);
            $action = $ad_info_session['action'];
            if ($action == "add") {
                $response['redirect_url'] = route('confirm-ad-creation');
            }
        } else {
            $response['button_clicked'] = $data['button_clicked'];
            $response['user_type'] = $data['user'];
        }
        $response['button_clicked'] = $data['button_clicked'];
        return response()->json($response);
    }

    //scenario_3

    public function saveStep1Sc3(Request $request)
    {
        if ($request->contact_continue == '0') {
            $strings = array($request->title, $request->description);
            if (!isInfoWithoutContact($strings)) {
                return response()->json(['error' => "contact_error", "message" => __('backend_messages.error_contact_ad')]);
            }
            $request->session()->forget('signaledAd');
        } else {
            $request->session()->put('signaledAd', true);
        }

        $data = $request->all();
        if (!array_key_exists("latitude", $data)) {
            $addressInfo = $request->session()->get("ADRESS_INFO");
            $latitude = $addressInfo->latitude;
            $longitude = $addressInfo->longitude;
            $address = $addressInfo->address;
        } else {
            $address = $request->address;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
        }


        modifyVilleAdresse($request->address, $latitude, $longitude);

        $latitude = round($latitude, 6);
        $longitude = round($longitude, 6);
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['address'] = $address;
        $data['actual_address'] = $address;
        $response = array();
        if (!(isset($request->edite) && $request->edite == "edite")) {
            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn(Ads::where('user_id', Auth::id())->pluck('title')->toArray()),],
                    'description' => 'required|min:10|max:2000|unique:ads',
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                ],
                [
                    'latitude.required' => 'Please input a valid address!',
                    'longitude.required' => 'Please input a valid address!',
                    'title.not_in' => __('backend_messages.value_taken')
                ]
            );
        } else {
            $refuse = Ads::where('user_id', Auth::id())->pluck('title');
            $ad_title = Ads::where('id', $request->ad_id)->get();
            $key_title = $refuse->search($ad_title->pluck('title')->first());
            //tedavina ny key de esosina anaty tableau rehefa
            if ($key_title !== false)
                $refuse->splice($key_title, 1);
            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn($refuse->toArray()),],
                    'description' => ['required', 'min:10', 'max:2000', Rule::unique('ads')->ignore($request->ad_id),],
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                ],
                [
                    'latitude.required' => 'Please input a valid address!',
                    'longitude.required' => 'Please input a valid address!',
                    'title.not_in' => __('backend_messages.value_taken')
                ]
            );
        }
        if ($validator->passes()) {
            $user_id = Auth::id();
            if (isset($data['email'])) {
                $user_id = $this->registerWithAd((object)$data);
            }
            $unique_id = uniqid();
            $step1_data = $data;
            $Ads = new $this->Ads;
            if (!empty($data['ad_id'])) {
                $ad_id = $data['ad_id'];
                $action = 'edit';
            } else {
                $action = "add";
            }
            $ad = (object)array(
                "title" => $data['title'],
                "description" => $data['description'],
                "adress" => (!empty($data['actual_address'])) ? $data['actual_address'] : $data['address']
            );
            if (isset($ad_id)) {
                $Ads = $Ads::find($ad_id);
                //verifier s'il y a une changement, alors il faut que l'admin doivent le checker
                if ($Ads->title !== $request->title || $Ads->description !== $request->description) {
                    $Ads->ad_treaty = 0;
                }
            }

            if ($action != "edit" && Auth::check()) {
                $Ads->user_id = $user_id;
                $Ads->ad_treaty = 0;
                $Ads->updated_at = date("Y-m-d H:i:s");
            }
            $Ads->title = $step1_data['title'];
            $Ads->description = $step1_data['description'];
            if (!empty($step1_data['actual_address'])) {
                $Ads->address = $step1_data['actual_address'];
            } else {
                $Ads->address = $step1_data['address'];
            }
            $Ads->latitude = $step1_data['latitude'];
            $Ads->longitude = $step1_data['longitude'];
            $Ads->scenario_id = $step1_data['scenario_id'];
            $Ads->status = '1';
            $Ads->admin_approve = '1';
            $Ads->url_slug = str_slug($step1_data['title'], '-');
            if (isset($step1_data['ad_url'])) {
                $Ads->custom_url = $step1_data['ad_url'];
            }
            $Ads->save();
            countAds("insert");
            $ad_id = $Ads->id;
            if (isset($step1_data['proximity'])) {
                $this->saveProximity($ad_id, $step1_data['proximity']);
            }
            if (!empty($step1_data['accept_as']) || (isset($step1_data['accept_as']) && $step1_data['accept_as'] == 0)) {
                $AdDetails = new $this->AdDetails;
                $AdDetails->ad_id = $ad_id;
                $AdDetails->accept_as = $step1_data['accept_as'];
                $AdDetails->save();
            }
            $request->session()->put('AD_INFO', array('unique_id' => $unique_id, "ad_id" => $ad_id, "action" => $action));
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];

        return response()->json($response);
    }

    public function saveStep2Sc3(Request $request)
    {

        $data = $request->all();
        $response = array();

        $validator = Validator::make($data, [
            'property_type' => 'required',
            'prop_square_meters' => 'required|numeric|min:1',
            'prop_square_meters_max' => 'numeric',
            'date_of_availablity' => 'required|date_format:d/m/Y'
        ]);

        if ($validator->passes()) {
            if (!empty($data['rent_per_month']) && !empty($data['rent_per_month_max'])) {
                $data['rent_per_month'] = save_conversion_devise($data['rent_per_month']);
                $data['rent_per_month_max'] = save_conversion_devise($data['rent_per_month_max']);
            }
            $validator->sometimes(
                'rent_per_month',
                'numeric|min:1',
                function ($input) {
                    return $input->rent_per_month != '';
                }
            );

            if (is_numeric($request->rent_per_month)) {
                $validator->sometimes(
                    'rent_per_month_max',
                    'numeric|min:' . (int)($request->rent_per_month + 1),
                    function ($input) {
                        return $input->rent_per_month_max != '';
                    }
                );
            }

            $validator->sometimes(
                'prop_square_meters_max',
                'numeric|min:' . (int)($request->prop_square_meters + 1),
                function ($input) {
                    return $input->prop_square_meters_max != '';
                }
            );
        }


        if ($validator->passes()) {

            $response['error'] = 'no';

            if ($request->session()->has('AD_INFO')) {
                $step2_data = $data;
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
                $ad_id = $ad_info_session['ad_id'];
                $Ads = Ads::where('id', $ad_id)->first();

                # nrh
                $Ads->min_rent = $step2_data['rent_per_month'];

                if (!empty($step2_data['rent_per_month_max'])) {
                    $Ads->max_rent = $step2_data['rent_per_month_max'];
                }
                if (isset($step2_data['date_of_availablity'])) {
                    $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step2_data['date_of_availablity'])));
                }
                if (isset($step2_data['sous_loc_type'])) {
                    $Ads->sous_type_loc = $step2_data['sous_loc_type'];
                }
                $Ads->complete = 1;
                $Ads->admin_approve = '1';
                $Ads->save();
                countAds("insert");
                $this->saveAllDocuments($ad_id, $step2_data);
                $AdDetails = AdDetails::where('ad_id', $ad_id)->first();
                if (is_null($AdDetails)) {
                    $AdDetails = new $this->AdDetails;
                }
                $AdDetails->ad_id = $ad_id;
                $AdDetails->property_type_id = $step2_data['property_type'];
                $AdDetails->min_surface_area = $step2_data['prop_square_meters'];
                if (!empty($step2_data['prop_square_meters_max'])) {
                    $AdDetails->max_surface_area = $step2_data['prop_square_meters_max'];
                }

                $AdDetails->bedrooms = $step2_data['no_of_bedrooms'];
                $AdDetails->bathrooms = $step2_data['no_of_bathrooms'];
                if (!empty($step2_data['separate_kitchen']) || (isset($step2_data['separate_kitchen']) && $step2_data['separate_kitchen'] == 0)) {
                    $AdDetails->kitchen_separated = $step2_data['separate_kitchen'];
                }
                if (!empty($step2_data['min_stay_months']) || (isset($step2_data['min_stay_months']) && $step2_data['min_stay_months'] == 0)) {
                    $AdDetails->minimum_stay = $step2_data['min_stay_months'];
                }
                if (!empty($step2_data['deposit_price'])) {
                    $AdDetails->deposit_price = $step2_data['deposit_price'];
                }
                if (!empty($step2_data['add_utility_cost'])) {
                    $AdDetails->add_utility_costs = $step2_data['add_utility_cost'];
                }
                if (!empty($step2_data['utility_cost'])) {
                    $AdDetails->utility_cost = $step2_data['utility_cost'];
                }

                if (!isset($step2_data['furnished']) || count($step2_data['furnished']) > 1 || count($step2_data['furnished']) == 0) {
                    $furnished = '2';
                } else {
                    $furnished = $step2_data['furnished'][0];
                }
                $AdDetails->furnished = $furnished;
                /*$AdDetails->furnished = $step3_data['furnished'];*/
                if (!empty($step2_data['broker_fees'])) {
                    $AdDetails->broker_fees = $step2_data['broker_fees'];
                }

                if (isset($step2_data['allowed_pets'])) {
                    $this->saveAllowedPets($ad_id, $step2_data['allowed_pets']);
                }

                if (isset($step2_data['property_rules'])) {
                    $this->savePropertyRules($ad_id, $step2_data['property_rules']);
                }

                if (isset($step2_data['prop_feature'])) {
                    $this->savePropFeatures($ad_id, $step2_data['prop_feature']);
                }

                if (isset($step2_data['building_amenities'])) {
                    $this->saveBuildingAmenities($ad_id, $step2_data['building_amenities']);
                }

                $AdDetails->save();
                //$request->session()->push('AD_INFO', array('step2_data' => $data));
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];

        return response()->json($response);
    }

    private function saveRentalApplications($ad_id, $data)
    {
        $AdRentalApplications = AdRentalApplications::where('ad_id', $ad_id)->first();
        if (is_null($AdRentalApplications)) {
            $AdRentalApplications = new $this->AdRentalApplications;
        }

        $AdRentalApplications->ad_id = $ad_id;
        $AdRentalApplications->actual_renting_price = $data['actual_renting_price'];
        if (!empty($data['occupation']) || (isset($data['occupation']) && $data['occupation'] == 0)) {
            $AdRentalApplications->occupation = $data['occupation'];
        }
        $AdRentalApplications->save();
    }

    public function saveStep3Sc3(Request $request)
    {

        $data = $request->all();
        $response = array();

        $validator = Validator::make($data, [
            'actual_renting_price' => 'required|numeric|min:1'
        ]);

        $validator->sometimes(
            'job_title',
            'required|min:3|max:50',
            function ($input) {
                return $input->occupation != 0;
            }
        );

        $validator->sometimes(
            'monthly_salary',
            'numeric|min:1',
            function ($input) {
                return $input->monthly_salary != '';
            }
        );

        $validator->sometimes(
            'start_date',
            'date_format:d/m/Y',
            function ($input) {
                return $input->start_date != '';
            }
        );

        if ($validator->passes()) {
            if (!empty($data['actual_renting_price']) && !empty($data['monthly_salary'])) {
                $data['actual_renting_price'] = save_conversion_devise($data['actual_renting_price']);
                $data['monthly_salary'] = save_conversion_devise($data['monthly_salary']);
            }
            $response['error'] = 'no';
            $response['redirect_url'] = route('user.dashboard');
            if ($request->session()->has('AD_INFO')) {
                $ad_info_session = $request->session()->get('AD_INFO');
                $ad_id = $ad_info_session['ad_id'];
                $action = $ad_info_session['action'];
                $this->saveRentalApplications($ad_id, $data);
                if ($action == "add") {
                    $response['redirect_url'] = route('confirm-ad-creation');
                }
                //$request->session()->push('AD_INFO', array('step2_data' => $data));
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }

        $response['button_clicked'] = $data['button_clicked'];
        $response['user_type'] = $data['user'];
        return response()->json($response);
    }

    //scenario_4
    public function saveStep1Sc4(Request $request)
    {
        if ($request->contact_continue == '0') {
            $strings = array($request->title, $request->description);
            if (!isInfoWithoutContact($strings)) {
                return response()->json(['error' => "contact_error", "message" => __('backend_messages.error_contact_ad')]);
            }
            $request->session()->forget('signaledAd');
        } else {
            $request->session()->put('signaledAd', true);
        }

        $data = $request->all();
        if (!array_key_exists("latitude", $data)) {
            $addressInfo = $request->session()->get("ADRESS_INFO");
            $latitude = $addressInfo->latitude;
            $longitude = $addressInfo->longitude;
            $address = $addressInfo->address;
        } else {
            $address = $request->address;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
        }


        modifyVilleAdresse($request->address, $latitude, $longitude);

        $latitude = round($latitude, 6);
        $longitude = round($longitude, 6);
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['address'] = $address;
        $data['actual_address'] = $address;
        $response = array();
        if (!(isset($request->edite) && $request->edite == "edite")) {
            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn(Ads::where('user_id', Auth::id())->pluck('title')->toArray()),],
                    'description' => 'required|min:10|max:2000|unique:ads',
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                ],
                [
                    'latitude.required' => 'Please input a valid address!',
                    'longitude.required' => 'Please input a valid address!',
                    'title.not_in' => __('backend_messages.value_taken')
                ]
            );
        } else {
            $refuse = Ads::where('user_id', Auth::id())->pluck('title');
            $ad_title = Ads::where('id', $request->ad_id)->get();
            $key_title = $refuse->search($ad_title->pluck('title')->first());
            //tedavina ny key de esosina anaty tableau rehefa
            if ($key_title !== false)
                $refuse->splice($key_title, 1);
            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn($refuse->toArray()),],
                    'description' => ['required', 'min:10', 'max:2000', Rule::unique('ads')->ignore($request->ad_id),],
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                ],
                [
                    'latitude.required' => 'Please input a valid address!',
                    'longitude.required' => 'Please input a valid address!',
                    'title.not_in' => __('backend_messages.value_taken')
                ]
            );
        }
        if ($validator->passes()) {
            $user_id = Auth::id();
            if (isset($data['email'])) {
                $user_id = $this->registerWithAd((object)$data);
            }
            $response['error'] = 'no';
            $unique_id = uniqid();
            $step1_data = $data;
            $Ads = new $this->Ads;
            if (!empty($data['ad_id'])) {
                $ad_id = $data['ad_id'];
                $action = 'edit';
            } else {
                $action = "add";
            }
            $ad = (object)array(
                "title" => $data['title'],
                "description" => $data['description'],
                "adress" => (!empty($data['actual_address'])) ? $data['actual_address'] : $data['address']
            );
            if (isset($ad_id)) {
                $Ads = $Ads::find($ad_id);
                //verifier s'il y a une changement, alors il faut que l'admin doivent le checker
                if ($Ads->title !== $request->title || $Ads->description !== $request->description) {
                    $Ads->ad_treaty = 0;
                }
            }

            if ($action != "edit" && Auth::check()) {
                $Ads->user_id = $user_id;
                $Ads->ad_treaty = 0;
                $Ads->updated_at = date("Y-m-d H:i:s");
            }
            $Ads->title = $step1_data['title'];
            $Ads->description = $step1_data['description'];
            if (!empty($step1_data['actual_address'])) {
                $Ads->address = $step1_data['actual_address'];
            } else {
                $Ads->address = $step1_data['address'];
            }
            $Ads->latitude = $step1_data['latitude'];
            $Ads->longitude = $step1_data['longitude'];
            $Ads->scenario_id = $step1_data['scenario_id'];
            $Ads->status = '1';
            $Ads->admin_approve = '1';
            $Ads->url_slug = str_slug($step1_data['title'], '-');
            if (isset($step1_data['ad_url'])) {
                $Ads->custom_url = $step1_data['ad_url'];
            }
            $Ads->save();
            countAds("insert");
            $ad_id = $Ads->id;
            if (isset($step1_data['proximity'])) {
                $this->saveProximity($ad_id, $step1_data['proximity']);
            }
            $request->session()->put('AD_INFO', array('unique_id' => $unique_id, "ad_id" => $ad_id, "action" => $action));
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];

        return response()->json($response);
    }

    public function saveStep2Sc4(Request $request)
    {

        $data = $request->all();
        $response = array();

        $validator = Validator::make($data, [
            'date_of_availablity' => 'required|date_format:d/m/Y',
            'prop_square_meters' => 'required|numeric|min:1',
            'prop_square_meters_max' => 'numeric'
        ]);
        if ($validator->passes()) {
            $validator->sometimes(
                'prop_square_meters_max',
                'numeric|min:' . (int)($request->prop_square_meters + 1),
                function ($input) {
                    return $input->prop_square_meters_max != '';
                }
            );
        }


        if ($validator->passes()) {

            $response['error'] = 'no';

            if ($request->session()->has('AD_INFO')) {
                $step2_data = $data;
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
                $ad_id = $ad_info_session['ad_id'];
                $Ads = Ads::where('id', $ad_id)->first();
                if (!empty($step2_data['rent_per_month_max'])) {
                    $Ads->max_rent = $step2_data['rent_per_month_max'];
                }
                if (isset($step2_data['date_of_availablity'])) {
                    $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step2_data['date_of_availablity'])));
                }
                $Ads->save();
                countAds("insert");
                $AdDetails = AdDetails::where('ad_id', $ad_id)->first();
                if (is_null($AdDetails)) {
                    $AdDetails = new $this->AdDetails;
                }
                $AdDetails->ad_id = $ad_id;

                $AdDetails->min_surface_area = $step2_data['prop_square_meters'];
                if (!empty($step2_data['prop_square_meters_max'])) {
                    $AdDetails->max_surface_area = $step2_data['prop_square_meters_max'];
                }

                $AdDetails->preferred_gender = $step2_data['preferred_gender'];
                $AdDetails->preferred_occupation = $step2_data['preferred_occupation'];
                if (!empty($step2_data['preferred_age_range_from'])) {
                    $tabAge = explode("-", $step2_data['preferred_age_range_from']);
                    $AdDetails->age_range_from = $tabAge[0];
                    if (count($tabAge) == 2 && !empty($tabAge[1])) {
                        $AdDetails->age_range_to = $tabAge[1];
                    }
                } else {
                    $AdDetails->age_range_from = null;
                    $AdDetails->age_range_from = null;
                }

                $AdDetails->save();
                $response['redirect_url'] = route('user.dashboard');
                //$request->session()->push('AD_INFO', array('step2_data' => $data));
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];

        return response()->json($response);
    }

    public function saveStep3Sc4(Request $request)
    {

        $data = $request->all();
        $response = array();

        $validator = Validator::make($data, [
            'property_type' => 'required',
            'budget' => 'required'
        ]);

        $validator->sometimes(
            'rent_per_month_max',
            'numeric|min:' . (int)($request->rent_per_month + 1),
            function ($input) {
                return $input->rent_per_month_max != '';
            }
        );

        if ($validator->passes()) {
            if (!empty($data['budget'])) {
                $data['budget'] = save_conversion_devise($data['budget']);
            }
            $response['error'] = 'no';

            if ($request->session()->has('AD_INFO')) {
                $step2_data = $data;
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
                $ad_id = $ad_info_session['ad_id'];
                $Ads = Ads::where('id', $ad_id)->first();

                $Ads->complete = 1;
                $Ads->admin_approve = '1';
                $Ads->min_rent = $step2_data['budget'];
                $Ads->budget = $step2_data['budget'];


                $Ads->save();
                countAds("insert");
                $AdDetails = AdDetails::where('ad_id', $ad_id)->first();
                if (is_null($AdDetails)) {
                    $AdDetails = new $this->AdDetails;
                }
                $AdDetails->ad_id = $ad_id;
                $AdDetails->property_type_id = $step2_data['property_type'];
                $AdDetails->budget = $step2_data['budget'];

                $AdDetails->bedrooms = $step2_data['no_of_bedrooms'];
                $AdDetails->bathrooms = $step2_data['no_of_bathrooms'];
                if (!empty($step2_data['separate_kitchen']) || (isset($step2_data['separate_kitchen']) && $step2_data['separate_kitchen'] == 0)) {
                    $AdDetails->kitchen_separated = $step2_data['separate_kitchen'];
                }

                if (!isset($step2_data['furnished']) || count($step2_data['furnished']) > 1 || count($step2_data['furnished']) == 0) {
                    $furnished = '2';
                } else {
                    $furnished = $step2_data['furnished'][0];
                }
                $AdDetails->furnished = $furnished;
                if (isset($step2_data['allowed_pets'])) {
                    $this->saveAllowedPets($ad_id, $step2_data['allowed_pets']);
                }

                if (isset($step2_data['property_rules'])) {
                    $this->savePropertyRules($ad_id, $step2_data['property_rules']);
                }

                if (isset($step2_data['prop_feature'])) {
                    $this->savePropFeatures($ad_id, $step2_data['prop_feature']);
                }

                if (isset($step2_data['building_amenities'])) {
                    $this->saveBuildingAmenities($ad_id, $step2_data['building_amenities']);
                }
                $AdDetails->no_of_roommates = $step2_data['no_of_roommates'];

                $AdDetails->save();

                //$request->session()->push('AD_INFO', array('step2_data' => $data));
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];

        return response()->json($response);
    }

    public function saveStep4Sc4(Request $request)
    {

        $data = $request->all();
        $response = array();

        $validator = Validator::make($data, [
            'actual_renting_price' => 'required|numeric|min:1',
            'guarantee_type' => 'required'
        ]);

        if ($validator->passes()) {
            if (!empty($data['actual_renting_price'])) {
                $data['actual_renting_price'] = save_conversion_devise($data['actual_renting_price']);
            }
            $response['error'] = 'no';
            $response['redirect_url'] = route('user.dashboard');
            if ($request->session()->has('AD_INFO')) {
                $ad_info_session = $request->session()->get('AD_INFO');
                $ad_id = $ad_info_session['ad_id'];
                $action = $ad_info_session['action'];
                $this->saveRentalApplications($ad_id, $data);
                $this->saveAdGuarantee($ad_id, $data['guarantee_type']);
                if ($action == "add") {
                    $response['redirect_url'] = route('confirm-ad-creation');
                }
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }

        $response['button_clicked'] = $data['button_clicked'];
        $response['user_type'] = $data['user'];
        return response()->json($response);
    }

    //scenario_5
    public function saveStep1Sc5(Request $request)
    {
        if ($request->contact_continue == '0') {
            $strings = array($request->title, $request->description);
            if (!isInfoWithoutContact($strings)) {
                return response()->json(['error' => "contact_error", "message" => __('backend_messages.error_contact_ad')]);
            }
            $request->session()->forget('signaledAd');
        } else {
            $request->session()->put('signaledAd', true);
        }

        $data = $request->all();
        if (!array_key_exists("latitude", $data)) {
            $addressInfo = $request->session()->get("ADRESS_INFO");
            $latitude = $addressInfo->latitude;
            $longitude = $addressInfo->longitude;
            $address = $addressInfo->address;
        } else {
            $address = $request->address;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
        }


        modifyVilleAdresse($request->address, $latitude, $longitude);

        $latitude = round($latitude, 6);
        $longitude = round($longitude, 6);
        $data['latitude'] = $latitude;
        $data['longitude'] = $longitude;
        $data['address'] = $address;
        $data['actual_address'] = $address;
        $response = array();
        if (!(isset($request->edite) && $request->edite == "edite")) {
            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn(Ads::where('user_id', Auth::id())->pluck('title')->toArray()),],
                    'description' => 'required|min:10|max:2000|unique:ads',
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                ],
                [
                    'latitude.required' => 'Please input a valid address!',
                    'longitude.required' => 'Please input a valid address!',
                    'title.not_in' => __('backend_messages.value_taken')
                ]
            );
        } else {

            $refuse = Ads::where('user_id', Auth::id())->pluck('title');
            $ad_title = Ads::where('id', $request->ad_id)->get();
            $key_title = $refuse->search($ad_title->pluck('title')->first());
            //tedavina ny key de esosina anaty tableau rehefa
            if ($key_title !== false)
                $refuse->splice($key_title, 1);
            $validator = Validator::make(
                $data,
                [
                    'title' => ['required', 'min:3', 'max:100', Rule::notIn($refuse->toArray()),],
                    'description' => ['required', 'min:10', 'max:2000', Rule::unique('ads')->ignore($request->ad_id),],
                    'email' => array_key_exists("email", $data) ? 'required|max:100|email|unique:users' : '',
                    'password' => array_key_exists("email", $data) ? 'required' : '',
                    'first_name' => array_key_exists("email", $data) ? 'required' : '',
                    'last_name' => array_key_exists("email", $data) ? 'required' : '',
                    'postal_code' => array_key_exists("email", $data) ? 'required' : ''
                ],
                [
                    'latitude.required' => 'Please input a valid address!',
                    'longitude.required' => 'Please input a valid address!',
                    'title.not_in' => __('backend_messages.value_taken')
                ]
            );
        }
        if ($validator->passes()) {
            $user_id = Auth::id();
            if (isset($data['email'])) {
                $user_id = $this->registerWithAd((object)$data);
            }
            $response['error'] = 'no';
            $unique_id = uniqid();
            $step1_data = $data;
            $Ads = new $this->Ads;
            if (!empty($data['ad_id'])) {
                $ad_id = $data['ad_id'];
                $action = 'edit';
            } else {
                $action = "add";
            }
            $ad = (object)array(
                "title" => $data['title'],
                "description" => $data['description'],
                "adress" => (!empty($data['actual_address'])) ? $data['actual_address'] : $data['address']
            );
            if (isset($ad_id)) {
                $Ads = $Ads::find($ad_id);
                //verifier s'il y a une changement, alors il faut que l'admin doivent le checker
                if ($Ads->title !== $request->title || $Ads->description !== $request->description) {
                    $Ads->ad_treaty = 0;
                }
            }

            if ($action != "edit" && Auth::check()) {
                $Ads->user_id = $user_id;
                $Ads->ad_treaty = 0;
                $Ads->updated_at = date("Y-m-d H:i:s");
            }
            $Ads->title = $step1_data['title'];
            $Ads->description = $step1_data['description'];
            if (!empty($step1_data['actual_address'])) {
                $Ads->address = $step1_data['actual_address'];
            } else {
                $Ads->address = $step1_data['address'];
            }
            $Ads->latitude = $step1_data['latitude'];
            $Ads->longitude = $step1_data['longitude'];
            $Ads->scenario_id = $step1_data['scenario_id'];
            $Ads->status = '1';
            $Ads->admin_approve = '1';
            $Ads->url_slug = str_slug($step1_data['title'], '-');
            if (isset($step1_data['ad_url'])) {
                $Ads->custom_url = $step1_data['ad_url'];
            }
            $Ads->save();
            countAds("insert");
            $ad_id = $Ads->id;
            if (isset($step1_data['proximity'])) {
                $this->saveProximity($ad_id, $step1_data['proximity']);
            }
            $request->session()->put('AD_INFO', array('unique_id' => $unique_id, "ad_id" => $ad_id, "action" => $action));
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }
        $response['button_clicked'] = $data['button_clicked'];

        return response()->json($response);
    }

    public function saveStep2Sc5(Request $request)
    {
        $data = $request->all();

        $response = array();

        $validator = Validator::make($data, [
            'property_type' => 'required',
            'prop_square_meters' => 'required|numeric|min:1',
            'date_of_availablity' => 'required|date_format:d/m/Y'
        ]);

        $validator->sometimes(
            'rent_per_month_max',
            'numeric|min:' . (int)($request->rent_per_month + 1),
            function ($input) {
                return $input->rent_per_month_max != '';
            }
        );

        $validator->sometimes(
            'prop_square_meters_max',
            'numeric|min:' . (int)($request->prop_square_meters + 1),
            function ($input) {
                return $input->prop_square_meters_max != '';
            }
        );

        if ($validator->passes()) {
            if (!empty($data ['rent_per_month']) && !empty($data ['rent_per_month_max'])) {
                $data ['rent_per_month'] = save_conversion_devise($data ['rent_per_month']);
                $data ['rent_per_month_max'] = save_conversion_devise($data ['rent_per_month_max']);
            }
            $response['error'] = 'no';
            $response['redirect_url'] = route('user.dashboard');
            if ($request->session()->has('AD_INFO')) {
                $step2_data = $data;
                $ad_info_session = $request->session()->get('AD_INFO');
                $unique_id = $ad_info_session['unique_id'];
                $ad_id = $ad_info_session['ad_id'];
                $action = $ad_info_session['action'];
                if ($action == "add") {
                    $response['redirect_url'] = route('confirm-ad-creation');
                }
                $Ads = Ads::where('id', $ad_id)->first();
                $Ads->min_rent = $step2_data['rent_per_month'];
                if (!empty($step2_data['rent_per_month_max'])) {
                    $Ads->max_rent = $step2_data['rent_per_month_max'];
                }
                if (isset($step2_data['date_of_availablity'])) {
                    $Ads->available_date = date("Y-m-d", strtotime(convertDateWithTiret($step2_data['date_of_availablity'])));
                }
                $Ads->complete = 1;
                $Ads->admin_approve = '1';
                $Ads->save();
                countAds("insert");
                $this->saveAllDocuments($ad_id, $step2_data);
                $AdDetails = AdDetails::where('ad_id', $ad_id)->first();
                if (is_null($AdDetails)) {
                    $AdDetails = new $this->AdDetails;
                }
                $AdDetails->ad_id = $ad_id;
                $AdDetails->property_type_id = $step2_data['property_type'];
                $AdDetails->min_surface_area = $step2_data['prop_square_meters'];
                if (!empty($step2_data['prop_square_meters_max'])) {
                    $AdDetails->max_surface_area = $step2_data['prop_square_meters_max'];
                }

                $AdDetails->bedrooms = $step2_data['no_of_bedrooms'];
                $AdDetails->bathrooms = $step2_data['no_of_bathrooms'];
                if (!empty($step2_data['separate_kitchen']) || (isset($step2_data['separate_kitchen']) && $step2_data['separate_kitchen'] == 0)) {
                    $AdDetails->kitchen_separated = $step2_data['separate_kitchen'];
                }
                if (!isset($step2_data['furnished']) || count($step2_data['furnished']) > 1 || count($step2_data['furnished']) == 0) {
                    $furnished = '2';
                } else {
                    $furnished = $step2_data['furnished'][0];
                }
                $AdDetails->furnished = $furnished;

                if (isset($step2_data['allowed_pets'])) {
                    $this->saveAllowedPets($ad_id, $step2_data['allowed_pets']);
                }

                if (isset($step2_data['property_rules'])) {
                    $this->savePropertyRules($ad_id, $step2_data['property_rules']);
                }

                if (isset($step2_data['prop_feature'])) {
                    $this->savePropFeatures($ad_id, $step2_data['prop_feature']);
                }

                if (isset($step2_data['building_amenities'])) {
                    $this->saveBuildingAmenities($ad_id, $step2_data['building_amenities']);
                }

                $AdDetails->save();
            } else {
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }

        $response['button_clicked'] = $data['button_clicked'];
        $response['user_type'] = $data['user'];
        return response()->json($response);
    }

    private function getAllDocuments($ad_id)
    {
        $documents = new AdDocuments();
        $adDocuments = $documents->where('ad_id', $ad_id)->get();
        $response = array();
        foreach ($adDocuments as $key => $value) {
            $response[$value->id] = $value;
        }
        return $response;
    }

    private function saveAllDocuments($ad_id, $data)
    {
        $docs = $this->getAllDocuments($ad_id);
        $valid = TRUE;
        if (!empty($data['documents'])) {
            foreach ($data['documents'] as $key => $value) {
                if (isset($value['id'])) {
                    $document = AdDocuments::find($value['id']);
                    if (isset($docs[$value['id']])) {
                        $docs[$value['id']] = null;
                    }
                } else {
                    $document = new AdDocuments();
                }
                $document->ad_id = $ad_id;
                if (isset($value['name']) && !empty($value['name'])) {
                    $document->document_name = $value['name'];
                    $document->document_required = isset($value['required']) ? TRUE : FALSE;
                    $document->save();
                } else {
                    $valid = FALSE;
                }
            }
            foreach ($docs as $key => $value) {
                if ($value != null)
                    $value->delete();
            }
        }
    }

    public function insertUserBadi(Request $request)
    {
        DB::table("badi")->insert([
            "ip" => get_ip(),
            "user_id" => Auth::id(),
            "ad_id" => $request->ad_id,
            "date" => date("Y-m-d H:i:s")
        ]);
        echo "true";
    }

    function contactAnnonce($adId, Request $request)
    {
        //countTrakingAds('message', $id);
        if (is_numeric($adId)) {
            $prenom = null;
            if (isset($request->user)) {
                $prenom = $request->user;
            }
            countTrakingAds('message', $adId);
            $ad = Ads::has('user')
                ->with(['user.user_profiles', 'user.user_packages' => function ($query) {
                    $query->orderBy('id', 'desc')->first();
                }])
                ->with('type_location')
                ->with(['ad_files' => function ($query) {
                    $query->where('media_type', '0')->orderBy('ordre', 'asc');
                }, 'ad_details.property_type', 'nearby_facilities', 'ad_to_allowed_pets.allowed_pets', 'ad_to_property_features.property_features', 'ad_to_amenities.amenities', 'ad_to_guarantees.guarantees', 'ad_to_property_rules.property_rules', 'ad_visiting_details', 'ad_uploaded_guarantees.guarantees'])
                ->where('id', $adId)
                ->first();
            return view('property.contact-annonce', compact("ad", "prenom"));
        } else {
            return redirect()->back();
        }
    }

    public function rotateImage(Request $request)
    {
        $destinationPathImages = base_path() . '/storage/uploads/images_annonces/';
        $extra = $request->extra;
        $angle = $request->angle;

        if ($extra['type'] == "temp") {
            $temp = AdTemporaryFiles::where("id", $extra['id'])->first();
        } else {
            $temp = AdFiles::where("id", $extra['id'])->first();
        }

        $file_name = $temp->filename;
        $ancienPath = $destinationPathImages . "ancien_" . $file_name;

        $savePath = $destinationPathImages . $file_name;
        $ext = pathinfo($ancienPath, PATHINFO_EXTENSION);
        $new_file_name = rand(999, 99999) . '_' . uniqid() . '.' . $ext;
        $newSavePath = $destinationPathImages . $new_file_name;
        $temp->filename = $new_file_name;
        $temp->save();
        autoRotateImage($ancienPath, $angle, $savePath, $newSavePath);
        copy($newSavePath, $destinationPathImages . "ancien_" . $new_file_name);
        unlink($ancienPath);

        $response['preview'] = "<img src='/uploads/images_annonces/" . $temp->filename . "' class='file-preview-image' alt='" . $temp->user_filename . "' title='" . $temp->user_filename . "'>";
        return response()->json($response);
    }

    private function deleteAncienFiles($ad_id)
    {
        $destinationPathImages = base_path() . '/storage/uploads/images_annonces/';
        $temp_files = DB::table("ad_files")->where('ad_id', $ad_id)->where("media_type", 0)->get();
        foreach ($temp_files as $key => $value) {
            if (file_exists($destinationPathImages . "ancien_" . $value->filename)) {
                unlink($destinationPathImages . "ancien_" . $value->filename);
            }
        }
    }

    public function generateAdUrl(Request $request)
    {
        $ville = $request->ville;
        $scenario_id = $request->scenario_id;
        return defaultAdUrl($ville, $scenario_id);
    }

    public function orderPhotos(Request $request)
    {
        if (isset($request->data)) {
            $files = $request->data;
            foreach ($files as $key => $info) {
                $extra = $info['extra'];
                if ($extra['type'] == "temp") {
                    $file = AdTemporaryFiles::where("id", $extra['id'])->first();
                    $file->ordre = $key;
                    $file->save();
                } else {
                    $file = AdFiles::where("id", $extra['id'])->first();
                    $file->ordre = $key;
                    $file->save();
                }
            }

            return response()->json([]);
        }
    }
}
