<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\UserProfiles;
use App\UserLifestyles;
use App\UserSocialInterests;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use App\Repositories\MasterRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


class ManageUsersController extends Controller
{

    private $perpage;

    public function __construct()
    {
        $this->perpage = config('app.perpage');
    }

    public function index(Request $request)
    {
        $shorting = $search_name = [];

        if (!empty($request->short) && !empty($request->type)) {
            $shorting['short'] = $request->short;
            $shorting['type'] = $request->type;

            if ($request->short == 'name') {
                $orderBy = 'created_at';
            } elseif ($request->short == 'date') {
                $orderBy = 'created_at';
            } elseif ($request->short == 'email') {
                $orderBy = 'created_at';
            } elseif ($request->short == 'verified') {
                $orderBy = 'created_at';
            } elseif ($request->short == 'provider') {
                $orderBy = 'created_at';
            } else {
                return redirect()->route('admin.users');
            }

            if ($request->type == "ASC" || $request->type == 'asc' || $request->type == "DESC" || $request->type == "desc") {
                $orderBy = $orderBy . ' ' . $request->type;
            } else {
                return redirect()->route('admin.users');
            }
        } else {
            $orderBy = 'id DESC';
        }

        if (!empty($request->search_name)) {
            $search_name['search_name'] = $searchName = $request->search_name;
            //\DB::enableQueryLog();
            $users = User::where('user_type_id', '1')
                ->where(function ($query) use ($searchName) {
                    $query->where('first_name', 'like', "%$searchName%")
                        ->orWhere('last_name', 'like', "%$searchName%")
                        ->orWhere('email', 'like', "%$searchName%");
                })
                ->orderByRaw($orderBy)
                ->paginate($this->perpage);
        } else {
            if (!empty($request->nume_phone)) {
                $search_name['nume_phone'] = $searchName = $request->nume_phone;

                $users = DB::table('users')
                    ->join("user_profiles", "user_profiles.user_id", "users.id")
                    ->select('users.*')
                    ->where('user_profiles.mobile_no', 'LIKE', "%$request->nume_phone%")
                    ->get();
                //dd($users);

                foreach ($users as $key => $user) {
                    $user->comunity = DB::table("ads")
                        ->select(DB::raw("users.first_name as prenom_comunity, users.last_name as nom_comunity"))
                        ->join("users", "ads.comunity_id", "users.id")
                        ->where("ads.user_id", $user->id)
                        ->whereNotNull("ads.comunity_id")->first();
                    $user->bloqued = isBlockedIp($user->ip);
                }
                return view('admin.user.listing2', compact('users', 'shorting', 'search_name'));
            } else {
                $users = User::where('user_type_id', '1')
                    ->orderByRaw($orderBy)
                    ->paginate($this->perpage);
            }
        }

        foreach ($users as $key => $user) {
            $user->comunity = DB::table("ads")
                ->select(DB::raw("users.first_name as prenom_comunity, users.last_name as nom_comunity"))
                ->join("users", "ads.comunity_id", "users.id")
                ->where("ads.user_id", $user->id)
                ->whereNotNull("ads.comunity_id")->first();
            $user->bloqued = isBlockedIp($user->ip);
        }

        foreach($users as $user){
            $type_promotion = DB::table("user_packages")->where("user_id",$user->id)->orderBy("end_date", "desc")->first();
            if($type_promotion){
                $now = date("Y-m-d");

                $user->type_promotion = ($now <= $type_promotion->end_date) ? "Premium" : "Basic";
                if($user->type_promotion == "Premium"){
                    $user->start_date = $type_promotion->start_date;
                    $user->end_date = $type_promotion->end_date;
                    $user->class = "label-success";
                }else{
                    $user->start_date = "";
                    $user->end_date = "";
                    $user->class = "label-info";
                }
            }else{
                $user->type_promotion = "Basic";
                $user->start_date = "";
                $user->end_date = "";
                $user->class = "label-info";
            }
        }
        return view('admin.user.listing', compact('users', 'shorting', 'search_name'));
    }

    public function messageDelete(Request $request)
    {
        $users = DB::table("raison_account_delete")->orderBy("date", "desc")->orderBy("id", "desc")->paginate($this->perpage);
        foreach ($users as $key => $user) {
            DB::table("raison_account_delete")->where("id", $user->id)->update(['vu' => "1"]);
        }

        return view('admin.user.liste_message', compact('users'));
    }

    public function professions(Request $request)
    {
        $users = User::with(['user_profiles'])->orderBy("users.created_at", "desc")->paginate($this->perpage);
        return view('admin.user.professions', compact('users'));
    }

    public function comunityList(Request $request)
    {
        $shorting = $search_name = [];
        if (!empty($request->short) && !empty($request->type)) {
            $shorting['short'] = $request->short;
            $shorting['type'] = $request->type;
            if ($request->short == 'name') {
                $orderBy = 'first_name';
            } elseif ($request->short == 'date') {
                $orderBy = 'created_at';
            } elseif ($request->short == 'email') {
                $orderBy = 'email';
            } elseif ($request->short == 'verified') {
                $orderBy = 'verified';
            } elseif ($request->short == 'provider') {
                $orderBy = 'provider';
            } else {
                return redirect()->route('admin.users');
            }
            if ($request->type == "ASC" || $request->type == 'asc' || $request->type == "DESC" || $request->type == "desc") {
                $orderBy = $orderBy . ' ' . $request->type;
            } else {
                return redirect()->route('admin.users');
            }
        } else {
            $orderBy = 'id DESC';
        }


        $types = ['2', '3', '4', '5', '6', '7'];
        if (!empty($request->search_name)) {
            $search_name['search_name'] = $searchName = $request->search_name;
            //\DB::enableQueryLog();
            $users = User::where('user_type_id', '!=', 1)
                ->select("users.*", "type_user.designation")
                ->join('type_user', 'type_user.id', 'users.user_type_id')
                ->where(function ($query) use ($searchName) {
                    $query->where('first_name', 'like', '%' . $searchName . '%')
                        ->orWhere('last_name', 'like', "%$searchName%");
                })
                ->orderByRaw($orderBy)
                ->paginate($this->perpage);
        } else {
            $users = User::where('user_type_id', '!=', 1)
                ->select("users.*", "type_user.designation")
                ->join('type_user', 'type_user.id', 'users.user_type_id')
                ->orWhere('user_type_id', '4')
                ->paginate($this->perpage);
        }

        return view('admin.user.admin_listing', compact('users', 'shorting', 'search_name'));
    }


    public function activeDeactiveUser($userId = null, $status, Request $request)
    {

        if (!empty($status)) {
            $status = '1';
            $msg = 'User activated successfuly.';
            $msgType = 'status';
        } else {
            $status = '0';
            $msg = 'User deactivated successfuly.';
            $msgType = 'status';
        }
        $queryStatus = User::where('id', base64_decode($userId))->update(['is_active' => $status]);
        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }
        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function activeDeactiveUserP($userId = null, $status, Request $request)
    {


        $msg = 'User phone active successfuly.';
        $msgType = 'status';

        $queryStatus = User::where('id', base64_decode($userId))->update(['etape_inscription' => 2]);
        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }
        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function activeDeactiveUser2($userId = null, $status, Request $request)
    {


        $msg = 'User phone active successfuly.';
        $msgType = 'status';

        $queryStatus = User::where('id', base64_decode($userId))->update(['etape_inscription' => 1]);
        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }
        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    //bloquer l'IP de l'user
    public function bloqued_user($user_id)
    {
        disableAds($user_id);
        $users = DB::table('users')->where('id', $user_id)->first();
        DB::table("users")->where("id", $user_id)->update(["is_active"=>0]);
        if ($users->ip) {
            $bloqued_ip = DB::table('blocked_ip')->where('ip', $users->ip)->first();
            if ($bloqued_ip) {
                DB::table('blocked_ip')->where('ip', $users->ip)->delete();
                Session::flash('bloqued', 'User débloquer');
            } else {
                DB::table('blocked_ip')->insert(['ip' => $users->ip]);
                Session::flash('bloqued', 'User bien boquer');
            }
        } else {
            Session::flash('error_bloqued', 'Cet user n\'a pas d\'IP, peut etre qu\'il est crée par community');
        }

        return redirect()->back();
    }


    public function Etape2activeDeactive($userId = null, $status, Request $request)
    {

        if (!empty($status)) {
            $status = '1';
            $msg = 'Etape_2 activated successfuly.';
            $msgType = 'status';
        } else {
            $status = '0';
            $msg = 'Etape_2  deactivated successfuly.';
            $msgType = 'status';
        }
        $queryStatus = User::where('id', base64_decode($userId))->update(['etape_inscription' => $status]);


        $userCheck = User::select('email')->where('id', base64_decode($userId))->first();
        $checkEtape2 = User::select('etape_inscription')->where('id', base64_decode($userId))->first();
        $user_id = $userCheck;

        $subject = __('login.registered_with');


        if ($checkEtape2->etape_inscription == 0) {
            $VerificationLink = url('/creer-compte/etape/1');
        } else {
            $VerificationLink = url('/creer-compte/etape/2');
        }
        sendMail($userCheck,'emails.users.registration',[
            "subject"    => $subject,
            "MailSubject" => $subject,
            "UserName" => $userCheck,
            "userId" => $user_id,
            "VerificationLink" => $VerificationLink

        ]);

        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }
        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function deleteUser($userId, Request $request)
    {
        if (!empty($userId)) {
            $user = User::find(base64_decode($userId));

            DB::table("user_mail")->where('email', $user->email)->delete();
            DB::table('users')->where("id", base64_decode($userId))->delete();
            DB::table('user_profiles')->where("user_id", base64_decode($userId))->delete();
            countUsers("delete");
            if (!empty($user)) {
                $user->delete();
                $msg = 'User deleted successfuly.';
                $msgType = 'status';
            } else {
                $msg = 'User not found!';
                $msgType = 'error';
            }
        } else {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }

        $request->session()->flash($msgType, $msg);

        return redirect()->back();
    }

    public function userProfile($userId, Request $request)
    {
        if (!empty($userId)) {
            $userDetail = User::with(['user_social_interests.social_interest', 'user_lifestyles.lifestyle', 'user_profiles.study_level', 'user_profiles.city', 'user_profiles.country', 'user_packages.package', 'user_packages' => function ($query) {
                $query->orderBy('id', 'desc')->first();
            }])
                ->where('id', base64_decode($userId))
                ->first();
            if (!empty($userDetail)) {
         $end_date = DB::table('users')->where('id',base64_decode($userId)) ->first();
         $date1 = date_create(date('Y-m-d')) ;
         $date2 = date_create($end_date ->last_conection)  ;
         $end_date_conx  = $end_date ->last_conection;
         //dd($end_date_conx);
             $diff=date_diff($date2,$date1);
            $jours=substr($diff->format("%R%a "),1);

                return view('admin.user.user_profile', compact('userDetail','end_date_conx','jours'));
            } else {
                $request->session()->flash('error', 'User not found!');
                return redirect()->route('admin.users');
            }
        } else {
            $request->session()->flash('error', 'Something went wrong!');
            return redirect()->route('admin.users');
        }
    }

    public function editProfile($userId, Request $request, MasterRepository $master)
    {
        $studyLevels = $master->getMasters('study_levels');
        $socialInterests = $master->getMasters('social_interests');
        $countries = $master->getMasters('countries', ['status' => 1]);

        $userLifestyles = $master->getMasters('user_lifestyles');
        $user = User::with(['user_profiles', 'user_social_interests', 'user_lifestyles'])->where('id', base64_decode($userId))->first();
        if (!empty($user)) {

            if (!empty($user->user_social_interests) && count($user->user_social_interests) > 0) {
                foreach ($user->user_social_interests as $social_interest) {
                    $social_interests_array[] = $social_interest->social_interest_id;
                }
            } else {
                $social_interests_array = array();
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

            return view('admin.user.edit_profile', compact('user', 'social_interests_array', 'user_lifestyles_array', 'studyLevels', 'socialInterests', 'countries', 'cities', 'userLifestyles'));
        } else {
            $request->session()->flash('error', 'User not found!');
            return redirect()->route('admin.users');
        }
    }

    public function saveUserProfile(Request $request)
    {
        if (empty($request->id)) {
            $validator = Validator::make(
                $request->all(),
                [
                    'email' => 'required|max:100|email|unique:users',
                    'first_name' => 'required|min:3|max:100|alpha',
                    'last_name' => 'required|max:100|alpha',
                    'mobile_no' => 'required|min:10|numeric',
                    'postal_code' => 'required|min:4|max:10',
                    'birth_date' => 'required|date_format:m/d/Y'
                ],
                ['password.regex' => 'Password should contain at least 1 alphabet, 1 special chars., 1 digit and at-least 6 chars long']
            );
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'first_name' => 'required|min:3|max:100|alpha',
                    'last_name' => 'required|max:100|alpha',
                    'mobile_no' => 'required|min:10|numeric',
                    'postal_code' => 'required|min:4|max:10',
                    'birth_date' => 'required|date_format:m/d/Y'
                ]
            );
        }

        $validator->sometimes('about_me', 'min:10|max:500', function ($input) {
            return $input->about_me != '';
        });

        $validator->sometimes('school_name', 'min:3|max:100', function ($input) {
            return $input->school_name != '';
        });

        $response = array();

        if ($validator->passes()) {

            if ($request->file('file_profile_photos')) {
                $file = $request->file('file_profile_photos');
                $destinationPathProfilePic = base_path() . '/public/uploads/profile_pics/';
                $file_name = rand(999, 99999) . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($destinationPathProfilePic, $file_name);
            }
            if (!empty($request->id)) {
                $user_id = base64_decode($request->id);
                $user = User::find($user_id);
            } else {
                $user = new User;
                $user->email = $request->email;
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $request->password = substr(str_shuffle($chars), 0, 8);
                $user->password = bcrypt($request->password);
            }

            $user->first_name = trim($request->first_name);
            $user->last_name = trim($request->last_name);

            if ($user->save()) {
                if (!empty($request->id)) {
                    $user_profile = UserProfiles::where('user_id', $user_id)->first();
                } else {
                    $user_profile = new UserProfiles;
                    $user_id = $user->id;
                    $user_profile->user_id = $user_id;
                }

                $user_profile->sex = $request->sex;
                $user_profile->mobile_no = trim($request->mobile_no);
                $user_profile->postal_code = trim($request->postal_code);
                $user_profile->birth_date = date("Y-m-d", strtotime($request->birth_date));

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
                if (!empty($request->id)) {
                    UserSocialInterests::where('user_id', $user_id)->delete();
                }

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
                $request->session()->flash('status', 'Success! Your profile has been updated successfully.');
                if (!empty($request->id)) {
                    $response['redirect_url'] = route('admin.user.edit_profile', [$request->id]);
                } else {
                    $response['redirect_url'] = route('admin.user.edit_profile', [base64_encode($user_id)]);
                    $userDetail = [];
                    $userDetail['email'] = $request->email;
                    $userDetail['password'] = $request->password;
                    $userDetail['name'] = $request->first_name . ' ' . $request->last_name;
                    $this->sendMailToUser($userDetail);
                }
            } else {
                $response['error'] = 'yes';
                $response['errors'] = ['failedmessage' => 'Not able to save your info, please try again.'];
            }
        } else {
            $response['error'] = 'yes';
            $response['errors'] = $validator->getMessageBag()->toArray();
        }

        return response()->json($response);
    }

    public function addUser(MasterRepository $master)
    {
        $studyLevels = $master->getMasters('study_levels');
        $socialInterests = $master->getMasters('social_interests');
        $countries = $master->getMasters('countries', ['status' => 1]);
        $cities = $master->getMasters('cities', ['status' => 1, 'country_id' => 1]);
        $userLifestyles = $master->getMasters('user_lifestyles');
        return view('admin.user.add_user', compact('studyLevels', 'socialInterests', 'countries', 'cities', 'userLifestyles'));
    }

    private function sendMailToUser($userDetail)
    {
        $userDetail['subject'] = __('Registered with') . ' ' . config('app.name', 'TicTacHouse');
        if (!empty($userDetail['email'])) {
            try {
                sendMail($userDetail['email'],'emails.users.registration',[
                    "userDetail" => $userDetail,
                    "subject" => $this->userDetail['subject']
                ]);
            } catch (Exception $ex) {
            }
            return true;
        }
    }

    public function userPackages(Request $request)
    {
        $userList = DB::table('user_packages as up')
            ->select(DB::raw("u.id, u.first_name, u.last_name, p.title as type_package, up.start_date, up.end_date, up.created_at, up.last_ad_id"))
            ->join('users as u', "u.id", "up.user_id")->join("packages as p", "p.id", "up.package_id")
            ->whereNotNull("up.payment_id")
            ->groupBy("up.created_at")
            ->orderBy("up.created_at", "DESC")
            ->get();

        return view('admin.package.listing_user_package', compact("userList"));
    }

    public function userBadi(Request $request)
    {
        $userList = DB::table('badi as b')->select(DB::raw("b.id, b.ip, b.ad_id, b.user_id, u.last_name,u.first_name, u.email, b.date"))->join("users as u", "u.id", "b.user_id")->orderBy("b.date", "DESC")->get();
        return view('admin.badi.listing_user_badi', compact("userList"));
    }

    public function dailyInteraction(Request $request)
    {
        $messages = DB::table("messages as m")
            ->select(DB::raw('m.ad_id, m.message as message,m.created_at as date, u.first_name as sender_prenom,u.last_name as sender_nom, u2.first_name as receiver_prenom,u2.last_name as receiver_nom, a.title as annonce, "Message" as type_interaction, u.id as sender_id, u2.id as receiver_id'))
            ->join("users as u", "u.id", "m.sender_id")
            ->join("users as u2", "u2.id", "m.receiver_id")
            ->join("ads as a", "a.id", "m.ad_id");
        $toctocs = DB::table("coup_de_foudre as cdf")
            ->select(DB::raw('cdf.ad_id, "" as message,cdf.created_at as date, u.first_name as sender_prenom,u.last_name as sender_nom, u2.first_name as receiver_prenom,u2.last_name as receiver_nom, a.title as annonce, "TocToc" as type_interaction, u.id as sender_id, u2.id as receiver_id'))
            ->join("users as u", "u.id", "cdf.sender_id")
            ->join("users as u2", "u2.id", "cdf.receiver_id")
            ->join("ads as a", "a.id", "cdf.ad_id");
        $query = $toctocs->union($messages);
        $querySql = $query->toSql();
        $results = DB::table(DB::raw("($querySql order by date desc) as a"))
            ->mergeBindings($query)->get();

        return view('admin.user.interaction', compact('results'));
    }

    public function userAlert(Request $request)
    {
        $alerts = DB::table('users_alertes')->join("users", "users.id", "users_alertes.user_id")->join("user_profiles", "user_profiles.user_id", "users_alertes.user_id")->orderBy("users_alertes.date", "DESC")->get();
        return view('admin.user.alerts', compact('alerts'));
    }


    public function userAvis(Request $request)
    {
        $avis = DB::table("user_avis")->select(DB::raw("users.*, user_avis.*"))->join("users", "user_avis.user_id", "users.id")->orderBy("date", "desc")->get();
        return view('admin.user.user_avis', compact('avis'));
    }

    public function activeDeactiveAvis(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        DB::table("user_avis")->where("id", $id)->update(['admin_approve' => $status]);
        $request->session()->flash("status", "Action treated successfully");
        return redirect()->back();
    }

    public function activeData(Request $request)
    {
        if ($request->type == "profession") {
            $user_id = $request->id;
            $user = DB::table('user_profiles')->where("user_id", $user_id)->first();
            if (!is_null($user)) {
                DB::table('metier')->insert(['name' => $user->profession]);
            }
        }

        if ($request->type == "school") {
            $user_id = $request->id;
            $user = DB::table('user_profiles')->where("user_id", $user_id)->first();
            if (!is_null($user)) {
                DB::table('schools')->insert(['name' => $user->school]);
            }
        }

        $request->session()->flash("status", "Activated successfully");
        return redirect()->back();
    }

    public function modifyProfession(Request $request)
    {
        if (!empty($request->profession)) {
            DB::table("user_profiles")->where("user_id", $request->user_id)->update(['profession' => $request->profession]);
            $request->session()->flash("status", "updated successfully");
        }
        return redirect()->back();
    }

    public function modifySchool(Request $request)
    {
        if (!empty($request->school)) {
            DB::table("user_profiles")->where("user_id", $request->user_id)->update(['school' => $request->school]);
            $request->session()->flash("status", "updated successfully");
        }
        return redirect()->back();
    }

    public function parainage(Request $request)
    {
        $search_name = [];
        $orderBy = 'date desc';
        if (!empty($request->search_name)) {
            $search_name['search_name'] = $searchName = $request->search_name;
            //\DB::enableQueryLog();
            $users = DB::table("user_parainage")->select(DB::raw("user_parainage.email, user_parainage.date, user_parainage.statut, users.first_name, user_parainage.user_id"))->join("users", "users.id", "user_parainage.user_id")->where(function ($query) use ($searchName) {
                $query->where('users.first_name', 'like', "%$searchName%")
                    ->orWhere('users.last_name', 'like', "%$searchName%")->orWhere('user_parainage.email', 'like', "%$searchName%");
            })
                ->orderByRaw($orderBy)
                ->paginate($this->perpage);
        } else {
            $users = DB::table("user_parainage")->select(DB::raw("user_parainage.email, user_parainage.date, user_parainage.statut, users.first_name, user_parainage.user_id"))->join("users", "users.id", "user_parainage.user_id")->paginate($this->perpage);
        }
        return view('admin.user.parainage', compact('users', 'search_name'));
    }

    public function setToPremium(Request $request)
    {
        $user_id = $request->user_id;
        $end_date = date("Y-m-d", strtotime(convertDateWithTiret($request->date)));;
        $start_date = date("Y-m-d");
        insertLangNavigateur($user_id);
        DB::table("user_packages")->insert([
            "user_id" => $user_id,
            "package_id" => 1,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "created_at"=>now()->toDateTimeString()
        ]);
        $request->session()->flash('status', 'User changed to premium');

        return redirect()->back();
    }

    public function blockIp(Request $request)
    {
        $ips = DB::table('blocked_ip')
            ->paginate($this->perpage);
        return view('admin.user.blocked_ip', compact('ips'));
    }

    public function saveIp(Request $request)
    {
        $ip = $request->ip;
        DB::table('blocked_ip')->insert([
            'ip' => $ip
        ]);
        $request->session()->flash("status", "Ip ajouté");
        return "true";
    }

    public function deleteIp($id, Request $request)
    {
        DB::table('blocked_ip')->where("id", $id)->delete();
        $request->session()->flash("status", "Ip éffacé");
        return redirect()->back();
    }
}
