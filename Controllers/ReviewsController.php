<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use stdClass;
use App\User;
use Illuminate\Support\Facades\DB;

class ReviewsController extends Controller
{
	public function index()
	{
		$reviews = DB::table("user_avis")->join("users", 'users.id', 'user_avis.user_id')->join("user_profiles", "user_profiles.user_id", "users.id")->where("admin_approve", "1")->orderBy("user_avis.date", "DESC")->get();
		
		return view('all-reviews', compact('reviews'));
	}
}