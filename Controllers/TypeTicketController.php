<?php

namespace App\Http\Controllers;

use App\TypeTicket;
use App\Http\Requests\StoreTypeTicketRequest;
use App\Http\Requests\UpdateTypeTicketRequest;

class TypeTicketController extends Controller
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
     * @param  \App\Http\Requests\StoreTypeTicketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTypeTicketRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TypeTicket  $typeTicket
     * @return \Illuminate\Http\Response
     */
    public function show(TypeTicket $typeTicket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TypeTicket  $typeTicket
     * @return \Illuminate\Http\Response
     */
    public function edit(TypeTicket $typeTicket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTypeTicketRequest  $request
     * @param  \App\TypeTicket  $typeTicket
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTypeTicketRequest $request, TypeTicket $typeTicket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TypeTicket  $typeTicket
     * @return \Illuminate\Http\Response
     */
    public function destroy(TypeTicket $typeTicket)
    {
        //
    }
}
