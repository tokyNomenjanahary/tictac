<?php

namespace App\Http\Controllers;

use App\File_location;
use App\TemporaryFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File ;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index(){
        return view('upload');
    }
    public function store(Request $request){
        $temporaryFolder = Session::get('folder');
        $namefile = Session::get('filename');

        $temporary = TemporaryFile::where('folder',$temporaryFolder)
                                    ->where('image',$namefile)
                                    ->first();

        if($temporary){
            File_location::create([
                'folder' => $temporaryFolder,
                'image'  => $namefile
            ]);

            $path = storage_path() . '/app/files/tmp/' . $temporary->folder . '/'. $temporary->image;
            if(File::exists($path)){
                Storage::move('files/tmp/'.$temporary->folder.'/'.$temporary->image, 'files/'.$temporary->folder.'/'.$temporary->image);
                File::delete($path);
                rmdir(storage_path('app/files/tmp/'.$temporary->folder));
                $temporary->delete();
                return response()->json(['status'=>true,'message'=>'Data success']);
            }
            return response()->json(['status'=>true,'message'=>'Data gagal']);

        }
        return response()->json(['status'=>false,'message'=>'Data gagal']);

    }
}
