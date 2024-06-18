@extends('proprietaire.index')
<style>


    th {
        color: blue !important;
        font-size: 10px !important;
    }

    td {
        font-size: 13px;
    }

    .dataTables_length {
        display: none !important;
    }

    .dataTables_empty {
        display: none !important;
    }

    .dataTables_info {
        display: none !important;
    }
    div.dataTables_wrapper div.dt-row {
        position: static !important;
    }
</style>
@section('contenue')
    <div class="content-wrapper"
         style="font-family: Manrope, -apple-system,BlinkMacSystemFont,segoe ui,Roboto,Oxygen,Ubuntu,Cantarell,open sans,helvetica neue,sans-serif;">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row tete mt-4">
                <h2 class="page-header page-header-top text-primary text-center mb-3"> CONFIRMATION DU PAYEMENT DE {{$Package->amount}} € POUR L'ABONNEMENT {{$Package->title}}</h2>
                <h4 class="page-header page-header-top text-dark text-center mb-3">Vous disposer maintenant de {{$Package->value}} de stockage en plus en plus</h4>
            </div>
        </div>
    </div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
        integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>

</script>
