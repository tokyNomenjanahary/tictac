<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Location;
use App\LocatairesGeneralInformations;
use App\Logement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Models\Applications\Application;
use App\Http\Models\ApplicationStates;
use App\Http\Models\Applications\ApplicationDocuments;
use App\Http\Models\Ads\AdDocuments;
use App\Http\Models\Ads\Ads;
use mysql_xdevapi\Table;
use stdClass;
use App\User;
use App\Http\Models\Ads\Favourites;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    public function add(Request $request)
    {
        if (Auth::check()) {
            $response = array();
            $data = $request->all();
            $documents = new AdDocuments();
            $ad_id = base64_decode($data['ad_id']);
            $sender_id = base64_decode($data['sender_ad_id']);
            $adDocuments = $documents->where('ad_id', $ad_id)->get();
            $validationParam = array(
                'motivation' => 'required',
            );
            $docs = array();
            $doc_error = array();
            foreach ($adDocuments as $doc) {
                if ($doc->document_required) {
                    if (empty($data['key-file-' . $doc->id]) && !isset($data["doc_" . $doc->id . '_1']))
                        $doc_error[] = $doc->id;
                }
                if (!empty($data['key-file-' . $doc->id])) {
                    $docs[$doc->id] = $data['key-file-' . $doc->id];
                }
            }
            $validator = Validator::make($data, $validationParam);
            if ($validator->passes() && empty($doc_error) && Auth::check()) {
                $response['error'] = 'no';

                $application = new Application();
                $application->ad_id = $ad_id;
                $application->user_id = Auth::id();
                $application->motivation = $data['motivation'];
                $application->sender_id = $sender_id;
                $application->save();
                $ad = Ads::find($application->ad_id);
                $application_id = $application->id;
                foreach ($docs as $key => $files) {
                    foreach ($files as $k => $file) {
                        $application_document = new ApplicationDocuments();
                        $application_document->document_id = $key;
                        $application_document->application_id = $application_id;
                        $application_document->name = $file;
                        $application_document->save();
                        if ($data['save_doc'] == 1) {
                            $docType = DB::table('ad_documents')->where('id', $key)->first()->document_name;
                            DB::table('documents')->insert([
                                ['user_id' => Auth::user()->id, 'name' => $file, 'type' => $docType]
                            ]);
                        }
                    }
                }

                //saved docs
                foreach ($adDocuments as $doc) {
                    for ($i = 1; ; $i++) {
                        $keyString = "doc_" . $doc->id . "_" . $i;
                        if (isset($data[$keyString])) {
                            $appDocInfo = DB::table('documents')->where('id', $data[$keyString])->first();
                            $application_document = new ApplicationDocuments();
                            $application_document->document_id = $doc->id;
                            $application_document->application_id = $application_id;
                            $application_document->name = $appDocInfo->name;
                            $application_document->save();
                        } else {
                            break;
                        }
                    }
                }

                //send Email
                $applicationInfo = DB::table('application')->select('ads.title', 'ads.user_id', 'users.first_name', 'users.email')->join('ads', 'ads.id', 'application.ad_id')->join('users', 'users.id', 'ads.user_id')->where('application.id', $application_id)->first();
                $this->sendMailNewApplication($applicationInfo);


            } else {
                $response['error'] = 'yes';
                if (!isset($data['motivation']) || empty($data['motivation']) || $data['motivation'] == '')
                    $response['errors']['motivation'] = __('backend_messages.give_motivation');
                if (!Auth::check()) {
                    $response['errors']['auth'] = __('backend_messages.must_auth');
                }
                foreach ($doc_error as $key => $value) {
                    $response['errors']['key-file-' . $value] = __('backend_messages.select_document_file');
                }
            }

            return response()->json($response);
        } else {
            return response()->json(array('error' => 'auth'));
        }
    }

    private function sendMailNewApplication($adInfo)
    {
        if (!is_null($adInfo->email)) {

            $subject = i18n('mail.new_application_subject',getLangUser($adInfo->user_id));

            try {

                sendMail($adInfo->email,"emails.users.newApplication",
                ["subject"=>$subject,"userApplicationInfo"=>$adInfo,'lang'=>getLangUser($adInfo->user_id)]);

            } catch (Exception $ex) {

            }
            return true;
        }
    }

    public function uploadFiles($id, Request $request)
    {
        if ($request->file('document_' . $id) != null) {
            $destination = base_path() . '/public/uploads/tempfile/';
            $unique_id = md5($request->file('document_' . $id)->hashName() . now() . $id);
            $upload_response = array();
            $file = $request->file('document_' . $id);
            $file_name = rand(999, 99999) . '_' . $unique_id . '.' . $file->getClientOriginalExtension();
            $user_filename = $file->getClientOriginalName();
            $file->move($destination, $file_name);
            $upload_response['initialPreview'][] = "<img src='/uploads/tempfile/" . $file_name . "' class='file-preview-image' alt='" . $user_filename . "' title='" . $user_filename . "'>";
            $upload_response['initialPreviewConfig'][] = array('caption' => $user_filename, 'size' => $file->getSize(), 'width' => '120px', 'url' => '/ad/deletefile', 'key' => $file_name, 'extra' => array('file_name' => $file_name));

            $upload_response['append'] = TRUE;
            return response()->json($upload_response);

        }
    }

    public function applications($type, $id, Request $request)
    {
        $types = array(
            "en-attente" => "waiting",
            "valide" => "validated",
            "refuse" => "declined"
        );

        $type = $types[$type];
        if (Auth::check()) {
            $user_id = Auth::id();
            $splitUrl = explode("~", $id);
            $ad_id = $splitUrl[count($splitUrl) - 1];
            $ad = Ads::where('id', $ad_id)->where('status', '1')->first();
            if (empty($ad))
                return redirect()->route('user.dashboard');
            if ($ad->scenario_id == 3 || $ad->scenario_id == 4)
                return redirect()->route('user.dashboard');
            $status_id = ApplicationStates::getId($type);
            if ($status_id == -1) {
                $request->session()->flash('error', __('backend_messages.no_ad_found'));
                return redirect()->route('user.dashboard');
            }
            $apps = Application::where('ad_id', $ad_id)->where('status', $status_id)->simplePaginate(10);
            $pagination = $apps->links();
            $ar = array();
            foreach ($apps as $app) {
                $_app = new stdClass();
                $_app->application = $app;
                $_app->user = User::find($app->user_id);
                $_app->sender_ads = $ad;
                $_app->sender = $app->sender_id;
                $_app->documents = ApplicationDocuments::where('application_id', $app->id)->get();
                foreach ($_app->documents as $k => $doc) {
                    $doc->document_id = AdDocuments::find($doc->document_id);
                }
                if ($_app->application->viewed == 0) {
                    $_app->application->viewed = 1;
                    $_app->application->save();
                }
                $ar[] = $_app;
            }
            $apps = $ar;
            return view('applications/page', compact('apps', 'ad', 'type', 'pagination'));
        }
        return redirect()->route('user.dashboard');
    }

    public function submitApplication(Request $request)
    {
        if (Auth::check()) {
            $data = $request->all();
            $id = $data['id'];
            $app = Application::find($id);
            $ad = Ads::find($app->ad_id);
            if ($ad->user_id == Auth::id()) {
                $app->status = ApplicationStates::getId($data['action']);
                $app->save();
                ApplicationStates::saveMessage($app->sender_id, $app->ad_id, $request->message, $app->user_id);
                $logement = Logement::where('ads_id', $app->ad_id)->first();
                $proprietaire = Ads::find($app->ad_id);
                $locataire = DB::table('users')->where('id', $app->user_id)->first();
                if ($logement && $data['action'] == 'validated') {
                    if (isset($locataire->email)) {
                        $dataLocataire['TenantEmail'] = $locataire->email;
                    }
                    $locataireUserProfil = DB::table('user_profiles')->where('user_id', $app->user_id)->first();
                    $dataLocataire['user_id'] = $proprietaire->user_id;
                    $dataLocataire['TenantFirstName'] = $locataire->first_name;
                    $dataLocataire['locataireType'] = 'Particulier';
                    $dataLocataire['TenantMobilePhone'] = $locataireUserProfil->mobile_no;
                    $locataireInfo = LocatairesGeneralInformations::create($dataLocataire);
                    $dataLocation['logement_id'] = $logement->id;
                    $dataLocation['fin'] = $data['dateDebut'];
                    $dataLocation['debut'] = $data['dateFin'];
                    $dataLocation['identifiant'] = 'location ' . $logement->identifiant;
                    $dataLocation['date_payment'] = date("Y-m-d");
                    $dataLocation['loyer_HC'] = $logement->loyer;
                    $dataLocation['locataire_id'] = $locataireInfo->id;
                    $dataLocation['user_id'] = $proprietaire->user_id;
                    Location::create($dataLocation);
                }
                return response()->json(array('error' => 'no'));
            }
            return response()->json(array('error' => 'yes', 'errors' => array('message' => 'You can only validate your received Application')));
        } else {
            return response()->json(array('error' => 'yes', 'errors' => array('all', 'You must be connected')));
        }
    }

    public function edit(Request $request)
    {
        if (Auth::check()) {
            $response = array();
            $data = $request->all();
            $documents = new AdDocuments();
            $ad_id = base64_decode($data['ad_id']);
            $sender_id = base64_decode($data['sender_ad_id']);
            $adDocuments = $documents->where('ad_id', $ad_id)->get();
            $validationParam = array(
                'motivation' => 'required',
            );
            $docs = array();
            $doc_error = array();
            foreach ($adDocuments as $doc) {
                if ($doc->document_required) {
                    if (empty($data['key-file-' . $doc->id]))
                        $doc_error[] = $doc->id;
                }
                if (!empty($data['key-file-' . $doc->id])) {
                    $docs[$doc->id] = $data['key-file-' . $doc->id];
                }
            }
            $validator = Validator::make($data, $validationParam);
            if ($validator->passes() && empty($doc_error) && Auth::check()) {
                $response['error'] = 'no';
                $application = Application::find($data['app_id']);
                $application->motivation = $data['motivation'];
                $application->save();
                $application_id = $application->id;
                foreach ($docs as $key => $files) {
                    foreach ($files as $k => $file) {

                        $application_document = new ApplicationDocuments();
                        $application_document->document_id = $key;
                        $application_document->application_id = $application_id;
                        $application_document->name = $file;
                        $old = ApplicationDocuments::where('application_id', $data['app_id'])->where('name', $file)->first();
                        if (!isset($old) || empty($old))
                            $application_document->save();
                    }
                }
            } else {
                $response['error'] = 'yes';
                if (!isset($data['motivation']) || empty($data['motivation']) || $data['motivation'] == '')
                    $response['errors']['motivation'] = 'You must give a motivation.';
                if (!Auth::check()) {
                    $response['errors']['auth'] = 'you must be authenticated.';
                }
                foreach ($doc_error as $key => $value) {
                    $response['errors']['key-file-' . $value] = 'You must select a file for this document.';
                }
            }
            return response()->json($response);
        } else {
            return response()->json(array('error' => 'auth'));
        }
    }

    public function getApplicationUpdate()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $d = DB::select('SELECT COUNT(app.id) AS nb FROM application AS app WHERE (app.ad_id IN (SELECT id FROM ads AS ads WHERE ads.user_id=?)) AND app.status=0', array($user_id));
            return response()->json(array('error' => 'no', 'data' => $d[0]->nb, 'other' => ApplicationStates::getAllCount($user_id)));
        } else {
            return response()->json(array('error' => 'yes'));
        }

    }
}
