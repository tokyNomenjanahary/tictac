<?php

namespace App\Http\Controllers;

use App\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    //
    public function index()
    {
        
        /* message envoyé par l'utilisateur trier par date d'envoi decroissante */
        $userId = Auth::id(); 
        $message_envoi = Ticket::leftJoin('message_tickets', function ($join) use ($userId) {
            $join->on('tickets.id', '=', 'message_tickets.ticket_id')
                ->whereRaw('(message_tickets.date_sent = (
                    SELECT MAX(date_sent) FROM message_tickets
                    WHERE message_tickets.ticket_id = tickets.id
                    AND message_tickets.id_user_sender = '.$userId.'
                ))');
        })
        ->select('tickets.*', 'message_tickets.Message','message_tickets.date_sent')
        ->orderBy('message_tickets.date_sent','DESC')
        ->get();
        return view('espace_message.index',compact('message_envoi'));
    }
}
