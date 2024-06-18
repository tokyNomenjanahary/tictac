<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Models\Signal;
use App\Http\Models\SignalTags;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Ads\Ads;

class SignalController extends Controller
{
    public function add(Request $request){

        if(!Auth::check()){
            return response()->json(array('error' => 'yes', 'errors' => array('signal-modal-comment' => __('addetails.you_must_logged'))));
        }

        if(!$this->can_signal_ad($request->ad_id))
        {
            return response()
            ->json(array('error' => 'yes', 
            'errors' => array('signal-modal-comment' => 
            __('addetails.ad_already_reported'))));
        }
        $data = $request->all();
        $signal = new Signal();
        $signal->ad_id = $data['ad_id'];
        $signal->commentaire = isset($data['comment']) && !empty($data['comment']) ? $data['comment'] : "None";
        $signal->user_id = Auth::id();
        $signal->done = 0;
        $ad = Ads::find($data['ad_id']);
        if(!empty($ad)){
            $signal->save();
            if(isset($data['tags'])){
                foreach($data['tags'] as $tag){
                    $t = new SignalTags();
                    $t->signal_id = $signal->id;
                    $t->tag_id = $tag;
                    $t->save();
                }
            }
        }
        return response()->json(array('error' => 'no'));
    }

    private function can_signal_ad($ad_id)
    {
        return !(Signal::Where('ad_id',$ad_id)->where('user_id',Auth::id())->exists());
    }
}

