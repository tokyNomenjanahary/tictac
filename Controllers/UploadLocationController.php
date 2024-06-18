<?php

namespace App\Http\Controllers;

use App\File_location;
use App\TemporaryFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class UploadLocationController extends Controller
{
    public function store(Request $request)
    {
        $file = $request->file('image');
        $filename = hexdec(uniqid()) . '.' . $file->extension();
        $folder = uniqid() . '-' . now()->timestamp;
        Session::put('folder', $folder);
        Session::put('filename',$filename);
        $file->storeAs('files/tmp/',$filename);

        TemporaryFile::create([
            'folder' => $folder,
            'image'  => $filename
        ]);

        return 'success';
    }

    public function destroy(TemporaryFile $temporaryImage){
        $temporaryFolder = Session::get('folder');
        $namefile = Session::get('filename');

        $path = storage_path() . '/app/files/tmp/' . $temporaryFolder . '/' . $namefile;
        if(File::exists($path)){

            File::delete($path);
            rmdir(storage_path('app/files/tmp/'. $temporaryFolder));

            TemporaryFile::where([
                'folder' => $temporaryFolder,
                'image'  => $namefile
            ])->delete();
            return 'success';

        }else{
            return 'not found';
        }


    }
}
