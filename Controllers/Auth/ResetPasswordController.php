<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;
//use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    //use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    public function showResetForm($token, Request $request){
        
        if (!empty($token)) {
            
            $user = User::where('password_reset_token', $token)->first();
            if(!empty($user) && !is_null($user)) {
                
                return view('auth.passwords.reset', compact('token'));
                
            } else {
                $request->session()->flash('error', __('backend_messages.token_not_valid'));
                return redirect()->route('login');
            }
            
        } else {
            $request->session()->flash('error', __('backend_messages.token_not_found'));
            return redirect()->route('login');
        }
        
    }
    
    public function reset(Request $request){
        
        $validator = Validator::make($request->all(),
                                [
                                    'email' => [
                                        'required',
                                        'max:100',
                                        'email',
                                        Rule::exists('users')->where(function ($query) {
                                            $query->where('user_type_id', '1')->where('deleted_at', NULL)->where('is_active', '1');
                                        })
                                    ],
                                    'password' => [
                                        'required',
                                        'max:100',
                                        'confirmed'
                                    ],
                                    'password_confirmation' => 'required'
                                ]
                    );
        
		
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        
        $passwordResetToken = $request->token;
        
        if (!empty($passwordResetToken)) {
            
            $user = User::where('password_reset_token', $passwordResetToken)->where('email', $request->email)->first();
            
            if(!empty($user) && !is_null($user)){
                $user->password = bcrypt($request->password);
                $user->password_reset_token = '';
                $user->save();
                
                $request->session()->flash('success', __('backend_messages.success_reset_password'));
                return redirect()->route('login');
            } else {
                $request->session()->flash('error', __('backend_messages.token_not_valid'));
                return redirect()->back();
            }
            
        } else {
            $request->session()->flash('error', __('backend_messages.token_not_present'));
            return redirect()->back();
        }
        
    }
    
}
