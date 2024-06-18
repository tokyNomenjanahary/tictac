@extends('proprietaire.index')

@section('contenue')
    <!-- / Navbar -->
    <style>
        .mx-w {
            max-width: 100px;
            min-height: 100px;
        }

        .wp-100 {
            width: 200px;
        }
    </style>
    {{-- Guide --}}
    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl container-p-y flex-column h-100 g-0 align-items-center d-flex">
            <div class="p-4 card flex-grow-1 d-flex align-items-center justify-content-between col-12">
                <div class="row justify-content-center g-0 col-12">
                    <div class="col-12 col-sm-2 col-md-2 text-center">
                        <a class="text-secondary cursor-pointer @if (Auth::user()->owner_step == 1) d-none @endif"
                        @if (Auth::user()->owner_step == 2)
                        href="{{route('proprietaire.nouveaux')}}"
                        @elseif (Auth::user()->owner_step == 3)
                        href="{{route('locataire.ajouterColocataire')}}"
                        @elseif (Auth::user()->owner_step == 4)
                        href="{{route('logement.create.location')}}"
                        @endif
                        >
                            <span><i class="fa-solid fa-circle-chevron-left"></i> </span>
                            Précedent
                        </a>
                    </div>
                    <div class="col-12 col-sm-2 col-md-2">
                        <p class="text-center text-secondary fw-bolder cursor-pointer">
                            <span><i class="fa-solid fa-circle-check @if (Auth::user()->owner_step > 1) text-success @endif"></i> </span>
                            Nouveau bien
                        </p>
                    </div>
                    <div class="col-12 col-sm-2 col-md-2">
                        <p class="text-center text-secondary fw-bolder cursor-pointer">
                            <span><i class="fa-solid fa-circle-check @if (Auth::user()->owner_step > 2) text-success @endif"></i> </span>
                            Nouveau locataire
                        </p>
                    </div>
                    <div class="col-12 col-sm-2 col-md-2">
                        <p class="text-center text-secondary fw-bolder cursor-pointer">
                            <span><i class="fa-solid fa-circle-check @if (Auth::user()->owner_step > 3) text-success @endif"></i> </span>
                            Nouveau location
                        </p>
                    </div>
                    <div class="col-12 col-sm-2 col-md-2">
                        <p class="text-center text-secondary fw-bolder cursor-pointer">
                            <span><i class="fa-solid fa-circle-check"></i> </span>
                            commencer
                        </p>
                    </div>
                    <div class="col-12 col-sm-2 col-md-2 text-center cursor-pointer">
                        <a class="text-secondary @if (Auth::user()->owner_step == 4 || Auth::user()->owner_step == 1) d-none @endif"
                            @if (Auth::user()->owner_step == 2)
                            href="{{route('locataire.ajouterColocataire')}}"
                            @elseif (Auth::user()->owner_step == 3)
                            href="{{route('logement.create.location')}}"
                            @elseif (Auth::user()->owner_step == 4)
                            href="{{route('proprietaire.ready')}}"
                            @endif>
                            Suivant <span> <i class="fa-solid fa-circle-chevron-right"></i></span>
                        </a>
                    </div>
                </div>
                <div class="col-12 my-5">
                    @if (Auth::user()->logements->isEmpty() && Auth::user()->owner_step >= 1)
                    <p class="h3 text-center"> Première étape, <span class="text-primary">créer un nouveau bien</span></p>
                    @elseif (!Auth::user()->logements->isEmpty() && Auth::user()->locataires->isEmpty() && Auth::user()->owner_step >= 2)
                    <p class="h3 text-center"> Deuxième étape, <span class="text-primary">créer un nouveau Locataire</span></p>
                    @elseif (!Auth::user()->logements->isEmpty() && !Auth::user()->locataires->isEmpty() && Auth::user()->locations->isEmpty() && Auth::user()->owner_step >= 3)
                    <p class="h3 text-center"> Vous y êtes presque, plus qu'une étape avant de <span class="text-primary">gérer vos locations</span></p>
                    @else
                    <p class="h3 text-center">Commencer votre <span class="text-primary">gestion locative</span></p>
                    @endif
                </div>
                <div class="col-12 row justify-content-around">
                    <a href="{{route('proprietaire.nouveaux')}}" class="p-0 nav-dash-container wp-100">
                        <div class="text-center rounded-circle border-2 @if (Auth::user()->owner_step > 1) {{'bg-primary'}}  @else {{'bg-secondary'}} @endif bg-secondary mx-w row justify-content-center align-items-center g-0 mx-auto cursor-pointer">
                            <div class="w-auto m-0 p-0">
                            <p class="h1 text-white mb-0">1</p>
                            </div>
                        </div>
                        <div class="w-auto mt-3">
                            <p class="form-label text-center">Créer un bien</p>
                        </div>
                        <div class="text-center @if (Auth::user()->owner_step > 1) {{'d-block text-success'}}  @else {{'bg-secondary d-none'}} @endif">
                            <i class="fa fa-check fs-1"></i>
                        </div>
                    </a>

                    <a href="{{route('locataire.ajouterColocataire')}}" class="p-0 nav-dash-container wp-100">
                        <div class="text-center rounded-circle border-2 @if (Auth::user()->owner_step > 2) {{'bg-primary'}}  @else {{'bg-secondary'}} @endif bg-secondary mx-w row justify-content-center align-items-center g-0 mx-auto cursor-pointer">
                            <div class="w-auto m-0 p-0">
                                <p class="h1 text-white mb-0">2</p>
                            </div>
                        </div>
                        <div class="w-auto mt-3">
                            <p class="form-label text-center">Créer un locataire</p>
                        </div>
                        <div class="text-center @if (Auth::user()->owner_step > 2) {{'d-block text-success'}}  @else {{'bg-secondary d-none'}} @endif">
                            <i class="fa fa-check fs-1"></i>
                        </div>
                    </a>

                    <a href="{{route('logement.create.location')}}" class="p-0 nav-dash-container wp-100">
                        <div class="text-center rounded-circle border-2 @if (Auth::user()->owner_step > 3) {{'bg-primary'}}  @else {{'bg-secondary'}} @endif bg-secondary mx-w row justify-content-center align-items-center g-0 mx-auto cursor-pointer">
                            <div class="w-auto m-0 p-0">
                            <p class="h1 text-white mb-0">3</p>
                            </div>
                        </div>
                        <div class="w-auto mt-3">
                            <p class="form-label text-center">Créer une location</p>
                        </div>
                        <div class="text-center @if (Auth::user()->owner_step > 3) {{'d-block text-success'}}  @else {{'bg-secondary d-none'}} @endif">
                            <i class="fa fa-check fs-1"></i>
                        </div>
                    </a>

                    <a href="{{route('proprietaire.ready')}}" class="p-0 nav-dash-container wp-100">
                        <div class="text-center rounded-circle border-2 @if (Auth::user()->owner_step > 4) {{'bg-primary'}}  @else {{'bg-secondary'}} @endif mx-w row justify-content-center align-items-center g-0 mx-auto cursor-pointer">
                            <div class="w-auto m-0 p-0">
                            <p class="h1 text-white mb-0">4</p>
                            </div>
                        </div>
                        <div class="w-auto mt-3">
                            <p class="form-label text-center">Commencez votre gestion</p>
                        </div>
                        <div class="text-center @if (Auth::user()->owner_step > 4) {{'d-block text-success'}}  @else {{'bg-secondary d-none'}} @endif">
                            <i class="fa fa-check fs-1"></i>
                        </div>
                    </a>
                </div>
                <div class="col-12 text-center my-5 row justify-content-center g-4">
                    <a class="col-12 col-md-2 text-white btn rounded-pill btn-primary mx-2 @if (Auth::user()->owner_step < 4) {{'d-none'}} @endif" href="{{route('proprietaire.ready')}}">Commencer</a>
                    <a class="col-12 col-md-2 text-white btn rounded-pill btn-primary mx-2 @if (Auth::user()->owner_step == 1 || Auth::user()->owner_step == 4) {{'d-none'}} @endif"
                        @if (Auth::user()->owner_step == 2)
                        href="{{route('locataire.ajouterColocataire')}}"
                        @elseif (Auth::user()->owner_step == 3)
                        href="{{route('logement.create.location')}}"
                        @endif
                        >
                        Continuer
                    </a>
                    <a class="col-12 col-md-2 text-white btn rounded-pill btn-warning mx-2 @if (Auth::user()->owner_step > 3) {{'d-none'}} @endif" href="{{route('proprietaire.ready')}}">Ignorer</a>
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->
    {{-- end guide --}}
@endsection