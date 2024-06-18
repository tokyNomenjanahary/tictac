<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;
use stdClass;
use App\User;
use Illuminate\Support\Facades\DB;

class MyDocumentsController extends Controller
{
	public function myDocuments(){
		if(Auth::check()){
			$user_id = Auth::id();
			$savedDocuments = DB::table('documents')
									->where('user_id', Auth::user()->id)
									->get();
			
			return view('documents/page', compact('savedDocuments'));
		} else {
			return redirect()->route('user.dashboard');
		}
	}
	
	public function removeDocuments(Request $request){
		if(Auth::check()){
			$ids = $request->ids;
			foreach($ids as $id) {
				$ancien_name = DB::table('documents')->select("name")->where("id", $id)->first()->name;
				$app_doc = DB::table('application_documents')->select("name")->where("name", $ancien_name)->first();
				if(is_null($app_doc)) {
					$destination = base_path() . '/public/uploads/tempfile/';
					unlink($destination . $ancien_name);
				}
				DB::table('documents')->where('id', $id)->delete();
			}
			
			$request->session()->flash('status', __('document.success_delete'));
			echo json_encode(array("response"=>"done"));
		}
	}
	
	public function addDocument(Request $request)
	{
		
		try {
			$destination = base_path() . '/public/uploads/tempfile/';
			$file = $request->file("documents_file");
			$unique_id = md5(now());
			$upload_response = array();
			$file_name = rand(999,99999) . '_' . $unique_id . '.' . $file->getClientOriginalExtension();
			$user_filename = $file->getClientOriginalName();
			$type = $request->type_document;
			$file->move($destination, $file_name);
			DB::table('documents')->insert([
				['user_id' => Auth::user()->id, 'name' => $file_name, 'type' => $type]
			]);
			$request->session()->flash('status', __('document.success_add'));
		} catch(Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
			$request->session()->flash('error', __('backend_messages.max_allowed_file_size',['size' => ini_get('post_max_size')]));
		}
		return redirect()->route('user.documents');
	}
	
	public function editDocument(Request $request)
	{
		
		try {
			$id = $request->id_doc;
			$destination = base_path() . '/public/uploads/tempfile/';
			$file = $request->file("edit-documents_file");
			$type = $request->edit_type_document;
			if(!is_null($file)) {
				$unique_id = md5(now());
				$upload_response = array();
				$file_name = rand(999,99999) . '_' . $unique_id . '.' . $file->getClientOriginalExtension();
				$file->move($destination, $file_name);
				$user_filename = $file->getClientOriginalName();
				$ancien_name = DB::table('documents')->select("name")->where("id", $id)->first()->name;
				$app_doc = DB::table('application_documents')->select("name")->where("name", $ancien_name)->first();
				if(is_null($app_doc)) {
					unlink($destination . $ancien_name);
				}
				DB::table('documents')->where("id", $id)
									  ->update(
											['type' => $type, 'name' => $file_name]);
			} else {
				DB::table('documents')->where("id", $id)
									  ->update(
											['type' => $type]);
			}
			
			$request->session()->flash('status', __('document.success_edit'));
		} catch(Symfony\Component\HttpFoundation\File\Exception\FileException $exception) {
			$request->session()->flash('error', __('backend_messages.max_allowed_file_size',['size' => ini_get('post_max_size')]));
		}
		return redirect()->route('user.documents');
	}
	
	public function deleteEditDocument(Request $request)
	{
		echo true;
	}
}