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
                    <h2 class="page-header page-header-top text-primary text-center mb-3"> ENVIE D'AVOIR PLUS D'ESPACE DE STOCKAGE POUR VOS DOCUMENTS ? </h2>
                    <h4 class="page-header page-header-top text-dark text-center mb-3"> Choisissez une offre de stockage pour optimiser la gestion de votre espace proprietaire </h4>
            </div>
            <div class="row mt-5">
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card text-center">
                        <div class="card-header">
                            <h4 class="text-success">{{$Package1->title}} 100 Mo</h4>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title text-gray">À partir de</h4>
                            <h2 class="text-success">€{{$Package1->amount}}</h2>
                            <h5 class="card-title text-gray">100 Mo de stockage en plus</h5>
                            <p class="card-text">{{$Package1->info}}</p>
                            <a href="javascript:void(0)" class="btn btn-success mb-4 checkout-button" id="100mo" data-id="{{$Package1->id}}">Choisir ce pass de stockage 100 Mo</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card text-center">
                        <div class="card-header"><h4 class="text-danger">{{$Package2->title}} 500 Mo</h4></div>
                        <div class="card-body">
                            <h4 class="card-title text-gray">À partir de</h4>
                            <h2 class="text-danger">€{{$Package2->amount}}</h2>
                            <h5 class="card-title text-gray">500 Mo de stockage en plus</h5>
                            <p class="card-text">{{$Package2->info}}</p>
                            <a href="javascript:void(0)" class="btn btn-danger mb-4 checkout-button" id="500mo" data-id="{{$Package2->id}}">Choisir ce pass de stockage 500 Mo</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card text-center">
                        <div class="card-header"><h4 class="text-warning">{{$Package3->title}} 1 Go</h4></div>
                        <div class="card-body">
                            <h4 class="card-title text-gray">À partir de</h4>
                            <h2 class="text-warning">€{{$Package3->amount}}</h2>
                            <h5 class="card-title text-gray">1 Go de stockage en plus</h5>
                            <p class="card-text">{{$Package3->info}}</p>
                            <a href="javascript:void(0)" class="btn btn-warning mb-4 checkout-button" id="1go" data-id="{{$Package3->id}}">Choisir ce pass de stockage 1 Go</a>
                        </div>
                    </div>
                </div>
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
    $(document).ready(function () {

        // Create an instance of the Stripe object with your publishable API key
        var stripe = Stripe("{{ config('customConfig.stripePublicApiKey') }}");
        $('.checkout-button').on('click', function () {
            var id = $(this).attr('data-id');
            stripeCheckout(id);
        });

        function stripeCheckout(package_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '{{route('package.checkout_session.stockage')}}',
                type: "POST",
                // dataType: "json",
                data: {
                    "package_id": package_id
                }
            }).done(function (session) {
                return stripe.redirectToCheckout({
                    sessionId: session.id
                });
                alert('rtyui');


            }).fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('No response from server');
            });
        }

    })

</script>
