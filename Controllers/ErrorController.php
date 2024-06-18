<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
Use App;
Use App\FacebookAPI;
use App\Http\Models\FeaturedCity;
use App\Http\Models\Ads\Ads;
use App\Http\Models\StaticPage;
use Illuminate\Support\Facades\DB;
use App\Http\Models\Ads\Messages;
use App\User;

use Illuminate\Support\Facades\Mail;


class ErrorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function reportError(Request $request)
    {
        customReportErrorNew($request->session()->get('exception'));
        $reportMessage = "Administrateur signalé, merci pour votre contribution";
        return response(view("error", compact("page_title", "page_description", "reportMessage")));
    }


}
