@extends('layouts.app')

@section('content')
    @push('styles')

        <style>
            .mt-2 {
                margin-top: 2rem;
            }
            .p-2 {
                padding: 2rem;
            }
            .card-header {
                padding: .5rem 1rem;
                margin-bottom: 0;
                background-color: rgba(0,0,0,.03);
                border-bottom: 1px solid rgba(0,0,0,.125);
            }
            .no-display {
                display: none;
            }
        </style>

    @endpush
    @push('scripts')

        <script>

            $(document).ready(function () {
                $('#btn-reactivation').click(function (event) {
                    event.preventDefault()

                    console.log('#btn-reactivation', $(this).data('url'))

                    btn = $(this);

                    $.ajax({
                        type: "GET",
                        url: btn.data('url'),
                        success: function(data) {
                            console.log('#btn-reactivation', data.statut)

                            if (data.statut) {
                                $('.alert-success').removeClass( "d-none no-display" );
                                //btn.removeAttr("disabled");
                                btn.addClass( "d-none no-display");

                                $('#btn-login').removeClass( "d-none no-display" );

                                // $("#confirmation-modal").modal('hide');
                            }else {
                                $('.alert-danger').removeClass( "d-none no-display" );
                            }
                        },
                        error: function(data) {
                            $('#alert-danger').removeClass( "d-none no-display" );
                        }
                    });
                })
            })
        </script>
    @endpush

    <section class="inner-page-wrap">

        <div id="popup-modal-body-login" class="login-form-x rent-property-form-content project-form edit-pro-content-1 white-bg m-t-20">

            {{--@if ($message = Session::get('success'))--}}
                <div class="alert alert-success fade in alert-dismissable no-display"  style="margin-top:20px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                    {{  __('reactivation.message_success_reactivation') }}
                </div>
            {{--@endif--}}
            {{--@if ($message = Session::get('error'))--}}
                <div class="alert alert-danger fade in alert-dismissable no-display" style="margin-top:20px;">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="{{ __('close') }}">×</a>
                    {{  __('reactivation.message_error_reactivation') }}
                </div>
            {{--@endif--}}

            <div class="card mt-2">
                    <div class="card-header">
                        <h4 class="card-title">{{  __('reactivation.reactivation_annonce') }}</h4>
                    </div>
                    <div class="card-body p-2">
                        @if($ad)
                            <h5 class="card-title"> {{  __('reactivation.titre_annonce') }} : {{ $ad->title }}</h5>
                            <p class="card-text">{{  __('reactivation.dernier_mise') }} : {{ $ad->updated_at->format('d-m-Y H:i:s')}}</p>
                        @else
                            <p class="card-text">{{ __('reactivation.not_found') }}</p>
                        @endif

                        {{--@if(!Session::has('success'))--}}
                        <button data-url="{{ route('reactiver.ad.update', ['id' => $ad->id, 'email' => $ad->user->email]) }}"
                           class="btn btn-primary mt-2" id="btn-reactivation" {{--onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"--}}>
                            {{  __('reactivation.text_btn_reactivation') }}</button>
                        {{--@else--}}
                           <a href="{{ route('login_popup') }}"
                                   class="btn btn-primary mt-2 no-display" id="btn-login">
                               {{  __('reactivation.text_btn_connecter') }}</a>
                        {{--@endif--}}

                    </div>
                </div>

                {{--<form id="logout-form" action="{{ route('reactiver.ad.post', ['id' => $ad->id, 'email' => $ad->user->email]) }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>--}}
        </div>
    </section>


@endsection




