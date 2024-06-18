
@extends('proprietaire.index')

@section('contenue')
    <style>
        label{
            font-size: 12px;
            color: rgb(88, 85, 85);
        }
        p{
            font-size: 12px;
        }

        @media only screen and (max-width: 600px) {
            .lab-mob{
                float:  left !important;
            }
            .card{
                box-shadow: none !important;
            }
            label{
                float:  left !important;
            }
        }
    </style>
    <div class="container">
        <div class="row" style="margin-top: 30px;">
            <div class="row tete">
                <div class="col-lg-4 col-sm-4 col-md-4 titre">
                    <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.terminer')}}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="p-12" style="margin-top:-20px;">
        <form action="{{route('location.depart')}}" method="POST">
            @csrf
        <header class="bg-white " style="margin:25px auto;margin-left:25px;margin-right: 25px">
            {{-- <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8"> --}}
                <div class="card mt-2" style="margin-top: 5px;box-shadow:none ">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                        {{__('location.terminer')}}
                    </div>
                    <div class="card-body mt-3 ">
                        <div class="row" >
                            <div class="col-md-2 col-sm-10 text-end">
                                <label for="" style="margin-top:8px;">{{__('location.dateDepart')}}</label>
                            </div>
                            <div class="col-md-2 col-sm-10" >
                                <input type="date" name="date_depart" value="{{$location->fin}}" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="card-body " style="margin-top:-40px;">
                        <div class="row" >
                            <div class="col-md-2 col-sm-10 text-end">
                                <label for="" style="margin-top:10px;">{{__('location.nouvelleAdresse')}}</label>
                            </div>
                            <div class="col-md-8 col-sm-10" >
                                <input type="text" name="Adresse" class="form-control" value="@if($depart === null)  @else {{$depart->Adresse}} @endif">
                                <p style="margin-top: 5px;">{{__('location.textDepart')}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="margin-top:-40px;">
                        <div class="row" >
                            <div class="col-md-2 col-sm-10 text-end">
                                <label for="">{{__('location.comDepart')}}</label>
                            </div>
                            <div class="col-md-8 col-sm-10" >
                                <textarea name="Commentaire" id="" style="width: 100%"  rows="5">@if($depart === null)  @else {{$depart->Commentaire}} @endif</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="location_id" value="{{$location->id}}">


                <div class="card mt-2" style="margin-top: 5px;box-shadow:none ">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                        {{__('location.depot')}}
                    </div>
                    <div class="card-body mt-3 ">
                        <div class="row" >
                            <div class="col-md-2 col-sm-10 text-end">
                                <label for="" style="margin-top:8px;">{{__('location.depotRestituer')}}</label>
                            </div>
                            <div class="col-md-4 col-sm-10" >
                                <input type="text" name="depot" value="@if($depart === null)  @else {{$depart->depot}} @endif" class="form-control">
                                <p style="margin-top: 5px;">{{__('location.textRestituer')}}</p>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-md-2 col-sm-10 text-end">
                                <label for="" style="margin-top:8px;">{{__('location.dateRestitution')}}</label>
                            </div>
                            <div class="col-md-4 col-sm-10" >
                                <input type="date" name="date_restitution" value="{{ optional($depart)->date_depart ? \Carbon\Carbon::parse($depart->date_depart)->format('Y-m-d') : \Carbon\Carbon::now()->toDateString() }}" class="form-control">
                                <input type="hidden" name="location_id" value="{{$location->id}}">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card mt-2" style="margin-top: 5px;box-shadow:none ">
                    <div class="card-header"
                        style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                        {{__('location.dernierQuitance')}}
                    </div>
                    <div class="card-body mt-3 ">
                        <div class="row" >
                            <div class="col-md-2 col-sm-10 text-end">
                                <label for="" style="margin-top:8px;">{{__('location.TerminerFin')}}</label>
                            </div>
                            <div class="col-md-8 col-sm-10 " >
                                <input type="date" id="finquitance" style="width: 200px" class="form-control">
                                <p class="text-danger" id="date_err"></p>
                                <p style="margin-top: 5px;">{{__('location.textTerminerFin')}}</p>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-2"></div>
                            <div class="col-2">
                                <a class="btn btn-secondary btn-sm text-white" id="butti" onclick="calcule_date()">{{__('location.calculer')}}</a>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-2 col-sm-10 text-end">
                                <label for="" style="margin-top:8px;">{{__('location.LoyerHC')}}</label>
                            </div>
                            <div class="col-md-4 col-sm-10" >
                                <input type="text" name="loyer" id="dernier_loyer" placeholder="€" class="form-control">
                                <input type="hidden" id="loyer_actuelle" value="{{$location->Logement->loyer}}">
                                <input type="hidden" id="charge_actuelle" value="{{$location->Logement->charge}}">
                            </div>
                        </div>
                        <div class="row mt-2" >
                            <div class="col-md-2 col-sm-10 text-end">
                                <label for="" style="margin-top:8px;">CHARGE</label>
                            </div>
                            <div class="col-md-4 col-sm-10" >
                                <input type="text" name="charge" id="dernier_charge" placeholder="€" class="form-control">
                                <input type="hidden" name="location_id" value="{{$location->id}}">
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card" style="margin-top: 5px">
                    <div class="row">
                        <div class="col-md-12" style="padding: 15px;">
                            <center>
                                <a class="btn btn-dark"  style="color:white;">{{ __('depense.Annuler') }}</a>
                                <button type="submit"  class="btn btn-primary"> {{__('location.enregistrer')}} </button>
                            </center>
                        </div>
                    </div>
                </div>
        </header>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>

        function calcule_date(){
            var date_entree = $("#finquitance").val();
            if(date_entree == ''){
                // alert('entrée un date');
                $('#finquitance').addClass('is-invalid')
                $('#date_err').text('entré une date valide')
            }else{
                $('#date_err').text('')
                $('#finquitance').removeClass('is-invalid')
                // Convertissez la chaîne de caractères de la date en un objet de date
                var date = new Date(date_entree);
                // Récupérez le mois actuel
                var mois_actuel = new Date().getMonth();
                // Récupérez le nombre de jours dans le mois actuel
                var jours_dans_mois = new Date(new Date().getFullYear(), mois_actuel+1, 0).getDate();
                // Calculez le nombre de jours entre la date entrée par l'utilisateur et la fin du mois actuel
                var jours_restants = jours_dans_mois - date.getDate();
                // Calculez le montant du loyer pour le nombre de jours restants
                var montant_total = $('#loyer_actuelle').val(); // Supposons que le montant total du loyer est de 1000 €
                var charge_total = $('#charge_actuelle').val(); // Supposons que le montant total du loyer est de 1000 €
                var montant_payer = (montant_total / jours_dans_mois) * jours_restants;
                var charge = (charge_total / jours_dans_mois) * jours_restants;
                // Affichez le montant du loyer à payer dans une zone de texte
                // $("#montant_payer").val(montant_payer);
                $('#dernier_loyer').val((montant_total - montant_payer).toFixed(2));
                $('#dernier_charge').val((charge_total - charge).toFixed(2));
            }
        }
        $(document).ready(function () {
            $("#butti").prop("disabled", true);
            $('#finquitance').on('click',function(){
                $("#butti").prop("disabled", false);
            })
            var fin_quittance = $('#finquitance');

            // Obtenez la date actuelle
            var date_actuelle = new Date();
            // Définissez le jour de la date actuelle sur le dernier jour du mois
            date_actuelle.setDate(new Date(date_actuelle.getFullYear(), date_actuelle.getMonth()+1, 0).getDate());
            // Définissez la valeur maximale de l'entrée de date sur la date actuelle
            fin_quittance.attr('max', date_actuelle.toISOString().split('T')[0]);

        });
    </script>


@endsection
