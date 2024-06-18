<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



use Illuminate\Validation\Rule;
use App\User;

//use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    //use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm(){
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request){

        $validator = Validator::make($request->all(),
                                [
                                    'email' => [
                                        'required',
                                        'email',
                                        Rule::exists('users')->where(function ($query) {
                                            $query->where('user_type_id', '1')->where('deleted_at', NULL)->where('is_active', '1');
                                        })
                                    ]
                                ]
                    );

        $email = $request->email;
        $token = time() . str_random(30);

        $user = User::where('email', $email)->where('verified', '1')->first();
        if(!empty($user)){
                $user->password_reset_token = $token;
                $user->save();
                $resetUrl = route('password.reset', [$token]);

                $control = $this->sendForgotPasswordMail($resetUrl, $user);
                if($control){
                    # code email max OK
                    $request->session()->flash('success', __('backend_messages.reset_pass_check_mail'));
                    /*return redirect()->route('password.request');*/
                    return response()->json(['type' => 'success','message' => __('backend_messages.reset_pass_check_mail')]);
                } else {
                    # code email max et atteinte
                    $request->session()->flash('errorrs', __('backend_messages.nbr_email'));

                return response()->json(['type' => 'errorrs','message' => __('backend_messages.nbr_email')]);

                }

            } else {
                #USER not verified
            $request->session()->flash('error', __('backend_messages.account_not_verified'));
             $request->session()->flash('email_verif', $request->email);

             return response()->json(['type' => 'error','message' => __('backend_messages.account_not_verified')]);
        }
    }

    private function sendForgotPasswordMail($resetUrl, $user) {

        $subject = i18n('mail.forgot_subject',getLangUser($user->id));


            try {
                sendMail($user->email,'emails.users.forgotpassword',[
                    'user' => $user,
                    'subject' => $subject,
                    'resetUrl' => $resetUrl,
                    'lang' => getLangUser($user->id)
                  ]);
            } catch (Exception $ex) {

            }
            return true;


    }

}
