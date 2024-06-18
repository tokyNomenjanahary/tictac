@extends('proprietaire.index')

@section('contenue')
<style>
    @media only screen and (max-width: 600px) {
        .lab-mob{
            float:  left !important;
        }
        .card{
            box-shadow: none !important;
        }
    }
</style>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="row tete">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>Dépense</h3>
            </div>
        </div>
    </div>


    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #ffff">
        <div class="card" id="ttt" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                Dépense
            </div>
            <form action="{{ route('ticket.saveDepenseTicket') }}" method="POST" enctype="multipart/form-data">
            @csrf
                <input type="text" hidden name="location_id" value="{{ $location->id }}" required>
                <input type="text" hidden name="locataire_id" value="{{ $location->locataire_id }}" required>
                <div class="card-body" style="margin-top: px;">
                    <div class="row align-middle mt-3">
                        <div class=" col-md-2 text-end  col-sm-12" style="margin-top:5px">
                            <label for="" class=" lab-mob form-label">Déscription</label>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <textarea name="description" required id="" class="form-control @error('description') is-invalid @enderror" class="w-100" rows="7" required>{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="row align-middle mt-2">
                        <div class="col-md-2 text-end" style="margin-top:5px">
                            <label for="" class="lab-mob  form-label">Bien</label>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <input type="text" name="bien" value="{{ $location->Logement->identifiant }}" class="form-control" readonly required>
                            <input type="text" hidden value=" {{ $location->logement_id }}" name="logement_id" required>
                            <input type="text" hidden value=" {{ $ticket_id }}" name="ticket_id" required>
                        </div>
                    </div>

                    <div class="row align-middle mt-2">
                        <div class="col-md-2 text-end" style="margin-top:5px">
                            <label for="" class="lab-mob form-label">Type</label>
                        </div>
                        <div class="col-md-10 col-sm-12" >
                            <select class="form-control" name="ticket_type" id="" required>
                                <option value="1">type 1</option>
                                <option value="2">type 2</option>
                            </select>
                        </div>
                    </div>

                    <div class="row align-middle mt-2">
                        <div class="col-md-2 text-end" style="margin-top:5px">
                            <label for="" class="lab-mob form-label">Payer à</label>
                        </div>
                        <div class="col-md-10 col-sm-12" >
                            <input name="payer_a" type="text" class="form-control" required>
                        </div>
                    </div>

                    <div class="row align-middle mt-2">
                        <div class="col-md-2 text-end" style="margin-top:5px">
                            <label for="" class="lab-mob form-label">Date</label>
                        </div>
                        <div class="col-md-10 col-sm-12" >
                            <input name="date_depense" type="date" class="form-control" required>
                        </div>
                    </div>

                    <div class="row align-middle mt-2">
                        <div class="col-md-2 text-end" style="margin-top:5px">
                            <label for="" class="lab-mob form-label">Montant</label>
                        </div>
                        <div class="col-md-10 col-sm-12" >
                            <input name="montant" type="number" class="form-control" required>
                        </div>
                    </div>

                </div>
        </div>
        <div class="card" style="margin-top: 5px">
            <div class="row p-4">
                <div class="col-md-2">

                </div>
                <div class="col-10">
                    <button   type="submit" class="btn btn-primary">Sauvegarder</button>
                    </form>
                    <a href="" class="btn btn-secondary">Annuler</a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection
