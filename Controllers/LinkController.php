<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Models\Ads\Ads;
use App\Http\Models\Ads\Favourites;
use App\Http\Models\Ads\AdVisitingDetails;
use App\Http\Models\Ads\VisitRequests;
use App\Http\Models\Ads\Messages;
use App\Http\Models\SignalTag;
use App\Http\Models\Ads\AdDocuments;
use App\Http\Models\Ads\AdProximity;
use App\User;
Use App\FacebookAPI;

use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;




use Ping;
use App\Http\Controllers\Controller;


class LinkController extends Controller
{

    /**
     * Show the current health of a given URL.
     *
     * @param  string  $url
     * @return string
     */
    public function healthCheck()
    {
        $url = 'https://bailti.fr/';
        $health = Ping::check($url);

        if (($health >= 100 && $health <= 199) || ($health >= 200 && $health < 299))  {
            echo "Serveur up";

            $subjectEmail = __('mail.email_max');


            sendMailAdmin('emails.users.emailma',['subject'=>$subjectEmail,'email_moin' => $health]);


        } else {
            echo "le serveur est down";
        }
    }
}
