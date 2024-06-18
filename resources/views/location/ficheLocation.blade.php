@extends('proprietaire.index')
<style>
     .nav-tabs .nav-item .nav-link:not(.active) {
            background-color: rgb(250, 250, 250);
        }
        .nav-tabs .nav-item .nav-link.active  {
            border-top: 3px solid blue !important;
            border-bottom: 3px solid white !important;
        }
        .nav-tabs .nav-item .nav-link   {
            color: blue !important;
            font-size: 13px !important;

        }
        p{
            font-size: 13px;
        }
        .file-type-icon {
        display: block;
        padding-top: 8px;
        width: 36px;
        height: 44px;
        background: #f9f9f9;
        position: relative;
        border: 1px solid #e6e6e6;
        border-radius: 2px;
        box-shadow: inset 1px 1px 0 0 #ffffff, inset -1px -1px 0 0 #ffffff, inset 0 10px 20px -10px rgba(0, 0, 0, 0.05);
        }
        .file-type-icon .corner {
        display: block;
        position: absolute;
        top: -1px;
        right: -1px;
        width: 0;
        height: 0;
        border-left: 12px solid #eeeeee;
        border-top: 12px solid #ffffff;
        box-shadow: -1px 1px 0px 0 #e6e6e6;
    }
    .red
    {
     color:red !important;
    }
    .green
    {
     color:yellow !important;
    }
    .blue
    {
     color:blue !important;
    }
    
        @media only screen and (max-width: 600px) {
            header{
                margin:25px auto;
                padding-bottom:50px !important;
                margin-left: 18px !important;
                margin-right: 18px !important;
            }
            .filtre{
                display: none;
            }
            .nav-mob{
                margin-top:20px;
            }
            .card{
                box-shadow: none !important;
            }
        }
</style>
@section('contenue')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<div class="p-12">
    <header class="bg-white " style="margin:25px auto;margin-left:25px;margin-right: 25px">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" style="padding-bottom: 20px">
            <div class="row">
                <div class="col-md-12 p-3">
                    <div class="float-start">
                        <h3><a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.ficheLocation')}}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                    <div class="card">
                        <div class="card-header" style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                            {{__('location.information')}}
                        </div>
                        <div class="card-body p-3" style="padding: 10px">
                            <div class="row mt-2">
                                <h5><b>{{$location->Logement->identifiant}}</b> - {{$location->typelocation->description}}</h5>
                                <p style="font-size: 15px">{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</p>
                                @if($location->etat == 2 || strtotime($location->fin) < time())
                                    <span class="btn btn-secondary btn-sm" style="width: 120px;margin-left:10px">Inactive</span>
                                @else
                                    <span class="btn btn-info btn-sm" style="width: 120px;margin-left:10px">Active</span>
                                @endif
                            </div>
                            <div class="row  mt-2 p-3" style="background: #F5F5F9">
                                <div class="col-2 filtre">
                                    <span aria-hidden="true" class=" fas fa-coins pull-left circle m-r-10 " style="font-size:50px"></span>
                                </div>
                                <div class="col-6" style="line-height: 10px;">
                                    <p style="text-transform: uppercase">{{__('location.loyers')}}</p>
                                    <p style="font-size:20px;" class="text-success">{{$location->Logement->loyer + $location->Logement->charge}} €</p>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 col-sm-12">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>Adresse</b></h5>
                                    </div>
                                    <p>{{$location->Logement->adresse}}</p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>{{__('location.proprietaire')}}</b></h5>
                                    </div>
                                    <p>{{$location->user->first_name}}</p>
                                </div>
                            </div>
                            <div class="row mt-3 ">
                                <div class="col-md-6 col-sm-12" style="line-height: 5px">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>{{__('location.location')}}</b></h5>
                                    </div>
                                    <p class="mt-2"><b>Type : </b>{{$location->typelocation->description}}</p>
                                    <p><b>Durée : </b>{{\Carbon\Carbon::parse($location->debut)->format('d/m/Y').' - '.\Carbon\Carbon::parse($location->fin)->format('d/m/Y')}}</p>
                                    {{-- <p><b>Renouvellement : </b>Oui</p> --}}
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>{{__('location.locataire')}}</b></h5>
                                    </div>
                                    <a href="" style="margin-top:20px;">{{$location->Locataire->civilite . ' '}}{{$location->Locataire->TenantFirstName . ' '}}{{$location->Locataire->TenantLastName}}</a>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-6 col-sm-12" style="line-height: 5px">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>{{__('location.loyers')}}</b></h5>
                                    </div>
                                    <p class="mt-2"><b>{{__('location.loyers')}}   : </b>{{$location->Logement->loyer}} €</p>
                                    <p class="mt-2 text-capitalize"><b>{{__('location.charge')}}   : </b>@if($location->Logement->charge == null) 0.00 € @else {{$location->Logement->charge}} € @endif</p>
                                    <p><b class="text-capitalize">{{__('location.payment')}} : </b>{{$location->typepayement->description}}</p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div style="border-bottom: #4C8DCB 1px solid;">
                                        <h5><b>Dépôts</b></h5>
                                    </div>
                                    <p><b>{{__('location.depotGarantie')}} : </b>@if($location->garantie == null) 0.00 € @else {{$location->garantie}} € @endif</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 ">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs nav-mob" id="myTab" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">FINANCE</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">{{__('location.notes')}}</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="messages-tab" data-bs-toggle="tab" data-bs-target="#messages" type="button" role="tab" aria-controls="messages" aria-selected="false">ACTIVITES</button>
                      </li>
                      <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab" aria-controls="messages" aria-selected="false">DOCUMENTS</button>
                      </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content" style="padding: 0px;">
                      <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="card-header"
                            style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                            Finances
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between" style="border-bottom: rgb(34, 239, 16) 2px solid !important">
                                        <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                                            <p class="m-0 me-2 w-auto">REVENUS</p>
                                        </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h5 class="text-success">{{$revenue}} €</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header  py-2 px-3 d-flex align-items-center justify-content-between" style="border-bottom: orangered 2px solid !important">
                                            <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                                                <p class="m-0 me-2 w-auto">{{__('location.attente')}}</p>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h5 style="color: orangered">{{$en_attente}} €</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header  py-2 px-3 d-flex align-items-center justify-content-between" style="border-bottom: #4C8DCB 2px solid !important">
                                            <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                                                <p class="m-0 me-2 w-auto">{{__('location.solde')}}</p>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <h5 style="color: #4C8DCB"> 0.000 €</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <a href="{{route('voir_finance',['id' => $location->id])}}" class="btn btn-primary btn-block shadow-none" style="width: 100%;background: #F5F5F9;color:#4C8DCB">finance</a>
                            </div>
                            <div class="col-6">
                                <a href="{{route('voir_bilan',['id' => $location->id])}}" class="btn btn-primary btn-block shadow-none" style="width: 100%;background: #F5F5F9;color:#4C8DCB">{{__('location.bilan')}}</a>
                            </div>
                        </div>
                      </div>
                      <div class="tab-pane " id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="card-header text-capitalize"
                            style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;text-transform: capitalize !important">
                            {{__('location.notes')}}
                            </div>
                            <div class="card-body" id="liste_note">
                                @forelse ($notes as $note)
                                    <div class="card text-start mt-1" id="parent_{{$note->id}}">
                                        <div class="card-body">
                                            <div class="col-12 align-middle">
                                                <div class="float-start">
                                                    <p class="card-text"><i class="fa-regular fa-clipboard"></i>&nbsp;&nbsp;&nbsp;{{$note->notes}} </p><span style="font-size:12px;">le : &nbsp;{{\Carbon\Carbon::parse($note->created_at)->format('d/m/Y')}}</span>
                                                </div>

                                                <div class="float-end align-middle" style="display: flex">

                                                    <button class="btn btn-danger btn-sm supprimer align-middle" id="{{$note->id}}"><i class="fa-solid fa-trash"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <center id="vide">{{__('location.rienIci')}}</center>
                                @endforelse
                            </div>
                            <div class="card-body" >
                                <form id="add-note-form">
                                    @csrf
                                    <input type="hidden" id="location_id" name="location_id" value="{{$location->id}}">
                                    <textarea name="notes" id="note" style="width: 100%" rows="5"></textarea>
                                    <button type="submit" class="btn btn-primary btn-block mt-1" style="width: 100%">{{__('location.enregistrer')}}</button>
                                </form>
                            </div>

                      </div>
                      <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                        <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                        {{__('location.historique')}}
                        </div>
                        <div class="card-body" >
                            <div class="card text-start mt-1" >
                                <div class="card-body">
                                    <p><i class="fa-solid fa-circle-check " style="color:greenyellow" ></i> {{__('location.creeLe')}} : {{\Carbon\Carbon::parse($location->created_at)->format('d/m/Y H:i:s')}}</p>
                                    @if($location->date_archive == null) @else <p><i class="fa-solid fa-box-archive"></i> {{__('location.archiveLe')}} : {{\Carbon\Carbon::parse($location->date_archive)->format('d/m/Y ')}}</p> @endif
                                    @if($location->date_desarchive == null) @else <p><i class="fa-solid fa-boxes-packing"></i> {{__('location.desarchiveLe')}} : {{\Carbon\Carbon::parse($location->date_desarchive)->format('d/m/Y ')}}</p> @endif
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                        <div class="card-header text-center"
                        style="color:#4C8DCB;padding:10px;background-color:F5F5F9;margin-top:20px;border-radius:0px;">
                           <span>Les documents locations s'afficheront ici</span>
                        </div>
                        <div class="card-body" >
                            <div class="card text-start mt-1" >
                                <div class="card-body row" >
                                    @if(count($location->files)>0)
                                      @foreach($location->files as $file)
                                        @php
                                            $filename = $file->folder;
                                            $extension = pathinfo($filename, PATHINFO_EXTENSION);
                                        @endphp
                                        <div class="col-md-2" style="padding-top:5px;">
                                        <div class="file-type-icon pull-left">
                                            <span class="corner"></span>
                                            <span @if(in_array($extension, ['jpg', 'jpeg', 'png','pdf'])) class="red" @elseif(in_array($extension, ['xlxs','ods'])) class="green" @elseif(in_array($extension, ['docx','txt'])) class="blue" @endif style="font-size:13px;">{{ $extension }}</span>
                                       </div>
                                        </div>
                                        <div class="col-md-10 pull-left" style="padding-top:20px;">
                                            <a href="{{route('location.downDocuments',$file->id)}}">{{$file->folder}}</a>
                                        </div>
                                     @endforeach
                                    @else
                                    <h5>Votre location ne contient pas des documents</h5> 
                                   @endif
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[type="hidden"]').attr('value')
                }
        });
        $('#add-note-form').submit(function (event) {
            event.preventDefault();
            var location_id = $('#location_id').val()
            var notes = $('#note').val()
            $.ajax({
                url: '{{route('location.note')}}',
                type: 'POST',
                data: {
                    location_id : location_id,
                    notes : notes
                },
                success: function (data) {
                    if(data == 'erreur'){
                        toastr.error("Entrée une note !");
                    }else{
                        $('#note').val('')
                        $('#vide').hide()
                        toastr.success('Note enregistré ')
                        $("#liste_note").append('<div class="card text-start mt-1" id="parent_'+data.id+'">\
                                    <div class="card-body">\
                                        <div class="col-12">\
                                            <div class="float-start">\
                                                <p class="card-text"><i class="fa-regular fa-clipboard"></i>&nbsp;&nbsp;&nbsp;' + data.notes + '</p>\
                                            </div>\
                                            <div class="float-end">\
                                                <button class="btn btn-danger btn-sm supprimer" id="'+data.id+'"><i class="fa-solid fa-trash"></i></button>\
                                            </div\
                                        </div>\
                                    </div>\
                            </div>')
                    }
                }
            });
        });
    });
    $(document).on('click', '.supprimer', function() {
        var id = $(this).attr('id');
        var parent = $(this).closest('#parent_'+ id)

        $.ajax({
            type: "GET",
            url: "{{route('note.delete')}}",
            data: {
                id:id
            },
            dataType: "json",
            success: function (response) {
                parent.remove();
            }
        });

    });
</script>
@endsection
