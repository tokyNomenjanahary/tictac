@extends('proprietaire.index')
<style>
    hr {
        margin-top: -10px;
    }

    p {
        line-height: 5px;
    }

    .nav-link.active {
        border-bottom: 3px solid #4C8DCB !important;
    }

    #image {
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }

    #image img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
    }
</style>
@section('contenue')
    <div class="p-12">
        <header class="bg-white shadow" style="margin:25px auto;margin-left:25px;margin-right: 25px">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="row">
                    <div class="col-md-12 p-3">
                        <div class="float-start">
                            {{-- <h3>Logement premier</h3> --}}
                        </div>
                        <div class="float-end">
                            <a href="/proprietaire" class="btn btn-dark">Retour</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-left:20px">
                <div class="col-md-6">
                    <h6>Informations</h6>
                    <div class="row " style="margin-top: -8px">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation" style="width: 40%">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#sary"
                                    type="button" role="tab" aria-controls="home" aria-selected="true">Photo</button>
                            </li>
                            <li class="nav-item" role="presentation" style="width: 40%">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#carte"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Carte</button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="sary" role="tabpanel" aria-labelledby="home-tab">
                                @if ($nbFile = count($detailLogement->files))
                                    <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="false"
                                        data-bs-interval="false">
                                        <div class="carousel-inner">
                                            <div class="carousel-item active">
                                                <img height="460"
                                                    src="{{ URL::asset('uploads/images_annonces/' . $detailLogement->files[0]->file_name) }}"
                                                    class="d-block w-100" alt=".....">
                                            </div>
                                            @for ($i = 1; $i < $nbFile; $i++)
                                                <div class="carousel-item">
                                                    <img height="460"
                                                        src="{{ URL::asset('uploads/images_annonces/' . $detailLogement->files[$i]->file_name) }}"
                                                        class="d-block w-100" alt=".....">
                                                </div>
                                            @endfor
                                            {{-- @foreach ($detailLogement->files as $file)
                                        <div class="carousel-item active">
                                            <img height="" src="{{ URL::asset('uploads/images_annonces/'.$detailLogement->files[0]->file_name) }}" class="d-block w-100" alt=".....">
                                        </div>
                                    @endforeach --}}
                                        </div>
                                        <button class="carousel-control-prev" type="button"
                                            data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Previous</span>
                                        </button>
                                        <button class="carousel-control-next" type="button"
                                            data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="visually-hidden">Next</span>
                                        </button>
                                    </div>
                                @else
                                    <div class="row">
                                        <img style="margin-left:-20px;"
                                            src="{{ asset('assets/img/lotie/no_image_600x450.png') }}" alt=""
                                            srcset="">
                                    </div>
                                @endif

                            </div>
                            <div class="tab-pane" id="carte" role="tabpanel" aria-labelledby="profile-tab"> Cart map
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <h3>{{ $detailLogement->identifiant }}</h3>
                        <hr>
                        <div class="col-md-12">
                            <div class="float-start">
                                <h5>{{ $typeLogement->property_type }}</h5>
                                <i class="fa-solid fa-door-open"></i><span
                                    style="margin-left: 5px">{{ $detailLogement->nbr_piece }}</span>
                                <i class="fa-solid fa-bed" style="margin-left: 10px"></i><span
                                    style="margin-left: 5px">{{ $detailLogement->nbr_chambre }}</span>
                                <i class="fa-solid fa-bath" style="margin-left: 10px"></i><span
                                    style="margin-left: 5px">{{ $detailLogement->salle_bain }}</span>
                            </div>
                            <div class="float-end">
                                <h5>Loyer</h5>
                                <h4 class="text-success">{{ $detailLogement->loyer }}€</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p>Adresse :</p>
                            <hr>
                            {{ $detailLogement->adresse }}
                        </div>
                        <div class="col-6">
                            <p>Déscription :</p>
                            <hr>
                            <h6>{{ $detailLogement->description }}</h6>
                        </div>
                    </div>
                    @if (count($listLogementEnfant) > 0)
                        <div class="row mt-3">
                            <hr>
                            <p>Chambres :</p>
                            @foreach ($listLogementEnfant as $logement)
                                <div class="col-6 mt-2">
                                    <a href="/detail/{{ $logement->id }}"
                                        target="_blank">{{ $logement->identifiant }}</a><a
                                        href="{{ route('proprietaire.deleteLogementEnfant', $logement->id) }}"
                                        class="ms-3"><i class='bx bx-x-circle' style="color: red"></i></a><br>
                                </div>
                            @endforeach
                            <hr class="mt-3">
                        </div>
                    @endif
                    <div class="row mt-3">
                        <div class="col-md-6 col-12">
                            <p>Loyer :</p>
                            <hr>
                            <p>Loyer hors charge : {{ $detailLogement->loyer }}€</p>
                            <p>Charge : {{ $detailLogement->charge }}€</p>
                        </div>
                        @if ($listEquipements)
                            <div class="col-6">
                                <p>Equipements</p>
                                <hr>
                                @foreach ($listEquipements as $list)
                                    <p>
                                        <i class='bx bx-check'></i>
                                        {{ $list['equipement'] }}
                                    </p>
                                @endforeach
                            </div>
                        @endif
                        {{-- <div class="col-6">
                  <p>Information d'habitation :</p><hr>
                  <p>Taxe d'habitation : 0€</p>
                  <p>Taxe foncière : 0.00 €</p>
                  <p>Prix d’acquisition : 0.00 €</p>
                  <p>Frais d'acquisition : 0.00 €</p>
                </div> --}}
                    </div>
                    <div class="row mt-3">

                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="row mt-4">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Location</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile"
                                    aria-selected="false">Note</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="messages-tab" data-bs-toggle="tab"
                                    data-bs-target="#messages" type="button" role="tab" aria-controls="messages"
                                    aria-selected="false">Documents</button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                <h5>Les locataires:</h5>
                                @foreach ($locataireDuLogement as $locataire)
                                    <div class="row shadow-sm p-3">
                                        <div class="col-md-3">


                                            @if ($locataire->TenantPhoto)
                                                @if (file_exists(storage_path('/uploads/locataire/profil/' . $locataire->TenantPhoto)))
                                                    <img style="width:90%;height:85%;"
                                                        src="{{ '/uploads/locataire/profil/' . $locataire->TenantPhoto }}"
                                                        alt="Avatar" class="rounded-circle avatar" />
                                                @endif
                                            @else
                                                <span class="badge text-center badge-center rounded-pill bg-primary"
                                                    style="width:95%;height:95%;font-size:60px">{{ strtoupper(substr($locataire->TenantFirstName, 0, 2)) }}</span>
                                                {{-- <img src="{{ asset('/Locataire/Profil.jpg') }}" alt="Avatar" class="rounded-circle avatar"/> --}}
                                            @endif

                                        </div>
                                        <div class="col-md-9">
                                            <p><u> Noms et prenoms </u>:
                                                {{ $locataire->TenantFirstName . ' ' . $locataire->TenantLastName }}</p>
                                            <p><u> Date de naissance</u> :
                                                {{ \Carbon\Carbon::parse($locataire->TenantBirthDate)->format('d/m/Y') }}
                                            </p>
                                            <p><u> Debut du bail</u> :
                                                {{ \Carbon\Carbon::parse($locataire->debutBail)->format('d/m/Y') }}</p>
                                            <p><u>Fin du bail</u> :
                                                {{ \Carbon\Carbon::parse($locataire->finBail)->format('d/m/Y') }}</p>
                                            <a href="" class="btn btn-secondary btn-sm">Voir contrat</a>
                                            <a href="" class="btn btn-danger btn-sm">Resilier le contrat</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <h5>Note</h5>
                                <input type="text" class="form-control" style="height: 200px">
                                <button class="btn btn-primary float-right mt-2">Sauvegarder</button>
                            </div>
                            <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">

                                @foreach ($document_sans_dossier as $doc)
                                    <div class="card mb-3 mt-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-1 sol-sm-12 text-center">
                                                    <i class="{{ getIconFile($doc->nomFichier) }}"
                                                        style="{{ getIconStyle($doc->nomFichier) }}"></i>
                                                </div>
                                                <div class="col-md-10 col-sm-12">
                                                    <p style=" line-height: normal"><a
                                                            style=" color: inherit;text-decoration: none;"
                                                            href="{{ route('documents.telecharger', ['id' => $doc->id]) }}">
                                                            {{ $doc->nomFichier }}
                                                        </a>
                                                    </p>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                                @foreach ($document_dossier as $key => $contenu_dossier)
                                    <div class="card mb-3 mt-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-10 ">

                                                    <a data-bs-toggle="collapse"
                                                        href="#collapse-{{ str_replace(' ', '-', $key) }}" role="button"
                                                        aria-expanded="false" aria-controls="collapseExample"
                                                        style="color: inherit ; text-decoration:inherit;">
                                                        <i class="fa-solid fa-folder fa-2x"></i><span
                                                            style="margin-left: 10px;margin-bottom:0px">{{ $key }}</span>
                                                    </a>
                                                    <ul class="collapse" id="collapse-{{ str_replace(' ', '-', $key) }}">
                                                        @foreach ($contenu_dossier as $contenu)
                                                            <li class="menu-item" style="margin-top: 10px">
                                                                <div class="row g-0 align-items-center">
                                                                    <div class="w-auto">
                                                                        <i
                                                                        class="{{ getIconFile($contenu->nomFichier) }}"
                                                                        style="{{ getIconStyle($contenu->nomFichier) }}"></i>
                                                                    </div>
                                                                    <div class="w-auto">
                                                                        <p style="line-height: normal"  class="m-0"> <a
                                                                                style=" color: inherit;text-decoration: none;"
                                                                                href="{{ route('documents.telecharger', ['id' => $contenu->id]) }}"><span
                                                                                    style="margin-left: 10px;margin-bottom:0px">{{ $contenu->nomFichier }}</span>
                                                                            </a>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
@endsection
