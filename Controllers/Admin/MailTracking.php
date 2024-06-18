<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MailFollow;
use Illuminate\Http\Request;

class MailTracking extends Controller
{
    public function index(){
        $NbreMailDays = MailFollow::getNbreMailDays();
        $NbreMailWeeks = MailFollow::getNbreMailWeek();
        $NbreMailMonths = MailFollow::getNbreMailMonth();
        $emails = MailFollow::all();
        return view('emails.suivi_mail',compact('emails','NbreMailDays','NbreMailMonths','NbreMailWeeks'));
    }
}
