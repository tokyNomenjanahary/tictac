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
                        <div class="row tete mt-4">
                            <div class="col-lg-3 col-sm-4 col-12 col-md-4 titre">
                                <h3 class="page-header page-header-top m-0">Message</h3>
                            </div>
                            <div class="col-12 col-lg-5 col-sm-7 col-md-5 mb-2">

                            </div>

                            <div class="col-lg-4 col-sm-4 col-md-4 nouv text-end">
                                <div>
                                    <button type="button" class="btn btn-primary">
                                        <a href="{{ route('espaceMessage.create')}}" style="color: white;"><i class="fa fa-plus-circle"></i>Nouveau
                                            message</a>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3" id="chat3" style="border-radius: 15px;">
                            <div class="card-body">

                                <div class="row">
                                    <div class="mb-4 mb-md-0">

                                        <div class="p-3">
                                            <div class="" data-mdb-perfect-scrollbar="true">
                                                <ul class="list-unstyled mb-0">
                                                    @foreach ($discussion as $value)
                                                        <li class="p-2 border-bottom">
                                                            <a href="{{ route('espaceMessage.showConversation', $value->id) }}"
                                                                class="d-flex justify-content-between">
                                                                <div class="d-flex flex-row">
                                                                    <div>
                                                                        <img class="rounded-circle" style="width: 60px; height: 60px; margin-right: 1rem"
                                                                            @if($value->getUserReceiver->id == Auth::id())
                                                                                @if (!empty($value->getUserSender->user_profiles) &&
                                                                                    !empty($value->getUserSender->user_profiles->profile_pic) &&
                                                                                    File::exists(storage_path('uploads/profile_pics/' . $value->getUserSender->user_profiles->profile_pic))) src="{{ URL::asset('uploads/profile_pics/' . $value->getUserSender->user_profiles->profile_pic) }}" style="transform: rotate({{ $value->getUserSender->user_profiles->pdp_rotate }}deg);"
                                                                                @else
                                                                                    src="{{ URL::asset('images/profile_avatar.jpeg') }}"
                                                                                @endif
                                                                            @else
                                                                                @if (!empty($value->getUserReceiver->user_profiles) &&
                                                                                    !empty($value->getUserReceiver->user_profiles->profile_pic) &&
                                                                                    File::exists(storage_path('uploads/profile_pics/' . $value->getUserReceiver->user_profiles->profile_pic))) src="{{ URL::asset('uploads/profile_pics/' . $value->getUserReceiver->user_profiles->profile_pic) }}" style="transform: rotate({{ $value->getUserReceiver->user_profiles->pdp_rotate }}deg);"
                                                                                @else
                                                                                    src="{{ URL::asset('images/profile_avatar.jpeg') }}"
                                                                                @endif
                                                                            @endif


                                                                            alt="">
                                                                        <span class="badge bg-success badge-dot"></span>
                                                                    </div>
                                                                    <div class="pt-1">
                                                                        <p>{{ $value->getUserSender->id == Auth::id() ? $value->getUserReceiver->first_name : $value->getUserSender->first_name }}
                                                                        </p>
                                                                        @if (count($value->getLatestMessage) != 0)
                                                                            <p class="small text-muted ellipsis">
                                                                                @if (strlen($value->getLatestMessage[0]->message) > 20)
                                                                                    {!! substr($value->getLatestMessage[0]->message, 0, 20) !!} ...
                                                                                @else
                                                                                    {!! substr($value->getLatestMessage[0]->message, 0, 20) !!}
                                                                                @endif
                                                                                @if ( \Carbon\Carbon::parse($value->getLatestMessage[0]->created_at)->year == \Carbon\Carbon::now()->year && \Carbon\Carbon::parse($value->getLatestMessage[0]->created_at)->month == \Carbon\Carbon::now()->month && \Carbon\Carbon::parse($value->getLatestMessage[0]->created_at)->day == \Carbon\Carbon::now()->day)
                                                                                    {{ '  '.\Carbon\Carbon::parse($value->getLatestMessage[0]->created_at)->format('h:i A') }}
                                                                                @else
                                                                                    {{ '  '.\Carbon\Carbon::parse($value->getLatestMessage[0]->created_at)->format('d-m-Y') }}
                                                                                @endif
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="pt-1">
                                                                    <p>Sujet : {{ $value->sujet }}</p>
                                                                    <p class="small text-muted mb-1"></p>
                                                                    {{-- <span class="badge bg-danger rounded-pill float-end">3</span> --}}
                                                                </div>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
