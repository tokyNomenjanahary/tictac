@extends('espace_locataire.index')

@section('locataire-contenue')
    @if($locataireInfo)
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="card p-5">
                        <h5 class="text-info" style="margin-bottom: 0px">{{__("ticket.informations")}}</h5>
                        <hr>
                        <div class="mb-3 p-2 rounded-0">
                            <div class="row">
                                <div class="col-md-5">
                                    <p>{{__("ticket.nom")}}</b>: {{ $locataireInfo->TenantFirstName.' '.$locataireInfo->TenantLastName }}</p>
                                    <p>{{__("ticket.naissance")}} : {{  $locataireInfo->TenantBirthDate .' A '. $locataireInfo->TenantBirthPlace }}</p>
                                </div>
                                <div class="col-md-7 text-end">
                                    @if($locataireInfo->TenantPhoto)
                                        <img style="height:120px!important;width:120px!important;"
                                             src="{{ '/uploads/locataire/profil/' . $locataireInfo->TenantPhoto }}"
                                             alt="">
                                    @else
                                        <img style="height:120px!important;width:120px!important;"
                                             src="https://previews.123rf.com/images/thesomeday123/thesomeday1231712/thesomeday123171200009/91087331-ic%C3%B4ne-de-profil-d-avatar-par-d%C3%A9faut-pour-le-m%C3%A2le-espace-r%C3%A9serv%C3%A9-photo-gris-vecteur-d.jpg"
                                             alt="">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <h5 class="text-info"  style="margin-bottom: 0px">{{__("ticket.situation")}}</h5>
                        <hr>
                        <div class="mb-3 p-2 rounded-0">
                            <div class="row">
                                <div class="col-md-5">
                                    <p>{{__("ticket.Profession")}} : {{  $locataireInfo->TenantProfession }}</p>
                                    <p>{{__("ticket.Revenus ")}} : {{  $locataireInfo->TenantRevenus }}</p>
                                </div>
                                <div class="col-md-7 text-end">
                                    @if($locataireInfo->TenantIDCard)
                                        <img style="height:120px!important;width:120px!important;"
                                             src="{{ '/uploads/locataire/cin/' . $locataireInfo->TenantIDCard }}"
                                             alt="">
                                    @else
                                        <img style="height:120px!important;width:120px!important;"
                                             src="https://previews.123rf.com/images/thesomeday123/thesomeday1231712/thesomeday123171200009/91087331-ic%C3%B4ne-de-profil-d-avatar-par-d%C3%A9faut-pour-le-m%C3%A2le-espace-r%C3%A9serv%C3%A9-photo-gris-vecteur-d.jpg"
                                             alt="">
                                    @endif
                                </div>
                            </div>
                        </div>
                        <h5 class="text-info"  style="margin-bottom: 0px">{{__("ticket.contact ")}}</h5>
                        <hr>
                        <div class="mb-3 p-2 rounded-0">
                            <p>{{__('echeance.Email')}} : {{  $locataireInfo->TenantEmail }}</p>
                            <p>{{__('echeance.Téléphone')}} : {{  $locataireInfo->TenantMobilePhone }}</p>
                            <p>{{__("ticket.Adresse")}} : {{  $locataireInfo->TenantAddress }}</p>
                            <p>{{__("ticket.postal")}} : {{  $locataireInfo->TenantZip }}</p>
                        </div>
                        <h5 class="text-info"  style="margin-bottom: 0px">{{__("ticket.professionnelle")}}</h5>
                        <hr>
                        <div class="mb-3 p-2 rounded-0">
                            <p>{{__("ticket.Adresse")}}: @if($locataireInfo->LocatairesComplementaireInformations)
                                    {{  $locataireInfo->LocatairesComplementaireInformations->adresseProfesionnel }}
                                       @endif
                            </p>
                            <p>{{__("ticket.Employeur")}}
                                @if($locataireInfo->LocatairesComplementaireInformations)
                                {{  $locataireInfo->LocatairesComplementaireInformations->NameSociete }}
                                @endif
                            </p>
                        </div>
                        <h5 class="text-info"  style="margin-bottom: 0px">{{__("ticket.Coordonnées")}}</h5>
                        <hr>
                        <div class="mb-3 p-2 rounded-0">
                            <p>{{__("ticket.Banque")}} :
                                @if($locataireInfo->LocatairesComplementaireInformations)
                                {{  $locataireInfo->LocatairesComplementaireInformations->banque }}
                                @endif
                            </p>
                            <p>{{__("ticket.Adresse")}} :
                                @if($locataireInfo->LocatairesComplementaireInformations)
                                {{  $locataireInfo->LocatairesComplementaireInformations->adresseBanque }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
    @else
              <div class="container">
                  <div class="row tete mt-4">
                      <div class="col-lg-4 col-sm-4 col-md-4 titre">
                          <h3 class="page-header page-header-top">{{__("ticket.Proprietaires")}}</h3>
                      </div>
                  </div>
                  <div class="alert  m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7; border-left: 4px solid rgb(58,135,173);">
                       <span class="label m-r-2"
                             style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{__("ticket.INFORMATION")}}</span>
                      </p style="margin-top:50px;font-size:12px !important;">{{__("ticket.alertinfo")}}</p>
                  </div>
              </div>
    @endif
@endsection
