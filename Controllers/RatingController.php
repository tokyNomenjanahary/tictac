<?php

namespace App\Http\Controllers;

use App\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    
    public function index_note() {
        $ratings = Rating::where('user_id_proprio', Auth::id())->get();
        return view('proprietaire.index_note', compact('ratings'));
    }

    public function ratingUpdate(Request $request) {
        $rating = Rating::findOrFail($request->rating_id);

        $rating->update([
            "Note" => $request->note_star,
            "Avis" => $request->avis
        ]);
        return redirect()->back();
    }
}
