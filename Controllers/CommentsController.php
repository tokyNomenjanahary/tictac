<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\Comment;
use App\Http\Models\CommentsVote;
use App\Http\Models\CommentResponse;
use App\Http\Models\Ads\Ads;
use Illuminate\Http\Request;

class CommentsController extends Controller
{

	public function index($ad_id){
		$list = Comment::where('ad_id', $ad_id)->get();
		$list = Comment::select('comments.*')
			->addSelect(DB::raw('COUNT(comments_vote.id) as nb_vote'))
			->leftJoin('comments_vote', 'comments_vote.comment_id', '=', 'comments.id')
			->where('ad_id', $ad_id)
			->groupBy('comments.id')
			->orderBy('nb_vote', 'DESC')
			->get();
		$all = [];
		foreach ($list as $e) {
			$arr = array(
				'id' => $e->id,
				'text' => $e->text,
				'responses' => array(),
				'user_id' => $e->user_id,
				'vote' => $e->nb_vote
			);
			$responses = CommentResponse::where('comment_id', $e->id)->get();
			foreach ($responses as $v) {
				$arr['responses'][] = array('id' => $v->id, 'text' => $v->text);
			}
			$all[] = $arr;
		}
		return response()->json($all);
	}

	public function add(Request $request){
		if(!Auth::check()){
			return response()->json(array('error' => 'yes', 'errors' => array('form-add-comment-text' => __('comments.must_be_connected'))));
		}
		$comment = new Comment();
		$data = $request->all();
		if(empty($data['text']) || $data['text'] == ""){
			return response()->json(array('error' => 'yes', 'errors' => array('form-add-comment-text' => __('validator.required'))));
		}
		$comment->text = htmlentities($data['text']);
		$comment->user_id = Auth::id();
		$comment->ad_id = $data['ad_id'];
		$comment->save();
		return response()->json(array('error' => 'no'));
	}

	public function edit(Request $request){
		if(!Auth::check())
			return response()->json(array('error' => 'yes', 'errors' => array('comment-view-modal-text' => __('comments.must_be_connected'))));
		$user_id = Auth::id();
		$data = $request->all();
		if($data['text'] == "" || empty($data['text']))
			return response()->json(array('error' => 'yes', 'errors' => array('comment-view-modal-text' => __('validator.required'))));
		$comment_id = $data['comment-id'];
		$text = $data['text'];
		$comment = Comment::find($comment_id);
		$comment->text = $text;
		$comment->save();

		$data = $request->all();

		$comment->text = $data['text'];
		return response()->json(array('error' => 'no'));
	}


	public function remove($comment_id){
		if(!Auth::check())
			return response()->json(array('error' => 'yes', 'errors' => array('text' => __('comments.must_be_connected'))));
		$user_id = Auth::id();
		$comment = Comment::find($comment_id);
		$ad = Ads::find($comment->ad_id);

		if($comment->user_id != $user_id && $ad->user_id != $user_id){
			return response()->json(array('error' => 'yes', 'errors' => array('text' => __('comments.cannot_edit'))));
		}

		$comment->delete();

		return response()->json(array('error' => 'no'));
	}


	public function respond($comment_id, Request $request){
		$data = $request->all();
		if(!Auth::check())
			return response()->json(array('error' => 'yes', 'errors' => array('form-add-comment' => __('comments.must_be_connected'))));
		$user_id = Auth::id();
		$comment = Comment::find($comment_id);
		$ad = Ads::find($comment->ad_id);
		$response = new CommentResponse();
		$response->comment_id = $comment_id;
		$response->text = $data['comment'];
		$response->save();
		return response()->json(array('error' => 'no'));
	}

	public function vote_up($comment_id){
		if(!Auth::check())
			return response()->json(array('error' => 'yes', 'errors' => array('form-add-comment' => __('comments.must_be_connected'))));
		$user_id = Auth::id();
		$comment_vote = CommentsVote::where('comment_id', $comment_id)->where('user_id', $user_id)->get();
		if(count($comment_vote) <= 0){
			$cv = new CommentsVote();
			$cv->user_id = $user_id;
			$cv->comment_id = $comment_id;
			$cv->save();
		}
		return response()->json(array('error' => 'no'));
	}

	public function vote_down($comment_id){
		if(!Auth::check())
			return response()->json(array('error' => 'yes', 'errors' => array('form-add-comment' => __('comments.must_be_connected'))));
		$user_id = Auth::id();
		$comment_vote = CommentsVote::where('comment_id', $comment_id)->where('user_id', $user_id)->get();
		foreach($comment_vote as $cv){
			$cv->delete();
		}
		return response()->json(array('error' => 'no'));
	}	
}