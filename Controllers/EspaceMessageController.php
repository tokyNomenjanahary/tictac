<?php

namespace App\Http\Controllers;

use App\User;
use App\Ticket;
use App\EspaceMessage;
use App\MessageTicket;
use App\DiscussionMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use App\LocatairesGeneralInformations;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EspaceMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('espaceMessage.indexMessage');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!isTenant()){
            $data_user_receiver = LocatairesGeneralInformations::where('user_id', auth::id())
                                                                ->where('archiver', 0)
                                                                ->get();
        }else{
            $data_user_receiver = LocatairesGeneralInformations::where('user_account_id', Auth::user()->id)
                                    ->join('users', 'users.id', '=', 'locataires_general_informations.user_id')
                                    ->join('user_profiles', 'user_profiles.user_id', '=', 'locataires_general_informations.user_id')
                                    ->select('users.*','user_profiles.*','locataires_general_informations.id as loc_id')
                                    ->get();
        }

        return view('espaceMessage.newMessage', compact('data_user_receiver'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'espace_message_id' => 'required',
            'id_user_receiver' => 'required',
            'message' => 'required',
        ]);
        if ($validator->passes()) {
            $data =  DiscussionMessage::create([
                            'espace_message_id' => $request->espace_message_id,
                            'id_user_sender' => Auth::id(),
                            'id_user_receiver' => $request->id_user_receiver,
                            'message' => $request->message,
                        ]);

            return response()->json($data, 200);
        }else{
            // toastr()->error('Veuillez ecrire une message s\'il vous plait!');
            return response()->json(['error' => true, 'message' => 'Veuillez ecrire une message s\'il vous plait!'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function boiteMessage()
    {
        $created_by = 1;
        $received_by = 0;
        if (isTenant()) {
            $created_by = 0;
            $received_by = 1;
        }

        


        $discussion = EspaceMessage::where(function ($query) use ($created_by) {
                $query->where('id_user_sender', Auth::id())
                    ->where('for_tenant', $created_by);
                })
                ->orWhere(function ($query) use ($received_by) {
                    $query->where('id_user_receiver', Auth::id())
                        ->where('for_tenant', $received_by);
                })->orderBy('updated_at','desc')
                ->get();
        return view('espaceMessage.indexMessage', compact('discussion'));


    }

    public function showConversation($id)
    {
        $getConversation = DiscussionMessage::where('espace_message_id',$id)->latest()->take(20)->get()->reverse();
        DiscussionMessage::where('espace_message_id',$id)
                ->where('id_user_receiver',Auth::id())
                ->update([
                    'read' => 1
                ]);

        $espace_message = EspaceMessage::find($id);
        $sujet = $espace_message->sujet;
        if($espace_message->id_user_sender == Auth::id()){
            $id_user_receiver = $espace_message->id_user_receiver;
        }else{
            $id_user_receiver =  $espace_message->id_user_sender;
        }
        $user_recveiver = User::find($id_user_receiver);
        if (!empty($user_recveiver->user_profiles) && !empty($user_recveiver->user_profiles->profile_pic) && File::exists(storage_path('uploads/profile_pics/' . $user_recveiver->user_profiles->profile_pic))) {
            $src_receiver =URL::asset('uploads/profile_pics/' . $user_recveiver->user_profiles->profile_pic);
            $style_rotate = 'width: 45px; height: 45px; transform: rotate('.$user_recveiver->user_profiles->pdp_rotate.'deg);';
        }else{
            $src_receiver=URL::asset('images/profile_avatar.jpeg');
            $style_rotate = 'width: 45px; height: 45px;';
        }
        $espace_message_id = $id;

        return view('espaceMessage.showConversation', compact('getConversation', 'espace_message_id', 'sujet', 'id_user_receiver','src_receiver', 'style_rotate'));
    }

    public function getResponseMessage($id)
    {
        $responseMessage = DiscussionMessage::where('espace_message_id',$id)->with('getUserSender')->latest()->first();
        if($responseMessage){
            if($responseMessage->id_user_receiver == Auth::id() && $responseMessage->read == 0){
                $dataResponseMessage = $responseMessage;
                $responseMessage->read = 1;
                $responseMessage->save();
                return response()->json($dataResponseMessage, 200);
            }
        }
    }

    public function saveNewMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sujet' => 'required',
            'id_user_receiver' => 'required',
            'message' => 'required',
        ]);
        if ($validator->passes()) {
            $user_exist = User::find($request->id_user_receiver);
            if($user_exist){
                $espace_message = EspaceMessage::create([
                                                "id_user_sender"=>Auth::id(),
                                                "id_user_receiver"=>$request->id_user_receiver,
                                                "sujet"=>$request->sujet,
                                                "message"=>$request->message,
                                                "for_tenant"=> isTenant() ? 0 : 1,
                                            ]);
                DiscussionMessage::create([
                    'espace_message_id' => $espace_message->id,
                    'id_user_sender' => Auth::id(),
                    'id_user_receiver' => $request->id_user_receiver,
                    'message' => $request->message,
                ]);
            }else{
                toastr()->error('Le destinateur n\'a pas encore de compte sur bailti. Invite le à s\'incrire pour recevoir votre message');
                return redirect()->back();
            }

        }

        return redirect()->route('espaceMessage.showConversation', $espace_message->id);
    }

    public function supprimer($id){
        DB::table('message_tickets')->where('id', $id)->delete();
        toastr()->success(__('Ticket supprimé avec succès'));
        return back();
    }
}
