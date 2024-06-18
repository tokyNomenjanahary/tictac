<?php
if (!isTenant()) {
    $entete = 'proprietaire.index';
    $contenue = 'contenue';
} else {
    $entete = 'espace_locataire.index';
    $contenue = 'locataire-contenue';
}
?>

@extends($entete)

@push('style')
    <style>
        #chat3 .form-control {
            border-color: transparent;
        }

        #chat3 .form-control:focus {
            border-color: transparent;
            box-shadow: inset 0px 0px 0px 1px transparent;
        }

        .badge-dot {
            border-radius: 50%;
            height: 10px;
            width: 10px;
            margin-left: 2.9rem;
            margin-top: -.75rem;
        }

        .ellipsis {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endpush

@section($contenue)
    <div class="content-wrapper">
        <section style="">
            <div class="container py-5">

                <div class="row">
                    <div class="col-md-12">

                        <div class="card" id="chat3" style="border-radius: 15px;">
                            <div class="card-body">

                                <div class="row">

                                    <div class="col">
                                        <h3>Sujet : {{ $sujet}}</h3>
                                        <div class="pt-3 pe-3 scrollable-div overflow-auto"
                                            data-mdb-perfect-scrollbar="true" style="position: relative; height: 400px" id="chat_scroll">
                                            @foreach ($getConversation as $value)

                                                @if ($value->id_user_sender != Auth::id())
                                                    <div class="d-flex flex-row justify-content-start">
                                                            <img class="user_sender_avatar w-px-40 rounded-circle " style="width: 40px; height: 45px;"
                                                                @if (!empty($value->getUserSender->user_profiles) &&
                                                                    !empty($value->getUserSender->user_profiles->profile_pic) &&
                                                                    File::exists(storage_path('uploads/profile_pics/' . $value->getUserSender->user_profiles->profile_pic))) src="{{ URL::asset('uploads/profile_pics/' . $value->getUserSender->user_profiles->profile_pic) }}" style="transform: rotate({{ $value->getUserSender->user_profiles->pdp_rotate }}deg);"
                                                                @else src="{{ URL::asset('images/profile_avatar.jpeg') }}" @endif
                                                            alt="">
                                                        <div>
                                                            <p class="small p-2 ms-3 mb-1 rounded-3"
                                                                style="background-color: #f5f6f7;">
                                                                @if (strpos($value->message, '<br>') !== false)
                                                                    {!! $value->message !!}
                                                                @else
                                                                    {{ $value->message }}
                                                                @endif

                                                            </p>

                                                            <p class="small ms-3 mb-3 rounded-3 text-muted float-end">
                                                                {{  \Carbon\Carbon::parse($value->created_at)->format('h:i A | d-m-Y') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="d-flex flex-row justify-content-end">
                                                        <div>
                                                            <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">
                                                                @if (strpos($value->message, '<br>') !== false)
                                                                    {!! $value->message !!}
                                                                @else
                                                                    {{ $value->message }}
                                                                @endif
                                                            </p>
                                                            <p class="small me-3 mb-3 rounded-3 text-muted float-end">
                                                                {{  \Carbon\Carbon::parse($value->created_at)->format('h:i A | d-m-Y') }}
                                                            </p>
                                                        </div>
                                                        <img class="w-px-40 rounded-circle" style="width: 45px; height: 45px;"
                                                        @if (!empty(Auth::user()->user_profiles) &&
                                                            !empty(Auth::user()->user_profiles->profile_pic) &&
                                                            File::exists(storage_path('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic))) src="{{ URL::asset('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic) }}" style="transform: rotate({{ Auth::user()->user_profiles->pdp_rotate }}deg);"
                                                        @else src="{{ URL::asset('images/profile_avatar.jpeg') }}" @endif
                                                        alt="">
                                                    </div>
                                                @endif
                                            @endforeach
                                            <div id="focus-bottom">

                                            </div>
                                            <div id="src_receiver" hidden>
                                                <img class="w-px-40 rounded-circle" style="{{ $style_rotate  }}" src="{{ $src_receiver }}" alt="">
                                            </div>




                                        </div>
                                        <form id="data_message" enctype="multipart/form-data">
                                            <input hidden type="text" name="espace_message_id" value="{{ $espace_message_id }}">
                                            <input hidden type="text" name="id_user_receiver" value="{{ $id_user_receiver }}">
                                            <div
                                                class="text-muted d-flex justify-content-start align-items-center pe-3 pt-3 mt-2">
                                                    <div id="profil-avatar">
                                                        <img class="w-px-40 rounded-circle" style="width: 40px; height: 45px;"
                                                            @if (!empty(Auth::user()->user_profiles) &&
                                                                !empty(Auth::user()->user_profiles->profile_pic) &&
                                                                File::exists(storage_path('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic))) src="{{ URL::asset('uploads/profile_pics/' . Auth::user()->user_profiles->profile_pic) }}" style="transform: rotate({{ Auth::user()->user_profiles->pdp_rotate }}deg);"
                                                            @else
                                                                src="{{ URL::asset('images/profile_avatar.jpeg') }}"
                                                            @endif
                                                        alt="">
                                                    </div>

                                                <input id="envoi_message" required type="text" class="form-control form-control-lg" name="message" id="exampleFormControlInput2" placeholder="Type message">
                                                <button id="submit_message" class="btn"><i class="fas fa-paper-plane"></i></button>

                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#envoi_message').ready(function() {
                    $('#submit_message').click(function(e) {
                        e.preventDefault();
                        if($("input[name=message]").val() != ''){
                            $.ajaxSetup({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });
                            $.ajax({
                                type: "POST",
                                url: '/espaceMessage/store',//{{ route('espaceMessage.store') }},
                                data: $("#data_message").serialize(),
                                dataType: 'json',
                                success: function (data) {
                                    var profil_avatar = $('#profil-avatar').html();
                                    var dateFormatted = moment(data.created_at).format('hh:mm A | DD-MM-YYYY');
                                    $('#focus-bottom').append('<div class="d-flex flex-row justify-content-end">\
                                                        <div>\
                                                            <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary">'+data.message+'</p>\
                                                            <p class="small me-3 mb-3 rounded-3 text-muted float-end">\
                                                                '+dateFormatted+'\
                                                            </p>\
                                                        </div>\
                                                        '+profil_avatar+'\
                                                    </div>');
                                    console.log()
                                    $('html, body').animate({ scrollTop: $('#focus-bottom').offset().top }, 'slow');
                                    $('#chat_scroll').scrollTop( $('#chat_scroll').prop("scrollHeight"));
                                    $("input[name=message]").val('');
                                },
                                error: function(data) {
                                    let msgs = data.responseJSON.message
                                    toastr.error(msgs)
                                }
                            });
                        }else{
                            toastr.error('Veuillez ecreire une message s\'il vous pllait!')
                        }
                        return false; // Empêche le rechargement de la page
                    });
                });

                setInterval(function() {
                    $.ajax({
                    url: '/espaceMessage/getResponseMessage/' +   {{ $espace_message_id }},
                    type: 'GET',
                    success: function(data) {
                        if(data){
                            var dateFormatted = moment(data.created_at).format('hh:mm A | DD-MM-YYYY');
                            $('#focus-bottom').append('<div class="d-flex flex-row justify-content-start">\
                                                    '+$('#src_receiver').html()+'\
                                                    <div>\
                                                        <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">'+data.message+'</p>\
                                                        <p class="small ms-3 mb-3 rounded-3 text-muted float-end">'+dateFormatted+'</p>\
                                                    </div>\
                                                </div>\
                                            ');
                            $('html, body').animate({ scrollTop: $('#focus-bottom').offset().top }, 'slow');
                            $('#chat_scroll').scrollTop( $('#chat_scroll').prop("scrollHeight"));
                        }else{
                            return false;
                        }
                    }
                    });
                }, 1000);
            });
        </script>
    @endpush
@endsection
