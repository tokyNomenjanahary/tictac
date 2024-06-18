<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Comment;
use App\Http\Models\CommentResponse;
use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;
use stdClass;
use App\User;
use Illuminate\Support\Facades\DB;

class MyCommentsController extends Controller
{
    public function received()
    {

        if (Auth::check()) {
            $user_id = Auth::id();
            $ads = Ads::with("ad_details")->where('user_id', $user_id)->get();

            $list_ads = array();
            foreach ($ads as $k => $v) {
                $list_ads[] = $v->id;
            }
            $comments = Comment::whereIn('ad_id', $list_ads)->simplePaginate(10);
            $pagination = $comments->links();
            $cs = array();
            foreach ($comments as $k => $v) {
                $a = new stdClass();
                $a->id = $v->id;
                $a->text = $v->text;
                $a->responses = array();
                $a->user = User::find($v->user_id);
                $a->sender_ads = Ads::find($v->ad_id);
                $responses = CommentResponse::where('comment_id', $v->id)->get();
                $v->viewed = 1;
                $v->save();
                foreach ($responses as $key => $value) {
                    $resp = new stdClass();
                    $resp->id = $value->id;
                    $resp->text = $value->text;
                    $a->responses[] = $resp;
                }
                $cs[] = $a;
            }
            $type = 'received';
            return view('comments/page', compact('cs', 'pagination', 'type'));
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function posted()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $comments = Comment::where('user_id', $user_id)->simplePaginate(10);
            $pagination = $comments->links();
            $cs = array();
            foreach ($comments as $k => $v) {
                $a = new stdClass();
                $a->id = $v->id;
                $a->text = $v->text;
                $a->responses = array();
                $a->user = User::find($v->user_id);
                $a->sender_ads = Ads::find($v->ad_id);
                $responses = CommentResponse::where('comment_id', $v->id)->get();
                foreach ($responses as $key => $value) {
                    $resp = new stdClass();
                    $resp->id = $value->id;
                    $resp->text = $value->text;
                    $a->responses[] = $resp;
                }
                $cs[] = $a;
            }
            $type = 'posted';
            return view('comments/page', compact('cs', 'pagination', 'type'));
        } else {
            return redirect()->route('user.dashboard');
        }
    }

    public function editResponse(Request $request)
    {
        if (Auth::check()) {
            $data = $request->all();
            if ($data['text'] == "" || empty($data['text']))
                return response()->json(array('error' => 'yes', 'errors' => array('response-edit-modal-text' => __('validator.required'))));
            $response_id = $data['id'];
            $response = CommentResponse::find($response_id);
            $response->text = $data['text'];
            $response->save();
            return response()->json(array('error' => 'no'));
        } else {
            return response()->json(array('error' => 'yes', 'errors' => array('response-edit-modal-text' => __("comments.must_be_connected"))));
        }
    }

    public function addResponse(Request $request)
    {
        if (Auth::check()) {
            $validationParam = array(
                'text' => 'required',
            );
            $data = $request->all();
            if ($data['text'] == "" || empty($data['text']))
                return response()->json(array('error' => 'yes', 'errors' => array('response-modal-text' => __('validator.required'))));
            $response = new CommentResponse();
            $response->comment_id = $data['comment_id'];
            $response->text = $data['text'];
            $response->save();
            return response()->json(array('error' => 'no'));
        } else {
            return response()->json(array('error' => 'yes', 'errors' => array('response-modal-text' => __("comments.must_be_connected"))));
        }
    }

    public function deleteResponse($response_id)
    {
        if (Auth::check()) {
            $response = CommentResponse::find($response_id);
            $response->delete();
            return response()->json(array('error' => 'no'));
        } else {
            return response()->json(array('error' => 'yes', 'errors' => array('text' => __("comments.must_be_connected"))));
        }
    }

    public function getCommentsUpdate()
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $d = DB::select('SELECT COUNT(c.id) AS nb FROM comments AS c WHERE (c.ad_id IN (SELECT id FROM ads AS ads WHERE ads.user_id=?)) AND c.viewed=0', array($user_id));
            return response()->json(array('error' => 'no', 'data' => $d[0]->nb));
        } else {
            return response()->json(array('error' => 'yes'));
        }

    }
}
