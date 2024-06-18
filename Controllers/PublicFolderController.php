<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;
use stdClass;
use App\User;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image; 

class PublicFolderController extends Controller
{
	public function showPublicFile($file, Request $request)
	{
		
		$folder = $request->segment(1);
		$storagePath = storage_path($folder . "/" . $file);
		if(is_dir($storagePath)) {
			return abort(404);
		}
		if(file_exists($storagePath)) {
			$temp = explode(".", $file);
			$ext = $temp[count($temp) - 1];

			switch($ext)
			{
				case 'css' : 
					$mime = "text/css";
					break;
				case 'xml' :
					$mime = "text/xml";
					break;
				case 'pdf' : 
					$mime = "application/pdf";
					break;
				case 'js' :
					$mime = "application/javascript";
					break;
				case 'svg' :
					$mime = "image/svg+xml";
					break;
			}
			
			if(!empty($mime)) {
				return response(file_get_contents($storagePath), 200)->header('Content-Type', $mime);
			} else {
				return response()->download($storagePath);
			}
			
		} else {
			return redirect("/" . $file);
		}	
	}

	

	public function sitemap(Request $request)
	{
		$folder = $request->segment(1);
		$storagePath = storage_path("sitemap/sitemap.xml.gz");
		return response()->download($storagePath);	
	}
	
}