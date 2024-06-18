<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRegistration;

//use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    //use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request) {
        if ($request->session()->has('AD_INFO')) {
            $request->session()->forget('AD_INFO');
        }
        $request->session()->forget("userInfoRegister");
        return view('auth.login');
    }

	 public function createAccount(Request $request, $ad_id=null) {
        $step = 1;
        if(isset($request->etape))
        {
            $step = $request->etape;
        }
        $register = true;
        if(!is_null($ad_id))
        {
            $request->session()->put("ad_id_redirection", $ad_id);
        }

        return view('auth.login', compact("register", "step"));
    }

    public  function verificationUserDecode(Request $request)
    {
        $user = DB::table("users")->where('email', $request->email)->first();
        $request->email = urldecode($request->email);
        $token = $user->verification_token;
        $UserName = $user->first_name;
        $VerificationLink = url('/users/verify/email') . '/' . $token;

        $subject = __('login.registered_with');

        try {
            sendMail($request->email,'emails.users.registration',[
                "subject"     => $subject." Bailti",
                "MailSubject" => $subject,
                "UserName" => $UserName,
                "userId" => $user->id,
                "VerificationLink" => $VerificationLink,
            ]);
        } catch (Exception $ex) {

        }
        $request->session()->flash('email_verif', "Envoi Email à {$request->email} avec success");
        return back()->withInput($request->all);
    }


    public function login (Request $request){
        /*$secret = "6Ldfd3IUAAAAABvGbwlbUO6VQKH39U5olvlx_sX3";
        // Paramètre renvoyé par le recaptcha
        $response = $_POST['g-recaptcha-response'];
        // On récupère l'IP de l'utilisateur
        $remoteip = $_SERVER['REMOTE_ADDR'];

        $api_url = "https://www.google.com/recaptcha/api/siteverify?secret="
            . $secret
            . "&response=" . $response
            . "&remoteip=" . $remoteip ;*/

        /*$google_check = json_decode(file_get_contents($api_url), true);
        if ($google_check['success'] == true) {*/

            $validator = Validator::make($request->all(), [
                        'email' => 'required|max:100|email',
                        'password' => 'required'
            ]);
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }
            $remember_me = $request->has('remember') ? true : false;

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
                if (Auth::user()->verified == '1') {
                    if (Auth::user()->is_active == '1') {

                        $user_id = Auth::id();
                        /*$userInfoRegister = DB::table("user_registers")->where("user_id", $user_id)->first();
                        if(!is_null($userInfoRegister)) {
                            $infoRegister = (array) $userInfoRegister;
                            $request->session()->put("userInfoRegister", $infoRegister);
                            DB::table("user_registers")->where("user_id", $user_id)->delete();

                            if($infoRegister['scenario_register'] != 3 && $infoRegister['scenario_register'] != 4) {
                                return redirect()->route("searchProfile");
                            } else  {
                                $request->session()->put('ADRESS_INFO', (object) array('address' => $infoRegister['address_register'], 'latitude' => $infoRegister['latitude_register'], "longitude" =>$infoRegister['longitude_register']));
                                if($infoRegister['scenario_register'] == 3) {
                                   return redirect(route("step-address-annonce") . "?type=louer-une-propriete");
                                } else {
                                    return redirect(route("step-address-annonce") . "?type=partager-un-logement");
                                }
                            }
                        }
                        $request->session()->get("userInfoRegister");*/
                        return redirect(generateConnexionReturnUrl());
                    } else {
                        $request->session()->flash('error', __('backend_messages.account_not_active'));
                        Auth::logout();
                        return redirect()->back();
                    }
                } else {
                    $request->session()->flash('error', __('backend_messages.account_not_verified'));
                     $request->session()->flash('email_verif', $request->email);
                    Auth::logout();
                    return redirect()->back();
                }
            } else {
                $request->session()->flash('error', __('backend_messages.wrong_cred'));
                return redirect()->back();
            }

    }

    public function loginPopup (Request $request){
        $validator = Validator::make($request->all(), [
                        'email' => 'required|max:100|email',
                        'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['type' => 'validator','message' => __('email_invalid')]);
            //return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        retransfert_profiles($request->email);
        transfert($request->email,'archive_users','users');
        $remember_me = $request->has('remember') ? true : false;

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me))
        {
            if (Auth::user()->verified == '1') {
                if (Auth::user()->is_active == '1') {
                    if(isset($request->timezone)){
                        session(['user_timezone' => $request->timezone]); // saving timezone in the session
                    }
                    $user_id = Auth::id();
                    $userInfoRegister = DB::table("user_registers")->where("user_id", $user_id)->first();
                    if(!is_null($userInfoRegister)) {
                        $infoRegister = (array) $userInfoRegister;
                        $request->session()->put("userInfoRegister", $infoRegister);
                        DB::table("user_registers")->where("user_id", $user_id)->delete();

                        if($infoRegister['scenario_register'] != 3 && $infoRegister['scenario_register'] != 4) {
                            //return redirect()->route("searchProfile");
                            // return response()->json(['type' => 'route','message' => route("searchProfile")]);
                        } else  {
                            $request->session()->put('ADRESS_INFO', (object) array('address' => $infoRegister['address_register'], 'latitude' => $infoRegister['latitude_register'], "longitude" =>$infoRegister['longitude_register']));
                            if($infoRegister['scenario_register'] == 3) {
                                //return redirect(route("step-address-annonce") . "?type=louer-une-propriete");
                                return response()->json(['type' => 'route','message' => route("step-address-annonce") . "?type=louer-une-propriete"]);
                            } else {
                                //return redirect(route("step-address-annonce") . "?type=partager-un-logement");
                                return response()->json(['type' => 'route','message' => route("step-address-annonce") . "?type=partager-un-logement"]);
                            }
                        }
                    }
                    $request->session()->get("userInfoRegister");
                    //return redirect(generateConnexionReturnUrl());

                    return response()->json(['type' => 'route','message' => redirectDashboard()]);
                    //return response()->json(['type' => 'route','message' => generateConnexionReturnUrl()]);
                } else {
                    //$request->session()->flash('error', __('backend_messages.account_not_active'));
                    Auth::logout();
                    return response()->json(['type' => 'route','message' => route('bloquedUser')]);
                    //return redirect()->back();
                    //return response()->json(['type' => 'error','message' => __('backend_messages.account_not_active')]);
                }
            } else {
                Auth::logout();
                //return redirect()->back();
                return response()->json(['type' => 'error','message' => __('backend_messages.account_not_verified')]);
            }
        } else {
            return response()->json(['type' => 'error','message' => __('backend_messages.wrong_cred')]);
        }
    }

    public function logout() {
        \Session::forget("returnInfos");
        Auth::logout();
        return redirect()->route('home');
    }

    public  function verificationUser(Request $request)
    {
        //si l'emain est vide, on retourne sur la page Réinitialiser mot de passe
        if($request->email){
            $user = DB::table("users")->where('email', $request->email)->first();

            if ($user) {

                $token = $user->verification_token;
                $UserName = $user->first_name;
                $VerificationLink = url('/users/verify/email') . '/' . $token;

                $subject = __('login.registered_with');

                try {
                    sendMail($request->email,'emails.users.registration',[
                        "subject"     => $subject." Bailti",
                        "MailSubject" => $subject,
                        "UserName" => $UserName,
                        "userId" => $user->id,
                        "VerificationLink" => $VerificationLink,

                    ]);
                } catch (Exception $ex) {

                }
                $request->session()->flash('email_verif', "Envoi Email à {$request->email} avec success");
            } else {
                //on ne trouve pas le mail dans le base de donnée
                $request->session()->flash('error', "Cet email {$request->email} n'est pas encore enregistré, Veuillez creer un compte avec cet email");
            }


            return back()->withInput($request->all);
        }else{
           return redirect('/reinitialisation-mot-de-passe');
        }

    }

     public function resendMailVerif(Request $request)
    {

        $user = DB::table("users")->where("email", $request->email)->first();
        $token = $user->verification_token;
        $UserName = $user->first_name;
        $VerificationLink = url('/users/verify/email') . '/' . $token;

        $subject = __('login.registered_with');

       try {
        sendMail($request->email,'emails.users.registration',[
            "subject"     => $subject." Bailti",
            "MailSubject" => $subject,
            "UserName" => $UserName,
            "userId" => $user->id,
            "VerificationLink" => $VerificationLink,
        ]);
        } catch (Exception $ex) {

        }
        return "true";
    }

    public function loginFacebook(Request $request)
    {
        $request->session()->put('loginFacebook', true);
        echo "true";
    }
}
