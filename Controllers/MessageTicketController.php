<?php

namespace App\Http\Controllers;

use App\MessageTicket;
use App\Http\Requests\StoreMessageTicketRequest;
use App\Http\Requests\UpdateMessageTicketRequest;

class MessageTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMessageTicketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageTicketRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MessageTicket  $messageTicket
     * @return \Illuminate\Http\Response
     */
    public function show(MessageTicket $messageTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MessageTicket  $messageTicket
     * @return \Illuminate\Http\Response
     */
    public function edit(MessageTicket $messageTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMessageTicketRequest  $request
     * @param  \App\MessageTicket  $messageTicket
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMessageTicketRequest $request, MessageTicket $messageTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MessageTicket  $messageTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy(MessageTicket $messageTicket)
    {
        //
    }
}
