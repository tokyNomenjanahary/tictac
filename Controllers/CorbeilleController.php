<?php

namespace App\Http\Controllers;

use App\Corbeille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CorbeilleController extends Controller
{
    public function  index()
    {
        $corbeilles = Corbeille::where('user_id', Auth::id())->get();
        $filtre_epic = Corbeille::distinct()->pluck('epic');
        return view('corbeille.list', compact('corbeilles','filtre_epic'));
    }


    public function restaurer(Request $request, $id)
    {
        $corbeille = Corbeille::findOrFail($id);
        try {
            DB::table($corbeille->nom_table)->where('id', $corbeille->data_id)->update(['deleted_at' => null]);
            $corbeille->delete();
        } catch (\Throwable $th) {
            toastr()->error("Une erreur s'est produite");
        }
        toastr()->success("Element restauré avec succès");
        return back();
    }

    public function vider(){
        $corbeilles = Corbeille::where('user_id',Auth::id())->get();
        foreach($corbeilles as $corbeille){
            DB::table($corbeille->nom_table)
                ->where('id',$corbeille->data_id)
                ->delete();
        }
        Corbeille::where('user_id',Auth::id())->delete();
        toastr()->success("Corbeille vider avec succès");
        return back();
    }
    public function deletePermanent(Request $request)
    {
        $ids = $request->ids;
        try {
            foreach ($ids as $id) {
                $corbeille = Corbeille::findOrFail($id);
                DB::table($corbeille->nom_table)->where('id', $corbeille->data_id)->delete();
                $corbeille->delete();
            }
            toastr()->success("Supprimez avec succès");
        } catch (\Throwable $th) {
            toastr()->error("Des problèmes sont survenue lors de la suppression");
        }
    }

    public function restorePermanent(Request $request)
    {
        $ids = $request->ids;
        try {
            foreach ($ids as $id) {
                $corbeille = Corbeille::findOrFail($id);
                DB::table($corbeille->nom_table)->where('id', $corbeille->data_id)->update(['deleted_at' => null]);
                $corbeille->delete();
            }
            toastr()->success("Restaurer avec succès");
        } catch (\Throwable $th) {
            toastr()->error("Des problèmes sont survenue lors de la restauration");
        }
    }
}
