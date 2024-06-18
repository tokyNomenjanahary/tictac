<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    public function update(Request $request) {
        $user = User::where('id', Auth::user()->id);
        $user->update([
            'role_id' => $request->role_id
        ]);
        $user = $user->first();
        if ($user->role_id == 2) {
            return redirect()->route('espaceLocataire.dashboard');
        }
        return redirect()->route('proprietaire.bureau');
    }
}
