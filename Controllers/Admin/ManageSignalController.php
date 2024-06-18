<?php
namespace App\Http\Controllers\Admin;

use App\Http\Models\Signal;
use App\Http\Models\Ads\Ads;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageSignalController extends Controller{
    public function index(Request $request){
        $page = $request->page;
        $sort = $request->sort;
        $order = $request->order;
        $foreign = $request->foreign;
        $foreign_field = $request->ffield;
        $treaty = 0;
        if(isset($request->treaty)) {
            $treaty = $request->treaty;
        }
        if(isset($foreign) && isset($sort)){
            $list = Signal::with(['ad','user'])->select('signal_ad.*')->join($foreign, 'signal_ad.'.$sort, '=', $foreign.'.id')->where("treaty", $treaty)->orderBy($foreign.'.'.$foreign_field, isset($order)  ? $order : 'asc')->paginate(10);
        } elseif (isset($sort) && !isset($foreign)){
            $list = Signal::with(['ad','user'])->where("treaty", $treaty)->orderBy($sort, isset($order) ? $order : 'asc')->paginate(10);
        } else {
            $list = Signal::with(['ad','user'])->where("treaty", $treaty)->orderBy("signal_ad.created_at", 'desc')->paginate(10);
        }
        foreach ($list as $key => $ls) {
            $list[$key]->ad_user = DB::table("ads")->join("users", "users.id", "ads.user_id")->where("ads.id", $ls->ad_id)->first();
        }


        $list->appends($request->only(['sort', 'order', 'foreign', 'foreign_field']));
        return view('admin.signal.listing', compact('list', 'page', 'sort', 'order', 'foreign', 'foreign_field', "treaty"));
    }

    public function delete(Request $request, $id){
        $ads = Ads::find($id);
        $signals = Signal::where('ad_id', $id)->get();
        foreach($signals as $signal){
            $signal->delete();
        }
        $ads->delete();
        countAds("delete");
        return redirect()->back();
    }

    public function delete_signal(Request $request, $id) {
        $signal = Signal::find($id);
        $signal->delete();
        return redirect()->back();
    }

    public function updateSignalActivated(Request $request, $id){
        $signal = Signal::find($id);
        $signal->done = TRUE;
        $signal->save();
        echo 'ads/'.$signal->ad->url_slug.'-'.$signal->ad->id;
        return redirect('ads/'.$signal->ad->url_slug.'-'.$signal->ad->id);
    }

    public function treatAd($signalId,$status,Request $request)
    {
        if ($status == 1) {
            $status = '1';
            $msg = 'Ad Treated successfuly.';
            $msgType = 'status';
            $type = "active";
        } else {
            $status = '0';
            $msg = 'Ad UnTreated successfuly.';
            $msgType = 'status';
            $type = "desactive";
        }

        DB::table("signal_ad")->where("id", $signalId)->update([
            "treaty" => $status
        ]);
        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }

    public function DeactiveAd(Request $request)
    {
        $status = '0';
        $msg = 'Ad disapproved successfuly.';
        $msgType = 'status';
        $type = "desactive";
        $adId = $request->ad_id;

        if(isset($request->comment))
        {
            DB::table('signal_ad')->where("ad_id", base64_decode($adId))->update(
                ["deactive_comment" => $request->comment]
            );
        }

        $queryStatus = Ads::where('id', base64_decode($adId))->update(['admin_approve' => $status]);

        $ad = Ads::has("user")->with(["ad_details", "user"])->where('id', base64_decode($adId))->first();

        if(!empty($ad->user->email)) {
            $this->sendMailActiveDeactiveAd($type, $ad);
        }


        if (empty($queryStatus)) {
            $msg = 'Something went wrong!';
            $msgType = 'error';
        }
        $request->session()->flash($msgType, $msg);
        return redirect()->back();
    }
}
