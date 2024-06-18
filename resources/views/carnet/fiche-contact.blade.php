@extends('proprietaire.index')

@section('contenue')
<style>
    .square{
        width: 90px;
        height: 90px;
        line-height: 90px;
    }
</style>
<div class="content-wrapper">
    <!-- Content -->
      <div class="container-xxl flex-grow-1 container-p-y pt-0">
        <div class="row g-0 justify-content-center py-4">
            <div class="order-0 mb-4">
                <div class="card">
                    <div class="card-header border-bottom py-2 px-3 d-flex align-items-center justify-content-between">
                        <div class="card-title mb-0 row justify-content-between align-items-center w-100 g-0">
                            <strong class="fs-5">Informations</strong>
                        </div>
                    </div>
                    <div class="card-body p-3 border-1 border-bottom m-4 mb-0">
                        <div class="d-flex align-items-center">
                            <div class="square bg-warning text-center text-white fs-1 rounded-circle me-2">
                                {{ $contact->first_name[0] . $contact->name[0] }} 
                            </div>
                            <div>
                                <h4 class="mb-1">
                                    {{ $contact->first_name  . ' ' . $contact->name }}  
                                </h4>
                                <p class="mb-1"><strong>Mobile : </strong> <span>{{ $contact->mobile }}</span></p>
                                <p class="mb-0"><strong>Email : </strong> <span>{{ $contact->email }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="row m-4 gx-4 justify-content-between mb-2">
                        <div class="col-12 col-md-6 ps-0">
                            <div class="border-1 border-bottom mb-2">
                                <strong class="fs-5">Catégorie</strong>
                            </div>
                            <div>
                                <p>{{ $contact->category->name }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 pe-0">
                            <div class="border-1 border-bottom mb-2">
                                <strong class="fs-5">Adresse</strong>
                            </div>
                            <div>
                                <p class="mb-1"><strong>Adresse: </strong> <span>{{ $contact->adress }}</span></p>
                                <p class="mb-1"><strong>Ville: </strong> <span>{{ $contact->ville }}</span></p>
                                <p class="mb-1"><strong>Code postal: </strong> <span>{{ $contact->code_postal }}</span></p>
                                <p class="mb-0"><strong>Région: </strong> <span>{{ $contact->pays }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="m-2 mx-4">
                        <div class="border-1 border-bottom mb-2">
                            <strong class="fs-5">Banque</strong>
                        </div>
                        <div>
                            <p>Pas d'information</p>
                        </div>
                    </div>
                    <div class="m-2 mx-4">
                        <div class="border-1 border-bottom mb-2">
                            <strong class="fs-5">Notes</strong>
                        </div>
                        <div>
                            @if ($contact->comment)
                            <p>{{ $contact->comment }}</p>
                            @else
                            <p>Pas d'information</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
    <!-- / Content -->
  </div>
@endsection