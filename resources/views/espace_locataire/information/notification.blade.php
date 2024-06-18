@extends('espace_locataire.index')

@section('locataire-contenue')
    <div class="container">
        <div class="card mt-5">
            <h4 class="card-title" style="padding: 10px;background-color: #F2F5F6;">{{__('quittance.Notification')}}</h4>
            <div class="card-body">
                @if(count($etat_finance)>0)
                    <div class="row">
                        <div class="alert  m-b-0 m-l-10 m-r-10" style="background-color: #F2DEDE; border-left: 4px solid rgb(58,135,173);">
                       <span class="label m-r-2"
                             style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{__('finance.Loyer_en_retard')}}</span>
                            <p style="margin-top:10px;font-size:12px;"> {{__('quittance.Vous_avez')}} {{count($etat_finance)}} {{__('quittance.payement_en_retard_cette_mois')}} </p>
                            <ul>
                                @foreach($etat_finance as $subArray)
                                    @foreach($subArray as $item)
                                        <li>{{$item}}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
                @if(count($etat_location)>0)
                    <div class="row">
                        <div class="alert  m-b-0 m-l-10 m-r-10" style="background-color: #F2DEDE; border-left: 4px solid rgb(58,135,173);">
                       <span class="label m-r-2"
                             style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{__('quittance.Fin_de_bail')}}</span>
                            <p style="margin-top:10px;font-size:12px !important;">{{__('quittance.Vous_avez')}} {{count($etat_location)}} {{__('quittance.location_qui_aura_prendre_fin_cette_mois')}} :</p>
                            <ul>
                                @foreach($etat_location as $subArray)
                                    @foreach($subArray as $item)
                                        <li>{{$item}}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                @endif
                </div>
            </div>
        </div>
    </div>
@endsection
