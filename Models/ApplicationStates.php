<?php
namespace App\Http\Models;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Http\Models\Ads\Ads;
use App\Http\Models\Ads\Favourites;
use App\Http\Models\Ads\AdVisitingDetails;
use App\Http\Models\Ads\VisitRequests;
use App\Http\Models\Ads\Messages;
use App\Http\Models\Ads\AdDocuments;
use App\User;

class ApplicationStates
{
	public static function getAll(){
		return array(
			'waiting' => 0,
			'validated' => 1,
			'declined' => 2
		);
	}

	public static function getAllId(){
		return array(
			0 => 'waiting',
			1 => 'validated',
			2 => 'declined'
		);
	}

	public static function getId($name){
		$a = ApplicationStates::getAll();
        if(isset($a[$name]))
		  return $a[$name];
        else
            return -1;
	}

	public static function saveMessage($adId, $senderAdId, $m, $rId) {
            $senderId = Auth::id();
            
            $response = array();
                                            
                $sender_ad = Ads::where('id', $senderAdId)->first();
                $ad = Ads::find($adId);
                
                if(!empty($ad) && !empty($sender_ad)){
                    
                    $response['error'] = 'no';
                    
                    $receiverId = $rId;                    

                    $arrData = Messages::
                            where(function ($query) use ($senderId, $receiverId) {
                                $query->where(function ($query) use ($senderId, $receiverId) {
                                    $query->where('sender_id', $senderId)
                                          ->where('receiver_id', $receiverId);
                                });
                                $query->orWhere(function ($query) use ($senderId, $receiverId) {
                                    $query->where('sender_id', $receiverId)
                                          ->where('receiver_id', $senderId);
                                });
                            })
                            ->where(function ($query) use ($adId, $senderAdId) {
                                $query->where(function ($query) use ($adId, $senderAdId) {
                                    $query->where('ad_id', $adId)
                                          ->where('sender_ad_id', $senderAdId);
                                });
                                $query->orWhere(function ($query) use ($adId, $senderAdId) {
                                    $query->where('ad_id', $senderAdId)
                                          ->where('sender_ad_id', $adId);
                                });
                            })
                            ->orderBy('id','DESC')
                            ->first();
                    if (!empty($arrData)) {
                        $threadId = $arrData->thread_id;
                    } else {
                        $threadId = uniqid() . rand(999,99999);
                    }
                    
                    $message = new Messages;
                    
                    $message->thread_id = $threadId;
                    $message->ad_id = $adId;
                    $message->sender_ad_id = $senderAdId;
                    $message->sender_id = $senderId;
                    $message->receiver_id = $receiverId;
                    $message->message = trim($m);
                    $message->save();
                    
                    $response['message_info'] = ['message' => trim($m), 'created_date' => date('m/d/Y', strtotime($message->created_at)), 'created_time' => date("h:i A", strtotime($message->created_at))];
                }
            
            
            return $response;
        
    }
    public static function saveMessage2($adId, $senderAdId, $m, $rId) {
            $senderId = Auth::id();
            
            $response = array();
                                            
                $sender_ad = Ads::where('id', $senderAdId)->first();
                $ad = Ads::find($adId);
                
                if(!empty($ad) && !empty($sender_ad)){
                    
                    $response['error'] = 'no';
                    
                    $receiverId = $rId;                    

                    $arrData = Messages::
                            where(function ($query) use ($senderId, $receiverId) {
                                $query->where(function ($query) use ($senderId, $receiverId) {
                                    $query->where('sender_id', $senderId)
                                          ->where('receiver_id', $receiverId);
                                });
                                $query->orWhere(function ($query) use ($senderId, $receiverId) {
                                    $query->where('sender_id', $receiverId)
                                          ->where('receiver_id', $senderId);
                                });
                            })
                            ->where(function ($query) use ($adId, $senderAdId) {
                                $query->where(function ($query) use ($adId, $senderAdId) {
                                    $query->where('ad_id', $adId)
                                          ->where('sender_ad_id', $senderAdId);
                                });
                                $query->orWhere(function ($query) use ($adId, $senderAdId) {
                                    $query->where('ad_id', $senderAdId)
                                          ->where('sender_ad_id', $adId);
                                });
                            })
                            ->orderBy('id','DESC')
                            ->first();
                    if (!empty($arrData)) {
                        $threadId = $arrData->thread_id;
                    } else {
                        $threadId = uniqid() . rand(999,99999);
                    }
                    
                    $message = new Messages;
                    
                    $message->thread_id = $threadId;
                    $message->ad_id = $adId;
                    $message->sender_ad_id = $senderAdId;
                    $message->sender_id = $senderId;
                    $message->receiver_id = $receiverId;
                    $message->message = trim($m);
                    $message->save();
                    
                    $response['message_info'] = ['message' => trim($m), 'created_date' => date('m/d/Y', strtotime($message->created_at)), 'created_time' => date("h:i A", strtotime($message->created_at))];
                }
            
            
            return $response;
        
    }

    public static function getAllCount($user_id){
        $all_sent = DB::select('SELECT COUNT(id) AS nb FROM application WHERE user_id=?', array($user_id));
        $waiting = DB::select('SELECT COUNT(id) AS nb FROM application WHERE user_id=? AND status=0', array($user_id));
        $accepted = DB::select('SELECT COUNT(id) AS nb FROM application WHERE user_id=? AND status=1', array($user_id));
        $declined = DB::select('SELECT COUNT(id) AS nb FROM application WHERE user_id=? AND status=2', array($user_id));
        $received = DB::select('SELECT COUNT(app.id) AS nb FROM application AS app WHERE app.ad_id IN (SELECT id FROM ads AS ads WHERE ads.user_id=?)', array($user_id));
        return array(
            'sent' => $all_sent[0]->nb,
            'waiting' => $waiting[0]->nb,
            'accepted' => $accepted[0]->nb,
            'declined' => $declined[0]->nb,
            'received' => $received[0]->nb
        );
    }
}