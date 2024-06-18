<?php

namespace App\Http\Controllers;

use App\Http\Models\FbFriendList;
use App\Libraries\FacebookGraphInterface;

use App\Mail\PersoData;
use App\Repositories\MasterRepository;
use App\User;
use App\UserLifestyles;
use App\UserProfiles;
use App\UserSocialInterests;
use App\UserTypeMusics;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use Socialite;
use Illuminate\Support\Facades\Session as Sessions;

class UsersController extends Controller
{

    /**
     * Method to login user
     * @param Request $request
     * @return type
     */
    private $colocNbPerPage;
    private $logementNbPerPage;
    private $master;

    public function __construct(MasterRepository $master)
    {

        $this->middleware('auth', ['except' => ['postLogin', 'postRegisterSt1', 'postRegisterError', 'postRegisterSt2', 'postRegisterSt3', 'getCities', 'postRegisterSt4', 'redirectToProvider', 'handleProviderCallback', 'findOrCreateUser', 'verifyEmail', 'registerWithAd', 'verifyEtape2']]);
        $this->colocNbPerPage = config('customConfig.maxColoc');
        $this->logementNbPerPage = config('customConfig.maxLogement');
        $this->master = $master;
    }

    public function postLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email_address' => 'required|max:100|email',
            'password' => 'required',
        ]);
        $response = array();

        $remember_me = $request->has('remember_me') ? true : false;

        if ($validator->passes()) {

            if (Auth::attempt(['email' => $request->email_address, 'password' => $request->password], $remember_me)) {

                $response['error'] = 'no';
                $response['redirect_url'] = route('save.all');

            } else {

                $response['error'] = 'yes';
                $response['error_type'] = 'wrong_cred';
                $response['error_message'] = __('backend_messages.wrong_cred');
            }

        } else {

            $response['error'] = 'yes';
            $response['error_type'] = 'validation';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }

        return response()->json($response);

    }

    public function registerWithAd(Request $request)
    {
        $ad_info_session = $request->session()->get('AD_INFO');
        $data = (object) $ad_info_session['step1_data'];
        if (isset($data->email)) {
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
                    'address_register' => $data->address,
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
                    'address_register' => $data->address,
                ]);
                countUsers("insert");

            }

            if ($user) {
                DB::table("users")->where("id", $user->id)->update(["scenario_register" => $data->scenario_id, "address_register" => $data->address]);

                $userInfo = (object) array(
                    "email" => $email,
                    "first_name" => $data->first_name,
                    "last_name" => $data->last_name,
                    "postal_code" => trim($data->postal_code),
                    "mobile_no" => manageMobileNo($data->dial_code, trim($data->mobile_no)),
                );

                $this->sendMailAdminUserRegistration($userInfo);

                $user_profile_array = array();

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
                    sendMail($email,'emails.users.registration',[
                        "subject"     => $subject,
                        "MailSubject" => $subject,
                        "UserName" => $UserName,
                        "userId" => $user->id,
                        "VerificationLink" => $VerificationLink

                    ]);
                } catch (Exception $ex) {

                }
                return redirect()->route("save.all");

            }
        } else {
            return redirect("/");
        }

    }

    public function postRegisterSt1(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|max:100|email|unique:users',
            'password' => ['required'],
        ],
            [
                "email.unique" => __('validator.email_pris'),
            ]
        );

        $response = array();
        $request->session()->put('userInfoRegister', $request->all());

        if ($validator->passes()) {

            $token = time() . str_random(30);
            $response['error'] = 'no';

            $userCheck = DB::table("user_mail")->where("email", $request->email)->first();
            if (is_null($userCheck)) {
                DB::table("user_mail")->insert(["email" => $request->email, "ville_form1" => $request->address_register, 'verification_token' => $token, 'lat' => $request->latitude_register, 'log' => $request->longitude_register, 'scenario' => $request->scenario_register]);
            }else{
                $verif_user = DB::table("users")->where("email", $request->email)->first();
                if(is_null($verif_user)){
                    DB::table("user_mail")->where("email", $request->email)->delete();
                    DB::table("user_mail")->insert(["email" => $request->email, "ville_form1" => $request->address_register, 'verification_token' => $token, 'lat' => $request->latitude_register, 'log' => $request->longitude_register, 'scenario' => $request->scenario_register]);
                }
            }

            $request->session()->put("newUserEmail", $request->email);
            $request->session()->put("newUserPassword", $request->password);
            countValidateForm("form1");

            countShowForm("form2");

            //$response['redirect_url'] = "/creer-compte/etape/2";
            if (getConfig('verification_mail') == 1) {
                $UserName = $request->email;
                $userCheck = User::where("email", $UserName)->first();
                //$checkEtape2=User::select('is_etape_2')->where('email', $UserName)->first();
                $user_id = $userCheck;
                $subject = __('login.registered_with');
                $subject_register=__('login.register_mail');

                $VerificationLink = url('/creer-compte/etape/2') . '/' . $token;

                try {
                    # Envoi Email de VerificationLink
                    sendMail($UserName,'emails.users.registration',[
                        "subject"     => $subject_register,
                        "MailSubject" => $subject,
                        "userId" => $user_id,
                        "VerificationLink" => $VerificationLink
                    ]);
                } catch (Exception $ex) {
                    var_dump($ex);
                }
            }

            if (getConfig('verification_mail') == 0) {
                $response['error'] = 'etape2';

            }
        } else {

            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();

        }

        return response()->json($response);

    }


    public function postRegisterError(Request $request)
    {
        $user = DB::table("user_mail")->where("email", $request->email)->first();
        if (!is_null($user)) {
            $user = DB::table("user_mail")->where("email", $request->email)->delete();
            $response['error'] = 'no';
            return response()->json($response);

        } else {
            $response['error'] = 'yes';
            return response()->json($response);
        }

    }

    public function postRegisterSt2(Request $request)
    {

        /////////////////////////////////////////////

        $validator1 = Validator::make($request->all(), [
            'first_name' => 'required|min:3|max:100',
        ]
        );
        $validator2 = Validator::make($request->all(), [
            'birth_date' => 'required|date_format:d/m/Y',
        ]
        );

        // dd($request->all());
        if ($request->name_societe) {
            $this->registerStep2Society($request);
        }else{

            $validationNumero = DB::table('user_profiles')->where('mobile_no', $request->mobile_no)->first();
            if(!is_null($validationNumero))
            {
                $usernum = DB::table('users')->where('id',$validationNumero->user_id)->first();
                $verificationnumero = $usernum->verified_number;
                      //annuler
                if (!$verificationnumero) {
                    $validationNumero=null;
                    DB::table('user_profiles')->where('mobile_no', $request->mobile_no)->update(['mobile_no'=>null]);
                }
    
            }
    
    
            $response = array();
            if($validator1->fails())
            {
                $response['error'] = 'yes';
                $response['errors'] = ['first_name' => $validator1->errors()->first()];
                return response()->json($response);
            }
            if($validator2->fails())
            {
                $response['error'] = 'yes';
                $response['errors'] = ['birth_date' => $validator2->errors()->first()];
                return response()->json($response);
            }
            if (is_null($validationNumero)) {
                $infoRegister = $request->session()->get("userInfoRegister");
    
                /*$infoRegister['budget'] = $request->budget;*/
                $infoRegister["scenario_register"] = $request->scenario_register;
                $scenario_register = $request->scenario_register;
                $infoRegister['address_register'] = $request->address_register;
                $infoRegister['latitude_register'] = $request->latitude_register;
                $infoRegister['longitude_register'] = $request->longitude_register;
    
                if (isset($request->accept_as)) {
                    $infoRegister['accept_as'] = $request->accept_as;
                }
                $request->session()->put("userInfoRegister", $infoRegister);
                //$scenario_register = $infoRegister["scenario_register"];
    
                $email = $request->email2;
                $passwordUser = $request->session()->get("newUserPassword");
                $request->session()->forget("newUserEmail");
                $request->session()->forget("newUserPassword");
    
                if (Session::has('ad_id_redirection')) {
                    $user = [
                        "first_name" => $request->first_name,
                        "last_name" => $request->last_name,
                        "email" => $email,
                    ];
                    $this->checkAndMatchUser($user);
                }
    
                if ($request->scenario_id == 2 || $request->scenario_id == 4 || $request->scenario_id == 5) {
    
                    $response['error'] = 'no';
                    DB::table("test")->insert(["ip" => "oui"]);
    
                } else {
                    $token = time() . str_random(30);
                    $userCheck = User::where("email", $email)->first();
                    if (!is_null($userCheck)) {
                        $user_id = $userCheck->id;
                        $aujoud_hui = date('Y-m-d');
                        User::where("id", $user_id)->update([
                            'first_name' => trim($request->first_name),
                            'last_name' => trim($request->last_name),
                            'email' => $email,
                            'password' => bcrypt($passwordUser),
                            'verified' => '1',
                            'verification_token' => $token,
                            'budget' => $request->budget,
                            'etape_inscription' => 1,
                            'ip' => get_ip(),
                            'last_conection' => $aujoud_hui,
                        ]);
                        DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 1]);
                        DB::table("users")->where("id", $user_id)->update(['last_conection' => $aujoud_hui]);
                        ///////////////////////
                        /////////////////////
    
                        //////////////////////:
                        ///////////////////////
                        DB::table("users")->where("id", $user_id)->update(['scenario_register' => $scenario_register, "address_register" => $infoRegister['address_register']]);
                        $user = $userCheck;
                    } else {
                        $user = User::create([
                            'first_name' => trim($request->first_name),
                            'last_name' => trim($request->last_name),
                            'email' => $email,
                            'password' => bcrypt($passwordUser),
                            'verified' => '1',
                            'verification_token' => $token,
                            'budget' => $request->budget,
                            'etape_inscription' => 1,
                            'ip' => get_ip(),
                        ]);
                        $aujoud_hui = date('Y-m-d');
                        DB::table("users")->where("id", $user->id)->update(['etape_inscription' => 1]);
                        DB::table("users")->where("id", $user->id)->update(['last_conection' => $aujoud_hui]);
                        ////////////////////////////////////::
                        ///////////////////////////////////
    
                        //////////////////////////////////
                        ////////////////////////////////////
                        DB::table("users")->where("id", $user->id)->update(['scenario_register' => $scenario_register, "address_register" => $infoRegister['address_register']]);
                        $request->session()->put("lastUserId", $user->id);
    
                        $infoRegister = $request->session()->get("userInfoRegister");
                        $infoRegister['user_id'] = $user->id;
                        $request->session()->put("userInfoRegister", $infoRegister);
    
                        saveUserRegister();
                        countUsers("insert");
                    }
    
                    if ($user) {
    
                        $userInfo = (object) array(
                            "email" => $email,
                            "first_name" => $request->first_name,
                            "last_name" => $request->last_name,
                            "mobile_no" => manageMobileNo($request->dial_code, trim($request->mobile_no)),
                        );
    
                        # Envoi des emails aux Admins
                        $this->sendMailAdminUserRegistration($userInfo);
    
                        $user_profile_array = array();
    
                        if (!empty($request->sex)) {
                            $user_profile_array['sex'] = $request->sex;
                        }
    
                        if (!empty($request->iso_code)) {
                            $user_profile_array['iso_code'] = trim($request->iso_code);
                        }
    
                        if (!empty($request->dial_code)) {
                            $user_profile_array['dial_code'] = trim($request->dial_code);
                        }
    
                        if (!empty($request->valid_number)) {
                            $user_profile_array['mobile_no'] = manageMobileNo($request->dial_code, trim($request->valid_number));
                        } else {
                            $user_profile_array['mobile_no'] = manageMobileNo($request->dial_code, trim($request->mobile_no));
                        }
    
                        if (isset($request->school) && !empty($request->school)) {
                            $user_profile_array['school'] = trim($request->school);
                        }
    
                        if (isset($request->profession) && !empty($request->profession)) {
                            $user_profile_array['profession'] = trim($request->profession);
                        }
    
                        if (isset($request->revenus) && !empty($request->revenus)) {
                            $user_profile_array['revenus'] = trim($request->revenus);
                        }
    
                        if (isset($request->origin_country) && !empty($request->origin_country)) {
                            $user_profile_array['origin_country'] = trim($request->origin_country);
                            $user_profile_array['origin_country_code'] = trim($request->origin_country_code);
                        }
    
                        $user_profile_array['birth_date'] = date("Y-m-d", strtotime(convertDateWithTiret($request->birth_date)));
                        if (isset($request->professional_category) && $request->professional_category != "") {
                            $user_profile_array['professional_category'] = $request->professional_category;
                        }
                        if (isset($request->budget)) {
                            $user_profile_array['budget'] = $request->budget;
                        }
    
                        $user_profile_array['user_id'] = $user->id;
                        DB::table("user_profiles")->insert($user_profile_array);
                        $user_id = $user->id;
                        if (!empty($request->type_musics) && count($request->type_musics) > 0) {
                            UserTypeMusics::where('user_id', $user_id)->delete();
                            foreach ($request->type_musics as $type_music) {
                                $userTypeMusic = new UserTypeMusics;
                                $userTypeMusic->user_id = $user_id;
                                $userTypeMusic->music_id = $type_music;
                                $userTypeMusic->save();
                            }
                        }
                        Auth::login($user, true);
    
                        $response['error'] = 'no';
                        if (Session::has('AD_INFO')) {
                            $response['redirect_url'] = route('save.all');
                        } else {
                            if ($infoRegister['scenario_register'] != 3 && $infoRegister['scenario_register'] != 4) {
    
                                countShowForm("form3");
    
                                $response['redirect_url'] = searchUrl($infoRegister['latitude_register'], $infoRegister['longitude_register'], $infoRegister['address_register'], $infoRegister['scenario_register']) . getPathInscription() . "3";
                            } else {
                                $request->session()->put('ADRESS_INFO', (object) array('address' => $infoRegister['address_register'], 'latitude' => $infoRegister['latitude_register'], "longitude" => $infoRegister['longitude_register']));
                                if ($infoRegister['scenario_register'] == 3) {
                                    $response['redirect_url'] = "/louer-une-propriete" . getPathInscription() . "4";
                                } else {
                                    countShowForm("form3");
                                    $response['redirect_url'] = searchUrl($infoRegister['latitude_register'], $infoRegister['longitude_register'], $infoRegister['address_register'], $infoRegister['scenario_register']) . getPathInscription() . "3";
                                }
                            }
    
                        }
    
                        countValidateForm("form2");
                        $response['success_message'] = __('login.registration_successful');
                        $request->session()->flash('status', __('login.registration_successful'));
                        if (getConfig('verification_mail') == 0) {
    
                            $subject = __('login.registered_with');
    
                            $UserName = trim($request->first_name);
                            $VerificationLink = url('/users/verify/email') . '/' . $token;
    
                            try {
                                sendMail($email,'emails.users.registration',[
                                    "subject"     => $subject,
                                    "MailSubject" => $subject,
                                    "UserName" => $UserName,
                                    "userId" => $user->id,
                                    "VerificationLink" => $VerificationLink
                                ]);
                            } catch (Exception $ex) {
    
                            }
    
                        }
    
                        /******si l'email est un parainage*****/
                        $this->manageParainage($email);
    
                    } else {
                        $response['error'] = 'yes';
                        $response['errors'] = ['failedmessage' => 'Not able to save your info, please try again.'];
                    }
                }
    
            } else {
                $response['error'] = 'yes';
                //////////////////////////
                $response['errors'] = ['mobile_no' => i18n('number_phone_exist')];
                ///////////////////////////////
            }
    
            return response()->json($response);
        }

    }
    /*** SQL pour l'ajout de colonne siren sur la table users ***/
    /** ALTER TABLE `users` ADD `siren` VARCHAR(255) NULL AFTER `role_id`; **/
    public function registerStep2Society($request) {

        $email = $request->session()->get("newUserEmail");
        $password = $request->session()->get("newUserPassword");
        $infoRegister = $request->session()->get("userInfoRegister");
        // dd($infoRegister);
        $validationNumero = DB::table('user_profiles')->where('mobile_no', $request->mobile_no_societe)->first();
        if(!is_null($validationNumero))
        {
            $usernum = DB::table('users')->where('id',$validationNumero->user_id)->first();
            $verificationnumero = $usernum->verified_number;
                  //annuler
            if (!$verificationnumero) {
                $validationNumero=null;
                DB::table('user_profiles')->where('mobile_no', $request->mobile_no_societe)->update(['mobile_no'=>null]);
            }

        }

        if (is_null($validationNumero)) {
            $request->session()->forget("newUserEmail");
            $request->session()->forget("newUserPassword");
            $scenario_register = $infoRegister['scenario_register'];

            if (Session::has('ad_id_redirection')) {
                $user = [
                    "first_name" => $request->name_societe,
                    "last_name" => "",
                    "email" => $email,
                ];
                $this->checkAndMatchUser($user);
            }

            $token = time() . str_random(30);
            $userCheck = User::where("email", $email)->first();
            if (!is_null($userCheck)) {
                /*** Verification de l'etape 2 si l'email n'est pas encore checkée ***/
                $virfUserProfil = DB::table("user_profiles")->where('user_id',$userCheck->id)->first();
                if($virfUserProfil){
                    toastr()->warning('Votre inscription sur cet étape est déjà fait!');
                    return response()->json(['success' => true],200);
                    // return redirect()->route('user.dashboard');
                }
                $user_id = $userCheck->id;
                $aujoud_hui = date('Y-m-d');
                User::where("id", $user_id)->update([
                    'first_name' => trim($request->name_societe),
                    'last_name' => "",
                    'email' => $email,
                    'password' => bcrypt($password),
                    'verified' => '1',
                    'verification_token' => $token,
                    'budget' => 0,
                    'etape_inscription' => 1,
                    'ip' => get_ip(),
                    'last_conection' => $aujoud_hui,
                    'siren' => $request->siren,
                ]);
                DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 1]);
                DB::table("users")->where("id", $user_id)->update(['last_conection' => $aujoud_hui]);
                ///////////////////////
                /////////////////////

                //////////////////////:
                ///////////////////////
                DB::table("users")->where("id", $user_id)->update(['scenario_register' => $scenario_register, "address_register" => $infoRegister['address_register']]);
                $user = $userCheck;
            } else {
                $user = User::create([
                    'first_name' => trim($request->name_societe),
                    'last_name' => "",
                    'email' => $email,
                    'password' => bcrypt($password),
                    'verified' => '1',
                    'verification_token' => $token,
                    'budget' => 0,
                    'etape_inscription' => 1,
                    'ip' => get_ip(),
                    'siren' => $request->siren,
                ]);
                $aujoud_hui = date('Y-m-d');
                DB::table("users")->where("id", $user->id)->update(['etape_inscription' => 1]);
                DB::table("users")->where("id", $user->id)->update(['last_conection' => $aujoud_hui]);
                ////////////////////////////////////::
                ///////////////////////////////////

                //////////////////////////////////
                ////////////////////////////////////
                DB::table("users")->where("id", $user->id)->update(['scenario_register' => $scenario_register, "address_register" => $infoRegister['address_register']]);
                $request->session()->put("lastUserId", $user->id);

                $infoRegister = $request->session()->get("userInfoRegister");
                $infoRegister['user_id'] = $user->id;
                $request->session()->put("userInfoRegister", $infoRegister);

                saveUserRegister();
                countUsers("insert");
            }

            if ($user) {

                $userInfo = (object) array(
                    "email" => $email,
                    "first_name" => $request->name_societe,
                    "last_name" => "",
                    "mobile_no" => $request->mobile_no_societe,
                );

                # Envoi des emails aux Admins
                $this->sendMailAdminUserRegistration($userInfo);

                $user_profile_array = array();
                $user_profile_array['user_id'] = $user->id;
                $user_profile_array['sex'] = 1;
                $user_profile_array['iso_code'] = "";
                $user_profile_array['dial_code'] = "";
                $user_profile_array['birth_date'] = $request->society_created;
                $user_profile_array['mobile_no'] = $request->mobile_no_societe;
                $user_profile_array['budget'] = 0;

                DB::table("user_profiles")->insert($user_profile_array);
                $user_id = $user->id;

                Auth::login($user, true);
                
                if (getConfig('verification_mail') == 0) {

                    $subject = __('login.registered_with');

                    $UserName = trim($request->first_name);
                    $VerificationLink = url('/users/verify/email') . '/' . $token;

                    try {
                        sendMail($email,'emails.users.registration',[
                            "subject"     => $subject,
                            "MailSubject" => $subject,
                            "UserName" => $UserName,
                            "userId" => $user->id,
                            "VerificationLink" => $VerificationLink
                        ]);
                    } catch (Exception $ex) {

                    }

                }

                /******si l'email est un parainage*****/
                // $this->manageParainage($email);
                return response()->json(['success' => true],200);
            } else {
                return response()->json(['error' => true],400);
            }
        } else {
            return response()->json(['error' => true,'message'=>'Ce numero est déjà utilisé'],400);
        }
    }



    public function postRegisterPhone(Request $request)
    {

        $user_id = Auth::id();
        $check_user = DB::table('users')->where('id', $user_id)->first();
        $check_siren = $check_user->siren;

        $modif_numero = DB::table('modif_numero')->where('user_id',$user_id)->first();
        if($modif_numero){
            if($modif_numero->verifie_num_modif == 0)
            {
                DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 3]);
                DB::table('modif_numero')->where('id',$user_id)->delete();
                $response['redirect_url'] = '/modifier-profile?step=2';
            }

        }else{
            DB::table("users")->where("id", $user_id)->update(['verified_number' => true]);
            if ($check_siren) {
                DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 3]);
                $response['redirect_url'] = '/tableau-de-bord';
            }else{
                DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 2]);
                //phone_no
                DB::table("user_profiles")->where("user_id", $user_id)->update(['mobile_no' => $request->phone_no]);
                $response = array();
                $infoRegister = $request->session()->get("userInfoRegister");
                if(is_null($infoRegister)){
                    $infoRegister =[
                        'scenario_id'=>'1',
                        'address_register'=>'Paris, Île-de-France, France',
                        'latitude_register'=>'48.8546',
                        'longitude_register'=>'2.34771',
                        'scenario_register'=>'2'
                    ];
    
                }
    
                $request->session()->put("userInfoRegister", $infoRegister);
                $scenario_register = $infoRegister["scenario_register"];
    
                if ($infoRegister['scenario_register'] != 3 && $infoRegister['scenario_register'] != 4) {
                    /*$response['redirect_url'] = route("searchProfile") . getPathInscription() . "3";*/
                    countShowForm("form3");
                    $response['redirect_url'] = searchUrl($infoRegister['latitude_register'], $infoRegister['longitude_register'], $infoRegister['address_register'], $infoRegister['scenario_register']) . getPathInscription() . "3";
                } else {
                    $request->session()->put('ADRESS_INFO', (object) array('address' => $infoRegister['address_register'], 'latitude' => $infoRegister['latitude_register'], "longitude" => $infoRegister['longitude_register']));
                    if ($infoRegister['scenario_register'] == 3) {
                        $response['redirect_url'] = "/louer-une-propriete" . getPathInscription() . "4";
                    } else {
                        countShowForm("form3");
                        $response['redirect_url'] = searchUrl($infoRegister['latitude_register'], $infoRegister['longitude_register'], $infoRegister['address_register'], $infoRegister['scenario_register']) . getPathInscription() . "3";
                    }
                }
            }
        }
        $response['siren'] = $check_siren;
        return response()->json($response);
    }

    public function bot_check_phon(Request $request)
    {

       if (!empty($request->getcode)) {
        if ($request->getcode=='876456') {
            $user_id = Auth::id();
            DB::table("users")->where("id", $user_id)->update(['verified_number' => true]);
            $user_society = DB::table('users')->where('id',$user_id)->select('siren')->first();
            if($user_society){
                DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 3]);
            }else{
                DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 2]);
            }
            //phone_no
            //DB::table("user_profiles")->where("user_id", $user_id)->update(['mobile_no' => $request->phone_no]);
            $response = array();
            $infoRegister = $request->session()->get("userInfoRegister");
            if (is_null($infoRegister)) {
                $infoRegister =[
                'scenario_id'=>'1',
                'address_register'=>'Paris, Île-de-France, France',
                'latitude_register'=>'48.8546',
                'longitude_register'=>'2.34771',
                'scenario_register'=>'2'
            ];
            }
            if(!$user_society){
                $request->session()->put("userInfoRegister", $infoRegister);
                $scenario_register = $infoRegister["scenario_register"];

                if ($infoRegister['scenario_register'] != 3 && $infoRegister['scenario_register'] != 4) {
                    /*$response['redirect_url'] = route("searchProfile") . getPathInscription() . "3";*/
                    countShowForm("form3");
                    $response['redirect_url'] = searchUrl($infoRegister['latitude_register'], $infoRegister['longitude_register'], $infoRegister['address_register'], $infoRegister['scenario_register']) . getPathInscription() . "3";
                } else {
                    $request->session()->put('ADRESS_INFO', (object) array('address' => $infoRegister['address_register'], 'latitude' => $infoRegister['latitude_register'], "longitude" => $infoRegister['longitude_register']));
                    if ($infoRegister['scenario_register'] == 3) {
                        $response['redirect_url'] = "/louer-une-propriete" . getPathInscription() . "4";
                    } else {
                        countShowForm("form3");
                        $response['redirect_url'] = searchUrl($infoRegister['latitude_register'], $infoRegister['longitude_register'], $infoRegister['address_register'], $infoRegister['scenario_register']) . getPathInscription() . "3";
                    }
                }
            }else{
                $response['redirect_url'] = '/tableau-de-bord';
            }
        }
        else
        {
            $response['bot']='continue';
        }
       }
       else
       {
        $response['bot']='continue';
       }

           return response()->json($response);


    }

    public function verify_phone_ajax(Request $request)
    {
        $phone=$request->phone_no;
        $already_use=DB::table("user_profiles")->where('mobile_no',$phone);

        if ($already_use->exists()) {
            //ici le numero a été déja utilisé
            $id=$already_use->first()->user_id;
            $user=DB::table("users")->where('id', $id)->first()->verified_number;
            if($user)
            {
                //ici le numéro est déja utilisé et confirmé

                return response()->json(['can_use'=>false]);
            }
            else
            {
                //ici le numéro est déja utilisé mais pas encore confirmé
                return response()->json(['can_use'=>true]);
            }
        }
        else
        {
            //là, le numéro n'est pas encore utilisé
            return response()->json(['can_use'=>true]);
        }
    }

    private function manageParainage($email, $first_name = null)
    {
        $check = DB::table('user_parainage')->where("email", $email)->first();
        if (!is_null($check)) {

            $userInfo = DB::table('users')->where("id", $check->user_id)->first();
            $this->sendMailParain($userInfo, $first_name);
        }
    }

    private function sendMailParain($userInfos, $first_name = null)
    {
        $subject = __('mail.Parainnage');

        try {
            if (is_null($first_name)) {
                sendMail($userInfos->email,'emails.users.parainnage',[
                    "subject"     => $subject,
                    "MailSubject" => $subject,
                    "UserName" => $userInfos->first_name,
                    "type" => 'parrain',
                    "UserNameFileul" => Auth::user()->first_name,
                   ]);
            } else {
                sendMail($userInfos->email,'emails.users.parainnage',[
                    "subject"     => $subject,
                    "MailSubject" => $subject,
                    "UserName" => $userInfos->first_name,
                    "type" => 'parrain',
                    "UserNameFileul" => $first_name,
                   ]);
            }

        } catch (Exception $ex) {

        }
    }

    private function sendMailAdminUserRegistration($user)
    {


        $subject = __('mail.user_registration');

        try {

            sendMailAdmin('emails.admin.newUserCreated',["subject" => $subject,"user" => $user]);

        } catch (Exception $ex) {

        }
        return true;
    }

    public function postRegisterSt3(Request $request)
    {

        $validator = Validator::make($request->all(), []);

        $validator->sometimes('about_me', 'min:10|max:500',
            function ($input) {
                return $input->about_me != '';
            });

        $validator->sometimes('school_name', 'min:3|max:100',
            function ($input) {
                return $input->school_name != '';
            });

        $response = array();

        if ($validator->passes()) {
            $response['error'] = 'no';
        } else {

            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();

        }

        return response()->json($response);
    }

    public function getCities(Request $request, MasterRepository $master)
    {

        if (!empty($request->country_id)) {
            $cities = DB::table("cities")->where("status", 1)->where("country_id", $request->country_id)->orderBy('city_name')->get();

            if (!empty($cities)) {
                $response = '';
                foreach ($cities as $city) {
                    $response .= '<option value="' . $city->id . '">' . $city->city_name . '</option>';
                }
                echo $response;
            }
        }
    }

    public function postRegisterSt4(Request $request)
    {

        if ($request->file('file_profile_photos')) {
            $file = $request->file('file_profile_photos');
            $destinationPathProfilePic = base_path() . '/storage/uploads/profile_pics/';
            $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPathProfilePic, $file_name);
            pasteLogo($destinationPathProfilePic . $file_name);
            $size = filesize($destinationPathProfilePic . $file_name);
            if ($size > 40000) {
                compressImage($destinationPathProfilePic . $file_name, removeExtension($file_name), $destinationPathProfilePic, 45, 9);
            }

        }

        $response = array();

        $token = time() . str_random(30);

        $user = User::create([
            'first_name' => trim($request->first_name),
            'last_name' => trim($request->last_name),
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verified' => '1',
            'verification_token' => $token,
        ]);
        countUsers("insert");

        if ($user) {

            $user_profile_array = array();
            $user_profile_array['sex'] = $request->sex;
            if (!empty($request->iso_code)) {
                $user_profile_array['iso_code'] = trim($request->iso_code);
            }

            if (!empty($request->dial_code)) {
                $user_profile_array['dial_code'] = trim($request->dial_code);
            }

            if (!empty($request->valid_number)) {
                $user_profile_array['mobile_no'] = trim($request->valid_number);
            } else {
                $user_profile_array['mobile_no'] = trim($request->mobile_no);
            }
            $user_profile_array['postal_code'] = trim($request->postal_code);
            $user_profile_array['birth_date'] = date("Y-m-d", strtotime($request->birth_date));

            if (!empty($file_name)) {
                $user_profile_array['profile_pic'] = $file_name;
            }

            if (!empty($request->about_me)) {
                $user_profile_array['about_me'] = $request->about_me;
            }

            if (!empty($request->school_name)) {
                $user_profile_array['school'] = $request->school_name;
            }

            $user_profile_array['professional_category'] = $request->professional_category;
            $user_profile_array['study_level_id'] = $request->lvl_of_study;

            $user_profile_array['smoker'] = $request->smoker;

            $user_profile_array['country_id'] = $request->country;

            if (!empty($request->city)) {
                $user_profile_array['city_id'] = $request->city;
            }

            if (!empty($request->fb_profile_link)) {
                $user_profile_array['fb_profile_link'] = $request->fb_profile_link;
            }

            if (!empty($request->in_profile_link)) {
                $user_profile_array['in_profile_link'] = $request->in_profile_link;
            }

            $user->user_profiles()->create($user_profile_array);

            if (!empty($request->social_interests) && count($request->social_interests) > 0) {
                $user_to_social_intrsts = array();
                foreach ($request->social_interests as $social_interests) {
                    $user_to_social_intrsts[] = array("social_interest_id" => $social_interests);
                }

                $user->user_social_interests()->createMany($user_to_social_intrsts);
            }

            if (!empty($request->user_lifestyles) && count($request->user_lifestyles) > 0) {
                $user_to_life_styles = array();
                foreach ($request->user_lifestyles as $user_lifestyles) {
                    $user_to_life_styles[] = array("lifestyle_id" => $user_lifestyles);
                }

                $user->user_lifestyles()->createMany($user_to_life_styles);
            }

            Auth::login($user, true);

            $response['error'] = 'no';
            $response['redirect_url'] = route('save.all');

            $subject = __('login.registered_with');

            $UserName = trim($request->first_name) . " " . trim($request->last_name);
            $VerificationLink = url('/users/verify/email') . '/' . $token;
            try {
                sendMail($request->email,'emails.users.registration',[
                    "subject"     => $subject,
                    "MailSubject" => $subject,
                    "UserName" => $UserName,
                    "userId" => $user->id,
                    "VerificationLink" => $VerificationLink

                ]);
            } catch (Exception $ex) {

            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = ['failedmessage' => 'Not able to save your info, please try again.'];
        }

        return response()->json($response);
    }

    public function editProfileInscription(Request $request, MasterRepository $master)
    {

        return $this->editProfile($request, $master);
    }

    public function editProfile(Request $request, MasterRepository $master)
    {
        $studyLevels = $master->getMasters('study_levels');
        $socialInterests = $master->getMasters('social_interests');
        $typeMusics = $master->getMasters('user_type_music');
        $countries = $master->getMasters('countries', ['status' => 1]);
        $sports = $master->getMasters('user_sport');
        $profilPercent = calculProfilPercent();

        $userLifestyles = $master->getMasters('user_lifestyles');

        $user_id = Auth::id();
        $user_profil = DB::table('user_profiles')->where('user_id',$user_id)->first();
        if(is_null($user_profil)){
            DB::table('user_profiles')->insert([
                "user_id"=>$user_id,
                "sex"=>0
            ]);
        }

        $user = User::with(['user_profiles', 'user_social_interests', 'user_lifestyles', 'user_type_musics', 'user_sports'])->where('id', $user_id)->first();

        if (!empty($user->user_social_interests) && count($user->user_social_interests) > 0) {
            foreach ($user->user_social_interests as $social_interest) {
                $social_interests_array[] = $social_interest->social_interest_id;
            }
        } else {
            $social_interests_array = array();
        }

        if (!empty($user->user_sports) && count($user->user_sports) > 0) {
            foreach ($user->user_sports as $sport) {
                $user_sport_array[] = $sport->sport_id;
            }
        } else {
            $user_sport_array = array();
        }

        if (!empty($user->user_profiles) && !empty($user->user_profiles->country_id)) {
            $cities = $master->getMasters('cities', ['status' => 1, 'country_id' => $user->user_profiles->country_id]);
        } else {
            $cities = $master->getMasters('cities', ['status' => 1, 'country_id' => 1]);
        }

        if (!empty($user->user_lifestyles) && count($user->user_lifestyles) > 0) {
            foreach ($user->user_lifestyles as $user_lifestyle) {
                $user_lifestyles_array[] = $user_lifestyle->lifestyle_id;
            }
        } else {
            $user_lifestyles_array = array();
        }

        if (!empty($user->user_type_musics) && count($user->user_type_musics) > 0) {
            foreach ($user->user_type_musics as $user_type_music) {
                $user_musics_array[] = $user_type_music->music_id;
            }
        } else {
            $user_musics_array = array();
        }

        return view('profile/edit_profile', compact('user', 'social_interests_array', 'user_lifestyles_array', 'studyLevels', 'socialInterests', 'countries', 'cities', 'userLifestyles', 'profilPercent', 'user_musics_array', 'typeMusics', 'sports', 'user_sport_array'));

    }

    public function postEditProfileStep1(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3', /*
            'last_name' => 'required|max:100',*/
            'birth_date' => 'required|date_format:d/m/Y',
        ]
        );

        $response = array();

        if ($validator->passes()) {

            $user_id = Auth::id();
            $user = User::find($user_id);

            $user->first_name = trim($request->first_name);
            $user->last_name = trim($request->last_name);

            if ($user->save()) {

                $user_profile = UserProfiles::where('user_id', $user_id)->first();
                $user_profile->sex = $request->sex;

                if (!empty($request->iso_code)) {
                    $user_profile->iso_code = trim($request->iso_code);
                }

                if (!empty($request->dial_code)) {
                    $user_profile->dial_code = trim($request->dial_code);
                }

                if (!empty($request->valid_number)) {
                    if($request->valid_number != $user_profile->mobile_no){
                        DB::table('modif_numero')->insert(['user_id' => $user_id,'verifie_num_modif' => 0]);
                        DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 1]);
                    }
                    $user_profile->mobile_no = manageMobileNo($request->dial_code, trim($request->valid_number));
                } else {
                    $user_profile->mobile_no = manageMobileNo($request->dial_code, trim($request->mobile_no));
                }

                $user_profile->birth_date = date("Y-m-d", strtotime(convertDateWithTiret($request->birth_date)));
                $user_profile->save();
                $response['error'] = 'no';
                $request->session()->flash('status', __('backend_messages.profile_update_success'));
                $response['percent'] = calculProfilPercent();
                $response['redirect_url'] = route('edit.profile');
                $response['message'] = __('backend_messages.profile_update_success');

            } else {
                $response['error'] = 'yes';
                $response['errors'] = ['failedmessage' => __('backend_messages.not_able_to_save')];
            }

        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }

        return response()->json($response);
    }

    public function changePhotoProfile(Request $request)
    {
        $response = [];

        $response['error'] = 'yes';
        $response['message'] = 'Retirer de photo de profil avec Echec';

        $user_id = Auth::id();
        $user_profile = UserProfiles::where('user_id', $user_id)->first();

        if ($request->key && $user_id && $user_profile) {
            $user_profile->profile_pic = null;

            $user_profile->save();
            $response['error'] = 'no';
            $response['message'] = 'Retirer de photo de profil avec sucess';
            $response['id'] = $user_id;
        }

        return response()->json($response);
    }

    public function postEditProfileStep2(Request $request)
    {

        $validator = Validator::make($request->all(), []
        );

        $validator->sometimes('about_me', 'min:10',
            function ($input) {
                return $input->about_me != '';
            });

        $validator->sometimes('school_name', 'min:3',
            function ($input) {
                return $input->school_name != '';
            });

        $response = array();

        if ($validator->passes()) {
            $user_id = Auth::id();
            $user_profile = UserProfiles::where('user_id', $user_id)->first();

            if ($request->hasFile('file_profile_photos')) {
                //on stocke dans un variable
                $file = $request->file('file_profile_photos');
                //capturer son taille
                $size = $request->file("file_profile_photos")-> getSize ();
                // son destination

                $destinationPathProfilePic = base_path() . '/storage/uploads/profile_pics/';
                 //son nom
                //$file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file_nameWithExtension = $request->file('file_profile_photos')->getClientOriginalName();
                //extension
                $extension = $request->file('file_profile_photos')->getClientOriginalExtension();

                $file_name= $file_nameWithExtension.'_'.time().'.'.$extension;



                    //deplacement
                    $file->move($destinationPathProfilePic, $file_name);
                     pasteLogo($destinationPathProfilePic . $file_name);



                if ($size > 100000) {
                    compressImage($destinationPathProfilePic . $file_name,
                        removeExtension($file_name),
                        $destinationPathProfilePic);
                }
                else
                {
                    $file_name= $file_nameWithExtension.'_'.time().'.'.$extension;
                }
            }

            if (!empty($file_name)) {
                $user_profile->profile_pic = $file_name;
            }

            if (!empty($request->about_me)) {
                $user_profile->about_me = $request->about_me;
            }

            if (!empty($request->school_name)) {
                $user_profile->school = $request->school_name;
            }

            if (!empty($request->profession)) {
                $user_profile->profession = $request->profession;
            }

            $user_profile->professional_category = $request->professional_category;
            $user_profile->study_level_id = $request->lvl_of_study;
            $user_profile->pdp_rotate = $request->pdp_rotate;
            $user_profile->save();

            $response['error'] = 'no';

            $request->session()->flash('status', __('backend_messages.profile_update_success'));

            $response['percent'] = calculProfilPercent();
            $response['redirect_url'] = route('edit.profile');
            $response['message'] = __('backend_messages.profile_update_success');

        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }

        return response()->json($response);
    }

    public function postEditProfileStep3(Request $request)
    {

        $response = array();

        $user_id = Auth::id();

        $user_profile = UserProfiles::where('user_id', $user_id)->first();

        $user_profile->smoker = $request->smoker;
        $user_profile->alcool = $request->alcool;
        $user_profile->gay = $request->gay;

        $user_profile->country_id = $request->country;

        if (!empty($request->city)) {
            $user_profile->city = $request->city;
        }

        if (!empty($request->hometown)) {
            $user_profile->hometown = $request->hometown;
        }

        if (!empty($request->fb_profile_link)) {
            $user_profile->fb_profile_link = $request->fb_profile_link;
        }

        $user_profile->save();

        UserSocialInterests::where('user_id', $user_id)->delete();

        if (!empty($request->social_interests) && count($request->social_interests) > 0) {
            foreach ($request->social_interests as $social_interests) {
                $userSocialInterest = new UserSocialInterests;

                $userSocialInterest->user_id = $user_id;
                $userSocialInterest->social_interest_id = $social_interests;
                $userSocialInterest->save();
            }
        }

        if (!empty($request->sports) && count($request->sports) > 0) {
            UserTypeMusics::where('user_id', $user_id)->delete();
            foreach ($request->sports as $sport) {
                $infos = [
                    "user_id" => $user_id,
                    "sport_id" => $sport,
                ];
                DB::table('user_to_sport')->insert($infos);
            }
        }

        UserTypeMusics::where('user_id', $user_id)->delete();

        if (!empty($request->type_musics) && count($request->type_musics) > 0) {
            foreach ($request->type_musics as $type_music) {
                $userTypeMusic = new UserTypeMusics;

                $userTypeMusic->user_id = $user_id;
                $userTypeMusic->music_id = $type_music;
                $userTypeMusic->save();
            }
        }

        $response['error'] = 'no';
        $request->session()->flash('status', __('backend_messages.profile_update_success'));
        $response['percent'] = calculProfilPercent();
        $response['redirect_url'] = route('edit.profile');
        $response['message'] = __('backend_messages.profile_update_success');

        return response()->json($response);
    }

    public function postEditProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:3|max:100',
            'last_name' => 'required|max:100',
            'postal_code' => 'required|min:4|max:10',
            'birth_date' => 'required|date_format:d/m/Y',
        ]
        );

        $validator->sometimes('about_me', 'min:10|max:500',
            function ($input) {
                return $input->about_me != '';
            });

        $validator->sometimes('school_name', 'min:3|max:100',
            function ($input) {
                return $input->school_name != '';
            });

        $response = array();

        if ($validator->passes()) {

            if ($request->file('file_profile_photos')) {
                $file = $request->file('file_profile_photos');
                $destinationPathProfilePic = base_path() . '/storage/uploads/profile_pics/';
                $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPathProfilePic, $file_name);
                pasteLogo($destinationPathProfilePic . $file_name);
                if ($file->getClientSize() > 100000) {
                    compressImage($destinationPathProfilePic . $file_name, removeExtension($file_name), $destinationPathProfilePic);
                }
            }

            $user_id = Auth::id();
            $user = User::find($user_id);

            $user->first_name = trim($request->first_name);
            $user->last_name = trim($request->last_name);

            if ($user->save()) {

                $user_profile = UserProfiles::where('user_id', $user_id)->first();
                $user_profile->sex = $request->sex;

                if (!empty($request->iso_code)) {
                    $user_profile->iso_code = trim($request->iso_code);
                }

                if (!empty($request->dial_code)) {
                    $user_profile->dial_code = trim($request->dial_code);
                }

                if (!empty($request->valid_number)) {
                    $user_profile->mobile_no = trim($request->valid_number);
                } else {
                    $user_profile->mobile_no = trim($request->mobile_no);
                }

                $user_profile->postal_code = trim($request->postal_code);
                $user_profile->birth_date = date("Y-m-d", strtotime(convertDateWithTiret($request->birth_date)));
                if (!empty($file_name)) {
                    $user_profile->profile_pic = $file_name;
                }

                if (!empty($request->about_me)) {
                    $user_profile->about_me = $request->about_me;
                }

                if (!empty($request->school_name)) {
                    $user_profile->school = $request->school_name;
                }

                $user_profile->professional_category = $request->professional_category;
                $user_profile->study_level_id = $request->lvl_of_study;

                $user_profile->smoker = $request->smoker;

                $user_profile->country_id = $request->country;

                if (!empty($request->city)) {
                    $user_profile->city_id = $request->city;
                }

                if (!empty($request->fb_profile_link)) {
                    $user_profile->fb_profile_link = $request->fb_profile_link;
                }

                if (!empty($request->in_profile_link)) {
                    $user_profile->in_profile_link = $request->in_profile_link;
                }

                $user_profile->save();

                UserSocialInterests::where('user_id', $user_id)->delete();

                if (!empty($request->social_interests) && count($request->social_interests) > 0) {
                    foreach ($request->social_interests as $social_interests) {
                        $userSocialInterest = new UserSocialInterests;

                        $userSocialInterest->user_id = $user_id;
                        $userSocialInterest->social_interest_id = $social_interests;
                        $userSocialInterest->save();
                    }
                }

                UserLifestyles::where('user_id', $user_id)->delete();
                if (!empty($request->user_lifestyles) && count($request->user_lifestyles) > 0) {

                    foreach ($request->user_lifestyles as $user_lifestyles) {
                        $userLifeStyle = new UserLifestyles;

                        $userLifeStyle->user_id = $user_id;
                        $userLifeStyle->lifestyle_id = $user_lifestyles;
                        $userLifeStyle->save();
                    }
                }

                $response['error'] = 'no';
                $request->session()->flash('status', __('backend_messages.profile_update_success'));

                $response['redirect_url'] = route('edit.profile');

            } else {
                $response['error'] = 'yes';
                $response['errors'] = ['failedmessage' => __('backend_messages.not_able_to_save')];
            }

        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }

        return response()->json($response);
    }

    public function changeEmail(Request $request)
    {

        if ($request->post()) {

            $validator = Validator::make($request->all(), [
                'email' => ['required',
                    'max:100',
                    'email',
                    Rule::exists('users')->where(function ($query) {
                        $query->where('id', Auth::id());
                    })],
                'newemail' => 'required|max:100|email|unique:users,email',
            ],
                [
                    'email.required' => 'The current email address is required.',
                    'email.max' => 'The current email address may not be greater than 100 characters.',
                    'email.email' => 'The current email address must be a valid email address.',
                    'email.exists' => 'The selected current email address is not correct.',
                    'newemail.required' => 'The new email address is required.',
                    'newemail.max' => 'The new email address may not be greater than 100 characters.',
                    'newemail.email' => 'The new email address must be a valid email address.',
                    'newemail.unique' => 'The new email address is already taken.',
                ]
            );

            if ($validator->fails()) {

                return redirect()->route('edit.changeemail')->withErrors($validator)->withInput($request->all());

            } else {

                $user_id = Auth::id();
                $user = User::find($user_id);

                $user->email = trim($request->newemail);

                if ($user->save()) {
                    return redirect()->route('edit.changeemail')->with('message', 'Your email has been successfully changed.')->with('alert-class', 'alert-success');
                } else {
                    return redirect()->route('edit.changeemail')->with('message', 'Not able to change your email, please try again.')->with('alert-class', 'alert-danger')->withInput($request->all());
                }

            }
        }

        return view('profile/change_email');

    }

    public function changePassword(Request $request)
    {

        if ($request->post()) {

            $validator = Validator::make($request->all(),
                [
                    'password' => [
                        'required',
                        'max:100',
                        'confirmed',
                    ],
                    'password_confirmation' => 'required',
                ]
            );

            if ($validator->fails()) {
                return redirect()->route('edit.changepwd')->withErrors($validator)->withInput($request->all());

            } else {

                $user_id = Auth::id();
                $user = User::find($user_id);

                $user->password = bcrypt($request->password);

                if ($user->save()) {
                    $request->session()->flash('status', __('profile.success_changed_password'));
                    return redirect()->route('edit.changepwd')->with('message', 'Your password has been successfully changed.')->with('alert-class', 'alert-success');
                } else {
                    return redirect()->route('edit.changepwd')->with('message', 'Not able to change your password, please try again.')->with('alert-class', 'alert-danger')->withInput($request->all());
                }

            }

        }

        $profilPercent = calculProfilPercent();
        return view('profile/change_password', compact("profilPercent"));

    }

    public function deleteProfile(Request $request)
    {

        $user_id = Auth::id();
        $user = User::find($user_id);

        if (!empty($user)) {

            DB::table("ads")->where('user_id', $user->id)->update([
                "admin_approve" => 0,
            ]);
            DB::table("user_mail")->where('email', $user->email)->delete();
            DB::table('users')->where("id", $user_id)->delete();
            DB::table('user_profiles')->where("user_id", $user_id)->delete();
            countUsers("delete");
            /*$user->verified = 0;
            $user->is_active = 0;
            $user->email = $user->email . "_deactivate";
            $user->save();*/
            $request->session()->flash('suppression', 'Compte bien supprimé, Bye!');
            Auth::logout();
            return redirect()->route('home');
        }

    }

    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        if ($provider == 'facebook') {
            return Socialite::driver($provider)->fields([
                'first_name', 'last_name', 'email', 'gender', 'birthday', 'location', 'link', 'hometown', 'friends',
            ])->scopes([
                'email', 'user_birthday', 'user_gender', 'user_location', 'user_link', 'user_hometown', 'user_friends',
            ])->redirect();
        } else {
            return Socialite::driver($provider)->redirect();
        }
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {

        try {
            if ($provider == 'facebook') {
                $user = Socialite::driver('facebook')->fields([
                    'first_name', 'last_name', 'email', 'gender', 'birthday', 'location', 'link', 'hometown', 'friends',
                ])->stateless()->user();
            } else {
                $user = Socialite::driver($provider)->user();
            }

        } catch (\Exception $e) {
            if (Session::has('AD_INFO') && isset($ad_info_session['step1_data'])) {
                $ad_info_session = Session::get('AD_INFO');
                if ($ad_info_session['step1_data']['scenario_id'] == '1') {
                    return redirect()->route('rent.property');
                } else if ($ad_info_session['step1_data']['scenario_id'] == '2') {
                    return redirect()->route('rent.accommodation');
                } else if ($ad_info_session['step1_data']['scenario_id'] == '3') {
                    return redirect()->route('looking.property');
                } else if ($ad_info_session['step1_data']['scenario_id'] == '4') {
                    return redirect()->route('looking.accommodation');
                } else if ($ad_info_session['step1_data']['scenario_id'] == '5') {
                    return redirect()->route('looking.partner');
                }
            } else {
                Session::flash('error', __('backend_messages.error_occured'));
                return redirect()->route('login');
            }
        }

        if (Session::has('ad_id_redirection') && $provider == "facebook") {
            $ad_id = Session::get('ad_id_redirection');
            $adToRedirect = $this->matchAdToUser($ad_id, $user);

        }

        $isNew = false;
        $authUser = $this->findOrCreateUser($user, $provider, $isNew);
        $user_id = $authUser->id;
        if ($isNew) {
            /******si l'email est un parainage*****/
            $this->manageParainage($authUser->email, $authUser->first_name);
            Session::put("registerFb", true);
            Session::put("authUser", $authUser);
        } else {
            Auth::login($authUser, true);
        }

        if ($provider == "google") {
            saveGooglePdp($user->avatar, $authUser->id);
        }

        if ($provider == "linkedin") {
            saveLinkedinPdp($user->avatar, $authUser->id);
        }

        if ($provider == 'facebook') {

            $this->saveSocialProfilInfo($user, $authUser->id);
            saveFbPdp($user->id, $authUser->id);
            $objFacebook = new FacebookGraphInterface();
            $friendsListResponse = $objFacebook->getFriendsList($user->id, $user->token);

            if ($friendsListResponse['status'] == true) {
                $FbFriendList = new FbFriendList;
                $FbFriendList->where('user_id', $user_id)->delete();
                if (!empty($friendsListResponse['friends_list'])) {
                    foreach ($friendsListResponse['friends_list'] as $friend_list) {
                        $userFriend = DB::table('users')->where('provider_id', $friend_list['id'])->first();
                        if (!is_null($userFriend) && !is_null($user_id)) {
                            $FbFriendList = new FbFriendList;

                            $FbFriendList->user_id = $user_id;
                            $FbFriendList->fb_friend_name = $friend_list['name'];
                            $FbFriendList->fb_friend_id = $friend_list['id'];

                            $FbFriendList->save();
                            $this->manageUserFriends($friend_list['id'], $user);
                        }

                    }
                }
            }

        }

        if (isset($adToRedirect) && !is_null($adToRedirect)) {
            return redirect(adUrl($ad_id));
        }

        if (Session::has('AD_INFO')) {
            return redirect()->route('save.all');
        } else {
            if ($isNew) {

                Session::put("provider", $provider);

                if (!is_null(getInfoRegister('scenario_register')) && getInfoRegister('scenario_register') != 3 && getInfoRegister('scenario_register') != 4) {
                    DB::table("users")->where("id", $authUser->id)->update(['scenario_register' => getInfoRegister('scenario_register'), 'address_register' => getInfoRegister('address_register')
                    ,'etape_inscription' => "1"]);

                    Auth::login($authUser, true);
                    Session::put('ADRESS_INFO', (object) array('address' => getInfoRegister('address_register'), 'latitude' => getInfoRegister('latitude_register'), "longitude" => getInfoRegister('longitude_register')));

                    return redirect("/facebook-inscription/etape2");
                    /*Auth::login($authUser, true);
                return redirect(route("searchProfile") . getPathInscription());*/
                } else {
                    DB::table("users")->where("id", $authUser->id)->update(['scenario_register' => getInfoRegister('scenario_register'), 'address_register' => getInfoRegister('address_register')
                    ,'etape_inscription' => "1"]);
                    Auth::login($authUser, true);
                    /*return redirect(generateInterRegisterReturnUrl()); */
                    Session::put('ADRESS_INFO', (object) array('address' => getInfoRegister('address_register'), 'latitude' => getInfoRegister('latitude_register'), "longitude" => getInfoRegister('longitude_register')));

                    return redirect("/facebook-inscription/etape2");
                }
                Auth::login($authUser, true);
                return redirect(generateInterRegisterReturnUrl($provider));

            } else {
                return redirect(generateConnexionReturnUrl());
            }
        }

    }

    private function manageUserFriends($friend_id, $user)
    {
        $friendInfo = DB::table('users')->where('provider_id', $friend_id)->first();
        $friendCheck = DB::table("fb_friend_lists")->where("user_id", $friendInfo->id)->where("fb_friend_id", $user->id)->first();
        if (is_null($friendCheck)) {
            $FbFriendList = new FbFriendList;
            $FbFriendList->user_id = $friendInfo->id;
            $FbFriendList->fb_friend_name = trim($user->user['first_name']) . " " . trim($user->user['last_name']);
            $FbFriendList->fb_friend_id = $user->id;
            $FbFriendList->save();
        }
    }

    public function modifyUserObject($user)
    {
        $name = $user->user['name'];
        $tabName = explode(" ", $name);
        $user->user['last_name'] = $tabName[count($tabName) - 1];
        array_pop($tabName);
        $user->user['first_name'] = implode(" ", $tabName);
        return $user;
    }

    public function checkAndMatchUser($user)
    {
        $first_name = strtolower(trim($user['first_name']));
        $last_name = strtolower(trim($user['last_name']));
        $userDb = DB::table('users')->whereRaw("LOWER(first_name) = '" . $first_name . "'")->whereRaw("LOWER(last_name) = '" . $last_name . "'")->first();
        if (!is_null($userDb)) {
            if (empty($userDb->email)) {
                DB::table("users")->where("id", $userDb->id)->update(
                    [
                        "email" => $user['email'],
                        "verified" => '1',
                    ]
                );
            }

            DB::table("ads")->where("user_id", $userDb->id)->update(["admin_approve" => "1"]);
            Session::forget('ad_id_redirection');
        }

    }

    public function matchAdToUser($ad_id, $user)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        $ad = DB::table("ads")->leftJoin("users", "ads.user_id", "users.id")->join("ad_details", "ads.id", "ad_details.ad_id")->join("property_types", "property_types.id", "ad_details.property_type_id")->where("ads.id", $ad_id)->first();

        $first_name = trim($user->user['first_name']);
        $last_name = trim($user->user['last_name']);
        $userName = trim($first_name . " " . $last_name);
        if (strtolower($userName) == trim(strtolower($ad->first_name) . " " . strtolower($ad->last_name))) {
            if (!is_null($authUser)) {
                DB::table("ads")->where("id", $ad_id)->update(
                    ["user_id" => $authUser->id]
                );
                DB::table("users")->where("id", $ad->user_id)->delete();

            } else {
                DB::table("users")->where("id", $ad->user_id)->update(
                    [
                        "email" => $user->email,
                        "provider_id" => $user->id,
                        "first_name" => $first_name,
                        "last_name" => $last_name,
                        "verified" => '1',
                    ]
                );

                if (!empty($user->user['gender'])) {
                    if ($user->user['gender'] == 'male') {
                        $sex = '0';
                    } else {
                        $sex = '1';
                    }
                } else {
                    $sex = '0';
                }
                DB::table("user_profiles")->where("user_id", $ad->user_id)->update(
                    ["sex" => $sex]
                );
            }

            return $ad;
        }
    }

    public function getSex($sex)
    {
        $sexs = ["male" => 1, "female" => 1];
        return $sexs[$sex];
    }

    public function isNewFbAccount($user)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            return false;
        }
        return true;
    }
    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider, &$new)
    {
        $authUser = User::where('provider_id', $user->id)->first();
        if ($authUser) {
            $authUser->remember_token = $user->token;
            $authUser->save();
            return $authUser;
        }
        $new = true;
        if ($provider == 'facebook') {
            if (!empty($user->user['first_name']) && !empty($user->user['last_name'])) {
                $first_name = trim($user->user['first_name']);
                $last_name = trim($user->user['last_name']);
            } else {
                $name_ex = explode(' ', $user->user['name']);
                $first_name = trim($name_ex[0]);
                if (!empty($name_ex[1])) {
                    $last_name = trim($name_ex[1]);
                } else {
                    $last_name = null;
                }
            }
            if (isset($user->user['email'])) {
                $email = $user->user['email'];
            }
        } else if ($provider == 'linkedin') {
            $first_name = trim($user->user['firstName']);
            $last_name = trim($user->user['lastName']);
            $email = $user->user['emailAddress'];
        } else if ($provider == 'google') {
            $name_ex = explode(' ', $user->name);
            $first_name = trim($name_ex[0]);
            if (!empty($name_ex[1])) {
                $last_name = trim($name_ex[1]);
            } else {
                $last_name = null;
            }

            $email = $user->email;

        }
        if (isset($user->user['email']) && empty(checkEmailFb(($user->user['email'])))){
            $user_created = User::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'provider' => $provider,
                'provider_id' => $user->id,
                'verified' => '1',
                'ip' => get_ip(),
                "remember_token" => $user->token,
            ]);
            countUsers("insert");
        } else {
            $user_created = User::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'provider' => $provider,
                'provider_id' => $user->id,
                'verified' => '1',
                'ip' => get_ip(),
                "remember_token" => $user->token,
            ]);
            countUsers("insert");
        }

        if ($user_created) {
            if (!empty($user->user['gender'])) {
                if ($user->user['gender'] == 'male') {
                    $sex = '0';
                } else {
                    $sex = '1';
                }
            } else {
                $sex = '0';
            }
            $user_created->user_profiles()->create([
                'sex' => $sex,
            ]);
        } else {

        }

        return $user_created;

    }

    //connexion via fb, enregistrer les infos social automatiquement
    protected function saveSocialProfilInfo($user, $user_id)
    {
        $infoUsers = array();
        if (isset($user->user['birthday']) && !empty($user->user['birthday'])) {
            $infoUsers['birth_date'] = date('Y-m-d', strtotime($user->user['birthday']));
        }
        if (isset($user->user['gender']) && !empty($user->user['gender'])) {
            $infoUsers['sex'] = ($user->user['gender'] == 'male') ? "0" : "1";
        }

        if (isset($user->user['link']) && !empty($user->user['link'])) {
            $infoUsers['fb_profile_link'] = $user->user['link'];
        }

        if (isset($user->user['location']) && !empty($user->user['location'])) {
            $infoUsers['city'] = $user->user['location']['name'];

        }

        if (isset($user->user['hometown']) && !empty($user->user['hometown'])) {
            $infoUsers['hometown'] = $user->user['hometown']['name'];
        }

        if (!empty($infoUsers)) {
            $userCheck = DB::table("user_profiles")->where("user_id", $user_id)->first();
            if (!is_null($userCheck)) {
                DB::table("user_profiles")->where("user_id", $user_id)->update($infoUsers);
            } else {
                $infoUsers['user_id'] = $user_id;
                DB::table("user_profiles")->insert($infoUsers);
            }
        }

    }

    //gerer la ville de l'user fb, ajouter dans bdd si non present
    protected function manageUserCity($city, $country_id)
    {
        if (!empty($city)) {
            $check = DB::table('cities')->where("city_name", trim($city))->first();
            if (!is_null($check)) {
                return $check->id;
            } else {
                DB::table('cities')->insert(['city_name' => $city, 'country_id' => $country_id, 'status' => 1]);
                return DB::getPdo()->lastInsertId();
            }
        }
    }

    //gerer le pays de l'user fb, ajouter dans bdd si non present
    protected function manageUserCountry($country)
    {
        if (!empty($country)) {
            $check = DB::table('countries')->where("country_name", trim($country))->first();
            if (!is_null($check)) {
                return $check->id;
            } else {
                DB::table('countries')->insert(['country_name' => $country, 'status' => 1]);
                return DB::getPdo()->lastInsertId();
            }
        } else {
            $check = DB::table('countries')->where("country_name", "Autres")->first();
            return $check->id;
        }
    }

    private function extractUserCityAndCountry($address)
    {
        $tab = explode(',', $address);
        $result = array();
        if (count($tab) == 3) {
            $result['city'] = $tab[1];
            $result['country'] = $tab[2];
        } elseif (count($tab) == 2) {
            $result['city'] = $tab[0];
            $result['country'] = $tab[1];
        } elseif (count($tab) == 1) {
            $result['city'] = $tab[0];
            $result['country'] = null;
        } else {
            $result['city'] = null;
            $result['country'] = null;
        }
        return $result;
    }



    /**
     * Method to verify Email
     * @param type $token
     */
    public function verifyEmail($token, Request $request)
    {

        if (!empty($token)) {
            $user = User::where('verification_token', $token)->first();
            if (!empty($user) && !is_null($user) > 0) {
                $user->verified = '1';
                $user->verification_token = '';
                $user->save();
                $request->session()->flash('status', __('backend_messages.email_verified_only'));
                $request->session()->flash('success_register', 'true');
            } else {
                $request->session()->flash('error', __('backend_messages.error_occured'));
            }
        } else {
            $request->session()->flash('error', __('backend_messages.error_occured'));
        }

        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        } else {
            if (isset($user)) {
                Auth::login($user, true);
                return redirect()->route('user.dashboard');
            }
            return redirect()->route('login');
        }
    }

    /**
     * Method to verify Email
     * @param type $token
     */
    public function verifyEtape2($token, Request $request)
    {
        $user = DB::table("user_mail")->where("verification_token", $token)->first();
        if (!empty($token)) {

            if (!empty($user) && !is_null($user) > 0) {

                DB::table("user_mail")->where('email', $user->email)->update(['verified' => '1']);
                $request->session()->flash('status', __('backend_messages.email_verified_only'));
                $request->session()->flash('success_register', 'true');

            } else {
                if($user != null){
                    DB::table("user_mail")->where('email', $user->email)->delete();
                    countUsers("delete");
                }
                // $request->session()->flash('error', __('backend_messages.error_occured'));
                // return view('auth.register_step_error');
                $request->session()->flash('stepAccount', "1");
                //Afficher l'erreur si la session est expirer
                return view('auth.error_register');
            }
        } else {
            $request->session()->flash('error', __('backend_messages.error_occured'));
        }

        $socialInterests = $this->master->getMasters('social_interests');
        $typeMusics = $this->master->getMasters('user_type_music');
        $sports = DB::table("user_sport")->orderBy("label")->get();
        $request->session()->flash('stepAccount', "2");
        $step = 2;
        $email = $user->email;
        if (!empty($user->lat)) {
            $lat = $user->lat;
        } else {
            $lat = 48.8546;
        }
        if (!empty($user->log)) {
            $log = $user->log;
        } else {
            $log = 2.34771;
        }
        if (!empty($user->scenario)) {
            $scenario = $user->scenario;
        } else {
            $scenario = 2;
        }
        if (!empty($user->ville_form1)) {
            $ville_form1 = $user->ville_form1;
        } else {
            $ville_form1 = "Paris, Île-de-France, France";
        }

        return view('auth.register_step_1ok', compact('step', 'socialInterests', 'typeMusics', 'sports', 'email', 'lat', 'log', 'scenario', 'ville_form1'));
    }

    /**
     * Method to verify Email
     * @param type $token
     */
    public function verifyEmailValider($token, Request $request)
    {

        if (!empty($token)) {
            $user = User::where('verification_token', $token)->first();
            if (!empty($user) && count($user) > 0) {
                $user->verified = '1';
                $user->verification_token = '';
                $user->save();
                $request->session()->flash('status', __('backend_messages.email_verified_only'));
                $request->session()->flash('success_register', 'true');
            } else {
                $request->session()->flash('error', __('backend_messages.error_occured'));
            }
        } else {
            $request->session()->flash('error', __('backend_messages.error_occured'));
        }

        if (Auth::check()) {
            return redirect()->route('user.dashboard');
        } else {
            if (isset($user)) {
                Auth::login($user, true);
                return redirect()->route('user.dashboard');
            }
            return redirect()->route('login');
        }
    }
    public function rate(Request $request)
    {
        $user_id = Auth::id();
        $editAction = $request->action;
        if (empty($editAction)) {
            DB::table('user_avis')->insert(
                ["user_id" => $user_id, 'notes' => $request->notes, 'comments' => $request->comment]
            );
        } else {
            DB::table('user_avis')->where("user_id", $user_id)->update(
                ['notes' => $request->notes, 'comments' => $request->comment]
            );
        }
        $request->session()->flash('status', __('header.rating_message'));
        echo "true";

    }
    public function testMail()
    {

        $subject = __('login.registered_with');

        $UserName = "Rishi Sharma";
        $VerificationLink = "www.gmail.com";

        sendMail('rishi1@yopmail.com','emails.users.registration',[
            "subject"     => $subject,
            "MailSubject" => $subject,
            "UserName" => $UserName,
            "VerificationLink" => $VerificationLink

        ]);
    }

    public function getPersonalData(Request $request)
    {
        $infosPerso = DB::table('user_profiles')->where("user_id", Auth::id())->first();
        $fichier = fopen(storage_path("persoData/" . $request->mail . ".json"), 'w+');

        $sendInfos = array(
            'name' => Auth::user()->first_name,
            'first_name' => Auth::user()->last_name,
            'email' => Auth::user()->email,
            'mobile_no' => (!is_null($infosPerso) && !is_null($infosPerso->mobile_no)) ? $infosPerso->mobile_no : null,
            'birth_date' => (!is_null($infosPerso) && !is_null($infosPerso->birth_date)) ? $infosPerso->birth_date : null,
            'postal_code' => (!is_null($infosPerso) && !is_null($infosPerso->postal_code)) ? $infosPerso->postal_code : null,
        );
        fwrite($fichier, json_encode($sendInfos));
        fclose($fichier);
        $this->sendMailPersoData($request->mail);
        unlink(storage_path("persoData/" . $request->mail . ".json"));
        echo true;
    }

    private function sendMailPersoData($mail)
    {
        $subject = __('mail.perso_data_subject');

        try {

            Mail::to($mail)->send(new PersoData($subject, $mail . '.json'));
            $email_moin = $email_moin - 1;

        } catch (Exception $ex) {

        }
        return true;
    }

    public function saveRaisonDelete(Request $request)
    {
        $raison = $request->raison;
        DB::table('raison_account_delete')->insert([
            "user_id" => Auth::id(),
            'message' => $raison,
            "email" => Auth::user()->email,
        ]);
        echo "true";
    }

    public function creerProfilRecherche(Request $request, MasterRepository $master)
    {
        $socialInterests = $master->getMasters('social_interests');
        $typeMusics = $master->getMasters('user_type_music');
        $guarAsked = $master->getMasters('guarantees');
        $sports = DB::table("user_sport")->orderBy("label")->get();
        //dd($sports);
        return view("profile.profile-recherche", compact("socialInterests", "typeMusics", "guarAsked", "sports"));
    }

    public function chooseDesign($type_design)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);
        $user->type_design = $type_design;
        $user->save();
        return redirect()->back();
    }

    public function saveProfilRecherche(Request $request)
    {
        $user_id = Auth::id();
        $user_profile = UserProfiles::where('user_id', $user_id)->first();
        DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 3]);

        $request_file = $request->file('file_profile_photos');
        if ($request_file) {
            if ($request_file->isValid()) {
                $file = $request_file;

                $destinationPathProfilePic = base_path() . '/storage/uploads/profile_pics/';

                $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                $file->move($destinationPathProfilePic, $file_name);

                pasteLogo($destinationPathProfilePic . $file_name);

                $size = filesize($destinationPathProfilePic . $file_name);
                if ($size > 5120) {
                    compressImage($destinationPathProfilePic . $file_name, removeExtension($file_name), $destinationPathProfilePic, 45, 9);
                }
            }else{
                Sessions::flash('error', __('property.error_import_image'));
            // DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 2 ]);
                return redirect()->back();
            }

        }
        // else{
        //   //  dd($request);
        //    // DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 2 ]);
        //     return redirect()->back();
        // }

        if (!empty($file_name)) {
            $user_profile->profile_pic = $file_name;
        }
        if (!empty($request->smoker)) {
            if ($request->smoker >= 0) {
                $user_profile->smoker = $request->smoker;
            }
        }
        if (!empty($request->revenus)) {
            $user_profile->revenus = $request->revenus;
        }
        if (!empty($request->gay)) {
            if ($request->gay >= 0) {
                $user_profile->gay = $request->gay;
            }
        }
        if (!empty($request->alcool)) {
            if ($request->alcool >= 0) {
                $user_profile->alcool = $request->alcool;
            }
        }

        $user_profile->save();

        if (!empty($request->sports) && count($request->sports) > 0) {
            UserTypeMusics::where('user_id', $user_id)->delete();
            foreach ($request->sports as $sport) {
                $infos = [
                    "user_id" => $user_id,
                    "sport_id" => $sport,
                ];
                DB::table('user_to_sport')->insert($infos);
            }
        }

        if (!empty($request->type_musics) && count($request->type_musics) > 0) {
            UserTypeMusics::where('user_id', $user_id)->delete();
            foreach ($request->type_musics as $type_music) {
                $userTypeMusic = new UserTypeMusics;

                $userTypeMusic->user_id = $user_id;
                $userTypeMusic->music_id = $type_music;
                $userTypeMusic->save();
            }
        }

        if (!empty($request->social_interests) && count($request->social_interests) > 0) {
            UserSocialInterests::where('user_id', $user_id)->delete();
            foreach ($request->social_interests as $social_interests) {
                $userSocialInterest = new UserSocialInterests;

                $userSocialInterest->user_id = $user_id;
                $userSocialInterest->social_interest_id = $social_interests;
                $userSocialInterest->save();
            }
        }

        $redirectUrl = route("user.dashboard");

        if ($request->session()->has("userInfoRegister")) {
            $infoRegister = $request->session()->get("userInfoRegister");
            $title = getFirstWord(Auth::user()->first_name);
            $title1 = Auth::user()->first_name;

            if (!empty($request->description)) {
                $description = $request->description;
            } else {
                $description = __('property.default_description' . $infoRegister['scenario_register'], array('first_name' => Auth::user()->first_name, "addresse" => $infoRegister['address_register']));
                if (!empty($user_profile->profession)) {
                    $description .= __('property.metier_description', array('metier' => $user_profile->profession));
                }
                if (!empty($user_profile->school)) {
                    $description .= __('property.school_description', array('school' => $user_profile->school));
                }
                $description .= __('property.merci_description', array('first_name' => Auth::user()->first_name));
            }

            switch ($infoRegister['scenario_register']) {
                case '1':
                    $scenario_id = 3;
                    break;
                case '2':
                    $scenario_id = 4;
                    break;
                case '3':
                    $scenario_id = 1;
                    break;
                case '4':
                    $scenario_id = 2;
                    break;
                case '5':
                    $scenario_id = 5;
                    break;

                default:
                    # code...
                    break;
            }
            $user_id = Auth::id();
            $budget = getFirstWord(Auth::user()->budget);
            if ($infoRegister['scenario_register'] != 4) {
                $adsInfo = array();
                $adsInfo['user_id'] = $user_id;
                $adsInfo['title'] = $title1;
                $adsInfo['description'] = $description;
                $adsInfo['address'] = $infoRegister['address_register'];
                $adsInfo['latitude'] = $infoRegister['latitude_register'];
                $adsInfo['longitude'] = $infoRegister['longitude_register'];

                $adsInfo['available_date'] = date("Y-m-d");
                $adsInfo['scenario_id'] = $scenario_id;
                $adsInfo['status'] = '1';
                $adsInfo['url_slug'] = str_slug($title, '-');
                $adsInfo['created_at'] = date("Y-m-d H:i:s");
                $adsInfo['updated_at'] = date("Y-m-d H:i:s");
                $adsInfo['complete'] = 1;
                $adsInfo['budget'] = $budget;
                $adsInfo['min_rent'] = $budget;

                DB::table('ads')->insert($adsInfo);

                $ad_id = DB::getPdo()->lastInsertId();
                $this->signalAd($request->contact_continue, $ad_id);

                $AdDetails = array();
                $AdDetails['ad_id'] = $ad_id;
                $AdDetails['property_type_id'] = "2";
                $AdDetails['furnished'] = "0";
                $AdDetails['budget'] = $budget;

                DB::table('ad_details')->insert($AdDetails);
                if (isset($request->guarantee_type)) {
                    $guarantees = $request->guarantee_type;
                    foreach ($guarantees as $key => $guar) {
                        if ($guar != 0) {
                            DB::table("ad_to_guarantees")->insert(
                                ["ad_id" => $ad_id, "guarantees_id" => $guar, "created_at" => date("Y-m-d"), "updated_at" => date("Y-m-d")]
                            );
                        }
                    }
                }

            }

            if ($infoRegister['scenario_register'] == 4) {
                $redirectUrl = "/partager-un-logement" . getPathInscriptionok();
            } else {
                $redirectUrl = searchUrl($adsInfo['latitude'], $adsInfo['longitude'], $adsInfo['address'], $infoRegister['scenario_register']) . getPathInscriptionok();
            }

        }

        countValidateForm("form3");
        return redirect($redirectUrl);
    }

    public function saveProfilRecherche_fixe(Request $request)
    {
        $user_id = Auth::id();
        $user_profile = UserProfiles::where('user_id', $user_id)->first();
        DB::table("users")->where("id", $user_id)->update(['etape_inscription' => 3]);

        if ($request->file('file_profile_photos')) {

            $file = $request->file('file_profile_photos');

            $destinationPathProfilePic = base_path() . '/storage/uploads/profile_pics/';

            $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($destinationPathProfilePic, $file_name);

            pasteLogo($destinationPathProfilePic . $file_name);

            $size = filesize($destinationPathProfilePic . $file_name);
            if ($size > 40000) {
                compressImage($destinationPathProfilePic . $file_name, removeExtension($file_name), $destinationPathProfilePic, 45, 9);
            }
        }

        if (!empty($file_name)) {
            $user_profile->profile_pic = $file_name;
        }
        if (!empty($request->smoker)) {
            if ($request->smoker >= 0) {
                $user_profile->smoker = $request->smoker;
            }
        }
        if (!empty($request->revenus)) {
            $user_profile->revenus = $request->revenus;
        }
        if (!empty($request->gay)) {
            if ($request->gay >= 0) {
                $user_profile->gay = $request->gay;
            }
        }
        if (!empty($request->alcool)) {
            if ($request->alcool >= 0) {
                $user_profile->alcool = $request->alcool;
            }
        }

        $user_profile->save();

        if (!empty($request->sports) && count($request->sports) > 0) {
            UserTypeMusics::where('user_id', $user_id)->delete();
            foreach ($request->sports as $sport) {
                $infos = [
                    "user_id" => $user_id,
                    "sport_id" => $sport,
                ];
                DB::table('user_to_sport')->insert($infos);
            }
        }

        if (!empty($request->type_musics) && count($request->type_musics) > 0) {
            UserTypeMusics::where('user_id', $user_id)->delete();
            foreach ($request->type_musics as $type_music) {
                $userTypeMusic = new UserTypeMusics;

                $userTypeMusic->user_id = $user_id;
                $userTypeMusic->music_id = $type_music;
                $userTypeMusic->save();
            }
        }

        if (!empty($request->social_interests) && count($request->social_interests) > 0) {
            UserSocialInterests::where('user_id', $user_id)->delete();
            foreach ($request->social_interests as $social_interests) {
                $userSocialInterest = new UserSocialInterests;

                $userSocialInterest->user_id = $user_id;
                $userSocialInterest->social_interest_id = $social_interests;
                $userSocialInterest->save();
            }
        }

        $redirectUrl = route("user.dashboard");

        if ($request->session()->has("userInfoRegister")) {
            $infoRegister = $request->session()->get("userInfoRegister");
            $title = getFirstWord(Auth::user()->first_name);
            if (!empty($request->description)) {
                $description = $request->description;
            } else {
                $description = __('property.default_description' . $infoRegister['scenario_register'], array('first_name' => Auth::user()->first_name, "addresse" => $infoRegister['address_register']));
                if (!empty($user_profile->profession)) {
                    $description .= __('property.metier_description', array('metier' => $user_profile->profession));
                }
                if (!empty($user_profile->school)) {
                    $description .= __('property.school_description', array('school' => $user_profile->school));
                }
                $description .= __('property.merci_description', array('first_name' => Auth::user()->first_name));
            }

            switch ($infoRegister['scenario_register']) {
                case '1':
                    $scenario_id = 3;
                    break;
                case '2':
                    $scenario_id = 4;
                    break;
                case '3':
                    $scenario_id = 1;
                    break;
                case '4':
                    $scenario_id = 2;
                    break;
                case '5':
                    $scenario_id = 5;
                    break;

                default:
                    # code...
                    break;
            }
            $user_id = Auth::id();
            $budget = getFirstWord(Auth::user()->budget);

            if ($infoRegister['scenario_register'] != 4) {
                $adsInfo = array();
                $adsInfo['user_id'] = $user_id;
                $adsInfo['title'] = $title;
                $adsInfo['description'] = $description;
                $adsInfo['address'] = $infoRegister['address_register'];
                $adsInfo['latitude'] = $infoRegister['latitude_register'];
                $adsInfo['longitude'] = $infoRegister['longitude_register'];

                $adsInfo['available_date'] = date("Y-m-d");
                $adsInfo['scenario_id'] = $scenario_id;
                $adsInfo['status'] = '1';
                $adsInfo['url_slug'] = str_slug($title, '-');
                $adsInfo['created_at'] = date("Y-m-d H:i:s");
                $adsInfo['updated_at'] = date("Y-m-d H:i:s");
                $adsInfo['complete'] = 1;
                $adsInfo['budget'] = $budget;
                $adsInfo['min_rent'] = $budget;

                DB::table('ads')->insert($adsInfo);

                $ad_id = DB::getPdo()->lastInsertId();
                $this->signalAd($request->contact_continue, $ad_id);

                $AdDetails = array();
                $AdDetails['ad_id'] = $ad_id;
                $AdDetails['property_type_id'] = "2";
                $AdDetails['furnished'] = "0";
                $AdDetails['budget'] = $budget;

                DB::table('ad_details')->insert($AdDetails);
                if (isset($request->guarantee_type)) {
                    $guarantees = $request->guarantee_type;
                    foreach ($guarantees as $key => $guar) {
                        if ($guar != 0) {
                            DB::table("ad_to_guarantees")->insert(
                                ["ad_id" => $ad_id, "guarantees_id" => $guar, "created_at" => date("Y-m-d"), "updated_at" => date("Y-m-d")]
                            );
                        }
                    }
                }

            }

            if ($infoRegister['scenario_register'] == 4) {
                $redirectUrl = "/partager-un-logement" . getPathInscriptionok();
            } else {
                $redirectUrl = searchUrl($adsInfo['latitude'], $adsInfo['longitude'], $adsInfo['address'], $infoRegister['scenario_register']) . getPathInscriptionok();
            }

        }

        countValidateForm("form3");
        return redirect($redirectUrl);
    }

    private function signalAd($contact_continue, $ad_id)
    {
        if ($contact_continue == 1) {
            DB::table('signal_ad')->where('ad_id', $ad_id)->delete();
            DB::table('signal_ad')->insert([
                "ad_id" => $ad_id,
                "user_id" => Auth::id(),
                "commentaire" => "Annonce avec coordonnées dans la description ou le titre",
                "done" => '0',
            ]);
        } else {
            DB::table('signal_ad')->where('ad_id', $ad_id)->delete();
        }
    }

    public function checkCoordoneAds(Request $request)
    {
        $strings = array($request->description);
        if (!isInfoWithoutContact($strings)) {
            return response()->json(['error' => "contact_error", "message" => __('backend_messages.error_contact_ad')]);
        }
        return response()->json(['error' => "no"]);
    }

    public function viewProfile(Request $request, $userId, $adId = null)
    {

        $user_id = $userId;

        $user = User::with(['user_profiles', 'user_social_interests', 'user_lifestyles', 'user_type_musics'])->where('id', $user_id)->first();

        $lifeStyles = DB::table('user_to_lifestyles')
            ->join('user_lifestyles', 'user_to_lifestyles.lifestyle_id', 'user_lifestyles.id')
            ->where('user_to_lifestyles.user_id', $user_id)
            ->get();
        $socialInfo = DB::table('user_to_social_interests')
            ->join('social_interests', 'social_interests.id', 'user_to_social_interests.social_interest_id')
            ->where('user_to_social_interests.user_id', $user_id)
            ->get();
        $typeMusics = DB::table('user_to_music')
            ->join('user_type_music', 'user_type_music.id', 'user_to_music.music_id')
            ->where('user_to_music.user_id', $user_id)
            ->get();
            // $page_title = getFirstWord($user->first_name) . " | " . config('app.name', "Bailvite");
        $page_title = getFirstWord($user['first_name']) . " | " . config('app.name', "Bailvite");
        // print_r($page_title);
        $user_ad = DB::table("ads")->where("user_id", $userId)->first();

        $is_user_has_ad = !is_null($user_ad);
        $is_user_premium = (isUserSubscribed() || isUserSubscribed($user->id));

        return view('profile/view-profil', compact('user', "page_title", 'lifeStyles', 'socialInfo', 'typeMusics', "is_user_has_ad", "is_user_premium", "adId"));
    }

    public function file_get_contents_fix(Request $request)
    {
        try {
            if ($provider == 'facebook') {
                $user = Socialite::driver('facebook')->fields([
                    'first_name', 'last_name', 'email', 'gender', 'birthday', 'location', 'link', 'hometown', 'friends',
                ])->stateless()->user();
            } else {
                $user = Socialite::driver($provider)->user();
            }
        } catch (\Exception $e) {
            if (Session::has('AD_INFO') && isset($ad_info_session['step1_data'])) {
                $ad_info_session = Session::get('AD_INFO');
                if ($ad_info_session['step1_data']['scenario_id'] == '1') {
                    return redirect()->route('rent.property');
                } else if ($ad_info_session['step1_data']['scenario_id'] == '2') {
                    return redirect()->route('rent.accommodation');
                } else if ($ad_info_session['step1_data']['scenario_id'] == '3') {
                    return redirect()->route('looking.property');
                } else if ($ad_info_session['step1_data']['scenario_id'] == '4') {
                    return redirect()->route('looking.accommodation');
                } else if ($ad_info_session['step1_data']['scenario_id'] == '5') {
                    return redirect()->route('looking.partner');
                }
            } else {
                Session::flash('error', __('backend_messages.error_occured'));
                return redirect()->route('login');
            }
        }
        if (!empty(Session::has('ad_id_redirection'))) {
            $ad_id = Session::get('ad_id_redirection');
            $cities = DB::table("users")->where("status", 1)->where("country_id", $request->country_id)->orderBy('city_name')->get();

            if (!empty($cities)) {
                $response = '';
                foreach ($cities as $city) {
                    $response .= '<option value="' . $city->id . '">' . $city->city_name . '</option>';
                }
                echo $response;
            }
        }

        if (Session::has('ad_id_redirection') && $provider == "facebook") {
            $ad_id = Session::get('ad_id_redirection');
            $adToRedirect = $this->matchAdToUser($ad_id, $user);

        }

        if (!isset($user->user['email'])) {
            return redirect()->route('login_popup')->withError(__('backend_messages.email_facebook_required'));
        }

        $isNew = false;
        $authUser = $this->findOrCreateUser($user, $provider, $isNew);
        $user_id = $authUser->id;
        if ($isNew) {

            $this->manageParainage($authUser->email, $authUser->first_name);
            Session::put("registerFb", true);
            Session::put("authUser", $authUser);
        } else {
            Auth::login($authUser, true);
        }

        if ($provider == "google") {
            saveGooglePdp($user->avatar, $authUser->id);
        }

        if ($provider == "linkedin") {
            saveLinkedinPdp($user->avatar, $authUser->id);
        }

        if ($provider == 'facebook') {

            $this->saveSocialProfilInfo($user, $authUser->id);
            saveFbPdp($user->id, $authUser->id);
            $objFacebook = new FacebookGraphInterface();
            $friendsListResponse = $objFacebook->getFriendsList($user->id, $user->token);

            if ($friendsListResponse['status'] == true) {
                $FbFriendList = new FbFriendList;
                $FbFriendList->where('user_id', $user_id)->delete();
                if (!empty($friendsListResponse['friends_list'])) {
                    foreach ($friendsListResponse['friends_list'] as $friend_list) {
                        $userFriend = DB::table('users')->where('provider_id', $friend_list['id'])->first();
                        if (!is_null($userFriend) && !is_null($user_id)) {
                            $FbFriendList = new FbFriendList;

                            $FbFriendList->user_id = $user_id;
                            $FbFriendList->fb_friend_name = $friend_list['name'];
                            $FbFriendList->fb_friend_id = $friend_list['id'];

                            $FbFriendList->save();
                            $this->manageUserFriends($friend_list['id'], $user);
                        }

                    }
                }
            }

        }

        if (isset($adToRedirect) && !is_null($adToRedirect)) {
            return redirect(adUrl($ad_id));
        }

        if (Session::has('AD_INFO')) {
            return redirect()->route('save.all');
        } else {
            if ($isNew) {

                Session::put("provider", $provider);

                if (!is_null(getInfoRegister('scenario_register')) && getInfoRegister('scenario_register') != 3 && getInfoRegister('scenario_register') != 4) {
                    DB::table("users")->where("id", $authUser->id)->update(['scenario_register' => getInfoRegister('scenario_register'), 'address_register' => getInfoRegister('address_register')
                    ,'etape_inscription' => "1"]);
                    return redirect("/facebook-inscription/etape2");

                } else {
                    DB::table("users")->where("id", $authUser->id)->update(['scenario_register' => getInfoRegister('scenario_register'), 'address_register' => getInfoRegister('address_register')
                    ,'etape_inscription' => "1"]);
                    Auth::login($authUser, true);

                    Session::put('ADRESS_INFO', (object) array('address' => getInfoRegister('address_register'), 'latitude' => getInfoRegister('latitude_register'), "longitude" => getInfoRegister('longitude_register')));
                    if (getInfoRegister('scenario_register') == 3) {
                        return redirect(route("step-address-annonce") . getPathInscription() . "?type=louer-une-propriete");
                    } else {
                        return redirect(route("searchProfile") . getPathInscription());
                        return redirect(route("step-address-annonce") . getPathInscription() . "?type=partager-un-logement");
                    }
                    return redirect("/facebook-inscription/etape2");
                }
                Auth::login($authUser, true);
                return redirect(generateInterRegisterReturnUrl($provider));

            } else {
                return redirect(generateConnexionReturnUrl());
            }
        }
    }

    public function saveParainnage(Request $request)
    {
        $email = htmlspecialchars($request->email);
        $check = DB::table("user_parainage")->where("email", $email)->first();
        $checkUsers = DB::table("users")->where("email", $email)->first();
        $result = [];
        $result["status"] = "success";
        if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#i", $email)) {
            if (is_null($checkUsers) && is_null($check)) {
                DB::table("user_parainage")->insert([
                    "email" => $email,
                    "user_id" => Auth::id(),
                ]);
                $this->sendMailParainnage($email);
            } else {
                $result["status"] = "error";
                $result['message'] = __('backend_messages.email_parainage_user');
            }
        } else {
            $result["status"] = "error";
            $result['message'] = __('backend_messages.email_parainage_bad_format');
        }
        return response()->json($result);
    }

    private function sendMailParainnage($email)
    {
        $subject = __('mail.Parainnage');

        try {
        sendMail($email,'emails.users.parainnage',[
          "subject"     => $subject,
          "MailSubject" => $subject,
          "UserNameFileul" => Auth::user()->first_name
        ]);
        } catch (Exception $ex) {

        }
    }
}
