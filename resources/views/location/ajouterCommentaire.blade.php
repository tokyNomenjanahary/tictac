@extends('proprietaire.index')
<style>
     @media only screen and (max-width: 600px) {
            .card{
                box-shadow: none !important;

            }
            .lab-mob{
                float:  left !important;
            }
        }
</style>
@section('contenue')
<div class="container">
    <div class="row" style="margin-top: 30px;">
        <div class="row tete">
            <div class="col-lg-4 col-sm-4 col-md-4 titre">
                <h3 class="page-header page-header-top"> <a href="javascript:history.go(-1)"> <i class="fas fa-chevron-left"></i> </a>{{__('location.addComment')}}</h3>
            </div>
        </div>
    </div>


    <div class="alert m-t-15 m-b-0 m-l-10 m-r-10" style="background-color: #ffff">
        <div class="card" id="ttt" style="margin-top: 5px">
            <div class="card-header"
                style="color:#4C8DCB;padding:10px;background-color:#F5F5F9;margin-top:20px;border-radius:0px;">
                {{__('location.addComment')}}
            </div>
            <form  action="{{route('enregistrement.commentaire')}}"  method="POST">
                @csrf
            <div class="card-body" style="margin-top: px;">
                <div class="row align-middle">
                    <div class="col-md-2 text-end" style="margin-top:5px">
                        <label for="" class="form-label lab-mob">NOTES</label>
                        <input type="hidden" name="location_id" value="{{$location->id}}">
                    </div>
                    <div class="col-10">
                        <textarea name="commentaire" id="" style="width: 100%" rows="10">{{$location->commentaires}}</textarea>
                    </div>
                </div>

            </div>
            <div id="import-table"></div>
        </div>
        <div class="card" style="margin-top: 5px">
            <div class="row p-4">
                <div class="col-md-2 col-sm-0">

                </div>
                <div class="col-md-10 col-sm-10 bt-mob" style="dispay: flex">
                    <a href="/location" class="btn btn-secondary">{{__('location.Annuler')}}</a>
                    <button   type="submit" id="valid" class="btn btn-primary"> {{__('location.enregistrer')}}  </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
