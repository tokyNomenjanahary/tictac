@extends('espace_locataire.index')

@section('locataire-contenue')
<div class="content-wrapper">
    <!-- Content -->
    <style>
        .nav-dash-items {
        height: 100px;
        width: 100px;
        }
        .nav-dash-icon-size{
        font-size: 3.5rem;
        }
        div.nav-dash-container:hover div.nav-dash-items{
        border: solid 2px #696cff !important;
        background: transparent !important;
        }
        div.nav-dash-container:hover div.nav-dash-items i.nav-dash-icon-size{
        color: #696cff !important;
        }
    </style>
        <div class="container-xxl flex-grow-1 container-p-y">
        <!-- DASHBOARD NAV CARD  -->
        <div class="nav-dash">
            <div class="card p-4">
            <div class="row justify-content-around">
                <div class="p-0 w-auto nav-dash-container">
                <a href="{{route('locataire.quittance')}}">
                    <div class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
                    <div class="w-auto m-0 p-0">
                        <i class="fa-solid fa-receipt text-white nav-dash-icon-size"></i>
                    </div>
                    </div>
                <div class="w-auto mt-3">
                    <p class="form-label text-center"> <a href="">mes quittances</a></p>
                </div>
                </a>
                </div>


                <div class="p-0 w-auto nav-dash-container">
                <a href="{{ route('message.index') }}">
                    <div class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
                    <div class="w-auto m-0 p-0">
                        <i class="fa-solid fa-message text-white nav-dash-icon-size"></i>
                    </div>
                    </div>
                <div class="w-auto mt-3">
                    <p class="form-label text-center"> <a href="">nouveau message</a> </p>
                </div>
                </a>
                </div>

                <div class="p-0 w-auto nav-dash-container">
                <a href="{{ route('ticket.index') }}">
                    <div class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
                    <div class="w-auto m-0 p-0">
                        <i class="fa-solid fa-screwdriver-wrench text-white nav-dash-icon-size"></i>
                    </div>
                    </div>
                <div class="w-auto mt-3">
                    <p class="form-label text-center"> <a href=""> nouvelle intervention</a></p>
                </div>
                </a>
                </div>

                <div class="p-0 w-auto nav-dash-container">
                <a href="{{route('location.liste_locataire')}}">
                    <div class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
                    <div class="w-auto m-0 p-0">
                        <i class="fa-sharp fa-solid fa-clipboard text-white nav-dash-icon-size"></i>
                    </div>
                    </div>
                <div class="w-auto mt-3">
                    <p class="form-label text-center"><a href="">Location</a> </p>
                </div>
                </a>
                </div>

                <div class="p-0 w-auto nav-dash-container">
                <a href="{{ route('documents.index') }}">
                    <div class="rounded-circle border-2 bg-primary nav-dash-items row justify-content-center align-items-center g-0 mx-auto">
                    <div class="w-auto m-0 p-0">
                        <i class="menu-icon fa fa-briefcase text-white nav-dash-icon-size"></i>
                    </div>
                    </div>
                <div class="w-auto mt-3">
                    <p class="form-label text-center"> <a href="">nouveau document</a></p>
                </div>
                </a>
                </div>
            </div>
            </div>
        </div>
        <!-- END DASHBOARD NAV CARD -->

        <!-- GESTION -->
        <div class="row mt-4">
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-3 col-xl-3 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                    <h5 class="m-0 me-2 w-auto">Locations</h5>
                </div>
                </div>
                <div class="card-body p-3">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="m-2">
                    <i class="fa-sharp fa-solid fa-key nav-dash-icon-size text-danger"></i>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-1 m-2">
                    <h2 class="mb-0 bg-label-danger rounded-pill p-2">{{count($etat_location)}}</h2>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <!--/ Order Statistics -->

            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-3 col-xl-3 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                    <h5 class="m-0 me-2 w-auto">Loyers en retard</h5>
                </div>
                </div>
                <div class="card-body p-3">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="m-2">
                    <i class="fa-sharp fa-solid fa-coins nav-dash-icon-size nav-dash-icon-size text-primary"></i>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-1 m-2">
                    <h2 class="mb-0 bg-label-primary rounded-pill p-2">{{count($etat_finance)}}</h2>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <!--/ Order Statistics -->
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-3 col-xl-3 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                    <h5 class="m-0 me-2 w-auto">Interventions</h5>
                </div>
                </div>
                <div class="card-body p-3">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="m-2">
                    <i class="fa-solid fa-screwdriver-wrench nav-dash-icon-size text-success"></i>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-1 m-2">
                    <h2 class="mb-0 bg-label-success rounded-pill p-2">{{$tickets}}</h2>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <!--/ Order Statistics -->

            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-3 col-xl-3 order-0 mb-4">
            <div class="card h-100">
                <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                    <h5 class="m-0 me-2 w-auto">Quittances</h5>
                </div>
                </div>
                <div class="card-body p-3">
                <div class="d-flex justify-content-center align-items-center">
                    <div class="m-2">
                    <i class="menu-icon fa-solid fa-clipboard-check nav-dash-icon-size nav-dash-icon-size text-warning"></i>
                    </div>
                    <div class="d-flex flex-column align-items-center gap-1 m-2">
                    <h2 class="mb-0 bg-label-warning rounded-pill p-2">{{$quittances}}</h2>
                    </div>
                </div>
                </div>
            </div>
            </div>
            <!--/ Order Statistics -->

        </div>
        <div class="row">
            <div class="col-lg-6 col-xl-6">
                <div class="card">
                    <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                            <h5 class="m-0 me-2 w-auto">Mon agenda</h5>
                            <a href="#" title="Gérer" class="w-auto text-secondary p-0">

                            </a>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="border border-1">
                            @forelse ($agendas as $agenda)
                                <div class="d-flex flex-row align-items-center border border-bottom cursor-pointer agenda"
                                data-bs-toggle="modal" data-bs-target="#eventModal-{{$agenda->id}}">
                                    <div class="p-2 col-7">{{$agenda->objet}}</div>
                                    <div class="p-2 col-5 text-end">
                                    <div>{{explode(' ',$agenda->start_time)[0]}} à {{substr(explode(' ',$agenda->start_time)[1], 0, 5)}}</div>
                                    </div>
                                </div>
                                <div class="modal" tabindex="-1" role="dialog" id="eventModal-{{$agenda->id}}">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title"></h5>
                                            </div>
                                            <div class="modal-body">
                                                <span style="border: 1px solid gray;padding: 5px;">{{explode(' ',$agenda->start_time)[0]}}</span> -
                                                <span style="border: 1px solid gray;padding: 5px;">{{explode(' ',$agenda->finish_time)[0]}}</span>
                                                <div class="mt-5">
                                                    <h5 class="">Lieu de rendez-vous</h5>
                                                    <p>Adresse : <span> {{$agenda->adresse_rdv}}</span></p>
                                                    <h5 class="">Locataire</h5>
                                                    <p><span>{{$agenda->locataire->TenantFirstName . '' . $agenda->locataire->TenantLastName }}</span></p>
                                                    <h5 class="">Description</h5>
                                                    <p> <span>{{$agenda->description}}</span></p>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-bs-dismiss="modal">FERMER</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <h4 class="text-center text-secondary mt-3">Aucun rendez-vous pour le moment</h4>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END GESTION -->
    </div>
    <!-- / Content -->
</div>
@endsection
