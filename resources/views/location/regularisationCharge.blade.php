@extends('proprietaire.index')
<style>
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
            .apercu p{
                /* line-height: 10px; */
                font-size: 10px;
                color: black;
                /* font-weight: 300; */
            }
        }
</style>
@section('contenue')
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="row tete">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.regularisation')}}</h3>
            </div>
        </div>
    </div>
    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #D9EDF7">
        <span class="label m-r-2 text-uppercase" style="background-color: #3A87AD;color:white;padding:5px;font-size:10px;">{{__('location.information')}}</span>
        </p style="margin-top:50px;font-size:12px !important;">{{__('location.textinfo1')}}</p>
        <p>{{__('location.textinfo2')}}</p>
    </div>


    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #ffff">
        <div class="card" id="ttt" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                {{__('location.regularisation')}}
            </div>
            <form  action="{{route('enregistrement.regularisantion')}}"  method="POST">
                @csrf
            <div class="card-body" style="margin-top: px;">
                <div class="row align-middle">
                    <div class="col-md-2 text-end" style="margin-top:5px">
                        <label for="" class="form-label text-uppercase">{{__('location.location')}}</label>
                    </div>

                    <div class="col-md-6 col-sm-12">
                        <select name="location_id" id="" class="form-select">
                            @foreach ($locations as $location)
                            <option value="{{$location->id}}" @if($location->id == $location_actuele->id) selected @endif>{{$location->Logement->identifiant .' - '. $location->Locataire->TenantFirstName .' '. $location->Locataire->TenantLastName . ' (' . \Carbon\Carbon::parse($location->debut)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($location->fin)->format('d/m/Y') . ')'}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-1 ">
                    <div class="col-md-2 text-end" style="margin-top:5px">
                        <label for="" class="form-label">{{__('location.PERIODE')}}</label>
                    </div>

                    <div class="col-md-2 col-sm-6">
                        <input type="date" name="date_debut" class="form-control  @error('date_debut') is-invalid @enderror ">
                    </div>
                    <div class="col-md-1 col-sm-6 text-center align-middle">
                        <p style="margin-top:5px">{{__('location.au')}}</p>
                    </div>
                    <div class="col-md-2 col-sm-6">
                        <input type="date" name="date_fin" class="form-control  @error('date_fin') is-invalid @enderror ">
                    </div>
                </div>
                <div class="row mt-1 ">
                    <div class="col-md-2 text-end" style="margin-top:5px">
                        <label for="" class="form-label">NOTIFICATION</label>
                    </div>
                    <div class="col-10" style="margin-top:8px;">
                        <input type="checkbox" name="notifier">&nbsp;&nbsp;{{__('location.notifLoc')}}
                    </div>
                </div>
            </div>
            <div id="import-table"></div>
        </div>
        <div class="card" style="margin-top: 5px">
            <div class="row p-4">
                <div class="col-md-2">

                </div>
                <div class="col-10">
                    <button   type="submit" id="valid" class="btn btn-primary"> {{__('location.continuer')}}  </button>
                    <a href="" class="btn btn-secondary">{{__('location.Annuler')}}</a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
