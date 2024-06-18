@extends('layouts.appinner')
@push('scripts')
<style>
    .larg{width: 10rem;}
</style>
<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/payment.min.js') }}"></script>
@endpush
@section('content')


<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal" data-mdb-keyboard="false" data-mdb-backdrop="static">
        <div class="modal-dialog" style="margin-top: 4rem;">
        
        <!-- Modal content-->
            <div class="modal-content">
                <center>
                    <div class="modal-header">
                    
                    <h5 class="modal-title">{{ __('verif_payment.title') }}</h5>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('verif_payment.deja_paye') }}<strong><h4>{{ $packages->title }}</h4></strong> </p>
                        <br>
                        <p>{{ __('verif_payment.etes_vous_sur') }}</p>
                    </div>
                    <div class="modal-footer">
                        <center>
                            <a href="/" type="button" class="btn btn-warning larg"><strong>{{ __('verif_payment.oui') }}</strong></a>
                            <a href="/tableau-de-bord" type="button" class="btn btn-primary larg"><strong>{{ __('verif_payment.non') }}</strong></a>
                        </center>
                    </div>
                </center>
          </div>
          
        </div>
      </div>
</div>

    <script>
        $('#myModal').modal({backdrop: 'static', keyboard: false}) 
        $('#myModal').modal('show');
        
    </script>

<div style="height: 20rem"></div>
@endsection
