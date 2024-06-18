<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Facades\Session;

class AdminLoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function superAdminLogin($user_id, Request $request)
    {
        $authUser = User::where('id', $user_id)->first();
        if(!is_null($authUser)) {
            Auth::login($authUser, true);
            return redirect(generateConnexionReturnUrl());
        }
        return redirect()->back();

    }

    public function login()
    {

        return view('admin.login');

    }

    public function postLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
                    'email' => 'required|max:100|email',
                    'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        $credentials = array(
            'email' => $request->email,
            'password' => $request->password,
            'verified' => '1'
        );
         retransfert_profiles($request->email);
         transfert($request->email,'archive_users','users');

        $ifAdmin = Auth::validate($credentials);

        if ($ifAdmin) {
            $type_traducteur = DB::table('type_user')->where('designation', "Traducteur")->first();
            $type_validateur = DB::table('type_user')->where('designation', "Validateur de traduction")->first();

            $arrUser = User::with(['user_profiles'])
                            ->where('email', $request->email)
                            ->first();

            if (!empty($arrUser) && in_array($arrUser->user_type_id, array('1', '2', '3', '4', '5' , '6', '7', '10', $type_traducteur->id, $type_validateur->id, $arrUser->user_type_id))) {
                Session::put('ADMIN_USER', $arrUser);
                $aujoud_hui = date('Y-m-d');
                 $data =  array();
                $data['last_conection'] = $aujoud_hui;
                DB::table('users')->where('email', $request->email)->update($data);
                if($arrUser->user_type_id == 2 || $arrUser->user_type_id == 4)
                    return redirect()->route('admin.dashboard');
                elseif($arrUser->user_type_id == 5) {
                    $this->sendMailBlog("titre", "url", "connexion");
                    return redirect("/admin/add_new_blog");
                }
                elseif(isPost()) {
                    return redirect()->route("admin.pub_community");
                }
                elseif ($arrUser->user_type_id == $type_traducteur->id){
                    return redirect()->route('admin.pagetextlisting');
                }
                elseif ($arrUser->user_type_id == $type_validateur->id || $arrUser->user_type_id == 10){
                    return redirect()->route('admin.list.traduction.validation');
                }
                elseif ((int) $arrUser->user_type_id == 1){
                    $request->session()->forget('ADMIN_USER');
                    $request->session()->flash('error', 'Your account is a customer account, please contact the admin!');
                    return redirect()->route('admin.login');
                }
                else return redirect()->route('admin.pub_community');
            } else {
                $request->session()->flash('error', 'Wrong credentials. Please, try again!');
                return redirect()->back()->withInput($request->all());
            }
        } else {
            $request->session()->flash('error', 'Wrong credentials. Please, try again!');
            return redirect()->back()->withInput($request->all());
        }
    }

    private function sendMailBlog($titre, $url, $action = "add")
    {

        $subject = "Un article publié";

        try {
            sendMailAdmin("emails.admin.newblog",["subject"=>$subject,"titre"=>$titre,"url"=>$url,"action"=>$action,"ip"=>get_ip()]);

        } catch (Exception $ex) {

        }
        return true;
    }

    public function postLogout(Request $request)
    {
        $request->session()->forget('ADMIN_USER');
        return redirect()->route('admin.login');
    }

}
