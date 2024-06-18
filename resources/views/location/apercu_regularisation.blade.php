@extends('proprietaire.index')

@section('contenue')
<style>
    .apercu p{
        line-height: 5px;
        font-size: 12px;
        color: black;
        font-weight: 300;
    }
    .tit{
        border-top: 2px solid black;
        border-bottom: 2px solid black;
    }
    .apercu .text p{
        line-height: 11px;
    }
    @media only screen and (max-width: 600px) {

        label{
            float:  left !important;
        }
        .card{
            box-shadow: none !important;
        }
        .apercu .text p{
        line-height: 20px;
    }
    }
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznTQsxqPgnpkI4DNTlrdUmTzrDgektczlKNRRhy5X5AAOnx5S09ydFYWWNSfcEqDTTHgtNA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="row tete">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top">{{__('location.confirmerRegularisation')}}</h3>
            </div>
        </div>
    </div>
    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7">
        <span class="label m-r-2" style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">INFORMATION</span>
        </p style="margin-top:50px;font-size:12px !important;">Le site prend en compte les provisions sur charges versées par le locataire pour la période choisie et les Charges récupérables (montants récupérables) saisis dans la rubrique "Finances". </p>
        <p>Ensuite il calcule la différence et crée un Revenu ou Dépense par rapport au résultat.</p>
    </div>
    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #ffff">
        <div class="card" id="ttt" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                Vérifiez et confirmez la régularisation
            </div>
            <div class="card-body">
                <div class="row align-middle mt-2">
                    <div class="col-md-2 text-end" style="margin-top:10px">
                        <label for="" class="form-label">RESULTAT</label>
                    </div>

                    <div class="col-md-3 col-sm-10">
                        <p style="margin-top:px;border:#3A87AD 1px solid;padding: 10px;"><span class="text-success">{{$regularisation->location->Logement->charge}}€</span> (en faveur du Propriétaire)</p>
                    </div>
                </div>
                <div class="row align-middle mt-2 p-3" >
                    <div class="col-md-2 text-end" style="margin-top:10px">
                        {{-- <label for="" class="form-label">RESULTAT</label> --}}
                    </div>

                    <div class="col-md-10 col-sm-12 apercu p-4 shadow-sm " id="pdf" style="border:#3A87AD 1px solid">
                        <div class="row">
                            <div class="col-6">
                                <p>{{Auth()->user()->first_name. ' ' . Auth()->user()->last_name}}</p>
                                {{-- <p>101 Mada</p>
                                <p>Madagascar</p> --}}
                            </div>
                            <div class="col-md-6 col-sm-10">
                                <p>A {{$regularisation->location->Locataire->civilite . ' ' . $regularisation->location->Locataire->TenantFirstName .' '. $regularisation->location->Locataire->TenantLastName}}</p>
                                <p>{{$regularisation->location->Locataire->TenantAddress}}</p>
                                <p>{{$regularisation->location->Locataire->TenantCity}}</p>
                                <p>{{$regularisation->location->Locataire->TenantZip}}</p>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <p>Date {{\Carbon\Carbon::now()->format('Y-m-d')}}</p>
                        </div>
                        <div class="row mt-3 p-2 tit">
                            <center>
                                <b style="font-size: 19px;color:black">Régularisation des charges locatives </b>
                            </center>
                        </div>
                        <div class="row mt-4 text">
                            <p>Bonjour {{$regularisation->location->Locataire->civilite . ' ' . $regularisation->location->Locataire->TenantFirstName .' '. $regularisation->location->Locataire->TenantLastName}},</p>
                            <p>Propriétaire du bien situé à {{$regularisation->location->Logement->adresse}}, j'ai procédé à une régularisation des charges locatives. </p>
                            <p>Le montant de cette régularisation est donc de {{$regularisation->location->Logement->charge}}€ en ma faveur. </p>
                            <p>Vous trouverez ci-après le bilan des charges réelles et des provisions de charges appelées sur la période {{\Carbon\Carbon::parse($regularisation->date_debut)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($regularisation->date_fin)->format('Y-m-d')}}. </p>
                            <p>Les pièces justificatives des charges sont tenues à votre disposition. </p>
                            <p>N’hésitez pas à me contacter si vous souhaitez des renseignements complémentaires.</p>
                            <p>Je vous prie de bien vouloir agréer, l’expression de mes sentiments distingués. </p>
                        </div>
                        <div class="row mt-4 text text-end">
                            <p>{{Auth()->user()->first_name. ' ' . Auth()->user()->last_name}}</p>
                        </div>
                        <div class="row mt-5 text">
                            <p>Bilan de la régularisation des charges sur la période: {{\Carbon\Carbon::parse($regularisation->date_debut)->format('Y-m-d')}} - {{\Carbon\Carbon::parse($regularisation->date_fin)->format('Y-m-d')}}</p>
                        </div>
                        <div class="row mt-2">
                            <p>Charges locatives réelles: {{$regularisation->location->Logement->charge}}€ </p>
                        </div>
                        <div class="row mt-2">
                            <p>Provisions sur charges appelées: {{$regularisation->location->Logement->charge}}€</p>
                        </div>
                        <div class="row mt-2">
                            <p>Crédit en faveur du Propriétaire : {{$regularisation->location->Logement->charge}}€</p>
                        </div>
                    </div>
                </div>
                <div class="row align-middle " >
                    <div class="col-md-2 text-end" style="margin-top:10px">
                        {{-- <label for="" class="form-label">RESULTAT</label> --}}
                    </div>
                    <div class="col-4">
                        <div class="dropdown" >
                            <button type="button" class="btn  dropdown-toggle" data-bs-toggle="dropdown" style="background: rgb(224, 215, 215);color:black">
                                <i class="fa-solid fa-download"></i>&nbsp;Télécharger
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{route('regularisantion.formatPDF',$regularisation->id)}}"  style="font-size: 14px"><i class="fa-regular fa-file-pdf"></i>&nbsp;Télécharger en format ADOBE PDF</a>
                                <a class="dropdown-item" href="{{route('regularisation.formatWord',$regularisation->id)}}" style="cursor:pointer;font-size: 14px"><i class="fa-regular fa-file-word"></i>&nbsp;Télécharger en format Word</a>
                                {{-- <a class="dropdown-item" href="" style="cursor:pointer;font-size: 14px"><i class="fa-solid fa-file-excel"></i>&nbsp;Télécharger en format Open Office</a> --}}
                            </div>
                        </div>
                        {{-- <button class="btn " style="background: rgb(224, 215, 215);color:black"><i class="fa-solid fa-download"></i>Télécharger</button> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="card" style="margin-top: 5px">
            <div class="row p-4">
                <div class="col-md-2">

                </div>
                <div class="col-10">
                    <a href="{{route('confirmation.regularisation',$regularisation->id)}}"  class="btn btn-primary"> Confirmer la régularisation  </a>
                    <a href="{{route('annuler.regularisation',$regularisation->id)}}" class="btn btn-secondary">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('#telechargerPDF').on('click',function(e){
                e.preventDefault()
                var doc = new jsPDF();
            // Source HTMLElement or a string containing HTML.
            var elementHTML = document.querySelector("#pdf");

            doc.html(elementHTML, {
                callback: function(doc) {
                    // Save the PDF
                    doc.save('sample-document.pdf');
                },
                x: 15,
                y: 15,
                width: 170, //target width in the PDF document
                windowWidth: 650 //window width in CSS pixels
            });
        })
</script>
@endsection
