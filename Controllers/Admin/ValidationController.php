<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ValidationController extends Controller
{
    private $perpage;

    public function __construct() {
        $this->perpage = config('app.perpage');

        # $this->middleware('adminauth', ['except' => ['getTraduction']]);
    }

    public function index() {
        $pages = DB::table("page_text")->where('valide', '=' ,'2')->orderBy("text_fr", "asc")
                
                ->paginate($this->perpage);
        

        return view('admin.validation.listing', compact('pages'));
    }

    public function valide(Request $request)
    {
        DB::table("page_text")->where('id', $request->id)->update([
            "text_fr"      => $request->text_fr_proposer,
            "text_en"      => $request->text_en_proposer,
            "commentaire"  => $request->commentaire,
            "valide"       => 1,
        ]);

        # Envoi mail

        $request->session()->flash('status', "Validation de traduction avec succès");
        return redirect()->back();
    }

    public function invalide(Request $request)
    {
        DB::table("page_text")->where('id', $request->id)->update([
            "commentaire"  => $request->commentaire
        ]);


        # Envoi mail
        $this->sendMailToValitor($request->traducteur);

        $request->session()->flash('invalide', "Une Notification est envoyé au traducteur concerne");
        return redirect()->back();
    }

    private function sendMailToValitor($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        
        $subject = "Alerte une proposition de traduction";

        sendMail($user->email,'emails.traduction.retour',[
            "subject" => $subject,
            "name" => $user->first_name,
            "lang" => getLangUser($user->id)
        ]);
    }
}
