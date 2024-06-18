<?php
namespace App\Http\Controllers;

use Auth;
use App\User;
use stdClass;
use App\Documents;
use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;
use App\Http\Models\Ads\AdDetails;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Models\Ads\AdDocuments;
use App\Http\Models\ApplicationStates;
use App\LocatairesGeneralInformations;
use Illuminate\Support\Facades\Validator;
use App\Http\Models\Applications\Application;
use App\Http\Models\Applications\ApplicationDocuments;
Use App\FacebookAPI;

class MyApplicationController extends Controller
{
	public function index($type, Request $request){
		$types = array(
			"en-attente" => "waiting",
			"valide" => "validated",
			"refuse" => "declined"
		);

		if(isset($types[$type]))
		{
			$type = $types[$type];
		}
		if(Auth::check()){
			$uid = Auth::id();
			$Application = new Application();
            $status_id = ApplicationStates::getId($type);
            if($status_id == -1){
                $type = 'tous';
                $apps = $Application->where('user_id', $uid)->orderBy('created_at','DESC')->simplePaginate();
            }
            else
		        $apps = $Application->where('user_id', $uid)->where('status', $status_id)->orderBy('created_at','DESC')->simplePaginate(10);

			$pagination = $apps->links();
			$ar = array();
    		foreach($apps as $app){
                switch($type){
                    case "waiting":
                        $Application->where('user_id',$app->user_id)->where('ad_id',$app->ad_id)->update(['pending_checked'=>1]);
                        break;
                    case "validated":
                        $Application->where('user_id',$app->user_id)->where('ad_id',$app->ad_id)->update(['accepted_checked'=>1]);
                        break;
                    case "declined":
                        $Application->where('user_id',$app->user_id)->where('ad_id',$app->ad_id)->update(['refused_checked'=>1]);
                        break;
                }
    			$_app = new stdClass();
    			$_app->application = $app;
    			$_app->user = User::find($app->user_id);

    			$_app->sender_ads = Ads::find($app->ad_id);

    			$_app->documents = ApplicationDocuments::where('application_id', $app->id)->get();

    			foreach ($_app->documents as $k => $doc) {
    				$doc->document_id = AdDocuments::find($doc->document_id);
    			}
                if($app->scenario_id != 3 || $app->scenario_id != 4){
    			    $ar[] = $_app;
                }
				$app->ad_details = AdDetails::where('ad_id', $app->ad_id)->join('property_types', 'ad_details.property_type_id', '=', 'property_types.id')->first();

    		}

    		$apps = $ar;

    		$axis = 'sent';
            $count_all = ApplicationStates::getAllCount($uid);
			$locataires= LocatairesGeneralInformations::where('user_id', auth::id())->get();
    		return view('applications/my-page', compact('apps', 'type', 'axis', 'pagination', 'count_all','locataires'));
		} else {
            return redirect()->route('user.dashboard');
        }
	}
	public function tranfert_document(Request $request)
	{
		$result = $request->id;
		$obj = json_decode($result);
		$id = $obj->id;
		$doc_name = $obj->document_name;
		$path='/uploads/tempfile/';
		$documents = ApplicationDocuments::where('document_id',$id)->get();
		foreach($documents as $document){
			$name_du_path = $document->name;
			$docFiles = new Documents();
			$docFiles->file_name = $name_du_path;
			$docFiles->user_id = Auth::id();
			$docFiles->path = $path;
			$docFiles->locataire_id = $request->bien;
			$docFiles->nomFichier = $doc_name;
			$docFiles->save();
		}
		return redirect()->route('documents.index')->with('success', __('documents.document_transferé_ave_succès'));
	}
	public function received(Request $request){
		if(Auth::check()){
			$uid = Auth::id();
			$Application = new Application();
			$apps = $Application->whereIn('ad_id', Ads::select('id')->where('user_id', $uid))->simplePaginate(10);
            $pagination = $apps->links();
			$ar = array();
    		foreach($apps as $app){
                DB::table('application')->where('user_id',$app->user_id)->where('ad_id',$app->ad_id)->update(['notif_checked'=>1]);

    			$_app = new stdClass();
    			$_app->application = $app;
    			$_app->user = User::find($app->user_id);
    			$_app->sender_ads = Ads::find($app->ad_id);
    			$_app->documents = ApplicationDocuments::where('application_id', $app->id)->get();
				if(Auth::user()->provider == 'facebook' && $_app->user->provider=='facebook') {
					$creatorFriends =  DB::table('fb_friend_lists')->where('user_id', $_app->user->id)->pluck("fb_friend_id")->toArray();;
					$_app->common_friend = DB::table('fb_friend_lists')->where('user_id', Auth::user()->id)->whereIn("fb_friend_id", $creatorFriends)->get();

				}

    			foreach ($_app->documents as $k => $doc) {
    				$doc->document_id = AdDocuments::find($doc->document_id);
    			}

                if($_app->application->viewed == 0){
                    $_app->application->viewed = 1;
					$_app->application->date_view = date("Y-m-d H:i:s");
                    $_app->application->save();
                }
                $ar[] = $_app;

				$app->ad_details = AdDetails::where('ad_id', $app->ad_id)->join('property_types', 'ad_details.property_type_id', '=', 'property_types.id')->first();

    		}
    		$apps = $ar;
    		$axis = 'received';
    		$type = 'received';
            $count_all = ApplicationStates::getAllCount($uid);
			$locataires= LocatairesGeneralInformations::where('user_id', auth::id())->get();

    		return view('applications/my-page', compact('apps', 'type', 'axis', 'pagination', 'count_all','locataires'));
		} else {
            return redirect()->route('user.dashboard');
        }
	}


    public function edit($id){
		$splitUrl = explode("-", $id);
		if (count(explode("~", $id))>1) {
            $splitUrl=explode("~", $id);
        }
        $id = $splitUrl[count($splitUrl)-1];
        $user_id = Auth::id();
        if(Auth::check()){
            $application = Application::where('id', $id)->first();
            if(isset($application) && !empty($application)){
                if($application->user_id == $user_id){
                    $ad = Ads::find($application->ad_id);
                    if(isset($ad) && !empty($ad)){
                        $ad_docs = AdDocuments::where('ad_id', $ad->id)->get();
                        $ar = array();
                        foreach ($ad_docs as $key => $value) {
                            $app_docs = ApplicationDocuments::where('application_id', $application->id)->where('document_id', $value->id)->get();
                            $e = new stdClass;
                            $e->content = array();
                            foreach ($app_docs as $k => $v) {
                                $e->content[] = $v->name;
                            }
                            $e->content = implode(',', $e->content);
                            $e->element = $value;
                            $ar[] = $e;
                        }
                        $ad_docs = $ar;
                        $layout = 'outer';

                        return view('applications/edit', compact("application", 'ad', 'ad_docs', 'layout'));
                    } else {
						return redirect()->route('user.dashboard');
					}
                } else {
                    return redirect()->route('user.dashboard');
                }
            } else {
                return redirect()->route('user.dashboard');
            }
        } else {
            return redirect()->route('user.dashboard');
        }
    }

	public function common_friend_fb(Request $request)
	{
		$app_user_id = $request->user_id;
		//Amis en commun facebook
		$creatorFriends =  DB::table('fb_friend_lists')->where('user_id', $app_user_id)->pluck("fb_friend_id")->toArray();;
		$friendsFb = DB::table('fb_friend_lists')->where('user_id', Auth::user()->id)->whereIn("fb_friend_id", $creatorFriends)->get();
		foreach($friendsFb as $friend){
			$fbAPI = new FacebookAPI($friend->fb_friend_id);
			$friend->pdp = $fbAPI->profilePicture()->data->url;
		}
		return view('applications/fb-common-friend', compact("friendsFb"));
	}
}
